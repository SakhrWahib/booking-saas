<?php

// هوية المنصة نفسها (المالك)، منفصلة عن هوية كل نشاط في config/brand.php
return [
    'name' => env('PLATFORM_NAME', 'MawidCloud'),
    'name_ar' => env('PLATFORM_NAME_AR', 'موعد كلاود'),
    'support_email' => env('PLATFORM_SUPPORT_EMAIL', ''),

    // المساعد الآلي القديم مكتوب لنشاط سابق (أسعار وروابط ثابتة)، فهو معطّل حتى يُعاد بناؤه
    'chatbot_enabled' => (bool) env('CHATBOT_ENABLED', false),

    // البيانات التجريبية (DemoSeeder) ممنوعة في الإنتاج إلا بهذا الإذن الصريح
    'allow_demo_in_production' => (bool) env('ALLOW_DEMO_SEED_IN_PRODUCTION', false),
];
