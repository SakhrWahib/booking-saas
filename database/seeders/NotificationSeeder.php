<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\Booking;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء إشعارات اختبار
        $bookings = Booking::take(5)->get();
        
        foreach ($bookings as $booking) {
            Notification::create([
                'title' => 'حجز جديد',
                'message' => "تم إنشاء حجز جديد من العميل: {$booking->customer->name}",
                'type' => 'booking',
                'booking_id' => $booking->id,
                'is_read' => false
            ]);
        }

        // إنشاء إشعارات مقروءة للاختبار
        Notification::create([
            'title' => 'حجز مؤكد',
            'message' => 'تم تأكيد حجز العميل أحمد محمد',
            'type' => 'booking',
            'booking_id' => null,
            'is_read' => true
        ]);

        $this->command->info('تم إنشاء إشعارات اختبار بنجاح!');
    }
}
