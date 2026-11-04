<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AddNotificationPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:add-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'إضافة صلاحية الإشعارات لدور مسؤول العملاء والفواتير';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔔 إضافة صلاحية الإشعارات لدور مسؤول العملاء والفواتير...');

        try {
            // التحقق من وجود الدور
            $officeRole = DB::table('roles')->where('name', 'office')->first();
            if (!$officeRole) {
                $this->error('❌ دور مسؤول العملاء والفواتير غير موجود!');
                return 1;
            }

            // التحقق من وجود الصلاحية
            $notificationPermission = DB::table('permissions')->where('name', 'system.notifications')->first();
            if (!$notificationPermission) {
                $this->error('❌ صلاحية الإشعارات غير موجودة!');
                return 1;
            }

            // التحقق من عدم وجود الربط مسبقاً
            $existingPermission = DB::table('role_permissions')
                ->where('role_id', $officeRole->id)
                ->where('permission_id', $notificationPermission->id)
                ->first();

            if ($existingPermission) {
                $this->warn('⚠️ صلاحية الإشعارات موجودة بالفعل لدور مسؤول العملاء والفواتير');
                return 0;
            }

            // إضافة الصلاحية
            DB::table('role_permissions')->insert([
                'role_id' => $officeRole->id,
                'permission_id' => $notificationPermission->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->info('✅ تم إضافة صلاحية الإشعارات لدور مسؤول العملاء والفواتير بنجاح!');

            // عرض الصلاحيات الحالية للدور
            $this->info('📋 الصلاحيات الحالية لدور مسؤول العملاء والفواتير:');
            $permissions = DB::table('role_permissions')
                ->join('permissions', 'role_permissions.permission_id', '=', 'permissions.id')
                ->where('role_permissions.role_id', $officeRole->id)
                ->orderBy('permissions.category')
                ->orderBy('permissions.display_name')
                ->get();

            foreach ($permissions as $permission) {
                $this->line("   - {$permission->display_name} ({$permission->category})");
            }

            return 0;

        } catch (\Exception $e) {
            $this->error('❌ حدث خطأ أثناء إضافة الصلاحية: ' . $e->getMessage());
            return 1;
        }
    }
} 