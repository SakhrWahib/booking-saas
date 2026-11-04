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
        Schema::create('abandoned_carts', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name'); // اسم العميل
            $table->string('customer_phone', 20); // رقم الجوال
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); // المنتج المختار
            $table->boolean('is_contacted')->default(false); // هل تم التواصل معه
            $table->text('notes')->nullable(); // ملاحظات إضافية
            $table->timestamps();
            
            // فهارس للأداء
            $table->index('customer_phone');
            $table->index('is_contacted');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abandoned_carts');
    }
}; 