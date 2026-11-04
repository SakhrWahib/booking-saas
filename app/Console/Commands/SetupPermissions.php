<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class SetupPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:setup {--fresh : إعادة إنشاء الجداول من جديد}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'إعداد نظام الصلاحيات';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 بدء إعداد نظام الصلاحيات...');

        try {
            // التحقق من وجود الأدوار
            $rolesCount = DB::table('roles')->count();
            if ($rolesCount == 0) {
                $this->error('❌ لا توجد أدوار في قاعدة البيانات. يرجى تشغيل RoleSeeder أولاً.');
                return 1;
            }

            // تشغيل migration للصلاحيات
            $this->info('📋 إنشاء جداول الصلاحيات...');
            Artisan::call('migrate', ['--path' => 'database/migrations/2025_01_25_000000_create_permissions_table.php']);

            // تشغيل seeder للصلاحيات
            $this->info('🔐 إضافة الصلاحيات...');
            Artisan::call('db:seed', ['--class' => 'PermissionSeeder']);

            // التحقق من النتائج
            $permissionsCount = DB::table('permissions')->count();
            $rolePermissionsCount = DB::table('role_permissions')->count();

            $this->info("✅ تم إعداد نظام الصلاحيات بنجاح!");
            $this->info("📊 إحصائيات النظام:");
            $this->info("   - عدد الصلاحيات: {$permissionsCount}");
            $this->info("   - عدد ربط الصلاحيات بالأدوار: {$rolePermissionsCount}");

            // عرض الأدوار والصلاحيات
            $this->displayRolesAndPermissions();

            return 0;

        } catch (\Exception $e) {
            $this->error('❌ حدث خطأ أثناء إعداد نظام الصلاحيات: ' . $e->getMessage());
            return 1;
        }
    }

    private function displayRolesAndPermissions()
    {
        $this->info("\n📋 الأدوار والصلاحيات:");

        $roles = DB::table('roles')->get();
        
        foreach ($roles as $role) {
            $this->info("\n🎭 الدور: {$role->display_name}");
            
            $permissions = DB::table('role_permissions')
                ->join('permissions', 'role_permissions.permission_id', '=', 'permissions.id')
                ->where('role_permissions.role_id', $role->id)
                ->select('permissions.category', 'permissions.display_name')
                ->orderBy('permissions.category')
                ->orderBy('permissions.display_name')
                ->get();

            if ($permissions->count() > 0) {
                $groupedPermissions = $permissions->groupBy('category');
                
                foreach ($groupedPermissions as $category => $categoryPermissions) {
                    $this->info("   📁 {$category}:");
                    foreach ($categoryPermissions as $permission) {
                        $this->info("      ✅ {$permission->display_name}");
                    }
                }
            } else {
                $this->info("   ⚠️  لا توجد صلاحيات محددة");
            }
        }
    }
} 