<!DOCTYPE html>
<html lang="{{ App::getLocale() }}" dir="{{ App::getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>نظام حجوزات غسيل {{ config('brand.name') }}</title>
    
    <!-- Basic Meta Tags -->
    <meta name="description" content="لوحة تحكم نظام حجوزات غسيل {{ config('brand.name') }}">
    <meta name="robots" content="noindex, nofollow">
    <meta name="googlebot" content="noindex, nofollow">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ url('/logo.php') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ url('/logo.php') }}">
    <link rel="shortcut icon" type="image/png" href="{{ url('/logo.php') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ url('/logo.php') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    
    <!-- Styles -->
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Saudi Riyal Font -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@emran-alhaddad/saudi-riyal-font/index.css">
    
    
    <!-- Additional CSS -->
    <style>
        @include('layouts.app-styles')
    </style>
    <style>:root { --brand-color: {{ config('brand.primary_color') }}; }</style>
</head>
<body class="bg-gray-100">
    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay"></div>
    
    <!-- Navigation Bar -->
    <nav class="nav-container shadow-lg fixed w-full top-0 z-50 bg-white">
        <div class="max-w-full mx-auto px-4">
            <div class="flex justify-between h-14">
                <div class="flex items-center {{ App::getLocale() == 'ar' ? 'space-x-reverse' : 'space-x-4' }}">
                    <!-- Mobile Menu Button -->
                    <button class="mobile-menu-btn" id="mobileMenuBtn" onclick="toggleMobileMenu()">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <div class="flex-shrink-0 flex items-center">
                        <!-- الشعار -->
                        <img src="{{ url('/logo.php') }}" alt="{{ config('brand.name_en') }} Logo" class="h-8 w-auto {{ App::getLocale() == 'ar' ? 'ml-2' : 'mr-2' }}" style="max-height: 32px; object-fit: contain; display: block;" onerror="this.style.display='none'">
                        <!-- أيقونة بديلة في حالة عدم ظهور الشعار -->
                        <div class="bg-indigo-900/10 p-2 rounded-full {{ App::getLocale() == 'ar' ? 'ml-2' : 'mr-2' }}" style="display: none;" id="logoFallback">
                            <i class="fas fa-cat text-indigo-900 text-xl"></i>
                        </div>
                        <span class="text-lg font-bold text-gray-800">نظام حجوزات غسيل {{ config('brand.name') }}</span>
                    </div>
                </div>
                <div class="flex items-center gap-6">
                    <!-- Time and Date -->
                    <div class="flex items-center text-sm text-gray-600 bg-gray-50 px-3 py-1.5 rounded-lg">
                        <i class="fas fa-calendar-alt {{ App::getLocale() == 'ar' ? 'ml-2' : 'mr-2' }} text-indigo-500"></i>
                        <span>{{ now()->format('H:i') }}</span>
                        <span class="mx-2 text-gray-300">|</span>
                        <span>{{ now()->format('Y-m-d') }}</span>
                    </div>

                    <!-- Language Switcher -->
                    <div class="relative">
                        @if(App::getLocale() == 'ar')
                            <a href="{{ route('switch.language', 'en') }}" class="language-btn">
                                <i class="fas fa-globe"></i>
                                <span>English</span>
                            </a>
                        @else
                            <a href="{{ route('switch.language', 'ar') }}" class="language-btn">
                                <i class="fas fa-globe"></i>
                                <span>العربية</span>
                            </a>
                        @endif
                    </div>

                    <!-- Notifications -->
                    <div class="relative" x-data="{ open: false, notifications: [], unreadCount: 0 }" x-init="
                        // تحميل الإشعارات عند تحميل الصفحة
                        fetchNotifications();
                        fetchUnreadCount();
                        
                        // تحديث الإشعارات كل 30 ثانية
                        setInterval(() => {
                            fetchNotifications();
                            fetchUnreadCount();
                        }, 30000);
                        
                        function fetchNotifications() {
                            fetch('/admin/notifications/dropdown')
                                .then(response => response.json())
                                .then(data => {
                                    notifications = data.notifications;
                                })
                                .catch(error => console.error('خطأ في تحميل الإشعارات:', error));
                        }
                        
                        function fetchUnreadCount() {
                            fetch('/admin/notifications/unread-count')
                                .then(response => response.json())
                                .then(data => {
                                    unreadCount = data.count;
                                })
                                .catch(error => console.error('خطأ في تحميل عداد الإشعارات:', error));
                        }
                    ">
                        <button @click="open = !open" class="relative flex items-center p-2 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors duration-200">
                            <i class="fas fa-bell text-xl"></i>
                            <!-- Badge for unread notifications -->
                            <span x-show="unreadCount > 0" x-text="unreadCount" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold"></span>
                        </button>
                        
                        <!-- Notifications Dropdown -->
                        <div x-show="open" @click.away="open = false" class="absolute {{ App::getLocale() == 'ar' ? 'left-0' : 'right-0' }} mt-2 w-80 bg-white rounded-lg shadow-lg overflow-hidden border border-gray-100 z-50">
                            <div class="p-4 border-b border-gray-100">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-semibold text-gray-800">الإشعارات</h3>
                                    <button @click="markAllAsRead()" class="text-sm text-indigo-600 hover:text-indigo-800">تحديد الكل كمقروء</button>
                                </div>
                            </div>
                            
                            <div class="max-h-96 overflow-y-auto">
                                <template x-if="notifications.length === 0">
                                    <div class="p-4 text-center text-gray-500">
                                        <i class="fas fa-bell-slash text-2xl mb-2"></i>
                                        <p>لا توجد إشعارات جديدة</p>
                                    </div>
                                </template>
                                
                                <template x-for="notification in notifications" :key="notification.id">
                                    <div class="notification-item p-4 border-b border-gray-50 hover:bg-gray-50 transition-colors duration-200" :data-notification-id="notification.id" :class="notification.is_read ? 'read' : ''">
                                        <div class="flex items-start space-x-3" :class="currentLocale == 'ar' ? 'space-x-reverse' : 'space-x-3'">
                                            <div class="flex-shrink-0">
                                                <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-calendar-check text-indigo-600 text-sm"></i>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900" x-text="notification.title"></p>
                                                <p class="text-sm text-gray-600 mt-1" x-text="notification.message"></p>
                                                <p class="text-xs text-gray-400 mt-2" x-text="formatDate(notification.created_at)"></p>
                                            </div>
                                            <button x-show="!notification.is_read" @click="markAsRead(notification.id)" class="mark-read-btn flex-shrink-0 text-gray-400 hover:text-indigo-600">
                                                <i class="fas fa-check text-xs"></i>
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            
                            <div class="p-4 border-t border-gray-100">
                                <a href="/admin/notifications" class="block text-center text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                    عرض جميع الإشعارات
                                </a>
                            </div>
                        </div>
                    </div>

                    
                    <!-- User Menu -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center {{ App::getLocale() == 'ar' ? 'space-x-reverse' : 'space-x-2' }} hover:bg-indigo-50 rounded-lg px-3 py-2 transition-colors duration-200">
                            <div class="bg-indigo-100 p-2 rounded-full">
                                <i class="fas fa-user text-indigo-600"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-700">{{ __('web.users.roles.admin') }}</span>
                            <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" class="absolute {{ App::getLocale() == 'ar' ? 'left-0' : 'right-0' }} mt-2 w-48 bg-white rounded-lg shadow-lg overflow-hidden border border-gray-100">
                            <div class="py-1">
                                <form method="POST" action="{{ route('admin.logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full {{ App::getLocale() == 'ar' ? 'text-right' : 'text-left' }} px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                                        <i class="fas fa-sign-out-alt {{ App::getLocale() == 'ar' ? 'ml-2' : 'mr-2' }} text-red-500"></i>
                                        {{ __('web.auth.logout') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar fixed top-14 w-64 h-full" id="sidebar" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%); box-shadow: 4px 0 6px -1px rgba(0, 0, 0, 0.1);">
        <nav class="p-4">
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('dashboard') }}" class="flex items-center text-gray-100 p-2.5 rounded-lg hover:bg-white/10 transition-all duration-300 group transform hover:-translate-x-1">
                        <div class="bg-indigo-500/20 p-2 rounded-lg {{ App::getLocale() == 'ar' ? 'ml-2' : 'mr-2' }} group-hover:bg-indigo-500/30 transition-colors duration-300">
                            <i class="fas fa-home text-indigo-300 text-base"></i>
                        </div>
                        <span class="font-medium tracking-wide text-sm">{{ __('web.sidebar.home') }}</span>
                    </a>
                </li>

                @can('system.settings')
                <li>
                    <a href="{{ route('admin.settings.edit') }}" class="flex items-center text-gray-100 p-2.5 rounded-lg hover:bg-white/10 transition-all duration-300 group transform hover:-translate-x-1">
                        <div class="bg-indigo-500/20 p-2 rounded-lg {{ App::getLocale() == 'ar' ? 'ml-2' : 'mr-2' }} group-hover:bg-indigo-500/30 transition-colors duration-300">
                            <i class="fas fa-sliders-h text-indigo-300 text-base"></i>
                        </div>
                        <span class="font-medium tracking-wide text-sm">إعدادات النشاط</span>
                    </a>
                </li>
                @endcan
                
                <!-- برنامج الولاء - للمدير فقط -->
                @auth
                <li>
                    <a href="{{ route('admin.loyalty.index') }}" class="flex items-center text-gray-100 p-2.5 rounded-lg hover:bg-white/10 transition-all duration-300 group transform hover:-translate-x-1">
                        <div class="bg-indigo-500/20 p-2 rounded-lg {{ App::getLocale() == 'ar' ? 'ml-2' : 'mr-2' }} group-hover:bg-indigo-500/30 transition-colors duration-300">
                            <i class="fas fa-star text-indigo-300 text-base"></i>
                        </div>
                        <span class="font-medium tracking-wide text-sm">برنامج الولاء</span>
                    </a>
                </li>
                @endauth
                
         
                @can('users.view')
                <li>
                    <a href="{{ route('admin.users.index') }}" class="flex items-center text-gray-100 p-2.5 rounded-lg hover:bg-white/10 transition-all duration-300 group transform hover:-translate-x-1">
                        <div class="bg-blue-500/20 p-2 rounded-lg {{ App::getLocale() == 'ar' ? 'ml-2' : 'mr-2' }} group-hover:bg-blue-500/30 transition-colors duration-300">
                            <i class="fas fa-user-shield text-blue-300 text-base"></i>
                        </div>
                        <span class="font-medium tracking-wide text-sm">إدارة المستخدمين</span>
                    </a>
                </li>
                @endcan
                @can('customers.view')
                <li>
                    <a href="{{ route('admin.customers.index') }}" class="flex items-center text-gray-100 p-2.5 rounded-lg hover:bg-white/10 transition-all duration-300 group transform hover:-translate-x-1">
                        <div class="bg-blue-500/20 p-2 rounded-lg {{ App::getLocale() == 'ar' ? 'ml-2' : 'mr-2' }} group-hover:bg-blue-500/30 transition-colors duration-300">
                            <i class="fas fa-users text-blue-300 text-base"></i>
                        </div>
                        <span class="font-medium tracking-wide text-sm">العملاء</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.mobile-customers.index') }}" class="flex items-center text-gray-100 p-2.5 rounded-lg hover:bg-white/10 transition-all duration-300 group transform hover:-translate-x-1">
                        <div class="bg-blue-500/20 p-2 rounded-lg {{ App::getLocale() == 'ar' ? 'ml-2' : 'mr-2' }} group-hover:bg-blue-500/30 transition-colors duration-300">
                            <i class="fas fa-mobile-alt text-blue-300 text-base"></i>
                        </div>
                        <span class="font-medium tracking-wide text-sm">العملاء عبر الجوال</span>
                    </a>
                </li>
                @endcan
                @can('customers.view')
                <li>
                    <a href="{{ route('admin.abandoned-carts.index') }}" class="flex items-center text-gray-100 p-2.5 rounded-lg hover:bg-white/10 transition-all duration-300 group transform hover:-translate-x-1">
                        <div class="bg-blue-500/20 p-2 rounded-lg {{ App::getLocale() == 'ar' ? 'ml-2' : 'mr-2' }} group-hover:bg-blue-500/30 transition-colors duration-300">
                            <i class="fas fa-shopping-cart text-blue-300 text-base"></i>
                        </div>
                        <span class="font-medium tracking-wide text-sm">السلات المتروكة</span>
                    </a>
                </li>
                @endcan
                @can('service_providers.view')
                <li>
                    <a href="{{ route('admin.service-providers.index') }}" class="flex items-center text-gray-100 p-2.5 rounded-lg hover:bg-white/10 transition-all duration-300 group transform hover:-translate-x-1">
                        <div class="bg-blue-500/20 p-2 rounded-lg {{ App::getLocale() == 'ar' ? 'ml-2' : 'mr-2' }} group-hover:bg-blue-500/30 transition-colors duration-300">
                            <i class="fas fa-user-tie text-blue-300 text-base"></i>
                        </div>
                        <span class="font-medium tracking-wide text-sm">مقدمي الخدمات</span>
                    </a>
                </li>
                @endcan
                @can('working_schedules.view')
                <li>
                    <a href="{{ route('admin.working-schedules.index') }}" class="flex items-center text-gray-100 p-2.5 rounded-lg hover:bg-white/10 transition-all duration-300 group transform hover:-translate-x-1">
                        <div class="bg-blue-500/20 p-2 rounded-lg {{ App::getLocale() == 'ar' ? 'ml-2' : 'mr-2' }} group-hover:bg-blue-500/30 transition-colors duration-300">
                            <i class="fas fa-clock text-blue-300 text-base"></i>
                        </div>
                        <span class="font-medium tracking-wide text-sm">أوقات العمل</span>
                    </a>
                </li>
                @endcan
                @can('products.view')
                <li>
                    <a href="{{ route('admin.products.index') }}" class="flex items-center text-gray-100 p-2.5 rounded-lg hover:bg-white/10 transition-all duration-300 group transform hover:-translate-x-1">
                        <div class="bg-blue-500/20 p-2 rounded-lg {{ App::getLocale() == 'ar' ? 'ml-2' : 'mr-2' }} group-hover:bg-blue-500/30 transition-colors duration-300">
                            <i class="fas fa-box text-blue-300 text-base"></i>
                        </div>
                        <span class="font-medium tracking-wide text-sm">المنتجات</span>
                    </a>
                </li>
                @endcan
                @can('bookings.view')
                <li>
                    <a href="{{ route('admin.bookings.index') }}" class="flex items-center text-gray-100 p-2.5 rounded-lg hover:bg-white/10 transition-all duration-300 group transform hover:-translate-x-1">
                        <div class="bg-blue-500/20 p-2 rounded-lg {{ App::getLocale() == 'ar' ? 'ml-2' : 'mr-2' }} group-hover:bg-blue-500/30 transition-colors duration-300">
                            <i class="fas fa-calendar-check text-blue-300 text-base"></i>
                        </div>
                        <span class="font-medium tracking-wide text-sm">الحجوزات</span>
                    </a>
                </li>
                @endcan
                @can('invoices.view')
                <li>
                    <a href="{{ route('admin.invoices.index') }}" class="flex items-center text-gray-100 p-2.5 rounded-lg hover:bg-white/10 transition-all duration-300 group transform hover:-translate-x-1">
                        <div class="bg-blue-500/20 p-2 rounded-lg {{ App::getLocale() == 'ar' ? 'ml-2' : 'mr-2' }} group-hover:bg-blue-500/30 transition-colors duration-300">
                            <i class="fas fa-file-invoice text-blue-300 text-base"></i>
                        </div>
                        <span class="font-medium tracking-wide text-sm">الفواتير</span>
                    </a>
                </li>
                @endcan
                @can('coupons.view')
                <li>
                    <a href="{{ route('admin.coupons.index') }}" class="flex items-center text-gray-100 p-2.5 rounded-lg hover:bg-white/10 transition-all duration-300 group transform hover:-translate-x-1">
                        <div class="bg-blue-500/20 p-2 rounded-lg {{ App::getLocale() == 'ar' ? 'ml-2' : 'mr-2' }} group-hover:bg-blue-500/30 transition-colors duration-300">
                            <i class="fas fa-ticket-alt text-blue-300 text-base"></i>
                        </div>
                        <span class="font-medium tracking-wide text-sm">الكوبونات</span>
                    </a>
                </li>
                @endcan
                @can('system.settings')
                <li>
                    <a href="{{ route('admin.languages.index') }}" class="flex items-center text-gray-100 p-2.5 rounded-lg hover:bg-white/10 transition-all duration-300 group transform hover:-translate-x-1">
                        <div class="bg-indigo-500/20 p-2 rounded-lg {{ App::getLocale() == 'ar' ? 'ml-2' : 'mr-2' }} group-hover:bg-indigo-500/30 transition-colors duration-300">
                            <i class="fas fa-language text-indigo-300 text-base"></i>
                        </div>
                        <span class="font-medium tracking-wide text-sm">{{ __('web.sidebar.languages') }}</span>
                    </a>
                </li>
                @endcan
             
      


                </li>
            </ul>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content min-h-screen pt-14">
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        // تعريف متغير اللغة الحالية للاستخدام في Alpine.js
        window.currentLocale = '{{ App::getLocale() }}';
        
        // تهيئة AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        // دالة عامة لإظهار النوافذ المنبثقة
        function showModal(modalId) {
            $('#' + modalId).removeClass('hidden').addClass('show');
        }
        
        // دالة عامة لإخفاء النوافذ المنبثقة
        function hideModal(modalId) {
            $('#' + modalId).removeClass('show').addClass('hidden');
        }

        // دالة تنسيق التاريخ للإشعارات
        function formatDate(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diffInMinutes = Math.floor((now - date) / (1000 * 60));
            
            if (diffInMinutes < 1) {
                return 'الآن';
            } else if (diffInMinutes < 60) {
                return `منذ ${diffInMinutes} دقيقة`;
            } else if (diffInMinutes < 1440) {
                const hours = Math.floor(diffInMinutes / 60);
                return `منذ ${hours} ساعة`;
            } else {
                const days = Math.floor(diffInMinutes / 1440);
                return `منذ ${days} يوم`;
            }
        }

        // دالة تحديد إشعار كمقروء
        function markAsRead(notificationId) {
            fetch('/admin/notifications/mark-as-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ notification_id: notificationId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // تحديث حالة الإشعار في القائمة بدلاً من إزالته
                    const notificationElement = document.querySelector(`[data-notification-id="${notificationId}"]`);
                    if (notificationElement) {
                        notificationElement.classList.add('read');
                        const checkButton = notificationElement.querySelector('.mark-read-btn');
                        if (checkButton) {
                            checkButton.style.display = 'none';
                        }
                    }
                    // تحديث العداد
                    fetchUnreadCount();
                }
            })
            .catch(error => console.error('خطأ في تحديد الإشعار كمقروء:', error));
        }

        // دالة تحديد جميع الإشعارات كمقروءة
        function markAllAsRead() {
            fetch('/admin/notifications/mark-all-as-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // تحديث حالة جميع الإشعارات في القائمة بدلاً من إزالتها
                    const notificationElements = document.querySelectorAll('[data-notification-id]');
                    notificationElements.forEach(element => {
                        element.classList.add('read');
                        const checkButton = element.querySelector('.mark-read-btn');
                        if (checkButton) {
                            checkButton.style.display = 'none';
                        }
                    });
                    // تحديث العداد
                    fetchUnreadCount();
                }
            })
            .catch(error => console.error('خطأ في تحديد جميع الإشعارات كمقروءة:', error));
        }

        // دالة تحديث عداد الإشعارات
        function updateNotificationCount() {
            fetch('/admin/notifications/unread-count')
                .then(response => response.json())
                .then(data => {
                    const badge = document.querySelector('.notification-badge');
                    if (badge) {
                        if (data.count > 0) {
                            badge.textContent = data.count;
                            badge.style.display = 'flex';
                        } else {
                            badge.style.display = 'none';
                        }
                    }
                })
                .catch(error => console.error('خطأ في تحديث عداد الإشعارات:', error));
        }

        // ===== MOBILE MENU FUNCTIONS =====
        
        // دالة فتح/إغلاق القائمة المتنقلة
        function toggleMobileMenu() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobileOverlay');
            const menuBtn = document.getElementById('mobileMenuBtn');
            
            if (sidebar.classList.contains('mobile-open')) {
                // إغلاق القائمة
                sidebar.classList.remove('mobile-open');
                overlay.classList.remove('show');
                menuBtn.innerHTML = '<i class="fas fa-bars"></i>';
            } else {
                // فتح القائمة
                sidebar.classList.add('mobile-open');
                overlay.classList.add('show');
                menuBtn.innerHTML = '<i class="fas fa-times"></i>';
            }
        }
        
        // إغلاق القائمة عند النقر على Overlay
        document.getElementById('mobileOverlay').addEventListener('click', function() {
            toggleMobileMenu();
        });
        
        // إغلاق القائمة عند تغيير حجم النافذة للشاشات الكبيرة
        let resizeTimeout;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(function() {
                if (window.innerWidth >= 768) {
                    const sidebar = document.getElementById('sidebar');
                    const overlay = document.getElementById('mobileOverlay');
                    const menuBtn = document.getElementById('mobileMenuBtn');
                    
                    sidebar.classList.remove('mobile-open');
                    overlay.classList.remove('show');
                    menuBtn.innerHTML = '<i class="fas fa-bars"></i>';
                }
            }, 250);
        });
        
        // ===== FINAL ENHANCEMENTS - PART 4 =====
        
        // تحسين الأداء - إضافة loading state
        function showSidebarLoading() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.add('loading');
        }
        
        function hideSidebarLoading() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.remove('loading');
        }
        
        // تحسين تجربة المستخدم - إغلاق القائمة عند النقر على أي رابط
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarLinks = document.querySelectorAll('.sidebar nav ul li a');
            sidebarLinks.forEach(function(link) {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 768) {
                        setTimeout(function() {
                            toggleMobileMenu();
                        }, 100);
                    }
                });
            });
        });
        
        // تحسين إمكانية الوصول - دعم التنقل بالكيبورد
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const sidebar = document.getElementById('sidebar');
                if (sidebar.classList.contains('mobile-open')) {
                    toggleMobileMenu();
                }
            }
        });
        
        // تحسين الأداء - إضافة intersection observer للـ sidebar
        if ('IntersectionObserver' in window) {
            const sidebar = document.getElementById('sidebar');
            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        entry.target.style.visibility = 'visible';
                    }
                });
            });
            
            observer.observe(sidebar);
        }
        
        // تحسين تجربة المستخدم - حفظ حالة القائمة في localStorage
        function saveSidebarState() {
            const sidebar = document.getElementById('sidebar');
            const isOpen = sidebar.classList.contains('mobile-open');
            localStorage.setItem('sidebarOpen', isOpen);
        }
        
        function loadSidebarState() {
            if (window.innerWidth < 768) {
                const savedState = localStorage.getItem('sidebarOpen');
                if (savedState === 'true') {
                    toggleMobileMenu();
                }
            }
        }
        
        // تحميل الحالة المحفوظة عند تحميل الصفحة
        document.addEventListener('DOMContentLoaded', loadSidebarState);
        
        // حفظ الحالة عند تغييرها
        const originalToggleMobileMenu = toggleMobileMenu;
        toggleMobileMenu = function() {
            originalToggleMobileMenu();
            saveSidebarState();
        };
        
        // التعامل مع الشعار
        document.addEventListener('DOMContentLoaded', function() {
            const logo = document.querySelector('img[alt="{{ config('brand.name_en') }} Logo"]');
            const logoFallback = document.getElementById('logoFallback');
            
            if (logo) {
                logo.addEventListener('error', function() {
                    this.style.display = 'none';
                    if (logoFallback) {
                        logoFallback.style.display = 'flex';
                    }
                });
                
                logo.addEventListener('load', function() {
                    if (logoFallback) {
                        logoFallback.style.display = 'none';
                    }
                });
            }
        });
    </script>

    @stack('scripts')

    <!-- Flash Messages -->
    @if (session('success'))
    <script>
        Swal.fire({
            title: '{{ __('web.messages.success_title') }}',
            text: "{{ session('success') }}",
            icon: 'success',
            confirmButtonText: '{{ __('web.actions.ok') }}'
        });
    </script>
    @endif

    @if (session('error'))
    <script>
        Swal.fire({
            title: '{{ __('web.messages.error_title') }}',
            text: "{{ session('error') }}",
            icon: 'error',
            confirmButtonText: '{{ __('web.actions.ok') }}'
        });
    </script>
    @endif
</body>
</html>
