<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// نشاط تجاري مستقل (عميل للمنصة) له بياناته وإعداداته ومستخدموه واشتراكه
class Tenant extends Model
{
    public const STATUSES = [
        'trial' => 'تجربة',
        'active' => 'فعّال',
        'past_due' => 'متأخر السداد',
        'cancelled' => 'ملغي',
    ];

    protected $fillable = [
        'name', 'slug', 'domain', 'is_active', 'plan_id', 'subscription_status',
        'trial_ends_at', 'subscription_ends_at', 'owner_name', 'owner_phone', 'notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'trial_ends_at' => 'date',
        'subscription_ends_at' => 'date',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    // الاشتراك ساري: فعّال ولم ينتهِ، أو في فترة تجربة لم تنتهِ
    public function hasValidSubscription(): bool
    {
        return match ($this->subscription_status) {
            'active' => !$this->subscription_ends_at || $this->subscription_ends_at->gte(today()),
            'trial' => !$this->trial_ends_at || $this->trial_ends_at->gte(today()),
            default => false,
        };
    }

    public function hasFeature(string $feature): bool
    {
        return (bool) $this->plan?->hasFeature($feature);
    }

    // عدد الاستخدام الحالي لحد معيّن
    public function usage(string $limit): int
    {
        $scope = fn ($model) => $model::withoutGlobalScope('tenant')->where('tenant_id', $this->id);

        return match ($limit) {
            'staff' => $scope(ServiceProvider::class)->count(),
            'services' => $scope(Product::class)->count(),
            'monthly_bookings' => $scope(Booking::class)->where('created_at', '>=', now()->startOfMonth())->count(),
        };
    }

    // هل يسمح الحد بإضافة عنصر جديد؟ (بلا باقة أو حد فارغ = بلا حد)
    public function canAdd(string $limit): bool
    {
        $max = $this->plan?->{Plan::LIMITS[$limit][0]};

        return $max === null || $this->usage($limit) < $max;
    }
}
