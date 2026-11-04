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
        // إنشاء الرابط الرمزي تلقائياً
        if (!file_exists(public_path('storage'))) {
            $this->createStorageLink();
        }

        // Blade Directives للصلاحيات
        $this->registerPermissionBladeDirectives();

        // إعدادات النشاط (SaaS) من قاعدة البيانات
        \App\Support\Brand::load();
    }

    private function createStorageLink()
    {
        try {
            $target = storage_path('app/public');
            $link = public_path('storage');
            
            // التحقق من وجود دالة symlink قبل استخدامها
            if (function_exists('symlink')) {
                if (!file_exists($link)) {
                    symlink($target, $link);
                }
            } else {
                // حل بديل: إنشاء مجلد storage يدوياً إذا لم تكن symlink متاحة
                \Log::warning('symlink function is not available on this server, using manual directory creation');
                
                if (!file_exists($link)) {
                    if (!file_exists($target)) {
                        mkdir($target, 0755, true);
                    }
                    
                    // نسخ محتويات storage/app/public إلى public/storage
                    if (is_dir($target)) {
                        $this->copyDirectory($target, $link);
                    }
                }
            }
        } catch (\Exception $e) {
            // تجاهل الأخطاء إذا كان الرابط موجود بالفعل
            \Log::warning('Failed to create storage link: ' . $e->getMessage());
        }
    }
    
    private function copyDirectory($source, $destination)
    {
        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }
        
        $dir = opendir($source);
        while (($file = readdir($dir)) !== false) {
            if ($file != '.' && $file != '..') {
                $sourcePath = $source . '/' . $file;
                $destPath = $destination . '/' . $file;
                
                if (is_dir($sourcePath)) {
                    $this->copyDirectory($sourcePath, $destPath);
                } else {
                    copy($sourcePath, $destPath);
                }
            }
        }
        closedir($dir);
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
