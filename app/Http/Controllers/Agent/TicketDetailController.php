<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class TicketDetailController extends Controller
{
    public function show($id)
    {
        $ticket = Ticket::findOrFail($id);
        
        // Mark notifications for this ticket as read
        if (auth()->check()) {
            auth()->user()->unreadNotifications()
                ->where(function($query) use ($id) {
                    $query->where('data->ticket_id', $id)
                          ->orWhere('data->id', $id);
                })
                ->get()
                ->markAsRead();
        }
        
        // Check if current agent is assigned to this ticket and it's not closed/dispatched/saltik
        $canEdit = ($ticket->assigned_to_user_id === auth()->id() && !in_array($ticket->condition, ['Closed', 'Dispatched', 'DISPATCHED', 'Saltik']));
        
        $activities = $ticket->activities()->latest()->get();
        
        return view('agent.ticket-detail', compact('ticket', 'canEdit', 'activities'));
    }

    public function showV2($id)
    {
        $ticket = Ticket::findOrFail($id);
        
        // Mark notifications for this ticket as read
        if (auth()->check()) {
            auth()->user()->unreadNotifications()
                ->where(function($query) use ($id) {
                    $query->where('data->ticket_id', $id)
                          ->orWhere('data->id', $id);
                })
                ->get()
                ->markAsRead();
        }
        
        // Check if current agent is assigned to this ticket and it's not closed/dispatched/saltik
        $canEdit = ($ticket->assigned_to_user_id === auth()->id() && !in_array($ticket->condition, ['Closed', 'Dispatched', 'DISPATCHED', 'Saltik']));
        
        $activities = $ticket->activities()->latest()->get();
        
        return view('agent.ticket-detail-v2', compact('ticket', 'canEdit', 'activities'));
    }

    public function update(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        
        // Prevent edit if not assigned to this agent or if ticket is closed/dispatched/saltik
        if ($ticket->assigned_to_user_id !== auth()->id() || in_array($ticket->condition, ['Closed', 'Dispatched', 'DISPATCHED', 'Saltik'])) {
            return redirect()->route('agent.ticket.detail', $id)->with('error', 'Akses ditolak: Anda tidak dapat mengedit tiket yang tidak di-assign ke Anda atau sudah ditutup/dispatched/saltik.');
        }
        
        $validated = $request->validate([
            'resume'             => 'nullable|string|max:2000',
            'klasifikasi'        => 'nullable|string|in:Technical,Non-Technical',
            'topic'              => 'nullable|string|max:255',
            'topicDetail'        => 'nullable|string|max:255',
            'noSC'               => 'nullable|string|max:100',
            'statusSC'           => 'nullable|string|max:50',
            'validateClose'      => 'nullable|string|max:255',
            'reasonnoODS'        => 'nullable|string|max:1000',
            'eksalasiTicket'     => 'nullable|string|max:255',
            'eksalasiVia'        => 'nullable|string|max:255',
            'PIC'                => [
                'nullable',
                'string',
                Rule::in(
                    strtolower($request->input('klasifikasi')) === 'technical' 
                        ? array_keys(config('teams.technical')) 
                        : array_keys(config('teams.non_technical'))
                )
            ],
            'contact'            => 'nullable|string|max:50',
            'responBE'           => 'nullable|string|max:2000',
            'description'        => 'nullable|string|max:5000',
            'resolved_by_agent'  => 'nullable|string|max:255',
            'hasil_pengecekan'   => 'nullable|string|max:5000',
            'attachment'         => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:10240',
        ], [
            'PIC.in' => 'Tim terkait yang dipilih tidak valid.'
        ]);
        
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('attachments'), $fileName);
            $validated['attachment'] = 'attachments/' . $fileName;
        }
        
        $ticket->update($validated);
        
        return redirect()->route('agent.ticket.detail', $id)->with('success', 'Ticket updated successfully!');
    }

    public function updateStatus(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        // Prevent status update if not assigned to this agent or if ticket is closed/dispatched/saltik
        if ($ticket->assigned_to_user_id !== auth()->id() || in_array($ticket->condition, ['Closed', 'Dispatched', 'DISPATCHED', 'Saltik'])) {
            return redirect()->route('agent.ticket.detail', $id)->with('error', 'Akses ditolak: Anda tidak dapat mengubah status tiket yang tidak di-assign ke Anda atau sudah ditutup/dispatched/saltik.');
        }

        // Fix S-5: Validasi 'action' dengan enum in: agar hanya nilai yang diizinkan lolos
        $request->validate([
            'action' => 'required|string|in:submit,expired,closed,saltik,dispatch',
        ]);

        $action = $request->input('action');

        // Fix R-2: Bungkus semua perubahan status dengan DB::transaction()
        // Jika ada error di tengah proses, semua perubahan otomatis di-rollback
        DB::transaction(function () use ($ticket, $action) {
            switch ($action) {
                case 'submit':
                    $ticket->update([
                        'status'    => 'In Progress',
                        'condition' => 'In Progress'
                    ]);
                    break;

                case 'expired':
                    $ticket->update([
                        'status'    => 'Closed',
                        'condition' => 'EXPIRED'
                    ]);
                    break;

                case 'closed':
                    $ticket->update([
                        'status'            => 'Closed',
                        'condition'         => 'Closed',
                        'datesolved'        => now(),
                        'solved_by_user_id' => auth()->id()
                    ]);
                    break;

                case 'saltik':
                    $ticket->update([
                        'status'            => 'Closed',
                        'condition'         => 'Saltik',
                        'datesolved'        => now(),
                        'solved_by_user_id' => auth()->id()
                    ]);
                    break;

                case 'dispatch':
                    $ticket->update([
                        'status'    => 'DISPATCHED',
                        'condition' => 'Dispatched'
                    ]);
                    break;
            }
        });

        // Event dispatch di luar transaction (boleh gagal, tidak rollback data tiket)
        if ($action === 'dispatch') {
            try {
                broadcast(new \App\Events\TicketDispatched($ticket, auth()->user()->name))->toOthers();
            } catch (\Exception $e) {
                Log::warning('TicketDispatched event failed', [
                    'ticket_id' => $ticket->idTicket,
                    'user_id'   => auth()->id(),
                    'error'     => $e->getMessage(),
                ]);
            }
        }

        return redirect()->route('agent.ticket.detail', $id)->with('success', 'Status tiket berhasil diperbarui!');
    }
}
