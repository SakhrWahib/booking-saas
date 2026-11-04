<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbandonedCart extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'customer_phone',
        'product_id',
        'is_contacted',
        'notes'
    ];

    protected $casts = [
        'is_contacted' => 'boolean'
    ];

    /**
     * العلاقة مع المنتج
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * السلات التي لم يتم التواصل معها
     */
    public function scopeNotContacted($query)
    {
        return $query->where('is_contacted', false);
    }

    /**
     * السلات التي تم التواصل معها
     */
    public function scopeContacted($query)
    {
        return $query->where('is_contacted', true);
    }

    /**
     * السلات المضافة اليوم
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * السلات المضافة هذا الأسبوع
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    /**
     * السلات المضافة هذا الشهر
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
    }

    /**
     * تحديث حالة التواصل
     */
    public function markAsContacted($notes = null)
    {
        $this->update([
            'is_contacted' => true,
            'notes' => $notes ? $this->notes . "\n\n" . now()->format('Y-m-d H:i') . " - تم التواصل: " . $notes : $this->notes
        ]);
    }
} 