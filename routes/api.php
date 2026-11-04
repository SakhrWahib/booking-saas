<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\WorkingScheduleController as ApiWorkingScheduleController;
use App\Http\Controllers\Api\ServiceProviderController;
use App\Http\Controllers\Api\TimeSlotController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\CouponController as ApiCouponController;
use App\Http\Controllers\Api\AbandonedCartController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route for authenticated user
Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| Coupons API Routes (Validate only)
|--------------------------------------------------------------------------
*/
Route::prefix('coupons')->group(function () {
    // GET للتحقق عبر بارامترات الاستعلام
    Route::get('/validate', [ApiCouponController::class, 'validateCoupon']);
    Route::post('/validate', [ApiCouponController::class, 'validateCoupon']);
});

/*
|--------------------------------------------------------------------------
| Customer API Routes
|--------------------------------------------------------------------------
*/

// Customer CRUD operations
Route::prefix('customers')->group(function () {
    // Get all customers with search and filtering
    Route::get('/', [CustomerController::class, 'index']);
    
    // Get specific customer
    Route::get('/{id}', [CustomerController::class, 'show']);
    
    // Create new customer
    Route::post('/', [CustomerController::class, 'store']);
    
    // Update customer
    Route::put('/{id}', [CustomerController::class, 'update']);
    Route::patch('/{id}', [CustomerController::class, 'update']);
    
    // Delete customer
    Route::delete('/{id}', [CustomerController::class, 'destroy']);
    
    // OTP operations
    Route::post('/send-otp', [CustomerController::class, 'sendOtp']);
    Route::post('/verify-otp', [CustomerController::class, 'verifyOtp']);
    
    // Location-based search
    Route::post('/search-by-location', [CustomerController::class, 'searchByLocation']);
    
    // Mobile login tracking
    Route::post('/record-mobile-login', [CustomerController::class, 'recordMobileLogin']);
    Route::post('/store-with-mobile-login', [CustomerController::class, 'storeWithMobileLogin']);
});

/*
|--------------------------------------------------------------------------
| Product API Routes
|--------------------------------------------------------------------------
*/

// Product CRUD operations
Route::prefix('products')->group(function () {
    // Get all products with search and filtering
    Route::get('/', [ProductController::class, 'index']);
    
    // Get specific product
    Route::get('/{id}', [ProductController::class, 'show']);
    
    // Create new product
    Route::post('/', [ProductController::class, 'store']);
    
    // Update product
    Route::put('/{id}', [ProductController::class, 'update']);
    Route::patch('/{id}', [ProductController::class, 'update']);
    
    // Delete product
    Route::delete('/{id}', [ProductController::class, 'destroy']);
    
    // Search operations
    Route::post('/search-by-price', [ProductController::class, 'searchByPrice']);
    Route::post('/search-by-service-provider', [ProductController::class, 'searchByServiceProvider']);
    
    // Status operations
    Route::patch('/{id}/toggle-status', [ProductController::class, 'toggleStatus']);
    
    // Active products only
    Route::get('/active/list', [ProductController::class, 'activeProducts']);
});

/*
|--------------------------------------------------------------------------
| Working Schedules API Routes
|--------------------------------------------------------------------------
*/
Route::prefix('working-schedules')->group(function () {
    // List with filters
    Route::get('/', [ApiWorkingScheduleController::class, 'index']);

    // Show one
    Route::get('/{id}', [ApiWorkingScheduleController::class, 'show']);

    // Create
    Route::post('/', [ApiWorkingScheduleController::class, 'store']);

    // Update
    Route::put('/{id}', [ApiWorkingScheduleController::class, 'update']);
    Route::patch('/{id}', [ApiWorkingScheduleController::class, 'update']);

    // Delete
    Route::delete('/{id}', [ApiWorkingScheduleController::class, 'destroy']);

    // Toggle active status
    Route::patch('/{id}/toggle-status', [ApiWorkingScheduleController::class, 'toggleStatus']);

    // Set default
    Route::post('/{id}/set-default', [ApiWorkingScheduleController::class, 'setDefault']);

    // Active list for a date
    Route::get('/active/list', [ApiWorkingScheduleController::class, 'activeList']);

    // Check availability for a specific date
    Route::post('/{id}/check-availability', [ApiWorkingScheduleController::class, 'checkAvailability']);
});

/*
|--------------------------------------------------------------------------
| Service Providers API Routes (Read-only)
|--------------------------------------------------------------------------
*/
Route::prefix('service-providers')->group(function () {
    // قائمة مع فلاتر بسيطة
    Route::get('/', [ServiceProviderController::class, 'index']);

    // تفاصيل مقدم خدمة
    Route::get('/{id}', [ServiceProviderController::class, 'show']);

    // المقدمون النشطون فقط (حقول مختصرة)
    Route::get('/active/list', [ServiceProviderController::class, 'activeList']);

    // بحث بالموقع
    Route::post('/search-by-location', [ServiceProviderController::class, 'searchByLocation']);
});

/*
|--------------------------------------------------------------------------
| Time Slots API Routes (Read-only)
|--------------------------------------------------------------------------
*/
Route::prefix('time-slots')->group(function () {
    // قائمة عامة مع فلاتر
    Route::get('/', [TimeSlotController::class, 'index']);

    // تفاصيل وقت واحد
    Route::get('/{id}', [TimeSlotController::class, 'show']);

    // أوقات جدول معين
    Route::get('/by-schedule/{working_schedule_id}', [TimeSlotController::class, 'listBySchedule']);

    // الأوقات النشطة فقط لجدول معين
    Route::get('/by-schedule/{working_schedule_id}/active', [TimeSlotController::class, 'activeBySchedule']);
});

/*
|--------------------------------------------------------------------------
| Bookings API Routes
|--------------------------------------------------------------------------
*/
Route::prefix('bookings')->group(function () {
    // قراءة مع فلاتر
    Route::get('/', [BookingController::class, 'index']);

    // إنشاء حجز
    Route::post('/', [BookingController::class, 'store']);

    // تفاصيل
    Route::get('/{id}', [BookingController::class, 'show']);

    // إلغاء
    Route::patch('/{id}/cancel', [BookingController::class, 'cancel']);

    // الأوقات المتاحة فعلياً
    Route::post('/available-time-slots', [BookingController::class, 'availableTimeSlots']);
});

/*
|--------------------------------------------------------------------------
| Abandoned Carts API Routes
|--------------------------------------------------------------------------
*/
Route::prefix('abandoned-carts')->group(function () {
    // قائمة السلات المتروكة مع فلاتر
    Route::get('/', [AbandonedCartController::class, 'index']);
    
    // إنشاء سلة متروكة جديدة
    Route::post('/', [AbandonedCartController::class, 'store']);
    
    // عرض سلة متروكة محددة
    Route::get('/{id}', [AbandonedCartController::class, 'show']);
    
    // تحديث بيانات السلة المتروكة
    Route::put('/{id}', [AbandonedCartController::class, 'update']);
    Route::patch('/{id}', [AbandonedCartController::class, 'update']);
    
    // حذف سلة متروكة
    Route::delete('/{id}', [AbandonedCartController::class, 'destroy']);
    
    // تحديث حالة التواصل
    Route::patch('/{id}/mark-contacted', [AbandonedCartController::class, 'markAsContacted']);
    
    // إحصائيات السلات المتروكة
    Route::get('/statistics/overview', [AbandonedCartController::class, 'statistics']);
    
    // السلات التي لم يتم التواصل معها
    Route::get('/not-contacted/list', [AbandonedCartController::class, 'notContacted']);
});

