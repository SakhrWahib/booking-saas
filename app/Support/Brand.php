<?php

namespace App\Support;

use App\Models\SystemSetting;
use Illuminate\Support\Facades\Cache;

// يدمج إعدادات النشاط المحفوظة في قاعدة البيانات مع config/brand.php
class Brand
{
    public const CACHE_KEY = 'brand_settings';

    public const KEYS = [
        'name', 'name_en', 'business_type', 'tagline', 'phone', 'whatsapp', 'email',
        'address', 'primary_color', 'logo', 'service_label', 'provider_label',
    ];

    public static function load(): void
    {
        try {
            $saved = Cache::rememberForever(self::CACHE_KEY, fn () => SystemSetting::where('category', 'brand')
                ->pluck('value', 'key')->all());
        } catch (\Throwable $e) {
            return; // قاعدة البيانات غير جاهزة بعد (مثل أثناء التثبيت)
        }

        // نبدأ دائمًا من قيم config/brand.php الأصلية حتى يكون التحميل المتكرر صحيحًا
        self::$defaults ??= config('brand');
        $brand = self::$defaults;

        foreach ($saved as $key => $value) {
            if ($value !== null && $value !== '') {
                $brand[$key] = $value;
            }
        }

        // المسميات الافتراضية حسب نوع النشاط
        $preset = $brand['presets'][$brand['business_type']] ?? $brand['presets']['other'];
        $brand['service_label'] = $brand['service_label'] ?: $preset['service'];
        $brand['provider_label'] = $brand['provider_label'] ?: $preset['provider'];
        $brand['business_label'] = $preset['label'];

        config(['brand' => $brand]);
    }

    private static ?array $defaults = null;

    public static function save(array $values): void
    {
        foreach ($values as $key => $value) {
            SystemSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'category' => 'brand', 'type' => 'string', 'label' => $key]
            );
        }

        Cache::forget(self::CACHE_KEY);
    }
}
