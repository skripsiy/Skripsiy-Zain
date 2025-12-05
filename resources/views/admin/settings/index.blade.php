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
            width: 50px;
            height: 26px;
            background: #E5E7EB;
            border-radius: 13px;
            cursor: pointer;
            transition: 0.3s;
        }
        .toggle-switch.active {
            background: #10B981;
        }
        .toggle-switch::before {
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
        .toggle-switch.active::before {
            left: 27px;
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
        @media (max-width: 768px) {
            .settings-grid {
                grid-template-columns: 1fr;
            }
        }
    </x-slot>
    
    <div class="settings-grid">
        <div class="settings-card">
            <h3>General Settings</h3>
            <div class="setting-item">
                <div>
                    <div class="setting-label">Auto-assign Tickets</div>
                    <div class="setting-description">Automatically assign incoming tickets to available agents</div>
                </div>
                <div class="toggle-switch active" onclick="this.classList.toggle('active')"></div>
            </div>
            <div class="setting-item">
                <div>
                    <div class="setting-label">Email Notifications</div>
                    <div class="setting-description">Send email notifications for new tickets</div>
                </div>
                <div class="toggle-switch active" onclick="this.classList.toggle('active')"></div>
            </div>
            <div class="setting-item">
                <div>
                    <div class="setting-label">Maintenance Mode</div>
                    <div class="setting-description">Put the system in maintenance mode</div>
                </div>
                <div class="toggle-switch" onclick="this.classList.toggle('active')"></div>
            </div>
        </div>

        <div class="settings-card">
            <h3>Notification Settings</h3>
            <div class="setting-item">
                <div>
                    <div class="setting-label">Push Notifications</div>
                    <div class="setting-description">Enable browser push notifications</div>
                </div>
                <div class="toggle-switch active" onclick="this.classList.toggle('active')"></div>
            </div>
            <div class="setting-item">
                <div>
                    <div class="setting-label">Sound Alerts</div>
                    <div class="setting-description">Play sound for new notifications</div>
                </div>
                <div class="toggle-switch" onclick="this.classList.toggle('active')"></div>
            </div>
            <div class="setting-item">
                <div>
                    <div class="setting-label">Desktop Notifications</div>
                    <div class="setting-description">Show desktop notifications</div>
                </div>
                <div class="toggle-switch active" onclick="this.classList.toggle('active')"></div>
            </div>
        </div>

        <div class="settings-card">
            <h3>Security Settings</h3>
            <div class="setting-item">
                <div>
                    <div class="setting-label">Two-Factor Authentication</div>
                    <div class="setting-description">Require 2FA for admin accounts</div>
                </div>
                <div class="toggle-switch" onclick="this.classList.toggle('active')"></div>
            </div>
            <div class="setting-item">
                <div>
                    <div class="setting-label">Session Timeout</div>
                    <div class="setting-description">Auto logout after 30 minutes of inactivity</div>
                </div>
                <div class="toggle-switch active" onclick="this.classList.toggle('active')"></div>
            </div>
            <div class="setting-item">
                <div>
                    <div class="setting-label">IP Whitelist</div>
                    <div class="setting-description">Restrict access to specific IP addresses</div>
                </div>
                <div class="toggle-switch" onclick="this.classList.toggle('active')"></div>
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
        <button class="btn-save" onclick="saveSettings()">Save All Settings</button>
    </div>

    <x-slot name="additionalScripts">
        function saveSettings() {
            window.showToast('Settings saved successfully!', 'success');
        }
    </x-slot>
</x-agent-layout>
