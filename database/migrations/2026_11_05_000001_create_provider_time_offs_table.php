<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// إجازات مقدّمي الخدمة: لا تظهر لهم مواعيد متاحة خلال الفترة
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('provider_time_offs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_provider_id')->constrained()->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('reason')->nullable();
            $table->timestamps();
            $table->index(['service_provider_id', 'start_date', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('provider_time_offs');
    }
};
