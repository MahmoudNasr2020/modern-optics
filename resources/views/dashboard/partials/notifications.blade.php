<style>
    /* ══════════════════════════════════════════════════════════════
       🔔 NOTIFICATION DROPDOWN STYLES - FIXED VERSION
       ══════════════════════════════════════════════════════════════ */
    .notifications-wrapper {
        position: relative;
        display: inline-block;
        margin: 0 10px;
    }

    .notification-bell {
        position: relative;
        padding: 8px 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        border-radius: 8px;
        background: rgba(255,255,255,0.1);
    }

    .notification-bell:hover {
        background: rgba(255,255,255,0.25);
        transform: scale(1.05);
    }

    .notification-bell i {
        font-size: 22px;
        color: white;
    }

    .notification-badge {
        position: absolute;
        top: 3px;
        right: 8px;
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        color: white;
        border-radius: 12px;
        padding: 3px 7px;
        font-size: 11px;
        font-weight: 800;
        min-width: 20px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(231, 76, 60, 0.5);
        animation: pulse-badge 2s infinite;
    }

    @keyframes pulse-badge {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.15); }
    }

    .notifications-dropdown {
        position: absolute;
        top: calc(100% + 10px);
        right: 0;
        width: 380px;
        max-height: 550px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.25);
        z-index: 999999 !important; /* ✅ أعلى من كل حاجة */
        display: none;
        animation: slideDown 0.3s ease-out;
        overflow: hidden;
    }

    .notifications-dropdown.show {
        display: block !important;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-15px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .notifications-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 18px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .notifications-header h4 {
        margin: 0;
        font-size: 16px;
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .notifications-header .badge {
        background: rgba(255,255,255,0.3);
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 12px;
    }

    .notifications-actions {
        display: flex;
        gap: 10px;
    }

    .notifications-actions button {
        background: rgba(255,255,255,0.2);
        border: none;
        color: white;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.3s;
        font-weight: 600;
    }

    .notifications-actions button:hover {
        background: rgba(255,255,255,0.35);
        transform: scale(1.05);
    }

    .notifications-body {
        max-height: 400px;
        overflow-y: auto;
    }

    .notifications-body::-webkit-scrollbar {
        width: 6px;
    }

    .notifications-body::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .notifications-body::-webkit-scrollbar-thumb {
        background: #667eea;
        border-radius: 10px;
    }

    .notification-item {
        padding: 15px 20px;
        border-bottom: 1px solid #f0f0f0;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        gap: 15px;
        position: relative;
    }

    .notification-item:hover {
        background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
    }

    .notification-item.unread {
        background: linear-gradient(135deg, #f0f3ff 0%, #fff 100%);
    }

    .notification-item.unread::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .notification-icon {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 20px;
        color: white;
    }

    .notification-icon.success {
        background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
    }

    .notification-icon.info {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
    }

    .notification-icon.warning {
        background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
    }

    .notification-icon.danger {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
    }

    .notification-content {
        flex: 1;
    }

    .notification-title {
        font-weight: 700;
        color: #2c3e50;
        font-size: 14px;
        margin-bottom: 5px;
    }

    .notification-message {
        font-size: 13px;
        color: #7f8c8d;
        line-height: 1.4;
        margin-bottom: 5px;
    }

    .notification-time {
        font-size: 11px;
        color: #95a5a6;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .notifications-footer {
        padding: 12px 20px;
        text-align: center;
        border-top: 1px solid #f0f0f0;
        background: #f8f9fa;
    }

    .notifications-footer a {
        color: #667eea;
        font-weight: 700;
        font-size: 13px;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        transition: all 0.3s;
    }

    .notifications-footer a:hover {
        color: #764ba2;
    }

    .empty-notifications {
        padding: 60px 20px;
        text-align: center;
        color: #95a5a6;
    }

    .empty-notifications i {
        font-size: 60px;
        margin-bottom: 15px;
        opacity: 0.3;
    }

    .empty-notifications p {
        margin: 0;
        font-size: 14px;
    }

    /* Mobile responsive */
    @media (max-width: 768px) {
        .notifications-dropdown {
            width: 320px;
            right: -50px;
        }
    }
</style>

<div class="notifications-wrapper">
    {{-- Bell Icon --}}
    <div class="notification-bell" id="notificationBell">
        <i class="bi bi-bell-fill"></i>
        <span class="notification-badge" id="notificationBadge" style="display: none;">0</span>
    </div>

    {{-- Dropdown --}}
    <div class="notifications-dropdown" id="notificationsDropdown">
        {{-- Header --}}
        <div class="notifications-header">
            <h4>
                <i class="bi bi-bell"></i>
                Notifications
                <span class="badge" id="dropdownBadge">0</span>
            </h4>
            <div class="notifications-actions">
                <button onclick="markAllAsReadFromDropdown(); return false;">
                    <i class="bi bi-check-all"></i> Mark All
                </button>
            </div>
        </div>

        {{-- Body --}}
        <div class="notifications-body" id="notificationsBody">
            {{-- Loading State --}}
            <div class="empty-notifications">
                <i class="bi bi-hourglass-split"></i>
                <p>Loading notifications...</p>
            </div>
        </div>

        {{-- Footer --}}
        <div class="notifications-footer">
            <a href="{{ route('dashboard.notifications.index') }}">
                <i class="bi bi-arrow-right-circle"></i>
                View All Notifications
            </a>
        </div>
    </div>
</div>

<script>
    // ✅ Wait for jQuery to be ready
    document.addEventListener('DOMContentLoaded', function() {
        console.log('🔔 Notifications Script Loaded');

        // Check if jQuery is loaded
        if (typeof jQuery === 'undefined') {
            console.error('❌ jQuery is not loaded!');
            return;
        }

        console.log('✅ jQuery is loaded');

        // ══════════════════════════════════════════════════════════════
        // 🔔 TOGGLE DROPDOWN
        // ══════════════════════════════════════════════════════════════
        $('#notificationBell').off('click').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            console.log('🔔 Bell clicked!');

            var dropdown = $('#notificationsDropdown');
            var isVisible = dropdown.hasClass('show');

            if (isVisible) {
                dropdown.removeClass('show');
                console.log('🚪 Closing dropdown');
            } else {
                dropdown.addClass('show');
                console.log('✅ Opening dropdown');
                loadNotifications();
            }
        });

        // Close dropdown when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.notifications-wrapper').length) {
                $('#notificationsDropdown').removeClass('show');
            }
        });

        // ══════════════════════════════════════════════════════════════
        // 📥 LOAD NOTIFICATIONS
        // ══════════════════════════════════════════════════════════════
        function loadNotifications() {
            console.log('📥 Loading notifications...');

            $.ajax({
                url: '{{ route('dashboard.notifications.fetch') }}',
                type: 'GET',
                success: function(response) {
                    console.log('✅ Notifications loaded:', response);
                    updateNotifications(response.notifications, response.unread_count);
                },
                error: function(xhr, status, error) {
                    console.error('❌ Error loading notifications:', error);
                    console.error('Response:', xhr.responseText);

                    $('#notificationsBody').html(`
                        <div class="empty-notifications">
                            <i class="bi bi-exclamation-triangle"></i>
                            <p>Error loading notifications</p>
                            <small style="color: #e74c3c;">${error}</small>
                        </div>
                    `);
                }
            });
        }

        // ══════════════════════════════════════════════════════════════
        // 🔄 UPDATE UI
        // ══════════════════════════════════════════════════════════════
        function updateNotifications(notifications, unreadCount) {
            console.log('🔄 Updating UI - Unread:', unreadCount, 'Total:', notifications.length);

            // Update badge
            if (unreadCount > 0) {
                $('#notificationBadge').text(unreadCount).show();
                $('#dropdownBadge').text(unreadCount);
            } else {
                $('#notificationBadge').hide();
                $('#dropdownBadge').text('0');
            }

            // Update body
            if (notifications.length === 0) {
                $('#notificationsBody').html(`
                    <div class="empty-notifications">
                        <i class="bi bi-bell-slash"></i>
                        <p>No notifications yet</p>
                    </div>
                `);
                return;
            }

            let html = '';
            notifications.forEach(function(notification) {
                html += `
                    <div class="notification-item ${notification.is_read ? '' : 'unread'}"
                         onclick="handleNotificationClick(${notification.id}, '${notification.action_url || '#'}')">
                        <div class="notification-icon bi bi-${notification.icon} ${notification.color}">
                            <i class="bi ${notification.icon_class}"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">${notification.title}</div>
                            <div class="notification-message">${notification.message}</div>
                            <div class="notification-time">
                                <i class="bi bi-clock"></i>
                                ${notification.time_ago}
                            </div>
                        </div>
                    </div>
                `;
            });

            $('#notificationsBody').html(html);
        }

        // ══════════════════════════════════════════════════════════════
        // 🖱️ HANDLE CLICK
        // ══════════════════════════════════════════════════════════════
        window.handleNotificationClick = function(id, url) {
            console.log('🖱️ Notification clicked:', id);

            // Mark as read
            $.ajax({
                url: '/dashboard/notifications/' + id + '/read',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function() {
                    console.log('✅ Marked as read');
                    loadNotifications();

                    if (url && url !== '#' && url !== 'null') {
                        window.location.href = url;
                    }
                },
                error: function(xhr) {
                    console.error('❌ Error marking as read:', xhr.responseText);
                }
            });
        };

        // ══════════════════════════════════════════════════════════════
        // ✅ MARK ALL AS READ
        // ══════════════════════════════════════════════════════════════
        window.markAllAsReadFromDropdown = function() {
            console.log('✅ Marking all as read...');

            $.ajax({
                url: '{{ route('dashboard.notifications.mark-all-read') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function() {
                    console.log('✅ All marked as read');
                    loadNotifications();
                },
                error: function(xhr) {
                    console.error('❌ Error:', xhr.responseText);
                }
            });
        };

        // ══════════════════════════════════════════════════════════════
        // ⏱️ AUTO REFRESH (every 30 seconds)
        // ══════════════════════════════════════════════════════════════
        setInterval(function() {
            $.ajax({
                url: '{{ route('dashboard.notifications.fetch') }}',
                type: 'GET',
                success: function(response) {
                    // Update badge only
                    if (response.unread_count > 0) {
                        $('#notificationBadge').text(response.unread_count).show();
                    } else {
                        $('#notificationBadge').hide();
                    }
                },
                error: function() {
                    console.log('⚠️ Auto-refresh failed (silent)');
                }
            });
        }, 30000); // 30 seconds

        // Load on page load
        console.log('🚀 Initial load...');
        loadNotifications();
    });
</script>
