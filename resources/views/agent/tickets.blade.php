<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XENA - Ticket Management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: #F5F5F5; min-height: 100vh; }
        .header { background: #FFFFFF; padding: 16px 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.08); display: flex; align-items: center; justify-content: space-between; }
        .logo { font-size: 22px; font-weight: 700; background: linear-gradient(90deg,#0C1D25 0%,#1F4A5E 56%,#2D6D8B 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .header-right { display: flex; gap: 15px; align-items: center; }
        .dark-mode-toggle { width: 50px; height: 26px; background: #E5E7EB; border-radius: 13px; position: relative; cursor: pointer; transition: 0.3s; }
        .dark-mode-toggle::before { content: ''; position: absolute; width: 20px; height: 20px; border-radius: 50%; background: white; top: 3px; left: 3px; transition: 0.3s; }
        .dark-mode-toggle.active { background: #DC2626; }
        .dark-mode-toggle.active::before { left: 27px; }
        .user-avatar { width: 38px; height: 38px; border-radius: 50%; background: linear-gradient(135deg,#1F4A5E 0%,#2D6D8B 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 15px; }
        .container { display: flex; height: calc(100vh - 70px); overflow: hidden; }
        .sidebar { width: 50px; background: #FFFFFF; padding: 15px 0; display: flex; flex-direction: column; align-items: center; gap: 25px; box-shadow: 2px 0 4px rgba(0,0,0,0.05); }
        .sidebar-icon { width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #666; transition: all 0.2s; text-decoration: none; }
        .sidebar-icon:hover, .sidebar-icon.active { color: #1F4A5E; }
        .main-content { flex: 1; padding: 15px 20px; display: flex; flex-direction: column; overflow-y: auto; background: #F5F7FA; }
        .stats-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 12px; margin-bottom: 16px; }
        .stat-card { background: #2C3E50; border-radius: 10px; padding: 18px; display: flex; align-items: center; gap: 14px; color: white; }
        .stat-icon { width: 50px; height: 50px; background: rgba(255,255,255,0.15); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 24px; }
        .stat-info { flex: 1; }
        .stat-label { font-size: 13px; opacity: 0.9; margin-bottom: 4px; }
        .stat-value { font-size: 24px; font-weight: 700; }
        .content-card { background: #FFFFFF; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); padding: 18px; flex: 1; display: flex; flex-direction: column; }
        .workspace-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; }
        .workspace-title { font-size: 16px; font-weight: 600; color: #1F2A37; }
        .header-actions { display: flex; gap: 8px; }
        .search-box { display: flex; gap: 8px; align-items: center; }
        .search-input { padding: 7px 12px; border: 1px solid #D1D5DB; border-radius: 6px; font-family: 'Poppins', sans-serif; font-size: 13px; width: 250px; background: #F3F4F6; }
        .search-btn { padding: 7px 14px; background: #1F4A5E; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; }
        .btn { padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 500; cursor: pointer; border: none; transition: 0.2s; }
        .btn-primary { background: #1F4A5E; color: white; }
        .btn-secondary { background: #F3F4F6; color: #1F2A37; border: 1px solid #D1D5DB; }
        .btn:hover { opacity: 0.9; }
        .entries-control { display: flex; align-items: center; gap: 8px; margin-bottom: 12px; font-size: 13px; color: #6B7280; }
        .entries-select { padding: 4px 8px; border: 1px solid #D1D5DB; border-radius: 4px; font-family: 'Poppins', sans-serif; }
        .table-wrapper { flex: 1; overflow: auto; border: 1px solid #E5E7EB; border-radius: 8px; }
        table { width: 100%; border-collapse: collapse; font-size: 12px; min-width: 1400px; }
        thead { background: #1F4A5E; color: #FFFFFF; position: sticky; top: 0; z-index: 10; }
        th { padding: 10px 12px; text-align: left; font-weight: 600; font-size: 11px; white-space: nowrap; }
        td { padding: 10px 12px; border-bottom: 1px solid #F3F4F6; }
        tbody tr:hover { background: #F9FAFB; }
        .status-queued { color: #6B7280; font-weight: 600; }
        .escalation-assigned { color: #DC2626; font-weight: 600; }
        .escalation-pickup { color: #F59E0B; font-weight: 600; }
        .condition-closed { color: #DC2626; font-weight: 600; }
        .condition-expired { color: #DC2626; font-weight: 600; font-style: italic; }
        .condition-progress { color: #F59E0B; font-weight: 600; font-style: italic; }
        .condition-open { color: #6B7280; font-weight: 600; }
        .pagination { display: flex; justify-content: space-between; align-items: center; margin-top: 12px; font-size: 13px; }
        .pagination-info { color: #6B7280; }
        .pagination-btns { display: flex; gap: 6px; }
        .pagination-btns button { padding: 6px 12px; border: 1px solid #D1D5DB; background: #FFFFFF; border-radius: 4px; cursor: pointer; font-size: 12px; }
        .pagination-btns button.active { background: #1F4A5E; color: white; border-color: #1F4A5E; }
        .pagination-btns button:disabled { opacity: 0.5; cursor: not-allowed; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">XENA</div>
        <div class="header-right">
            <div class="dark-mode-toggle" onclick="this.classList.toggle('active')"></div>
            <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
        </div>
    </div>

    <div class="container">
        <div class="sidebar">
            <a class="sidebar-icon" href="{{ route('agent.dashboard') }}" title="Dashboard">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                </svg>
            </a>
            <a class="sidebar-icon active" href="{{ route('agent.tickets') }}" title="Tickets">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                </svg>
            </a>
            <a class="sidebar-icon" href="{{ route('agent.profile') }}" title="My Profile">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
            </a>
        </div>

        <div class="main-content">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">🗄️</div>
                    <div class="stat-info">
                        <div class="stat-label">Tickets</div>
                        <div class="stat-value">{{ $totalTickets }}</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">✅</div>
                    <div class="stat-info">
                        <div class="stat-label">Consumed</div>
                        <div class="stat-value">{{ $consumedTickets }}</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">📄</div>
                    <div class="stat-info">
                        <div class="stat-label">Submitted</div>
                        <div class="stat-value">{{ $submittedTickets }}</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">🔒</div>
                    <div class="stat-info">
                        <div class="stat-label">Closed</div>
                        <div class="stat-value">{{ $closedTickets }}</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">👤</div>
                    <div class="stat-info">
                        <div class="stat-label">AHT</div>
                        <div class="stat-value">0.0</div>
                    </div>
                </div>
            </div>

            <div class="content-card">
                <div class="workspace-header">
                    <div class="workspace-title">Workspace</div>
                    <div class="header-actions">
                        <form method="GET" action="{{ route('agent.tickets') }}" class="search-box">
                            <input type="hidden" name="view" value="{{ $view }}">
                            <input type="text" name="search" class="search-input" placeholder="Search" value="{{ request('search') }}">
                            <button type="submit" class="search-btn">🔍</button>
                        </form>
                        <a href="{{ route('agent.tickets', ['view' => 'active']) }}" class="btn {{ $view == 'active' ? 'btn-primary' : 'btn-secondary' }}">Active Ticket</a>
                        <a href="{{ route('agent.tickets', ['view' => 'today']) }}" class="btn {{ $view == 'today' ? 'btn-primary' : 'btn-secondary' }}">Today Logs</a>
                    </div>
                </div>

                <div class="entries-control">
                    <span>Show</span>
                    <select class="entries-select">
                        <option>1</option>
                        <option selected>10</option>
                        <option>25</option>
                        <option>50</option>
                    </select>
                    <span>entries</span>
                </div>

                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>No. Tiket</th>
                                <th>Reported Priority</th>
                                <th>Tanggal Open</th>
                                <th>Status</th>
                                <th>Regional</th>
                                <th>Witel</th>
                                <th>Lapul</th>
                                <th>Gaul</th>
                                <th>Condition</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tickets as $ticket)
                            <tr ondblclick="window.location='{{ route('agent.ticket.detail', $ticket->idTicket) }}'" style="cursor: pointer;">
                                <td>IN{{ str_pad($ticket->idTicket, 8, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $ticket->reportedpriority ?? '-' }}</td>
                                <td>{{ $ticket->datereport ? $ticket->datereport->format('Y-m-d | H:i:s') : '-' }}</td>
                                <td><span class="status-queued">{{ $ticket->status }}</span></td>
                                <td>{{ $ticket->regional ?? '-' }}</td>
                                <td>{{ $ticket->witel ?? '-' }}</td>
                                <td>{{ $ticket->lapul }}</td>
                                <td>{{ $ticket->gaul }}</td>
                                <td>
                                    <span class="@if($ticket->condition == 'Closed') condition-closed @elseif($ticket->condition == 'EXPIRED') condition-expired @elseif($ticket->condition == 'In Progress') condition-progress @else condition-open @endif">
                                        {{ $ticket->condition ?? 'Open' }}
                                    </span>
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
                        @foreach($tickets->getUrlRange(1, $tickets->lastPage()) as $page => $url)
                            <button class="{{ $page == $tickets->currentPage() ? 'active' : '' }}" onclick="window.location='{{ $url }}'">{{ $page }}</button>
                        @endforeach
                        <button {{ !$tickets->hasMorePages() ? 'disabled' : '' }} onclick="window.location='{{ $tickets->nextPageUrl() }}'">Next</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
