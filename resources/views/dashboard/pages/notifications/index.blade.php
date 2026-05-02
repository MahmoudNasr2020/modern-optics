@extends('dashboard.layouts.master')

@section('title', 'Notifications')

@section('content')

    <style>
        .notifications-page {
            padding: 20px;
        }

        .notifications-header-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 35px 30px;
            border-radius: 12px 12px 0 0;
            margin-bottom: 0;
        }

        .notifications-header-box h1 {
            margin: 0 0 10px 0;
            font-size: 32px;
            font-weight: 900;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .notifications-header-box h1 i {
            font-size: 36px;
        }

        .notifications-box {
            background: white;
            border-radius: 0 0 12px 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .notifications-filters {
            padding: 20px 30px;
            border-bottom: 2px solid #e8ecf7;
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .filter-tabs {
            display: flex;
            gap: 10px;
        }

        .filter-tab {
            padding: 10px 20px;
            border-radius: 8px;
            background: white;
            border: 2px solid #e8ecf7;
            color: #7f8c8d;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 14px;
        }

        .filter-tab:hover {
            border-color: #667eea;
            color: #667eea;
        }

        .filter-tab.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: #667eea;
            color: white;
        }

        .filter-actions {
            display: flex;
            gap: 10px;
        }

        .btn-action {
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 14px;
        }

        .btn-mark-all {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
        }

        .btn-mark-all:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(39, 174, 96, 0.3);
        }

        .btn-delete-all {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
        }

        .btn-delete-all:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
        }

        .notifications-list {
            padding: 30px;
        }

        .notification-card {
            background: white;
            border: 2px solid #e8ecf7;
            border-radius: 12px;
            padding: 20px 25px;
            margin-bottom: 15px;
            display: flex;
            gap: 20px;
            transition: all 0.3s;
            position: relative;
        }

        .notification-card:hover {
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.15);
            border-color: #667eea;
        }

        .notification-card.unread {
            background: linear-gradient(135deg, #f0f3ff 0%, #fff 100%);
            border-left: 5px solid #667eea;
        }

        .notification-card-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: white;
            flex-shrink: 0;
        }

        .notification-card-icon.success {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
        }

        .notification-card-icon.info {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        }

        .notification-card-icon.warning {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
        }

        .notification-card-icon.danger {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        }

        .notification-card-content {
            flex: 1;
        }

        .notification-card-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 10px;
        }

        .notification-card-title {
            font-size: 18px;
            font-weight: 800;
            color: #2c3e50;
            margin: 0;
        }

        .notification-card-time {
            font-size: 13px;
            color: #95a5a6;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .notification-card-message {
            color: #7f8c8d;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 12px;
        }

        .notification-card-meta {
            display: flex;
            gap: 15px;
            font-size: 13px;
        }

        .notification-meta-item {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #95a5a6;
        }

        .notification-card-actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .btn-icon {
            width: 35px;
            height: 35px;
            border-radius: 8px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-icon:hover {
            transform: translateY(-2px);
        }

        .btn-view {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
        }

        .btn-delete {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: #95a5a6;
        }

        .empty-state i {
            font-size: 80px;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        .empty-state h3 {
            font-size: 24px;
            font-weight: 800;
            margin-bottom: 10px;
            color: #7f8c8d;
        }

        .empty-state p {
            font-size: 16px;
        }
    </style>

    <section class="content-header">
        <h1>
            <i class="bi bi-bell-fill"></i> Notifications
            <small>View all your notifications</small>
        </h1>
    </section>

    <div class="notifications-page">
        <div class="notifications-header-box">
            <h1>
                <i class="bi bi-bell-fill"></i>
                Notifications Center
            </h1>
            <p style="margin: 0; opacity: 0.95;">Stay updated with all system activities</p>
        </div>

        <div class="notifications-box">
            {{-- Filters --}}
            <div class="notifications-filters">
                <div class="filter-tabs">
                    <div class="filter-tab active" data-filter="all">
                        <i class="bi bi-list-ul"></i> All
                    </div>
                    <div class="filter-tab" data-filter="unread">
                        <i class="bi bi-envelope"></i> Unread
                    </div>
                    <div class="filter-tab" data-filter="read">
                        <i class="bi bi-envelope-open"></i> Read
                    </div>
                </div>

                <div class="filter-actions">
                    <button class="btn-action btn-mark-all" onclick="markAllAsRead()">
                        <i class="bi bi-check-all"></i> Mark All as Read
                    </button>
                    <button class="btn-action btn-delete-all" onclick="deleteAll()">
                        <i class="bi bi-trash"></i> Delete All
                    </button>
                </div>
            </div>

            {{-- List --}}
            <div class="notifications-list">
                @forelse($notifications as $notification)
                    <div class="notification-card {{ $notification->is_read ? '' : 'unread' }}"
                         data-read="{{ $notification->is_read ? 'read' : 'unread' }}">
                        <div class="notification-card-icon {{ $notification->color_class }}">
                            <i class="bi {{ $notification->icon_class }}"></i>
                        </div>

                        <div class="notification-card-content">
                            <div class="notification-card-header">
                                <h4 class="notification-card-title">{{ $notification->title }}</h4>
                                <span class="notification-card-time">
                                <i class="bi bi-clock"></i>
                                {{ $notification->time_ago }}
                            </span>
                            </div>

                            <div class="notification-card-message">
                                {{ $notification->message }}
                            </div>

                            <div class="notification-card-meta">
                                @if($notification->creator)
                                    <div class="notification-meta-item">
                                        <i class="bi bi-person"></i>
                                        {{ $notification->creator->full_name }}
                                    </div>
                                @endif

                                @if($notification->branch)
                                    <div class="notification-meta-item">
                                        <i class="bi bi-building"></i>
                                        {{ $notification->branch->name }}
                                    </div>
                                @endif

                                <div class="notification-meta-item">
                                    <i class="bi bi-tag"></i>
                                    {{ str_replace('_', ' ', ucfirst($notification->type)) }}
                                </div>
                            </div>
                        </div>

                        <div class="notification-card-actions">
                            @if($notification->action_url)
                                <button class="btn-icon btn-view" onclick="window.location.href='{{ $notification->action_url }}'">
                                    <i class="bi bi-eye"></i>
                                </button>
                            @endif
                            <button class="btn-icon btn-delete" onclick="deleteNotification({{ $notification->id }})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="bi bi-bell-slash"></i>
                        <h3>No Notifications</h3>
                        <p>You're all caught up! Check back later for updates.</p>
                    </div>
                @endforelse

                {{-- Pagination --}}
                @if($notifications->hasPages())
                    <div style="margin-top: 30px;">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // ══════════════════════════════════════════════════════════════
            // 🗂️ FILTER TABS
            // ══════════════════════════════════════════════════════════════
            $('.filter-tab').click(function() {
                $('.filter-tab').removeClass('active');
                $(this).addClass('active');

                const filter = $(this).data('filter');

                if (filter === 'all') {
                    $('.notification-card').show();
                } else {
                    $('.notification-card').hide();
                    $('.notification-card[data-read="' + filter + '"]').show();
                }
            });

            // ══════════════════════════════════════════════════════════════
            // ✅ MARK ALL AS READ
            // ══════════════════════════════════════════════════════════════
            window.markAllAsRead = function() {
                Swal.fire({
                    title: 'Mark All as Read?',
                    text: 'This will mark all notifications as read',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#27ae60',
                    cancelButtonColor: '#95a5a6',
                    confirmButtonText: 'Yes, Mark All',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('dashboard.notifications.mark-all-read') }}',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function() {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Done!',
                                    text: 'All notifications marked as read',
                                    timer: 2000
                                }).then(() => {
                                    window.location.reload();
                                });
                            }
                        });
                    }
                });
            };

            // ══════════════════════════════════════════════════════════════
            // 🗑️ DELETE NOTIFICATION
            // ══════════════════════════════════════════════════════════════
            window.deleteNotification = function(id) {
                Swal.fire({
                    title: 'Delete Notification?',
                    text: 'This action cannot be undone',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e74c3c',
                    cancelButtonColor: '#95a5a6',
                    confirmButtonText: 'Yes, Delete',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/dashboard/notifications/${id}`,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function() {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    timer: 1500
                                }).then(() => {
                                    window.location.reload();
                                });
                            }
                        });
                    }
                });
            };

            // ══════════════════════════════════════════════════════════════
            // 🗑️ DELETE ALL
            // ══════════════════════════════════════════════════════════════
            window.deleteAll = function() {
                Swal.fire({
                    title: 'Delete All Notifications?',
                    text: 'This will permanently delete all your notifications',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e74c3c',
                    cancelButtonColor: '#95a5a6',
                    confirmButtonText: 'Yes, Delete All',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('dashboard.notifications.destroy-all') }}',
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function() {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'All Deleted!',
                                    timer: 1500
                                }).then(() => {
                                    window.location.reload();
                                });
                            }
                        });
                    }
                });
            };
        });
    </script>
@endsection
