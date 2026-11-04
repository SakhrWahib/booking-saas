<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // رمز الكوبون
            $table->string('name'); // اسم الكوبون
            $table->enum('type', ['percentage', 'fixed'])->default('percentage'); // نوع الخصم: نسبة مئوية أو مبلغ ثابت
            $table->decimal('value', 10, 2); // قيمة الخصم (نسبة مئوية أو مبلغ)
            $table->decimal('min_amount', 10, 2)->default(0); // الحد الأدنى للطلب
            $table->integer('max_uses')->nullable(); // الحد الأقصى لعدد مرات الاستخدام
            $table->integer('used_count')->default(0); // عدد مرات الاستخدام الحالية
            $table->date('start_date')->nullable(); // تاريخ بداية الصلاحية
            $table->date('end_date')->nullable(); // تاريخ انتهاء الصلاحية
            $table->boolean('is_active')->default(true); // حالة الكوبون
            $table->timestamps();
            
            // إضافة الفهارس
            $table->index('code');
            $table->index('is_active');
            $table->index(['start_date', 'end_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}; 