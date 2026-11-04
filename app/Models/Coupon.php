<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'type',
        'value',
        'min_amount',
        'max_uses',
        'used_count',
        'start_date',
        'end_date',
        'is_active'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_amount' => 'decimal:2',
        'max_uses' => 'integer',
        'used_count' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean'
    ];

    /**
     * العلاقة مع الحجوزات
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * العلاقة مع الفواتير
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * التحقق من صلاحية الكوبون
     */
    public function isValid()
    {
        // التحقق من حالة الكوبون
        if (!$this->is_active) {
            return false;
        }

        // التحقق من تاريخ الصلاحية
        $now = Carbon::now();
        if ($this->start_date && $now->lt($this->start_date)) {
            return false;
        }
        if ($this->end_date && $now->gt($this->end_date)) {
            return false;
        }

        // التحقق من عدد مرات الاستخدام
        if ($this->max_uses && $this->used_count >= $this->max_uses) {
            return false;
        }

        return true;
    }

    /**
     * التحقق من صلاحية الكوبون لمبلغ معين
     */
    public function isValidForAmount($amount)
    {
        if (!$this->isValid()) {
            return false;
        }

        // التحقق من الحد الأدنى للطلب
        if ($amount < $this->min_amount) {
            return false;
        }

        return true;
    }

    /**
     * حساب قيمة الخصم
     */
    public function calculateDiscount($amount)
    {
        if (!$this->isValidForAmount($amount)) {
            return 0;
        }

        if ($this->type === 'percentage') {
            return ($amount * $this->value) / 100;
        } else {
            return min($this->value, $amount); // لا يزيد الخصم عن المبلغ الأصلي
        }
    }

    /**
     * زيادة عدد مرات الاستخدام
     */
    public function incrementUsage()
    {
        $this->increment('used_count');
    }

    /**
     * الكوبونات النشطة
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * الكوبونات الصالحة حالياً
     */
    public function scopeValid($query)
    {
        $now = Carbon::now();
        return $query->where('is_active', true)
                    ->where(function($q) use ($now) {
                        $q->whereNull('start_date')
                          ->orWhere('start_date', '<=', $now);
                    })
                    ->where(function($q) use ($now) {
                        $q->whereNull('end_date')
                          ->orWhere('end_date', '>=', $now);
                    })
                    ->where(function($q) {
                        $q->whereNull('max_uses')
                          ->orWhereRaw('used_count < max_uses');
                    });
    }

    /**
     * البحث بالكود
     */
    public function scopeByCode($query, $code)
    {
        return $query->where('code', $code);
    }

    /**
     * الحصول على كوبون بالكود
     */
    public static function findByCode($code)
    {
        return static::byCode($code)->first();
    }

    /**
     * الحصول على كوبون صالح بالكود
     */
    public static function findValidByCode($code)
    {
        return static::byCode($code)->valid()->first();
    }
} 