<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderTimeOff extends Model
{
    use \App\Models\Concerns\BelongsToTenant;
    protected $fillable = ['service_provider_id', 'start_date', 'end_date', 'reason'];

    protected $casts = ['start_date' => 'date', 'end_date' => 'date'];

    public function serviceProvider()
    {
        return $this->belongsTo(ServiceProvider::class);
    }

    public function scopeCovering($query, $date)
    {
        return $query->whereDate('start_date', '<=', $date)->whereDate('end_date', '>=', $date);
    }
}
