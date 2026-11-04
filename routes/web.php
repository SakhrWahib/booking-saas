<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;


// توجيه الصفحة الرئيسية إلى الفرونت إند
Route::get('/', [App\Http\Controllers\frontend\FrontendController::class, 'index'])->name('home');

// مسارات المصادقة للإدارة
Route::get('/admin/login', [LoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [LoginController::class, 'login'])->name('admin.login.submit');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/admin/logout', [LoginController::class, 'logout'])->name('admin.logout');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// المسارات المحمية
Route::middleware(['auth'])->group(function () {
    // لوحة التحكم
    Route::get('/dashboard', [App\Http\Controllers\Admin\OverviewController::class, 'index'])->name('dashboard');
    Route::get('/admin/reports', [DashboardController::class, 'index'])->name('admin.reports.index');
    
    // مسارات برنامج الولاء (للمدير فقط)
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('/loyalty', [App\Http\Controllers\LoyaltyController::class, 'index'])->name('loyalty.index');
        Route::get('/loyalty/customer/{customer}', [App\Http\Controllers\LoyaltyController::class, 'getCustomerDetails'])->name('loyalty.customer.details');
        Route::post('/loyalty/create-coupon', [App\Http\Controllers\LoyaltyController::class, 'createCustomerCoupon'])->name('loyalty.create-coupon');
    });
    
   
    Route::post('/users/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::post('admin/{id}/toggle-status', [AdminController::class, 'toggleStatus'])->name('admin.toggle-status');

    // مسارات إدارة اللغات (للمدير فقط)
    Route::prefix('admin')->name('admin.')->middleware('permission:system.settings')->group(function () {
        Route::get('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
        Route::get('/languages', [App\Http\Controllers\Admin\LanguageController::class, 'index'])->name('languages.index');
        Route::post('/languages', [App\Http\Controllers\Admin\LanguageController::class, 'store'])->name('languages.store');
        Route::get('/languages/{language}', [App\Http\Controllers\Admin\LanguageController::class, 'show'])->name('languages.show');
        Route::put('/languages/{language}', [App\Http\Controllers\Admin\LanguageController::class, 'update'])->name('languages.update');
        Route::delete('/languages/{language}', [App\Http\Controllers\Admin\LanguageController::class, 'destroy'])->name('languages.destroy');
        Route::post('/languages/{language}/toggle-status', [App\Http\Controllers\Admin\LanguageController::class, 'toggleStatus'])->name('languages.toggle-status');
        
        // مسارات إدارة الترجمات
        Route::get('/languages/{language}/translations/{group?}', [App\Http\Controllers\Admin\LanguageController::class, 'getTranslations'])->name('languages.translations.get');
        Route::post('/languages/{language}/translations', [App\Http\Controllers\Admin\LanguageController::class, 'updateTranslations'])->name('languages.translations.update');
    });
    
    // مسارات إدارة المستخدمين (للمدير فقط)
    Route::prefix('admin')->name('admin.')->middleware('permission:users.view')->group(function () {
        Route::resource('users', App\Http\Controllers\Admin\UserController::class);
        Route::get('/users/search', [App\Http\Controllers\Admin\UserController::class, 'search'])->name('users.search');
        Route::post('/users/{id}/toggle-status', [App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::post('/users/{id}/reset-password', [App\Http\Controllers\Admin\UserController::class, 'resetPassword'])->name('users.reset-password');
    });
        
    // مسارات إدارة العملاء (للمدير ومسؤول العملاء)
    Route::prefix('admin')->name('admin.')->middleware('permission:customers.view')->group(function () {
        Route::resource('customers', App\Http\Controllers\Admin\CustomerController::class);
        Route::get('/customers/search', [App\Http\Controllers\Admin\CustomerController::class, 'search'])->name('customers.search');
        Route::get('/customers/export', [App\Http\Controllers\Admin\CustomerController::class, 'export'])->name('customers.export');
        Route::post('/customers/{customer}/generate-otp', [App\Http\Controllers\Admin\CustomerController::class, 'generateOtp'])->name('customers.generate-otp');
        Route::post('/customers/{customer}/verify-otp', [App\Http\Controllers\Admin\CustomerController::class, 'verifyOtp'])->name('customers.verify-otp');
        
        // العملاء عبر الجوال
        Route::get('/mobile-customers', [App\Http\Controllers\Admin\MobileCustomersController::class, 'index'])->name('mobile-customers.index');
        Route::get('/mobile-customers/{customer}', [App\Http\Controllers\Admin\MobileCustomersController::class, 'show'])->name('mobile-customers.show');
        Route::get('/mobile-customers/statistics', [App\Http\Controllers\Admin\MobileCustomersController::class, 'statistics'])->name('mobile-customers.statistics');
        Route::post('/mobile-customers/export', [App\Http\Controllers\Admin\MobileCustomersController::class, 'export'])->name('mobile-customers.export');
    });
    
    // مسارات إدارة مقدمي الخدمة (للمدير ومسؤول المنتجات)
    Route::prefix('admin')->name('admin.')->middleware('permission:service_providers.view')->group(function () {
        Route::get('/time-offs', [App\Http\Controllers\Admin\TimeOffController::class, 'index'])->name('time-offs.index');
        Route::post('/time-offs', [App\Http\Controllers\Admin\TimeOffController::class, 'store'])->middleware('permission:service_providers.edit')->name('time-offs.store');
        Route::delete('/time-offs/{timeOff}', [App\Http\Controllers\Admin\TimeOffController::class, 'destroy'])->middleware('permission:service_providers.edit')->name('time-offs.destroy');
        Route::resource('service-providers', App\Http\Controllers\Admin\ServiceProviderController::class);
        
        // مسارات إضافية لمقدمي الخدمة
        Route::post('/service-providers/{service_provider}/toggle-status', [App\Http\Controllers\Admin\ServiceProviderController::class, 'toggleStatus'])->name('service-providers.toggle-status');
        Route::get('/service-providers/{service_provider}/working-schedules', [App\Http\Controllers\Admin\ServiceProviderController::class, 'getWorkingSchedules'])->name('service-providers.working-schedules');
        Route::post('/service-providers/{service_provider}/coverage-area', [App\Http\Controllers\Admin\ServiceProviderController::class, 'updateCoverageArea'])->name('service-providers.coverage-area');
        Route::post('/service-providers/{service_provider}/check-coverage', [App\Http\Controllers\Admin\ServiceProviderController::class, 'checkCoverage'])->name('service-providers.check-coverage');
        Route::get('/service-providers/{service_provider}/available-slots', [App\Http\Controllers\Admin\ServiceProviderController::class, 'getAvailableTimeSlots'])->name('service-providers.available-slots');
    });
    
    // مسارات إدارة جداول العمل (للمدير ومسؤول المنتجات)
    Route::prefix('admin')->name('admin.')->middleware('permission:working_schedules.view')->group(function () {
        Route::resource('working-schedules', App\Http\Controllers\Admin\WorkingScheduleController::class);
        
        // مسارات إضافية لجداول العمل
        Route::post('/working-schedules/{working_schedule}/toggle-status', [App\Http\Controllers\Admin\WorkingScheduleController::class, 'toggleStatus'])->name('working-schedules.toggle-status');
        Route::post('/working-schedules/{working_schedule}/set-default', [App\Http\Controllers\Admin\WorkingScheduleController::class, 'setDefault'])->name('working-schedules.set-default');
        Route::post('/working-schedules/{working_schedule}/duplicate', [App\Http\Controllers\Admin\WorkingScheduleController::class, 'duplicate'])->name('working-schedules.duplicate');
        Route::get('/working-schedules/api/active', [App\Http\Controllers\Admin\WorkingScheduleController::class, 'getActive'])->name('working-schedules.api.active');
        Route::post('/working-schedules/{working_schedule}/check-availability', [App\Http\Controllers\Admin\WorkingScheduleController::class, 'checkAvailability'])->name('working-schedules.check-availability');
        
        // مسارات إدارة الأوقات المتاحة
        Route::get('/working-schedules/{working_schedule}/time-slots', [App\Http\Controllers\Admin\WorkingScheduleController::class, 'manageTimeSlots'])->name('working-schedules.manage-time-slots');
        Route::post('/working-schedules/{working_schedule}/time-slots', [App\Http\Controllers\Admin\WorkingScheduleController::class, 'updateTimeSlots'])->name('working-schedules.update-time-slots');
        Route::post('/working-schedules/{working_schedule}/time-slots/add', [App\Http\Controllers\Admin\WorkingScheduleController::class, 'addTimeSlot'])->name('working-schedules.add-time-slot');
        Route::delete('/working-schedules/{working_schedule}/time-slots/{timeSlot}', [App\Http\Controllers\Admin\WorkingScheduleController::class, 'deleteTimeSlot'])->name('working-schedules.delete-time-slot');
        Route::post('/working-schedules/{working_schedule}/time-slots/reorder', [App\Http\Controllers\Admin\WorkingScheduleController::class, 'updateTimeSlotsOrder'])->name('working-schedules.reorder-time-slots');
    });
    
    // مسارات إدارة المنتجات (للمدير ومسؤول المنتجات)
    Route::prefix('admin')->name('admin.')->middleware('permission:products.view')->group(function () {
        Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
        
        // مسارات إضافية للمنتجات
        Route::post('/products/{product}/toggle-status', [App\Http\Controllers\Admin\ProductController::class, 'toggleStatus'])->name('products.toggle-status');
        Route::delete('/products/{product}/delete-image', [App\Http\Controllers\Admin\ProductController::class, 'deleteImage'])->name('products.delete-image');
    });
        
    // مسارات إدارة الحجوزات (للمدير ومسؤول العملاء)
    Route::prefix('admin')->name('admin.')->middleware('permission:bookings.view')->group(function () {
        // مسارات التقويم والعرض اليومي (يجب أن تكون قبل resource routes)
        Route::get('/bookings/calendar', [App\Http\Controllers\Admin\BookingController::class, 'calendar'])->name('bookings.calendar');
        Route::get('/bookings/daily', [App\Http\Controllers\Admin\BookingController::class, 'dailyBookings'])->name('bookings.daily');
        
        // مسارات API للحجوزات
            Route::get('/bookings/api/available-time-slots', [App\Http\Controllers\Admin\BookingController::class, 'getAvailableTimeSlots'])->name('bookings.api.available-time-slots');
    Route::get('/bookings/api/product-add-ons', [App\Http\Controllers\Admin\BookingController::class, 'getProductAddOns'])->name('bookings.api.product-add-ons');
    Route::post('/bookings/api/check-coverage', [App\Http\Controllers\Admin\BookingController::class, 'checkCoverageArea'])->name('bookings.api.check-coverage');
    Route::post('/bookings/api/upload-attachment', [App\Http\Controllers\Admin\BookingController::class, 'uploadAttachment'])->name('bookings.api.upload-attachment');
    Route::post('/bookings/validate-coupon', [App\Http\Controllers\Admin\BookingController::class, 'validateCoupon'])->name('bookings.validate-coupon');
        
        // مسارات CRUD للحجوزات
        Route::resource('bookings', App\Http\Controllers\Admin\BookingController::class);
        
        // مسارات إضافية للحجوزات
        Route::post('/bookings/{booking}/confirm', [App\Http\Controllers\Admin\BookingController::class, 'confirm'])->name('bookings.confirm');
        Route::post('/bookings/{booking}/complete', [App\Http\Controllers\Admin\BookingController::class, 'complete'])->name('bookings.complete');
        Route::post('/bookings/{booking}/cancel', [App\Http\Controllers\Admin\BookingController::class, 'cancel'])->name('bookings.cancel');
    });
    
    // مسارات إدارة الفواتير (للمدير ومسؤول العملاء)
    Route::prefix('admin')->name('admin.')->middleware('permission:invoices.view,invoices.create,invoices.edit,invoices.delete')->group(function () {
        Route::resource('invoices', App\Http\Controllers\Admin\InvoiceController::class);
        
        // مسارات إضافية للفواتير
        Route::post('/invoices/{invoice}/record-payment', [App\Http\Controllers\Admin\InvoiceController::class, 'recordPayment'])->name('invoices.record-payment');
        Route::post('/invoices/{invoice}/apply-discount', [App\Http\Controllers\Admin\InvoiceController::class, 'applyDiscount'])->name('invoices.apply-discount');
        Route::post('/invoices/{invoice}/update-status', [App\Http\Controllers\Admin\InvoiceController::class, 'updateStatus'])->name('invoices.update-status');
        Route::get('/invoices/{invoice}/print', [App\Http\Controllers\Admin\InvoiceController::class, 'print'])->name('invoices.print');
            Route::get('/invoices/create-from-booking/{booking}', [App\Http\Controllers\Admin\InvoiceController::class, 'createFromBooking'])->name('invoices.create-from-booking');
    Route::get('/invoices/{invoice}/update-from-booking', [App\Http\Controllers\Admin\InvoiceController::class, 'updateFromBooking'])->name('invoices.update-from-booking');
    Route::get('/invoices/statistics', [App\Http\Controllers\Admin\InvoiceController::class, 'statistics'])->name('invoices.statistics');
    Route::get('/invoices/export', [App\Http\Controllers\Admin\InvoiceController::class, 'export'])->name('invoices.export');
    });
    
    // مسارات الإشعارات (للمدير فقط)
    Route::prefix('admin')->name('admin.')->middleware('permission:system.notifications')->group(function () {
        Route::get('/notifications', [App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
        Route::get('/notifications/unread', [App\Http\Controllers\Admin\NotificationController::class, 'getUnread'])->name('notifications.unread');
        Route::get('/notifications/dropdown', [App\Http\Controllers\Admin\NotificationController::class, 'getAllForDropdown'])->name('notifications.dropdown');
        Route::get('/notifications/unread-count', [App\Http\Controllers\Admin\NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
        Route::post('/notifications/mark-as-read', [App\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
        Route::post('/notifications/mark-all-as-read', [App\Http\Controllers\Admin\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');
        Route::get('/notifications/{id}', [App\Http\Controllers\Admin\NotificationController::class, 'show'])->name('notifications.show');
    });
    
    // مسارات إدارة السلات المتروكة (للمدير ومسؤول العملاء)
    Route::prefix('admin')->name('admin.')->middleware('permission:customers.view')->group(function () {
        Route::resource('abandoned-carts', App\Http\Controllers\Admin\AbandonedCartController::class);
        
        // مسارات إضافية للسلات المتروكة
        Route::post('/abandoned-carts/{id}/mark-as-contacted', [App\Http\Controllers\Admin\AbandonedCartController::class, 'markAsContacted'])->name('abandoned-carts.mark-as-contacted');
        Route::post('/abandoned-carts/bulk-mark-as-contacted', [App\Http\Controllers\Admin\AbandonedCartController::class, 'bulkMarkAsContacted'])->name('abandoned-carts.bulk-mark-as-contacted');
        Route::post('/abandoned-carts/bulk-destroy', [App\Http\Controllers\Admin\AbandonedCartController::class, 'bulkDestroy'])->name('abandoned-carts.bulk-destroy');
        Route::get('/abandoned-carts/export', [App\Http\Controllers\Admin\AbandonedCartController::class, 'export'])->name('abandoned-carts.export');
    });

    // مسارات إدارة الكوبونات (للمدير)
    Route::prefix('admin')->name('admin.')->middleware('permission:coupons.view')->group(function () {
        Route::resource('coupons', App\Http\Controllers\Admin\CouponController::class);
        
        // مسارات إضافية للكوبونات
        Route::post('/coupons/{coupon}/toggle-status', [App\Http\Controllers\Admin\CouponController::class, 'toggleStatus'])->name('coupons.toggle-status');
        Route::get('/coupons/generate-code', [App\Http\Controllers\Admin\CouponController::class, 'generateCode'])->name('coupons.generate-code');
        Route::post('/coupons/validate', [App\Http\Controllers\Admin\CouponController::class, 'validateCoupon'])->name('coupons.validate');
        Route::get('/coupons/statistics', [App\Http\Controllers\Admin\CouponController::class, 'statistics'])->name('coupons.statistics');
    });
});

// تغيير اللغة
Route::get('language/{locale}', [LanguageController::class, 'switchLang'])->name('switch.language');

// مسارات الفرونت إند
Route::get('/products', [App\Http\Controllers\frontend\FrontendController::class, 'products'])->name('products');

Route::prefix('frontend')->name('frontend.')->group(function () {
    // تم إزالة المسار المكرر للصفحة الرئيسية لتجنب التضارب
    // Route::get('/', [App\Http\Controllers\frontend\FrontendController::class, 'index'])->name('index');
    Route::get('/products', [App\Http\Controllers\frontend\FrontendController::class, 'products'])->name('products');
    Route::get('/product/{id}', [App\Http\Controllers\frontend\FrontendController::class, 'productDetails'])->name('product.details');
    
    // مسارات الحجز
    Route::prefix('booking')->name('booking.')->group(function () {
        Route::post('/start', [App\Http\Controllers\frontend\FrontendController::class, 'startBooking'])->name('start');
        
        // مسارات الإضافات
        Route::get('/select-addons', [App\Http\Controllers\frontend\FrontendController::class, 'selectAddOns'])->name('select-addons');
        Route::post('/save-addons', [App\Http\Controllers\frontend\FrontendController::class, 'saveAddOns'])->name('save-addons');
        Route::get('/api/product-addons', [App\Http\Controllers\frontend\FrontendController::class, 'getProductAddOns'])->name('api.product-addons');
        
        Route::get('/select-location', [App\Http\Controllers\frontend\FrontendController::class, 'selectLocation'])->name('select-location');
        Route::post('/check-coverage', [App\Http\Controllers\frontend\FrontendController::class, 'checkCoverage'])->name('check-coverage');
        
        Route::get('/select-datetime', [App\Http\Controllers\frontend\FrontendController::class, 'selectDateTime'])->name('select-datetime');
        Route::get('/available-slots', [App\Http\Controllers\frontend\FrontendController::class, 'getAvailableSlots'])->name('available-slots');
        Route::post('/save-datetime', [App\Http\Controllers\frontend\FrontendController::class, 'saveDateTime'])->name('save-datetime');
        
        Route::get('/customer-info', [App\Http\Controllers\frontend\FrontendController::class, 'customerInfo'])->name('customer-info');
        Route::post('/save-customer-info', [App\Http\Controllers\frontend\FrontendController::class, 'saveCustomerInfo'])->name('save-customer-info');
        
        // Customer OTP verification for booking
        Route::get('/verify-customer-otp', [App\Http\Controllers\frontend\FrontendController::class, 'verifyCustomerOtp'])->name('verify-customer-otp');
        Route::post('/confirm-customer-otp', [App\Http\Controllers\frontend\FrontendController::class, 'confirmCustomerOtp'])->name('confirm-customer-otp');
        Route::post('/resend-customer-otp', [App\Http\Controllers\frontend\FrontendController::class, 'resendCustomerOtp'])->name('resend-customer-otp');
        
        Route::get('/payment-details', [App\Http\Controllers\frontend\FrontendController::class, 'paymentDetails'])->name('payment-details');
        Route::post('/confirm-later', [App\Http\Controllers\frontend\FrontendController::class, 'confirmBookingLater'])->name('confirm-later');
        
        Route::get('/bank-transfer', [App\Http\Controllers\frontend\FrontendController::class, 'bankTransfer'])->name('bank-transfer');
        Route::post('/upload-receipt', [App\Http\Controllers\frontend\FrontendController::class, 'uploadTransferReceipt'])->name('upload-receipt');
        
        Route::get('/success/{id}', [App\Http\Controllers\frontend\FrontendController::class, 'bookingSuccess'])->name('success');
        Route::get('/details/{id}', [App\Http\Controllers\frontend\FrontendController::class, 'bookingDetails'])->name('details');
        Route::post('/cancel/{id}', [App\Http\Controllers\frontend\FrontendController::class, 'cancelBooking'])->name('cancel');
    });
    
        // راوت إضافة السلة المتروكة
    Route::post('/add-to-cart', [App\Http\Controllers\frontend\FrontendController::class, 'addToCart'])->name('add-to-cart');
    
    // Coupon routes
    Route::post('/validate-coupon', [App\Http\Controllers\frontend\CouponController::class, 'validateCoupon'])->name('validate-coupon');
    
    // راوت اختبار للتأكد من عمل النظام
    Route::get('/test-booking', function() {
        return response()->json([
            'success' => true,
            'message' => 'النظام يعمل بشكل طبيعي',
            'session' => session()->all()
        ]);
    })->name('test-booking');
    
    // راوت اختبار للحجز
    Route::get('/test-start-booking/{product_id}', function($product_id) {
        try {
            // حفظ معرف المنتج في الجلسة
            session(['booking.product_id' => $product_id]);
            
            return response()->json([
                'success' => true,
                'message' => 'تم حفظ المنتج في الجلسة',
                'product_id' => $product_id,
                'session' => session()->all()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ: ' . $e->getMessage()
            ], 500);
        }
    })->name('test-start-booking');
    
    // مسارات العميل
    Route::prefix('customer')->name('customer.')->group(function () {
        Route::get('/login', [App\Http\Controllers\frontend\CustomerController::class, 'login'])->name('login');
        Route::post('/login', [App\Http\Controllers\frontend\CustomerController::class, 'doLogin'])->name('do-login');
        
        // OTP verification routes
        Route::get('/verify-otp', [App\Http\Controllers\frontend\CustomerController::class, 'verifyOtp'])->name('verify-otp');
        Route::post('/confirm-otp', [App\Http\Controllers\frontend\CustomerController::class, 'confirmOtp'])->name('confirm-otp');
        Route::post('/resend-otp', [App\Http\Controllers\frontend\CustomerController::class, 'resendOtp'])->name('resend-otp');
        
        Route::get('/profile', [App\Http\Controllers\frontend\CustomerController::class, 'profile'])->name('profile');
        Route::post('/profile/update', [App\Http\Controllers\frontend\CustomerController::class, 'updateProfile'])->name('update-profile');
        Route::get('/logout', [App\Http\Controllers\frontend\CustomerController::class, 'logout'])->name('logout');
    });
});


// لوحة مالك المنصة
Route::middleware(['auth', 'super_admin'])->prefix('platform')->name('platform.')->group(function () {
    $c = App\Http\Controllers\Platform\PlatformController::class;
    Route::get('/', [$c, 'overview'])->name('overview');
    Route::get('/tenants', [$c, 'tenants'])->name('tenants.index');
    Route::get('/tenants/create', [$c, 'createTenant'])->name('tenants.create');
    Route::post('/tenants', [$c, 'storeTenant'])->name('tenants.store');
    Route::get('/tenants/{tenant}/edit', [$c, 'editTenant'])->name('tenants.edit');
    Route::put('/tenants/{tenant}', [$c, 'updateTenant'])->name('tenants.update');
    Route::get('/plans', [$c, 'plans'])->name('plans.index');
    Route::post('/plans', [$c, 'savePlan'])->name('plans.store');
    Route::put('/plans/{plan}', [$c, 'savePlan'])->name('plans.update');
});

// راوتات البوت الذكي
Route::get('/chatbot', [App\Http\Controllers\ChatBotController::class, 'index'])->name('chatbot.index');
Route::post('/chatbot/process-message', [App\Http\Controllers\ChatBotController::class, 'processMessage'])->name('chatbot.process-message');







