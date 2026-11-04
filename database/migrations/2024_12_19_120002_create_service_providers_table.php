<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('service_providers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم مقدم الخدمة
            $table->string('phone', 20); // رقم الجوال
            $table->string('email')->nullable(); // الإيميل
            
            // سعة مقدم الخدمة
            $table->integer('capacity')->default(3); // عدد الحجوزات المسموح بها في نفس الوقت
            
            // هل يعمل مقدم الخدمة في الحجز الواحد على
            $table->enum('work_type', ['single_person', 'multiple_persons'])->default('single_person'); 
            // single_person = شخص واحد فقط
            // multiple_persons = عدة أشخاص في نفس الوقت
            
            // الموقع الجغرافي ونطاق التغطية
            $table->enum('coverage_type', ['area_based', 'nearest_booking'])->default('area_based');
            // area_based = التعيين على مناطق الخدمة
            // nearest_booking = التعيين على أقرب حجز
            
            // نطاق التغطية الجغرافية (JSON للمضلع على الخريطة)
            $table->json('coverage_area')->nullable();
            /*
            مثال على بنية coverage_area:
            {
                "type": "polygon",
                "coordinates": [
                    [{"lat": 24.7136, "lng": 46.6753}, {"lat": 24.7200, "lng": 46.6800}, ...]
                ]
            }
            */
            
            // الموقع الأساسي لمقدم الخدمة
            $table->decimal('latitude', 10, 8)->nullable(); // خط العرض
            $table->decimal('longitude', 11, 8)->nullable(); // خط الطول
            $table->text('address')->nullable(); // العنوان النصي
            
            // مرجع لجدول العمل الحالي (المرتبط به)
            $table->foreignId('working_schedule_id')->nullable()->constrained('working_schedules')->onDelete('set null');
            
            // معرفات المنتجات المرتبطة بمقدم الخدمة (JSON array)
            $table->json('product_ids')->nullable();
            /*
            مثال على بنية product_ids:
            [1, 2, 4, 6, 9]
            */
            
            $table->boolean('is_active')->default(true); // حالة مقدم الخدمة
            $table->timestamps();
            
            // إضافة الفهارس
            $table->index('is_active');
            $table->index('coverage_type');
            $table->index(['latitude', 'longitude']);
            $table->index('working_schedule_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('service_providers');
    }
}; 