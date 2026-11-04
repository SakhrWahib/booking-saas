<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number')->unique(); // رقم الحجز
            
            // العلاقات الأساسية
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade'); // العميل
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); // المنتج/الخدمة
            $table->foreignId('service_provider_id')->constrained('service_providers')->onDelete('cascade'); // مقدم الخدمة
            
            // تفاصيل الحجز
            $table->date('booking_date'); // تاريخ الحجز
            $table->time('booking_time'); // وقت الحجز (11:00, 13:00, 15:00, 17:00, 19:00, 21:00)
            $table->integer('quantity')->default(1); // العدد
            
            // الإضافات المحددة من المنتج (JSON)
            $table->json('selected_add_ons')->nullable();
            /*
            مثال على بنية selected_add_ons:
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
            
            // حالة الحجز
            $table->enum('status', [
                'pending', // قيد الانتظار
                'confirmed', // مؤكد
                'paid', // مدفوع
                'completed', // مكتمل
                'cancelled' // ملغي
            ])->default('pending');
            
            // ملاحظات
            $table->text('notes')->nullable(); // ملاحظات عامة
            
            // معلومات الموقع الجغرافي للعميل (للحجوزات من الموقع الإلكتروني)
            $table->decimal('customer_latitude', 10, 8)->nullable(); // خط العرض
            $table->decimal('customer_longitude', 11, 8)->nullable(); // خط الطول
            $table->text('customer_address')->nullable(); // العنوان النصي
            $table->text('location_map_url')->nullable(); // رابط الموقع في الخريطة
            
            // تأكيد الموقع (هل العميل في الرياض)
            $table->boolean('is_riyadh_confirmed')->default(false);
            
            // مصدر الحجز
            $table->enum('booking_source', ['admin_panel', 'website'])->default('admin_panel');
            
            // المرفقات (إيصالات الحوالة، صور، إلخ)
            $table->json('attachments')->nullable();
            /*
            مثال على بنية attachments:
            {
                "receipts": [
                    {"type": "image", "path": "receipts/receipt1.jpg", "name": "إيصال الحوالة"},
                    {"type": "pdf", "path": "receipts/receipt2.pdf", "name": "إيصال بنكي"}
                ],
                "documents": [
                    {"type": "image", "path": "documents/id.jpg", "name": "صورة الهوية"}
                ]
            }
            */
            
            // تفاصيل إضافية للحجز
            $table->decimal('total_price', 10, 2)->nullable(); // السعر الإجمالي
            $table->text('admin_notes')->nullable(); // ملاحظات الإدارة
            $table->text('customer_notes')->nullable(); // ملاحظات العميل
            
            // تواريخ مهمة
            $table->timestamp('confirmed_at')->nullable(); // تاريخ التأكيد
            $table->timestamp('completed_at')->nullable(); // تاريخ الإكمال
            $table->timestamp('cancelled_at')->nullable(); // تاريخ الإلغاء
            
            // التواريخ الأساسية
            $table->timestamps();
            
            // الفهارس الأساسية
            $table->index('booking_date');
            $table->index('booking_time');
            $table->index(['booking_date', 'booking_time', 'service_provider_id']); // فهرس مركب للبحث السريع
            $table->index('status');
            
            // الفهارس الإضافية من migration التحديث
            $table->index(['customer_latitude', 'customer_longitude']);
            $table->index('booking_source');
            $table->index('is_riyadh_confirmed');
            $table->index('total_price');
            $table->index('confirmed_at');
            $table->index('completed_at');
            $table->index('cancelled_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}; 