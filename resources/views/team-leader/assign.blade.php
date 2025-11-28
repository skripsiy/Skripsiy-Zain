<x-agent-layout>
    <x-slot name="title">Assign Tickets</x-slot>
    
    <x-slot name="headerContent">
        <div class="time-filter">
            <button class="active">All Tickets</button>
            <button>Unassigned</button>
            <button>Pending</button>
        </div>
    </x-slot>
    
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
        
        .section-header {
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
        <h1 class="page-title">Assign Super Emergency Tickets</h1>
        <p class="page-subtitle">Assign super emergency tickets to available agents immediately</p>
    </div>
    
    <!-- Stats Cards -->
    <div class="stats-cards">
        <div class="stat-card">
            <div class="stat-label">Super Emergency Tickets</div>
            <div class="stat-value">{{ $tickets->count() }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Unassigned (QUEUED)</div>
            <div class="stat-value orange">{{ $tickets->whereNull('assignby')->count() }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Active Agents</div>
            <div class="stat-value green">{{ $agents->count() }}</div>
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
        
        <div class="table-wrapper">
            <table id="ticketsTable">
                <thead>
                    <tr>
                        <th>Ticket Code</th>
                        <th>Customer</th>
                        <th>Priority</th>
                        <th>Issue</th>
                        <th>Status</th>
                        <th>Current Agent</th>
                        <th>Assign To</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                        <tr>
                            <td><strong>TK{{ str_pad($ticket->idTicket, 6, '0', STR_PAD_LEFT) }}</strong></td>
                            <td>{{ $ticket->namacust ?? 'N/A' }}</td>
                            <td>
                                <span class="status-badge" style="background: #FFEBEE; color: #C62828;">
                                    🔴 {{ strtoupper($ticket->reportedpriority ?? 'EMERGENCY') }}
                                </span>
                            </td>
                            <td>{{ Str::limit($ticket->detailticket ?? 'No description', 40) }}</td>
                            <td>
                                @if($ticket->assignby)
                                    <span class="status-badge status-assigned">ASSIGNED</span>
                                @else
                                    <span class="status-badge status-unassigned">QUEUED</span>
                                @endif
                            </td>
                            <td>{{ $ticket->assignby ?? '-' }}</td>
                            <td>
                                <form action="{{ route('team-leader.assign.ticket', $ticket->idTicket) }}" method="POST" class="assign-form">
                                    @csrf
                                    <select name="agent_id" class="agent-select" required>
                                        <option value="">Select Agent</option>
                                        @foreach($agents as $agent)
                                            <option value="{{ $agent->id }}">
                                                {{ $agent->name }}
                                            </option>
                                        @endforeach
                                    </select>
                            </td>
                            <td>
                                    <button type="submit" class="assign-btn">Assign</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 40px; color: #999;">
                                ✓ No super emergency tickets in queue - All clear!
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
        
        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const table = document.getElementById('ticketsTable');
            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
            
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const text = row.textContent.toLowerCase();
                
                if (text.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
        
        // Filter buttons functionality
        const filterButtons = document.querySelectorAll('.time-filter button');
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                const filter = this.textContent.toLowerCase();
                const table = document.getElementById('ticketsTable');
                const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
                
                for (let i = 0; i < rows.length; i++) {
                    const row = rows[i];
                    const statusCell = row.cells[3];
                    
                    if (filter === 'all tickets') {
                        row.style.display = '';
                    } else if (filter === 'unassigned') {
                        row.style.display = statusCell.textContent.toLowerCase().includes('unassigned') ? '' : 'none';
                    } else if (filter === 'pending') {
                        row.style.display = statusCell.textContent.toLowerCase().includes('pending') ? '' : 'none';
                    }
                }
            });
        });
    </x-slot>
</x-agent-layout>
