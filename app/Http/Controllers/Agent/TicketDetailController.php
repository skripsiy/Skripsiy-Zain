<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketDetailController extends Controller
{
    public function show($id)
    {
        $ticket = Ticket::findOrFail($id);
        
        // Check if current agent is assigned to this ticket and it's not closed/dispatched
        $canEdit = ($ticket->assignby === auth()->user()->name && !in_array($ticket->condition, ['Closed', 'Dispatched', 'DISPATCHED']));
        
        $activities = $ticket->activities()->latest()->get();
        
        return view('agent.ticket-detail', compact('ticket', 'canEdit', 'activities'));
    }

    public function update(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        
        // Prevent edit if not assigned to this agent or if ticket is closed/dispatched
        if ($ticket->assignby !== auth()->user()->name || in_array($ticket->condition, ['Closed', 'Dispatched', 'DISPATCHED'])) {
            return redirect()->route('agent.ticket.detail', $id)->with('error', 'Akses ditolak: Anda tidak dapat mengedit tiket yang tidak di-assign ke Anda atau sudah ditutup/dispatched.');
        }
        
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
            'resolved_by_agent' => 'nullable|string',
            'hasil_pengecekan' => 'nullable|string',
        ]);
        
        $ticket->update($validated);
        
        return redirect()->route('agent.ticket.detail', $id)->with('success', 'Ticket updated successfully!');
    }

    public function updateStatus(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        
        // Prevent status update if not assigned to this agent or if ticket is closed/dispatched
        if ($ticket->assignby !== auth()->user()->name || in_array($ticket->condition, ['Closed', 'Dispatched', 'DISPATCHED'])) {
            return redirect()->route('agent.ticket.detail', $id)->with('error', 'Akses ditolak: Anda tidak dapat mengubah status tiket yang tidak di-assign ke Anda atau sudah ditutup/dispatched.');
        }

        $action = $request->input('action');
        
        switch ($action) {
            case 'submit':
                $ticket->update(['condition' => 'In Progress']);
                break;
            case 'expired':
                $ticket->update(['condition' => 'EXPIRED']);
                break;
            case 'closed':
                $ticket->update(['condition' => 'Closed', 'datesolved' => now()]);
                break;
            case 'dispatch':
                $ticket->update([
                    'status' => 'DISPATCHED',
                    'condition' => 'Dispatched'
                ]);
                event(new \App\Events\TicketDispatched($ticket, auth()->user()->name));
                break;
        }
        
        return redirect()->route('agent.ticket.detail', $id)->with('success', 'Ticket status updated!');
    }
}
