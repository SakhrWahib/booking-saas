
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم المنتج
            $table->text('short_description'); // وصف مختصر للمنتج
            $table->longText('description'); // وصف المنتج
            $table->json('image_gallery')->nullable(); // معرض الصور (JSON array)
            $table->decimal('price', 10, 2); // السعر
            
            // الإضافات والخيارات (JSON)
            $table->json('add_ons')->nullable(); // الإضافات مع الخيارات والأسعار والصور
            /*
            مثال على بنية add_ons:
            [
                {
                    "option": "غسيل إضافي",
                    "name": "غسيل شامبو",
                    "price": 20.00,
                    "image": "path/to/image.jpg"
                },
                {
                    "option": "تقليم أظافر",
                    "name": "تقليم احترافي",
                    "price": 15.00,
                    "image": "path/to/image2.jpg"
                }
            ]
            */
            
            // معرفات مقدمي الخدمات المرتبطين بالمنتج (JSON array)
            $table->json('service_provider_ids')->nullable();
            /*
            مثال على بنية service_provider_ids:
            [1, 3, 5, 8]
            */
            
            $table->boolean('is_active')->default(true); // حالة المنتج
            $table->timestamps();
            
            // إضافة الفهارس
            $table->index('is_active');
            $table->index('price');
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}; 