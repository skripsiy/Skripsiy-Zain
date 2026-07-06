{{-- ============================================================
     XENA - Team Leader Ticket Detail
     Layout updated to match Lo-fi wireframe (SAME as agent layout):
       - HEADER  : logo + notification bell + avatar
       - SIDEBAR  : icon-only (Dashboard, Tickets active, Assign, Profile)
       - MAIN    : ← back link → ticket header card → 2-col layout
                    LEFT : form fields (all disabled / read-only for TL) + TL actions
                    RIGHT: activity log (Spatie)
     *** ALL FORM FIELDS, HANDLERS, JS LOGIC, BUTTON GUARDS UNCHANGED ***
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
        /* ── Base ── */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: #F5F5F5; min-height: 100vh; }

        /* ── HEADER ── */
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

        /* ── CONTAINER / SIDEBAR ── */
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

        /* ── MAIN CONTENT ── */
        .main-content {
            flex: 1;
            padding: 20px 24px;
            overflow-y: auto;
            background: #F5F7FA;
        }

        /* ── Back link ── */
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

        /* ── Alerts (logic unchanged) ── */
        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 14px; font-size: 13px; }
        .alert-success { background: #D1FAE5; color: #065F46; border: 1px solid #10B981; }

        /* ── TICKET HEADER CARD ── */
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
        .ticket-customer { font-size: 13px; color: #718096; }
        .badge-group { display: flex; gap: 8px; }
        .badge-status, .badge-urgency {
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }

        /* 4-col info grid */
        .ticket-info-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-top: 12px;
        }
        .ticket-info-label {
            font-size: 10px; font-weight: 600; color: #999;
            text-transform: uppercase; margin-bottom: 3px; letter-spacing: 0.4px;
        }
        .ticket-info-value { font-size: 13px; color: #1F2A37; font-weight: 500; }

        /* Stats row */
        .stats-row {
            display: flex; gap: 20px; margin-top: 14px;
            padding: 12px 16px; background: #F9FAFB; border-radius: 8px;
            justify-content: center;
            align-items: center;
        }
        .stat-item { text-align: center; }
        .stat-label { font-size: 11px; color: #6B7280; margin-bottom: 2px; }
        .stat-value { font-size: 16px; font-weight: 700; color: #1F2A37; }
        .stat-null { color: #E53E3E; }

        /* ── 2-col layout ── */
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
        .card-title { font-size: 14px; font-weight: 700; color: #1F2A37; margin-bottom: 16px; }

        /* ── Form (TL: all disabled) ── */
        .form-grid   { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; margin-bottom: 14px; }
        .form-grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 14px; }
        .form-group  { display: flex; flex-direction: column; gap: 4px; }
        .form-label  { font-size: 11px; font-weight: 600; color: #1F2A37; }
        .form-select {
            padding: 8px 10px;
            border: 1px solid #D1D5DB;
            border-radius: 6px;
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
            background: #F3F4F6;
            color: #374151;
        }
        .form-textarea {
            padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px;
            font-family: 'Poppins', sans-serif; font-size: 12px;
            background: #F3F4F6; resize: vertical; min-height: 80px; width: 100%;
        }

        /* ── Action buttons ── */
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
        .btn:disabled { opacity: 0.5; cursor: not-allowed; }
        .btn:not(:disabled):hover { opacity: 0.9; transform: translateY(-1px); }

        /* Quick actions section */
        .quick-actions-section {
            margin-top: 18px; padding-top: 16px;
            border-top: 1px solid #F0F0F0;
        }
        .quick-actions-title { font-size: 12px; font-weight: 700; color: #333; margin-bottom: 10px; }
        .quick-actions-row   { display: flex; gap: 8px; flex-wrap: wrap; }

        /* ── Activity Log ── */
        .activity-log-panel { max-height: calc(100vh - 280px); overflow-y: auto; }
        .activity-timeline  { margin-top: 4px; padding-left: 10px; }
        .activity-item {
            position: relative; padding-left: 20px; padding-bottom: 18px;
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
        .activity-user   { font-weight: 600; color: #1F2A37; }
        .activity-action { color: #6B7280; }
        .activity-time   { color: #9CA3AF; font-size: 10px; margin-left: auto; }
        .activity-changes {
            background: #F9FAFB; padding: 10px 12px; border-radius: 6px;
            font-size: 11px; margin-top: 4px; position: relative;
        }
        .change-item  { display: flex; gap: 6px; margin-bottom: 2px; }
        .change-key   { font-weight: 600; color: #4B5563; }
        .change-value { color: #1F2A37; }
        .no-activity  { color: #9CA3AF; font-size: 12px; font-style: italic; }
    </style>
</head>
<body>

    {{-- ======================================================
         HEADER
         ====================================================== --}}
    <div class="header">
        <div class="logo">XENA</div>
        <div class="header-right">
            <x-notification-bell />
            <div class="user-avatar">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
        </div>
    </div>

    <div class="container">

        {{-- ======================================================
             SIDEBAR  — TL has 4 icons (unchanged order)
             ====================================================== --}}
        <div class="sidebar">
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
        </div>

        {{-- ======================================================
             MAIN CONTENT
             ====================================================== --}}
        <div class="main-content">

            {{-- Back link --}}
            <a href="{{ route('team-leader.tickets') }}" class="back-link">
                ← Back to Ticket List
            </a>

            {{-- Flash messages (logic unchanged) --}}
            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- Read-only banner (logic unchanged) --}}
            @if(in_array($ticket->condition, ['Closed', 'Dispatched', 'EXPIRED', 'Saltik']))
            <div class="alert" style="background:#FEF3C7;color:#92400E;border:1px solid #F59E0B;">
                ⚠️ Tiket ini sudah dalam status <strong>{{ strtoupper($ticket->condition) }}</strong>. Aksi perubahan status dinonaktifkan.
            </div>
            @endif

            {{-- ==================================================
                 TICKET HEADER CARD
                 ================================================== --}}
            <div class="ticket-header-card">
                <div class="ticket-header-top">
                    {{-- Left: code + customer --}}
                    <div>
                        <div class="ticket-code">TK{{ str_pad($ticket->idTicket, 6, '0', STR_PAD_LEFT) }}</div>
                        <div class="ticket-customer">
                            {{ $ticket->namacust ?? '-' }} &mdash; {{ $ticket->notelpCust ?? '-' }}
                        </div>
                    </div>
                    {{-- Right: status + priority badges --}}
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

                {{-- 4-col info grid --}}
                <div class="ticket-info-grid">
                    <div>
                        <div class="ticket-info-label">Date Report</div>
                        <div class="ticket-info-value">
                            {{ $ticket->datereport ? \Carbon\Carbon::parse($ticket->datereport)->format('Y-m-d H:i') : '-' }}
                        </div>
                    </div>
                    <div>
                        <div class="ticket-info-label">Regional</div>
                        <div class="ticket-info-value">{{ $ticket->regional ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="ticket-info-label">Witel</div>
                        <div class="ticket-info-value">{{ $ticket->witel ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="ticket-info-label">Assigned To</div>
                        <div class="ticket-info-value">{{ $ticket->assignedTo?->name ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="ticket-info-label">ID Laporan</div>
                        <div class="ticket-info-value">{{ $ticket->idlaporan ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="ticket-info-label">No Tiket</div>
                        <div class="ticket-info-value">{{ $ticket->idTicket ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="ticket-info-label">Lapul</div>
                        <div class="ticket-info-value">{{ $ticket->lapul ?? 0 }}</div>
                    </div>
                    <div>
                        <div class="ticket-info-label">Gaul</div>
                        <div class="ticket-info-value">{{ $ticket->gaul ?? 0 }}</div>
                    </div>
                </div>

                {{-- Stats row --}}
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
                 2-col layout
                 ================================================== --}}
            <div class="two-col-layout">

                {{-- ── LEFT: form fields (read-only for TL) + TL actions ── --}}
                <div class="content-card">
                    <div class="card-title">Ticket Content (Read-only)</div>

                    {{-- *** Form 1: field viewer — fields are disabled for TL (unchanged) *** --}}
                    <form method="POST" action="{{ route('team-leader.ticket.update', $ticket->idTicket) }}">
                        @csrf

                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Resume</label>
                                <select name="resume" class="form-select" disabled>
                                    <option value="">Select</option>
                                    <option value="Resolved" {{ $ticket->resume == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                                    <option value="Pending"  {{ $ticket->resume == 'Pending'  ? 'selected' : '' }}>Pending</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Classification</label>
                                <select name="klasifikasi" class="form-select" disabled>
                                    <option value="">Select</option>
                                    <option value="Technical"     {{ $ticket->klasifikasi == 'Technical'     ? 'selected' : '' }}>Technical</option>
                                    <option value="Non-Technical" {{ $ticket->klasifikasi == 'Non-Technical' ? 'selected' : '' }}>Non-Technical</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Topic</label>
                                <select name="topic" class="form-select" disabled>
                                    <option value="">Select</option>
                                    <option value="Internet Issue" {{ $ticket->topic == 'Internet Issue' ? 'selected' : '' }}>Internet Issue</option>
                                    <option value="Phone Issue"    {{ $ticket->topic == 'Phone Issue'    ? 'selected' : '' }}>Phone Issue</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Topic Detail</label>
                                <select name="topicDetail" class="form-select" disabled>
                                    <option value="">Select</option>
                                    <option value="Slow Connection" {{ $ticket->topicDetail == 'Slow Connection' ? 'selected' : '' }}>Slow Connection</option>
                                    <option value="No Connection"   {{ $ticket->topicDetail == 'No Connection'   ? 'selected' : '' }}>No Connection</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-grid-4">
                            <div class="form-group">
                                <label class="form-label">No SC/Track ID</label>
                                <select name="noSC" class="form-select" disabled>
                                    <option value="">Select</option>
                                    <option value="SC001" {{ $ticket->noSC == 'SC001' ? 'selected' : '' }}>SC001</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Status SC/Track ID</label>
                                <select name="statusSC" class="form-select" disabled>
                                    <option value="">Select</option>
                                    <option value="Open"   {{ $ticket->statusSC == 'Open'   ? 'selected' : '' }}>Open</option>
                                    <option value="Closed" {{ $ticket->statusSC == 'Closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Validasi Close</label>
                                <select name="validateClose" class="form-select" disabled>
                                    <option value="">Select</option>
                                    <option value="Yes" {{ $ticket->validateClose == 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No"  {{ $ticket->validateClose == 'No'  ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Reason Not ODS</label>
                                <select name="reasonnoODS" class="form-select" disabled>
                                    <option value="">Select</option>
                                    <option value="Customer Request" {{ $ticket->reasonnoODS == 'Customer Request' ? 'selected' : '' }}>Customer Request</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Eskalasi Tiket</label>
                                <select name="eksalasiTicket" class="form-select" disabled>
                                    <option value="">Select</option>
                                    <option value="Yes" {{ $ticket->eksalasiTicket == 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No"  {{ $ticket->eksalasiTicket == 'No'  ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Eskalasi via</label>
                                <select name="eksalasiVia" class="form-select" disabled>
                                    <option value="">Select</option>
                                    <option value="Email" {{ $ticket->eksalasiVia == 'Email' ? 'selected' : '' }}>Email</option>
                                    <option value="Phone" {{ $ticket->eksalasiVia == 'Phone' ? 'selected' : '' }}>Phone</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">PIC</label>
                                <select name="PIC" class="form-select" disabled>
                                    <option value="">Select</option>
                                    <option value="Agent 1" {{ $ticket->PIC == 'Agent 1' ? 'selected' : '' }}>Agent 1</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Kontak</label>
                                <select name="contact" class="form-select" disabled>
                                    <option value="">Select</option>
                                    <option value="Phone" {{ $ticket->contact == 'Phone' ? 'selected' : '' }}>Phone</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom:14px;">
                            <label class="form-label">Respon Backend</label>
                            <select name="responBE" class="form-select" disabled>
                                <option value="">Select</option>
                                <option value="Responded" {{ $ticket->responBE == 'Responded' ? 'selected' : '' }}>Responded</option>
                            </select>
                        </div>

                        <div class="form-group" style="margin-bottom:14px;">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description" class="form-textarea" placeholder="Deskripsi" disabled>{{ $ticket->detailticket }}</textarea>
                        </div>

                        {{-- Attach button (disabled for TL) — updated for design consistency --}}
                        <div style="display:flex;justify-content:flex-end;">
                            <label class="btn btn-submit" style="cursor: not-allowed; display: inline-flex; align-items: center; gap: 6px; margin: 0; background: #1F4A5E; color: white; opacity: 0.5; pointer-events: none;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
                                Attach (PDF, PNG, JPG)
                                <input type="file" name="attachment" style="display: none;" disabled>
                            </label>
                        </div>
                    </form>

                    {{-- *** Form 2: TL status actions — logic/guards unchanged *** --}}
                    <form method="POST" action="{{ route('team-leader.ticket.status', $ticket->idTicket) }}">
                        @csrf
                        <div class="quick-actions-section">
                            <div class="quick-actions-title">Quick Actions (Team Leader)</div>
                            <div class="quick-actions-row">
                                <button type="submit" name="action" value="closed"
                                    class="btn btn-closed"
                                    {{ in_array($ticket->condition, ['Closed', 'Dispatched', 'EXPIRED', 'Saltik']) ? 'disabled' : '' }}>
                                    CLOSED
                                </button>
                                <button type="submit" name="action" value="saltik"
                                    class="btn btn-saltik"
                                    {{ in_array($ticket->condition, ['Closed', 'Dispatched', 'EXPIRED', 'Saltik']) ? 'disabled' : '' }}>
                                    SALTIK
                                </button>
                                <button type="submit" name="action" value="expired"
                                    class="btn btn-expired"
                                    {{ in_array($ticket->condition, ['Closed', 'Dispatched', 'EXPIRED', 'Saltik']) ? 'disabled' : '' }}>
                                    EXPIRED
                                </button>
                                <button type="submit" name="action" value="dispatch"
                                    class="btn btn-dispatch"
                                    {{ in_array($ticket->condition, ['Closed', 'Dispatched', 'EXPIRED', 'Saltik']) ? 'disabled' : '' }}>
                                    DISPATCH
                                </button>
                            </div>
                        </div>
                    </form>
                </div>{{-- /left column --}}

                {{-- ── RIGHT: Activity Log ── --}}
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
                                    <div class="activity-changes">
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

</body>
</html>
