<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Notification;
use App\Events\NewBookingNotification;
use Illuminate\Console\Command;

class TestNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'اختبار نظام الإشعارات';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('بدء اختبار نظام الإشعارات...');
        
        // اختبار 1: إنشاء إشعار مباشر
        $this->info('1. اختبار إنشاء إشعار مباشر...');
        $notification = Notification::create([
            'title' => 'اختبار الإشعارات',
            'message' => 'هذا إشعار اختبار تم إنشاؤه في ' . now()->format('Y-m-d H:i:s'),
            'type' => 'booking',
            'booking_id' => null,
            'is_read' => false
        ]);
        
        if ($notification) {
            $this->info('✅ تم إنشاء الإشعار بنجاح!');
        } else {
            $this->error('❌ فشل في إنشاء الإشعار');
        }
        
        // اختبار 2: إطلاق Event للحجز
        $this->info('2. اختبار إطلاق Event للحجز...');
        $booking = Booking::first();
        
        if ($booking) {
            // تغيير مصدر الحجز إلى الموقع لاختبار الإشعار
            $booking->update(['booking_source' => 'website']);
            
            // إطلاق Event
            event(new NewBookingNotification($booking));
            
            $this->info('✅ تم إطلاق Event بنجاح!');
            
            // التحقق من إنشاء الإشعار
            $newNotification = Notification::where('booking_id', $booking->id)
                ->where('created_at', '>=', now()->subMinute())
                ->first();
                
            if ($newNotification) {
                $this->info('✅ تم إنشاء إشعار للحجز بنجاح!');
            } else {
                $this->warn('⚠️ لم يتم العثور على إشعار للحجز');
            }
        } else {
            $this->error('❌ لا توجد حجوزات للاختبار');
        }
        
        // اختبار 3: عرض الإحصائيات
        $this->info('3. إحصائيات الإشعارات...');
        $totalNotifications = Notification::count();
        $unreadNotifications = Notification::where('is_read', false)->count();
        
        $this->info("إجمالي الإشعارات: {$totalNotifications}");
        $this->info("الإشعارات غير المقروءة: {$unreadNotifications}");
        
        $this->info('✅ تم الانتهاء من اختبار الإشعارات بنجاح!');
    }
}
