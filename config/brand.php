<?php

// إعدادات النشاط التجاري (SaaS): القيم هنا افتراضية من .env،
// وما يحفظه صاحب النشاط من لوحة التحكم (الإعدادات) يُقدَّم عليها تلقائيًا
return [
    // الاسم الافتراضي: اسم سجل النشاط (Brand::load)، وإلا اسم المنصة
    'name' => env('BRAND_NAME', 'موعد كلاود'),
    'name_en' => env('BRAND_NAME_EN', 'MawidCloud'),
    'business_type' => env('BRAND_BUSINESS_TYPE', 'other'),
    'tagline' => env('BRAND_TAGLINE', 'احجز موعدك بسهولة في دقيقة'),
    'phone' => env('BRAND_PHONE', ''),          // مثال: 05XXXXXXXX
    'whatsapp' => env('BRAND_WHATSAPP', ''),    // مثال: 9665XXXXXXXX (بدون +)
    'email' => env('BRAND_EMAIL', ''),
    'address' => env('BRAND_ADDRESS', ''),
    // بيانات التحويل البنكي: كل نشاط يدخلها من الإعدادات، والصفحة تتعطل إذا ما فيه آيبان
    'bank_name' => env('BRAND_BANK_NAME', ''),
    'bank_account_holder' => env('BRAND_BANK_ACCOUNT_HOLDER', ''),
    'bank_iban' => env('BRAND_BANK_IBAN', ''),
    'bank_account_number' => env('BRAND_BANK_ACCOUNT_NUMBER', ''),
    'primary_color' => env('BRAND_PRIMARY_COLOR', '#7c3aed'),
    'logo' => env('BRAND_LOGO', '/mawidcloud-logo.svg'),
    // روابط اختيارية تظهر في القالب العام إن وُجدت
    'store_url' => env('BRAND_STORE_URL', ''),
    'android_app_url' => env('BRAND_ANDROID_APP_URL', ''),
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
