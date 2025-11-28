<?php

namespace App\Http\Controllers\TeamLeader;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        // Get all tickets ordered by latest
        $tickets = Ticket::orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('team-leader.tickets', compact('tickets'));
    }
}
