<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * عرض جميع الإشعارات
     */
    public function index()
    {
        $notifications = Notification::with('booking.customer')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.notifications.index', compact('notifications'));
    }

    /**
     * الحصول على الإشعارات غير المقروءة (لـ AJAX)
     */
    public function getUnread()
    {
        $notifications = Notification::with('booking.customer')
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'notifications' => $notifications,
            'count' => $notifications->count()
        ]);
    }

    /**
     * الحصول على جميع الإشعارات (للعرض في القائمة)
     */
    public function getAllForDropdown()
    {
        $notifications = Notification::with('booking.customer')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        return response()->json([
            'notifications' => $notifications,
            'count' => Notification::where('is_read', false)->count()
        ]);
    }

    /**
     * الحصول على عدد الإشعارات غير المقروءة للشريط العلوي
     */
    public function getUnreadCount()
    {
        $count = Notification::where('is_read', false)->count();
        return response()->json(['count' => $count]);
    }

    /**
     * تحديد إشعار كمقروء
     */
    public function markAsRead(Request $request)
    {
        $notification = Notification::findOrFail($request->notification_id);
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * تحديد جميع الإشعارات كمقروءة
     */
    public function markAllAsRead()
    {
        Notification::where('is_read', false)->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * عرض تفاصيل الإشعار
     */
    public function show($id)
    {
        $notification = Notification::with('booking.customer', 'booking.product', 'booking.serviceProvider')
            ->findOrFail($id);

        // تحديد الإشعار كمقروء
        if (!$notification->is_read) {
            $notification->markAsRead();
        }

        return view('admin.notifications.show', compact('notification'));
    }
}
