<?php

// قائمة لوحة التحكم: مجموعات وعناصر. كل عنصر يظهر فقط لمن لديه الصلاحية (permission)
// أو الدور (role). 'active' نمط اسم المسار لتمييز العنصر الحالي. 'super' = لمالك المنصة فقط.
// العناصر المرتبطة بخاصية في الباقة تُخفى تلقائيًا (انظر EnforcePlan::ROUTE_FEATURES).
return [
    [
        'title' => ['ar' => 'المنصة', 'en' => 'Platform'],
        'items' => [
            ['label' => ['ar' => 'نظرة عامة', 'en' => 'Overview'], 'icon' => 'fa-crown', 'route' => 'platform.overview', 'active' => 'platform.overview', 'super' => true],
            ['label' => ['ar' => 'الأنشطة', 'en' => 'Businesses'], 'icon' => 'fa-building', 'route' => 'platform.tenants.index', 'active' => 'platform.tenants.*', 'super' => true],
            ['label' => ['ar' => 'الباقات', 'en' => 'Plans'], 'icon' => 'fa-box-open', 'route' => 'platform.plans.index', 'active' => 'platform.plans.*', 'super' => true],
        ],
    ],
    [
        'title' => ['ar' => 'الرئيسية', 'en' => 'Overview'],
        'items' => [
            ['label' => ['ar' => 'لوحة المعلومات', 'en' => 'Dashboard'], 'icon' => 'fa-gauge-high', 'route' => 'dashboard', 'active' => 'dashboard'],
            ['label' => ['ar' => 'التقارير', 'en' => 'Reports'], 'icon' => 'fa-chart-line', 'route' => 'admin.reports.index', 'active' => 'admin.reports.*', 'role' => 'admin'],
        ],
    ],
    [
        'title' => ['ar' => 'الحجوزات', 'en' => 'Bookings'],
        'items' => [
            ['label' => ['ar' => 'كل الحجوزات', 'en' => 'All bookings'], 'icon' => 'fa-calendar-check', 'route' => 'admin.bookings.index', 'active' => 'admin.bookings.index|admin.bookings.show|admin.bookings.create|admin.bookings.edit', 'permission' => 'bookings.view'],
            ['label' => ['ar' => 'التقويم', 'en' => 'Calendar'], 'icon' => 'fa-calendar-days', 'route' => 'admin.bookings.calendar', 'active' => 'admin.bookings.calendar', 'permission' => 'bookings.view'],
            ['label' => ['ar' => 'حجوزات اليوم', 'en' => 'Today'], 'icon' => 'fa-clock', 'route' => 'admin.bookings.daily', 'active' => 'admin.bookings.daily', 'permission' => 'bookings.view'],
            ['label' => ['ar' => 'أوقات العمل', 'en' => 'Working hours'], 'icon' => 'fa-business-time', 'route' => 'admin.working-schedules.index', 'active' => 'admin.working-schedules.*', 'permission' => 'working_schedules.view'],
        ],
    ],
    [
        'title' => ['ar' => 'الخدمات والفريق', 'en' => 'Services & team'],
        'items' => [
            ['label' => ['ar' => 'الخدمات', 'en' => 'Services'], 'icon' => 'fa-layer-group', 'route' => 'admin.products.index', 'active' => 'admin.products.*', 'permission' => 'products.view'],
            ['label' => ['ar' => 'مقدّمو الخدمة', 'en' => 'Staff'], 'icon' => 'fa-user-tie', 'route' => 'admin.service-providers.index', 'active' => 'admin.service-providers.*', 'permission' => 'service_providers.view'],
            ['label' => ['ar' => 'الإجازات', 'en' => 'Time off'], 'icon' => 'fa-umbrella-beach', 'route' => 'admin.time-offs.index', 'active' => 'admin.time-offs.*', 'permission' => 'service_providers.view'],
        ],
    ],
    [
        'title' => ['ar' => 'العملاء', 'en' => 'Customers'],
        'items' => [
            ['label' => ['ar' => 'العملاء', 'en' => 'Customers'], 'icon' => 'fa-users', 'route' => 'admin.customers.index', 'active' => 'admin.customers.*', 'permission' => 'customers.view'],
            ['label' => ['ar' => 'عملاء التطبيق', 'en' => 'App customers'], 'icon' => 'fa-mobile-screen', 'route' => 'admin.mobile-customers.index', 'active' => 'admin.mobile-customers.*', 'permission' => 'customers.view'],
            ['label' => ['ar' => 'سلات غير مكتملة', 'en' => 'Abandoned carts'], 'icon' => 'fa-cart-arrow-down', 'route' => 'admin.abandoned-carts.index', 'active' => 'admin.abandoned-carts.*', 'permission' => 'customers.view'],
            ['label' => ['ar' => 'برنامج الولاء', 'en' => 'Loyalty'], 'icon' => 'fa-gift', 'route' => 'admin.loyalty.index', 'active' => 'admin.loyalty.*', 'role' => 'admin'],
        ],
    ],
    [
        'title' => ['ar' => 'المالية', 'en' => 'Finance'],
        'items' => [
            ['label' => ['ar' => 'الفواتير', 'en' => 'Invoices'], 'icon' => 'fa-file-invoice-dollar', 'route' => 'admin.invoices.index', 'active' => 'admin.invoices.*', 'permission' => 'invoices.view'],
            ['label' => ['ar' => 'الكوبونات', 'en' => 'Coupons'], 'icon' => 'fa-ticket', 'route' => 'admin.coupons.index', 'active' => 'admin.coupons.*', 'permission' => 'coupons.view'],
        ],
    ],
    [
        'title' => ['ar' => 'الإعدادات', 'en' => 'Settings'],
        'items' => [
            ['label' => ['ar' => 'إعدادات النشاط', 'en' => 'Business settings'], 'icon' => 'fa-sliders', 'route' => 'admin.settings.edit', 'active' => 'admin.settings.*', 'permission' => 'system.settings'],
            ['label' => ['ar' => 'المستخدمون', 'en' => 'Users'], 'icon' => 'fa-user-shield', 'route' => 'admin.users.index', 'active' => 'admin.users.*', 'permission' => 'users.view'],
            ['label' => ['ar' => 'اللغات', 'en' => 'Languages'], 'icon' => 'fa-language', 'route' => 'admin.languages.index', 'active' => 'admin.languages.*', 'permission' => 'system.settings'],
        ],
    ],
];
