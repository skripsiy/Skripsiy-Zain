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
        .info-label { font-weight: 600; color: #1F2A37; min-width: 140px; display: flex; justify-content: space-between; }
        .info-label::after { content: ":"; }
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
                        <button class="search-btn">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        </button>
                    </div>
                </div>

                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
                
                @if(session('error'))
                <div class="alert alert-danger" style="background: #FEE2E2; color: #991B1B; border: 1px solid #EF4444; padding: 12px 16px; border-radius: 6px; margin-bottom: 15px; font-size: 13px;">
                    {{ session('error') }}
                </div>
                @endif
                
                @if(!$canEdit)
                <div class="alert alert-warning" style="background: #FEF3C7; color: #92400E; border: 1px solid #F59E0B; padding: 12px 16px; border-radius: 6px; margin-bottom: 15px; font-size: 13px;">
                    ⚠️ Anda sedang dalam mode <strong>Hanya Lihat (Read-Only)</strong> karena tiket ini tidak di-assign kepada Anda. Anda tidak dapat melakukan perubahan atau update status.
                </div>
                @endif

                <div class="ticket-id">IN{{ str_pad($ticket->idTicket, 8, '0', STR_PAD_LEFT) }}</div>

                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Reported Date</div>
                        <div class="info-value">{{ $ticket->datereport ? $ticket->datereport->format('Y-m-d / H:i:s') : '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Name</div>
                        <div class="info-value">{{ $ticket->namacust ?? '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">No.Telp</div>
                        <div class="info-value">{{ $ticket->notelpCust ?? '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">ID</div>
                        <div class="info-value">{{ $ticket->idlaporan ?? '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">No Tiket</div>
                        <div class="info-value">{{ $ticket->idTicket ?? '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Service No</div>
                        <div class="info-value">{{ $ticket->noSC ?? '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Usia Tiket (TTR)</div>
                        <div class="info-value">{{ $ticket->datereport ? $ticket->datereport->diff(\Carbon\Carbon::now())->format('%d Hari, %h Jam') : '-' }}</div>
                    </div>
                </div>

                <div class="badge-row">
                    <div class="badge">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        {{ $ticket->regional ?? 'REG 1' }} | WITEL : {{ $ticket->witel ?? 'RIKEP' }} | WORKZONE : BTC
                    </div>
                    <div class="badge">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        OWNER GROUP : DBO
                    </div>
                    <div class="badge">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        CUSTOMER TYPE : HVC_SILVER
                    </div>
                    <div class="badge">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="2"></circle><path d="M16.24 7.76a6 6 0 0 1 0 8.49m-8.48-.01a6 6 0 0 1 0-8.49m11.31-2.82a10 10 0 0 1 0 14.14m-14.14 0a10 10 0 0 1 0-14.14"></path></svg>
                        SERVICE TYPE : INTERNET
                    </div>
                    <div class="badge">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        PRIORITY DESC : {{ $ticket->reportedpriority ?? '0.97' }}
                    </div>
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

                <form method="POST" action="{{ route('agent.ticket.update', $ticket->idTicket) }}" id="ticketDetailForm">
                    @csrf
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Resume</label>
                            <input type="text" name="resume" class="form-select" value="{{ $ticket->resume }}" placeholder="Masukkan resume" {{ (!$canEdit || $ticket->condition == 'Closed') ? 'disabled' : '' }}>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Hasil Inputan/ Hasil Pengecekan</label>
                            <input type="text" name="hasil_pengecekan" class="form-select" value="{{ $ticket->hasil_pengecekan }}" placeholder="Masukkan hasil pengecekan" {{ (!$canEdit || $ticket->condition == 'Closed') ? 'disabled' : '' }}>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Resolved by Agent</label>
                            <select name="resolved_by_agent" class="form-select" {{ (!$canEdit || $ticket->condition == 'Closed') ? 'disabled' : '' }}>
                                <option value="">Select</option>
                                <option value="Succes Resolved" {{ $ticket->resolved_by_agent == 'Succes Resolved' ? 'selected' : '' }}>Succes Resolved</option>
                                <option value="Gagal Resolved" {{ $ticket->resolved_by_agent == 'Gagal Resolved' ? 'selected' : '' }}>Gagal Resolved</option>
                                <option value="No Action" {{ $ticket->resolved_by_agent == 'No Action' ? 'selected' : '' }}>No Action</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Eskalasi via</label>
                            <select name="eksalasiVia" class="form-select" {{ (!$canEdit || $ticket->condition == 'Closed') ? 'disabled' : '' }}>
                                <option value="">Select</option>
                                <option value="Telp" {{ $ticket->eksalasiVia == 'Telp' ? 'selected' : '' }}>Telp</option>
                                <option value="Telegram" {{ $ticket->eksalasiVia == 'Telegram' ? 'selected' : '' }}>Telegram</option>
                                <option value="WA" {{ $ticket->eksalasiVia == 'WA' ? 'selected' : '' }}>WA</option>
                                <option value="Grup internal" {{ $ticket->eksalasiVia == 'Grup internal' ? 'selected' : '' }}>Grup internal</option>
                                <option value="Grup Solver" {{ $ticket->eksalasiVia == 'Grup Solver' ? 'selected' : '' }}>Grup Solver</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Status Call (Kontak)</label>
                            <select name="contact" class="form-select" {{ (!$canEdit || $ticket->condition == 'Closed') ? 'disabled' : '' }}>
                                <option value="">Select</option>
                                <option value="Contacted" {{ $ticket->contact == 'Contacted' ? 'selected' : '' }}>Contacted</option>
                                <option value="Rna" {{ $ticket->contact == 'Rna' ? 'selected' : '' }}>Rna</option>
                                <option value="Busy" {{ $ticket->contact == 'Busy' ? 'selected' : '' }}>Busy</option>
                                <option value="Rejected" {{ $ticket->contact == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="Mailbox" {{ $ticket->contact == 'Mailbox' ? 'selected' : '' }}>Mailbox</option>
                                <option value="Rechedule" {{ $ticket->contact == 'Rechedule' ? 'selected' : '' }}>Rechedule</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Reason Not ODS</label>
                            <select name="reasonnoODS" class="form-select" {{ (!$canEdit || $ticket->condition == 'Closed') ? 'disabled' : '' }}>
                                <option value="">Select</option>
                                <option value="PEOPLE - Menunggu Konfirmasi Dari Pelanggan" {{ $ticket->reasonnoODS == 'PEOPLE - Menunggu Konfirmasi Dari Pelanggan' ? 'selected' : '' }}>PEOPLE - Menunggu Konfirmasi Dari Pelanggan</option>
                                <option value="PEOPLE - Pelanggan Belum Melunasi Tagihan" {{ $ticket->reasonnoODS == 'PEOPLE - Pelanggan Belum Melunasi Tagihan' ? 'selected' : '' }}>PEOPLE - Pelanggan Belum Melunasi Tagihan</option>
                                <option value="PEOPLE - Pelanggan Belum Upload Berkas" {{ $ticket->reasonnoODS == 'PEOPLE - Pelanggan Belum Upload Berkas' ? 'selected' : '' }}>PEOPLE - Pelanggan Belum Upload Berkas</option>
                                <option value="PEOPLE - Pelanggan Reschedule" {{ $ticket->reasonnoODS == 'PEOPLE - Pelanggan Reschedule' ? 'selected' : '' }}>PEOPLE - Pelanggan Reschedule</option>
                                <option value="PEOPLE - Pelanggan Sulit Dihubungi" {{ $ticket->reasonnoODS == 'PEOPLE - Pelanggan Sulit Dihubungi' ? 'selected' : '' }}>PEOPLE - Pelanggan Sulit Dihubungi</option>
                                <option value="PEOPLE - Pelanggan Tetap Ingin Di visit" {{ $ticket->reasonnoODS == 'PEOPLE - Pelanggan Tetap Ingin Di visit' ? 'selected' : '' }}>PEOPLE - Pelanggan Tetap Ingin Di visit</option>
                                <option value="PEOPLE - Pelanggan Tidak Ada Dilokasi" {{ $ticket->reasonnoODS == 'PEOPLE - Pelanggan Tidak Ada Dilokasi' ? 'selected' : '' }}>PEOPLE - Pelanggan Tidak Ada Dilokasi</option>
                                <option value="PEOPLE - Teknisi Full Order" {{ $ticket->reasonnoODS == 'PEOPLE - Teknisi Full Order' ? 'selected' : '' }}>PEOPLE - Teknisi Full Order</option>
                                <option value="PEOPLE - Unit Solver No Respon" {{ $ticket->reasonnoODS == 'PEOPLE - Unit Solver No Respon' ? 'selected' : '' }}>PEOPLE - Unit Solver No Respon</option>
                                <option value="PEOPLE - Mapping KIP Tidak Sesuai" {{ $ticket->reasonnoODS == 'PEOPLE - Mapping KIP Tidak Sesuai' ? 'selected' : '' }}>PEOPLE - Mapping KIP Tidak Sesuai</option>
                                <option value="PROSES - Status Order Tidak Sesuai" {{ $ticket->reasonnoODS == 'PROSES - Status Order Tidak Sesuai' ? 'selected' : '' }}>PROSES - Status Order Tidak Sesuai</option>
                                <option value="PROSES - Workzone Kosong" {{ $ticket->reasonnoODS == 'PROSES - Workzone Kosong' ? 'selected' : '' }}>PROSES - Workzone Kosong</option>
                                <option value="PROSES - Ada Permintaan Berlangsung" {{ $ticket->reasonnoODS == 'PROSES - Ada Permintaan Berlangsung' ? 'selected' : '' }}>PROSES - Ada Permintaan Berlangsung</option>
                                <option value="PROSES - Alamat Tidak Sesuai" {{ $ticket->reasonnoODS == 'PROSES - Alamat Tidak Sesuai' ? 'selected' : '' }}>PROSES - Alamat Tidak Sesuai</option>
                                <option value="PROSES - Alpro Terpasang Namun Order Belum Complete di DSC" {{ $ticket->reasonnoODS == 'PROSES - Alpro Terpasang Namun Order Belum Complete di DSC' ? 'selected' : '' }}>PROSES - Alpro Terpasang Namun Order Belum Complete di DSC</option>
                                <option value="PROSES - Bencana Alam" {{ $ticket->reasonnoODS == 'PROSES - Bencana Alam' ? 'selected' : '' }}>PROSES - Bencana Alam</option>
                                <option value="PROSES - Cuaca Tidak Mendukung" {{ $ticket->reasonnoODS == 'PROSES - Cuaca Tidak Mendukung' ? 'selected' : '' }}>PROSES - Cuaca Tidak Mendukung</option>
                                <option value="PROSES - Data Pelanggan Termapping Di Indhihome Lain" {{ $ticket->reasonnoODS == 'PROSES - Data Pelanggan Termapping Di Indhihome Lain' ? 'selected' : '' }}>PROSES - Data Pelanggan Termapping Di Indhihome Lain</option>
                                <option value="PROSES - Data Pelanggan Tidak Sesuai" {{ $ticket->reasonnoODS == 'PROSES - Data Pelanggan Tidak Sesuai' ? 'selected' : '' }}>PROSES - Data Pelanggan Tidak Sesuai</option>
                                <option value="PROSES - Datek Tidak Sesuai" {{ $ticket->reasonnoODS == 'PROSES - Datek Tidak Sesuai' ? 'selected' : '' }}>PROSES - Datek Tidak Sesuai</option>
                                <option value="PROSES - Gagal Resume Order" {{ $ticket->reasonnoODS == 'PROSES - Gagal Resume Order' ? 'selected' : '' }}>PROSES - Gagal Resume Order</option>
                                <option value="PROSES - Gagal Suspend Order" {{ $ticket->reasonnoODS == 'PROSES - Gagal Suspend Order' ? 'selected' : '' }}>PROSES - Gagal Suspend Order</option>
                                <option value="PROSES - Gagal Migrasi Order" {{ $ticket->reasonnoODS == 'PROSES - Gagal Migrasi Order' ? 'selected' : '' }}>PROSES - Gagal Migrasi Order</option>
                                <option value="PROSES - GAMAS" {{ $ticket->reasonnoODS == 'PROSES - GAMAS' ? 'selected' : '' }}>PROSES - GAMAS</option>
                                <option value="PROSES - Jaringan Tidak Tersedia di Lokasi" {{ $ticket->reasonnoODS == 'PROSES - Jaringan Tidak Tersedia di Lokasi' ? 'selected' : '' }}>PROSES - Jaringan Tidak Tersedia di Lokasi</option>
                                <option value="PROSES - Ketersediaan Stock Perangkat" {{ $ticket->reasonnoODS == 'PROSES - Ketersediaan Stock Perangkat' ? 'selected' : '' }}>PROSES - Ketersediaan Stock Perangkat</option>
                                <option value="PROSES - Link Upload Paperless Berkendala" {{ $ticket->reasonnoODS == 'PROSES - Link Upload Paperless Berkendala' ? 'selected' : '' }}>PROSES - Link Upload Paperless Berkendala</option>
                                <option value="PROSES - ODP Belum Golive" {{ $ticket->reasonnoODS == 'PROSES - ODP Belum Golive' ? 'selected' : '' }}>PROSES - ODP Belum Golive</option>
                                <option value="PROSES - ODP Full" {{ $ticket->reasonnoODS == 'PROSES - ODP Full' ? 'selected' : '' }}>PROSES - ODP Full</option>
                                <option value="PROSES - ODP Redaman Tinggi" {{ $ticket->reasonnoODS == 'PROSES - ODP Redaman Tinggi' ? 'selected' : '' }}>PROSES - ODP Redaman Tinggi</option>
                                <option value="PROSES - Paket Tidak Tersedia/Expired" {{ $ticket->reasonnoODS == 'PROSES - Paket Tidak Tersedia/Expired' ? 'selected' : '' }}>PROSES - Paket Tidak Tersedia/Expired</option>
                                <option value="PROSES - Paperless Invalid Identity" {{ $ticket->reasonnoODS == 'PROSES - Paperless Invalid Identity' ? 'selected' : '' }}>PROSES - Paperless Invalid Identity</option>
                                <option value="PROSES - Pembayaran Gagal" {{ $ticket->reasonnoODS == 'PROSES - Pembayaran Gagal' ? 'selected' : '' }}>PROSES - Pembayaran Gagal</option>
                                <option value="PROSES - Pengajuan Material Pendukung" {{ $ticket->reasonnoODS == 'PROSES - Pengajuan Material Pendukung' ? 'selected' : '' }}>PROSES - Pengajuan Material Pendukung</option>
                                <option value="PROSES - Pengecekan Ketersediaan Jaringan" {{ $ticket->reasonnoODS == 'PROSES - Pengecekan Ketersediaan Jaringan' ? 'selected' : '' }}>PROSES - Pengecekan Ketersediaan Jaringan</option>
                                <option value="PROSES - Quota Masih Dalam Pengecekan" {{ $ticket->reasonnoODS == 'PROSES - Quota Masih Dalam Pengecekan' ? 'selected' : '' }}>PROSES - Quota Masih Dalam Pengecekan</option>
                                <option value="PROSES - Reguler - Kompensasi SLA" {{ $ticket->reasonnoODS == 'PROSES - Reguler - Kompensasi SLA' ? 'selected' : '' }}>PROSES - Reguler - Kompensasi SLA</option>
                                <option value="PROSES - SMOOA - Nomor tidak sesuai Kriteria" {{ $ticket->reasonnoODS == 'PROSES - SMOOA - Nomor tidak sesuai Kriteria' ? 'selected' : '' }}>PROSES - SMOOA - Nomor tidak sesuai Kriteria</option>
                                <option value="PROSES - Terkendala Perizinan" {{ $ticket->reasonnoODS == 'PROSES - Terkendala Perizinan' ? 'selected' : '' }}>PROSES - Terkendala Perizinan</option>
                                <option value="PROSES - Tidak Mendapatkan Kode Virtual Account" {{ $ticket->reasonnoODS == 'PROSES - Tidak Mendapatkan Kode Virtual Account' ? 'selected' : '' }}>PROSES - Tidak Mendapatkan Kode Virtual Account</option>
                                <option value="PROSES - Belum dicaring Status Tiket Bisa diclosed" {{ $ticket->reasonnoODS == 'PROSES - Belum dicaring Status Tiket Bisa diclosed' ? 'selected' : '' }}>PROSES - Belum dicaring Status Tiket Bisa diclosed</option>
                                <option value="PROSES - Sudah dicaring namun belum ada respon pelanggan" {{ $ticket->reasonnoODS == 'PROSES - Sudah dicaring namun belum ada respon pelanggan' ? 'selected' : '' }}>PROSES - Sudah dicaring namun belum ada respon pelanggan</option>
                                <option value="PROSES - Sudah dicaring namun pelanggan tidak bersedia di closed" {{ $ticket->reasonnoODS == 'PROSES - Sudah dicaring namun pelanggan tidak bersedia di closed' ? 'selected' : '' }}>PROSES - Sudah dicaring namun pelanggan tidak bersedia di closed</option>
                                <option value="PROSES - Mandeg UFO" {{ $ticket->reasonnoODS == 'PROSES - Mandeg UFO' ? 'selected' : '' }}>PROSES - Mandeg UFO</option>
                                <option value="PROSES - Mandeg IT" {{ $ticket->reasonnoODS == 'PROSES - Mandeg IT' ? 'selected' : '' }}>PROSES - Mandeg IT</option>
                                <option value="PROSES - Mandeg Fallout OTT" {{ $ticket->reasonnoODS == 'PROSES - Mandeg Fallout OTT' ? 'selected' : '' }}>PROSES - Mandeg Fallout OTT</option>
                                <option value="PROSES - Mandeg Fallout Request BI" {{ $ticket->reasonnoODS == 'PROSES - Mandeg Fallout Request BI' ? 'selected' : '' }}>PROSES - Mandeg Fallout Request BI</option>
                                <option value="PROSES - Mandeg Cancel/Input" {{ $ticket->reasonnoODS == 'PROSES - Mandeg Cancel/Input' ? 'selected' : '' }}>PROSES - Mandeg Cancel/Input</option>
                                <option value="TOOLS - Labor WO belum Completed" {{ $ticket->reasonnoODS == 'TOOLS - Labor WO belum Completed' ? 'selected' : '' }}>TOOLS - Labor WO belum Completed</option>
                                <option value="TOOLS - UNSPEC" {{ $ticket->reasonnoODS == 'TOOLS - UNSPEC' ? 'selected' : '' }}>TOOLS - UNSPEC</option>
                                <option value="TOOLS - Tidak Bisa Takeownership" {{ $ticket->reasonnoODS == 'TOOLS - Tidak Bisa Takeownership' ? 'selected' : '' }}>TOOLS - Tidak Bisa Takeownership</option>
                                <option value="TOOLS - Tidak Bisa Takeownership Dispatch Tiket" {{ $ticket->reasonnoODS == 'TOOLS - Tidak Bisa Takeownership Dispatch Tiket' ? 'selected' : '' }}>TOOLS - Tidak Bisa Takeownership Dispatch Tiket</option>
                                <option value="TOOLS - Tidak Bisa Takeownership Closed Tiket" {{ $ticket->reasonnoODS == 'TOOLS - Tidak Bisa Takeownership Closed Tiket' ? 'selected' : '' }}>TOOLS - Tidak Bisa Takeownership Closed Tiket</option>
                                <option value="TOOLS - Status SC Permintaan Di Insera / DSC Tidak Sesuai" {{ $ticket->reasonnoODS == 'TOOLS - Status SC Permintaan Di Insera / DSC Tidak Sesuai' ? 'selected' : '' }}>TOOLS - Status SC Permintaan Di Insera / DSC Tidak Sesuai</option>
                                <option value="TOOLS - Owner Grup Tidak Berubah" {{ $ticket->reasonnoODS == 'TOOLS - Owner Grup Tidak Berubah' ? 'selected' : '' }}>TOOLS - Owner Grup Tidak Berubah</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Respon Backend</label>
                            <select name="responBE" class="form-select" {{ (!$canEdit || $ticket->condition == 'Closed') ? 'disabled' : '' }}>
                                <option value="">Select</option>
                                <option value="Balas chat & Memberi Update Progress" {{ $ticket->responBE == 'Balas chat & Memberi Update Progress' ? 'selected' : '' }}>Balas chat & Memberi Update Progress</option>
                                <option value="Hanya di Read saja" {{ $ticket->responBE == 'Hanya di Read saja' ? 'selected' : '' }}>Hanya di Read saja</option>
                                <option value="Membalas chat tapi lama (Lowrespon)" {{ $ticket->responBE == 'Membalas chat tapi lama (Lowrespon)' ? 'selected' : '' }}>Membalas chat tapi lama (Lowrespon)</option>
                                <option value="Hanya Membalas Chat (Fast Respon)" {{ $ticket->responBE == 'Hanya Membalas Chat (Fast Respon)' ? 'selected' : '' }}>Hanya Membalas Chat (Fast Respon)</option>
                                <option value="Menjawab Telp dan memberi Update" {{ $ticket->responBE == 'Menjawab Telp dan memberi Update' ? 'selected' : '' }}>Menjawab Telp dan memberi Update</option>
                                <option value="Respon Menolak Kordinasi" {{ $ticket->responBE == 'Respon Menolak Kordinasi' ? 'selected' : '' }}>Respon Menolak Kordinasi</option>
                                <option value="Tidak menerima koord C4" {{ $ticket->responBE == 'Tidak menerima koord C4' ? 'selected' : '' }}>Tidak menerima koord C4</option>
                                <option value="Tanpa Koordinasi" {{ $ticket->responBE == 'Tanpa Koordinasi' ? 'selected' : '' }}>Tanpa Koordinasi</option>
                                <option value="Tidak Membalas Chat" {{ $ticket->responBE == 'Tidak Membalas Chat' ? 'selected' : '' }}>Tidak Membalas Chat</option>
                                <option value="Tidak Menjawab Telp" {{ $ticket->responBE == 'Tidak Menjawab Telp' ? 'selected' : '' }}>Tidak Menjawab Telp</option>
                                <option value="Menjawab Telp Saja" {{ $ticket->responBE == 'Menjawab Telp Saja' ? 'selected' : '' }}>Menjawab Telp Saja</option>
                                <option value="Membalas chat namun tidak memberi update progress" {{ $ticket->responBE == 'Membalas chat namun tidak memberi update progress' ? 'selected' : '' }}>Membalas chat namun tidak memberi update progress</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Classification</label>
                            <select name="klasifikasi" class="form-select" {{ (!$canEdit || $ticket->condition == 'Closed') ? 'disabled' : '' }}>
                                <option value="">Select</option>
                                <option value="Technical" {{ $ticket->klasifikasi == 'Technical' ? 'selected' : '' }}>Technical</option>
                                <option value="Non-Technical" {{ $ticket->klasifikasi == 'Non-Technical' ? 'selected' : '' }}>Non-Technical</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Topic</label>
                            <select name="topic" class="form-select" {{ (!$canEdit || $ticket->condition == 'Closed') ? 'disabled' : '' }}>
                                <option value="">Select</option>
                                <option value="Internet Issue" {{ $ticket->topic == 'Internet Issue' ? 'selected' : '' }}>Internet Issue</option>
                                <option value="Phone Issue" {{ $ticket->topic == 'Phone Issue' ? 'selected' : '' }}>Phone Issue</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Topic Detail</label>
                            <select name="topicDetail" class="form-select" {{ (!$canEdit || $ticket->condition == 'Closed') ? 'disabled' : '' }}>
                                <option value="">Select</option>
                                <option value="Slow Connection" {{ $ticket->topicDetail == 'Slow Connection' ? 'selected' : '' }}>Slow Connection</option>
                                <option value="No Connection" {{ $ticket->topicDetail == 'No Connection' ? 'selected' : '' }}>No Connection</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">No SC/Track ID</label>
                            <select name="noSC" class="form-select" {{ (!$canEdit || $ticket->condition == 'Closed') ? 'disabled' : '' }}>
                                <option value="">Select</option>
                                <option value="SC001" {{ $ticket->noSC == 'SC001' ? 'selected' : '' }}>SC001</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Status SC/Track ID</label>
                            <select name="statusSC" class="form-select" {{ (!$canEdit || $ticket->condition == 'Closed') ? 'disabled' : '' }}>
                                <option value="">Select</option>
                                <option value="Open" {{ $ticket->statusSC == 'Open' ? 'selected' : '' }}>Open</option>
                                <option value="Closed" {{ $ticket->statusSC == 'Closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Validasi Close</label>
                            <select name="validateClose" class="form-select" {{ (!$canEdit || $ticket->condition == 'Closed') ? 'disabled' : '' }}>
                                <option value="">Select</option>
                                <option value="Yes" {{ $ticket->validateClose == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $ticket->validateClose == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Eskalasi Tiket</label>
                            <select name="eksalasiTicket" class="form-select" {{ (!$canEdit || $ticket->condition == 'Closed') ? 'disabled' : '' }}>
                                <option value="">Select</option>
                                <option value="Yes" {{ $ticket->eksalasiTicket == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $ticket->eksalasiTicket == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">PIC</label>
                            <select name="PIC" class="form-select" {{ (!$canEdit || $ticket->condition == 'Closed') ? 'disabled' : '' }}>
                                <option value="">Select</option>
                                <option value="Agent 1" {{ $ticket->PIC == 'Agent 1' ? 'selected' : '' }}>Agent 1</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-textarea" placeholder="Deskripsi" {{ (!$canEdit || $ticket->condition == 'Closed') ? 'disabled' : '' }}>{{ $ticket->description }}</textarea>
                    </div>

                    <div class="button-row">
                        <button type="submit" class="btn btn-submit" {{ (!$canEdit || $ticket->condition == 'Closed') ? 'disabled' : '' }}>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                            SUBMIT
                        </button>
                        <button type="submit" formaction="{{ route('agent.ticket.status', $ticket->idTicket) }}" name="action" value="closed" class="btn btn-closed" {{ (!$canEdit || $ticket->condition == 'Closed') ? 'disabled' : '' }}>
                            CLOSED
                        </button>
                        <button type="submit" formaction="{{ route('agent.ticket.status', $ticket->idTicket) }}" name="action" value="expired" class="btn btn-expired" {{ (!$canEdit || $ticket->condition == 'Closed') ? 'disabled' : '' }}>
                            EXPIRED
                        </button>
                        <button type="submit" formaction="{{ route('agent.ticket.status', $ticket->idTicket) }}" name="action" value="dispatch" class="btn btn-dispatch" id="btnDispatch" {{ (!$canEdit || $ticket->condition == 'Closed') ? 'disabled' : '' }}>
                            DISPATCH
                        </button>
                    </div>
                </form>

                <div class="detail-section" style="margin-top: 30px; border-top: 2px solid #E5E7EB; padding-top: 20px;">
                    <div class="section-title">Activity History</div>
                    <div class="activity-timeline">
                        @forelse($activities as $activity)
                        <div class="activity-item">
                            <div class="activity-dot"></div>
                            <div class="activity-content">
                                <div class="activity-header">
                                    <span class="activity-user">{{ $activity->causer->name ?? 'System' }}</span>
                                    <span class="activity-action">{{ $activity->description }}</span>
                                    <span class="activity-time">{{ $activity->created_at->diffForHumans() }}</span>
                                </div>
                                @if($activity->properties->has('attributes'))
                                <div class="activity-changes" id="activity-changes-{{ $activity->id }}">
                                    <button type="button" class="btn-copy" onclick="copyActivityChanges('activity-changes-{{ $activity->id }}', this)" title="Copy changes">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
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
            </div>
        </div>
    </div>

    <style>
        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #1F2A37;
        }
        .activity-timeline {
            margin-top: 15px;
            padding-left: 10px;
        }
        .activity-item {
            position: relative;
            padding-left: 20px;
            padding-bottom: 20px;
            border-left: 2px solid #E5E7EB;
        }
        .activity-item:last-child {
            border-left: none;
        }
        .activity-dot {
            position: absolute;
            left: -6px;
            top: 0;
            width: 10px;
            height: 10px;
            background: #1F4A5E;
            border-radius: 50%;
        }
        .activity-header {
            display: flex;
            gap: 8px;
            align-items: center;
            font-size: 13px;
            margin-bottom: 4px;
        }
        .activity-user {
            font-weight: 600;
            color: #1F2A37;
        }
        .activity-action {
            color: #6B7280;
        }
        .activity-time {
            color: #9CA3AF;
            font-size: 11px;
        }
        .activity-changes {
            background: #F9FAFB;
            padding: 12px;
            border-radius: 6px;
            font-size: 12px;
            margin-top: 4px;
            position: relative;
        }
        .btn-copy {
            position: absolute;
            top: 8px;
            right: 8px;
            background: #FFFFFF;
            border: 1px solid #E5E7EB;
            color: #6B7280;
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .btn-copy:hover {
            background: #F3F4F6;
            color: #1F4A5E;
        }
        .change-item {
            display: flex;
            gap: 6px;
        }
        .change-key {
            font-weight: 600;
            color: #4B5563;
        }
        .change-value {
            color: #1F2A37;
        }
        .no-activity {
            color: #9CA3AF;
            font-size: 13px;
            font-style: italic;
        }
    </style>

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
                // Change to checkmark icon
                btnElement.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>';
                
                setTimeout(() => {
                    btnElement.innerHTML = originalHTML;
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy text: ', err);
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('ticketDetailForm');
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
                        
                        // Periksa apakah nilai kosong
                        if (!input.value || input.value.trim() === '') {
                            allFilled = false;
                        }
                    });
                    
                    dispatchBtn.disabled = !allFilled;
                    if (!allFilled) {
                        dispatchBtn.style.opacity = '0.5';
                        dispatchBtn.style.cursor = 'not-allowed';
                        dispatchBtn.title = 'Semua field harus diisi sebelum melakukan Dispatch';
                    } else {
                        dispatchBtn.style.opacity = '1';
                        dispatchBtn.style.cursor = 'pointer';
                        dispatchBtn.title = '';
                    }
                }
                
                // Jalankan saat pertama load
                validateFields();
                
                // Pantau setiap ada ketikan / perubahan dropdown
                inputs.forEach(input => {
                    input.addEventListener('input', validateFields);
                    input.addEventListener('change', validateFields);
                });
            }
        });
    </script>
</body>
</html>
