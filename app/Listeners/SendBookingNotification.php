<?php

namespace App\Listeners;

use App\Events\NewBookingNotification;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendBookingNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NewBookingNotification $event): void
    {
        // إنشاء إشعار جديد في قاعدة البيانات
        Notification::createBookingNotification($event->booking);
    }
}
