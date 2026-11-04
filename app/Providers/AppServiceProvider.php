<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // الرابط الرمزي للتخزين يُنشأ بأمر php artisan storage:link عند التثبيت

        // Blade Directives للصلاحيات
        $this->registerPermissionBladeDirectives();

        // إعدادات النشاط (SaaS) من قاعدة البيانات
        \App\Support\Brand::load();
    }

    private function registerPermissionBladeDirectives()
    {
        // @can directive للصلاحيات
        Blade::directive('can', function ($expression) {
            return "<?php if (hasPermission({$expression})): ?>";
        });

        Blade::directive('endcan', function () {
            return "<?php endif; ?>";
        });

        // @canany directive للصلاحيات المتعددة
        Blade::directive('canany', function ($expression) {
            return "<?php if (hasAnyPermission({$expression})): ?>";
        });

        Blade::directive('endcanany', function () {
            return "<?php endif; ?>";
        });

        // @cannot directive للصلاحيات الممنوعة
        Blade::directive('cannot', function ($expression) {
            return "<?php if (!hasPermission({$expression})): ?>";
        });

        Blade::directive('endcannot', function () {
            return "<?php endif; ?>";
        });

        // @canview directive لعرض العناصر
        Blade::directive('canview', function ($expression) {
            return "<?php if (canView({$expression})): ?>";
        });

        Blade::directive('endcanview', function () {
            return "<?php endif; ?>";
        });

        // @cancreate directive لإنشاء العناصر
        Blade::directive('cancreate', function ($expression) {
            return "<?php if (canCreate({$expression})): ?>";
        });

        Blade::directive('endcancreate', function () {
            return "<?php endif; ?>";
        });

        // @canedit directive لتعديل العناصر
        Blade::directive('canedit', function ($expression) {
            return "<?php if (canEdit({$expression})): ?>";
        });

        Blade::directive('endcanedit', function () {
            return "<?php endif; ?>";
        });

        // @candelete directive لحذف العناصر
        Blade::directive('candelete', function ($expression) {
            return "<?php if (canDelete({$expression})): ?>";
        });

        Blade::directive('endcandelete', function () {
            return "<?php endif; ?>";
        });
    }
}
