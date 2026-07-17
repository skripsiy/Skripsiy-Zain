<x-agent-layout>
    <x-slot name="title">User Reports</x-slot>
    
    <!-- [MODIFIKASI] Menu navigasi atas diselaraskan dengan Laporan Tiket (nav-tab active pada Laporan User) -->
    <x-slot name="headerContent">
        <div style="display:flex; gap:12px; align-items:center;">
            <a href="{{ route('admin.reports.index') }}" class="nav-tab active">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                Laporan User
            </a>
            <a href="{{ route('admin.reports.tickets') }}" class="nav-tab">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>
                Laporan Tiket
            </a>
        </div>
    </x-slot>
    
    <x-slot name="sidebar">
        <a class="sidebar-icon" href="{{ route('admin.dashboard') }}" title="Dashboard">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
            </svg>
        </a>
        <a class="sidebar-icon" href="{{ route('admin.users.index') }}" title="Users">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
            </svg>
        </a>
        <a class="sidebar-icon active" href="{{ route('admin.reports.index') }}" title="Reports">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
            </svg>
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

        .header-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }
        .search-box {
            position: relative;
            display: flex;
            align-items: center;
        }
        .search-box svg {
            position: absolute;
            left: 12px;
            color: #666;
        }
        .search-box input {
            padding: 8px 12px 8px 38px;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            font-size: 13px;
            width: 250px;
            transition: all 0.3s ease;
        }
        .search-box input:focus {
            outline: none;
            border-color: #2C5F7C;
            box-shadow: 0 0 0 3px rgba(44, 95, 124, 0.1);
        }
        
        .download-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: linear-gradient(135deg, #2C5F7C 0%, #1F4A5E 100%);
            color: white;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        
        .download-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(44, 95, 124, 0.3);
            background: linear-gradient(135deg, #1F4A5E 0%, #2C5F7C 100%);
        }
        
        .download-btn svg {
            flex-shrink: 0;
        }

        /* [MODIFIKASI] Penyesuaian Style Badge Role sesuai wireframe */
        .role-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: lowercase;
            text-align: center;
        }

        .role-agent {
            background: #F3F4F6;
            color: #374151;
            border: 1px solid #E5E7EB;
        }
        .role-team-leader {
            background: #EFF6FF;
            color: #1E40AF;
            border: 1px solid #DBEAFE;
        }
        .role-admin {
            background: #FEF2F2;
            color: #991B1B;
            border: 1px solid #FEE2E2;
        }
        
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            animation: fadeIn 0.3s ease;
        }
        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .modal-content {
            background: white;
            border-radius: 16px;
            padding: 30px;
            width: 90%;
            max-width: 900px;
            max-height: 90vh;
            overflow-y: auto;
            animation: slideUp 0.3s ease;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        .modal-content::-webkit-scrollbar {
            display: none;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideUp {
            from { transform: translateY(50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 5px;
        }
        .modal-title {
            font-size: 20px;
            font-weight: 700;
            color: #1F4A5E;
        }
        
        .close-modal {
            background: none;
            border: none;
            font-size: 28px;
            color: #9CA3AF;
            cursor: pointer;
            line-height: 1;
            transition: color 0.2s ease;
        }
        .close-modal:hover {
            color: #374151;
        }
        
        .detail-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .detail-stat-card {
            background: linear-gradient(135deg, #F3F4F6 0%, #E5E7EB 100%);
            border-radius: 10px;
            padding: 15px;
            text-align: center;
        }
        
        .detail-stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #2C5F7C;
            line-height: 1;
            margin-bottom: 8px;
        }
        
        .detail-stat-label {
            font-size: 12px;
            color: #666;
            font-weight: 600;
        }
        
        .tickets-section {
            margin-top: 20px;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: 700;
            color: #1F4A5E;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .ticket-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
            max-height: 300px;
            overflow-y: auto;
            padding-right: 10px;
            
            scrollbar-width: thin;
            scrollbar-color: #2C5F7C #E5E7EB;
        }
        
        .ticket-list::-webkit-scrollbar {
            width: 6px;
        }
        
        .ticket-list::-webkit-scrollbar-track {
            background: #E5E7EB;
            border-radius: 10px;
        }
        
        .ticket-list::-webkit-scrollbar-thumb {
            background: #2C5F7C;
            border-radius: 10px;
        }
        
        .ticket-item {
            background: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            padding: 12px 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.2s ease;
        }
        
        .ticket-item:hover {
            background: #F3F4F6;
            border-color: #2C5F7C;
        }
        
        .ticket-info {
            flex: 1;
        }
        
        .ticket-id {
            font-size: 13px;
            font-weight: 700;
            color: #1F4A5E;
            margin-bottom: 4px;
        }
        
        .ticket-detail {
            font-size: 12px;
            color: #666;
        }
        
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-queued {
            background: #FEF3C7;
            color: #92400E;
        }
        
        .status-assigned {
            background: #DBEAFE;
            color: #1E40AF;
        }
        
        .status-in-progress {
            background: #FFF9C4;
            color: #F57F17;
        }
        
        .status-solved {
            background: #D1FAE5;
            color: #065F46;
        }
        
        .status-closed {
            background: #E5E7EB;
            color: #374151;
        }

        .condition-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: capitalize;
        }
        .condition-closed {
            background: #FEE2E2;
            color: #991B1B;
        }
        .condition-expired {
            background: #E5E7EB;
            color: #4B5563;
        }
        .condition-in-progress {
            background: #FEF3C7;
            color: #92400E;
        }
        .condition-dispatched {
            background: #EFF6FF;
            color: #1E40AF;
        }
        .condition-saltik {
            background: #FAF5FF;
            color: #6B21A8;
        }
        .condition-open {
            background: #F3F4F6;
            color: #374151;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #9CA3AF;
        }
        
        .empty-state svg {
            width: 64px;
            height: 64px;
            margin: 0 auto 15px;
            opacity: 0.3;
        }
        
        .loading {
            text-align: center;
            padding: 40px;
            color: #666;
        }
    </x-slot>
    
    <!-- [MODIFIKASI] Bagian Judul Reports, Sub-judul, Kotak Pencarian, dan tombol Export Excel diselaraskan rapi -->
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; flex-wrap:wrap; gap: 15px;">
        <div style="margin-bottom:0;">
            <h1 style="font-size: 24px; font-weight: 700; color: #0f172a;">Reports</h1>
            <p style="font-size: 13px; color: #64748b; margin-top: 2px;">Daily performance report per user</p>
        </div>
        <div style="display:flex; gap:12px; align-items:center;">
            <div class="search-box" style="max-width:280px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
                <input type="text" id="searchInput" placeholder="Cari nama user...">
            </div>
            
            <a href="{{ route('admin.reports.export.users') }}" class="download-btn" style="padding: 9px 16px; font-size:13px; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="7 10 12 15 17 10"></polyline>
                    <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
                Export Excel
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-error" style="background: #FEE2E2; color: #991B1B; border: 1px solid #FCA5A5;">
            {{ session('error') }}
        </div>
    @endif
    
    <!-- [MODIFIKASI] Mengubah card-grid (.users-grid) menjadi tabel tabular terstruktur sesuai wireframe referensi -->
    <div class="table-card" style="background:#FFFFFF; border-radius:12px; padding:20px; box-shadow:0 2px 8px rgba(0,0,0,0.06); border: 1px solid #E5E7EB; margin-top:20px;">
        <div style="font-size:15px; font-weight:700; color:#1e293b; margin-bottom:15px; padding-bottom:10px; border-bottom:1px solid #F3F4F6;">
            User Performance — Today ({{ \Carbon\Carbon::now()->format('j M Y') }})
        </div>
        <div class="table-wrapper" style="overflow-x:auto; border:1px solid #E5E7EB; border-radius:10px;">
            <table id="usersTable" style="width:100%; border-collapse:collapse; font-size:13px; min-width:950px;">
                <thead>
                    <tr style="background:#1F4A5E; color:#FFFFFF;">
                        <th style="padding:12px 15px; text-align:left; font-weight:600; font-size:11px; text-transform:uppercase;">#</th>
                        <th style="padding:12px 15px; text-align:left; font-weight:600; font-size:11px; text-transform:uppercase;">Name</th>
                        <th style="padding:12px 15px; text-align:left; font-weight:600; font-size:11px; text-transform:uppercase;">Email</th>
                        <th style="padding:12px 15px; text-align:left; font-weight:600; font-size:11px; text-transform:uppercase;">Role</th>
                        <th style="padding:12px 15px; text-align:center; font-weight:600; font-size:11px; text-transform:uppercase;">Assigned (Today)</th>
                        <th style="padding:12px 15px; text-align:center; font-weight:600; font-size:11px; text-transform:uppercase;">Solved (Today)</th>
                        <th style="padding:12px 15px; text-align:center; font-weight:600; font-size:11px; text-transform:uppercase;">Inbox (Queued)</th>
                        <th style="padding:12px 15px; text-align:left; font-weight:600; font-size:11px; text-transform:uppercase;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr style="border-bottom:1px solid #F3F4F6; transition: background 0.2s;" onmouseover="this.style.background='#F9FAFB'" onmouseout="this.style.background=''">
                        <td style="padding:12px 15px; font-weight:500;">{{ $loop->iteration }}</td>
                        <td style="padding:12px 15px; font-weight:600; color:#1F4A5E;">{{ $user->name }}</td>
                        <td style="padding:12px 15px; color:#4a5568;">{{ $user->email }}</td>
                        <td style="padding:12px 15px;">
                            @if($user->role === 'agent')
                                <span class="role-badge role-agent">agent</span>
                            @elseif($user->role === 'team_leader')
                                <span class="role-badge role-team-leader">team_leader</span>
                            @elseif($user->role === 'admin')
                                <span class="role-badge role-admin">admin</span>
                            @else
                                <span class="role-badge" style="background:#F3F4F6; color:#374151;">{{ $user->role }}</span>
                            @endif
                        </td>
                        <td style="padding:12px 15px; font-weight:600; text-align:center;">{{ $user->assigned_tickets }}</td>
                        <td style="padding:12px 15px; font-weight:600; text-align:center;">{{ $user->solved_tickets }}</td>
                        <td style="padding:12px 15px; font-weight:600; text-align:center;">{{ $user->inbox_tickets }}</td>
                        <td style="padding:12px 15px;">
                            <button class="btn-detail-view" onclick="viewUserDetails({{ $user->id }})" style="padding:6px 12px; border:1px solid #cbd5e1; background:#FFFFFF; color:#334155; border-radius:6px; font-size:12px; font-weight:600; cursor:pointer; transition:all 0.2s;" onmouseover="this.style.background='#f1f5f9';this.style.borderColor='#94a3b8'" onmouseout="this.style.background='#FFFFFF';this.style.borderColor='#cbd5e1'">
                                View Detail
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr class="empty-row">
                        <td colspan="8" style="text-align: center; padding: 40px; color: #9CA3AF;">
                            No users found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- User Details Modal -->
    <div id="userDetailsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <!-- [MODIFIKASI] Mengubah modal title dan tombol close -->
                <h2 class="modal-title" id="modalUserName">User Details</h2>
                <button class="close-modal" onclick="closeUserDetails()">&times;</button>
            </div>
            
            <div id="modalContent">
                <div class="loading">
                    <p>Loading...</p>
                </div>
            </div>
        </div>
    </div>
    
    <x-slot name="additionalScripts">
        // Helper to escape HTML to prevent XSS
        function escapeHtml(str) {
            if (str === null || str === undefined) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        // [MODIFIKASI] Diubah untuk mendukung pencarian di baris tabel, bukan kartu grid
        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const userRows = document.querySelectorAll('#usersTable tbody tr:not(.empty-row)');
            
            userRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchValue) ? '' : 'none';
            });
        });
        
        // [MODIFIKASI] AJAX detail user dengan data gabungan tiket dalam satu tabel terpadu sesuai wireframe
        // View user details
        async function viewUserDetails(userId) {
            const modal = document.getElementById('userDetailsModal');
            const modalContent = document.getElementById('modalContent');
            
            modal.classList.add('active');
            modalContent.innerHTML = '<div class="loading"><p>Loading user details...</p></div>';
            
            try {
                const response = await fetch(`/admin/reports/user/${userId}`);
                const data = await response.json();
                
                // Format judul: {Nama User} — Daily Detail
                document.getElementById('modalUserName').textContent = data.user.name + ' — Daily Detail';
                
                // Gabungkan tiket (inbox, assigned, solved) untuk tabel tunggal terpadu
                const allTicketsMap = {};
                (data.inbox_tickets || []).forEach(t => allTicketsMap[t.idTicket] = t);
                (data.assigned_tickets || []).forEach(t => allTicketsMap[t.idTicket] = t);
                (data.solved_tickets || []).forEach(t => allTicketsMap[t.idTicket] = t);
                const combinedTickets = Object.values(allTicketsMap).sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
                
                const ticketsHtml = combinedTickets.length > 0 ? combinedTickets.map(ticket => {
                    const ticketCode = 'TK' + String(ticket.idTicket).padStart(6, '0');
                    const dateStr = ticket.datereport ? ticket.datereport.substring(0, 10) : '-';
                    const statusClass = 'status-' + ticket.status.toLowerCase().replace(' ', '-');
                    const conditionClass = 'condition-' + (ticket.condition ? ticket.condition.toLowerCase().replace(' ', '-') : 'open');
                    
                    return `
                        <tr style="border-bottom: 1px solid #E5E7EB; transition: background 0.2s;" onmouseover="this.style.background='#F9FAFB'" onmouseout="this.style.background=''">
                            <td style="padding:10px 12px; font-weight: 700; color: #1a202c;">${escapeHtml(ticketCode)}</td>
                            <td style="padding:10px 12px;">${escapeHtml(ticket.namacust || '-')}</td>
                            <td style="padding:10px 12px;">
                                <span class="status-badge ${statusClass}">${escapeHtml(ticket.status)}</span>
                            </td>
                            <td style="padding:10px 12px;">
                                <span class="condition-badge ${conditionClass}">${escapeHtml(ticket.condition || 'Open')}</span>
                            </td>
                            <td style="padding:10px 12px; color:#4a5568;">${escapeHtml(dateStr)}</td>
                        </tr>
                    `;
                }).join('') : `
                    <tr>
                        <td colspan="5" style="text-align:center; padding:30px; color:#9ca3af;">No tickets found for this user</td>
                    </tr>
                `;
                
                // Format tanggal hari ini: d M Y
                const todayVal = new Date();
                const formattedDate = todayVal.getDate() + ' ' + todayVal.toLocaleString('en-US', { month: 'short' }) + ' ' + todayVal.getFullYear();
                
                modalContent.innerHTML = `
                    <div style="font-size:13px; color:#64748b; margin-top:-15px; margin-bottom:20px;">
                        Breakdown for ${formattedDate}
                    </div>
                    
                    <!-- 3 Ringkasan angka di atas tabel (Assigned, Solved, Inbox) -->
                    <div style="display: flex; gap: 15px; margin-bottom: 25px;">
                        <div style="padding: 12px 24px; border: 1.5px solid #CBD5E1; border-radius: 8px; font-size: 14px; font-weight: 600; background: #F8FAFC; color: #1E293B;">
                            <span style="font-size: 20px; font-weight: 700; margin-right: 5px; color: #2C5F7C;">${data.assigned_count}</span> Assigned
                        </div>
                        <div style="padding: 12px 24px; border: 1.5px solid #CBD5E1; border-radius: 8px; font-size: 14px; font-weight: 600; background: #F8FAFC; color: #1E293B;">
                            <span style="font-size: 20px; font-weight: 700; margin-right: 5px; color: #10B981;">${data.solved_count}</span> Solved
                        </div>
                        <div style="padding: 12px 24px; border: 1.5px solid #CBD5E1; border-radius: 8px; font-size: 14px; font-weight: 600; background: #F8FAFC; color: #1E293B;">
                            <span style="font-size: 20px; font-weight: 700; margin-right: 5px; color: #F59E0B;">${data.inbox_count}</span> Inbox
                        </div>
                    </div>
                    
                    <!-- Tabel Tiket User Terpadu -->
                    <div style="margin-top: 20px;">
                        <div style="overflow-x: auto; border: 1px solid #E2E8F0; border-radius: 8px; max-height: 350px; overflow-y: auto;">
                            <table style="width:100%; border-collapse:collapse; font-size:12px; min-width: 600px;">
                                <thead style="background:#1F4A5E; color:#FFFFFF; position:sticky; top:0; z-index:10;">
                                    <tr>
                                        <th style="padding:10px 12px; text-align:left; font-weight:600; font-size:11px; text-transform:uppercase;">Ticket Code</th>
                                        <th style="padding:10px 12px; text-align:left; font-weight:600; font-size:11px; text-transform:uppercase;">Customer</th>
                                        <th style="padding:10px 12px; text-align:left; font-weight:600; font-size:11px; text-transform:uppercase;">Status</th>
                                        <th style="padding:10px 12px; text-align:left; font-weight:600; font-size:11px; text-transform:uppercase;">Condition</th>
                                        <th style="padding:10px 12px; text-align:left; font-weight:600; font-size:11px; text-transform:uppercase;">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${ticketsHtml}
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Tombol Aksi Bawah Modal -->
                    <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 25px; border-top: 1px solid #E2E8F0; padding-top: 20px;">
                        <button class="btn-cancel" onclick="closeUserDetails()" style="flex:none; width:auto; padding: 10px 24px; font-size:13px; font-weight:600;">Close</button>
                        <a href="/admin/reports/export/user-tickets/${data.user.id}" class="download-btn" style="padding: 10px 24px; font-size:13px; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:8px;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="7 10 12 15 17 10"></polyline>
                                <line x1="12" y1="15" x2="12" y2="3"></line>
                            </svg>
                            Export User Tickets
                        </a>
                    </div>
                `;
            } catch (error) {
                modalContent.innerHTML = '<div class="empty-state"><p>Error loading user details</p></div>';
                console.error('Error:', error);
            }
        }
        
        // Close modal
        function closeUserDetails() {
            document.getElementById('userDetailsModal').classList.remove('active');
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('userDetailsModal');
            
            if (event.target === modal) {
                closeUserDetails();
            }
        }
    </x-slot>
</x-agent-layout>
