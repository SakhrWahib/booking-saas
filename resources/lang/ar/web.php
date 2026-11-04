<?php

return [
    // ترجمات عامة للتطبيق
    'app' => [
        'system_name' => 'نظام إدارة مكاتب العمرة وحجز الباصات',
    ],

    // القائمة الجانبية
    'sidebar' => [
        'home' => 'الرئيسية',
        'user_management' => 'إدارة المستخدمين',
        'users' => 'المستخدمين',
        'offices' => 'المكاتب',
        'buses' => 'الباصات',
        'bookings' => 'الحجوزات',
        'reports' => 'التقارير',
        'settings' => 'الإعدادات',
        'languages' => 'اللغات'
    ],

    // المصادقة
    'auth' => [
        'login' => 'تسجيل الدخول',
        'logout' => 'تسجيل الخروج',
        'email' => 'البريد الإلكتروني',
        'password' => 'كلمة المرور',
        'remember_me' => 'تذكرني',
        'forgot_password' => 'نسيت كلمة المرور؟',
        'reset_password' => 'إعادة تعيين كلمة المرور'
    ],

    // الإشعارات
    'notifications' => [
        'title' => 'الإشعارات',
        'unread' => 'غير مقروءة',
        'view_all' => 'عرض جميع الإشعارات',
        'no_notifications' => 'لا توجد إشعارات',
        'messages' => [
            'new_device' => 'تم تسجيل جهاز جديد',
            'booking_confirmed' => 'تم تأكيد الحجز',
            'booking_cancelled' => 'تم إلغاء الحجز',
            'new_message' => 'رسالة جديدة'
        ]
    ],

    // المحادثات
    'chat' => [
        'title' => 'المحادثات',
        'unread' => 'رسائل جديدة',
        'view_all' => 'عرض كل المحادثات',
        'messages' => [
            'new_message' => 'رسالة جديدة',
            'sample_message' => 'مرحباً، هل يمكنني الاستفسار عن حجز الباصات؟'
        ]
    ],

    // المستخدمين
    'users' => [
        'management' => 'إدارة المستخدمين',
        'management_description' => 'إدارة وتنظيم حسابات المستخدمين في النظام',
        'add_new' => 'إضافة مستخدم جديد',
        'search_placeholder' => 'البحث عن مستخدم...',
        'roles' => [
            'admin' => 'مدير النظام',
            'office' => 'مكتب',
            'operator' => 'مشغل',
            'user' => 'مستخدم'
        ],
        'status' => [
            'active' => 'نشط',
            'inactive' => 'غير نشط',
            'pending' => 'قيد الانتظار'
        ],
        'verification' => [
            'verified' => 'تم التحقق',
            'pending' => 'قيد الانتظار',
            'rejected' => 'مرفوض'
        ]
    ],

    // جدول المستخدمين
    'table' => [
        'actions' => 'الإجراءات',
        'status' => 'الحالة',
        'verification_status' => 'حالة التحقق',
        'role' => 'الدور',
        'phone' => 'رقم الهاتف',
        'email' => 'البريد الإلكتروني',
        'user' => 'المستخدم',
        'avatar' => 'الصورة الشخصية'
    ],

    // الحالات
    'status' => [
        'active' => 'نشط',
        'inactive' => 'غير نشط',
        'pending' => 'قيد الانتظار',
        'new' => 'جديد'
    ],

    // حالات التحقق
    'verification_status' => [
        'verified' => 'تم التحقق',
        'pending' => 'قيد الانتظار',
        'rejected' => 'مرفوض'
    ],

    // الأدوار
    'web_roles' => [
        'admin' => 'مدير النظام',
        'office' => 'مكتب',
        'operator' => 'مشغل',
        'user' => 'مستخدم'
    ],

    // الأجهزة
    'devices' => [
        'device_number' => 'جهاز رقم :number'
    ],

    // الوقت
    'time' => [
        'minutes_ago' => 'دقائق مضت',
        'hours_ago' => 'ساعات مضت',
        'days_ago' => 'أيام مضت',
        'now' => 'الآن'
    ],

    // الرسائل
    'messages' => [
        'success' => 'تمت العملية بنجاح',
        'error' => 'حدث خطأ',
        'error_title' => 'خطأ في النظام',
        'confirm_delete' => 'هل أنت متأكد من الحذف؟',
        'confirm_action' => 'هل أنت متأكد من تنفيذ هذا الإجراء؟',
        'validation_error' => 'خطأ في البيانات المدخلة',
        'server_error' => 'حدث خطأ في الخادم، يرجى المحاولة مرة أخرى'
    ],

    // الأخطاء
    'errors' => [
        'title' => 'خطأ في النظام',
        'general' => 'حدث خطأ، يرجى المحاولة مرة أخرى',
        'validation' => 'خطأ في البيانات المدخلة',
        'server' => 'حدث خطأ في الخادم'
    ],

    // الأزرار والإجراءات
    'actions' => [
        'ok' => 'موافق',
        'cancel' => 'إلغاء',
        'save' => 'حفظ',
        'edit' => 'تعديل',
        'delete' => 'حذف',
        'view' => 'عرض',
        'back' => 'العودة',
        'close' => 'إغلاق',
        'retry' => 'إعادة المحاولة'
    ],

    // لوحة التحكم
    'dashboard' => [
        'title' => 'نظام حجوزات غسيل كات شوب',
        'welcome' => 'مرحباً بك في لوحة تحكم كات شوب',
        'stats' => [
            'verification_requests' => 'طلبات التحقق',
            'new_users' => 'المستخدمون الجدد',
            'active_users' => 'المستخدمون النشطون',
            'total_users' => 'إجمالي المستخدمين',
            'awaiting_review' => 'في انتظار المراجعة',
            'this_week' => 'هذا الأسبوع',
            'activity_rate' => 'معدل النشاط :value%',
            'increase_percentage' => 'زيادة بنسبة :value%',
            'last_30_days' => 'آخر 30 يوم'
        ]
    ],

    // الأدوار
    'roles' => [
        'admin' => 'مدير النظام',
        'office' => 'مكتب',
        'operator' => 'مشغل',
        'user' => 'مستخدم'
    ],

    // حالات التحقق
    'verification' => [
        'verified' => 'تم التحقق',
        'pending' => 'قيد الانتظار',
        'rejected' => 'مرفوض'
    ],

    // الفلاتر
    'filters' => [
        'status' => 'الحالة',
        'role' => 'الدور',
        'verification' => 'حالة التحقق',
        'date' => 'التاريخ',
        'date_options' => [
            'today' => 'اليوم',
            'week' => 'آخر أسبوع',
            'month' => 'آخر شهر'
        ]
    ],

    // النوافذ المنبثقة
    'modals' => [
        'add_user' => 'إضافة مستخدم جديد',
        'edit_user' => 'تعديل المستخدم',
        'close' => 'إغلاق',
        'confirm' => 'تأكيد',
        'cancel' => 'إلغاء',
        'save' => 'حفظ',
        'save_changes' => 'حفظ التغييرات',
        'delete' => 'حذف',
        'loading' => 'جاري التحميل...'
    ],

    // النماذج
    'forms' => [
        'name' => 'الاسم',
        'email' => 'البريد الإلكتروني',
        'phone' => 'رقم الهاتف',
        'password' => 'كلمة المرور',
        'password_hint' => 'اتركه فارغاً إذا كنت لا تريد تغيير كلمة المرور',
        'role' => 'الدور',
        'avatar' => 'الصورة الشخصية',
        'verification_status' => 'حالة التحقق',
        'status' => 'الحالة',
        'cancel' => 'إلغاء',
        'save' => 'حفظ',
        'save_changes' => 'حفظ التغييرات',
        'choose_file' => 'اختيار ملف',
        'no_file_chosen' => 'لم يتم اختيار أي ملف',
        'current_avatar' => 'الصورة الحالية',
        'no_avatar' => 'لا توجد صورة',
        'new_avatar_preview' => 'معاينة الصورة الجديدة'
    ],

    // إدارة اللغات
    'languages' => [
        'management' => 'إدارة اللغات',
        'management_description' => 'إدارة وتنظيم اللغات المتاحة في النظام',
        'add_new' => 'إضافة لغة جديدة',
        'search_placeholder' => 'بحث في اللغات...',
        'total_languages' => 'إجمالي اللغات',
        'active_languages' => 'اللغات النشطة',
        'default_language' => 'اللغة الافتراضية',
        'total_translations' => 'إجمالي الترجمات',
        'multilingual' => 'متعدد اللغات',
        'available' => 'متاحة للاستخدام',
        'across_languages' => 'عبر جميع اللغات',
        
        'table' => [
            'flag' => 'العلم',
            'code' => 'رمز اللغة',
            'name' => 'اسم اللغة',
            'native_name' => 'الاسم المحلي',
            'translations' => 'الترجمات',
            'status' => 'الحالة',
            'actions' => 'الإجراءات'
        ],

        'status' => [
            'active' => 'نشط',
            'inactive' => 'غير نشط',
            'default' => 'افتراضي'
        ],

        'forms' => [
            'code' => [
                'label' => 'رمز اللغة',
                'placeholder' => 'مثال: ar'
            ],
            'name' => [
                'label' => 'اسم اللغة',
                'placeholder' => 'مثال: Arabic'
            ],
            'native_name' => [
                'label' => 'الاسم المحلي',
                'placeholder' => 'مثال: العربية'
            ],
            'flag' => [
                'label' => 'علم اللغة',
                'current' => 'العلم الحالي',
                'no_flag' => 'لا يوجد علم'
            ],
            'status' => [
                'label' => 'الحالة',
                'active' => 'نشط',
                'inactive' => 'غير نشط'
            ],
            'is_default' => [
                'label' => 'اللغة الافتراضية',
                'yes' => 'نعم',
                'no' => 'لا'
            ]
        ],

        'modals' => [
            'add' => [
                'title' => 'إضافة لغة جديدة'
            ],
            'edit' => [
                'title' => 'تعديل اللغة'
            ]
        ],

        'messages' => [
            'confirm_status' => 'هل أنت متأكد من تغيير حالة اللغة؟',
            'confirm_delete' => 'هل أنت متأكد من حذف هذه اللغة؟',
            'loading' => 'جاري التحميل...',
            'success' => [
                'add' => 'تم إضافة اللغة بنجاح',
                'update' => 'تم تحديث اللغة بنجاح',
                'delete' => 'تم حذف اللغة بنجاح',
                'status' => 'تم تحديث حالة اللغة بنجاح'
            ],
            'error' => [
                'add' => 'حدث خطأ أثناء إضافة اللغة',
                'update' => 'حدث خطأ أثناء تحديث اللغة',
                'delete' => 'حدث خطأ أثناء حذف اللغة',
                'status' => 'حدث خطأ أثناء تحديث حالة اللغة',
                'fetch' => 'حدث خطأ أثناء جلب بيانات اللغة'
            ]
        ]
    ]
]; 