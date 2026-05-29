<x-agent-layout>
    <x-slot name="title">Ticket Management</x-slot>
    
    <x-slot name="headerContent">
        <div class="time-filter">
            <button onclick="window.location.href='{{ request()->fullUrlWithQuery(['time_filter' => 'all']) }}'" class="{{ $timeFilter == 'all' ? 'active' : '' }}">All Tickets</button>
            <button onclick="window.location.href='{{ request()->fullUrlWithQuery(['time_filter' => 'today']) }}'" class="{{ $timeFilter == 'today' ? 'active' : '' }}">Today</button>
            <button onclick="window.location.href='{{ request()->fullUrlWithQuery(['time_filter' => 'week']) }}'" class="{{ $timeFilter == 'week' ? 'active' : '' }}">This Week</button>
        </div>
    </x-slot>
    
    <x-slot name="sidebar">
        <a class="sidebar-icon" href="{{ route('team-leader.dashboard') }}" title="Dashboard">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
            </svg>
        </a>
        <a class="sidebar-icon active" href="{{ route('team-leader.tickets') }}" title="Tickets">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
            </svg>
        </a>
        <a class="sidebar-icon" href="{{ route('team-leader.assign') }}" title="Assign Tickets">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
            </svg>
        </a>
        <a class="sidebar-icon" href="{{ route('team-leader.profile') }}" title="My Profile">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
            </svg>
        </a>
    </x-slot>
    
    <x-slot name="customStyles">
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 12px;
            margin-bottom: 16px;
        }
        .stat-card {
            background: #2C3E50;
            border-radius: 10px;
            padding: 18px;
            display: flex;
            align-items: center;
            gap: 14px;
            color: white;
        }
        .stat-icon {
            width: 50px;
            height: 50px;
            background: rgba(255,255,255,0.15);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        .stat-info {
            flex: 1;
        }
        .stat-label {
            font-size: 13px;
            opacity: 0.9;
            margin-bottom: 4px;
        }
        .stat-value {
            font-size: 24px;
            font-weight: 700;
        }
        .content-card {
            background: #FFFFFF;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            padding: 18px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .workspace-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
        }
        .workspace-title {
            font-size: 16px;
            font-weight: 600;
            color: #1F2A37;
        }
        .header-actions {
            display: flex;
            gap: 8px;
        }
        .search-box {
            display: flex;
            gap: 8px;
            align-items: center;
        }
        .search-input {
            padding: 7px 12px;
            border: 1px solid #D1D5DB;
            border-radius: 6px;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            width: 250px;
            background: #F3F4F6;
        }
        .search-btn {
            padding: 7px 14px;
            background: #1F4A5E;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
        }
        .table-wrapper {
            flex: 1;
            overflow: auto;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            min-width: 1400px;
        }
        thead {
            background: #1F4A5E;
            color: #FFFFFF;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        th {
            padding: 10px 12px;
            text-align: left;
            font-weight: 600;
            font-size: 11px;
            white-space: nowrap;
        }
        td {
            padding: 10px 12px;
            border-bottom: 1px solid #F3F4F6;
        }
        tbody tr:hover {
            background: #F9FAFB;
        }
        .status-badge {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
            display: inline-block;
        }
        .status-queued {
            background: #FFF3E0;
            color: #F57C00;
        }
        .status-assigned {
            background: #E3F2FD;
            color: #1976D2;
        }
        .status-closed {
            background: #FFEBEE;
            color: #C62828;
        }
        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 12px;
            font-size: 13px;
        }
        .pagination-info {
            color: #6B7280;
        }
        .pagination-btns {
            display: flex;
            gap: 6px;
        }
        .pagination-btns button {
            padding: 6px 12px;
            border: 1px solid #D1D5DB;
            background: #FFFFFF;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }
        .pagination-btns button.active {
            background: #1F4A5E;
            color: white;
            border-color: #1F4A5E;
        }
        .pagination-btns button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
    </x-slot>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                </svg>
            </div>
            <div class="stat-info">
                <div class="stat-label">Total Tickets</div>
                <div class="stat-value">{{ $totalTickets }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
            </div>
            <div class="stat-info">
                <div class="stat-label">Queued</div>
                <div class="stat-value">{{ $queuedTickets }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>
            <div class="stat-info">
                <div class="stat-label">Assigned</div>
                <div class="stat-value">{{ $assignedTickets }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
            </div>
            <div class="stat-info">
                <div class="stat-label">Dispatched</div>
                <div class="stat-value">{{ $dispatchedTickets }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
            </div>
            <div class="stat-info">
                <div class="stat-label">Closed</div>
                <div class="stat-value">{{ $closedTickets }}</div>
            </div>
        </div>
    </div>

    <div class="content-card">
        <div class="workspace-header">
            <div class="workspace-title">All Tickets</div>
            <div class="header-actions">
                <form method="GET" action="{{ route('team-leader.tickets') }}" class="search-box">
                    <input type="text" name="search" class="search-input" placeholder="Search tickets..." value="{{ request('search') }}">
                    <button type="submit" class="search-btn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Ticket Code</th>
                        <th>Customer</th>
                        <th>Priority</th>
                        <th>Date Report</th>
                        <th>Status</th>
                        <th>Assigned To</th>
                        <th>Regional</th>
                        <th>Witel</th>
                        <th>Condition</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                    <tr ondblclick="window.location='{{ route('team-leader.ticket.detail', $ticket->idTicket) }}'" style="cursor: pointer;">
                        <td><strong>TK{{ str_pad($ticket->idTicket, 6, '0', STR_PAD_LEFT) }}</strong></td>
                        <td>{{ $ticket->namacust ?? '-' }}</td>
                        <td>{{ $ticket->reportedpriority ?? '-' }}</td>
                        <td>{{ $ticket->datereport ? \Carbon\Carbon::parse($ticket->datereport)->format('Y-m-d H:i') : '-' }}</td>
                        <td>
                            @if($ticket->status == 'QUEUED')
                                <span class="status-badge status-queued">QUEUED</span>
                            @elseif($ticket->status == 'ASSIGNED')
                                <span class="status-badge status-assigned">ASSIGNED</span>
                            @elseif($ticket->status == 'DISPATCHED')
                                <span class="status-badge" style="background: #FFE0B2; color: #E65100;">DISPATCHED</span>
                            @else
                                <span class="status-badge">{{ $ticket->status }}</span>
                            @endif
                        </td>
                        <td>{{ $ticket->assignby ?? '-' }}</td>
                        <td>{{ $ticket->regional ?? '-' }}</td>
                        <td>{{ $ticket->witel ?? '-' }}</td>
                        <td>
                            @if($ticket->condition == 'Closed')
                                <span class="status-badge status-closed">Closed</span>
                            @elseif($ticket->condition == 'Dispatched')
                                <span class="status-badge" style="background: #E3F2FD; color: #0D47A1;">Dispatched</span>
                            @else
                                {{ $ticket->condition ?? 'Open' }}
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 30px; color: #9CA3AF;">No tickets found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination">
            <div class="pagination-info">
                Showing {{ $tickets->firstItem() ?? 0 }} to {{ $tickets->lastItem() ?? 0 }} of {{ $tickets->total() }} entries
            </div>
            <div class="pagination-btns">
                <button {{ $tickets->onFirstPage() ? 'disabled' : '' }} onclick="window.location='{{ $tickets->previousPageUrl() }}'">Previous</button>
                @foreach($tickets->getUrlRange(1, min($tickets->lastPage(), 5)) as $page => $url)
                    <button class="{{ $page == $tickets->currentPage() ? 'active' : '' }}" onclick="window.location='{{ $url }}'">{{ $page }}</button>
                @endforeach
                <button {{ !$tickets->hasMorePages() ? 'disabled' : '' }} onclick="window.location='{{ $tickets->nextPageUrl() }}'">Next</button>
            </div>
        </div>
    </div>
    
    <x-slot name="additionalScripts">
        // Time filter functionality is now handled server-side
    </x-slot>
</x-agent-layout>
