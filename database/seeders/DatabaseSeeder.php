<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\AdminSeeder;
use Database\Seeders\SampleDataSeeder;
use Database\Seeders\DeviceSeeder;
use Database\Seeders\TranslationSeeder;
use Database\Seeders\ServiceProviderSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\AbandonedCartSeeder;
use Database\Seeders\CouponSeeder;

class DatabaseSeeder extends Seeder
{
   
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            TranslationSeeder::class,
            ServiceProviderSeeder::class,
            RoleSeeder::class, // إضافة seeder الأدوار
            PermissionSeeder::class, // إضافة seeder الصلاحيات
            CouponSeeder::class, // إضافة seeder الكوبونات
        ]);

    
    }
}
