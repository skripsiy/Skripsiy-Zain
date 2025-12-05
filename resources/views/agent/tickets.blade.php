<x-agent-layout>
    <x-slot name="title">Ticket Management</x-slot>
    
    <x-slot name="headerContent">
        <div class="work-duration" id="workDuration" style="display: none;">00:00</div>
        <div class="work-status-toggle" id="workToggle" onclick="handleToggleClick()"></div>
        <div class="user-avatar-header">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
    </x-slot>
    
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
        .work-status-toggle {
            width: 50px;
            height: 26px;
            background: #E5E7EB;
            border-radius: 13px;
            position: relative;
            cursor: pointer;
            transition: 0.3s;
        }
        .work-status-toggle::before {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: white;
            top: 3px;
            left: 3px;
            transition: 0.3s;
        }
        .work-status-toggle.active {
            background: #10B981;
        }
        .work-status-toggle.active::before {
            left: 27px;
        }
        .work-duration {
            font-size: 13px;
            color: #374151;
            font-weight: 600;
            margin-right: 8px;
        }
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
            grid-template-columns: repeat(5, 1fr);
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
            gap: 8px;
            margin-bottom: 12px;
            font-size: 13px;
            color: #6B7280;
        }
        .entries-select {
            padding: 4px 8px;
            border: 1px solid #D1D5DB;
            border-radius: 4px;
            font-family: 'Poppins', sans-serif;
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
        .status-queued {
            color: #6B7280;
            font-weight: 600;
        }
        .escalation-assigned {
            color: #DC2626;
            font-weight: 600;
        }
        .escalation-pickup {
            color: #F59E0B;
            font-weight: 600;
        }
        .condition-closed {
            color: #DC2626;
            font-weight: 600;
        }
        .condition-expired {
            color: #DC2626;
            font-weight: 600;
            font-style: italic;
        }
        .condition-progress {
            color: #F59E0B;
            font-weight: 600;
            font-style: italic;
        }
        .condition-open {
            color: #6B7280;
            font-weight: 600;
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
            <div class="stat-info">
                <div class="stat-label">Tickets</div>
                <div class="stat-value">{{ $totalTickets }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">Consumed</div>
                <div class="stat-value">{{ $consumedTickets }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">Submitted</div>
                <div class="stat-value">{{ $submittedTickets }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">Closed</div>
                <div class="stat-value">{{ $closedTickets }}</div>
            </div>
        </div>
        <div class="stat-card">
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
        let durationInterval = null;
        let isActive = false;

        // Check status saat halaman dimuat
        async function checkWorkStatus() {
            try {
                const response = await fetch('{{ route("agent.work-session.status") }}');
                const data = await response.json();
                
                if (data.active) {
                    isActive = true;
                    document.getElementById('workToggle').classList.add('active');
                    document.getElementById('workDuration').style.display = 'block';
                    document.getElementById('workDuration').textContent = data.duration;
                    startDurationCounter();
                } else {
                    isActive = false;
                    document.getElementById('workToggle').classList.remove('active');
                    document.getElementById('workDuration').style.display = 'none';
                }
            } catch (error) {
                console.error('Error checking work status:', error);
            }
        }

        function startDurationCounter() {
            if (durationInterval) clearInterval(durationInterval);
            
            durationInterval = setInterval(async () => {
                try {
                    const response = await fetch('{{ route("agent.work-session.status") }}');
                    const data = await response.json();
                    
                    if (data.active) {
                        document.getElementById('workDuration').textContent = data.duration;
                    } else {
                        clearInterval(durationInterval);
                    }
                } catch (error) {
                    console.error('Error updating duration:', error);
                }
            }, 60000); // Update setiap 1 menit
        }

        async function handleToggleClick() {
            // Temporarily disabled - akan diupdate dengan sistem baru
            alert('Fitur time tracking sedang dalam perbaikan');
            /*
            if (!isActive) {
                // Mulai sesi kerja
                try {
                    const response = await fetch('{{ route("agent.work-session.toggle-online") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        isActive = true;
                        document.getElementById('workToggle').classList.add('active');
                        document.getElementById('workDuration').style.display = 'block';
                        document.getElementById('workDuration').textContent = '00:00';
                        startDurationCounter();
                    } else {
                        alert(data.message);
                    }
                } catch (error) {
                    console.error('Error starting session:', error);
                    alert('Gagal memulai sesi kerja');
                }
            } else {
                // Tampilkan modal konfirmasi
                document.getElementById('endModal').classList.add('show');
            }
            */
        }

        function closeModal() {
            document.getElementById('endModal').classList.remove('show');
        }

        async function endSession(type) {
            try {
                const response = await fetch('{{ route("agent.work-session.end") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ end_type: type })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    isActive = false;
                    document.getElementById('workToggle').classList.remove('active');
                    document.getElementById('workDuration').style.display = 'none';
                    if (durationInterval) clearInterval(durationInterval);
                    closeModal();
                    
                    const hours = Math.floor(data.duration_minutes / 60);
                    const minutes = data.duration_minutes % 60;
                    alert(`Sesi ${type === 'break' ? 'istirahat' : 'selesai'}. Durasi kerja: ${hours} jam ${minutes} menit`);
                } else {
                    alert(data.message);
                }
            } catch (error) {
                console.error('Error ending session:', error);
                alert('Gagal mengakhiri sesi kerja');
            }
        }

        // Check status saat halaman dimuat
        checkWorkStatus();
    </x-slot>
</x-agent-layout>
