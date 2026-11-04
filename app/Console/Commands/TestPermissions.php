<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

class TestPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:test {--user= : معرف المستخدم للاختبار}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'اختبار نظام الصلاحيات';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 بدء اختبار نظام الصلاحيات...');

        // اختبار الأدوار
        $this->testRoles();
        
        // اختبار الصلاحيات
        $this->testPermissions();
        
        // اختبار المستخدمين
        $this->testUsers();

        $this->info('✅ تم إكمال اختبار نظام الصلاحيات بنجاح!');
    }

    private function testRoles()
    {
        $this->info("\n🎭 اختبار الأدوار:");
        
        $roles = Role::with('permissions')->get();
        
        if ($roles->count() == 0) {
            $this->error('❌ لا توجد أدوار في قاعدة البيانات');
            return;
        }

        foreach ($roles as $role) {
            $this->info("   📋 الدور: {$role->display_name} ({$role->name})");
            $this->info("      📝 الوصف: {$role->description}");
            $this->info("      🔐 الصلاحيات: {$role->permissions->count()} صلاحية");
            
            if ($role->permissions->count() > 0) {
                $groupedPermissions = $role->permissions->groupBy('category');
                foreach ($groupedPermissions as $category => $permissions) {
                    $this->info("         📁 {$category}: {$permissions->count()} صلاحية");
                }
            }
            $this->info("");
        }
    }

    private function testPermissions()
    {
        $this->info("🔐 اختبار الصلاحيات:");
        
        $permissions = Permission::all();
        
        if ($permissions->count() == 0) {
            $this->error('❌ لا توجد صلاحيات في قاعدة البيانات');
            return;
        }

        $this->info("   📊 إجمالي الصلاحيات: {$permissions->count()}");
        
        $groupedPermissions = $permissions->groupBy('category');
        foreach ($groupedPermissions as $category => $categoryPermissions) {
            $this->info("   📁 {$category}: {$categoryPermissions->count()} صلاحية");
        }
        
        $this->info("");
    }

    private function testUsers()
    {
        $this->info("👥 اختبار المستخدمين:");
        
        $users = User::with('role.permissions')->get();
        
        if ($users->count() == 0) {
            $this->error('❌ لا توجد مستخدمين في قاعدة البيانات');
            return;
        }

        $this->info("   📊 إجمالي المستخدمين: {$users->count()}");
        
        foreach ($users as $user) {
            $status = $user->is_active ? '✅ نشط' : '❌ غير نشط';
            $roleName = $user->role ? $user->role->display_name : 'غير محدد';
            $permissionsCount = $user->role ? $user->role->permissions->count() : 0;
            
            $this->info("   👤 {$user->name} ({$user->email}) - {$status}");
            $this->info("      🎭 الدور: {$roleName}");
            $this->info("      🔐 الصلاحيات: {$permissionsCount} صلاحية");
            
            // اختبار صلاحيات محددة
            $this->testSpecificPermissions($user);
            $this->info("");
        }
    }

    private function testSpecificPermissions($user)
    {
        $testPermissions = [
            'users.view',
            'customers.view', 
            'products.view',
            'bookings.view',
            'invoices.view',
            'system.settings'
        ];

        foreach ($testPermissions as $permission) {
            $hasPermission = $user->hasPermission($permission);
            $status = $hasPermission ? '✅' : '❌';
            $this->info("         {$status} {$permission}");
        }
    }
} 