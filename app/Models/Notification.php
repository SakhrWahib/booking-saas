<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'type',
        'booking_id',
        'is_read'
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    /**
     * العلاقة مع الحجز
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * إنشاء إشعار جديد للحجز
     */
    public static function createBookingNotification(Booking $booking)
    {
        return self::create([
            'title' => 'حجز جديد',
            'message' => "تم إنشاء حجز جديد من العميل: {$booking->customer->name}",
            'type' => 'booking',
            'booking_id' => $booking->id,
            'is_read' => false
        ]);
    }

    /**
     * الحصول على الإشعارات غير المقروءة
     */
    public static function getUnreadNotifications()
    {
        return self::where('is_read', false)->orderBy('created_at', 'desc')->get();
    }

    /**
     * تحديد الإشعار كمقروء
     */
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }
}
