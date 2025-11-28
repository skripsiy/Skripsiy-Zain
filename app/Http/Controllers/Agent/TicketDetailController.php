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
        
        // Check if current agent is assigned to this ticket
        $canEdit = ($ticket->assignby === auth()->user()->name);
        
        return view('agent.ticket-detail', compact('ticket', 'canEdit'));
    }

    public function update(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        
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
        
        $ticket->update($validated);
        
        return redirect()->route('agent.ticket.detail', $id)->with('success', 'Ticket updated successfully!');
    }

    public function updateStatus(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
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
                $ticket->update(['condition' => 'Closed', 'datesolved' => now()]);
                event(new \App\Events\TicketDispatched($ticket, auth()->user()->name));
                break;
        }
        
        return redirect()->route('agent.ticket.detail', $id)->with('success', 'Ticket status updated!');
    }
}
