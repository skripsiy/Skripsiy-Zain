<x-agent-layout>
    <x-slot name="title">Settings</x-slot>
    
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
        <a class="sidebar-icon" href="{{ route('admin.reports.index') }}" title="Reports">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
            </svg>
        </a>
        <a class="sidebar-icon active" href="{{ route('admin.settings.index') }}" title="Settings">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M19.14,12.94c0.04-0.3,0.06-0.61,0.06-0.94c0-0.32-0.02-0.64-0.07-0.94l2.03-1.58c0.18-0.14,0.23-0.41,0.12-0.61 l-1.92-3.32c-0.12-0.22-0.37-0.29-0.59-0.22l-2.39,0.96c-0.5-0.38-1.03-0.7-1.62-0.94L14.4,2.81c-0.04-0.24-0.24-0.41-0.48-0.41 h-3.84c-0.24,0-0.43,0.17-0.47,0.41L9.25,5.35C8.66,5.59,8.12,5.92,7.63,6.29L5.24,5.33c-0.22-0.08-0.47,0-0.59,0.22L2.74,8.87 C2.62,9.08,2.66,9.34,2.86,9.48l2.03,1.58C4.84,11.36,4.8,11.69,4.8,12s0.02,0.64,0.07,0.94l-2.03,1.58 c-0.18,0.14-0.23,0.41-0.12,0.61l1.92,3.32c0.12,0.22,0.37,0.29,0.59,0.22l2.39-0.96c0.5,0.38,1.03,0.7,1.62,0.94l0.36,2.54 c0.05,0.24,0.24,0.41,0.48,0.41h3.84c0.24,0,0.44-0.17,0.47-0.41l0.36-2.54c0.59-0.24,1.13-0.56,1.62-0.94l2.39,0.96 c0.22,0.08,0.47,0,0.59-0.22l1.92-3.32c0.12-0.22,0.07-0.47-0.12-0.61L19.14,12.94z M12,15.6c-1.98,0-3.6-1.62-3.6-3.6 s1.62-3.6,3.6-3.6s3.6,1.62,3.6,3.6S13.98,15.6,12,15.6z"/>
            </svg>
        </a>
    </x-slot>
    
    <x-slot name="customStyles">
        .settings-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        .settings-card {
            background: white;
            border-radius: 10px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .settings-card h3 {
            font-size: 16px;
            font-weight: 600;
            color: #1F2A37;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #E5E7EB;
        }
        .setting-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 0;
            border-bottom: 1px solid #F3F4F6;
        }
        .setting-item:last-child {
            border-bottom: none;
        }
        .setting-label {
            font-size: 14px;
            color: #374151;
            font-weight: 500;
        }
        .setting-description {
            font-size: 12px;
            color: #6B7280;
            margin-top: 4px;
        }
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 26px;
        }
        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #E5E7EB;
            transition: .4s;
            border-radius: 26px;
        }
        .slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        input:checked + .slider {
            background-color: #10B981;
        }
        input:checked + .slider:before {
            transform: translateX(24px);
        }
        .btn-save {
            background: linear-gradient(135deg, #1F4A5E 0%, #2D6D8B 100%);
            color: white;
            padding: 12px 32px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(31, 74, 94, 0.3);
        }
        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(31, 74, 94, 0.4);
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
        @media (max-width: 768px) {
            .settings-grid {
                grid-template-columns: 1fr;
            }
        }
    </x-slot>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        <div class="settings-grid">
        <div class="settings-card">
            <h3>General Settings</h3>
            <div class="setting-item">
                <div>
                    <div class="setting-label">Auto-assign Tickets</div>
                    <div class="setting-description">Automatically assign incoming tickets to available agents</div>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" name="auto_assign_tickets" {{ ($settings['auto_assign_tickets'] ?? '1') == '1' ? 'checked' : '' }}>
                    <span class="slider"></span>
                </label>
            </div>
            <div class="setting-item">
                <div>
                    <div class="setting-label">Email Notifications</div>
                    <div class="setting-description">Send email notifications for new tickets</div>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" name="email_notifications" {{ ($settings['email_notifications'] ?? '1') == '1' ? 'checked' : '' }}>
                    <span class="slider"></span>
                </label>
            </div>
            <div class="setting-item">
                <div>
                    <div class="setting-label">Maintenance Mode</div>
                    <div class="setting-description">Put the system in maintenance mode</div>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" name="maintenance_mode" {{ ($settings['maintenance_mode'] ?? '0') == '1' ? 'checked' : '' }}>
                    <span class="slider"></span>
                </label>
            </div>
        </div>

        <div class="settings-card">
            <h3>Notification Settings</h3>
            <div class="setting-item">
                <div>
                    <div class="setting-label">Push Notifications</div>
                    <div class="setting-description">Enable browser push notifications</div>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" name="push_notifications" {{ ($settings['push_notifications'] ?? '1') == '1' ? 'checked' : '' }}>
                    <span class="slider"></span>
                </label>
            </div>
            <div class="setting-item">
                <div>
                    <div class="setting-label">Sound Alerts</div>
                    <div class="setting-description">Play sound for new notifications</div>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" name="sound_alerts" {{ ($settings['sound_alerts'] ?? '0') == '1' ? 'checked' : '' }}>
                    <span class="slider"></span>
                </label>
            </div>
            <div class="setting-item">
                <div>
                    <div class="setting-label">Desktop Notifications</div>
                    <div class="setting-description">Show desktop notifications</div>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" name="desktop_notifications" {{ ($settings['desktop_notifications'] ?? '1') == '1' ? 'checked' : '' }}>
                    <span class="slider"></span>
                </label>
            </div>
        </div>

        <div class="settings-card">
            <h3>Security Settings</h3>
            <div class="setting-item">
                <div>
                    <div class="setting-label">Two-Factor Authentication</div>
                    <div class="setting-description">Require 2FA for admin accounts</div>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" name="two_factor_auth" {{ ($settings['two_factor_auth'] ?? '0') == '1' ? 'checked' : '' }}>
                    <span class="slider"></span>
                </label>
            </div>
            <div class="setting-item">
                <div>
                    <div class="setting-label">Session Timeout</div>
                    <div class="setting-description">Auto logout after 30 minutes of inactivity</div>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" name="session_timeout" {{ ($settings['session_timeout'] ?? '1') == '1' ? 'checked' : '' }}>
                    <span class="slider"></span>
                </label>
            </div>
            <div class="setting-item">
                <div>
                    <div class="setting-label">IP Whitelist</div>
                    <div class="setting-description">Restrict access to specific IP addresses</div>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" name="ip_whitelist" {{ ($settings['ip_whitelist'] ?? '0') == '1' ? 'checked' : '' }}>
                    <span class="slider"></span>
                </label>
            </div>
        </div>

        <div class="settings-card">
            <h3>System Information</h3>
            <div class="setting-item">
                <div>
                    <div class="setting-label">Application Version</div>
                    <div class="setting-description">v1.0.0</div>
                </div>
            </div>
            <div class="setting-item">
                <div>
                    <div class="setting-label">Laravel Version</div>
                    <div class="setting-description">{{ app()->version() }}</div>
                </div>
            </div>
            <div class="setting-item">
                <div>
                    <div class="setting-label">PHP Version</div>
                    <div class="setting-description">{{ phpversion() }}</div>
                </div>
            </div>
        </div>
    </div>

    <div style="text-align: center; margin-top: 30px;">
        <button type="submit" class="btn-save">Save All Settings</button>
    </div>
    </form>

    <x-slot name="additionalScripts">
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
