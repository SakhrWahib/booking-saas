<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
        // البريد وكلمة المرور من .env، وإذا ما فيه كلمة مرور تنولّد عشوائية وتنطبع مرة وحدة
        $email = env('ADMIN_EMAIL', 'admin@example.com');
        $password = env('ADMIN_PASSWORD') ?: Str::random(16);
        if (!env('ADMIN_PASSWORD')) {
            $this->command?->warn("Admin login: {$email} / {$password}");
        }

        $owner = User::create([
            'name' => 'مدير النظام',
            'email' => $email,
            'password' => Hash::make($password),
            'role_id' => $adminRole->id,
            'is_active' => true
        ]);

        // مالك المنصة (لوحة /platform)
        $owner->forceFill(['is_super_admin' => true])->save();
    }
}
