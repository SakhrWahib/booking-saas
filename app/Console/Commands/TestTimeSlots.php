<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Models\ServiceProvider;
use App\Models\WorkingSchedule;
use App\Models\TimeSlot;
use Carbon\Carbon;

class TestTimeSlots extends Command
{
    protected $signature = 'test:time-slots';
    protected $description = 'اختبار نظام الأوقات المتاحة';

    public function handle()
    {
        $this->info('🔍 اختبار نظام الأوقات المتاحة...');
        
        // البحث عن مقدم خدمة نشط
        $serviceProvider = ServiceProvider::with('workingSchedule.activeTimeSlots')->active()->first();
        
        if (!$serviceProvider) {
            $this->error('❌ لا يوجد مقدم خدمة نشط');
            return 1;
        }
        
        $this->info("مقدم الخدمة: {$serviceProvider->name}");
        
        if (!$serviceProvider->workingSchedule) {
            $this->error('❌ لا يوجد جدول عمل مرتبط');
            return 1;
        }
        
        $this->info("جدول العمل: {$serviceProvider->workingSchedule->schedule_name}");
        
        // عرض الأوقات النشطة
        $activeTimeSlots = $serviceProvider->workingSchedule->activeTimeSlots;
        $this->info("عدد الأوقات النشطة: " . $activeTimeSlots->count());
        
        foreach ($activeTimeSlots as $slot) {
            $this->info("  - {$slot->time} ({$slot->period}) - {$slot->label}");
        }
        
        // اختبار التاريخ غداً
        $tomorrow = Carbon::tomorrow();
        $this->info("\nاختبار التاريخ: " . $tomorrow->format('Y-m-d'));
        
        // اختبار الدالة القديمة
        $oldSlots = Booking::getAvailableTimeSlotsWithSchedule($tomorrow->format('Y-m-d'), $serviceProvider->id);
        $this->info("الأوقات المتاحة (الطريقة القديمة): " . count($oldSlots));
        foreach ($oldSlots as $time) {
            $this->info("  - {$time}");
        }
        
        // اختبار الدالة الجديدة
        $newSlots = Booking::getAvailableTimeSlotsWithPeriods($tomorrow->format('Y-m-d'), $serviceProvider->id);
        $this->info("الأوقات المتاحة (الطريقة الجديدة): " . count($newSlots));
        foreach ($newSlots as $slot) {
            $this->info("  - {$slot['time']} ({$slot['period']}) - {$slot['label']}");
        }
        
        $this->info("🎉 انتهى اختبار نظام الأوقات المتاحة!");
        
        return 0;
    }
} 