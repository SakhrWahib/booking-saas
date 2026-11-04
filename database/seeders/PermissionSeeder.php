<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // حذف البيانات الموجودة
        DB::table('role_permissions')->delete();
        DB::table('permissions')->delete();

        // إدخال الصلاحيات
        $permissions = [
            // صلاحيات المستخدمين
            ['name' => 'users.view', 'display_name' => 'عرض المستخدمين', 'category' => 'users', 'description' => 'إمكانية عرض قائمة المستخدمين'],
            ['name' => 'users.create', 'display_name' => 'إضافة مستخدمين', 'category' => 'users', 'description' => 'إمكانية إضافة مستخدمين جدد'],
            ['name' => 'users.edit', 'display_name' => 'تعديل المستخدمين', 'category' => 'users', 'description' => 'إمكانية تعديل بيانات المستخدمين'],
            ['name' => 'users.delete', 'display_name' => 'حذف المستخدمين', 'category' => 'users', 'description' => 'إمكانية حذف المستخدمين'],
            ['name' => 'users.manage_roles', 'display_name' => 'إدارة الأدوار', 'category' => 'users', 'description' => 'إمكانية إدارة أدوار المستخدمين'],

            // صلاحيات العملاء
            ['name' => 'customers.view', 'display_name' => 'عرض العملاء', 'category' => 'customers', 'description' => 'إمكانية عرض قائمة العملاء'],
            ['name' => 'customers.create', 'display_name' => 'إضافة عملاء', 'category' => 'customers', 'description' => 'إمكانية إضافة عملاء جدد'],
            ['name' => 'customers.edit', 'display_name' => 'تعديل العملاء', 'category' => 'customers', 'description' => 'إمكانية تعديل بيانات العملاء'],
            ['name' => 'customers.delete', 'display_name' => 'حذف العملاء', 'category' => 'customers', 'description' => 'إمكانية حذف العملاء'],

            // صلاحيات الحجوزات
            ['name' => 'bookings.view', 'display_name' => 'عرض الحجوزات', 'category' => 'bookings', 'description' => 'إمكانية عرض قائمة الحجوزات'],
            ['name' => 'bookings.create', 'display_name' => 'إضافة حجوزات', 'category' => 'bookings', 'description' => 'إمكانية إضافة حجوزات جديدة'],
            ['name' => 'bookings.edit', 'display_name' => 'تعديل الحجوزات', 'category' => 'bookings', 'description' => 'إمكانية تعديل الحجوزات'],
            ['name' => 'bookings.delete', 'display_name' => 'حذف الحجوزات', 'category' => 'bookings', 'description' => 'إمكانية حذف الحجوزات'],
            ['name' => 'bookings.confirm', 'display_name' => 'تأكيد الحجوزات', 'category' => 'bookings', 'description' => 'إمكانية تأكيد الحجوزات'],
            ['name' => 'bookings.complete', 'display_name' => 'إكمال الحجوزات', 'category' => 'bookings', 'description' => 'إمكانية إكمال الحجوزات'],
            ['name' => 'bookings.cancel', 'display_name' => 'إلغاء الحجوزات', 'category' => 'bookings', 'description' => 'إمكانية إلغاء الحجوزات'],

            // صلاحيات الفواتير
            ['name' => 'invoices.view', 'display_name' => 'عرض الفواتير', 'category' => 'invoices', 'description' => 'إمكانية عرض قائمة الفواتير'],
            ['name' => 'invoices.create', 'display_name' => 'إنشاء فواتير', 'category' => 'invoices', 'description' => 'إمكانية إنشاء فواتير جديدة'],
            ['name' => 'invoices.edit', 'display_name' => 'تعديل الفواتير', 'category' => 'invoices', 'description' => 'إمكانية تعديل الفواتير'],
            ['name' => 'invoices.delete', 'display_name' => 'حذف الفواتير', 'category' => 'invoices', 'description' => 'إمكانية حذف الفواتير'],
            ['name' => 'invoices.print', 'display_name' => 'طباعة الفواتير', 'category' => 'invoices', 'description' => 'إمكانية طباعة الفواتير'],

            // صلاحيات المنتجات
            ['name' => 'products.view', 'display_name' => 'عرض المنتجات', 'category' => 'products', 'description' => 'إمكانية عرض قائمة المنتجات'],
            ['name' => 'products.create', 'display_name' => 'إضافة منتجات', 'category' => 'products', 'description' => 'إمكانية إضافة منتجات جديدة'],
            ['name' => 'products.edit', 'display_name' => 'تعديل المنتجات', 'category' => 'products', 'description' => 'إمكانية تعديل المنتجات'],
            ['name' => 'products.delete', 'display_name' => 'حذف المنتجات', 'category' => 'products', 'description' => 'إمكانية حذف المنتجات'],

            // صلاحيات مقدمي الخدمة
            ['name' => 'service_providers.view', 'display_name' => 'عرض مقدمي الخدمة', 'category' => 'service_providers', 'description' => 'إمكانية عرض قائمة مقدمي الخدمة'],
            ['name' => 'service_providers.create', 'display_name' => 'إضافة مقدمي خدمة', 'category' => 'service_providers', 'description' => 'إمكانية إضافة مقدمي خدمة جدد'],
            ['name' => 'service_providers.edit', 'display_name' => 'تعديل مقدمي الخدمة', 'category' => 'service_providers', 'description' => 'إمكانية تعديل مقدمي الخدمة'],
            ['name' => 'service_providers.delete', 'display_name' => 'حذف مقدمي الخدمة', 'category' => 'service_providers', 'description' => 'إمكانية حذف مقدمي الخدمة'],

            // صلاحيات جداول العمل
            ['name' => 'working_schedules.view', 'display_name' => 'عرض جداول العمل', 'category' => 'working_schedules', 'description' => 'إمكانية عرض جداول العمل'],
            ['name' => 'working_schedules.create', 'display_name' => 'إنشاء جداول عمل', 'category' => 'working_schedules', 'description' => 'إمكانية إنشاء جداول عمل جديدة'],
            ['name' => 'working_schedules.edit', 'display_name' => 'تعديل جداول العمل', 'category' => 'working_schedules', 'description' => 'إمكانية تعديل جداول العمل'],
            ['name' => 'working_schedules.delete', 'display_name' => 'حذف جداول العمل', 'category' => 'working_schedules', 'description' => 'إمكانية حذف جداول العمل'],

            // صلاحيات الكوبونات
            ['name' => 'coupons.view', 'display_name' => 'عرض الكوبونات', 'category' => 'coupons', 'description' => 'إمكانية عرض قائمة الكوبونات'],
            ['name' => 'coupons.create', 'display_name' => 'إضافة كوبونات', 'category' => 'coupons', 'description' => 'إمكانية إضافة كوبونات جديدة'],
            ['name' => 'coupons.edit', 'display_name' => 'تعديل الكوبونات', 'category' => 'coupons', 'description' => 'إمكانية تعديل الكوبونات'],
            ['name' => 'coupons.delete', 'display_name' => 'حذف الكوبونات', 'category' => 'coupons', 'description' => 'إمكانية حذف الكوبونات'],
            ['name' => 'coupons.manage', 'display_name' => 'إدارة الكوبونات', 'category' => 'coupons', 'description' => 'إمكانية إدارة جميع جوانب الكوبونات'],

            // صلاحيات النظام
            ['name' => 'system.settings', 'display_name' => 'إعدادات النظام', 'category' => 'system', 'description' => 'إمكانية تعديل إعدادات النظام'],
            ['name' => 'system.reports', 'display_name' => 'التقارير', 'category' => 'system', 'description' => 'إمكانية عرض التقارير'],
            ['name' => 'system.notifications', 'display_name' => 'الإشعارات', 'category' => 'system', 'description' => 'إمكانية إدارة الإشعارات'],
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->insert([
                'name' => $permission['name'],
                'display_name' => $permission['display_name'],
                'category' => $permission['category'],
                'description' => $permission['description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // إضافة الصلاحيات للأدوار
        $this->assignPermissionsToRoles();
    }

    private function assignPermissionsToRoles()
    {
        // الحصول على معرفات الأدوار
        $adminRole = DB::table('roles')->where('name', 'admin')->first();
        $officeRole = DB::table('roles')->where('name', 'office')->first();
        $operatorRole = DB::table('roles')->where('name', 'operator')->first();

        if (!$adminRole || !$officeRole || !$operatorRole) {
            return;
        }

        // جميع الصلاحيات للمدير
        $allPermissions = DB::table('permissions')->pluck('id');
        foreach ($allPermissions as $permissionId) {
            DB::table('role_permissions')->insert([
                'role_id' => $adminRole->id,
                'permission_id' => $permissionId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // صلاحيات مسؤول العملاء والفواتير
        $officePermissions = DB::table('permissions')
            ->whereIn('name', [
                'customers.view', 'customers.create', 'customers.edit', 'customers.delete',
                'bookings.view', 'bookings.create', 'bookings.edit', 'bookings.confirm', 'bookings.complete', 'bookings.cancel',
                'invoices.view', 'invoices.create', 'invoices.edit', 'invoices.print',
                'coupons.view', 'coupons.create', 'coupons.edit', 'coupons.delete',
                'system.reports', 'system.notifications'
            ])
            ->pluck('id');

        foreach ($officePermissions as $permissionId) {
            DB::table('role_permissions')->insert([
                'role_id' => $officeRole->id,
                'permission_id' => $permissionId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // صلاحيات مسؤول المنتجات
        $operatorPermissions = DB::table('permissions')
            ->whereIn('name', [
                'products.view', 'products.create', 'products.edit', 'products.delete',
                'service_providers.view', 'service_providers.create', 'service_providers.edit', 'service_providers.delete',
                'working_schedules.view', 'working_schedules.create', 'working_schedules.edit', 'working_schedules.delete'
            ])
            ->pluck('id');

        foreach ($operatorPermissions as $permissionId) {
            DB::table('role_permissions')->insert([
                'role_id' => $operatorRole->id,
                'permission_id' => $permissionId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
} 