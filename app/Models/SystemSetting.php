<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    use AppModelsConcernsBelongsToTenant;

    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'category',
        'label',
        'description',
        'is_public'
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    /**
     * الحصول على قيمة إعداد معين
     */
    public static function getValue($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        return static::castValue($setting->value, $setting->type);
    }

    /**
     * تحديث قيمة إعداد معين
     */
    public static function setValue($key, $value)
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    /**
     * تحويل القيمة حسب النوع
     */
    public static function castValue($value, $type)
    {
        switch ($type) {
            case 'boolean':
                return (bool) $value;
            case 'integer':
                return (int) $value;
            case 'float':
                return (float) $value;
            case 'json':
                return json_decode($value, true);
            default:
                return $value;
        }
    }

    /**
     * الحصول على إعدادات فئة معينة
     */
    public static function getByCategory($category)
    {
        return static::where('category', $category)->get();
    }

    /**
     * الحصول على الإعدادات العامة (المتاحة للعرض)
     */
    public static function getPublicSettings()
    {
        return static::where('is_public', true)->get();
    }
} 