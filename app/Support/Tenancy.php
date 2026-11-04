<?php

namespace App\Support;

use App\Models\Tenant;

// النشاط الحالي للطلب. إن لم يُحدَّد صراحةً نستخدم النشاط الافتراضي (الأول).
class Tenancy
{
    private static ?Tenant $current = null;

    public static function set(?Tenant $tenant): void
    {
        self::$current = $tenant;
    }

    public static function forget(): void
    {
        self::$current = null;
    }

    public static function current(): ?Tenant
    {
        if (!self::$current) {
            try {
                self::$current = Tenant::query()->orderBy('id')->first();
            } catch (\Throwable $e) {
                return null; // قبل تشغيل الـ migrations
            }
        }

        return self::$current;
    }

    public static function id(): ?int
    {
        return self::current()?->id;
    }

    // تحديد النشاط من اسم النطاق: نطاق خاص، أو نطاق فرعي slug.central-domain
    public static function fromHost(string $host): ?Tenant
    {
        $tenant = Tenant::where('domain', $host)->where('is_active', true)->first();
        $central = config('tenancy.central_domain');

        if (!$tenant && $central && str_ends_with($host, '.' . $central)) {
            $slug = substr($host, 0, -strlen('.' . $central));
            $tenant = Tenant::where('slug', $slug)->where('is_active', true)->first();
        }

        return $tenant;
    }
}
