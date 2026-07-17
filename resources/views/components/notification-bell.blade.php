<div class="notification-container">
    <button class="notification-bell" id="notificationBell" onclick="toggleNotifications()">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
        </svg>
        <span class="notification-badge" id="notificationBadge" style="display: none;">0</span>
    </button>
    
    <div class="notification-dropdown" id="notificationDropdown">
        <div class="notification-header">
            <h3>Notifications</h3>
            <button class="mark-all-read" onclick="markAllAsRead()">Mark all as read</button>
        </div>
        <div class="notification-list" id="notificationList">
            <div class="notification-loading">
                <p>Loading notifications...</p>
            </div>
        </div>
    </div>
</div>

<style>
    .notification-container {
        position: relative;
    }
    
    .notification-bell {
        position: relative;
        background: none;
        border: none;
        cursor: pointer;
        padding: 8px;
        border-radius: 50%;
        transition: all 0.3s ease;
        color: #666;
    }
    
    .notification-bell:hover {
        background: #F3F4F6;
        color: #2C5F7C;
    }
    
    .notification-bell.has-notifications {
        color: #2C5F7C;
        animation: bellRing 0.5s ease-in-out;
    }
    
    @keyframes bellRing {
        0%, 100% { transform: rotate(0deg); }
        10%, 30%, 50%, 70%, 90% { transform: rotate(-10deg); }
        20%, 40%, 60%, 80% { transform: rotate(10deg); }
    }
    
    .notification-badge {
        position: absolute;
        top: 4px;
        right: 4px;
        background: #EF4444;
        color: white;
        border-radius: 50%;
        width: 18px;
        height: 18px;
        font-size: 10px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }
    
    .notification-dropdown {
        position: absolute;
        top: calc(100% + 10px);
        right: 0;
        width: 380px;
        max-height: 500px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        display: none;
        flex-direction: column;
        z-index: 1000;
        animation: slideDown 0.3s ease;
    }
    
    .notification-dropdown.active {
        display: flex;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .notification-header {
        padding: 16px 20px;
        border-bottom: 1px solid #E5E7EB;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .notification-header h3 {
        font-size: 16px;
        font-weight: 700;
        color: #1F4A5E;
        margin: 0;
    }
    
    .mark-all-read {
        background: none;
        border: none;
        color: #2C5F7C;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        padding: 4px 8px;
        border-radius: 4px;
        transition: background 0.2s ease;
    }
    
    .mark-all-read:hover {
        background: #F3F4F6;
    }
    
    .notification-list {
        overflow-y: auto;
        max-height: 400px;
        
        /* Custom scrollbar */
        scrollbar-width: thin;
        scrollbar-color: #2C5F7C #E5E7EB;
    }
    
    .notification-list::-webkit-scrollbar {
        width: 6px;
    }
    
    .notification-list::-webkit-scrollbar-track {
        background: #E5E7EB;
    }
    
    .notification-list::-webkit-scrollbar-thumb {
        background: #2C5F7C;
        border-radius: 10px;
    }
    
    .notification-item {
        padding: 16px 20px;
        border-bottom: 1px solid #F3F4F6;
        cursor: pointer;
        transition: background 0.2s ease;
        display: flex;
        gap: 12px;
    }
    
    .notification-item:hover {
        background: #F9FAFB;
    }
    
    .notification-item.unread {
        background: #EFF6FF;
    }
    
    .notification-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #2C5F7C 0%, #1F4A5E 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        flex-shrink: 0;
    }
    
    .notification-content {
        flex: 1;
        min-width: 0;
    }
    
    .notification-title {
        font-size: 13px;
        font-weight: 600;
        color: #1F4A5E;
        margin-bottom: 4px;
    }
    
    .notification-message {
        font-size: 12px;
        color: #666;
        margin-bottom: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .notification-time {
        font-size: 11px;
        color: #9CA3AF;
    }
    
    .notification-loading,
    .notification-empty {
        padding: 40px 20px;
        text-align: center;
        color: #9CA3AF;
    }
    
    .notification-empty svg {
        width: 48px;
        height: 48px;
        margin: 0 auto 12px;
        opacity: 0.3;
    }
</style>

<script>
    let notificationDropdownOpen = false;
    let notificationCheckInterval;
    let userId = {{ auth()->id() }};
    let userRole = '{{ auth()->user()->role }}';

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

    // Toggle notification dropdown
    function toggleNotifications() {
        const dropdown = document.getElementById('notificationDropdown');
        notificationDropdownOpen = !notificationDropdownOpen;

        if (notificationDropdownOpen) {
            dropdown.classList.add('active');
            loadNotifications();
        } else {
            dropdown.classList.remove('active');
        }
    }

    // Load notifications
    async function loadNotifications() {
        const listContainer = document.getElementById('notificationList');
        listContainer.innerHTML = '<div class="notification-loading"><p>Loading notifications...</p></div>';

        try {
            const response = await fetch('/notifications/unread', {
                headers: {
                    'Accept': 'application/json'
                }
            });
            
            if (response.status === 401) {
                window.location.href = '/login';
                return;
            }
            
            const data = await response.json();

            if (data.notifications.length === 0) {
                listContainer.innerHTML = `
                    <div class="notification-empty">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                        </svg>
                        <p>No new notifications</p>
                    </div>
                `;
            } else {
                listContainer.innerHTML = data.notifications.map(notif => `
                    <div class="notification-item unread" onclick="viewTicket(${notif.ticket_id})">
                        <div class="notification-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10 9 9 9 8 9"></polyline>
                            </svg>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">${escapeHtml(notif.title)}</div>
                            <div class="notification-message">${escapeHtml(notif.message)} - ${escapeHtml(notif.ticket_type) || 'N/A'}</div>
                            <div class="notification-time">${escapeHtml(notif.created_at)}</div>
                        </div>
                    </div>
                `).join('');
            }
        } catch (error) {
            listContainer.innerHTML = '<div class="notification-empty"><p>Error loading notifications</p></div>';
            console.error('Error loading notifications:', error);
        }
    }

    // Update notification badge
    async function updateNotificationBadge() {
        try {
            const response = await fetch('/notifications/count', {
                headers: {
                    'Accept': 'application/json'
                }
            });
            
            if (response.status === 401) {
                window.location.href = '/login';
                return;
            }
            
            const data = await response.json();

            const badge = document.getElementById('notificationBadge');
            const bell = document.getElementById('notificationBell');

            if (data.count > 0) {
                badge.textContent = data.count > 99 ? '99+' : data.count;
                badge.style.display = 'flex';
                bell.classList.add('has-notifications');

                // Play notification sound (optional)
                // playNotificationSound();
            } else {
                badge.style.display = 'none';
                bell.classList.remove('has-notifications');
            }
        } catch (error) {
            console.error('Error updating notification badge:', error);
        }
    }

    // Add new notification to the dropdown
    function addNewNotification(notification) {
        const listContainer = document.getElementById('notificationList');

        // Remove empty state if exists
        const emptyState = listContainer.querySelector('.notification-empty');
        if (emptyState) {
            emptyState.remove();
        }

        // Add new notification at the top
        const newNotifHtml = `
            <div class="notification-item unread" onclick="viewTicket(${notification.ticket_id})">
                <div class="notification-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                </div>
                <div class="notification-content">
                    <div class="notification-title">${escapeHtml(notification.title)}</div>
                    <div class="notification-message">${escapeHtml(notification.message)} - ${escapeHtml(notification.ticket_type) || 'N/A'}</div>
                    <div class="notification-time">${escapeHtml(notification.created_at || 'Just now')}</div>
                </div>
            </div>
        `;

        // Insert at the beginning
        listContainer.insertAdjacentHTML('afterbegin', newNotifHtml);

        // Update badge
        const badge = document.getElementById('notificationBadge');
        const bell = document.getElementById('notificationBell');
        let currentCount = parseInt(badge.textContent) || 0;
        currentCount++;
        badge.textContent = currentCount > 99 ? '99+' : currentCount;
        badge.style.display = 'flex';
        bell.classList.add('has-notifications');

        // Show toast notification
        showToastNotification(notification);
    }

    // Show toast notification
    function showToastNotification(notification) {
        // We will create the toast using safe textContent setters to completely prevent XSS
        const toast = document.createElement('div');
        
        // Urgency color: >=3 red, 2 warning (orange), others green
        let borderLeftColor = '#10B981'; // Green
        const urgencyVal = notification.urgency !== undefined && notification.urgency !== null ? parseInt(notification.urgency) : 1;
        if (urgencyVal >= 3) {
            borderLeftColor = '#EF4444'; // Red
        } else if (urgencyVal === 2) {
            borderLeftColor = '#F59E0B'; // Orange
        }

        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            border-left: 6px solid ${borderLeftColor};
            display: flex;
            flex-direction: column;
            gap: 8px;
            padding: 16px;
            z-index: 10000;
            width: 320px;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
            transform: translateX(120%);
            transition: transform 0.3s ease-out;
        `;

        // Close button
        const closeBtn = document.createElement('button');
        closeBtn.textContent = '×';
        closeBtn.style.cssText = `
            position: absolute;
            top: 8px;
            right: 12px;
            background: none;
            border: none;
            font-size: 20px;
            color: #9CA3AF;
            cursor: pointer;
            line-height: 1;
        `;
        closeBtn.addEventListener('click', (event) => {
            event.stopPropagation(); // Prevent triggering the click to view ticket
            toast.style.transform = 'translateX(120%)';
            setTimeout(() => toast.remove(), 300);
        });

        // Title
        const title = document.createElement('p');
        title.style.cssText = 'font-weight: 700; font-size: 14px; color: #1E293B; margin: 0; padding-right: 15px;';
        title.textContent = notification.title ?? 'Tiket baru di-assign';

        // Message / Body
        const body = document.createElement('p');
        body.style.cssText = 'font-size: 12px; color: #4B5563; margin: 0;';
        
        let messageText = notification.message ?? '';
        if (notification.customer_name || notification.ticket_type) {
            messageText = `${notification.customer_name ?? '-'} - ${notification.ticket_type ?? ''}`;
        }
        body.textContent = messageText;

        // Buttons wrapper
        const btns = document.createElement('div');
        btns.style.cssText = 'display: flex; justify-content: flex-end; margin-top: 4px;';

        // "Buka" button
        const btn = document.createElement('button');
        btn.textContent = 'Buka';
        btn.style.cssText = `
            background: #1F4A5E;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        `;
        btn.addEventListener('mouseover', () => btn.style.background = '#153543');
        btn.addEventListener('mouseout', () => btn.style.background = '#1F4A5E');
        btn.addEventListener('click', (event) => {
            event.stopPropagation();
            viewTicket(notification.ticket_id);
        });

        btns.appendChild(btn);
        toast.append(closeBtn, title, body, btns);
        document.body.appendChild(toast);

        // Animate in
        setTimeout(() => {
            toast.style.transform = 'translateX(0)';
        }, 100);

        // Play beep sound using Web Audio API
        try {
            const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioCtx.createOscillator();
            const gainNode = audioCtx.createGain();
            
            oscillator.connect(gainNode);
            gainNode.connect(audioCtx.destination);
            
            oscillator.type = 'sine';
            oscillator.frequency.setValueAtTime(587.33, audioCtx.currentTime); // D5 note
            gainNode.gain.setValueAtTime(0.08, audioCtx.currentTime);
            
            oscillator.start();
            gainNode.gain.exponentialRampToValueAtTime(0.00001, audioCtx.currentTime + 0.4);
            oscillator.stop(audioCtx.currentTime + 0.4);
        } catch (e) {
            console.log('Web Audio notification error:', e);
        }

        // Auto remove after 6 seconds
        setTimeout(() => {
            if (toast.parentElement) {
                toast.style.transform = 'translateX(120%)';
                setTimeout(() => toast.remove(), 300);
            }
        }, 6000);

        // Click to view ticket when clicking anywhere on toast
        toast.addEventListener('click', () => {
            viewTicket(notification.ticket_id);
        });
    }

    // Mark all as read
    async function markAllAsRead() {
        try {
            await fetch('/notifications/mark-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            updateNotificationBadge();
            loadNotifications();
        } catch (error) {
            console.error('Error marking notifications as read:', error);
        }
    }

    // View ticket
    function viewTicket(ticketId) {
        // Redirect to ticket detail page
        window.location.href = `/agent/tickets/${ticketId}`;
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const container = document.querySelector('.notification-container');
        if (container && !container.contains(event.target) && notificationDropdownOpen) {
            toggleNotifications();
        }
    });



    // ── Smart Polling: detect new notifications and show toast automatically ──
    let lastKnownCount = -1;          // -1 = not yet initialized
    let lastKnownTimestamp = null;     // ISO8601 of the most recent notification we've seen
    let echoConnected = false;

    // Poll for new notifications (primary mechanism — works without Reverb)
    async function pollNotifications() {
        try {
            const response = await fetch('/notifications/unread', {
                headers: { 'Accept': 'application/json' }
            });

            if (response.status === 401) {
                window.location.href = '/login';
                return;
            }

            const data = await response.json();
            const newCount = data.count ?? 0;
            const notifications = data.notifications ?? [];

            // Update badge
            const badge = document.getElementById('notificationBadge');
            const bell = document.getElementById('notificationBell');
            if (newCount > 0) {
                badge.textContent = newCount > 99 ? '99+' : newCount;
                badge.style.display = 'flex';
                bell.classList.add('has-notifications');
            } else {
                badge.style.display = 'none';
                bell.classList.remove('has-notifications');
            }

            // On first load, just record the state (don't show toasts for existing notifs)
            if (lastKnownCount === -1) {
                lastKnownCount = newCount;
                if (notifications.length > 0) {
                    lastKnownTimestamp = notifications[0].timestamp ?? null;
                }
                return;
            }

            // If count increased, we have new notifications — show toast for each new one
            if (newCount > lastKnownCount && notifications.length > 0) {
                const newNotifs = [];

                for (const notif of notifications) {
                    // If we have a timestamp marker, only show notifs newer than it
                    if (lastKnownTimestamp && notif.timestamp && notif.timestamp <= lastKnownTimestamp) {
                        break;
                    }
                    newNotifs.push(notif);
                }

                // Show in chronological order (oldest first) so toasts stack naturally
                newNotifs.reverse().forEach(notif => {
                    addNewNotification(notif);
                });
            }

            // Update trackers
            lastKnownCount = newCount;
            if (notifications.length > 0) {
                lastKnownTimestamp = notifications[0].timestamp ?? null;
            }

        } catch (error) {
            console.error('Error polling notifications:', error);
        }
    }

    // Start polling as primary mechanism
    function startNotificationPolling() {
        pollNotifications(); // Initial check
        // Poll every 5 seconds for near-real-time responsiveness
        notificationCheckInterval = setInterval(pollNotifications, 5000);
    }

    // Real-time notifications with Laravel Echo/Reverb (enhancement on top of polling)
    function initReverbNotifications() {
        let checkCount = 0;
        const checkInterval = setInterval(() => {
            checkCount++;
            if (typeof window.Echo !== 'undefined') {
                clearInterval(checkInterval);
                setupEchoListeners();
            } else if (checkCount > 100) { // Check for up to 10 seconds
                clearInterval(checkInterval);
                console.log('Laravel Echo not available — relying on polling for notifications.');
            }
        }, 100);
    }

    function setupEchoListeners() {
        // Listen for private channel based on user role
        const channelName = userRole === 'agent' ? `agent.${userId}` : `${userRole}.${userId}`;

        console.log('Echo connected. Listening on channel:', channelName);
        echoConnected = true;

        window.Echo.private(channelName)
            .listen('.ticket.assigned', (e) => {
                console.log('Real-time notification received:', e);
                // Immediately show toast + badge (don't wait for poll)
                addNewNotification(e);
                // Bump the lastKnownCount so the next poll doesn't double-fire
                lastKnownCount++;
            })
            .listen('TicketAssigned', (e) => {
                console.log('TicketAssigned event received:', e);
                addNewNotification(e);
                lastKnownCount++;
            });
    }

    // Start when page loads
    document.addEventListener('DOMContentLoaded', function() {
        startNotificationPolling();
        initReverbNotifications();
    });

    // Clean up interval when page unloads
    window.addEventListener('beforeunload', function() {
        if (notificationCheckInterval) {
            clearInterval(notificationCheckInterval);
        }
    });
</script>
