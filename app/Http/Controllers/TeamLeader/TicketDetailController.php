<?php

namespace App\Http\Controllers\TeamLeader;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function update(Request $request, $id)
    {
        $ticket = Ticket::where('idTicket', $id)->firstOrFail();

        // Validate the request
        $validated = $request->validate([
            'resume' => 'nullable|string',
            'klasifikasi' => 'nullable|string',
            'topic' => 'nullable|string',
            'topicDetail' => 'nullable|string',
            'noSC' => 'nullable|string',
            'statusSC' => 'nullable|string',
            'validateClose' => 'nullable|string',
            'reasonnoODS' => 'nullable|string',
            'eksalasiTicket' => 'nullable|string',
            'eksalasiVia' => 'nullable|string',
            'PIC' => 'nullable|string',
            'contact' => 'nullable|string',
            'responBE' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        // Update ticket
        $ticket->update($validated);

        return redirect()->route('team-leader.ticket.detail', $id)
            ->with('success', 'Ticket updated successfully!');
    }

    public function updateStatus(Request $request, $id)
    {
        $ticket = Ticket::where('idTicket', $id)->firstOrFail();
        
        $action = $request->input('action');
        
        switch ($action) {
            case 'closed':
                $ticket->update([
                    'condition' => 'Closed',
                    'datesolved' => now(),
                    'solvedby' => Auth::user()->name
                ]);
                $message = 'Ticket has been closed successfully!';
                break;
                
            case 'expired':
                $ticket->update([
                    'condition' => 'EXPIRED'
                ]);
                $message = 'Ticket has been marked as expired!';
                break;
                
            case 'dispatch':
                $ticket->update([
                    'status' => 'DISPATCHED',
                    'condition' => 'Dispatched'
                ]);
                event(new \App\Events\TicketDispatched($ticket, auth()->user()->name));
                $message = 'Ticket has been dispatched successfully!';
                break;
                
            default:
                return redirect()->back()->with('error', 'Invalid action!');
        }
        
        return redirect()->route('team-leader.ticket.detail', $id)
            ->with('success', $message);
    }
}
