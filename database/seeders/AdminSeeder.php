<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // إنشاء دور المدير إذا لم يكن موجوداً
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            [
                'display_name' => 'مدير النظام',
                'description' => 'مدير النظام مع جميع الصلاحيات'
            ]
        );

        // إنشاء مستخدم مدير النظام
        User::create([
            'name' => 'مدير النظام',
            'email' => 'admin@example.com',
            'password' => Hash::make('change-me'),
            'role_id' => $adminRole->id,
            'is_active' => true
        ]);
    }
}
