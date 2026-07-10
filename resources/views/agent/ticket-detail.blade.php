{{-- ============================================================
     XENA - Agent Ticket Detail
     Layout updated to match Lo-fi wireframe:
       - HEADER  : logo + notification bell + avatar
       - SIDEBAR  : icon-only (Dashboard, Tickets active, Profile)
       - MAIN    : ← back link → ticket header card → 2-col layout
                    LEFT : form fields + agent-only fields + save + quick actions
                    RIGHT: activity log (Spatie)
     *** ALL FORM FIELDS, HANDLERS, JS LOGIC, $canEdit GUARD UNCHANGED ***
     ============================================================ --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>XENA - Ticket Detail</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* ── Base (unchanged) ── */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: #F5F5F5; min-height: 100vh; }

        /* ── CHANGED: header layout now includes notification area ── */
        .header {
            background: #FFFFFF;
            padding: 16px 24px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .logo {
            font-size: 22px;
            font-weight: 700;
            background: linear-gradient(90deg,#0C1D25 0%,#1F4A5E 56%,#2D6D8B 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .header-right { display: flex; gap: 12px; align-items: center; }
        .user-avatar {
            width: 38px; height: 38px; border-radius: 50%;
            background: linear-gradient(135deg,#1F4A5E 0%,#2D6D8B 100%);
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 600; font-size: 15px; cursor: pointer;
        }

        /* ── Outer container: sidebar + main (unchanged widths) ── */
        .container { display: flex; height: calc(100vh - 70px); overflow: hidden; }
        .sidebar {
            width: 50px; background: #FFFFFF;
            padding: 15px 0; display: flex; flex-direction: column;
            align-items: center; gap: 25px;
            box-shadow: 2px 0 4px rgba(0,0,0,0.05);
        }
        .sidebar-icon {
            width: 30px; height: 30px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; color: #666; transition: all 0.2s; text-decoration: none;
        }
        .sidebar-icon:hover, .sidebar-icon.active { color: #1F4A5E; }

        /* ── CHANGED: main-content now overflow-y: auto with padding ── */
        .main-content {
            flex: 1;
            padding: 20px 24px;
            overflow-y: auto;
            background: #F5F7FA;
        }

        /* ── CHANGED: back link ── */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: #1F4A5E;
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 16px;
            transition: opacity 0.2s;
        }
        .back-link:hover { opacity: 0.75; }

        /* ── Alerts (unchanged logic, unchanged classes) ── */
        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 14px; font-size: 13px; }
        .alert-success { background: #D1FAE5; color: #065F46; border: 1px solid #10B981; }

        /* ── CHANGED: Ticket Header Card ── */
        .ticket-header-card {
            background: #FFFFFF;
            border-radius: 12px;
            padding: 20px 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 16px;
        }
        .ticket-header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
        }
        .ticket-code {
            font-size: 22px;
            font-weight: 700;
            color: #1F2A37;
            margin-bottom: 4px;
        }
        .ticket-customer {
            font-size: 13px;
            color: #718096;
        }
        .badge-group { display: flex; gap: 8px; }
        .badge-status, .badge-urgency {
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }
        /* CHANGED: ticket info in 4-col grid */
        .ticket-info-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-top: 12px;
        }
        .ticket-info-item {}
        .ticket-info-label {
            font-size: 10px;
            font-weight: 600;
            color: #999;
            text-transform: uppercase;
            margin-bottom: 3px;
            letter-spacing: 0.4px;
        }
        .ticket-info-value {
            font-size: 13px;
            color: #1F2A37;
            font-weight: 500;
        }

        /* CHANGED: Stats row kept but styled as small info inside header card */
        .stats-row {
            display: flex;
            gap: 20px;
            margin-top: 14px;
            padding: 12px 16px;
            background: #F9FAFB;
            border-radius: 8px;
            justify-content: center;
            align-items: center;
        }
        .stat-item { text-align: center; }
        .stat-label { font-size: 11px; color: #6B7280; margin-bottom: 2px; }
        .stat-value { font-size: 16px; font-weight: 700; color: #1F2A37; }
        .stat-null { color: #E53E3E; }

        /* ── CHANGED: 2-col main layout ── */
        .two-col-layout {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 16px;
            align-items: start;
        }

        /* ── Content cards ── */
        .content-card {
            background: #FFFFFF;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            padding: 20px;
        }
        .card-title {
            font-size: 14px;
            font-weight: 700;
            color: #1F2A37;
            margin-bottom: 16px;
        }

        /* ── Form (unchanged styling, just kept) ── */
        .form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; margin-bottom: 14px; }
        .form-grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 14px; }
        .form-group { display: flex; flex-direction: column; gap: 4px; }
        .form-label { font-size: 11px; font-weight: 600; color: #1F2A37; }
        .form-label-agent {
            font-size: 11px; font-weight: 600; color: #1F4A5E;
            display: flex; align-items: center; gap: 4px;
        }
        .agent-only-tag {
            font-size: 9px; font-weight: 400; color: #1F4A5E;
            background: #EBF4F8; border-radius: 4px; padding: 1px 5px;
        }
        .form-select {
            padding: 8px 10px;
            border: 1px solid #D1D5DB;
            border-radius: 6px;
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
            background: #F3F4F6;
            color: #374151;
        }
        .form-input {
            padding: 8px 10px;
            border: 1px solid #D1D5DB;
            border-radius: 6px;
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
            background: #F3F4F6;
            color: #374151;
        }
        .form-textarea {
            padding: 10px;
            border: 1px solid #D1D5DB;
            border-radius: 6px;
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
            background: #F3F4F6;
            resize: vertical;
            min-height: 80px;
            width: 100%;
        }
        .form-full { grid-column: span 2; }

        /* ── CHANGED: Save button right-aligned ── */
        .save-row {
            display: flex;
            justify-content: flex-end;
            margin-top: 14px;
        }

        /* ── Quick actions section (CHANGED: moved inside left card below form) ── */
        .quick-actions-section {
            margin-top: 18px;
            padding-top: 16px;
            border-top: 1px solid #F0F0F0;
        }
        .quick-actions-title {
            font-size: 12px;
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
        }
        .quick-actions-row { display: flex; gap: 8px; flex-wrap: wrap; }

        /* ── Buttons (classes unchanged, only quick-action colours added) ── */
        .btn {
            padding: 8px 19px; border-radius: 7px; font-size: 12px;
            font-weight: 600; cursor: pointer; border: 1px solid transparent;
            transition: 0.2s; display: inline-flex; align-items: center; gap: 6px;
            font-family: 'Poppins', sans-serif;
        }
        .btn-submit   { background: #1F4A5E; color: white; }
        .btn-closed   { background: #E53E3E; color: white; }
        .btn-expired  { background: #718096; color: white; }
        .btn-dispatch { background: #38A169; color: white; }
        .btn-saltik   { background: #F3E8FF; color: #6B21A8; border-color: #D8B4FE; }
        .btn-primary  { background: linear-gradient(135deg,#1F4A5E,#2D6D8B); color: white; }
        .btn:disabled { opacity: 0.5; cursor: not-allowed; }
        .btn:not(:disabled):hover { opacity: 0.9; transform: translateY(-1px); }

        /* ── CHANGED: Activity Log right panel ── */
        .activity-log-panel { max-height: calc(100vh - 280px); overflow-y: auto; }
        .activity-timeline { margin-top: 4px; padding-left: 10px; }
        .activity-item {
            position: relative;
            padding-left: 20px;
            padding-bottom: 18px;
            border-left: 2px solid #E5E7EB;
        }
        .activity-item:last-child { border-left: none; }
        .activity-dot {
            position: absolute; left: -6px; top: 0;
            width: 10px; height: 10px;
            background: #1F4A5E; border-radius: 50%;
        }
        .activity-header {
            display: flex; gap: 6px; align-items: center;
            flex-wrap: wrap; font-size: 12px; margin-bottom: 4px;
        }
        .activity-user { font-weight: 600; color: #1F2A37; }
        .activity-action { color: #6B7280; }
        .activity-time { color: #9CA3AF; font-size: 10px; margin-left: auto; }
        .activity-changes {
            background: #F9FAFB; padding: 10px 12px;
            border-radius: 6px; font-size: 11px;
            margin-top: 4px; position: relative;
        }
        .btn-copy {
            position: absolute; top: 6px; right: 6px;
            background: #FFFFFF; border: 1px solid #E5E7EB;
            color: #6B7280; cursor: pointer; padding: 4px;
            border-radius: 4px; transition: all 0.2s;
            display: flex; align-items: center; justify-content: center;
        }
        .btn-copy:hover { background: #F3F4F6; color: #1F4A5E; }
        .change-item { display: flex; gap: 6px; margin-bottom: 2px; }
        .change-key { font-weight: 600; color: #4B5563; }
        .change-value { color: #1F2A37; }
        .no-activity { color: #9CA3AF; font-size: 12px; font-style: italic; }
    </style>
</head>
<body>

    {{-- ======================================================
         HEADER  — CHANGED: added notification bell slot
         ====================================================== --}}
    <div class="header">
        <div class="logo">XENA</div>
        <div class="header-right">
            {{-- Notification bell component (unchanged, already in layout) --}}
            <x-notification-bell />
            <div class="user-avatar" onclick="toggleDropdown()">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
        </div>
    </div>

    <div class="container">

        {{-- ======================================================
             SIDEBAR  — icon links unchanged, order unchanged
             ====================================================== --}}
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

        {{-- ======================================================
             MAIN CONTENT
             ====================================================== --}}
        <div class="main-content">

            {{-- CHANGED: Back link --}}
            <a href="{{ route('agent.tickets') }}" class="back-link">
                ← Back to My Tickets
            </a>

            {{-- ── Flash messages (logic unchanged) ── --}}
            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
            <div class="alert" style="background:#FEE2E2;color:#991B1B;border:1px solid #EF4444;">
                {{ session('error') }}
            </div>
            @endif

            {{-- ── Read-only guard banner (logic UNCHANGED) ── --}}
            @if(!$canEdit)
            <div class="alert" style="background:#FEF3C7;color:#92400E;border:1px solid #F59E0B;display:flex;align-items:center;gap:8px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                <span>
                    @if(in_array($ticket->condition, ['Closed', 'Dispatched', 'DISPATCHED', 'Saltik']))
                        Anda sedang dalam mode <strong>Hanya Lihat (Read-Only)</strong> karena tiket ini sudah ditutup, didispatch, atau ditandai sebagai SALTIK.
                    @else
                        Anda sedang dalam mode <strong>Hanya Lihat (Read-Only)</strong> karena tiket ini tidak di-assign kepada Anda. Anda tidak dapat melakukan perubahan atau update status.
                    @endif
                </span>
            </div>
            @endif

            {{-- ==================================================
                 CHANGED: TICKET HEADER CARD
                 (replaces the old ticket-id div + info-grid + badge-row)
                 ================================================== --}}
            <div class="ticket-header-card">
                <div class="ticket-header-top">
                    {{-- Left: code + customer --}}
                    <div>
                        <div class="ticket-code">IN{{ str_pad($ticket->idTicket, 8, '0', STR_PAD_LEFT) }}</div>
                        <div class="ticket-customer">
                            {{ $ticket->namacust ?? '-' }} &mdash; {{ $ticket->notelpCust ?? '-' }}
                        </div>
                    </div>
                    {{-- Right: status + urgency badges --}}
                    <div class="badge-group">
                        <span class="badge-status" style="background:
                            {{ match(strtolower($ticket->condition ?? '')) {
                                'closed'      => '#FFEBEE; color:#C62828;',
                                'saltik'      => '#F3E5F5; color:#7B1FA2;',
                                'dispatched'  => '#E1F5FE; color:#0288D1;',
                                'assigned'    => '#E3F2FD; color:#1976D2;',
                                'in progress' => '#FFF9C4; color:#F57F17;',
                                default       => '#F3F4F6; color:#1F2A37;',
                            } }}">
                            {{ strtoupper($ticket->condition ?? $ticket->status) }}
                        </span>
                        @if($ticket->reportedpriority)
                        <span class="badge-urgency" style="background:#FFEEF0;color:#D0021B;">
                            {{ $ticket->reportedpriority }}
                        </span>
                        @endif
                    </div>
                </div>

                {{-- CHANGED: 4-column info grid --}}
                <div class="ticket-info-grid">
                    <div class="ticket-info-item">
                        <div class="ticket-info-label">Date Report</div>
                        <div class="ticket-info-value">
                            {{ $ticket->datereport ? $ticket->datereport->format('Y-m-d H:i') : '-' }}
                        </div>
                    </div>
                    <div class="ticket-info-item">
                        <div class="ticket-info-label">Regional</div>
                        <div class="ticket-info-value">{{ $ticket->regional ?? '-' }}</div>
                    </div>
                    <div class="ticket-info-item">
                        <div class="ticket-info-label">Witel</div>
                        <div class="ticket-info-value">{{ $ticket->witel ?? '-' }}</div>
                    </div>
                    <div class="ticket-info-item">
                        <div class="ticket-info-label">Assigned To</div>
                        <div class="ticket-info-value">
                            {{ $ticket->assignedTo?->name ?? Auth::user()->name }}
                        </div>
                    </div>
                    <div class="ticket-info-item">
                        <div class="ticket-info-label">ID Laporan</div>
                        <div class="ticket-info-value">{{ $ticket->idlaporan ?? '-' }}</div>
                    </div>
                    <div class="ticket-info-item">
                        <div class="ticket-info-label">No Tiket</div>
                        <div class="ticket-info-value">{{ $ticket->idTicket ?? '-' }}</div>
                    </div>
                    <div class="ticket-info-item">
                        <div class="ticket-info-label">Lapul</div>
                        <div class="ticket-info-value">{{ $ticket->lapul ?? 0 }}</div>
                    </div>
                    <div class="ticket-info-item">
                        <div class="ticket-info-label">Gaul</div>
                        <div class="ticket-info-value">{{ $ticket->gaul ?? 0 }}</div>
                    </div>
                </div>

                {{-- Stats row (unchanged data, styling adjusted) --}}
                <div class="stats-row">
                    <div class="stat-item">
                        <div class="stat-label stat-null">NULL</div>
                        <div class="stat-value">GAMAS</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">{{ $ticket->lapul ?? 0 }}</div>
                        <div class="stat-value">LAPUL</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">{{ $ticket->gaul ?? 0 }}</div>
                        <div class="stat-value">GAUL</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">TTR</div>
                        <div class="stat-value" style="font-size:13px;">
                            {{ $ticket->datereport ? $ticket->datereport->diff(\Carbon\Carbon::now())->format('%d Hari, %h Jam') : '-' }}
                        </div>
                    </div>
                </div>

                @if($ticket->attachment)
                <div style="margin-top: 14px; padding: 10px 14px; background: #EDF2F7; border-radius: 8px; display: flex; align-items: center; justify-content: space-between; border: 1px solid #E2E8F0;">
                    <div style="display: flex; align-items: center; gap: 8px; font-size: 12px; color: #4A5568; font-weight: 500;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
                        <span>Attachment: <strong style="color: #2D3748;">{{ basename($ticket->attachment) }}</strong></span>
                    </div>
                    <a href="{{ asset($ticket->attachment) }}" target="_blank" class="btn" style="padding: 5px 12px; background: #1F4A5E; color: white; text-decoration: none; border-radius: 5px; font-size: 11px; border: none; font-family: 'Poppins', sans-serif;">
                        View File
                    </a>
                </div>
                @endif
            </div>{{-- /ticket-header-card --}}

            {{-- ==================================================
                 CHANGED: 2-column layout (1.5fr form | 1fr log)
                 ================================================== --}}
            <div class="two-col-layout">

                {{-- ── LEFT COLUMN: form + actions ── --}}
                <div class="content-card">
                    <div class="card-title">Update Ticket Content</div>

                    <form method="POST" action="{{ route('agent.ticket.update', $ticket->idTicket) }}" id="ticketDetailForm" enctype="multipart/form-data">
                        @csrf

                        {{-- Row 1: Resume + Hasil Pengecekan (agent-only) --}}
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Resume</label>
                                <input type="text" name="resume" class="form-input"
                                    value="{{ $ticket->resume }}"
                                    placeholder="Masukkan resume"
                                    {{ (!$canEdit || $ticket->condition == 'Closed') ? 'disabled' : '' }}>
                            </div>
                            {{-- CHANGED: marked as agent-only per wireframe --}}
                            <div class="form-group">
                                <label class="form-label-agent">
                                    Hasil Inputan / Hasil Pengecekan
                                    <span class="agent-only-tag">Agent only</span>
                                </label>
                                <input type="text" name="hasil_pengecekan" class="form-input"
                                    value="{{ $ticket->hasil_pengecekan }}"
                                    placeholder="Masukkan hasil pengecekan"
                                    {{ (!$canEdit || $ticket->condition == 'Closed') ? 'disabled' : '' }}>
                            </div>
                        </div>

                        {{-- Row 2: Resolved by Agent (agent-only) + Eskalasi via --}}
                        <div class="form-grid">
                            {{-- CHANGED: marked as agent-only per wireframe --}}
                            <div class="form-group">
                                <label class="form-label-agent">
                                    Resolved by Agent
                                    <span class="agent-only-tag">Agent only</span>
                                </label>
                                <select name="resolved_by_agent" class="form-select"
                                    {{ (!$canEdit || $ticket->condition == 'Closed') ? 'disabled' : '' }}>
                                    <option value="">Select</option>
                                    <option value="Succes Resolved" {{ $ticket->resolved_by_agent == 'Succes Resolved' ? 'selected' : '' }}>Succes Resolved</option>
                                    <option value="Gagal Resolved"  {{ $ticket->resolved_by_agent == 'Gagal Resolved'  ? 'selected' : '' }}>Gagal Resolved</option>
                                    <option value="No Action"       {{ $ticket->resolved_by_agent == 'No Action'       ? 'selected' : '' }}>No Action</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Eskalasi via</label>
                                <select name="eksalasiVia" class="form-select"
                                    {{ (!$canEdit || $ticket->condition == 'Closed') ? 'disabled' : '' }}>
                                    <option value="">Select</option>
                                    <option value="Telp"         {{ $ticket->eksalasiVia == 'Telp'         ? 'selected' : '' }}>Telp</option>
                                    <option value="Telegram"     {{ $ticket->eksalasiVia == 'Telegram'     ? 'selected' : '' }}>Telegram</option>
                                    <option value="WA"           {{ $ticket->eksalasiVia == 'WA'           ? 'selected' : '' }}>WA</option>
                                    <option value="Grup internal" {{ $ticket->eksalasiVia == 'Grup internal'? 'selected' : '' }}>Grup internal</option>
                                    <option value="Grup Solver"  {{ $ticket->eksalasiVia == 'Grup Solver'  ? 'selected' : '' }}>Grup Solver</option>
                                </select>
                            </div>
                        </div>

                        {{-- Row 3: Status Call + Reason Not ODS --}}
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Status Call (Kontak)</label>
                                <select name="contact" class="form-select"
                                    {{ (!$canEdit || $ticket->condition == 'Closed') ? 'disabled' : '' }}>
                                    <option value="">Select</option>
                                    <option value="Contacted" {{ $ticket->contact == 'Contacted' ? 'selected' : '' }}>Contacted</option>
                                    <option value="Rna"       {{ $ticket->contact == 'Rna'       ? 'selected' : '' }}>Rna</option>
                                    <option value="Busy"      {{ $ticket->contact == 'Busy'      ? 'selected' : '' }}>Busy</option>
                                    <option value="Rejected"  {{ $ticket->contact == 'Rejected'  ? 'selected' : '' }}>Rejected</option>
                                    <option value="Mailbox"   {{ $ticket->contact == 'Mailbox'   ? 'selected' : '' }}>Mailbox</option>
                                    <option value="Rechedule" {{ $ticket->contact == 'Rechedule' ? 'selected' : '' }}>Rechedule</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Reason Not ODS</label>
                                <select name="reasonnoODS" class="form-select"
                                    {{ (!$canEdit || $ticket->condition == 'Closed') ? 'disabled' : '' }}>
                                    <option value="">Select</option>
                                    <option value="PEOPLE - Menunggu Konfirmasi Dari Pelanggan"           {{ $ticket->reasonnoODS == 'PEOPLE - Menunggu Konfirmasi Dari Pelanggan'           ? 'selected' : '' }}>PEOPLE - Menunggu Konfirmasi Dari Pelanggan</option>
                                    <option value="PEOPLE - Pelanggan Belum Melunasi Tagihan"            {{ $ticket->reasonnoODS == 'PEOPLE - Pelanggan Belum Melunasi Tagihan'            ? 'selected' : '' }}>PEOPLE - Pelanggan Belum Melunasi Tagihan</option>
                                    <option value="PEOPLE - Pelanggan Belum Upload Berkas"               {{ $ticket->reasonnoODS == 'PEOPLE - Pelanggan Belum Upload Berkas'               ? 'selected' : '' }}>PEOPLE - Pelanggan Belum Upload Berkas</option>
                                    <option value="PEOPLE - Pelanggan Reschedule"                        {{ $ticket->reasonnoODS == 'PEOPLE - Pelanggan Reschedule'                        ? 'selected' : '' }}>PEOPLE - Pelanggan Reschedule</option>
                                    <option value="PEOPLE - Pelanggan Sulit Dihubungi"                   {{ $ticket->reasonnoODS == 'PEOPLE - Pelanggan Sulit Dihubungi'                   ? 'selected' : '' }}>PEOPLE - Pelanggan Sulit Dihubungi</option>
                                    <option value="PEOPLE - Pelanggan Tetap Ingin Di visit"              {{ $ticket->reasonnoODS == 'PEOPLE - Pelanggan Tetap Ingin Di visit'              ? 'selected' : '' }}>PEOPLE - Pelanggan Tetap Ingin Di visit</option>
                                    <option value="PEOPLE - Pelanggan Tidak Ada Dilokasi"                {{ $ticket->reasonnoODS == 'PEOPLE - Pelanggan Tidak Ada Dilokasi'                ? 'selected' : '' }}>PEOPLE - Pelanggan Tidak Ada Dilokasi</option>
                                    <option value="PEOPLE - Teknisi Full Order"                          {{ $ticket->reasonnoODS == 'PEOPLE - Teknisi Full Order'                          ? 'selected' : '' }}>PEOPLE - Teknisi Full Order</option>
                                    <option value="PEOPLE - Unit Solver No Respon"                       {{ $ticket->reasonnoODS == 'PEOPLE - Unit Solver No Respon'                       ? 'selected' : '' }}>PEOPLE - Unit Solver No Respon</option>
                                    <option value="PEOPLE - Mapping KIP Tidak Sesuai"                    {{ $ticket->reasonnoODS == 'PEOPLE - Mapping KIP Tidak Sesuai'                    ? 'selected' : '' }}>PEOPLE - Mapping KIP Tidak Sesuai</option>
                                    <option value="PROSES - Status Order Tidak Sesuai"                   {{ $ticket->reasonnoODS == 'PROSES - Status Order Tidak Sesuai'                   ? 'selected' : '' }}>PROSES - Status Order Tidak Sesuai</option>
                                    <option value="PROSES - Workzone Kosong"                             {{ $ticket->reasonnoODS == 'PROSES - Workzone Kosong'                             ? 'selected' : '' }}>PROSES - Workzone Kosong</option>
                                    <option value="PROSES - Ada Permintaan Berlangsung"                  {{ $ticket->reasonnoODS == 'PROSES - Ada Permintaan Berlangsung'                  ? 'selected' : '' }}>PROSES - Ada Permintaan Berlangsung</option>
                                    <option value="PROSES - Alamat Tidak Sesuai"                         {{ $ticket->reasonnoODS == 'PROSES - Alamat Tidak Sesuai'                         ? 'selected' : '' }}>PROSES - Alamat Tidak Sesuai</option>
                                    <option value="PROSES - GAMAS"                                       {{ $ticket->reasonnoODS == 'PROSES - GAMAS'                                       ? 'selected' : '' }}>PROSES - GAMAS</option>
                                    <option value="PROSES - ODP Full"                                    {{ $ticket->reasonnoODS == 'PROSES - ODP Full'                                    ? 'selected' : '' }}>PROSES - ODP Full</option>
                                    <option value="PROSES - Mandeg UFO"                                  {{ $ticket->reasonnoODS == 'PROSES - Mandeg UFO'                                  ? 'selected' : '' }}>PROSES - Mandeg UFO</option>
                                    <option value="PROSES - Mandeg IT"                                   {{ $ticket->reasonnoODS == 'PROSES - Mandeg IT'                                   ? 'selected' : '' }}>PROSES - Mandeg IT</option>
                                    <option value="TOOLS - Labor WO belum Completed"                     {{ $ticket->reasonnoODS == 'TOOLS - Labor WO belum Completed'                     ? 'selected' : '' }}>TOOLS - Labor WO belum Completed</option>
                                    <option value="TOOLS - UNSPEC"                                       {{ $ticket->reasonnoODS == 'TOOLS - UNSPEC'                                       ? 'selected' : '' }}>TOOLS - UNSPEC</option>
                                    <option value="TOOLS - Tidak Bisa Takeownership"                     {{ $ticket->reasonnoODS == 'TOOLS - Tidak Bisa Takeownership'                     ? 'selected' : '' }}>TOOLS - Tidak Bisa Takeownership</option>
                                </select>
                            </div>
                        </div>

                        {{-- Row 4: Respon Backend + Classification --}}
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Respon Backend</label>
                                <select name="responBE" class="form-select"
                                    {{ (!$canEdit || $ticket->condition == 'Closed') ? 'disabled' : '' }}>
                                    <option value="">Select</option>
                                    <option value="Balas chat & Memberi Update Progress"             {{ $ticket->responBE == 'Balas chat & Memberi Update Progress'             ? 'selected' : '' }}>Balas chat &amp; Memberi Update Progress</option>
                                    <option value="Hanya di Read saja"                              {{ $ticket->responBE == 'Hanya di Read saja'                              ? 'selected' : '' }}>Hanya di Read saja</option>
                                    <option value="Membalas chat tapi lama (Lowrespon)"             {{ $ticket->responBE == 'Membalas chat tapi lama (Lowrespon)'             ? 'selected' : '' }}>Membalas chat tapi lama (Lowrespon)</option>
                                    <option value="Hanya Membalas Chat (Fast Respon)"               {{ $ticket->responBE == 'Hanya Membalas Chat (Fast Respon)'               ? 'selected' : '' }}>Hanya Membalas Chat (Fast Respon)</option>
                                    <option value="Menjawab Telp dan memberi Update"                {{ $ticket->responBE == 'Menjawab Telp dan memberi Update'                ? 'selected' : '' }}>Menjawab Telp dan memberi Update</option>
                                    <option value="Respon Menolak Kordinasi"                        {{ $ticket->responBE == 'Respon Menolak Kordinasi'                        ? 'selected' : '' }}>Respon Menolak Kordinasi</option>
                                    <option value="Tidak menerima koord C4"                         {{ $ticket->responBE == 'Tidak menerima koord C4'                         ? 'selected' : '' }}>Tidak menerima koord C4</option>
                                    <option value="Tanpa Koordinasi"                                {{ $ticket->responBE == 'Tanpa Koordinasi'                                ? 'selected' : '' }}>Tanpa Koordinasi</option>
                                    <option value="Tidak Membalas Chat"                             {{ $ticket->responBE == 'Tidak Membalas Chat'                             ? 'selected' : '' }}>Tidak Membalas Chat</option>
                                    <option value="Tidak Menjawab Telp"                             {{ $ticket->responBE == 'Tidak Menjawab Telp'                             ? 'selected' : '' }}>Tidak Menjawab Telp</option>
                                    <option value="Menjawab Telp Saja"                              {{ $ticket->responBE == 'Menjawab Telp Saja'                              ? 'selected' : '' }}>Menjawab Telp Saja</option>
                                    <option value="Membalas chat namun tidak memberi update progress" {{ $ticket->responBE == 'Membalas chat namun tidak memberi update progress' ? 'selected' : '' }}>Membalas chat namun tidak memberi update progress</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Classification</label>
                                <select name="klasifikasi" class="form-select"
                                    {{ (!$canEdit || $ticket->condition == 'Closed') ? 'disabled' : '' }}>
                                    <option value="">Select</option>
                                    <option value="Technical"     {{ $ticket->klasifikasi == 'Technical'     ? 'selected' : '' }}>Technical</option>
                                    <option value="Non-Technical" {{ $ticket->klasifikasi == 'Non-Technical' ? 'selected' : '' }}>Non-Technical</option>
                                </select>
                            </div>
                        </div>

                        {{-- Row 5: Topic + Topic Detail + No SC + Status SC --}}
                        <div class="form-grid-4">
                            <div class="form-group">
                                <label class="form-label">Topic</label>
                                <select name="topic" class="form-select"
                                    {{ (!$canEdit || $ticket->condition == 'Closed') ? 'disabled' : '' }}>
                                    <option value="">Select</option>
                                    <option value="Internet Issue" {{ $ticket->topic == 'Internet Issue' ? 'selected' : '' }}>Internet Issue</option>
                                    <option value="Phone Issue"    {{ $ticket->topic == 'Phone Issue'    ? 'selected' : '' }}>Phone Issue</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Topic Detail</label>
                                <select name="topicDetail" class="form-select"
                                    {{ (!$canEdit || $ticket->condition == 'Closed') ? 'disabled' : '' }}>
                                    <option value="">Select</option>
                                    <option value="Slow Connection" {{ $ticket->topicDetail == 'Slow Connection' ? 'selected' : '' }}>Slow Connection</option>
                                    <option value="No Connection"   {{ $ticket->topicDetail == 'No Connection'   ? 'selected' : '' }}>No Connection</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">No SC/Track ID</label>
                                <select name="noSC" class="form-select"
                                    {{ (!$canEdit || $ticket->condition == 'Closed') ? 'disabled' : '' }}>
                                    <option value="">Select</option>
                                    <option value="SC001" {{ $ticket->noSC == 'SC001' ? 'selected' : '' }}>SC001</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Status SC/Track ID</label>
                                <select name="statusSC" class="form-select"
                                    {{ (!$canEdit || $ticket->condition == 'Closed') ? 'disabled' : '' }}>
                                    <option value="">Select</option>
                                    <option value="Open"   {{ $ticket->statusSC == 'Open'   ? 'selected' : '' }}>Open</option>
                                    <option value="Closed" {{ $ticket->statusSC == 'Closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                            </div>
                        </div>

                        {{-- Row 6: Validasi Close + Eskalasi Tiket + PIC --}}
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Validasi Close</label>
                                <select name="validateClose" class="form-select"
                                    {{ (!$canEdit || $ticket->condition == 'Closed') ? 'disabled' : '' }}>
                                    <option value="">Select</option>
                                    <option value="Yes" {{ $ticket->validateClose == 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No"  {{ $ticket->validateClose == 'No'  ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Eskalasi Tiket</label>
                                <select name="eksalasiTicket" class="form-select"
                                    {{ (!$canEdit || $ticket->condition == 'Closed') ? 'disabled' : '' }}>
                                    <option value="">Select</option>
                                    <option value="Yes" {{ $ticket->eksalasiTicket == 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No"  {{ $ticket->eksalasiTicket == 'No'  ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">PIC</label>
                                <select name="PIC" class="form-select"
                                    {{ (!$canEdit || $ticket->condition == 'Closed') ? 'disabled' : '' }}>
                                    <option value="">Select</option>
                                    <option value="Agent 1" {{ $ticket->PIC == 'Agent 1' ? 'selected' : '' }}>Agent 1</option>
                                </select>
                            </div>
                        </div>

                        {{-- Deskripsi textarea (unchanged, full-width) --}}
                        <div class="form-group" style="margin-bottom:14px;">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description" class="form-textarea"
                                placeholder="Deskripsi"
                                {{ (!$canEdit || $ticket->condition == 'Closed') ? 'disabled' : '' }}>{{ $ticket->description }}</textarea>
                        </div>

                        {{-- CHANGED: Quick Actions moved here (inside the same form for submit) --}}
                        <div class="quick-actions-section">
                            <div class="quick-actions-title">Quick Actions</div>
                            <div class="quick-actions-row">
                                <label class="btn btn-submit" style="cursor: pointer; display: inline-flex; align-items: center; gap: 6px; margin: 0; background: #2C3E50; color: white; {{ !$canEdit ? 'opacity: 0.5; pointer-events: none;' : '' }}">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
                                    Attach (PDF, PNG, JPG)
                                    <input type="file" name="attachment" accept=".pdf,.png,.jpg,.jpeg" style="display: none;" onchange="this.form.submit()" {{ !$canEdit ? 'disabled' : '' }}>
                                </label>
                                <button type="submit"
                                    formaction="{{ route('agent.ticket.status', $ticket->idTicket) }}"
                                    name="action" value="closed"
                                    class="btn btn-closed" {{ !$canEdit ? 'disabled' : '' }}>
                                    CLOSED
                                </button>
                                <button type="submit"
                                    formaction="{{ route('agent.ticket.status', $ticket->idTicket) }}"
                                    name="action" value="saltik"
                                    class="btn btn-saltik" {{ !$canEdit ? 'disabled' : '' }}>
                                    SALTIK
                                </button>
                                <button type="submit"
                                    formaction="{{ route('agent.ticket.status', $ticket->idTicket) }}"
                                    name="action" value="expired"
                                    class="btn btn-expired" {{ !$canEdit ? 'disabled' : '' }}>
                                    EXPIRED
                                </button>
                                <button type="submit"
                                    formaction="{{ route('agent.ticket.status', $ticket->idTicket) }}"
                                    name="action" value="dispatch"
                                    class="btn btn-dispatch"
                                    id="btnDispatch" {{ !$canEdit ? 'disabled' : '' }}>
                                    DISPATCH
                                </button>
                            </div>
                        </div>

                    </form>{{-- /ticketDetailForm --}}
                </div>{{-- /left column --}}

                {{-- ── RIGHT COLUMN: Activity Log ── --}}
                <div class="content-card">
                    <div class="card-title">Activity Log</div>
                    <div class="activity-log-panel">
                        <div class="activity-timeline">
                            @forelse($activities as $activity)
                            <div class="activity-item">
                                <div class="activity-dot"></div>
                                <div>
                                    <div class="activity-header">
                                        <span class="activity-user">{{ $activity->causer->name ?? 'System' }}</span>
                                        <span class="activity-action">{{ $activity->description }}</span>
                                        <span class="activity-time">{{ $activity->created_at->diffForHumans() }}</span>
                                    </div>
                                    @if($activity->properties->has('attributes'))
                                    <div class="activity-changes" id="activity-changes-{{ $activity->id }}">
                                        {{-- copy button (logic unchanged) --}}
                                        <button type="button" class="btn-copy"
                                            onclick="copyActivityChanges('activity-changes-{{ $activity->id }}', this)"
                                            title="Copy changes">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                                        </button>
                                        @foreach($activity->properties['attributes'] as $key => $value)
                                            @if($key != 'updated_at')
                                            <div class="change-item">
                                                <span class="change-key">{{ ucfirst($key) }}:</span>
                                                <span class="change-value">{{ Str::limit($value, 50) }}</span>
                                            </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @empty
                            <div class="no-activity">No activity recorded yet.</div>
                            @endforelse
                        </div>
                    </div>
                </div>{{-- /right column --}}

            </div>{{-- /two-col-layout --}}

        </div>{{-- /main-content --}}
    </div>{{-- /container --}}

    {{-- *** JS — UNCHANGED: copyActivityChanges + dispatch validation *** --}}
    <script>
        function copyActivityChanges(containerId, btnElement) {
            const container = document.getElementById(containerId);
            if (!container) return;
            const items = container.querySelectorAll('.change-item');
            let textToCopy = '';
            items.forEach(item => {
                const key = item.querySelector('.change-key').innerText.trim();
                const val = item.querySelector('.change-value').innerText.trim();
                textToCopy += `${key} ${val}\n`;
            });
            navigator.clipboard.writeText(textToCopy).then(() => {
                const originalHTML = btnElement.innerHTML;
                btnElement.innerHTML = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>';
                setTimeout(() => { btnElement.innerHTML = originalHTML; }, 2000);
            }).catch(err => { console.error('Failed to copy text: ', err); });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const form        = document.getElementById('ticketDetailForm');
            const dispatchBtn = document.getElementById('btnDispatch');
            if (!form || !dispatchBtn) return;

            // Cek apakah form dalam mode Read-Only / Disabled dari Blade/Backend
            const isReadOnly = dispatchBtn.hasAttribute('disabled');

            // Hanya aktifkan validasi form jika tidak dalam mode Read-Only
            if (!isReadOnly) {
                const inputs = form.querySelectorAll('input:not([type="hidden"]), select, textarea');

                function validateFields() {
                    let allFilled = true;
                    inputs.forEach(input => {
                        // Lewati input pencarian atau deskripsi (opsional)
                        if (input.name === 'search' || input.name === 'description') return;
                        if (!input.value || input.value.trim() === '') {
                            allFilled = false;
                        }
                    });
                    dispatchBtn.disabled = !allFilled;
                    if (!allFilled) {
                        dispatchBtn.style.opacity  = '0.5';
                        dispatchBtn.style.cursor   = 'not-allowed';
                        dispatchBtn.title = 'Semua field harus diisi sebelum melakukan Dispatch';
                    } else {
                        dispatchBtn.style.opacity  = '1';
                        dispatchBtn.style.cursor   = 'pointer';
                        dispatchBtn.title = '';
                    }
                }

                // Jalankan saat pertama load
                validateFields();
                // Pantau setiap ada ketikan / perubahan dropdown
                inputs.forEach(input => {
                    input.addEventListener('input',  validateFields);
                    input.addEventListener('change', validateFields);
                });
            }
        });
    </script>

</body>
</html>
