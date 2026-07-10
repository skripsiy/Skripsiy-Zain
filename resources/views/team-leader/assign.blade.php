<x-agent-layout>
    <x-slot name="title">Assign Tickets</x-slot>
    

    
    <x-slot name="sidebar">
        <a class="sidebar-icon" href="{{ route('team-leader.dashboard') }}" title="Dashboard">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
            </svg>
        </a>
        <a class="sidebar-icon" href="{{ route('team-leader.tickets') }}" title="Tickets">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
            </svg>
        </a>
        <a class="sidebar-icon active" href="{{ route('team-leader.assign') }}" title="Assign Tickets">
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
        .page-header {
            margin-bottom: 20px;
        }
        
        .page-title {
            font-size: 24px;
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 8px;
        }
        
        .page-subtitle {
            font-size: 14px;
            color: #666;
        }
        
        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }
        
        .stat-label {
            font-size: 12px;
            color: #666;
            margin-bottom: 8px;
        }
        
        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #1a202c;
        }
        
        .stat-value.blue { color: #4A90E2; }
        .stat-value.green { color: #7ED321; }
        .stat-value.orange { color: #FF9800; }
        
        .tickets-section {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .filter-toolbar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
            margin-top: 16px;
            padding: 12px 16px;
            background: #FFFFFF;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .filter-label {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 11px;
            font-weight: 600;
            color: #6B7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }

        .filter-label svg {
            color: #9CA3AF;
            flex-shrink: 0;
        }

        .filter-divider {
            width: 1px;
            height: 28px;
            background: #E5E7EB;
            margin: 0 4px;
        }

        .date-input {
            padding: 6px 10px;
            border: 1px solid #D1D5DB;
            border-radius: 6px;
            font-size: 12px;
            font-family: 'Poppins', sans-serif;
            color: #374151;
            background: #F9FAFB;
            cursor: pointer;
            transition: border-color 0.2s, background 0.2s;
            height: 32px;
        }

        .date-input:focus {
            outline: none;
            border-color: #1F4A5E;
            background: #fff;
        }

        .date-reset-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            padding: 0;
            background: #F3F4F6;
            border: 1px solid #E5E7EB;
            border-radius: 6px;
            color: #9CA3AF;
            cursor: pointer;
            transition: all 0.2s;
            flex-shrink: 0;
        }

        .date-reset-btn:hover {
            background: #FEE2E2;
            border-color: #FCA5A5;
            color: #DC2626;
        }

        .status-filters {
            display: flex;
            gap: 4px;
            background: #F3F4F6;
            border-radius: 7px;
            padding: 3px;
        }

        .status-filter-btn {
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 5px 14px;
            border: none;
            border-radius: 5px;
            font-size: 12px;
            font-weight: 500;
            font-family: 'Poppins', sans-serif;
            background: transparent;
            color: #6B7280;
            cursor: pointer;
            transition: all 0.18s;
            white-space: nowrap;
        }

        .status-filter-btn:hover {
            color: #1F4A5E;
            background: rgba(255,255,255,0.7);
        }

        .status-filter-btn.active {
            background: #fff;
            color: #1F4A5E;
            font-weight: 600;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .status-filter-btn.active-assigned {
            background: #fff;
            color: #2E7D32;
            font-weight: 600;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .status-filter-btn.active-unassigned {
            background: #fff;
            color: #C05621;
            font-weight: 600;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .filter-results {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
            color: #9CA3AF;
        }

        .filter-results .result-count {
            font-weight: 700;
            color: #1F4A5E;
            font-size: 13px;
        }

        /* ── Date / Urgency column ────────────────────────────────────── */
        .date-cell {
            white-space: nowrap;
            min-width: 130px;
        }

        .date-entry {
            display: flex;
            flex-direction: column;
            gap: 1px;
            margin-bottom: 5px;
        }

        .date-text {
            font-size: 12px;
            font-weight: 600;
            color: #1F2A37;
        }

        .date-time {
            font-size: 11px;
            color: #9CA3AF;
        }

        .age-pill {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .age-today {
            background: #DCFCE7;
            color: #166534;
        }

        .age-warn {
            background: #FEF3C7;
            color: #92400E;
        }

        .age-critical {
            background: #FEE2E2;
            color: #991B1B;
            animation: pulse-red 2s ease-in-out infinite;
        }

        @keyframes pulse-red {
            0%, 100% { opacity: 1; }
            50%       { opacity: 0.65; }
        }
        

        /* ── Searchable Agent Dropdown ─────────────────────────────────── */
        .agent-search-wrap {
            position: relative;
            min-width: 180px;
        }

        .agent-search-input {
            width: 100%;
            padding: 6px 30px 6px 10px;
            border: 1px solid #D1D5DB;
            border-radius: 6px;
            font-size: 12px;
            font-family: 'Poppins', sans-serif;
            color: #374151;
            background: #F9FAFB;
            cursor: text;
            transition: border-color 0.2s, background 0.2s;
            box-sizing: border-box;
        }

        .agent-search-input:focus {
            outline: none;
            border-color: #1F4A5E;
            background: #fff;
        }

        .agent-search-input.has-value {
            background: #EFF6FF;
            border-color: #3B82F6;
            color: #1D4ED8;
            font-weight: 600;
        }

        .agent-caret {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: #9CA3AF;
        }

        .agent-dropdown {
            display: none;
            position: absolute;
            top: calc(100% + 4px);
            left: 0;
            right: 0;
            background: #fff;
            border: 1px solid #D1D5DB;
            border-radius: 8px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
            z-index: 9999;
            max-height: 200px;
            overflow-y: auto;
            min-width: 200px;
        }

        .agent-dropdown.open {
            display: block;
        }

        .agent-option {
            padding: 8px 12px;
            font-size: 12px;
            color: #374151;
            cursor: pointer;
            transition: background 0.15s;
            border-bottom: 1px solid #F3F4F6;
        }

        .agent-option:last-child {
            border-bottom: none;
        }

        .agent-option:hover,
        .agent-option.focused {
            background: #EFF6FF;
            color: #1D4ED8;
        }

        .agent-option.selected {
            background: #DBEAFE;
            color: #1D4ED8;
            font-weight: 600;
        }

        .agent-no-results {
            padding: 10px 12px;
            font-size: 12px;
            color: #9CA3AF;
            text-align: center;
        }

        
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #1a202c;
        }
        
        .search-box {
            display: flex;
            gap: 10px;
        }
        
        .search-input {
            padding: 8px 15px;
            border: 1px solid #E0E0E0;
            border-radius: 6px;
            font-size: 13px;
            font-family: 'Poppins', sans-serif;
            width: 300px;
        }
        
        .search-input:focus {
            outline: none;
            border-color: #1F4A5E;
        }
        
        .table-wrapper {
            overflow-x: auto;
            border-radius: 8px;
            border: 1px solid #E0E0E0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        
        thead {
            background: #1F4A5E;
            color: white;
        }
        
        th {
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        td {
            padding: 15px;
            border-bottom: 1px solid #F0F0F0;
        }
        
        tbody tr:hover {
            background: #F9F9F9;
        }
        
        tbody tr:last-child td {
            border-bottom: none;
        }
        
        .status-badge {
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            display: inline-block;
        }
        
        .status-unassigned {
            background: #FFF3E0;
            color: #F57C00;
        }
        
        .status-pending {
            background: #FFF9C4;
            color: #F57F17;
        }
        
        .status-assigned {
            background: #E8F5E9;
            color: #2E7D32;
        }
        
        .agent-select {
            padding: 6px 12px;
            border: 1px solid #E0E0E0;
            border-radius: 6px;
            font-size: 12px;
            font-family: 'Poppins', sans-serif;
            min-width: 150px;
        }
        
        .agent-select:focus {
            outline: none;
            border-color: #1F4A5E;
        }
        
        .assign-btn {
            padding: 6px 16px;
            background: #1F4A5E;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .assign-btn:hover {
            background: #2D6D8B;
        }
        
        .assign-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
        
        /* Toast Notification */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }
        
        .toast {
            background: white;
            padding: 16px 24px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 300px;
            animation: slideIn 0.3s ease-out;
            border-left: 4px solid #4CAF50;
        }
        
        .toast.success {
            border-left-color: #4CAF50;
        }
        
        .toast-icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #4CAF50;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            flex-shrink: 0;
        }
        
        .toast-content {
            flex: 1;
        }
        
        .toast-title {
            font-weight: 600;
            color: #2E7D32;
            margin-bottom: 4px;
            font-size: 14px;
        }
        
        .toast-message {
            color: #666;
            font-size: 13px;
        }
        
        .toast-close {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #f5f5f5;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
            font-size: 16px;
            transition: all 0.2s;
            flex-shrink: 0;
        }
        
        .toast-close:hover {
            background: #e0e0e0;
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }
        
        .toast.hiding {
            animation: slideOut 0.3s ease-out forwards;
        }
    </x-slot>
    
    <!-- Page Header -->
    <div class="page-header">
        <div style="display:flex;align-items:center;gap:14px;flex-wrap:wrap;">
            <div>
                <h1 class="page-title">Loker Dispatch Tiket Prioritas</h1>
                <p class="page-subtitle">Tiket Super Emergency / HVC / VVIP-Management menunggu di-dispatch ke agent</p>
            </div>
            @if($tlDiv)
            @php
                $tlDivClass = match($tlDiv) {
                    'area'     => 'background:#E0F2FE;color:#0369A1',
                    'besfixed' => 'background:#DCFCE7;color:#15803D',
                    'saltik'   => 'background:#FEF3C7;color:#92400E',
                    default    => 'background:#F3F4F6;color:#374151',
                };
            @endphp
            <span style="{{ $tlDivClass }};padding:5px 16px;border-radius:20px;font-size:12px;font-weight:700;letter-spacing:0.5px;text-transform:uppercase;">
                Divisi {{ strtoupper($tlDiv) }}
            </span>
            @endif
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="stats-cards">
        <div class="stat-card">
            <div class="stat-label">Total Prioritas Tinggi</div>
            <div class="stat-value">{{ $stats['dispatch_count'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label" style="display: flex; align-items: center; gap: 6px;">
                <span style="background:#92400E; width:8px; height:8px; border-radius:50%; display:inline-block;"></span>
                VVIP/Management
            </div>
            <div class="stat-value" style="color:#92400E;">{{ $stats['vvip_count'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label" style="display: flex; align-items: center; gap: 6px;">
                <span style="background:#6B46C1; width:8px; height:8px; border-radius:50%; display:inline-block;"></span>
                HVC
            </div>
            <div class="stat-value" style="color:#6B46C1;">{{ $stats['hvc_count'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label" style="display: flex; align-items: center; gap: 6px;">
                <span style="background:#C53030; width:8px; height:8px; border-radius:50%; display:inline-block;"></span>
                Super Emergency
            </div>
            <div class="stat-value" style="color:#C53030;">{{ $stats['se_count'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Agent Online</div>
            <div class="stat-value green">{{ $stats['agents_online'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Total Agent Divisi</div>
            <div class="stat-value blue">{{ $agents->count() }}</div>
        </div>
    </div>
    
    <!-- Toast Notification Container -->
    <div class="toast-container" id="toastContainer"></div>
    
    <!-- Tickets Section -->
    <div class="tickets-section">
        <div class="section-header">
            <h2 class="section-title">Super Emergency Tickets List</h2>
            <div class="search-box">
                <input type="text" class="search-input" placeholder="Search by ticket code or customer..." id="searchInput">
            </div>
        </div>

        <!-- Filter Toolbar -->
        <div class="filter-toolbar">
            <!-- Date Filter -->
            <div class="filter-group">
                <span class="filter-label">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    Date
                </span>
                <input type="date" class="date-input" id="dateFilter" title="Filter by report date">
                <button class="date-reset-btn" id="dateResetBtn" title="Clear date">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>

            <div class="filter-divider"></div>

            <!-- Assignment Status Filter -->
            <div class="filter-group">
                <span class="filter-label">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    Status
                </span>
                <div class="status-filters">
                    <button class="status-filter-btn active" id="filterAll" data-filter="all">All</button>
                    <button class="status-filter-btn" id="filterAssigned" data-filter="assigned">Assigned</button>
                    <button class="status-filter-btn" id="filterUnassigned" data-filter="unassigned">Unassigned</button>
                </div>
            </div>

            <!-- Result count -->
            <div class="filter-results">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <span class="result-count" id="visibleCount">{{ $allTickets->count() }}</span> of {{ $allTickets->count() }} tickets
            </div>
        </div>
        
        <div class="table-wrapper">
            <table id="ticketsTable">
                <thead>
                    <tr>
                        <th>Ticket Code</th>
                        <th>Customer</th>
                        <th>Priority</th>
                        <th>Issue</th>
                        <th>Masuk</th>
                        <th>Status</th>
                        <th>Current Agent</th>
                        <th>Assign To</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($allTickets as $ticket)
                        <tr
                            data-date="{{ ($ticket->datereport ?? $ticket->created_at) ? \Illuminate\Support\Carbon::parse($ticket->datereport ?? $ticket->created_at)->format('Y-m-d') : '' }}"
                            data-assigned="{{ ($ticket->assigned_to_user_id || in_array($ticket->status, ['ASSIGNED','In Progress','DISPATCHED'])) ? 'assigned' : 'unassigned' }}"
                        >
                            <td><strong>IN{{ str_pad($ticket->idTicket, 8, '0', STR_PAD_LEFT) }}</strong></td>
                            <td>{{ $ticket->namacust ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $urgBadge = match((int)($ticket->urgency_level ?? 1)) {
                                        5 => ['label'=>'VVIP/Management', 'color'=>'#92400E', 'style'=>'background:#FEF3C7;color:#92400E;border:1px solid #F6AD55;'],
                                        4 => ['label'=>'HVC', 'color'=>'#6B46C1', 'style'=>'background:#F5F0FF;color:#6B46C1;border:1px solid #B794F4;'],
                                        3 => ['label'=>'Super Emergency', 'color'=>'#C53030', 'style'=>'background:#FFF5F5;color:#C53030;border:1px solid #FC8181;'],
                                        2 => ['label'=>'Emergency', 'color'=>'#B7791F', 'style'=>'background:#FFFBEA;color:#B7791F;border:1px solid #F6E05E;'],
                                        default => ['label'=>'Low Emergency', 'color'=>'#4A5568', 'style'=>'background:#EDF2F7;color:#4A5568;'],
                                    };
                                @endphp
                                <span class="status-badge" style="{{ $urgBadge['style'] }}font-size:10px; display:inline-flex; align-items:center; gap:5px;">
                                    <span style="background-color:{{ $urgBadge['color'] }}; width:6px; height:6px; border-radius:50%; display:inline-block;"></span>
                                    {{ $urgBadge['label'] }}
                                </span>
                            </td>
                            <td>{{ Str::limit($ticket->detailticket ?? 'No description', 40) }}</td>
                            <td class="date-cell">
                                @php
                                    $ticketDate = $ticket->datereport ?? $ticket->created_at;
                                    $daysWaiting = $ticketDate ? (int) \Carbon\Carbon::parse($ticketDate)->diffInDays(now()) : null;
                                @endphp
                                <div class="date-entry">
                                    <span class="date-text">{{ $ticketDate ? \Carbon\Carbon::parse($ticketDate)->format('d M Y') : '-' }}</span>
                                    <span class="date-time">{{ $ticketDate ? \Carbon\Carbon::parse($ticketDate)->format('H:i') : '' }}</span>
                                </div>
                                @if($daysWaiting !== null)
                                    @if($daysWaiting === 0)
                                        <span class="age-pill age-today">Hari ini</span>
                                    @elseif($daysWaiting === 1)
                                        <span class="age-pill age-warn">1 hari lalu</span>
                                    @elseif($daysWaiting <= 3)
                                        <span class="age-pill age-warn">{{ $daysWaiting }} hari lalu</span>
                                    @else
                                        <span class="age-pill age-critical">{{ $daysWaiting }} hari lalu</span>
                                    @endif
                                @endif
                            </td>
                            <td>
                                @if($ticket->assigned_to_user_id)
                                    <span class="status-badge status-assigned">ASSIGNED</span>
                                @else
                                    <span class="status-badge status-unassigned">QUEUED</span>
                                @endif
                            </td>
                            <td>{{ $ticket->assignedTo?->name ?? '-' }}</td>
                            <td>
                                <form action="{{ route('team-leader.assign.ticket', $ticket->idTicket) }}" method="POST" class="assign-form">
                                    @csrf
                                    <div class="agent-search-wrap">
                                        <input type="hidden" name="agent_id" class="agent-id-input">
                                        <input
                                            type="text"
                                            class="agent-search-input"
                                            placeholder="Search agent..."
                                            autocomplete="off"
                                            readonly
                                        >
                                        <svg class="agent-caret" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                        <div class="agent-dropdown">
                                            @foreach($agents as $agent)
                                                <div class="agent-option" data-id="{{ $agent->id }}" data-name="{{ $agent->name }}">
                                                    {{ $agent->name }}
                                                </div>
                                            @endforeach
                                            <div class="agent-no-results" style="display:none;">No agent found</div>
                                        </div>
                                    </div>
                            </td>
                            <td>
                                    <button type="submit" class="assign-btn">Assign</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 40px; color: #999;">
                                Tidak ada tiket prioritas tinggi saat ini. Semua aman!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <x-slot name="additionalScripts">
        // Toast Notification Function
        function showToast(title, message, type = 'success') {
            const container = document.getElementById('toastContainer');
            
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            
            toast.innerHTML = `
                <div class="toast-icon">✓</div>
                <div class="toast-content">
                    <div class="toast-title">${title}</div>
                    <div class="toast-message">${message}</div>
                </div>
                <button class="toast-close" onclick="closeToast(this)">×</button>
            `;
            
            container.appendChild(toast);
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                closeToast(toast.querySelector('.toast-close'));
            }, 5000);
        }
        
        function closeToast(button) {
            const toast = button.closest('.toast');
            toast.classList.add('hiding');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }
        
        // Show toast if there's a success message
        @if(session('success'))
            showToast('Success!', '{{ session('success') }}', 'success');
        @endif
        
        // ── Combined filter logic ────────────────────────────────────────────
        let activeStatusFilter = 'all';

        function applyFilters() {
            const searchVal   = document.getElementById('searchInput').value.toLowerCase().trim();
            const dateVal     = document.getElementById('dateFilter').value;   // 'YYYY-MM-DD' or ''
            const statusVal   = activeStatusFilter;                             // 'all' | 'assigned' | 'unassigned'

            const table = document.getElementById('ticketsTable');
            const rows  = table.querySelector('tbody').querySelectorAll('tr');
            let visible = 0;

            rows.forEach(row => {
                const rowDate     = row.dataset.date     || '';
                const rowAssigned = row.dataset.assigned || '';
                const rowText     = row.textContent.toLowerCase();

                const matchSearch = !searchVal || rowText.includes(searchVal);
                const matchDate   = !dateVal   || rowDate === dateVal;
                const matchStatus = statusVal === 'all' || rowAssigned === statusVal;

                const show = matchSearch && matchDate && matchStatus;
                row.style.display = show ? '' : 'none';
                if (show) visible++;
            });

            document.getElementById('visibleCount').textContent = visible;
        }

        // Search
        document.getElementById('searchInput').addEventListener('input', applyFilters);

        // Date filter
        document.getElementById('dateFilter').addEventListener('change', applyFilters);

        document.getElementById('dateResetBtn').addEventListener('click', function() {
            document.getElementById('dateFilter').value = '';
            applyFilters();
        });

        // Assignment status filter buttons
        document.querySelectorAll('.status-filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.status-filter-btn').forEach(b => {
                    b.classList.remove('active', 'active-assigned', 'active-unassigned');
                });

                activeStatusFilter = this.dataset.filter;

                if (activeStatusFilter === 'assigned') {
                    this.classList.add('active-assigned');
                } else if (activeStatusFilter === 'unassigned') {
                    this.classList.add('active-unassigned');
                } else {
                    this.classList.add('active');
                }

                applyFilters();
            });
        });

        // ── Default on page load: show all unassigned tickets (no date restriction) ──
        (function initDefaultFilter() {
            document.querySelectorAll('.status-filter-btn').forEach(b => {
                b.classList.remove('active', 'active-assigned', 'active-unassigned');
            });
            activeStatusFilter = 'unassigned';
            document.getElementById('filterUnassigned').classList.add('active-unassigned');
            document.getElementById('dateFilter').value = '';
            applyFilters();
        })();

        // ── Searchable Agent Dropdown ────────────────────────────────────────
        document.querySelectorAll('.agent-search-wrap').forEach(function(wrap) {
            const searchInput  = wrap.querySelector('.agent-search-input');
            const hiddenInput  = wrap.querySelector('.agent-id-input');
            const dropdown     = wrap.querySelector('.agent-dropdown');
            const options      = wrap.querySelectorAll('.agent-option');
            const noResults    = wrap.querySelector('.agent-no-results');
            let focusedIndex   = -1;

            // Make the text input editable when the dropdown opens
            function openDropdown() {
                searchInput.removeAttribute('readonly');
                dropdown.classList.add('open');
                filterOptions(searchInput.value);
                focusedIndex = -1;
            }

            function closeDropdown() {
                dropdown.classList.remove('open');
                searchInput.setAttribute('readonly', true);
                focusedIndex = -1;
            }

            function filterOptions(query) {
                const q = query.toLowerCase().trim();
                let visible = 0;
                options.forEach(opt => {
                    const match = opt.dataset.name.toLowerCase().includes(q);
                    opt.style.display = match ? '' : 'none';
                    if (match) visible++;
                });
                noResults.style.display = visible === 0 ? '' : 'none';
            }

            function selectOption(opt) {
                hiddenInput.value     = opt.dataset.id;
                searchInput.value     = opt.dataset.name;
                searchInput.classList.add('has-value');
                options.forEach(o => o.classList.remove('selected'));
                opt.classList.add('selected');
                closeDropdown();
            }

            // Toggle on click of the text input
            searchInput.addEventListener('click', function() {
                if (dropdown.classList.contains('open')) {
                    closeDropdown();
                } else {
                    openDropdown();
                }
            });

            // Filter while typing
            searchInput.addEventListener('input', function() {
                filterOptions(this.value);
                // Clear selection if user edits text manually
                hiddenInput.value = '';
                searchInput.classList.remove('has-value');
                options.forEach(o => o.classList.remove('selected'));
            });

            // Keyboard navigation
            searchInput.addEventListener('keydown', function(e) {
                const visible = [...options].filter(o => o.style.display !== 'none');
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    if (!dropdown.classList.contains('open')) openDropdown();
                    focusedIndex = Math.min(focusedIndex + 1, visible.length - 1);
                    visible.forEach((o, i) => o.classList.toggle('focused', i === focusedIndex));
                    if (visible[focusedIndex]) visible[focusedIndex].scrollIntoView({ block: 'nearest' });
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    focusedIndex = Math.max(focusedIndex - 1, 0);
                    visible.forEach((o, i) => o.classList.toggle('focused', i === focusedIndex));
                    if (visible[focusedIndex]) visible[focusedIndex].scrollIntoView({ block: 'nearest' });
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    if (visible[focusedIndex]) selectOption(visible[focusedIndex]);
                } else if (e.key === 'Escape') {
                    closeDropdown();
                }
            });

            // Click on an option
            options.forEach(opt => {
                opt.addEventListener('click', function() {
                    selectOption(this);
                });
            });

            // Close when clicking outside
            document.addEventListener('click', function(e) {
                if (!wrap.contains(e.target)) closeDropdown();
            });

            // Prevent form submit if no agent selected
            wrap.closest('.assign-form').addEventListener('submit', function(e) {
                if (!hiddenInput.value) {
                    e.preventDefault();
                    searchInput.style.borderColor = '#EF4444';
                    searchInput.placeholder = 'Please select an agent!';
                    setTimeout(() => {
                        searchInput.style.borderColor = '';
                        searchInput.placeholder = 'Search agent...';
                    }, 2000);
                }
            });
        });
        

    </x-slot>
</x-agent-layout>
