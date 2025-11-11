<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XENA - Ticket Detail</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: #F5F5F5; min-height: 100vh; }
        .header { background: #FFFFFF; padding: 16px 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.08); display: flex; align-items: center; justify-content: space-between; }
        .logo { font-size: 22px; font-weight: 700; background: linear-gradient(90deg,#0C1D25 0%,#1F4A5E 56%,#2D6D8B 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .header-right { display: flex; gap: 15px; align-items: center; }
        .user-avatar { width: 38px; height: 38px; border-radius: 50%; background: linear-gradient(135deg,#1F4A5E 0%,#2D6D8B 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 15px; }
        .container { display: flex; height: calc(100vh - 70px); overflow: hidden; }
        .sidebar { width: 50px; background: #FFFFFF; padding: 15px 0; display: flex; flex-direction: column; align-items: center; gap: 25px; box-shadow: 2px 0 4px rgba(0,0,0,0.05); }
        .sidebar-icon { width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #666; transition: all 0.2s; text-decoration: none; }
        .sidebar-icon:hover, .sidebar-icon.active { color: #1F4A5E; }
        .main-content { flex: 1; padding: 15px 20px; display: flex; flex-direction: column; overflow-y: auto; background: #F5F7FA; }
        .content-card { background: #FFFFFF; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); padding: 20px; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid #E5E7EB; }
        .page-title { font-size: 18px; font-weight: 600; color: #1F2A37; }
        .search-box { display: flex; gap: 8px; align-items: center; }
        .search-input { padding: 7px 12px; border: 1px solid #D1D5DB; border-radius: 6px; font-family: 'Poppins', sans-serif; font-size: 13px; width: 250px; background: #F3F4F6; }
        .search-btn { padding: 7px 14px; background: #1F4A5E; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; }
        .ticket-id { font-size: 24px; font-weight: 700; color: #1F2A37; margin-bottom: 20px; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px; }
        .info-item { display: flex; gap: 10px; font-size: 13px; }
        .info-label { font-weight: 600; color: #1F2A37; min-width: 120px; }
        .info-value { color: #6B7280; }
        .badge-row { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
        .badge { padding: 8px 14px; border-radius: 6px; font-size: 12px; font-weight: 500; display: flex; align-items: center; gap: 6px; background: #F3F4F6; color: #1F2A37; border: 1px solid #D1D5DB; }
        .stats-row { display: flex; gap: 20px; margin-bottom: 20px; padding: 15px; background: #F9FAFB; border-radius: 8px; justify-content: center; }
        .stat-item { text-align: center; }
        .stat-label { font-size: 11px; color: #6B7280; margin-bottom: 4px; }
        .stat-value { font-size: 18px; font-weight: 700; color: #1F2A37; }
        .stat-null { color: #DC2626; }
        .form-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 15px; }
        .form-group { display: flex; flex-direction: column; gap: 4px; }
        .form-label { font-size: 11px; font-weight: 600; color: #1F2A37; }
        .form-select { padding: 8px 10px; border: 1px solid #D1D5DB; border-radius: 6px; font-family: 'Poppins', sans-serif; font-size: 12px; background: #F3F4F6; color: #6B7280; }
        .form-textarea { padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px; font-family: 'Poppins', sans-serif; font-size: 12px; background: #F3F4F6; resize: vertical; min-height: 80px; }
        .button-row { display: flex; gap: 10px; justify-content: center; margin-top: 20px; }
        .btn { padding: 10px 24px; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; border: none; transition: 0.2s; display: flex; align-items: center; gap: 6px; }
        .btn-submit { background: #2C3E50; color: white; }
        .btn-closed { background: #DC2626; color: white; }
        .btn-expired { background: #9CA3AF; color: white; }
        .btn-dispatch { background: #7ED321; color: white; }
        .btn:disabled { opacity: 0.5; cursor: not-allowed; }
        .btn:not(:disabled):hover { opacity: 0.9; transform: translateY(-1px); }
        .alert { padding: 12px 16px; border-radius: 6px; margin-bottom: 15px; font-size: 13px; }
        .alert-success { background: #D1FAE5; color: #065F46; border: 1px solid #10B981; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">XENA</div>
        <div class="header-right">
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
            <div class="content-card">
                <div class="page-header">
                    <div class="page-title">Ticket Handling</div>
                    <div class="search-box">
                        <input type="text" class="search-input" placeholder="Search Pansol">
                        <button class="search-btn">🔍</button>
                    </div>
                </div>

                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif

                <div class="ticket-id">IN{{ str_pad($ticket->idTicket, 8, '0', STR_PAD_LEFT) }}</div>

                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Reported Date :</div>
                        <div class="info-value">{{ $ticket->datereport ? $ticket->datereport->format('Y-m-d / H:i:s') : '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Name :</div>
                        <div class="info-value">{{ $ticket->namacust }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">No.Telp :</div>
                        <div class="info-value">{{ $ticket->notelpCust }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">ID :</div>
                        <div class="info-value">{{ $ticket->idlaporan ?? '-' }}</div>
                    </div>
                </div>

                <div class="badge-row">
                    <div class="badge">📍 {{ $ticket->regional ?? 'REG 1' }} | WITEL : {{ $ticket->witel ?? 'RIKEP' }} | WORKZONE : BTC</div>
                    <div class="badge">👥 OWNER GROUP : DBO</div>
                    <div class="badge">👤 CUSTOMER TYPE : HVC_SILVER</div>
                    <div class="badge">📡 SERVICE TYPE : INTERNET</div>
                    <div class="badge">⏰ PRIORITY DESC : {{ $ticket->reportedpriority ?? '0.97' }}</div>
                </div>

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

                <form method="POST" action="{{ route('agent.ticket.update', $ticket->idTicket) }}">
                    @csrf
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Resume</label>
                            <select name="resume" class="form-select" {{ $ticket->condition == 'Closed' ? 'disabled' : '' }}>
                                <option value="">Select</option>
                                <option value="Resolved" {{ $ticket->resume == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="Pending" {{ $ticket->resume == 'Pending' ? 'selected' : '' }}>Pending</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Classification</label>
                            <select name="klasifikasi" class="form-select" {{ $ticket->condition == 'Closed' ? 'disabled' : '' }}>
                                <option value="">Select</option>
                                <option value="Technical" {{ $ticket->klasifikasi == 'Technical' ? 'selected' : '' }}>Technical</option>
                                <option value="Non-Technical" {{ $ticket->klasifikasi == 'Non-Technical' ? 'selected' : '' }}>Non-Technical</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Topic</label>
                            <select name="topic" class="form-select" {{ $ticket->condition == 'Closed' ? 'disabled' : '' }}>
                                <option value="">Select</option>
                                <option value="Internet Issue" {{ $ticket->topic == 'Internet Issue' ? 'selected' : '' }}>Internet Issue</option>
                                <option value="Phone Issue" {{ $ticket->topic == 'Phone Issue' ? 'selected' : '' }}>Phone Issue</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Topic Detail</label>
                            <select name="topicDetail" class="form-select" {{ $ticket->condition == 'Closed' ? 'disabled' : '' }}>
                                <option value="">Select</option>
                                <option value="Slow Connection" {{ $ticket->topicDetail == 'Slow Connection' ? 'selected' : '' }}>Slow Connection</option>
                                <option value="No Connection" {{ $ticket->topicDetail == 'No Connection' ? 'selected' : '' }}>No Connection</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">No SC/Track ID</label>
                            <select name="noSC" class="form-select" {{ $ticket->condition == 'Closed' ? 'disabled' : '' }}>
                                <option value="">Select</option>
                                <option value="SC001" {{ $ticket->noSC == 'SC001' ? 'selected' : '' }}>SC001</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Status SC/Track ID</label>
                            <select name="statusSC" class="form-select" {{ $ticket->condition == 'Closed' ? 'disabled' : '' }}>
                                <option value="">Select</option>
                                <option value="Open" {{ $ticket->statusSC == 'Open' ? 'selected' : '' }}>Open</option>
                                <option value="Closed" {{ $ticket->statusSC == 'Closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Validasi Close</label>
                            <select name="validateClose" class="form-select" {{ $ticket->condition == 'Closed' ? 'disabled' : '' }}>
                                <option value="">Select</option>
                                <option value="Yes" {{ $ticket->validateClose == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $ticket->validateClose == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Reason Not ODS</label>
                            <select name="reasonnoODS" class="form-select" {{ $ticket->condition == 'Closed' ? 'disabled' : '' }}>
                                <option value="">Select</option>
                                <option value="Customer Request" {{ $ticket->reasonnoODS == 'Customer Request' ? 'selected' : '' }}>Customer Request</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Eskalasi Tiket</label>
                            <select name="eksalasiTicket" class="form-select" {{ $ticket->condition == 'Closed' ? 'disabled' : '' }}>
                                <option value="">Select</option>
                                <option value="Yes" {{ $ticket->eksalasiTicket == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $ticket->eksalasiTicket == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Eskalasi via</label>
                            <select name="eksalasiVia" class="form-select" {{ $ticket->condition == 'Closed' ? 'disabled' : '' }}>
                                <option value="">Select</option>
                                <option value="Email" {{ $ticket->eksalasiVia == 'Email' ? 'selected' : '' }}>Email</option>
                                <option value="Phone" {{ $ticket->eksalasiVia == 'Phone' ? 'selected' : '' }}>Phone</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">PIC</label>
                            <select name="PIC" class="form-select" {{ $ticket->condition == 'Closed' ? 'disabled' : '' }}>
                                <option value="">Select</option>
                                <option value="Agent 1" {{ $ticket->PIC == 'Agent 1' ? 'selected' : '' }}>Agent 1</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Kontak</label>
                            <select name="contact" class="form-select" {{ $ticket->condition == 'Closed' ? 'disabled' : '' }}>
                                <option value="">Select</option>
                                <option value="Phone" {{ $ticket->contact == 'Phone' ? 'selected' : '' }}>Phone</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Respon Backend</label>
                            <select name="responBE" class="form-select" {{ $ticket->condition == 'Closed' ? 'disabled' : '' }}>
                                <option value="">Select</option>
                                <option value="Responded" {{ $ticket->responBE == 'Responded' ? 'selected' : '' }}>Responded</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-textarea" placeholder="Deskripsi" {{ $ticket->condition == 'Closed' ? 'disabled' : '' }}>{{ $ticket->description }}</textarea>
                    </div>

                    <div class="button-row">
                        <button type="submit" class="btn btn-submit" {{ $ticket->condition == 'Closed' ? 'disabled' : '' }}>
                            📧 SUBMIT
                        </button>
                    </div>
                </form>

                <form method="POST" action="{{ route('agent.ticket.status', $ticket->idTicket) }}">
                    @csrf
                    <div class="button-row">
                        <button type="submit" name="action" value="closed" class="btn btn-closed" {{ $ticket->condition == 'Closed' ? 'disabled' : '' }}>
                            CLOSED
                        </button>
                        <button type="submit" name="action" value="expired" class="btn btn-expired" {{ $ticket->condition == 'Closed' ? 'disabled' : '' }}>
                            EXPIRED
                        </button>
                        <button type="submit" name="action" value="dispatch" class="btn btn-dispatch" {{ $ticket->condition == 'Closed' ? 'disabled' : '' }}>
                            DISPATCH
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
