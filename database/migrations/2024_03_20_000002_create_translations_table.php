<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('language_id')->constrained('languages')->onDelete('cascade');
            $table->string('group')->index(); // مثل web, auth, validation
            $table->string('key'); // مفتاح الترجمة
            $table->text('value'); // نص الترجمة
            $table->timestamps();

            // إنشاء فهرس مركب للمفاتيح الفريدة
            $table->unique(['language_id', 'group', 'key']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('translations');
    }
}; 