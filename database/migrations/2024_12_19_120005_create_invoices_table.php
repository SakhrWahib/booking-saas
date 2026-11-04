<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique(); // رقم الفاتورة
            
            // العلاقات
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade'); // العميل
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade'); // الحجز
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict'); // أُنشئ بواسطة (من جدول المستخدمين)
            
            // تفاصيل الفاتورة
            $table->datetime('issue_date'); // تاريخ الإصدار
            $table->datetime('due_date')->nullable(); // تاريخ الاستحقاق
            $table->datetime('payment_date')->nullable(); // تاريخ الدفع
            
            // الحالة
            $table->enum('status', [
                'unpaid', // غير مدفوعة
                'paid', // مدفوعة
                'partially_paid', // مدفوعة جزئياً
                'overdue', // متأخرة
                'cancelled', // ملغية
                'refunded' // مُسترد
            ])->default('unpaid');
            
            // المبالغ
            $table->decimal('subtotal', 10, 2); // المجموع الفرعي
            $table->decimal('price_before_tax', 10, 2)->default(0); // السعر قبل الضريبة
            $table->decimal('tax_rate', 5, 2)->default(15.00); // نسبة الضريبة
            $table->decimal('tax_amount', 10, 2)->default(0); // مبلغ الضريبة
            $table->decimal('discount_amount', 10, 2)->default(0); // مبلغ الخصم
            $table->decimal('total_amount', 10, 2); // المبلغ الإجمالي
            $table->decimal('paid_amount', 10, 2)->default(0); // المبلغ المدفوع
            $table->decimal('remaining_amount', 10, 2)->default(0); // المبلغ المتبقي
            
            // تفاصيل الفاتورة (JSON)
            $table->json('invoice_items');
            /*
            مثال على بنية invoice_items:
            [
                {
                    "type": "service",
                    "name": "غسيل القطط",
                    "description": "خدمة غسيل شاملة",
                    "quantity": 3,
                    "unit_price": 50.00,
                    "total_price": 150.00
                },
                {
                    "type": "add_on",
                    "name": "تقليم أظافر",
                    "description": "إضافة تقليم الأظافر",
                    "quantity": 1,
                    "unit_price": 20.00,
                    "total_price": 20.00
                }
            ]
            */
            
            // طريقة الدفع
            $table->enum('payment_method', [
                'cash', // نقدي
                'bank_transfer', // تحويل بنكي
                'credit_card', // بطاقة ائتمان
                'digital_wallet', // محفظة رقمية
                'other' // أخرى
            ])->nullable();
            
            // معلومات الدفع
            $table->json('payment_details')->nullable();
            /*
            مثال على بنية payment_details:
            {
                "method": "bank_transfer",
                "reference_number": "REF123456",
                "receipt_image": "path/to/receipt.jpg",
                "notes": "ملاحظات الدفع"
            }
            */
            
            // ملاحظات
            $table->text('notes')->nullable(); // ملاحظات الفاتورة
            $table->text('terms_conditions')->nullable(); // الشروط والأحكام
            
            // معلومات العميل (نسخة للأرشفة)
            $table->string('customer_name'); // اسم العميل
            $table->string('customer_phone'); // رقم جوال العميل
            $table->text('customer_address')->nullable(); // عنوان العميل
            
            $table->timestamps();
            
            // إضافة الفهارس
            $table->index('invoice_number');
            $table->index('issue_date');
            $table->index('due_date');
            $table->index('payment_date');
            $table->index('status');
            $table->index(['customer_id', 'issue_date']);
            $table->index(['created_by', 'issue_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}; 