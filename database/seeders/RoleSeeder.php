<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // تحديث الأدوار الموجودة أو إنشاؤها

        // إدخال الأدوار
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'مدير النظام',
                'description' => 'مدير النظام مع جميع الصلاحيات',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'office',
                'display_name' => 'مسؤول العملاء والفواتير',
                'description' => 'مسؤول عن إدارة العملاء والحجوزات والفواتير',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'operator',
                'display_name' => 'مسؤول المنتجات',
                'description' => 'مسؤول عن إدارة المنتجات ومقدمي الخدمة وجداول العمل',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['name' => $role['name']],
                $role
            );
        }
    }
} 