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
        Schema::create('time_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('working_schedule_id')->constrained()->onDelete('cascade');
            $table->time('time'); // الوقت مثل 11:00
            $table->string('label')->nullable(); // تسمية الوقت مثل "الصباح"
            $table->string('period'); // فترة الوقت: morning, afternoon, evening
            $table->integer('sort_order')->default(0); // ترتيب الأوقات
            $table->boolean('is_active')->default(true); // هل الوقت نشط
            $table->timestamps();
            
            // فهارس للأداء
            $table->index(['working_schedule_id', 'is_active']);
            $table->index('sort_order');
            $table->unique(['working_schedule_id', 'time']); // منع تكرار الوقت في نفس الجدول
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_slots');
    }
};
