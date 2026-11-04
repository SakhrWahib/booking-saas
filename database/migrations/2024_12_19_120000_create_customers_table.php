<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم العميل
            $table->string('phone', 20)->unique(); // رقم الجوال
            $table->enum('customer_type', ['individual'])->default('individual'); // نوع العميل (أفراد)
            $table->string('email')->nullable(); // الإيميل (اختياري)
            
            // معلومات الموقع
            $table->decimal('latitude', 10, 8)->nullable(); // خط العرض
            $table->decimal('longitude', 11, 8)->nullable(); // خط الطول
            $table->string('address')->nullable(); // العنوان التفصيلي
            
            // رمز التحقق من 4 أرقام
            $table->string('otp_code', 4)->nullable(); // رمز التحقق
            $table->timestamp('otp_expires_at')->nullable(); // تاريخ انتهاء صلاحية OTP
            $table->integer('otp_attempts')->default(0); // عدد محاولات إدخال OTP الخاطئة
            
            // معلومات الدخول عبر الجوال
            $table->timestamp('last_mobile_login')->nullable(); // آخر دخول عبر الجوال
            $table->string('device_type')->nullable(); // نوع الجهاز (android, ios, web)
            
            $table->timestamps(); // تاريخ الإنشاء والتحديث
            
            // إضافة الفهارس
            $table->index('phone');
            $table->index('customer_type');
            $table->index(['latitude', 'longitude']); // فهرس للموقع

        });
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
}; 