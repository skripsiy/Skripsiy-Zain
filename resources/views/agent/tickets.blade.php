<x-agent-layout>
    <x-slot name="title">Ticket Management</x-slot>
    
    <x-slot name="sidebar">
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
    </x-slot>
    
    <x-slot name="customStyles">
        .user-avatar-header {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1F4A5E 0%, #2D6D8B 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 15px;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
        }
        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 12px;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .modal-title {
            font-size: 18px;
            font-weight: 600;
            color: #1F2A37;
            margin-bottom: 12px;
        }
        .modal-text {
            font-size: 14px;
            color: #6B7280;
            margin-bottom: 24px;
        }
        .modal-buttons {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }
        .modal-btn {
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: 0.2s;
            font-family: 'Poppins', sans-serif;
        }
        .modal-btn-break {
            background: #F59E0B;
            color: white;
        }
        .modal-btn-finish {
            background: #DC2626;
            color: white;
        }
        .modal-btn-cancel {
            background: #F3F4F6;
            color: #1F2A37;
            border: 1px solid #D1D5DB;
        }
        .modal-btn:hover {
            opacity: 0.9;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
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
        .btn {
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: 0.2s;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary {
            background: #1F4A5E;
            color: white;
        }
        .btn-secondary {
            background: #F3F4F6;
            color: #1F2A37;
            border: 1px solid #D1D5DB;
        }
        .btn:hover {
            opacity: 0.9;
        }
        .entries-control {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
            font-size: 14px;
            color: #4B5563;
        }
        .entries-select {
            padding: 8px 32px 8px 16px;
            border: 1px solid #D1D5DB;
            border-radius: 6px;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            font-weight: 500;
            color: #1F2937;
            background-color: #FFFFFF;
            width: 85px;
            height: 38px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .entries-select:focus {
            outline: none;
            border-color: #1F4A5E;
            box-shadow: 0 0 0 3px rgba(31, 74, 94, 0.15);
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
            font-size: 13px;
            min-width: 1200px;
        }
        thead {
            background: #1F4A5E;
            color: #FFFFFF;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        th {
            padding: 14px 16px;
            text-align: left;
            font-weight: 600;
            font-size: 12px;
            white-space: nowrap;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            opacity: 0.95;
        }
        td {
            padding: 14px 16px;
            border-bottom: 1px solid #F3F4F6;
            color: #374151;
        }
        tbody tr {
            transition: background-color 0.2s ease;
        }
        tbody tr:hover {
            background: #F9FAFB;
        }
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            display: inline-block;
            text-align: center;
            letter-spacing: 0.3px;
        }
        .status-queued {
            background: #FFF3E0;
            color: #F57C00;
        }
        .status-assigned {
            background: #E3F2FD;
            color: #1976D2;
        }
        .status-inprogress {
            background: #FFF9C4;
            color: #F57F17;
        }
        .status-dispatched {
            background: #E8F5E9;
            color: #2E7D32;
        }
        .status-closed {
            background: #FFEBEE;
            color: #C62828;
        }
        
        .condition-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            display: inline-block;
            text-align: center;
            letter-spacing: 0.3px;
        }
        .condition-closed {
            background: #FFEBEE;
            color: #C62828;
        }
        .condition-expired {
            background: #E0E0E0;
            color: #616161;
            font-style: italic;
        }
        .condition-progress {
            background: #FFF9C4;
            color: #F57F17;
        }
        .condition-dispatched {
            background: #E3F2FD;
            color: #0D47A1;
        }
        .condition-saltik {
            background: #F3E5F5;
            color: #7B1FA2;
        }
        .condition-open {
            background: #F3F4F6;
            color: #1F2A37;
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
        .pagination-btns button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        /* Responsive Design untuk User-Friendly */
        @media (max-width: 1400px) {
            .stats-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        
        @media (max-width: 992px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .header-actions {
                flex-direction: column;
                width: 100%;
            }
            
            .search-box {
                width: 100%;
            }
            
            .search-input {
                width: 100%;
            }
            
            table {
                font-size: 11px;
            }
            
            th, td {
                padding: 8px 6px;
            }
        }
        
        /* Prevent Horizontal Scroll */
        .content-card {
            overflow: hidden;
        }
        
        
        /* Dispatched Ticket Highlights */
        tr.highlight-dispatched td {
            background-color: rgba(16, 185, 129, 0.07) !important; /* Soft green */
        }
        
        tr.highlight-dispatched td:first-child {
            border-left: 4px solid #10b981;
            padding-left: 8px;
        }
        
        tr.highlight-dispatched:hover td {
            background-color: rgba(16, 185, 129, 0.12) !important;
        }

        /* Assigned Ticket Highlights */
        tr.highlight-assigned td {
            background-color: rgba(30, 136, 229, 0.06) !important; /* Soft blue */
        }
        
        tr.highlight-assigned td:first-child {
            border-left: 4px solid #1e88e5;
            padding-left: 8px;
        }
        
        tr.highlight-assigned:hover td {
            background-color: rgba(30, 136, 229, 0.11) !important;
        }
        
        @keyframes pulse-highlight-green {
            0% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.6);
            }
            70% {
                box-shadow: 0 0 0 6px rgba(16, 185, 129, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
            }
        }

        @keyframes pulse-highlight-blue {
            0% {
                box-shadow: 0 0 0 0 rgba(30, 136, 229, 0.6);
            }
            70% {
                box-shadow: 0 0 0 6px rgba(30, 136, 229, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(30, 136, 229, 0);
            }
        }
        
        .pulse-indicator {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 6px;
            vertical-align: middle;
        }

        .pulse-indicator.pulse-green {
            background-color: #10b981;
            animation: pulse-highlight-green 2s infinite;
        }

        .pulse-indicator.pulse-blue {
            background-color: #1e88e5;
            animation: pulse-highlight-blue 2s infinite;
        }

        /* ── Urgency Badge Tier ── */
        .urgency-badge {
            padding: 3px 9px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 700;
            display: inline-block;
            letter-spacing: 0.4px;
            white-space: nowrap;
        }
        .urgency-low       { background:#EDF2F7; color:#4A5568; }
        .urgency-emergency { background:#FFFBEA; color:#B7791F; border:1px solid #F6E05E; }
        .urgency-super     { background:#FFF5F5; color:#C53030; border:1px solid #FC8181; }
        .urgency-hvc       { background:#F5F0FF; color:#6B46C1; border:1px solid #B794F4; }
        .urgency-vvip      { background:linear-gradient(90deg,#FFF7ED,#FFFBEB); color:#92400E; border:1px solid #F6AD55; font-weight:800; }

        /* ── Division Banner ── */
        .division-banner {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .div-area     { background:#E0F2FE; color:#0369A1; }
        .div-besfixed { background:#DCFCE7; color:#15803D; }
        .div-saltik   { background:#FEF3C7; color:#92400E; }
    </x-slot>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                </svg>
            </div>
            <div class="stat-info">
                <div class="stat-label">Tickets</div>
                <div class="stat-value">{{ $totalTickets }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>
            <div class="stat-info">
                <div class="stat-label">Consumed</div>
                <div class="stat-value">{{ $consumedTickets }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                </svg>
            </div>
            <div class="stat-info">
                <div class="stat-label">Submitted</div>
                <div class="stat-value">{{ $submittedTickets }}</div>
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
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
            </div>
            <div class="stat-info">
                <div class="stat-label">AHT</div>
                <div class="stat-value">0.0</div>
            </div>
        </div>
    </div>

    <div class="content-card">
        <div class="workspace-header">
            <div style="display:flex;align-items:center;gap:12px;">
                <div class="workspace-title">Workspace</div>
                @php
                    $divClass = match($agentDiv) {
                        'area'     => 'div-area',
                        'besfixed' => 'div-besfixed',
                        'saltik'   => 'div-saltik',
                        default    => 'div-besfixed',
                    };
                @endphp
                <span class="division-banner {{ $divClass }}">{{ strtoupper($agentDiv) }}</span>
            </div>
            <div class="header-actions">
                <form method="GET" action="{{ route('agent.tickets') }}" class="search-box">
                    <input type="hidden" name="view" value="{{ $view }}">
                    <input type="text" name="search" class="search-input" placeholder="Search" value="{{ request('search') }}">
                    <button type="submit" class="search-btn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                    </button>
                </form>
                <a href="{{ route('agent.tickets', ['view' => 'active']) }}" class="btn {{ $view == 'active' ? 'btn-primary' : 'btn-secondary' }}">Active Ticket</a>
                <a href="{{ route('agent.tickets', ['view' => 'today']) }}" class="btn {{ $view == 'today' ? 'btn-primary' : 'btn-secondary' }}">Today Logs</a>
            </div>
        </div>

        <div class="entries-control">
            <span>Show</span>
            <select class="entries-select" id="entriesSelect">
                <option value="1" {{ request('per_page') == 1 ? 'selected' : '' }}>1</option>
                <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
            </select>
            <span>entries</span>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>No. Tiket</th>
                        <th>Urgency</th>
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
                    @php
                        $isDispatched = (strtolower($ticket->condition) === 'dispatched' || strtolower($ticket->status) === 'dispatched');
                        $isAssigned = (strtolower($ticket->condition) === 'assigned' || strtolower($ticket->status) === 'assigned');
                    @endphp
                    <tr ondblclick="window.location='{{ route('agent.ticket.detail', $ticket->idTicket) }}'" 
                        class="{{ $isDispatched ? 'highlight-dispatched' : ($isAssigned ? 'highlight-assigned' : '') }}" 
                        style="cursor: pointer;">
                        <td>
                            @if($isDispatched)
                                <span class="pulse-indicator pulse-green" title="Dispatched Ticket"></span>
                            @elseif($isAssigned)
                                <span class="pulse-indicator pulse-blue" title="Newly Assigned Ticket"></span>
                            @endif
                            IN{{ str_pad($ticket->idTicket, 8, '0', STR_PAD_LEFT) }}
                        </td>
                        <td>
                            <span class="urgency-badge {{ $ticket->urgency_badge_class ?? 'urgency-low' }}">
                                {{ $ticket->urgency_label ?? 'Low Emergency' }}
                            </span>
                        </td>
                        <td>{{ $ticket->datereport ? $ticket->datereport->format('Y-m-d | H:i:s') : '-' }}</td>
                        <td>
                            <span class="status-badge 
                                @if(strtolower($ticket->status) == 'queued') status-queued 
                                @elseif(strtolower($ticket->status) == 'assigned') status-assigned 
                                @elseif(strtolower($ticket->status) == 'in progress') status-inprogress 
                                @elseif(strtolower($ticket->status) == 'dispatched') status-dispatched 
                                @elseif(strtolower($ticket->status) == 'closed') status-closed 
                                @else status-queued 
                                @endif">
                                {{ strtoupper($ticket->status) }}
                            </span>
                        </td>
                        <td>{{ $ticket->regional ?? '-' }}</td>
                        <td>{{ $ticket->witel ?? '-' }}</td>
                        <td>{{ $ticket->lapul }}</td>
                        <td>{{ $ticket->gaul }}</td>
                        <td>
                            <span class="condition-badge 
                                @if($ticket->condition == 'Closed') condition-closed 
                                @elseif($ticket->condition == 'EXPIRED') condition-expired 
                                @elseif(in_array($ticket->condition, ['In Progress', 'Dispatched', 'DISPATCHED'])) condition-progress 
                                @elseif($ticket->condition == 'Saltik' || $ticket->condition == 'SALTIK') condition-saltik 
                                @else condition-open 
                                @endif">
                                @if(in_array($ticket->condition, ['Dispatched', 'DISPATCHED']))
                                    In Progress
                                @else
                                    {{ $ticket->condition ?? 'Open' }}
                                @endif
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

    <!-- Modal Konfirmasi -->
    <div class="modal" id="endModal">
        <div class="modal-content">
            <div class="modal-title">Akhiri Sesi Kerja</div>
            <div class="modal-text">Apakah Anda ingin istirahat atau selesai bekerja?</div>
            <div class="modal-buttons">
                <button class="modal-btn modal-btn-cancel" onclick="closeModal()">Batal</button>
                <button class="modal-btn modal-btn-break" onclick="endSession('break')">Istirahat</button>
                <button class="modal-btn modal-btn-finish" onclick="endSession('finish')">Selesai</button>
            </div>
        </div>
    </div>
    
    <x-slot name="additionalScripts">
        // Handle per_page change
        document.getElementById('entriesSelect').addEventListener('change', function() {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', this.value);
            url.searchParams.set('page', 1); // Reset to page 1
            window.location.href = url.toString();
        });
    </x-slot>
</x-agent-layout>
