<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // مفتاح الإعداد
            $table->text('value')->nullable(); // قيمة الإعداد
            $table->string('type')->default('string'); // نوع البيانات (string, integer, boolean, json)
            $table->string('category')->default('general'); // فئة الإعداد
            $table->string('label'); // التسمية الظاهرة للمستخدم
            $table->text('description')->nullable(); // وصف الإعداد
            $table->boolean('is_public')->default(false); // هل يمكن عرضه للعامة
            $table->timestamps();
            
            // إضافة الفهارس
            $table->index('key');
            $table->index('category');
            $table->index('is_public');
        });
        
        // إدخال الإعدادات الافتراضية
        DB::table('system_settings')->insert([
            [
                'key' => 'default_time_slot_interval',
                'value' => '120',
                'type' => 'integer',
                'category' => 'booking',
                'label' => 'فترة عرض الأوقات الافتراضية',
                'description' => 'الفترة الزمنية بين الأوقات المتاحة للحجز بالدقائق (مثال: كل 120 دقيقة)',
                'is_public' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'default_buffer_time',
                'value' => '60',
                'type' => 'integer',
                'category' => 'booking',
                'label' => 'فترة الانتقال الافتراضية',
                'description' => 'الفترة الزمنية بين الحجوزات للانتقال من موقع لآخر بالدقائق',
                'is_public' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'earliest_booking_time',
                'value' => '1',
                'type' => 'integer',
                'category' => 'booking',
                'label' => 'أقل مدة للحجز المسبق',
                'description' => 'أقل مدة زمنية مطلوبة للحجز قبل الموعد بالساعات',
                'is_public' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'max_bookings_per_day',
                'value' => '10',
                'type' => 'integer',
                'category' => 'booking',
                'label' => 'أقصى عدد حجوزات يومية',
                'description' => 'أقصى عدد حجوزات مسموح بها لمقدم الخدمة الواحد في اليوم',
                'is_public' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'booking_calendar_view',
                'value' => 'month',
                'type' => 'string',
                'category' => 'display',
                'label' => 'عرض تقويم الحجوزات الافتراضي',
                'description' => 'العرض الافتراضي لتقويم الحجوزات (month, week, day)',
                'is_public' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'working_hours_start',
                'value' => '08:00',
                'type' => 'string',
                'category' => 'working_hours',
                'label' => 'ساعة بداية العمل',
                'description' => 'ساعة بداية العمل الافتراضية',
                'is_public' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'working_hours_end',
                'value' => '18:00',
                'type' => 'string',
                'category' => 'working_hours',
                'label' => 'ساعة انتهاء العمل',
                'description' => 'ساعة انتهاء العمل الافتراضية',
                'is_public' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('system_settings');
    }
}; 