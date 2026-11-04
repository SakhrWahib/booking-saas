<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ServiceProvider;
use App\Models\WorkingSchedule;
use App\Models\TimeSlot;

class ServiceProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء مقدمي خدمة تجريبيين
        $providers = [
            [
                'name' => 'أحمد محمد',
                'phone' => '0501234567',
                'email' => 'ahmed@example.com',
                'capacity' => 3,
                'work_type' => 'single_person',
                'coverage_type' => 'area_based',
                'latitude' => 24.7136,
                'longitude' => 46.6753,
                'address' => 'الرياض، حي الملك فهد',
                'coverage_area' => [
                    'type' => 'polygon',
                    'coordinates' => [
                        ['lat' => 24.7300, 'lng' => 46.6500],
                        ['lat' => 24.7300, 'lng' => 46.7000],
                        ['lat' => 24.7000, 'lng' => 46.7000],
                        ['lat' => 24.7000, 'lng' => 46.6500],
                        ['lat' => 24.7300, 'lng' => 46.6500] // إغلاق المضلع
                    ]
                ]
            ],
            [
                'name' => 'فاطمة أحمد',
                'phone' => '0507654321',
                'email' => 'fatma@example.com',
                'capacity' => 2,
                'work_type' => 'multiple_persons',
                'coverage_type' => 'area_based',
                'latitude' => 24.7500,
                'longitude' => 46.7000,
                'address' => 'الرياض، حي الوادي',
                'coverage_area' => [
                    'type' => 'polygon',
                    'coordinates' => [
                        ['lat' => 24.7600, 'lng' => 46.6800],
                        ['lat' => 24.7600, 'lng' => 46.7200],
                        ['lat' => 24.7300, 'lng' => 46.7200],
                        ['lat' => 24.7300, 'lng' => 46.6800],
                        ['lat' => 24.7600, 'lng' => 46.6800] // إغلاق المضلع
                    ]
                ]
            ],
            [
                'name' => 'محمد علي',
                'phone' => '0509876543',
                'email' => 'mohammed@example.com',
                'capacity' => 4,
                'work_type' => 'single_person',
                'coverage_type' => 'area_based',
                'latitude' => 24.6800,
                'longitude' => 46.6400,
                'address' => 'الرياض، حي السليمانية',
                'coverage_area' => [
                    'type' => 'polygon',
                    'coordinates' => [
                        ['lat' => 24.6900, 'lng' => 46.6200],
                        ['lat' => 24.6900, 'lng' => 46.6600],
                        ['lat' => 24.6600, 'lng' => 46.6600],
                        ['lat' => 24.6600, 'lng' => 46.6200],
                        ['lat' => 24.6900, 'lng' => 46.6200] // إغلاق المضلع
                    ]
                ]
            ]
        ];

        foreach ($providers as $providerData) {
            // إنشاء جدول العمل الافتراضي أولاً
            $workingSchedule = WorkingSchedule::create([
                'schedule_name' => 'جدول العمل الافتراضي - ' . $providerData['name'],
                'start_date' => now()->startOfMonth(),
                'end_date' => now()->addYear()->endOfMonth(),
                'active_days' => [
                    'saturday' => ['active' => true, 'start_time' => '09:00', 'end_time' => '17:00'],
                    'sunday' => ['active' => true, 'start_time' => '09:00', 'end_time' => '17:00'],
                    'monday' => ['active' => true, 'start_time' => '09:00', 'end_time' => '17:00'],
                    'tuesday' => ['active' => true, 'start_time' => '09:00', 'end_time' => '17:00'],
                    'wednesday' => ['active' => true, 'start_time' => '09:00', 'end_time' => '17:00'],
                    'thursday' => ['active' => true, 'start_time' => '09:00', 'end_time' => '17:00'],
                    'friday' => ['active' => false, 'start_time' => null, 'end_time' => null],
                ],
                'description' => 'جدول العمل الافتراضي',
                'is_default' => true,
                'is_active' => true
            ]);
            
            // إضافة time slots افتراضية
            $workingSchedule->timeSlots()->createMany([
                ['time' => '09:00', 'period' => 'morning', 'is_active' => true],
                ['time' => '11:00', 'period' => 'morning', 'is_active' => true],
                ['time' => '13:00', 'period' => 'afternoon', 'is_active' => true],
                ['time' => '15:00', 'period' => 'afternoon', 'is_active' => true],
                ['time' => '17:00', 'period' => 'evening', 'is_active' => true],
                ['time' => '19:00', 'period' => 'evening', 'is_active' => true],
            ]);
            
            // إنشاء مقدم الخدمة مع ربطه بجدول العمل
            $providerData['working_schedule_id'] = $workingSchedule->id;
            $provider = ServiceProvider::create($providerData);
        }
    }
} 