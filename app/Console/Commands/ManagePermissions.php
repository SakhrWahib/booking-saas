<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

class ManagePermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:manage 
                            {action : إجراء الإدارة (list, assign, remove, reset)}
                            {--user= : معرف المستخدم}
                            {--role= : اسم الدور}
                            {--permission= : اسم الصلاحية}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'إدارة صلاحيات المستخدمين والأدوار';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');

        switch ($action) {
            case 'list':
                $this->listPermissions();
                break;
            case 'assign':
                $this->assignPermission();
                break;
            case 'remove':
                $this->removePermission();
                break;
            case 'reset':
                $this->resetPermissions();
                break;
            default:
                $this->error('إجراء غير معروف. الإجراءات المتاحة: list, assign, remove, reset');
                return 1;
        }

        return 0;
    }

    private function listPermissions()
    {
        $this->info('📋 قائمة الصلاحيات والأدوار:');

        // عرض الأدوار وصلاحياتها
        $roles = Role::with('permissions')->get();
        
        foreach ($roles as $role) {
            $this->info("\n🎭 الدور: {$role->display_name} ({$role->name})");
            $this->info("   📝 الوصف: {$role->description}");
            
            if ($role->permissions->count() > 0) {
                $groupedPermissions = $role->permissions->groupBy('category');
                foreach ($groupedPermissions as $category => $permissions) {
                    $this->info("   📁 {$category}:");
                    foreach ($permissions as $permission) {
                        $this->info("      ✅ {$permission->display_name} ({$permission->name})");
                    }
                }
            } else {
                $this->info("   ⚠️  لا توجد صلاحيات محددة");
            }
        }

        // عرض المستخدمين وأدوارهم
        $this->info("\n👥 المستخدمين وأدوارهم:");
        $users = User::with('role')->get();
        
        foreach ($users as $user) {
            $status = $user->is_active ? '✅ نشط' : '❌ غير نشط';
            $roleName = $user->role ? $user->role->display_name : 'غير محدد';
            
            $this->info("   👤 {$user->name} ({$user->email}) - {$status}");
            $this->info("      🎭 الدور: {$roleName}");
        }
    }

    private function assignPermission()
    {
        $userId = $this->option('user');
        $roleName = $this->option('role');
        $permissionName = $this->option('permission');

        if (!$userId && !$roleName) {
            $this->error('يجب تحديد معرف المستخدم أو اسم الدور');
            return 1;
        }

        if (!$permissionName) {
            $this->error('يجب تحديد اسم الصلاحية');
            return 1;
        }

        // التحقق من وجود الصلاحية
        $permission = Permission::where('name', $permissionName)->first();
        if (!$permission) {
            $this->error("الصلاحية '{$permissionName}' غير موجودة");
            return 1;
        }

        if ($userId) {
            // إضافة صلاحية لمستخدم محدد
            $user = User::find($userId);
            if (!$user) {
                $this->error("المستخدم برقم {$userId} غير موجود");
                return 1;
            }

            if (!$user->role) {
                $this->error("المستخدم {$user->name} لا يملك دور محدد");
                return 1;
            }

            if ($user->role->hasPermission($permissionName)) {
                $this->warn("المستخدم {$user->name} يملك الصلاحية '{$permissionName}' بالفعل");
                return 0;
            }

            $user->role->addPermission($permissionName);
            $this->info("✅ تم إضافة الصلاحية '{$permissionName}' للمستخدم {$user->name}");

        } elseif ($roleName) {
            // إضافة صلاحية لدور محدد
            $role = Role::where('name', $roleName)->first();
            if (!$role) {
                $this->error("الدور '{$roleName}' غير موجود");
                return 1;
            }

            if ($role->hasPermission($permissionName)) {
                $this->warn("الدور '{$role->display_name}' يملك الصلاحية '{$permissionName}' بالفعل");
                return 0;
            }

            $role->addPermission($permissionName);
            $this->info("✅ تم إضافة الصلاحية '{$permissionName}' للدور {$role->display_name}");
        }
    }

    private function removePermission()
    {
        $userId = $this->option('user');
        $roleName = $this->option('role');
        $permissionName = $this->option('permission');

        if (!$userId && !$roleName) {
            $this->error('يجب تحديد معرف المستخدم أو اسم الدور');
            return 1;
        }

        if (!$permissionName) {
            $this->error('يجب تحديد اسم الصلاحية');
            return 1;
        }

        if ($userId) {
            // إزالة صلاحية من مستخدم محدد
            $user = User::find($userId);
            if (!$user) {
                $this->error("المستخدم برقم {$userId} غير موجود");
                return 1;
            }

            if (!$user->role) {
                $this->error("المستخدم {$user->name} لا يملك دور محدد");
                return 1;
            }

            if (!$user->role->hasPermission($permissionName)) {
                $this->warn("المستخدم {$user->name} لا يملك الصلاحية '{$permissionName}'");
                return 0;
            }

            $user->role->removePermission($permissionName);
            $this->info("✅ تم إزالة الصلاحية '{$permissionName}' من المستخدم {$user->name}");

        } elseif ($roleName) {
            // إزالة صلاحية من دور محدد
            $role = Role::where('name', $roleName)->first();
            if (!$role) {
                $this->error("الدور '{$roleName}' غير موجود");
                return 1;
            }

            if (!$role->hasPermission($permissionName)) {
                $this->warn("الدور '{$role->display_name}' لا يملك الصلاحية '{$permissionName}'");
                return 0;
            }

            $role->removePermission($permissionName);
            $this->info("✅ تم إزالة الصلاحية '{$permissionName}' من الدور {$role->display_name}");
        }
    }

    private function resetPermissions()
    {
        if (!$this->confirm('هل أنت متأكد من إعادة تعيين جميع الصلاحيات؟ هذا سيحذف جميع الصلاحيات المخصصة.')) {
            $this->info('تم إلغاء العملية');
            return 0;
        }

        $this->info('🔄 إعادة تعيين الصلاحيات...');

        // حذف جميع الصلاحيات المخصصة
        \DB::table('role_permissions')->delete();

        // إعادة تشغيل seeder
        \Artisan::call('db:seed', ['--class' => 'PermissionSeeder']);

        $this->info('✅ تم إعادة تعيين الصلاحيات بنجاح');
    }
} 