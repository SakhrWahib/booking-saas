<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// نشاط تجاري مستقل (عميل للمنصة) له بياناته وإعداداته ومستخدموه
class Tenant extends Model
{
    protected $fillable = ['name', 'slug', 'domain', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
