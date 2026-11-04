<?php

namespace App\Models\Concerns;

use App\Models\Tenant;
use App\Support\Tenancy;
use Illuminate\Database\Eloquent\Builder;

// يعزل بيانات كل نشاط: كل استعلام يُقيَّد بالنشاط الحالي، وكل سجل جديد يأخذ رقمه تلقائيًا
trait BelongsToTenant
{
    public static function bootBelongsToTenant(): void
    {
        static::addGlobalScope('tenant', function (Builder $query) {
            if ($id = Tenancy::id()) {
                $query->where($query->getModel()->getTable() . '.tenant_id', $id);
            }
        });

        static::creating(function ($model) {
            $model->tenant_id ??= Tenancy::id();
        });
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
