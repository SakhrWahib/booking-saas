<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ServiceProvider;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // التأكد من وجود مقدمي الخدمات
        $serviceProviders = ServiceProvider::all();
        
        if ($serviceProviders->isEmpty()) {
            $this->command->info('لا يوجد مقدمي خدمات. سيتم إنشاء مقدمي خدمات تجريبيين...');
            
            $serviceProviders = collect([
                ServiceProvider::create([
                    'name' => 'أحمد محمد',
                    'phone' => '0501234567',
                    'email' => 'ahmed@example.com',
                    'capacity' => 3,
                    'work_type' => 'single_person',
                    'coverage_type' => 'area_based',
                    'latitude' => 24.7136,
                    'longitude' => 46.6753,
                    'address' => 'الرياض، المملكة العربية السعودية',
                    'is_active' => true
                ]),
                ServiceProvider::create([
                    'name' => 'فاطمة عبدالله',
                    'phone' => '0501234568',
                    'email' => 'fatima@example.com',
                    'capacity' => 2,
                    'work_type' => 'single_person',
                    'coverage_type' => 'area_based',
                    'latitude' => 24.7500,
                    'longitude' => 46.7000,
                    'address' => 'الرياض، المملكة العربية السعودية',
                    'is_active' => true
                ]),
                ServiceProvider::create([
                    'name' => 'محمد عبدالرحمن',
                    'phone' => '0501234569',
                    'email' => 'mohammed@example.com',
                    'capacity' => 4,
                    'work_type' => 'multiple_persons',
                    'coverage_type' => 'nearest_booking',
                    'latitude' => 24.6800,
                    'longitude' => 46.6200,
                    'address' => 'الرياض، المملكة العربية السعودية',
                    'is_active' => true
                ])
            ]);
        }

        // إنشاء المنتجات
        $products = [
            [
                'name' => 'غسيل قطط صغيرة',
                'short_description' => 'خدمة غسيل شاملة للقطط الصغيرة',
                'description' => 'خدمة غسيل متكاملة تشمل الشامبو المناسب، التجفيف، وقص الأظافر للقطط الصغيرة التي يقل عمرها عن سنة واحدة.',
                'price' => 50.00,
                'add_ons' => [
                    [
                        'option' => 'تنظيف الأذن',
                        'name' => 'تنظيف شامل للأذن',
                        'price' => 15.00,
                        'image' => null
                    ],
                    [
                        'option' => 'قص الشعر',
                        'name' => 'قص شعر أساسي',
                        'price' => 25.00,
                        'image' => null
                    ]
                ],
                'image_gallery' => [
                    'products/cat-small-1.jpg',
                    'products/cat-small-2.jpg'
                ],
                'is_active' => true,
                'service_providers' => [0, 1] // سيتم ربطها بأول مقدمي خدمات
            ],
            [
                'name' => 'غسيل كلاب متوسطة',
                'short_description' => 'خدمة غسيل للكلاب متوسطة الحجم',
                'description' => 'خدمة غسيل متخصصة للكلاب متوسطة الحجم تشمل الشامبو العلاجي، التجفيف بالمجفف المناسب، وتنظيف الأسنان.',
                'price' => 75.00,
                'add_ons' => [
                    [
                        'option' => 'تنظيف الأسنان',
                        'name' => 'تنظيف الأسنان واللثة',
                        'price' => 30.00,
                        'image' => null
                    ],
                    [
                        'option' => 'مساج مهدئ',
                        'name' => 'مساج للاسترخاء',
                        'price' => 40.00,
                        'image' => null
                    ]
                ],
                'image_gallery' => [
                    'products/dog-medium-1.jpg',
                    'products/dog-medium-2.jpg'
                ],
                'is_active' => true,
                'service_providers' => [1, 2] // سيتم ربطها بمقدمي خدمات آخرين
            ],
            [
                'name' => 'غسيل قطط كبيرة (شيرازي)',
                'short_description' => 'خدمة غسيل متخصصة للقطط الشيرازي',
                'description' => 'خدمة غسيل فاخرة للقطط الشيرازي وطويلة الشعر تشمل شامبو مخصص، تمشيط الشعر، وتنظيف العيون.',
                'price' => 85.00,
                'add_ons' => [
                    [
                        'option' => 'تمشيط متقدم',
                        'name' => 'تمشيط وفك التشابك',
                        'price' => 35.00,
                        'image' => null
                    ],
                    [
                        'option' => 'تنظيف العيون',
                        'name' => 'تنظيف العيون والوجه',
                        'price' => 20.00,
                        'image' => null
                    ],
                    [
                        'option' => 'عطر مخصص',
                        'name' => 'عطر آمن للحيوانات',
                        'price' => 25.00,
                        'image' => null
                    ]
                ],
                'image_gallery' => [
                    'products/cat-persian-1.jpg',
                    'products/cat-persian-2.jpg'
                ],
                'is_active' => true,
                'service_providers' => [0, 2] // سيتم ربطها بمقدمي خدمات مختلفين
            ]
        ];

        foreach ($products as $productData) {
            $serviceProviderIds = $productData['service_providers'];
            unset($productData['service_providers']);
            
            $product = Product::create($productData);
            
            // ربط المنتج بمقدمي الخدمات
            $providerIds = [];
            foreach ($serviceProviderIds as $index) {
                if (isset($serviceProviders[$index])) {
                    $providerIds[] = $serviceProviders[$index]->id;
                }
            }
            
            if (!empty($providerIds)) {
                $product->attachServiceProviders($providerIds);
            }

            $this->command->info("تم إنشاء المنتج: {$product->name}");
        }

        $this->command->info('تم إنشاء ' . count($products) . ' منتجات بنجاح مع ربطها بمقدمي الخدمات!');
    }
} 