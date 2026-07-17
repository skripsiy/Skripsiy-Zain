<?php

namespace App\Http\Controllers\TeamLeader;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketEscalation;
use App\Http\Requests\TeamLeader\DispatchTicketRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TicketDetailController extends Controller
{
    public function show($id)
    {
        $ticket = Ticket::where('idTicket', $id)->firstOrFail();
        $activities = \Spatie\Activitylog\Models\Activity::where('subject_type', 'App\Models\Ticket')
            ->where('subject_id', $ticket->idTicket)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('team-leader.ticket-detail', compact('ticket', 'activities'));
    }

    public function update(DispatchTicketRequest $request, $id)
    {
        $ticket = Ticket::where('idTicket', $id)->firstOrFail();
        $validated = $request->validated();

        DB::transaction(function () use ($ticket, $validated, $request) {
            // a. Buat record eskalasi baru
            TicketEscalation::create([
                'ticket_id'     => $ticket->idTicket,
                'escalated_to'  => $validated['PIC'],
                'escalated_via' => $validated['eksalasiVia'],
                'contact'       => $validated['contact'],
                'respon_be'     => $validated['responBE'],
                'status'        => 'Dispatched',
            ]);

            // b. Update field TICKET-LEVEL saja pada $ticket + transisi status
            $ticket->fill([
                'resume'        => $validated['resume'],
                'klasifikasi'   => $validated['klasifikasi'],
                'topic'         => $validated['topic'],
                'topicDetail'   => $validated['topicDetail'],
                'noSC'          => $validated['noSC'],
                'statusSC'      => $validated['statusSC'] ?? null,
                'validateClose' => $validated['validateClose'],
                'reasonnoODS'   => $validated['reasonnoODS'],
                'description'   => $validated['description'],
                'status'        => 'DISPATCHED',
                'condition'     => 'Dispatched',
            ]);

            // c. Attachment (opsional) - simpan aman via Storage disk 'public'
            if ($request->hasFile('attachment')) {
                $f = $request->file('attachment');
                $name = 'ticket_' . $ticket->idTicket . '_' . time() . '.' . $f->getClientOriginalExtension();
                $ticket->attachment = $f->storeAs('attachments', $name, 'public');
            }

            $ticket->save();
        });

        // Setelah transaksi: dispatch App\Events\TicketDispatched bila ada
        try {
            if (class_exists(\App\Events\TicketDispatched::class)) {
                broadcast(new \App\Events\TicketDispatched($ticket, auth()->user()->name))->toOthers();
            }
        } catch (\Exception $e) {
            Log::warning('Failed to dispatch TicketDispatched event: ' . $e->getMessage());
        }

        return redirect()->route('team-leader.ticket.detail', $id)
            ->with('success', 'Tiket berhasil di-dispatch ke tim terkait.');
    }

    public function updateStatus(Request $request, $id)
    {
        $ticket = Ticket::where('idTicket', $id)->firstOrFail();
        
        $action = $request->input('action');
        
        switch ($action) {
            case 'closed':
                $ticket->update([
                    'status' => 'Closed',
                    'condition' => 'Closed',
                    'datesolved' => now(),
                    'solved_by_user_id' => Auth::id()
                ]);
                $message = 'Ticket has been closed successfully!';
                break;
                
            case 'expired':
                $ticket->update([
                    'status' => 'Closed',
                    'condition' => 'EXPIRED'
                ]);
                $message = 'Ticket has been marked as expired!';
                break;
                
            case 'saltik':
                $ticket->update([
                    'status' => 'Closed',
                    'condition' => 'Saltik',
                    'datesolved' => now(),
                    'solved_by_user_id' => Auth::id()
                ]);
                $message = 'Ticket has been marked as SALTIK and closed!';
                break;
                
            case 'dispatch':
                $ticket->update([
                    'status' => 'DISPATCHED',
                    'condition' => 'Dispatched'
                ]);
                broadcast(new \App\Events\TicketDispatched($ticket, auth()->user()->name))->toOthers();
                $message = 'Ticket has been dispatched successfully!';
                break;
                
            default:
                return redirect()->back()->with('error', 'Invalid action!');
        }
        
        return redirect()->route('team-leader.ticket.detail', $id)
            ->with('success', $message);
    }
}
