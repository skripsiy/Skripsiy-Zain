<x-agent-layout>
    <x-slot name="title">Modify Role User</x-slot>
    
    <x-slot name="headerContent">
        <div class="header-actions">
            <div class="search-box">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
                <input type="text" id="searchInput" placeholder="Search users...">
            </div>
            <button class="btn-add-user" onclick="openAddUserModal()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Add New User
            </button>
        </div>
    </x-slot>
    
    <x-slot name="sidebar">
        <a class="sidebar-icon" href="{{ route('admin.dashboard') }}" title="Dashboard">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
            </svg>
        </a>
        <a class="sidebar-icon active" href="{{ route('admin.users.index') }}" title="Users">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
            </svg>
        </a>
        <a class="sidebar-icon" href="{{ route('admin.reports.index') }}" title="Reports">
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
        .btn-add-user {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: linear-gradient(135deg, #2C5F7C 0%, #1F4A5E 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-add-user:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(44, 95, 124, 0.3);
        }
        .table-card {
            background: #FFFFFF;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-top: 20px;
        }
        .table-wrapper {
            overflow-x: auto;
            border: 1px solid #E5E7EB;
            border-radius: 10px;
            margin-top: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            min-width: 1000px;
        }
        thead {
            background: #1F4A5E;
            color: #FFFFFF;
        }
        th {
            padding: 14px 16px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }
        td {
            padding: 14px 16px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: middle;
        }
        tbody tr {
            transition: background 0.2s ease;
        }
        tbody tr:hover {
            background: #F9FAFB;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: capitalize;
        }
        .status-active {
            background: #D1FAE5;
            color: #065F46;
        }
        .status-inactive {
            background: #FEE2E2;
            color: #991B1B;
        }
        .status-suspend {
            background: #FEF3C7;
            color: #92400E;
        }
        .action-buttons {
            display: flex;
            gap: 8px;
        }
        .btn-action {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }
        .btn-edit {
            background: #2C5F7C;
            color: white;
        }
        .btn-edit:hover {
            background: #1F4A5E;
            transform: scale(1.1);
        }
        .btn-delete {
            background: #4B5563;
            color: white;
        }
        .btn-delete:hover {
            background: #374151;
            transform: scale(1.1);
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
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
            animation: slideUp 0.3s ease;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            
            /* Hide scrollbar for Chrome, Safari and Opera */
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none;  /* IE and Edge */
        }
        .modal-content::-webkit-scrollbar {
            display: none; /* Chrome, Safari, Opera */
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
        .form-group {
            margin-bottom: 20px;
        }
        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }
        .form-input, .form-select {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        .form-input:focus, .form-select:focus {
            outline: none;
            border-color: #2C5F7C;
            box-shadow: 0 0 0 3px rgba(44, 95, 124, 0.1);
        }
        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 30px;
        }
        .btn-submit {
            flex: 1;
            padding: 12px 24px;
            background: linear-gradient(135deg, #2C5F7C 0%, #1F4A5E 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(44, 95, 124, 0.3);
        }
        .btn-cancel {
            flex: 1;
            padding: 12px 24px;
            background: #F3F4F6;
            color: #374151;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-cancel:hover {
            background: #E5E7EB;
        }
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 13px;
            font-weight: 500;
        }
        .alert-success {
            background: #D1FAE5;
            color: #065F46;
            border: 1px solid #A7F3D0;
        }
    </x-slot>
    
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
    
    <div class="table-card">
        <div class="table-wrapper">
            <table id="usersTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Campaign</th>
                        <th>Role</th>
                        <th>Area</th>
                        <th>Site</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->campaign ?? '-' }}</td>
                        <td style="text-transform: capitalize;">{{ str_replace('_', ' ', $user->role) }}</td>
                        <td>{{ $user->area ?? '-' }}</td>
                        <td>{{ $user->site ?? '-' }}</td>
                        <td>
                            <span class="status-badge status-{{ $user->status ?? 'active' }}">
                                {{ $user->status ?? 'Active' }}
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-action btn-edit" onclick="openEditModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->role }}', '{{ $user->campaign }}', '{{ $user->site }}', '{{ $user->area }}', '{{ $user->status ?? 'active' }}')" title="Edit Role" style="width: auto; padding: 0 12px; gap: 6px;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                    <span>Edit</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 40px; color: #9CA3AF;">
                            No users found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Add User Modal -->
    <div id="addUserModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Add New User</h2>
                <button class="close-modal" onclick="closeAddUserModal()">&times;</button>
            </div>
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Name *</label>
                    <input type="text" name="name" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Username *</label>
                    <input type="text" name="username" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Password *</label>
                    <input type="password" name="password" class="form-input" required minlength="8">
                </div>
                <div class="form-group">
                    <label class="form-label">Role *</label>
                    <select name="role" class="form-select" required>
                        <option value="">Select Role</option>
                        <option value="admin">Admin</option>
                        <option value="team_leader">Team Leader</option>
                        <option value="agent">Agent</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Campaign</label>
                    <input type="text" name="campaign" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Area</label>
                    <input type="text" name="area" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Site</label>
                    <input type="text" name="site" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-input">
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="closeAddUserModal()">Cancel</button>
                    <button type="submit" class="btn-submit">Create User</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Edit Role Modal -->
    <div id="editRoleModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Edit User Role</h2>
                <button class="close-modal" onclick="closeEditModal()">&times;</button>
            </div>
            <form id="editRoleForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="form-group">
                    <label class="form-label">User Name</label>
                    <input type="text" id="editUserName" class="form-input" disabled>
                </div>
                <div class="form-group">
                    <label class="form-label">Role *</label>
                    <select name="role" id="editRole" class="form-select" required>
                        <option value="admin">Admin</option>
                        <option value="team_leader">Team Leader</option>
                        <option value="agent">Agent</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Campaign</label>
                    <input type="text" name="campaign" id="editCampaign" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Area</label>
                    <input type="text" name="area" id="editArea" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Site</label>
                    <input type="text" name="site" id="editSite" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Status *</label>
                    <select name="status" id="editStatus" class="form-select" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="closeEditModal()">Cancel</button>
                    <button type="submit" class="btn-submit">Update Role</button>
                </div>
            </form>
        </div>
    </div>
    
    
    <x-slot name="additionalScripts">
        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('#usersTable tbody tr');
            
            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchValue) ? '' : 'none';
            });
        });
        
        // Add User Modal
        function openAddUserModal() {
            document.getElementById('addUserModal').classList.add('active');
        }
        
        function closeAddUserModal() {
            document.getElementById('addUserModal').classList.remove('active');
        }
        
        // Edit Role Modal
        function openEditModal(id, name, role, campaign, site, area, status) {
            document.getElementById('editUserName').value = name;
            document.getElementById('editRole').value = role;
            document.getElementById('editCampaign').value = campaign || '';
            document.getElementById('editSite').value = site || '';
            document.getElementById('editArea').value = area || '';
            document.getElementById('editStatus').value = status || 'active';

            // Lock dropdown ganti status (dan role) jika admin mengedit dirinya sendiri
            const currentUserId = {{ auth()->id() }};
            const statusSelect = document.getElementById('editStatus');
            const roleSelect = document.getElementById('editRole');
            
            if (id === currentUserId) {
                statusSelect.style.pointerEvents = 'none';
                statusSelect.style.backgroundColor = '#f3f4f6';
                statusSelect.tabIndex = -1;

                roleSelect.style.pointerEvents = 'none';
                roleSelect.style.backgroundColor = '#f3f4f6';
                roleSelect.tabIndex = -1;
            } else {
                statusSelect.style.pointerEvents = 'auto';
                statusSelect.style.backgroundColor = '';
                statusSelect.removeAttribute('tabindex');

                roleSelect.style.pointerEvents = 'auto';
                roleSelect.style.backgroundColor = '';
                roleSelect.removeAttribute('tabindex');
            }

            document.getElementById('editRoleForm').action = `{{ url('admin/users') }}/${id}/role`;
            document.getElementById('editRoleModal').classList.add('active');
        }
        
        function closeEditModal() {
            document.getElementById('editRoleModal').classList.remove('active');
        }
        

        
        // Close modals when clicking outside
        window.onclick = function(event) {
            const addModal = document.getElementById('addUserModal');
            const editModal = document.getElementById('editRoleModal');
            
            if (event.target === addModal) {
                closeAddUserModal();
            }
            if (event.target === editModal) {
                closeEditModal();
            }
        }
        
        // Auto-hide success message
        setTimeout(() => {
            const alert = document.querySelector('.alert-success');
            if (alert) {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        }, 3000);
    </x-slot>
</x-agent-layout>
