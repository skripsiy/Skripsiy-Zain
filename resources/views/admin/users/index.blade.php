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
        <a class="sidebar-icon" href="{{ route('admin.settings.index') }}" title="Settings">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M19.14,12.94c0.04-0.3,0.06-0.61,0.06-0.94c0-0.32-0.02-0.64-0.07-0.94l2.03-1.58c0.18-0.14,0.23-0.41,0.12-0.61 l-1.92-3.32c-0.12-0.22-0.37-0.29-0.59-0.22l-2.39,0.96c-0.5-0.38-1.03-0.7-1.62-0.94L14.4,2.81c-0.04-0.24-0.24-0.41-0.48-0.41 h-3.84c-0.24,0-0.43,0.17-0.47,0.41L9.25,5.35C8.66,5.59,8.12,5.92,7.63,6.29L5.24,5.33c-0.22-0.08-0.47,0-0.59,0.22L2.74,8.87 C2.62,9.08,2.66,9.34,2.86,9.48l2.03,1.58C4.84,11.36,4.8,11.69,4.8,12s0.02,0.64,0.07,0.94l-2.03,1.58 c-0.18,0.14-0.23,0.41-0.12,0.61l1.92,3.32c0.12,0.22,0.37,0.29,0.59,0.22l2.39-0.96c0.5,0.38,1.03,0.7,1.62,0.94l0.36,2.54 c0.05,0.24,0.24,0.41,0.48,0.41h3.84c0.24,0,0.44-0.17,0.47-0.41l0.36-2.54c0.59-0.24,1.13-0.56,1.62-0.94l2.39,0.96 c0.22,0.08,0.47,0,0.59-0.22l1.92-3.32c0.12-0.22,0.07-0.47-0.12-0.61L19.14,12.94z M12,15.6c-1.98,0-3.6-1.62-3.6-3.6 s1.62-3.6,3.6-3.6s3.6,1.62,3.6,3.6S13.98,15.6,12,15.6z"/>
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
                                <button class="btn-action btn-edit" onclick="openEditModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->role }}', '{{ $user->campaign }}', '{{ $user->site }}', '{{ $user->area }}', '{{ $user->status ?? 'active' }}')" title="Edit Role">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                </button>
                                <button class="btn-action btn-delete" onclick="confirmDelete({{ $user->id }}, '{{ $user->name }}')" title="Delete User">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M3 6h18"></path>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        <line x1="10" y1="11" x2="10" y2="17"></line>
                                        <line x1="14" y1="11" x2="14" y2="17"></line>
                                    </svg>
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
                        <option value="suspend">Suspend</option>
                    </select>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="closeEditModal()">Cancel</button>
                    <button type="submit" class="btn-submit">Update Role</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Delete Confirmation (using form) -->
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
    
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
            document.getElementById('editRoleForm').action = `/admin/users/${id}/role`;
            document.getElementById('editRoleModal').classList.add('active');
        }
        
        function closeEditModal() {
            document.getElementById('editRoleModal').classList.remove('active');
        }
        
        // Delete confirmation
        function confirmDelete(id, name) {
            if (confirm(`Are you sure you want to delete user "${name}"?`)) {
                const form = document.getElementById('deleteForm');
                form.action = `/admin/users/${id}`;
                form.submit();
            }
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
