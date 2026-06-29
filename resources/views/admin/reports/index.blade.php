<x-agent-layout>
    <x-slot name="title">User Reports</x-slot>
    
    <x-slot name="headerContent">
        <div style="display:flex; gap:12px; align-items:center;">
            <a href="{{ route('admin.reports.index') }}" style="display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border-radius:8px;font-size:13px;font-weight:600;background:#1F4A5E;color:#fff;text-decoration:none;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                Laporan User
            </a>
            <a href="{{ route('admin.reports.tickets') }}" style="display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border-radius:8px;font-size:13px;font-weight:500;color:#64748b;text-decoration:none;transition:all .2s;" onmouseover="this.style.background='#f1f5f9';this.style.color='#1F4A5E'" onmouseout="this.style.background='';this.style.color='#64748b'">
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
        
        .users-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .user-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .user-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(44, 95, 124, 0.15);
            border-color: #2C5F7C;
        }
        
        .user-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2C5F7C 0%, #1F4A5E 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            font-weight: 700;
            flex-shrink: 0;
        }
        
        .user-info {
            flex: 1;
            min-width: 0;
        }
        
        .user-name {
            font-size: 16px;
            font-weight: 700;
            color: #1F4A5E;
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .user-role {
            font-size: 12px;
            color: #666;
            text-transform: capitalize;
        }
        
        .user-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            padding-top: 15px;
            border-top: 1px solid #E5E7EB;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: #2C5F7C;
            line-height: 1;
            margin-bottom: 4px;
        }
        
        .stat-label {
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
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
            
            /* Hide scrollbar */
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
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #E5E7EB;
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
            
            /* Custom scrollbar */
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
        
        .ticket-status {
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
        
        .status-in-progress {
            background: #DBEAFE;
            color: #1E40AF;
        }
        
        .status-solved {
            background: #D1FAE5;
            color: #065F46;
        }
        
        .status-closed {
            background: #E5E7EB;
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
    
    <div style="margin-bottom:16px;">
        <div class="search-box" style="max-width:280px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <circle cx="11" cy="11" r="8"></circle>
                <path d="m21 21-4.35-4.35"></path>
            </svg>
            <input type="text" id="searchInput" placeholder="Cari nama user...">
        </div>
    </div>

    <div class="users-grid" id="usersGrid">
        @forelse($users as $user)

        <div class="user-card" onclick="viewUserDetails({{ $user->id }})">
            <div class="user-header">
                <div class="user-avatar">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="user-info">
                    <div class="user-name">{{ $user->name }}</div>
                    <div class="user-role">{{ str_replace('_', ' ', $user->role) }}</div>
                </div>
            </div>
            <div class="user-stats">
                <div class="stat-item">
                    <div class="stat-value">{{ $user->assigned_tickets }}</div>
                    <div class="stat-label">Assigned</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ $user->solved_tickets }}</div>
                    <div class="stat-label">Solved</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ $user->inbox_tickets }}</div>
                    <div class="stat-label">Inbox</div>
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state" style="grid-column: 1 / -1;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
            </svg>
            <p>No users found</p>
        </div>
        @endforelse
    </div>
    
    <!-- User Details Modal -->
    <div id="userDetailsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
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
        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const userCards = document.querySelectorAll('.user-card');
            
            userCards.forEach(card => {
                const text = card.textContent.toLowerCase();
                card.style.display = text.includes(searchValue) ? '' : 'none';
            });
        });
        
        // View user details
        async function viewUserDetails(userId) {
            const modal = document.getElementById('userDetailsModal');
            const modalContent = document.getElementById('modalContent');
            
            modal.classList.add('active');
            modalContent.innerHTML = '<div class="loading"><p>Loading user details...</p></div>';
            
            try {
                const response = await fetch(`/admin/reports/user/${userId}`);
                const data = await response.json();
                
                document.getElementById('modalUserName').textContent = data.user.name + ' - Ticket Report';
                
                let statusCountsHtml = '';
                for (const [status, count] of Object.entries(data.status_counts || {})) {
                    statusCountsHtml += `
                        <div class="detail-stat-card">
                            <div class="detail-stat-value">${count}</div>
                            <div class="detail-stat-label">${status}</div>
                        </div>
                    `;
                }
                
                modalContent.innerHTML = `
                    <div class="detail-stats">
                        <div class="detail-stat-card">
                            <div class="detail-stat-value">${data.assigned_count}</div>
                            <div class="detail-stat-label">Total Assigned</div>
                        </div>
                        <div class="detail-stat-card">
                            <div class="detail-stat-value">${data.solved_count}</div>
                            <div class="detail-stat-label">Total Solved</div>
                        </div>
                        <div class="detail-stat-card">
                            <div class="detail-stat-value">${data.inbox_count}</div>
                            <div class="detail-stat-label">In Inbox</div>
                        </div>
                        ${statusCountsHtml}
                    </div>
                    
                    <div class="tickets-section">
                        <div class="section-title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            Inbox Tickets (${data.inbox_count})
                        </div>
                        <div class="ticket-list">
                            ${data.inbox_tickets.length > 0 ? data.inbox_tickets.map(ticket => `
                                <div class="ticket-item">
                                    <div class="ticket-info">
                                        <div class="ticket-id">Ticket #${ticket.idTicket}</div>
                                        <div class="ticket-detail">${ticket.namacust} - ${ticket.jenisTicket || 'N/A'}</div>
                                    </div>
                                    <span class="ticket-status status-${ticket.status.toLowerCase().replace(' ', '-')}">${ticket.status}</span>
                                </div>
                            `).join('') : '<div class="empty-state"><p>No inbox tickets</p></div>'}
                        </div>
                    </div>
                    
                    <div class="tickets-section">
                        <div class="section-title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="9 11 12 14 22 4"></polyline>
                                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                            </svg>
                            All Assigned Tickets (${data.assigned_count})
                        </div>
                        <div class="ticket-list">
                            ${data.assigned_tickets.length > 0 ? data.assigned_tickets.map(ticket => `
                                <div class="ticket-item">
                                    <div class="ticket-info">
                                        <div class="ticket-id">Ticket #${ticket.idTicket}</div>
                                        <div class="ticket-detail">${ticket.namacust} - ${ticket.jenisTicket || 'N/A'}</div>
                                    </div>
                                    <span class="ticket-status status-${ticket.status.toLowerCase().replace(' ', '-')}">${ticket.status}</span>
                                </div>
                            `).join('') : '<div class="empty-state"><p>No assigned tickets</p></div>'}
                        </div>
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
