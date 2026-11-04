<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('working_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('schedule_name'); // اسم الجدول
            $table->date('start_date'); // من تاريخ
            $table->date('end_date'); // إلى تاريخ
            
            // الأيام الفعالة (JSON)
            $table->json('active_days'); 
            /*
            مثال على بنية active_days:
            {
                "saturday": {"active": true, "start_time": "09:00", "end_time": "17:00"},
                "sunday": {"active": true, "start_time": "09:00", "end_time": "17:00"},
                "monday": {"active": true, "start_time": "09:00", "end_time": "17:00"},
                "tuesday": {"active": true, "start_time": "09:00", "end_time": "17:00"},
                "wednesday": {"active": true, "start_time": "09:00", "end_time": "17:00"},
                "thursday": {"active": true, "start_time": "09:00", "end_time": "17:00"},
                "friday": {"active": false, "start_time": null, "end_time": null}
            }
            */
            
            // أيام العطلة (JSON array of dates) - تم نقلها من service_providers
            $table->json('holidays')->nullable(); // ["2024-01-01", "2024-12-25", ...]
            
            // فترات الإجازات (JSON array of periods)
            $table->json('vacation_periods')->nullable();
            /*
            مثال على بنية vacation_periods:
            [
                {
                    "start_date": "2024-07-01",
                    "end_date": "2024-07-15",
                    "reason": "إجازة صيفية"
                },
                {
                    "start_date": "2024-12-20",
                    "end_date": "2024-12-31",
                    "reason": "إجازة عيد الميلاد"
                }
            ]
            */
            
            $table->text('description')->nullable(); // وصف الجدول
            $table->boolean('is_default')->default(false); // هل هو الجدول الافتراضي
            $table->boolean('is_active')->default(true); // حالة الجدول
            $table->timestamps();
            
            // إضافة الفهارس
            $table->index('start_date');
            $table->index('end_date');
            $table->index('is_active');
            $table->index('is_default');
            $table->index(['start_date', 'end_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('working_schedules');
    }
}; 