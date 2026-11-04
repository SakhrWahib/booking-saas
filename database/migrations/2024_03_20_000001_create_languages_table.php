<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('code', 5)->unique(); // مثل ar, en
            $table->string('name'); // اسم اللغة
            $table->string('native_name'); // الاسم باللغة الأصلية
            $table->string('flagت')->nullable(); // رمز العلم أو الأيقونة
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        // إضافة اللغات الافتراضية
        DB::table('languages')->insert([
            [
                'code' => 'ar',
                'name' => 'Arabic',
                'native_name' => 'العربية',
                'is_active' => true,
                'is_default' => true,
                'created_at' => now()
            ],
            [
                'code' => 'en',
                'name' => 'English',
                'native_name' => 'English',
                'is_active' => true,
                'is_default' => false,
                'created_at' => now()
            ]
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('languages');
    }
}; 