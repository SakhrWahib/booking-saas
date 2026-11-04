<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// باقة اشتراك: حدود الاستخدام والخصائص المتاحة
class Plan extends Model
{
    // الخصائص التي يمكن تفعيلها في الباقات، مع اسمها للعرض
    public const FEATURES = [
        'invoices' => 'الفواتير',
        'coupons' => 'الكوبونات',
        'loyalty' => 'برنامج الولاء',
        'reports' => 'التقارير التفصيلية',
        'abandoned_carts' => 'السلات غير المكتملة',
        'mobile_app' => 'عملاء التطبيق',
    ];

    // الحدود: اسم الحد ← [عمود الباقة، الاسم]
    public const LIMITS = [
        'staff' => ['max_staff', 'مقدّمي الخدمة'],
        'services' => ['max_services', 'الخدمات'],
        'monthly_bookings' => ['max_monthly_bookings', 'حجوزات الشهر'],
    ];

    protected $fillable = [
        'name', 'slug', 'price_monthly', 'price_yearly', 'max_staff', 'max_services',
        'max_monthly_bookings', 'features', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
        'price_monthly' => 'decimal:2',
        'price_yearly' => 'decimal:2',
    ];

    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }

    public function hasFeature(string $feature): bool
    {
        return in_array($feature, $this->features ?? [], true);
    }
}
