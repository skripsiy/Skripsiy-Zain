<x-agent-layout>
    <x-slot name="title">Ticket Detail V2</x-slot>

    <x-slot name="sidebar">
        <a class="sidebar-icon" href="{{ route('agent.dashboard') }}" title="Dashboard">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z" />
            </svg>
        </a>
        <a class="sidebar-icon active" href="{{ route('agent.tickets') }}" title="Tickets">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path
                    d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z" />
            </svg>
        </a>
        <a class="sidebar-icon" href="{{ route('agent.profile') }}" title="My Profile">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path
                    d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
            </svg>
        </a>
    </x-slot>

    <x-slot name="customStyles">
        .v2-container {
        display: grid;
        grid-template-columns: 350px 1fr;
        gap: 20px;
        width: 100%;
        align-items: start;
        }

        .ticket-info-card {
        background: #FFFFFF;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        padding: 24px;
        border: 1px solid #E5E7EB;
        }

        .form-card {
        background: #FFFFFF;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        padding: 28px;
        border: 1px solid #E5E7EB;
        }

        .v2-title {
        font-size: 20px;
        font-weight: 700;
        color: #1F2A37;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        }

        .v2-badge-version {
        background: #1F4A5E;
        color: white;
        font-size: 11px;
        padding: 3px 8px;
        border-radius: 20px;
        font-weight: 600;
        text-transform: uppercase;
        }

        /* Section styling */
        .form-section {
        margin-bottom: 28px;
        padding-bottom: 20px;
        border-bottom: 1px solid #F3F4F6;
        }

        .form-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
        }

        .section-header {
        font-size: 14px;
        font-weight: 600;
        color: #1F4A5E;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        }

        .section-icon {
        width: 24px;
        height: 24px;
        background: #EBF5FA;
        color: #1F4A5E;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        }

        /* Inputs */
        .v2-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
        }

        .v2-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
        }

        .v2-group.full-width {
        grid-column: span 2;
        }

        .v2-label {
        font-size: 12px;
        font-weight: 600;
        color: #4B5563;
        }

        .v2-input, .v2-select, .v2-textarea {
        padding: 10px 14px;
        border: 1px solid #D1D5DB;
        border-radius: 8px;
        font-family: 'Poppins', sans-serif;
        font-size: 13px;
        background: #FFFFFF;
        color: #1F2A37;
        transition: all 0.2s ease-in-out;
        width: 100%;
        }

        .v2-input:focus, .v2-select:focus, .v2-textarea:focus {
        outline: none;
        border-color: #1F4A5E;
        box-shadow: 0 0 0 3px rgba(31, 74, 94, 0.1);
        }

        .v2-input::placeholder, .v2-textarea::placeholder {
        color: #9CA3AF;
        }

        .v2-textarea {
        resize: vertical;
        min-height: 80px;
        }

        /* Sidebar ticket info detail */
        .info-header {
        border-bottom: 1.5px solid #F3F4F6;
        padding-bottom: 16px;
        margin-bottom: 16px;
        }

        .info-ticket-id {
        font-size: 22px;
        font-weight: 800;
        color: #1F2A37;
        letter-spacing: -0.5px;
        }

        .info-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        margin-top: 8px;
        text-transform: uppercase;
        }

        .info-row {
        display: flex;
        flex-direction: column;
        gap: 12px;
        }

        .info-item {
        display: flex;
        flex-direction: column;
        gap: 2px;
        }

        .info-label {
        font-size: 11px;
        font-weight: 500;
        color: #9CA3AF;
        text-transform: uppercase;
        }

        .info-value {
        font-size: 13.5px;
        font-weight: 600;
        color: #374151;
        }

        .info-value-ttr {
        color: #EF4444;
        }

        /* Action buttons */
        .btn-row {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 24px;
        }

        .btn-v2 {
        padding: 10px 22px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
        font-family: 'Poppins', sans-serif;
        }

        .btn-v2-primary {
        background: linear-gradient(135deg, #1F4A5E 0%, #2D6D8B 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(31, 74, 94, 0.2);
        }

        .btn-v2-secondary {
        background: #F3F4F6;
        color: #374151;
        border: 1px solid #D1D5DB;
        }

        .btn-v2:hover {
        opacity: 0.95;
        transform: translateY(-1px);
        }

        .alert-v2 {
        background: #FEF3C7;
        color: #92400E;
        border: 1px solid #F59E0B;
        padding: 12px 16px;
        border-radius: 8px;
        font-size: 12.5px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 500;
        }

        @media (max-width: 1024px) {
        .v2-container {
        grid-template-columns: 1fr;
        }
        }
    </x-slot>

    <div class="v2-container">
        <!-- Left Column: General Ticket Info -->
        <div class="ticket-info-card">
            <div class="info-header">
                <div class="info-ticket-id">IN{{ str_pad($ticket->idTicket, 8, '0', STR_PAD_LEFT) }}</div>

                <span class="info-status-badge" style="background: 
                    {{ match (strtolower($ticket->condition ?? '')) {
    'closed' => '#FFEBEE; color: #C62828;',
    'saltik' => '#F3E5F5; color: #7B1FA2;',
    'dispatched' => '#E1F5FE; color: #0288D1;',
    'assigned' => '#E3F2FD; color: #1976D2;',
    'in progress' => '#FFF9C4; color: #F57F17;',
    default => '#F3F4F6; color: #1F2A37;',
} }}">
                    ● {{ strtoupper($ticket->condition ?? $ticket->status) }}
                </span>
            </div>

            <div class="info-row">
                <div class="info-item">
                    <span class="info-label">Customer Name</span>
                    <span class="info-value">{{ $ticket->namacust ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Contact Number</span>
                    <span class="info-value">{{ $ticket->notelpCust ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Reported Date</span>
                    <span
                        class="info-value">{{ $ticket->datereport ? $ticket->datereport->format('Y-m-d H:i:s') : '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Ticket Age (TTR)</span>
                    <span class="info-value info-value-ttr">
                        {{ $ticket->datereport ? $ticket->datereport->diff(\Carbon\Carbon::now())->format('%d Hari, %h Jam') : '-' }}
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Regional / Witel</span>
                    <span class="info-value">{{ $ticket->regional ?? 'REG 1' }} / {{ $ticket->witel ?? 'RIKEP' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Reported Priority</span>
                    <span class="info-value">{{ $ticket->reportedpriority ?? 'Emergency' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Report ID (ID Laporan)</span>
                    <span class="info-value">{{ $ticket->idlaporan ?? '-' }}</span>
                </div>
            </div>
        </div>

        <!-- Right Column: Form V2 -->
        <div class="form-card">
            <div class="v2-title">
                <span>Inputan Agent</span>
                <span class="v2-badge-version">Ver 2</span>
            </div>

            @if(!$canEdit)
                <div class="alert-v2">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    <span><strong>Mode Read-Only:</strong> Tiket ini sudah diselesaikan/tidak di-assign kepada Anda.
                        Formulir dinonaktifkan.</span>
                </div>
            @endif

            <form
                onsubmit="event.preventDefault(); alert('Ver 2 berhasil disubmit! (Offline Mode - Belum disambungkan ke mana-mana)');">

                <!-- SECTION 1: KASUS & ORDER -->
                <div class="form-section">
                    <div class="section-header">
                        <span class="section-icon">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="21 8 21 21 3 21 3 8"/><rect x="1" y="3" width="22" height="5"/><line x1="10" y1="12" x2="14" y2="12"/></svg>
                        </span>
                        <span>Kasus & Order</span>
                    </div>

                    <div class="v2-grid">
                        <div class="v2-group full-width">
                            <label class="v2-label">Detail Case</label>
                            <input type="text" class="v2-input" placeholder="Masukkan detail case..." {{ !$canEdit ? 'disabled' : '' }} required>
                        </div>
                        <div class="v2-group">
                            <label class="v2-label">Order ID</label>
                            <input type="text" class="v2-input" placeholder="Masukkan Order ID..." {{ !$canEdit ? 'disabled' : '' }} required>
                        </div>
                        <div class="v2-group">
                            <label class="v2-label">Status Order ID</label>
                            <select class="v2-select" {{ !$canEdit ? 'disabled' : '' }} required>
                                <option value="">Select Status</option>
                                <option value="Open">Open</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Pending">Pending</option>
                                <option value="Closed">Closed</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>
                        <div class="v2-group">
                            <label class="v2-label">Real Clasify (Reason 2)</label>
                            <select class="v2-select" {{ !$canEdit ? 'disabled' : '' }} required>
                                <option value="">Select Classification</option>
                                <option value="Technical Issue">Technical Issue</option>
                                <option value="Non-Technical Issue">Non-Technical Issue</option>
                                <option value="Hardware Replacement">Hardware Replacement</option>
                                <option value="Software / Config Issue">Software / Config Issue</option>
                                <option value="Outside Boundary / No Coverage">Outside Boundary / No Coverage</option>
                            </select>
                        </div>
                        <div class="v2-group">
                            <label class="v2-label">Real Symptom (Reason 3)</label>
                            <select class="v2-select" {{ !$canEdit ? 'disabled' : '' }} required>
                                <option value="">Select Symptom</option>
                                <option value="Modem Red light / LOS">Modem Red light / LOS</option>
                                <option value="Slow Internet Connection">Slow Internet Connection</option>
                                <option value="Frequent Disconnection">Frequent Disconnection</option>
                                <option value="No Dial Tone / Telephone Dead">No Dial Tone / Telephone Dead</option>
                                <option value="Cannot Login / Auth Error">Cannot Login / Auth Error</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: KOORDINASI & ESKALASI -->
                <div class="form-section">
                    <div class="section-header">
                        <span class="section-icon">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        </span>
                        <span>Koordinasi & Eskalasi</span>
                    </div>

                    <div class="v2-grid">
                        <div class="v2-group">
                            <label class="v2-label">Unit Koordinasi</label>
                            <select class="v2-select" {{ !$canEdit ? 'disabled' : '' }} required>
                                <option value="">Select Unit</option>
                                <option value="Backend Support">Backend Support</option>
                                <option value="Field Technician (Infras)">Field Technician (Infras)</option>
                                <option value="Core Network">Core Network</option>
                                <option value="Customer Care">Customer Care</option>
                                <option value="Provisioning Unit">Provisioning Unit</option>
                            </select>
                        </div>
                        <div class="v2-group">
                            <label class="v2-label">Jabatan</label>
                            <select class="v2-select" {{ !$canEdit ? 'disabled' : '' }} required>
                                <option value="">Select Jabatan</option>
                                <option value="Supervisor">Supervisor</option>
                                <option value="Team Leader">Team Leader</option>
                                <option value="Helpdesk Agent">Helpdesk Agent</option>
                                <option value="Field Engineer">Field Engineer</option>
                                <option value="Back Office Solver">Back Office Solver</option>
                            </select>
                        </div>
                        <div class="v2-group">
                            <label class="v2-label">Eskalasi Via</label>
                            <select class="v2-select" {{ !$canEdit ? 'disabled' : '' }} required>
                                <option value="">Select Escalation Channel</option>
                                <option value="WhatsApp">WhatsApp</option>
                                <option value="Telegram">Telegram</option>
                                <option value="Voice Call / Telepon">Voice Call / Telepon</option>
                                <option value="Internal System (Insera/DSC)">Internal System (Insera/DSC)</option>
                                <option value="Email">Email</option>
                            </select>
                        </div>
                        <div class="v2-group">
                            <label class="v2-label">Nama Solver</label>
                            <input type="text" class="v2-input" placeholder="Nama solver yang dihubungi..." {{ !$canEdit ? 'disabled' : '' }} required>
                        </div>
                        <div class="v2-group">
                            <label class="v2-label">Contact Solver</label>
                            <input type="text" class="v2-input" placeholder="No. HP / ID solver..." {{ !$canEdit ? 'disabled' : '' }} required>
                        </div>
                        <div class="v2-group">
                            <label class="v2-label">Respon BackEnd</label>
                            <select class="v2-select" {{ !$canEdit ? 'disabled' : '' }} required>
                                <option value="">Select Response</option>
                                <option value="Fast Respon">Fast Respon (Cepat)</option>
                                <option value="Low Respon">Low Respon (Lama)</option>
                                <option value="No Respon">No Respon (Read Only)</option>
                                <option value="Tanpa Koordinasi">Tanpa Koordinasi</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- SECTION 3: HASIL KONTAK & RESOLUSI -->
                <div class="form-section">
                    <div class="section-header">
                        <span class="section-icon">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        </span>
                        <span>Hasil Kontak & Resolusi</span>
                    </div>

                    <div class="v2-grid">
                        <div class="v2-group">
                            <label class="v2-label">Reason Not ODS</label>
                            <select class="v2-select" {{ !$canEdit ? 'disabled' : '' }} required>
                                <option value="">Select Reason</option>
                                <option value="PEOPLE - Pelanggan Sulit Dihubungi">PEOPLE - Pelanggan Sulit Dihubungi
                                </option>
                                <option value="PEOPLE - Pelanggan Reschedule">PEOPLE - Pelanggan Reschedule</option>
                                <option value="PEOPLE - Menunggu Konfirmasi Dari Pelanggan">PEOPLE - Menunggu Konfirmasi
                                    Dari Pelanggan</option>
                                <option value="PROSES - Jaringan Tidak Tersedia di Lokasi">PROSES - Jaringan Tidak
                                    Tersedia di Lokasi</option>
                                <option value="PROSES - GAMAS (Gangguan Massal)">PROSES - GAMAS (Gangguan Massal)
                                </option>
                                <option value="TOOLS - Labor WO belum Completed">TOOLS - Labor WO belum Completed
                                </option>
                                <option value="TOOLS - Tidak Bisa Takeownership">TOOLS - Tidak Bisa Takeownership
                                </option>
                            </select>
                        </div>
                        <div class="v2-group">
                            <label class="v2-label">Caring</label>
                            <select class="v2-select" {{ !$canEdit ? 'disabled' : '' }} required>
                                <option value="">Select Caring Status</option>
                                <option value="Yes">Yes (Caring Selesai)</option>
                                <option value="No">No (Belum Caring)</option>
                                <option value="Pending">Pending (Pelanggan Sibuk)</option>
                            </select>
                        </div>
                        <div class="v2-group">
                            <label class="v2-label">Status Call</label>
                            <select class="v2-select" {{ !$canEdit ? 'disabled' : '' }} required>
                                <option value="">Select Status Call</option>
                                <option value="Contacted">Contacted</option>
                                <option value="RNA (Ring No Answer)">RNA (Ring No Answer)</option>
                                <option value="Busy">Busy</option>
                                <option value="Rejected">Rejected</option>
                                <option value="Mailbox">Mailbox</option>
                                <option value="Reschedule">Reschedule</option>
                            </select>
                        </div>
                        <div class="v2-group">
                            <label class="v2-label">Resolved By Analisa</label>
                            <select class="v2-select" {{ !$canEdit ? 'disabled' : '' }} required>
                                <option value="">Select Analyst Resolution</option>
                                <option value="Success Resolved">Success Resolved</option>
                                <option value="Gagal Resolved">Gagal Resolved</option>
                                <option value="No Action">No Action</option>
                            </select>
                        </div>
                        <div class="v2-group full-width">
                            <label class="v2-label">Hasil Call</label>
                            <input type="text" class="v2-input" placeholder="Masukkan hasil pembicaraan..." {{ !$canEdit ? 'disabled' : '' }} required>
                        </div>
                        <div class="v2-group full-width">
                            <label class="v2-label">Keterangan</label>
                            <textarea class="v2-textarea" placeholder="Tambahkan keterangan/catatan tambahan..." {{ !$canEdit ? 'disabled' : '' }}></textarea>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="btn-row">
                    <a href="{{ route('agent.tickets') }}" class="btn-v2 btn-v2-secondary">Kembali</a>
                    <button type="submit" class="btn-v2 btn-v2-primary" {{ !$canEdit ? 'disabled' : '' }}>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        <span>Submit Ver 2</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-agent-layout>