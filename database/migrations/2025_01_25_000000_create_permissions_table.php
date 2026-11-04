<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // اسم الصلاحية
            $table->string('display_name'); // الاسم المعروض
            $table->string('category'); // فئة الصلاحية (users, customers, products, etc.)
            $table->text('description')->nullable(); // وصف الصلاحية
            $table->timestamps();
        });

        // إنشاء جدول العلاقة بين الأدوار والصلاحيات
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->foreignId('permission_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            // منع التكرار
            $table->unique(['role_id', 'permission_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('permissions');
    }
}; 