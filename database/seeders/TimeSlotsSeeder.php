<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WorkingSchedule;
use App\Models\TimeSlot;

class TimeSlotsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // الحصول على جميع جداول العمل الموجودة
        $workingSchedules = WorkingSchedule::all();

        foreach ($workingSchedules as $schedule) {
            // التحقق من عدم وجود أوقات مسبقاً
            if ($schedule->timeSlots()->count() == 0) {
                // إنشاء الأوقات الافتراضية
                $schedule->createDefaultTimeSlots();
                
                $this->command->info("تم إنشاء الأوقات الافتراضية لجدول العمل: {$schedule->schedule_name}");
            }
        }

        $this->command->info('تم إنشاء الأوقات الافتراضية لجميع جداول العمل بنجاح!');
    }
}
