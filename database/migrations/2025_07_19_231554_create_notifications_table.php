<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // عنوان الإشعار
            $table->text('message'); // رسالة الإشعار
            $table->string('type')->default('booking'); // نوع الإشعار (حجز، نظام، إلخ)
            $table->unsignedBigInteger('booking_id')->nullable(); // معرف الحجز المرتبط
            $table->boolean('is_read')->default(false); // هل تم قراءة الإشعار
            $table->timestamps();
            
            // إضافة مفتاح خارجي للحجز
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
