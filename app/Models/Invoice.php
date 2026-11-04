<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use AppModelsConcernsBelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'customer_id',
        'booking_id',
        'created_by',
        'issue_date',
        'due_date',
        'payment_date',
        'status',
        'subtotal',
        'price_before_tax',
        'tax_rate',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'paid_amount',
        'remaining_amount',
        'invoice_items',
        'payment_method',
        'payment_details',
        'notes',
        'terms_conditions',
        'customer_name',
        'customer_phone',
        'customer_address',
        'coupon_id'
    ];

    protected $casts = [
        'issue_date' => 'datetime',
        'due_date' => 'datetime',
        'payment_date' => 'datetime',
        'subtotal' => 'decimal:2',
        'price_before_tax' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'invoice_items' => 'array',
        'payment_details' => 'array'
    ];

    /**
     * العلاقة مع العميل
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * العلاقة مع الحجز
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * العلاقة مع منشئ الفاتورة
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * العلاقة مع الكوبون
     */
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * توليد رقم فاتورة فريد
     */
    public static function generateInvoiceNumber()
    {
        $prefix = 'INV';
        $date = now()->format('Ymd');
        $lastInvoice = static::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();
        
        $sequence = $lastInvoice ? 
            (int)substr($lastInvoice->invoice_number, -4) + 1 : 1;
        
        return $prefix . $date . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * الفواتير غير المدفوعة
     */
    public function scopeUnpaid($query)
    {
        return $query->where('status', 'unpaid');
    }

    /**
     * الفواتير المدفوعة
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * الفواتير المتأخرة
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue')
                    ->orWhere(function($q) {
                        $q->where('status', 'unpaid')
                          ->where('due_date', '<', now());
                    });
    }

    /**
     * حساب المبالغ تلقائياً - نظام الضريبة الشامل السعودي
     */
    public function calculateAmounts()
    {
        // في النظام السعودي، السعر شامل الضريبة
        // subtotal هو السعر الشامل للضريبة (المنتج + الإضافات)
        $taxRate = $this->tax_rate / 100;
        
        // السعر الشامل للضريبة (هو المبلغ الفرعي الحالي)
        $priceIncludingTax = $this->subtotal;
        
        // حساب قيمة الضريبة من السعر الشامل
        $this->tax_amount = ($priceIncludingTax * $taxRate) / (1 + $taxRate);
        
        // حساب السعر قبل الضريبة
        $this->price_before_tax = $priceIncludingTax - $this->tax_amount;
        
        // السعر بعد الخصم (من السعر الشامل)
        $priceAfterDiscount = $priceIncludingTax - $this->discount_amount;
        
        // المجموع الإجمالي يساوي السعر بعد الخصم (شامل الضريبة)
        $this->total_amount = $priceAfterDiscount;
        
        // حساب المبلغ المتبقي
        $this->remaining_amount = $this->total_amount - $this->paid_amount;
        
        // تحديث الحالة
        if ($this->remaining_amount <= 0) {
            $this->status = 'paid';
            $this->payment_date = $this->payment_date ?: now();
        } elseif ($this->paid_amount > 0) {
            $this->status = 'partially_paid';
        } elseif ($this->due_date && $this->due_date < now()) {
            $this->status = 'overdue';
        } else {
            $this->status = 'unpaid';
        }
        
        // حفظ التغييرات في قاعدة البيانات
        $this->save();
    }

    /**
     * تسجيل دفعة
     */
    public function recordPayment($amount, $method = null, $details = null)
    {
        $this->paid_amount += $amount;
        $this->payment_method = $method;
        $this->payment_details = $details;
        
        $this->calculateAmounts();
        $this->save();
        
        // تحديث حالة الحجز إذا تم الدفع بالكامل
        if ($this->status === 'paid' && $this->booking) {
            $this->booking->update(['status' => 'paid']);
        }
        
        return $this;
    }

    /**
     * دفع الفاتورة بالكامل
     */
    public function payInFull($method = 'cash', $details = null)
    {
        $remainingAmount = $this->total_amount - $this->paid_amount;
        return $this->recordPayment($remainingAmount, $method, $details);
    }

    /**
     * تحديث حالة الفاتورة إلى مدفوع
     */
    public function markAsPaid($method = 'cash', $details = null)
    {
        $this->status = 'paid';
        $this->paid_amount = $this->total_amount;
        $this->payment_date = now();
        $this->payment_method = $method;
        $this->payment_details = $details;
        $this->remaining_amount = 0;
        $this->save();
        
        // تحديث حالة الحجز
        if ($this->booking) {
            $this->booking->update(['status' => 'paid']);
        }
        
        return $this;
    }

    /**
     * إضافة خصم
     */
    public function applyDiscount($amount)
    {
        $this->discount_amount = $amount;
        $this->calculateAmounts();
        $this->save();
        
        return $this;
    }

    /**
     * إنشاء فاتورة من حجز
     */
    public static function createFromBooking(Booking $booking, $createdBy)
    {
        // إنشاء عناصر الفاتورة أولاً
        $items = [
            [
                'type' => 'service',
                'name' => $booking->product->name,
                'description' => $booking->product->short_description ?? 'خدمة ' . $booking->product->name,
                'quantity' => $booking->quantity,
                'unit_price' => $booking->product->price,
                'total_price' => $booking->product->price * $booking->quantity
            ]
        ];
        
        // إضافة الإضافات إلى عناصر الفاتورة
        if ($booking->selected_add_ons && is_array($booking->selected_add_ons)) {
            foreach ($booking->selected_add_ons as $addOn) {
                $items[] = [
                    'type' => 'addon',
                    'name' => $addOn['option'] ?? 'إضافة',
                    'description' => $addOn['name'] ?? '',
                    'quantity' => 1,
                    'unit_price' => $addOn['price'] ?? 0,
                    'total_price' => $addOn['price'] ?? 0
                ];
            }
        }
        
        // حساب المجموع الفرعي (المنتج + الإضافات)
        $subtotal = $booking->product->price * $booking->quantity;
        
        // إضافة أسعار الإضافات
        if ($booking->selected_add_ons && is_array($booking->selected_add_ons)) {
            foreach ($booking->selected_add_ons as $addOn) {
                $subtotal += $addOn['price'] ?? 0;
            }
        }
        
        $invoice = new static([
            'invoice_number' => static::generateInvoiceNumber(),
            'customer_id' => $booking->customer_id,
            'booking_id' => $booking->id,
            'created_by' => $createdBy,
            'issue_date' => now(),
            'due_date' => now()->addDays(30),
            'subtotal' => $subtotal,
            'price_before_tax' => $subtotal, // حساب السعر قبل الضريبة
            'tax_rate' => 15.00, // يمكن أن يكون من الإعدادات
            'discount_amount' => $booking->discount_amount ?? 0, // إضافة خصم الكوبون
            'paid_amount' => 0, // يبدأ بدون دفع
            'status' => 'unpaid', // حالة غير مدفوع بشكل صريح
            'customer_name' => $booking->customer->name,
            'customer_phone' => $booking->customer->phone,
            'customer_address' => $booking->customer_address ?? 'غير محدد',
            'coupon_id' => $booking->coupon_id // إضافة معرف الكوبون
        ]);
        
        $invoice->invoice_items = $items;
        $invoice->calculateAmounts();
        $invoice->save();
        
        return $invoice;
    }

    /**
     * تحديث فاتورة موجودة من الحجز
     */
    public function updateFromBooking(Booking $booking)
    {
        // إنشاء عناصر الفاتورة أولاً
        $items = [
            [
                'type' => 'service',
                'name' => $booking->product->name,
                'description' => $booking->product->short_description ?? 'خدمة ' . $booking->product->name,
                'quantity' => $booking->quantity,
                'unit_price' => $booking->product->price,
                'total_price' => $booking->product->price * $booking->quantity
            ]
        ];
        
        // إضافة الإضافات إلى عناصر الفاتورة
        if ($booking->selected_add_ons && is_array($booking->selected_add_ons)) {
            foreach ($booking->selected_add_ons as $addOn) {
                $items[] = [
                    'type' => 'addon',
                    'name' => $addOn['option'] ?? 'إضافة',
                    'description' => $addOn['name'] ?? '',
                    'quantity' => 1,
                    'unit_price' => $addOn['price'] ?? 0,
                    'total_price' => $addOn['price'] ?? 0
                ];
            }
        }
        
        // حساب المجموع الفرعي (المنتج + الإضافات)
        $subtotal = $booking->product->price * $booking->quantity;
        
        // إضافة أسعار الإضافات
        if ($booking->selected_add_ons && is_array($booking->selected_add_ons)) {
            foreach ($booking->selected_add_ons as $addOn) {
                $subtotal += $addOn['price'] ?? 0;
            }
        }
        
        // تحديث بيانات الفاتورة
        $this->update([
            'subtotal' => $subtotal,
            'price_before_tax' => $subtotal,
            'discount_amount' => $booking->discount_amount ?? 0,
            'coupon_id' => $booking->coupon_id,
            'customer_name' => $booking->customer->name,
            'customer_phone' => $booking->customer->phone,
            'customer_address' => $booking->customer_address ?? 'غير محدد',
        ]);
        
        $this->invoice_items = $items;
        $this->calculateAmounts();
        $this->save();
        
        return $this;
    }
} 