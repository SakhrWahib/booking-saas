<?php

// إعدادات النشاط التجاري (SaaS): القيم هنا افتراضية من .env،
// وما يحفظه صاحب النشاط من لوحة التحكم (الإعدادات) يُقدَّم عليها تلقائيًا
return [
    'name' => env('BRAND_NAME', 'كات شوب'),
    'name_en' => env('BRAND_NAME_EN', 'Cat Shop'),
    'business_type' => env('BRAND_BUSINESS_TYPE', 'pet_grooming'),
    'tagline' => env('BRAND_TAGLINE', 'احجز موعدك بسهولة في دقيقة'),
    'phone' => env('BRAND_PHONE', ''),          // مثال: 05XXXXXXXX
    'whatsapp' => env('BRAND_WHATSAPP', ''),    // مثال: 9665XXXXXXXX (بدون +)
    'email' => env('BRAND_EMAIL', ''),
    'address' => env('BRAND_ADDRESS', ''),
    'primary_color' => env('BRAND_PRIMARY_COLOR', '#7c3aed'),
    'logo' => env('BRAND_LOGO', '/logo.png'),
    'service_label' => env('BRAND_SERVICE_LABEL', ''),
    'provider_label' => env('BRAND_PROVIDER_LABEL', ''),

    // أنواع الأنشطة الجاهزة: تحدد المسميات الافتراضية
    'presets' => [
        'pet_grooming' => ['label' => 'عناية بالحيوانات الأليفة', 'service' => 'الخدمة', 'provider' => 'المختص'],
        'car_wash' => ['label' => 'غسيل سيارات', 'service' => 'الغسلة', 'provider' => 'الفني'],
        'salon' => ['label' => 'صالون تجميل', 'service' => 'الخدمة', 'provider' => 'المختص'],
        'clinic' => ['label' => 'عيادة', 'service' => 'الموعد', 'provider' => 'الطبيب'],
        'sports' => ['label' => 'ملاعب وصالات', 'service' => 'الحجز', 'provider' => 'الملعب'],
        'cleaning' => ['label' => 'تنظيف منازل', 'service' => 'الخدمة', 'provider' => 'الفريق'],
        'other' => ['label' => 'نشاط آخر', 'service' => 'الخدمة', 'provider' => 'مقدّم الخدمة'],
    ],
];
