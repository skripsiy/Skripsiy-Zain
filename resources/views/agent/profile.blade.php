<x-agent-layout>
    <x-slot name="title">My Profile</x-slot>

    <x-slot name="headerContent">
        <div class="header-actions">
            <a class="back-btn" href="{{ route('agent.dashboard') }}">← Back to Dashboar</a>
        </div>
    </x-slot>

    <x-slot name="sidebar">
        <a class="sidebar-icon" href="{{ route('agent.dashboard') }}" title="Dashboard">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z" />
            </svg>
        </a>
        <a class="sidebar-icon" href="{{ route('agent.tickets') }}" title="Tickets">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path
                    d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z" />
            </svg>
        </a>
        <a class="sidebar-icon active" href="{{ route('agent.profile') }}" title="My Profile">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path
                    d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
            </svg>
        </a>
    </x-slot>

    <x-slot name="customStyles">
        .profile-container {
        max-width: 1100px;
        margin: 0 auto;
        padding: 20px 16px;
        display: flex;
        flex-direction: column;
        gap: 16px;
        }
        .card {
        background: #FFFFFF;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        padding: 18px;
        }
        .profile-hero {
        display: flex;
        align-items: center;
        gap: 24px;
        }
        .avatar {
        width: 130px;
        height: 130px;
        border-radius: 50%;
        background: #E6EEF3;
        border: 4px solid #1F4A5E;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        color: #1F4A5E;
        font-weight: 700;
        }
        .identity {
        display: flex;
        flex-direction: column;
        gap: 6px;
        }
        .name {
        font-size: 28px;
        font-weight: 700;
        color: #1F2A37;
        }
        .role {
        font-size: 16px;
        color: #2D6D8B;
        font-weight: 600;
        }
        .grid {
        display: grid;
        grid-template-columns: 1.2fr 1fr 0.8fr;
        gap: 12px;
        }
        .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #1F4A5E;
        margin-bottom: 10px;
        }
        .info-table {
        width: 100%;
        border: 1px solid #E5E7EB;
        border-radius: 8px;
        padding: 12px;
        }
        .row {
        display: grid;
        grid-template-columns: 1fr auto 1fr;
        gap: 8px;
        padding: 6px 8px;
        font-size: 13px;
        }
        .row + .row {
        border-top: 1px dashed #E5E7EB;
        }
        .label {
        color: #6B7280;
        }
        .colon {
        color: #9CA3AF;
        }
        .value {
        font-weight: 600;
        color: #111827;
        }
        .input {
        width: 100%;
        background: #E9EEF2;
        border: 1px solid #E5E7EB;
        border-radius: 8px;
        height: 36px;
        padding: 0 12px;
        font-family: 'Poppins', sans-serif;
        color: #111827;
        font-size: 13px;
        }
        .input:focus {
        outline: none;
        border-color: #1F4A5E;
        background: #fff;
        }
        .alert {
        padding: 10px 14px;
        border-radius: 6px;
        margin-bottom: 12px;
        font-size: 13px;
        }
        .alert-success {
        background: #D1FAE5;
        color: #065F46;
        border: 1px solid #6EE7B7;
        }
        .alert-error {
        background: #FEE2E2;
        color: #991B1B;
        border: 1px solid #FCA5A5;
        }
        .form-edit {
        display: none;
        }
        .btn {
        background: #1F4A5E;
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 10px 14px;
        font-size: 12px;
        cursor: pointer;
        font-family: 'Poppins', sans-serif;
        }
        .btn:hover {
        opacity: 0.9;
        }
        .btn.secondary {
        background: #F3F4F6;
        color: #111827;
        border: 1px solid #E5E7EB;
        }
        .btn.secondary:hover {
        background: #E5E7EB;
        }
        .stack {
        display: flex;
        flex-direction: column;
        gap: 10px;
        }
        .list {
        border: 1px solid #E5E7EB;
        border-radius: 8px;
        padding: 10px 12px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        font-size: 13px;
        }
        .link {
        color: #1F4A5E;
        text-decoration: none;
        }
        .actions {
        display: flex;
        gap: 10px;
        margin-top: 12px;
        }
        .back-btn {
        padding: 8px 12px;
        border: 1px solid #D0D0D0;
        background: #fff;
        border-radius: 6px;
        cursor: pointer;
        font-size: 12px;
        text-decoration: none;
        color: #1F2A37;
        }
        .header-actions {
        display: flex;
        gap: 10px;
        align-items: center;
        }
        @media (max-width: 992px) {
        .grid {
        grid-template-columns: 1fr;
        }
        .profile-hero {
        align-items: flex-start;
        }
        }
    </x-slot>

    <div class="profile-container">
        @if(session('success'))
            <div class="alert alert-success">✓ {{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-error">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="profile-hero">
                <div class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                <div class="identity">
                    <div class="name">{{ Auth::user()->name }}</div>
                    <div class="role">Agent</div>
                </div>
            </div>
        </div>

        <div class="grid">
            <div class="card">
                <div class="section-title">Personal Information</div>
                <div class="info-table" id="infoDisplay">
                    <div class="row">
                        <div class="label">Name</div>
                        <div class="colon">:</div>
                        <div class="value">{{ Auth::user()->name }}</div>
                    </div>
                    <div class="row">
                        <div class="label">Campaign</div>
                        <div class="colon">:</div>
                        <div class="value">{{ Auth::user()->campaign ?? '-' }}</div>
                    </div>
                    <div class="row">
                        <div class="label">Site</div>
                        <div class="colon">:</div>
                        <div class="value">{{ Auth::user()->site ?? '-' }}</div>
                    </div>
                    <div class="row">
                        <div class="label">Username</div>
                        <div class="colon">:</div>
                        <div class="value">{{ Auth::user()->username ?? Str::slug(Auth::user()->name, '_') }}</div>
                    </div>
                    <div class="row">
                        <div class="label">Email</div>
                        <div class="colon">:</div>
                        <div class="value">{{ Auth::user()->email }}</div>
                    </div>
                    <div class="row">
                        <div class="label">No. telp</div>
                        <div class="colon">:</div>
                        <div class="value">{{ Auth::user()->phone ?? '-' }}</div>
                    </div>
                </div>
                <form method="POST" action="{{ route('agent.profile.update') }}" class="form-edit" id="editForm">
                    @csrf
                    @method('PATCH')
                    <div class="stack">
                        <div style="margin-bottom: 10px;">
                            <label style="font-size: 11px; color: #6b7280; display: block; margin-bottom: 2px; font-weight: 500;">Name (Read-only)</label>
                            <input class="input" type="text" value="{{ Auth::user()->name }}" disabled style="opacity: 0.65; cursor: not-allowed; background: #e5e7eb;">
                        </div>
                        <div style="margin-bottom: 10px;">
                            <label style="font-size: 11px; color: #6b7280; display: block; margin-bottom: 2px; font-weight: 500;">Campaign/Division (Read-only)</label>
                            <input class="input" type="text" value="{{ Auth::user()->campaign ?? '-' }}" disabled style="opacity: 0.65; cursor: not-allowed; background: #e5e7eb;">
                        </div>
                        <div style="margin-bottom: 10px;">
                            <label style="font-size: 11px; color: #6b7280; display: block; margin-bottom: 2px; font-weight: 500;">Site (Read-only)</label>
                            <input class="input" type="text" value="{{ Auth::user()->site ?? '-' }}" disabled style="opacity: 0.65; cursor: not-allowed; background: #e5e7eb;">
                            <small style="color: #6b7280; font-size: 11px; margin-top: 2px; display: block;">Hubungi admin untuk mengubah data ini</small>
                        </div>
                        
                        <div style="margin-bottom: 10px;">
                            <label style="font-size: 11px; color: #6b7280; display: block; margin-bottom: 2px; font-weight: 500;">Username</label>
                            <input class="input" type="text" name="username" placeholder="Username"
                                value="{{ old('username', Auth::user()->username) }}" required>
                        </div>
                        <div style="margin-bottom: 10px;">
                            <label style="font-size: 11px; color: #6b7280; display: block; margin-bottom: 2px; font-weight: 500;">Email</label>
                            <input class="input" type="email" name="email" placeholder="Email"
                                value="{{ old('email', Auth::user()->email) }}" required>
                        </div>
                        <div style="margin-bottom: 10px;">
                            <label style="font-size: 11px; color: #6b7280; display: block; margin-bottom: 2px; font-weight: 500;">Phone Number</label>
                            <input class="input" type="text" name="phone" placeholder="Phone Number"
                                value="{{ old('phone', Auth::user()->phone) }}">
                        </div>
                    </div>
                </form>
                <div class="actions">
                    <button class="btn" id="editBtn" onclick="toggleEdit()">Edit Profile</button>
                    <button class="btn" id="saveBtn" style="display:none;"
                        onclick="document.getElementById('editForm').submit()">Save Changes</button>
                    <button class="btn secondary" id="cancelBtn" style="display:none;"
                        onclick="toggleEdit()">Cancel</button>
                </div>
            </div>

            <div class="card">
                <div class="section-title">Change Password</div>
                <form method="POST" action="{{ route('agent.profile.password') }}">
                    @csrf
                    @method('PATCH')
                    <div class="stack">
                        <input class="input" type="password" name="current_password" placeholder="Current Password"
                            required>
                        <input class="input" type="password" name="password" placeholder="New Password" required>
                        <input class="input" type="password" name="password_confirmation"
                            placeholder="Confirm New Password" required>
                    </div>
                    <div class="actions">
                        <button type="submit" class="btn">Update Password</button>
                        <a class="btn secondary" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                    </div>
                </form>
                <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display:none;">@csrf</form>
            </div>

            <div class="card">
                <div class="section-title">Personalization</div>
                <div class="list">
                    <div>Dark Mode</div>
                    <div><a class="link" href="{{ route('agent.dashboard') }}">Back to Dashboard</a></div>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="additionalScripts">
        function toggleEdit() {
        const infoDisplay = document.getElementById('infoDisplay');
        const editForm = document.getElementById('editForm');
        const editBtn = document.getElementById('editBtn');
        const saveBtn = document.getElementById('saveBtn');
        const cancelBtn = document.getElementById('cancelBtn');

        if (editForm.style.display === 'none' || editForm.style.display === '') {
        infoDisplay.style.display = 'none';
        editForm.style.display = 'block';
        editBtn.style.display = 'none';
        saveBtn.style.display = 'inline-block';
        cancelBtn.style.display = 'inline-block';
        } else {
        infoDisplay.style.display = 'block';
        editForm.style.display = 'none';
        editBtn.style.display = 'inline-block';
        saveBtn.style.display = 'none';
        cancelBtn.style.display = 'none';
        }
        }
    </x-slot>
</x-agent-layout>