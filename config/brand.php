<?php

// إعدادات العلامة التجارية: كل عميل (نسخة SaaS) يغيّرها من .env بدون تعديل الكود
return [
    'name' => env('BRAND_NAME', 'كات شوب'),
    'name_en' => env('BRAND_NAME_EN', 'Cat Shop'),
    'phone' => env('BRAND_PHONE', ''),          // مثال: 05XXXXXXXX
    'whatsapp' => env('BRAND_WHATSAPP', ''),    // مثال: 9665XXXXXXXX (بدون +)
    'email' => env('BRAND_EMAIL', ''),
    'primary_color' => env('BRAND_PRIMARY_COLOR', '#7c3aed'),
    'logo' => env('BRAND_LOGO', '/logo.png'),
];
