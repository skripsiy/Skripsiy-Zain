<x-agent-layout>
    <x-slot name="title">Laporan Tiket</x-slot>

    <x-slot name="headerContent">
        <div style="display:flex; gap:12px; align-items:center;">
            <a href="{{ route('admin.reports.index') }}" class="nav-tab">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                Laporan User
            </a>
            <a href="{{ route('admin.reports.tickets') }}" class="nav-tab active">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>
                Laporan Tiket
            </a>
        </div>
    </x-slot>

    <x-slot name="sidebar">
        <a class="sidebar-icon" href="{{ route('admin.dashboard') }}" title="Dashboard">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
        </a>
        <a class="sidebar-icon" href="{{ route('admin.users.index') }}" title="Users">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
        </a>
        <a class="sidebar-icon active" href="{{ route('admin.reports.index') }}" title="Reports">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/></svg>
        </a>
    </x-slot>

    <x-slot name="customStyles">
        /* ───── Nav Tabs ───── */
        .nav-tab {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            color: #64748b;
            text-decoration: none;
            transition: all .2s;
        }
        .nav-tab:hover { background: #f1f5f9; color: #1F4A5E; }
        .nav-tab.active { background: #1F4A5E; color: #fff; }

        /* ───── Filter Bar ───── */
        .filter-card {
            background: #fff;
            border-radius: 14px;
            padding: 20px 24px;
            box-shadow: 0 2px 10px rgba(0,0,0,.07);
            margin-bottom: 20px;
        }
        .filter-title {
            font-size: 13px;
            font-weight: 700;
            color: #1F4A5E;
            letter-spacing: .5px;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .filter-row {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: flex-end;
        }
        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
            flex: 1;
            min-width: 160px;
        }
        .filter-group label {
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .5px;
        }
        .filter-group input,
        .filter-group select {
            padding: 9px 12px;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            font-size: 13px;
            font-family: 'Poppins', sans-serif;
            color: #374151;
            background: #f8fafc;
            transition: border-color .2s, box-shadow .2s;
        }
        .filter-group input:focus,
        .filter-group select:focus {
            outline: none;
            border-color: #2C5F7C;
            box-shadow: 0 0 0 3px rgba(44,95,124,.12);
            background: #fff;
        }
        .btn-filter {
            padding: 9px 20px;
            background: linear-gradient(135deg, #2C5F7C, #1F4A5E);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all .2s;
            white-space: nowrap;
        }
        .btn-filter:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(44,95,124,.3); }
        .btn-reset {
            padding: 9px 16px;
            background: #f1f5f9;
            color: #64748b;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            font-size: 13px;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all .2s;
            white-space: nowrap;
        }
        .btn-reset:hover { background: #e2e8f0; color: #374151; }

        /* ───── Stats Cards ───── */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin-bottom: 20px;
        }
        .stat-card {
            background: #fff;
            border-radius: 14px;
            padding: 18px 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,.07);
            display: flex;
            align-items: center;
            gap: 14px;
            transition: transform .2s, box-shadow .2s;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(0,0,0,.10); }
        .stat-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .stat-icon.blue   { background: #dbeafe; color: #1d4ed8; }
        .stat-icon.green  { background: #d1fae5; color: #065f46; }
        .stat-icon.amber  { background: #fef3c7; color: #92400e; }
        .stat-icon.purple { background: #ede9fe; color: #5b21b6; }
        .stat-body { flex: 1; min-width: 0; }
        .stat-value { font-size: 26px; font-weight: 700; color: #1F4A5E; line-height: 1; }
        .stat-label { font-size: 11px; color: #64748b; font-weight: 600; margin-top: 4px; text-transform: uppercase; letter-spacing: .4px; }

        /* ───── Table Card ───── */
        .table-card {
            background: #fff;
            border-radius: 14px;
            padding: 0;
            box-shadow: 0 2px 10px rgba(0,0,0,.07);
            overflow: hidden;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 22px;
            border-bottom: 1px solid #f0f4f8;
        }
        .table-title { font-size: 14px; font-weight: 700; color: #1F4A5E; }
        .table-meta  { font-size: 12px; color: #94a3b8; }
        .btn-export {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            text-decoration: none;
            transition: all .2s;
        }
        .btn-export:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(16,185,129,.3); }
        .table-scroll { overflow-x: auto; flex: 1; }
        table { width: 100%; border-collapse: collapse; font-size: 12px; min-width: 900px; }
        thead { position: sticky; top: 0; z-index: 5; }
        th {
            padding: 11px 14px;
            text-align: left;
            background: #f8fafc;
            color: #475569;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 10.5px;
            letter-spacing: .5px;
            border-bottom: 2px solid #e2e8f0;
            white-space: nowrap;
        }
        td {
            padding: 11px 14px;
            border-bottom: 1px solid #f1f5f9;
            color: #374151;
            vertical-align: middle;
        }
        tbody tr:hover { background: #f8fafc; }
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .badge-closed   { background: #d1fae5; color: #065f46; }
        .badge-assigned { background: #dbeafe; color: #1d4ed8; }
        .badge-queued   { background: #fef3c7; color: #92400e; }
        .badge-progress { background: #ede9fe; color: #5b21b6; }
        .badge-default  { background: #f1f5f9; color: #64748b; }

        /* ───── Pagination ───── */
        .pagination-wrap {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 22px;
            border-top: 1px solid #f0f4f8;
            flex-shrink: 0;
        }
        .pagination-info { font-size: 12px; color: #94a3b8; }
        .pagination { display: flex; gap: 4px; list-style: none; margin: 0; padding: 0; }
        .pagination li a,
        .pagination li span {
            display: block;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            text-decoration: none;
            color: #475569;
            border: 1px solid #e2e8f0;
            transition: all .2s;
        }
        .pagination li.active span {
            background: #1F4A5E;
            color: #fff;
            border-color: #1F4A5E;
        }
        .pagination li a:hover { background: #f1f5f9; color: #1F4A5E; }
        .pagination li.disabled span { color: #cbd5e1; cursor: not-allowed; }

        /* ───── Empty State ───── */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #94a3b8;
        }
        .empty-state svg { width: 60px; height: 60px; margin: 0 auto 16px; opacity: .35; display: block; }
        .empty-state p { font-size: 14px; font-weight: 500; }

        /* ───── Active Filter Tags ───── */
        .active-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 14px;
        }
        .filter-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            background: #e0f2fe;
            color: #0369a1;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }
    </x-slot>

    {{-- ═══ Active Filter Tags ═══ --}}
    @if($dateFrom || $dateTo || $status || $agentId || $ticketId)
    <div class="active-filters">
        <span style="font-size:11px;color:#64748b;font-weight:600;align-self:center;">Filter aktif:</span>
        @if($ticketId)
            <span class="filter-tag">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                ID Tiket: {{ $ticketId }}
            </span>
        @endif
        @if($dateFrom)
            <span class="filter-tag">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                Dari: {{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }}
            </span>
        @endif
        @if($dateTo)
            <span class="filter-tag">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                Sampai: {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}
            </span>
        @endif
        @if($status)
            <span class="filter-tag">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                Status: {{ $status }}
            </span>
        @endif
        @if($agentId)
            @php $selectedAgent = $agents->find($agentId); @endphp
            <span class="filter-tag">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                Agent: {{ $selectedAgent?->name ?? '-' }}
            </span>
        @endif
    </div>
    @endif

    {{-- ═══ Filter Bar ═══ --}}
    <div class="filter-card">
        <div class="filter-title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
            </svg>
            Filter Laporan Tiket
        </div>
        <form method="GET" action="{{ route('admin.reports.tickets') }}" id="filterForm">
            <div class="filter-row">
                <div class="filter-group">
                    <label>Keyword</label>
                    <input type="text" name="keyword" id="keyword"
                           placeholder="Cari tiket (pelanggan, no telp, deskripsi...)" value="{{ $keyword ?? '' }}">
                </div>
                <div class="filter-group">
                    <label>ID Tiket</label>
                    <input type="text" name="ticket_id" id="ticket_id"
                           placeholder="Cari ID tiket..." value="{{ $ticketId }}">
                </div>
                <div class="filter-group">
                    <label>Tanggal Dari</label>
                    <input type="date" name="date_from" id="date_from"
                           value="{{ $dateFrom }}" max="{{ date('Y-m-d') }}">
                </div>
                <div class="filter-group">
                    <label>Tanggal Sampai</label>
                    <input type="date" name="date_to" id="date_to"
                           value="{{ $dateTo }}" max="{{ date('Y-m-d') }}">
                </div>
                <div class="filter-group">
                    <label>Status Tiket</label>
                    <select name="status" id="status">
                        <option value="">— Semua Status —</option>
                        <option value="QUEUED"      {{ $status === 'QUEUED'      ? 'selected' : '' }}>Queued</option>
                        <option value="ASSIGNED"    {{ $status === 'ASSIGNED'    ? 'selected' : '' }}>Assigned</option>
                        <option value="In Progress" {{ $status === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="Open"        {{ $status === 'Open'        ? 'selected' : '' }}>Open</option>
                        <option value="Closed"      {{ $status === 'Closed'      ? 'selected' : '' }}>Closed</option>
                        <option value="Saltik"      {{ $status === 'Saltik'      ? 'selected' : '' }}>Saltik</option>
                        <option value="Dispatched"  {{ $status === 'Dispatched'  ? 'selected' : '' }}>Dispatched</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Agent</label>
                    <select name="agent_id" id="agent_id">
                        <option value="">— Semua Agent —</option>
                        @foreach($agents as $agent)
                            <option value="{{ $agent->id }}" {{ $agentId == $agent->id ? 'selected' : '' }}>
                                {{ $agent->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div style="display:flex;gap:8px;align-items:flex-end;">
                    <button type="submit" class="btn-filter">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                        </svg>
                        Terapkan
                    </button>
                    <a href="{{ route('admin.reports.tickets') }}" class="btn-reset">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                            <path d="M3 3v5h5"/>
                        </svg>
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- ═══ Stats Cards ═══ --}}
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon blue">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>
            </div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($totalTickets) }}</div>
                <div class="stat-label">Total Tiket</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>
            </div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($closedCount) }}</div>
                <div class="stat-label">Closed</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon purple">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
            </div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($assignedCount) }}</div>
                <div class="stat-label">Assigned</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon amber">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
            </div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($queuedCount) }}</div>
                <div class="stat-label">Queued / Unassigned</div>
            </div>
        </div>
    </div>

    {{-- ═══ Table Card ═══ --}}
    <div class="table-card">
        <div class="table-header">
            <div>
                <div class="table-title">Daftar Tiket</div>
                <div class="table-meta">
                    Menampilkan {{ $tickets->firstItem() ?? 0 }}–{{ $tickets->lastItem() ?? 0 }}
                    dari {{ $tickets->total() }} tiket
                </div>
            </div>
            <a href="{{ route('admin.reports.export.tickets', request()->query()) }}" class="btn-export">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="7 10 12 15 17 10"/>
                    <line x1="12" y1="15" x2="12" y2="3"/>
                </svg>
                Export Excel
            </a>
        </div>

        <div class="table-scroll">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ticket ID</th>
                        <th>Tanggal Masuk</th>
                        <th>Nama Customer</th>
                        <th>Jenis Tiket</th>
                        <th>Assigned To</th>
                        <th>Solved By</th>
                        <th>Regional</th>
                        <th>Status</th>
                        <th>Tanggal Solved</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $index => $ticket)
                    <tr>
                        <td style="color:#94a3b8;">{{ $tickets->firstItem() + $index }}</td>
                        <td style="font-weight:700;color:#1F4A5E;">{{ $ticket->idTicket }}</td>
                        <td>
                            {{ $ticket->datereport ? \Carbon\Carbon::parse($ticket->datereport)->format('d M Y') : '-' }}
                        </td>
                        <td style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"
                            title="{{ $ticket->namacust }}">
                            {{ $ticket->namacust ?? '-' }}
                        </td>
                        <td>{{ $ticket->jenisTicket ?? '-' }}</td>
                        <td>{{ $ticket->assignedTo?->name ?? '-' }}</td>
                        <td>{{ $ticket->solvedBy?->name ?? '-' }}</td>
                        <td>{{ $ticket->regional ?? '-' }}</td>
                        <td>
                            @php
                                $cond = strtolower($ticket->condition ?? '');
                            @endphp
                            @if(in_array($cond, ['closed']))
                                <span class="badge badge-closed">{{ $ticket->condition }}</span>
                            @elseif(in_array($cond, ['assigned']))
                                <span class="badge badge-assigned">{{ $ticket->condition }}</span>
                            @elseif(in_array($cond, ['queued', 'unassigned']))
                                <span class="badge badge-queued">{{ $ticket->condition }}</span>
                            @elseif(str_contains($cond, 'progress'))
                                <span class="badge badge-progress">{{ $ticket->condition }}</span>
                            @else
                                <span class="badge badge-default">{{ $ticket->condition ?? 'N/A' }}</span>
                            @endif
                        </td>
                        <td>
                            {{ $ticket->datesolved ? \Carbon\Carbon::parse($ticket->datesolved)->format('d M Y') : '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10">
                            <div class="empty-state">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/>
                                </svg>
                                <p>Tidak ada tiket yang ditemukan untuk filter ini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($tickets->hasPages())
        <div class="pagination-wrap">
            <span class="pagination-info">
                Halaman {{ $tickets->currentPage() }} dari {{ $tickets->lastPage() }}
            </span>
            {{ $tickets->links('pagination::simple-bootstrap-5') }}
        </div>
        @endif
    </div>

    <x-slot name="additionalScripts">
        // Validasi date range: date_to tidak boleh < date_from
        document.getElementById('filterForm').addEventListener('submit', function(e) {
            const from = document.getElementById('date_from').value;
            const to   = document.getElementById('date_to').value;
            if (from && to && to < from) {
                e.preventDefault();
                alert('Tanggal "Sampai" tidak boleh lebih awal dari tanggal "Dari".');
            }
        });

        // Auto-set date_to jika date_from dipilih dan date_to kosong
        document.getElementById('date_from').addEventListener('change', function() {
            const toField = document.getElementById('date_to');
            if (!toField.value) toField.value = this.value;
            toField.min = this.value;
        });
    </x-slot>
</x-agent-layout>
