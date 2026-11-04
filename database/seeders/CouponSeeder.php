<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coupon;
use Carbon\Carbon;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        // حذف الكوبونات الموجودة
        Coupon::query()->delete();

        // إضافة كوبونات تجريبية
        $coupons = [
            [
                'code' => 'CPN_TKHUYOGG',
                'name' => 'خصم 10% غسيل قطط',
                'type' => 'fixed',
                'value' => 5.00,
                'min_amount' => 0.00,
                'max_uses' => 100,
                'used_count' => 0,
                'start_date' => Carbon::now()->subDays(1),
                'end_date' => Carbon::now()->addDays(2),
                'is_active' => true,
            ],
            [
                'code' => 'WELCOME10',
                'name' => 'خصم ترحيبي 10%',
                'type' => 'percentage',
                'value' => 10.00,
                'min_amount' => 50.00,
                'max_uses' => 100,
                'used_count' => 0,
                'start_date' => Carbon::now()->subDays(30),
                'end_date' => Carbon::now()->addDays(60),
                'is_active' => true,
            ],
            [
                'code' => 'SAVE20',
                'name' => 'خصم 20 ريال',
                'type' => 'fixed',
                'value' => 20.00,
                'min_amount' => 100.00,
                'max_uses' => 50,
                'used_count' => 0,
                'start_date' => Carbon::now()->subDays(15),
                'end_date' => Carbon::now()->addDays(45),
                'is_active' => true,
            ],
            [
                'code' => 'SUMMER15',
                'name' => 'خصم صيفي 15%',
                'type' => 'percentage',
                'value' => 15.00,
                'min_amount' => 75.00,
                'max_uses' => 75,
                'used_count' => 0,
                'start_date' => Carbon::now()->subDays(10),
                'end_date' => Carbon::now()->addDays(30),
                'is_active' => true,
            ],
            [
                'code' => 'NEWCUSTOMER',
                'name' => 'خصم للعملاء الجدد',
                'type' => 'percentage',
                'value' => 25.00,
                'min_amount' => 25.00,
                'max_uses' => 200,
                'used_count' => 0,
                'start_date' => Carbon::now()->subDays(60),
                'end_date' => Carbon::now()->addDays(90),
                'is_active' => true,
            ],
            [
                'code' => 'VIP50',
                'name' => 'خصم VIP 50 ريال',
                'type' => 'fixed',
                'value' => 50.00,
                'min_amount' => 200.00,
                'max_uses' => 25,
                'used_count' => 0,
                'start_date' => Carbon::now()->subDays(5),
                'end_date' => Carbon::now()->addDays(15),
                'is_active' => true,
            ],
        ];

        foreach ($coupons as $coupon) {
            Coupon::create($coupon);
        }
    }
} 