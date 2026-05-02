<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * عرض كل الإشعارات
     */
    public function index()
    {
        $user = auth()->user();

        $notifications = Notification::where(function($query) use ($user) {
            $query->where('user_id', $user->id)
                ->orWhere('branch_id', $user->branch_id)
                ->orWhereIn('role', $user->getRoleNames()->toArray());
        })
            ->with(['creator', 'branch'])
            ->latest()
            ->paginate(20);

        return view('dashboard.pages.notifications.index', compact('notifications'));
    }

    /**
     * الحصول على الإشعارات (AJAX)
     */
    public function fetch()
    {
        $notifications = NotificationService::getUserNotifications(10);
        $unreadCount = NotificationService::getUnreadCount();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * تحديد إشعار كمقروء
     */
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'تم تحديد الإشعار كمقروء',
        ]);
    }

    /**
     * تحديد الكل كمقروء
     */
    public function markAllAsRead()
    {
        NotificationService::markAllAsRead();

        return response()->json([
            'success' => true,
            'message' => 'تم تحديد جميع الإشعارات كمقروءة',
        ]);
    }

    /**
     * حذف إشعار
     */
    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف الإشعار',
        ]);
    }

    /**
     * حذف الكل
     */
    public function destroyAll()
    {
        $user = auth()->user();

        Notification::where(function($query) use ($user) {
            $query->where('user_id', $user->id)
                ->orWhere('branch_id', $user->branch_id)
                ->orWhereIn('role', $user->getRoleNames()->toArray());
        })->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف جميع الإشعارات',
        ]);
    }
}
