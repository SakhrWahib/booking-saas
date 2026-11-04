<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'native_name',
        'flag',
        'is_active',
        'is_default'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean'
    ];

    // العلاقة مع الترجمات
    public function translations()
    {
        return $this->hasMany(Translation::class);
    }

    // الحصول على اللغة الافتراضية
    public static function getDefault()
    {
        return static::where('is_default', true)->first();
    }

    // الحصول على اللغات النشطة
    public static function getActive()
    {
        return static::where('is_active', true)->get();
    }
} 