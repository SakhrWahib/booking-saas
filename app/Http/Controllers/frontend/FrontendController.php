<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ServiceProvider;
use App\Models\Customer;
use App\Models\Booking;
use App\Models\TimeSlot;
use App\Models\WorkingSchedule;
use App\Models\AbandonedCart;
use App\Services\SmsService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FrontendController extends Controller
{
    /**
     * عرض الصفحة الرئيسية مع المنتجات
     */
    public function index()
    {
        // جلب المنتجات النشطة - محدودة لتحسين السرعة
        $products = Product::active()->limit(6)->get();

        return view('frontend.index', compact('products'));
    }

    /**
     * عرض صفحة المنتجات
     */
    public function products()
    {
        $products = Product::active()->paginate(12);

        return view('frontend.products', compact('products'));
    }

    /**
     * عرض تفاصيل منتج معين
     */
    public function productDetails($id)
    {
        $product = Product::active()->findOrFail($id);

        return view('frontend.product-details', compact('product'));
    }

    /**
     * بدء عملية الحجز
     */
    public function startBooking(Request $request)
    {
        try {
            \Log::info('Frontend startBooking: بدء عملية الحجز', [
                'request_data' => $request->all(),
                'session' => session()->all()
            ]);

            $request->validate([
                'product_id' => 'required|exists:products,id'
            ]);

            // إزالة المنتج من السلات المتروكة إذا كان موجوداً
            $productId = $request->product_id;
            $abandonedCart = AbandonedCart::where('product_id', $productId)
                ->where('is_contacted', false)
                ->first();
            
            if ($abandonedCart) {
                $abandonedCart->update(['is_contacted' => true]);
                \Log::info('Frontend startBooking: تم تحديث السلة المتروكة', [
                    'abandoned_cart_id' => $abandonedCart->id,
                    'product_id' => $productId
                ]);
            }

            // حفظ معرف المنتج في الجلسة أولاً
            session([
                'booking.product_id' => $request->product_id
            ]);

            \Log::info('Frontend startBooking: تم حفظ البيانات في الجلسة', [
                'product_id' => $request->product_id,
                'session_booking' => session('booking')
            ]);

            // توجيه المستخدم لصفحة اختيار الإضافات
            return redirect()->route('frontend.booking.select-addons');

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Frontend startBooking: خطأ في التحقق من البيانات', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            
            return redirect()->back()->withErrors($e)->withInput();
        } catch (\Exception $e) {
            \Log::error('Frontend startBooking: خطأ في بدء الحجز', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request_data' => $request->all(),
                'stack_trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'حدث خطأ أثناء بدء الحجز: ' . $e->getMessage());
        }
    }

    /**
     * عرض صفحة اختيار الإضافات للمنتج
     */
    public function selectAddOns(Request $request)
    {
        try {
            \Log::info('Frontend selectAddOns: عرض صفحة اختيار الإضافات', [
                'request_data' => $request->all(),
                'session_booking' => session('booking'),
                'full_session' => session()->all()
            ]);

            // التحقق من وجود product_id في الطلب أو الجلسة
            $productId = $request->product_id ?? session('booking.product_id');
            
            if (!$productId) {
                \Log::error('Frontend selectAddOns: لا يوجد product_id', [
                    'request_product_id' => $request->product_id,
                    'session_product_id' => session('booking.product_id'),
                    'full_session' => session()->all()
                ]);
                return redirect()->route('home')->with('error', 'يرجى اختيار منتج أولاً');
            }

            // جلب المنتج مع التحقق من كونه نشط
            $product = Product::active()->findOrFail($productId);

            // اختبار الوصول للإضافات للتأكد من عدم وجود أخطاء
            try {
                $addOnsCount = $product->add_ons_count;
                $hasAddOns = $product->hasAddOns();
                \Log::info('Frontend selectAddOns: اختبار الإضافات', [
                    'add_ons_count' => $addOnsCount,
                    'has_add_ons' => $hasAddOns
                ]);
            } catch (\Exception $addOnsError) {
                \Log::error('Frontend selectAddOns: خطأ في الوصول للإضافات', [
                    'error' => $addOnsError->getMessage(),
                    'product_id' => $product->id
                ]);
                // نستمر حتى لو كان هناك خطأ في الإضافات
            }

            // حفظ معرف المنتج في الجلسة (تحديث دائم للتأكد من صحة البيانات)
            session([
                'booking.product_id' => $product->id,
                'booking.product_name' => $product->name,
                'booking.base_price' => $product->price
            ]);

            \Log::info('Frontend selectAddOns: تم تحميل المنتج بنجاح', [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'session_booking' => session('booking')
            ]);

            return view('frontend.booking.select-addons', compact('product'));

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('Frontend selectAddOns: المنتج غير موجود', [
                'product_id' => $request->product_id ?? session('booking.product_id'),
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('home')->with('error', 'المنتج المطلوب غير موجود');
        } catch (\Exception $e) {
            \Log::error('Frontend selectAddOns: خطأ في تحميل صفحة الإضافات', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'product_id' => $request->product_id ?? session('booking.product_id'),
                'stack_trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('home')->with('error', 'حدث خطأ أثناء تحميل الصفحة: ' . $e->getMessage());
        }
    }

    /**
     * حفظ الإضافات المختارة والانتقال لصفحة اختيار الموقع
     */
    public function saveAddOns(Request $request)
    {
        try {
            \Log::info('Frontend saveAddOns: بداية حفظ الإضافات', [
                'request_data' => $request->all(),
                'session_booking' => session('booking')
            ]);

            // التحقق من وجود معرف المنتج في الجلسة
            if (!session('booking.product_id')) {
                return redirect()->route('home')->with('error', 'يرجى اختيار منتج أولاً');
            }

            // التحقق من صحة الإضافات المختارة
            if ($request->has('selected_add_ons') && is_array($request->selected_add_ons)) {
                $validator = Validator::make($request->all(), [
                    'selected_add_ons' => 'array',
                    'selected_add_ons.*.option' => 'required|string',
                    'selected_add_ons.*.name' => 'required|string',
                    'selected_add_ons.*.price' => 'required|numeric|min:0',
                    'selected_add_ons.*.image' => 'nullable|string'
                ]);

                if ($validator->fails()) {
                    \Log::error('Frontend saveAddOns: فشل في التحقق من البيانات', [
                        'errors' => $validator->errors()->toArray()
                    ]);
                    return back()->withErrors($validator)->withInput();
                }

                // التحقق من صحة الإضافات مقابل المنتج
                $product = Product::find(session('booking.product_id'));
                if (!$product) {
                    return redirect()->route('home')->with('error', 'المنتج غير موجود');
                }

                $productAddOns = $product->formatted_add_ons;
                $validAddOns = [];
                $addOnsTotal = 0;

                foreach ($request->selected_add_ons as $selectedAddOn) {
                    // التحقق من أن الإضافة موجودة في قائمة إضافات المنتج
                    $addOnExists = false;
                    foreach ($productAddOns as $productAddOn) {
                        if ($productAddOn['option'] === $selectedAddOn['option'] && 
                            $productAddOn['name'] === $selectedAddOn['name'] &&
                            $productAddOn['price'] == $selectedAddOn['price']) {
                            $addOnExists = true;
                            break;
                        }
                    }

                    if ($addOnExists) {
                        $validAddOns[] = $selectedAddOn;
                        $addOnsTotal += floatval($selectedAddOn['price']);
                    }
                }

                // حفظ الإضافات الصحيحة في الجلسة
                session([
                    'booking.selected_add_ons' => $validAddOns,
                    'booking.add_ons_total' => $addOnsTotal
                ]);

                \Log::info('Frontend saveAddOns: تم حفظ الإضافات بنجاح', [
                    'valid_add_ons_count' => count($validAddOns),
                    'add_ons_total' => $addOnsTotal,
                    'session_booking' => session('booking')
                ]);
            } else {
                // لا توجد إضافات مختارة
                session([
                    'booking.selected_add_ons' => [],
                    'booking.add_ons_total' => 0
                ]);

                \Log::info('Frontend saveAddOns: لا توجد إضافات مختارة', [
                    'session_booking' => session('booking')
                ]);
            }

            // معالجة الكوبون إذا كان موجوداً
            if ($request->filled('coupon_code')) {
                $couponCode = $request->coupon_code;
                $coupon = \App\Models\Coupon::where('code', $couponCode)->first();
                
                if ($coupon && $coupon->isValid()) {
                    // حساب السعر الإجمالي للمقارنة مع الحد الأدنى للكوبون
                    $product = Product::find(session('booking.product_id'));
                    $addOnsTotal = session('booking.add_ons_total', 0);
                    $totalAmount = $product->price + $addOnsTotal;
                    
                    if ($coupon->isValidForAmount($totalAmount)) {
                        $discountAmount = $coupon->calculateDiscount($totalAmount);
                        
                        session([
                            'booking.coupon_code' => $couponCode,
                            'booking.coupon_id' => $coupon->id,
                            'booking.discount_amount' => $discountAmount
                        ]);
                        
                        \Log::info('Frontend saveAddOns: تم تطبيق الكوبون بنجاح', [
                            'coupon_code' => $couponCode,
                            'coupon_id' => $coupon->id,
                            'discount_amount' => $discountAmount,
                            'total_amount' => $totalAmount
                        ]);
                    } else {
                        \Log::warning('Frontend saveAddOns: الكوبون غير صالح للمبلغ', [
                            'coupon_code' => $couponCode,
                            'total_amount' => $totalAmount,
                            'min_amount' => $coupon->min_amount
                        ]);
                    }
                } else {
                    \Log::warning('Frontend saveAddOns: الكوبون غير موجود أو غير صالح', [
                        'coupon_code' => $couponCode
                    ]);
                }
            }

            return redirect()->route('frontend.booking.select-location');

        } catch (\Exception $e) {
            \Log::error('Frontend saveAddOns: خطأ في حفظ الإضافات', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);
            
            return back()->with('error', 'حدث خطأ أثناء حفظ الإضافات: ' . $e->getMessage());
        }
    }





    /**
     * عرض صفحة اختيار الموقع
     */
    public function selectLocation()
    {
        \Log::info('Frontend selectLocation: عرض صفحة اختيار الموقع', [
            'session_booking' => session('booking')
        ]);

        // التحقق من وجود بيانات المنتج في الجلسة
        if (!session('booking.product_id')) {
            return redirect()->route('home')->with('error', 'يرجى اختيار منتج أولاً');
        }

        return view('frontend.booking.select-location');
    }

    /**
     * التحقق من نطاق التغطية وحفظ الموقع
     */
    public function checkCoverage(Request $request)
    {
        try {
            $latitude = $request->input('latitude');
            $longitude = $request->input('longitude');
            
            if (!$latitude || !$longitude) {
            return response()->json([
                    'covered' => false, 
                    'message' => 'لم يتم تحديد الموقع بشكل صحيح'
                ]);
            }

            // الحصول على جميع مقدمي الخدمة النشطين
            $serviceProviders = ServiceProvider::where('is_active', true)->get();

            if ($serviceProviders->isEmpty()) {
                return response()->json([
                    'covered' => false,
                    'message' => 'لا توجد خدمات متاحة حالياً'
                ]);
            }

            $coveredProviders = [];
        
            // التحقق من تغطية كل مقدم خدمة للموقع المطلوب
            foreach ($serviceProviders as $provider) {
                if ($provider->coversLocation($latitude, $longitude)) {
                    $coveredProviders[] = [
                        'id' => $provider->id,
                        'name' => $provider->name,
                        'coverage_type' => $provider->coverage_type
                    ];
                }
            }

            if (!empty($coveredProviders)) {
                // حفظ البيانات في الجلسة للخطوات التالية
                session([
                    'booking.latitude' => $latitude,
                    'booking.longitude' => $longitude,
                    'booking.address' => 'الموقع المحدد على الخريطة',
                    'booking.available_providers' => collect($coveredProviders)->pluck('id')->toArray()
                ]);

                // تحديث موقع العميل في قاعدة البيانات إذا كان مسجل الدخول
                if (session('customer_id')) {
                    $customer = Customer::find(session('customer_id'));
                    if ($customer) {
                        $customer->update([
                            'latitude' => $latitude,
                            'longitude' => $longitude,
                            'address' => 'الموقع المحدد على الخريطة'
                        ]);
                        
                        \Log::info('Frontend checkCoverage: تم تحديث موقع العميل المسجل', [
                            'customer_id' => $customer->id,
                            'customer_name' => $customer->name,
                            'latitude' => $latitude,
                            'longitude' => $longitude
                        ]);
                    }
                }

                return response()->json([
                    'covered' => true,
                    'message' => 'الموقع مشمول بالخدمة',
                    'providers' => $coveredProviders
                ]);
            } else {
                return response()->json([
                    'covered' => false,
                    'message' => 'عذراً، الموقع المحدد غير مشمول بنطاق الخدمة حالياً'
                ]);
            }

        } catch (\Exception $e) {
            \Log::error('Coverage check error', [
                'error' => $e->getMessage(),
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude')
            ]);
            
            return response()->json([
                'covered' => false, 
                'message' => 'حدث خطأ أثناء التحقق من التغطية'
            ], 500);
        }
    }

    /**
     * عرض صفحة اختيار التاريخ والوقت
     */
    public function selectDateTime()
    {
        // التحقق من وجود بيانات المنتج والموقع في الجلسة
        if (!session('booking.product_id')) {
            return redirect()->route('home')->with('error', 'يرجى اختيار منتج أولاً');
        }

        if (!session('booking.latitude') || !session('booking.longitude')) {
            return redirect()->route('frontend.booking.select-location')->with('error', 'يرجى تحديد الموقع أولاً');
        }

        return view('frontend.booking.select-datetime');
    }

    /**
     * الحصول على الأوقات المتاحة (AJAX)
     */
    public function getAvailableSlots(Request $request)
    {
        try {
            $request->validate([
                'date' => 'required|date|after_or_equal:today'
            ]);

            $date = $request->date;
            
            // الحصول على مقدمي الخدمة المتاحين من الجلسة
            $availableProviders = session('booking.available_providers', []);
            
            // إذا لم توجد providers في الجلسة، استخدم جميع النشطين
            if (empty($availableProviders)) {
                $allProviders = ServiceProvider::where('is_active', true)->pluck('id')->toArray();
                $availableProviders = $allProviders;
            }

            if (empty($availableProviders)) {
                return response()->json([
                    'available_slots' => [],
                    'is_valid_date' => false,
                    'message' => 'لا توجد مقدمي خدمة متاحين'
                ]);
            }

            // استخدام أول مقدم خدمة متاح للتحقق من صحة التاريخ
            $serviceProviderId = $availableProviders[0];

            // التحقق من أن التاريخ ليس في الماضي
            $dateCarbon = Carbon::parse($date);
            if ($dateCarbon->isPast() && !$dateCarbon->isToday()) {
                return response()->json([
                    'available_slots' => [],
                    'is_valid_date' => false,
                    'message' => 'لا يمكن الحجز في تاريخ سابق'
                ]);
            }

            // التحقق من يوم الجمعة
            if ($dateCarbon->isFriday()) {
                return response()->json([
                    'available_slots' => [],
                    'is_valid_date' => false,
                    'message' => 'عذراً، يوم الجمعة إجازة'
                ]);
            }

            // التحقق من صلاحية التاريخ
            $isValidDate = Booking::isValidWorkingDate($date, $serviceProviderId);
            
            // إذا كان التاريخ غير صالح، نرجع رسالة توضح السبب
            if (!$isValidDate) {
                $message = Booking::getDateValidationMessage($date, $serviceProviderId);

                return response()->json([
                    'available_slots' => [],
                    'is_valid_date' => false,
                    'message' => $message
                ]);
            }

            // الحصول على الأوقات المتاحة مع معلومات الفترة
            $availableSlots = Booking::getAvailableTimeSlotsWithPeriods($date, $serviceProviderId);
            
            return response()->json([
                'available_slots' => array_values($availableSlots),
                'is_valid_date' => true,
                'message' => count($availableSlots) > 0 ? 'الأوقات المتاحة للحجز' : 'لا توجد أوقات متاحة في هذا اليوم',
                'date_info' => [
                    'is_today' => $dateCarbon->isToday(),
                    'is_future' => $dateCarbon->isFuture(),
                    'day_name' => $dateCarbon->locale('ar')->translatedFormat('l')
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'available_slots' => [],
                'is_valid_date' => false,
                'message' => 'البيانات المرسلة غير صحيحة',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error in getAvailableSlots: ' . $e->getMessage(), [
                'date' => $request->date,
                'stack_trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'available_slots' => [],
                'is_valid_date' => false,
                'message' => 'حدث خطأ أثناء تحميل الأوقات المتاحة، يرجى المحاولة مرة أخرى'
            ], 500);
        }
    }

    /**
     * حفظ التاريخ والوقت المختار والانتقال لصفحة بيانات العميل
     */
    public function saveDateTime(Request $request)
    {
        try {
            \Log::info('Frontend saveDateTime: بداية حفظ التاريخ والوقت', [
                'request_data' => $request->all(),
                'current_session' => session('booking')
            ]);

            $request->validate([
                'date' => 'required|date|after_or_equal:today',
                'time' => 'required|string'
            ]);

            $selectedDate = Carbon::parse($request->date);
            $timeString = $request->time;
            
            // التحقق من أن اليوم ليس جمعة
            if ($selectedDate->isFriday()) {
                return back()->with('error', 'عذراً، لا نعمل يوم الجمعة');
            }

            // الحصول على مقدمي الخدمة المتاحين من الجلسة
            $availableProviders = session('booking.available_providers', []);
            $serviceProvider = null;
            
            if (!empty($availableProviders)) {
                // استخدم أول مقدم خدمة متاح من الجلسة
                $serviceProvider = ServiceProvider::where('is_active', true)
                    ->whereIn('id', $availableProviders)
                    ->first();
            }
            
            if (!$serviceProvider) {
                // كـ fallback، استخدم أي مقدم خدمة نشط
                $serviceProvider = ServiceProvider::where('is_active', true)->first();
            }
            
            if (!$serviceProvider) {
                return back()->with('error', 'لا توجد مقدمي خدمة متاحين');
            }

            // التحقق من صلاحية التاريخ مع مقدم الخدمة
            if (!Booking::isValidWorkingDate($selectedDate->toDateString(), $serviceProvider->id)) {
                $errorMessage = Booking::getDateValidationMessage($selectedDate->toDateString(), $serviceProvider->id);
                return back()->with('error', $errorMessage);
            }

            // التحقق من توفر الوقت
            if (!Booking::isTimeSlotAvailable($selectedDate->toDateString(), $timeString, $serviceProvider->id)) {
                return back()->with('error', 'عذراً، هذا الوقت محجوز بالفعل');
            }

            // حفظ بيانات التاريخ والوقت في الجلسة
            session([
                'booking.date' => $selectedDate->toDateString(),
                'booking.time' => $timeString,
                'booking.datetime' => $selectedDate->toDateString() . ' ' . $timeString,
                'booking.provider_id' => $serviceProvider->id
            ]);

            \Log::info('Frontend saveDateTime: تم حفظ التاريخ والوقت بنجاح', [
                'saved_date' => $selectedDate->toDateString(),
                'saved_time' => $timeString,
                'provider_id' => $serviceProvider->id,
                'final_session' => session('booking')
            ]);

            // إذا كان العميل مسجل الدخول، تخطي صفحة بيانات العميل والانتقال مباشرة لصفحة الدفع
            if (session('customer_id')) {
                \Log::info('Frontend saveDateTime: العميل مسجل الدخول، تخطي صفحة البيانات', [
                    'customer_id' => session('customer_id'),
                    'customer_name' => session('customer_name'),
                    'customer_phone' => session('customer_phone')
                ]);

                // تحديث موقع العميل في قاعدة البيانات إذا كان موجوداً في الجلسة
                if (session('booking.latitude') && session('booking.longitude')) {
                    $customer = Customer::find(session('customer_id'));
                    if ($customer) {
                        $customer->update([
                            'latitude' => session('booking.latitude'),
                            'longitude' => session('booking.longitude'),
                            'address' => session('booking.address', 'الموقع المحدد على الخريطة')
                        ]);
                        
                        \Log::info('Frontend saveDateTime: تم تحديث موقع العميل المسجل', [
                            'customer_id' => $customer->id,
                            'customer_name' => $customer->name,
                            'latitude' => session('booking.latitude'),
                            'longitude' => session('booking.longitude')
                        ]);
                    }
                }

                // حفظ بيانات العميل في جلسة الحجز
                session([
                    'booking.customer_id' => session('customer_id'),
                    'booking.customer_name' => session('customer_name'),
                    'booking.customer_phone' => session('customer_phone')
                ]);

                return redirect()->route('frontend.booking.payment-details');
            }

            return redirect()->route('frontend.booking.customer-info');

        } catch (\Exception $e) {
            \Log::error('Frontend: saveDateTime error', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return back()->with('error', 'حدث خطأ أثناء حفظ الموعد: ' . $e->getMessage());
        }
    }

    /**
     * حساب المسافة بالكيلومتر بين نقطتين جغرافيتين
     */
    private function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371; // نصف قطر الأرض بالكيلومتر
        
        $latFrom = deg2rad($lat1);
        $lngFrom = deg2rad($lng1);
        $latTo = deg2rad($lat2);
        $lngTo = deg2rad($lng2);
        
        $latDelta = $latTo - $latFrom;
        $lngDelta = $lngTo - $lngFrom;
        
        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lngDelta / 2), 2)));
            
        return $angle * $earthRadius;
    }


    /**
     * عرض صفحة إدخال بيانات العميل
     */
    public function customerInfo()
    {
        \Log::info('Frontend customerInfo: عرض صفحة بيانات العميل', [
            'session_booking' => session('booking'),
            'customer_id' => session('customer_id')
        ]);

        // التحقق من وجود بيانات الحجز
        if (!session('booking.product_id')) {
            return redirect()->route('home')->with('error', 'يرجى إكمال الخطوات السابقة أولاً');
        }

        if (!session('booking.datetime')) {
            return redirect()->route('frontend.booking.select-datetime')->with('error', 'يرجى اختيار التاريخ والوقت أولاً');
        }

        // إذا كان العميل مسجل الدخول، تخطي هذه الصفحة والانتقال مباشرة لصفحة الدفع
        if (session('customer_id')) {
            \Log::info('Frontend customerInfo: العميل مسجل الدخول، تخطي صفحة البيانات', [
                'customer_id' => session('customer_id'),
                'customer_name' => session('customer_name'),
                'customer_phone' => session('customer_phone')
            ]);

            // تحديث موقع العميل في قاعدة البيانات إذا كان موجوداً في الجلسة
            if (session('booking.latitude') && session('booking.longitude')) {
                $customer = Customer::find(session('customer_id'));
                if ($customer) {
                    $customer->update([
                        'latitude' => session('booking.latitude'),
                        'longitude' => session('booking.longitude'),
                        'address' => session('booking.address', 'الموقع المحدد على الخريطة')
                    ]);
                    
                    \Log::info('Frontend customerInfo: تم تحديث موقع العميل المسجل', [
                        'customer_id' => $customer->id,
                        'customer_name' => $customer->name,
                        'latitude' => session('booking.latitude'),
                        'longitude' => session('booking.longitude')
                    ]);
                }
            }

            // حفظ بيانات العميل في جلسة الحجز
            session([
                'booking.customer_id' => session('customer_id'),
                'booking.customer_name' => session('customer_name'),
                'booking.customer_phone' => session('customer_phone')
            ]);

            // الانتقال مباشرة لصفحة الدفع
            return redirect()->route('frontend.booking.payment-details');
        }

        return view('frontend.booking.customer-info');
    }

    /**
     * حفظ بيانات العميل والانتقال لصفحة التحقق من OTP
     */
    public function saveCustomerInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|min:8|max:20'
        ], [
            'name.required' => 'الاسم مطلوب',
            'name.max' => 'الاسم يجب ألا يزيد عن 255 حرف',
            'phone.required' => 'رقم الهاتف مطلوب',
            'phone.min' => 'رقم الهاتف يجب أن يحتوي على 8 أرقام على الأقل',
            'phone.max' => 'رقم الهاتف يجب ألا يزيد عن 20 حرف'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // تنظيف رقم الهاتف
        $phone = $request->phone;
        $phoneDigits = preg_replace('/[^0-9]/', '', $phone);
        
        // معالجة التنسيقات المختلفة
        if (strlen($phoneDigits) >= 12 && substr($phoneDigits, 0, 3) === '966') {
            // إزالة رمز الدولة 966
            $phoneDigits = substr($phoneDigits, 3);
        }
        
        if (strlen($phoneDigits) >= 10 && substr($phoneDigits, 0, 1) === '0') {
            // إزالة الصفر في البداية
            $phoneDigits = substr($phoneDigits, 1);
        }
        
        // التحقق من صحة رقم الجوال السعودي
        if (!preg_match('/^(5\d{8})$/', $phoneDigits)) {
            return back()->withErrors(['phone' => 'يجب أن يبدأ رقم الجوال بـ 5 ويحتوي على 9 أرقام'])->withInput();
        }

        // إذا كان العميل مسجل الدخول، تحديث بياناته
        if (session('customer_id')) {
            $customer = Customer::find(session('customer_id'));
            if ($customer) {
                // التحقق من أن رقم الجوال الجديد لا يتعارض مع عميل آخر
                $existingCustomer = Customer::where('phone', $phoneDigits)
                    ->where('id', '!=', $customer->id)
                    ->first();
                
                if ($existingCustomer) {
                    return back()->withErrors(['phone' => 'رقم الجوال مستخدم من قبل عميل آخر'])->withInput();
                }
                
                // تحديث البيانات فقط إذا كانت مختلفة
                $updateData = [];
                if ($customer->name !== $request->name) {
                    $updateData['name'] = $request->name;
                }
                if ($customer->phone !== $phoneDigits) {
                    $updateData['phone'] = $phoneDigits;
                }
                
                // تحديث الموقع إذا كان موجوداً في الجلسة
                if (session('booking.latitude') && session('booking.longitude')) {
                    $updateData['latitude'] = session('booking.latitude');
                    $updateData['longitude'] = session('booking.longitude');
                    $updateData['address'] = session('booking.address', 'الموقع المحدد على الخريطة');
                }
                
                if (!empty($updateData)) {
                    $customer->update($updateData);
                }
                
                // تحديث الجلسة
                session(['customer_name' => $request->name]);
                session(['customer_phone' => $phoneDigits]);
            }
        } else {
            // البحث عن العميل أو إنشاؤه
            $customer = Customer::firstOrCreate(
                ['phone' => $phoneDigits],
                [
                    'name' => $request->name,
                    'customer_type' => 'individual',
                    'latitude' => session('booking.latitude'),
                    'longitude' => session('booking.longitude'),
                    'address' => session('booking.address')
                ]
            );

            // تحديث بيانات العميل في حالة الاختلاف
            if ($customer->name !== $request->name) {
                $customer->update(['name' => $request->name]);
            }
        }

        // إنشاء وإرسال رمز OTP
        $otpCode = SmsService::generateOtp();
        $expiresAt = now()->addSeconds(90); // دقيقة ونصف

        // حفظ رمز OTP في قاعدة البيانات
        $customer->update([
            'otp_code' => $otpCode,
            'otp_expires_at' => $expiresAt,
            'otp_attempts' => 0
        ]);

        // إرسال OTP عبر SMS
        $smsService = new SmsService();
        $smsResult = $smsService->sendOtp($phoneDigits, $otpCode);

        if (!$smsResult['success']) {
            return back()->withErrors(['phone' => 'فشل في إرسال رمز التحقق، يرجى المحاولة مرة أخرى'])->withInput();
        }

        // حفظ بيانات العميل في الجلسة مؤقتاً
        session([
            'temp_booking_customer_id' => $customer->id,
            'temp_booking_customer_name' => $customer->name,
            'temp_booking_customer_phone' => $customer->phone
        ]);

        return redirect()->route('frontend.booking.verify-customer-otp')
                        ->with('success', 'تم إرسال رمز التحقق إلى رقمك');
    }

    /**
     * عرض صفحة التحقق من OTP في عملية الحجز
     */
    public function verifyCustomerOtp()
    {
        if (!session('temp_booking_customer_id')) {
            return redirect()->route('frontend.booking.customer-info')
                           ->with('error', 'يرجى إدخال بياناتك أولاً');
        }

        return view('frontend.booking.verify-customer-otp');
    }

    /**
     * التحقق من رمز OTP في عملية الحجز
     */
    public function confirmCustomerOtp(Request $request)
    {
        $request->validate([
            'otp_code' => 'required|digits:4'
        ]);

        $customerId = session('temp_booking_customer_id');
        if (!$customerId) {
            return redirect()->route('frontend.booking.customer-info')
                           ->with('error', 'انتهت صلاحية الجلسة، يرجى المحاولة مرة أخرى');
        }

        $customer = Customer::find($customerId);
        if (!$customer) {
            return redirect()->route('frontend.booking.customer-info')
                           ->with('error', 'حدث خطأ، يرجى المحاولة مرة أخرى');
        }

        // زيادة عدد المحاولات
        $customer->increment('otp_attempts');

        // التحقق من عدد المحاولات (الحد الأقصى 3 محاولات)
        if ($customer->otp_attempts > 3) {
            // مسح رمز OTP
            $customer->update([
                'otp_code' => null,
                'otp_expires_at' => null,
                'otp_attempts' => 0
            ]);

            session()->forget(['temp_booking_customer_id', 'temp_booking_customer_name', 'temp_booking_customer_phone']);
            
            return redirect()->route('frontend.booking.customer-info')
                           ->with('error', 'تم تجاوز الحد الأقصى للمحاولات، يرجى إعادة المحاولة');
        }

        // التحقق من صحة رمز OTP
        if (!SmsService::verifyOtp($request->otp_code, $customer->otp_code, $customer->otp_expires_at)) {
            $remainingAttempts = 3 - $customer->otp_attempts;
            return back()->withErrors(['otp_code' => "رمز التحقق غير صحيح. متبقي {$remainingAttempts} محاولات"]);
        }

        // رمز OTP صحيح - إكمال عملية الحجز
        $customer->update([
            'otp_code' => null,
            'otp_expires_at' => null,
            'otp_attempts' => 0,
            'last_mobile_login' => now()
        ]);

        // تحديث الموقع إذا كان موجوداً في الجلسة
        if (session('booking.latitude') && session('booking.longitude')) {
            $customer->update([
                'latitude' => session('booking.latitude'),
                'longitude' => session('booking.longitude'),
                'address' => session('booking.address', 'الموقع المحدد على الخريطة')
            ]);
        }

        // تسجيل الدخول
        session([
            'customer_id' => $customer->id,
            'customer_name' => $customer->name,
            'customer_phone' => $customer->phone
        ]);

        // حفظ بيانات العميل في جلسة الحجز
        session([
            'booking.customer_id' => $customer->id,
            'booking.customer_name' => $customer->name,
            'booking.customer_phone' => $customer->phone
        ]);

        // مسح البيانات المؤقتة
        session()->forget(['temp_booking_customer_id', 'temp_booking_customer_name', 'temp_booking_customer_phone']);

        return redirect()->route('frontend.booking.payment-details')
                        ->with('success', 'تم التحقق من رقمك بنجاح!');
    }

    /**
     * إعادة إرسال رمز OTP في عملية الحجز
     */
    public function resendCustomerOtp(Request $request)
    {
        $customerId = session('temp_booking_customer_id');
        if (!$customerId) {
            return response()->json([
                'success' => false,
                'message' => 'انتهت صلاحية الجلسة'
            ]);
        }

        $customer = Customer::find($customerId);
        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ، يرجى المحاولة مرة أخرى'
            ]);
        }

        // إنشاء رمز OTP جديد
        $otpCode = SmsService::generateOtp();
        $expiresAt = now()->addSeconds(90); // دقيقة ونصف

        $customer->update([
            'otp_code' => $otpCode,
            'otp_expires_at' => $expiresAt,
            'otp_attempts' => 0
        ]);

        // إرسال OTP عبر SMS
        $smsService = new SmsService();
        $smsResult = $smsService->sendOtp($customer->phone, $otpCode);

        if (!$smsResult['success']) {
            return response()->json([
                'success' => false,
                'message' => 'فشل في إرسال رمز التحقق، يرجى المحاولة مرة أخرى'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم إرسال رمز التحقق مرة أخرى'
        ]);
    }

    /**
     * عرض صفحة تفاصيل الدفع
     */
    public function paymentDetails()
    {
        \Log::info('Frontend paymentDetails: عرض صفحة تفاصيل الدفع', [
            'session_booking' => session('booking')
        ]);

        // التحقق من وجود بيانات الحجز
        if (!session('booking.customer_id')) {
            return redirect()->route('home')->with('error', 'يرجى إكمال الخطوات السابقة أولاً');
        }

        // استخدام البيانات المحفوظة في الجلسة بدلاً من جلب المنتج من قاعدة البيانات
        $basePrice = session('booking.base_price', 0);
        $addOnsTotal = session('booking.add_ons_total', 0);
        $selectedAddOns = session('booking.selected_add_ons', []);
        $subtotal = $basePrice + $addOnsTotal;
        
        // تطبيق خصم الكوبون إذا كان موجوداً
        $discountAmount = session('booking.discount_amount', 0);
        $finalSubtotal = $subtotal - $discountAmount;
        
        // حساب الضريبة (15%)
        $taxRate = 0.15;
        $priceWithoutTax = round($finalSubtotal / (1 + $taxRate), 2);
        $taxAmount = round($finalSubtotal - $priceWithoutTax, 2);
        $totalPrice = $finalSubtotal;
        
        $bookingData = [
            'product_id' => session('booking.product_id'),
            'product_name' => session('booking.product_name'),
            'base_price' => $basePrice,
            'selected_add_ons' => $selectedAddOns,
            'add_ons_total' => $addOnsTotal,
            'subtotal' => $subtotal,
            'discount_amount' => $discountAmount,
            'coupon_code' => session('booking.coupon_code'),
            'price_without_tax' => $priceWithoutTax,
            'tax_amount' => $taxAmount,
            'total_price' => $totalPrice,
            'datetime' => session('booking.datetime'),
            'customer_name' => session('booking.customer_name'),
            'customer_phone' => session('booking.customer_phone'),
            'address' => session('booking.address')
        ];

        \Log::info('Frontend paymentDetails: بيانات الحجز للعرض', [
            'booking_data' => $bookingData
        ]);

        return view('frontend.booking.payment-details', compact('bookingData'));
    }

    /**
     * تأكيد الحجز مع خيار الدفع لاحقاً
     */
    public function confirmBookingLater(Request $request)
    {
        return $this->createBooking('pay_later');
    }

    /**
     * عرض صفحة التحويل البنكي
     */
    public function bankTransfer()
    {
        // التحقق من وجود بيانات الحجز
        if (!session('booking.customer_id')) {
            return redirect()->route('home')->with('error', 'يرجى إكمال الخطوات السابقة أولاً');
        }

        $bankDetails = [
            'bank_name' => 'اسم البنك',
            'account_holder' => 'اسم صاحب الحساب',
            'iban' => 'SA0000000000000000000000',
            'account_number' => '00000000000000'
        ];

        // استخدام البيانات المحفوظة في الجلسة
        $basePrice = session('booking.base_price', 0);
        $addOnsTotal = session('booking.add_ons_total', 0);
        $subtotal = $basePrice + $addOnsTotal;
        
        // تطبيق خصم الكوبون إذا كان موجوداً
        $discountAmount = session('booking.discount_amount', 0);
        $finalSubtotal = $subtotal - $discountAmount;
        
        // حساب الضريبة (15%)
        $taxRate = 0.15;
        $priceWithoutTax = round($finalSubtotal / (1 + $taxRate), 2);
        $taxAmount = round($finalSubtotal - $priceWithoutTax, 2);
        $totalPrice = $finalSubtotal;
        
        $bookingData = [
            'subtotal' => $subtotal,
            'discount_amount' => $discountAmount,
            'coupon_code' => session('booking.coupon_code'),
            'price_without_tax' => $priceWithoutTax,
            'tax_amount' => $taxAmount,
            'total_price' => $totalPrice,
            'customer_name' => session('booking.customer_name')
        ];

        return view('frontend.booking.bank-transfer', compact('bankDetails', 'bookingData'));
    }

    /**
     * رفع إيصال التحويل البنكي وتأكيد الحجز
     */
    public function uploadTransferReceipt(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'receipt' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // رفع الإيصال
        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')->store('receipts', 'public');
        }

        return $this->createBooking('bank_transfer', $receiptPath);
    }

    /**
     * إنشاء الحجز في قاعدة البيانات
     */
    private function createBooking($paymentMethod, $receiptPath = null)
    {
        try {
            \Log::info('Frontend: بدء إنشاء الحجز', [
                'payment_method' => $paymentMethod,
                'session_data' => session('booking')
            ]);

            // التحقق من وجود البيانات المطلوبة
            if (!session('booking.customer_id')) {
                throw new \Exception('Customer ID missing from session');
            }
            
            if (!session('booking.product_id')) {
                throw new \Exception('Product ID missing from session');
            }
            
            if (!session('booking.date')) {
                throw new \Exception('Booking date missing from session');
            }
            
            if (!session('booking.time')) {
                throw new \Exception('Booking time missing from session');
            }

            // التحقق من صحة product_id
            $product = Product::find(session('booking.product_id'));
            if (!$product) {
                // محاولة العثور على المنتج بالاسم إذا كان product_id غير صحيح
                if (session('booking.product_name')) {
                    $product = Product::where('name', session('booking.product_name'))->first();
                    if ($product) {
                        // تحديث product_id في الجلسة
                        session(['booking.product_id' => $product->id]);
                        \Log::info('Frontend: تم تحديث product_id في الجلسة', [
                            'old_product_id' => session('booking.product_id'),
                            'new_product_id' => $product->id,
                            'product_name' => $product->name
                        ]);
                    } else {
                        // استخدام أول منتج متاح كحل أخير
                        $firstProduct = Product::first();
                        if ($firstProduct) {
                            $product = $firstProduct;
                            session([
                                'booking.product_id' => $firstProduct->id,
                                'booking.product_name' => $firstProduct->name,
                                'booking.base_price' => $firstProduct->price
                            ]);
                            \Log::info('Frontend: تم استخدام أول منتج متاح', [
                                'product_id' => $firstProduct->id,
                                'product_name' => $firstProduct->name
                            ]);
                        } else {
                            throw new \Exception('No products available in database');
                        }
                    }
                } else {
                    // استخدام أول منتج متاح كحل أخير
                    $firstProduct = Product::first();
                    if ($firstProduct) {
                        $product = $firstProduct;
                        session([
                            'booking.product_id' => $firstProduct->id,
                            'booking.product_name' => $firstProduct->name,
                            'booking.base_price' => $firstProduct->price
                        ]);
                        \Log::info('Frontend: تم استخدام أول منتج متاح', [
                            'product_id' => $firstProduct->id,
                            'product_name' => $firstProduct->name
                        ]);
                    } else {
                        throw new \Exception('No products available in database');
                    }
                }
            }

            DB::beginTransaction();

            // توليد رقم الحجز
            $bookingNumber = 'BK-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

            // تحديد مقدم الخدمة (استخدم أول مقدم خدمة نشط إذا لم يتم تحديده)
            $serviceProviderId = session('booking.provider_id');
            if (!$serviceProviderId) {
                $firstProvider = ServiceProvider::where('is_active', true)->first();
                if ($firstProvider) {
                    $serviceProviderId = $firstProvider->id;
                } else {
                    throw new \Exception('No active service provider found');
                }
            }

            // إعداد الملاحظات
            $notes = '';
            if ($paymentMethod === 'bank_transfer') {
                $notes = 'تم رفع إيصال التحويل البنكي';
            } elseif ($paymentMethod === 'pay_later') {
                $notes = 'سيتم الدفع عند تقديم الخدمة';
            }

            // إعداد المرفقات
            $attachments = null;
            if ($receiptPath) {
                $attachments = json_encode([$receiptPath]);
            }

            // حساب المبلغ النهائي مع الإضافات والضريبة
            $basePrice = session('booking.base_price', 0);
            $addOnsTotal = session('booking.add_ons_total', 0);
            $subtotal = $basePrice + $addOnsTotal;
            
            // تطبيق خصم الكوبون إذا كان موجوداً
            $discountAmount = session('booking.discount_amount', 0);
            $finalSubtotal = $subtotal - $discountAmount;
            
            // حساب الضريبة (15%)
            $taxRate = 0.15;
            $priceWithoutTax = round($finalSubtotal / (1 + $taxRate), 2);
            $taxAmount = round($finalSubtotal - $priceWithoutTax, 2);
            $finalTotal = $finalSubtotal;

            \Log::info('Frontend: حساب المبلغ النهائي', [
                'base_price' => $basePrice,
                'add_ons_total' => $addOnsTotal,
                'subtotal' => $subtotal,
                'price_without_tax' => $priceWithoutTax,
                'tax_amount' => $taxAmount,
                'final_total' => $finalTotal,
                'selected_add_ons' => session('booking.selected_add_ons', [])
            ]);

            // إنشاء الحجز
            $booking = Booking::create([
                'booking_number' => $bookingNumber,
                'customer_id' => session('booking.customer_id'),
                'product_id' => session('booking.product_id'),
                'service_provider_id' => $serviceProviderId,
                'booking_date' => session('booking.date'),
                'booking_time' => session('booking.time'),
                'quantity' => session('booking.quantity', 1),
                'selected_add_ons' => session('booking.selected_add_ons', []),
                'status' => 'pending',
                'booking_source' => 'website',
                'total_price' => $finalTotal, // المبلغ النهائي مع الإضافات (بدون ضريبة)
                'coupon_id' => session('booking.coupon_id'),
                'discount_amount' => $discountAmount,
                'customer_latitude' => session('booking.latitude'),
                'customer_longitude' => session('booking.longitude'),
                'customer_address' => session('booking.address'),
                'location_map_url' => null,
                'is_riyadh_confirmed' => true,
                'attachments' => $attachments,
                'notes' => $notes,
                'customer_notes' => 'حجز من الموقع الإلكتروني'
            ]);

            \Log::info('Frontend: تم إنشاء الحجز بنجاح', [
                'booking_id' => $booking->id,
                'booking_number' => $booking->booking_number
            ]);

            // زيادة عدد مرات استخدام الكوبون إذا كان مطبقاً
            if (session('booking.coupon_id')) {
                $coupon = \App\Models\Coupon::find(session('booking.coupon_id'));
                if ($coupon) {
                    $coupon->incrementUsage();
                    \Log::info('Frontend: تم زيادة عدد مرات استخدام الكوبون', [
                        'coupon_id' => $coupon->id,
                        'coupon_code' => $coupon->code,
                        'new_usage_count' => $coupon->used_count
                    ]);
                }
            }

            // إزالة المنتج من السلات المتروكة عند إتمام الحجز بنجاح
            $productId = session('booking.product_id');
            if ($productId) {
                $abandonedCarts = AbandonedCart::where('product_id', $productId)
                    ->where('is_contacted', false)
                    ->get();
                
                foreach ($abandonedCarts as $abandonedCart) {
                    $abandonedCart->update([
                        'is_contacted' => true,
                        'notes' => $abandonedCart->notes . "\n\n" . now()->format('Y-m-d H:i') . " - تم إتمام الحجز بنجاح (رقم الحجز: " . $booking->booking_number . ")"
                    ]);
                }
                
                \Log::info('Frontend: تم تحديث السلات المتروكة بعد إتمام الحجز', [
                    'product_id' => $productId,
                    'updated_carts_count' => $abandonedCarts->count(),
                    'booking_number' => $booking->booking_number
                ]);
            }

            DB::commit();

            // إرسال رسالة تأكيد الحجز للمدير
            try {
                $smsService = new SmsService();
                $bookingNotificationData = [
                    'customer_name' => $booking->customer->name ?? session('booking.customer_name'),
                    'customer_phone' => $booking->customer->phone ?? session('booking.customer_phone'),
                    'booking_date' => $booking->booking_date,
                    'booking_time' => $booking->booking_time,
                    'customer_address' => $booking->customer_address ?? 'الموقع المحدد على الخريطة',
                    'total_price' => number_format($booking->total_price, 2),
                    'booking_number' => $booking->booking_number
                ];
                
                $notificationResult = $smsService->sendBookingNotificationToManager($bookingNotificationData);
                
                if ($notificationResult['success']) {
                    \Log::info('Booking notification sent to manager successfully', [
                        'booking_id' => $booking->id,
                        'booking_number' => $booking->booking_number
                    ]);
                } else {
                    \Log::warning('Failed to send booking notification to manager', [
                        'booking_id' => $booking->id,
                        'error' => $notificationResult['error'] ?? 'Unknown error'
                    ]);
                }
            } catch (\Exception $e) {
                \Log::error('Exception while sending booking notification to manager', [
                    'booking_id' => $booking->id,
                    'error' => $e->getMessage()
                ]);
            }

            // تنظيف بيانات الجلسة
            session()->forget('booking');

            return redirect()->route('frontend.booking.success', $booking->id);

        } catch (\Exception $e) {
            \Log::error('Frontend: خطأ في إنشاء الحجز', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'session_data' => session('booking')
            ]);

            DB::rollback();
            return back()->with('error', 'حدث خطأ أثناء إنشاء الحجز: ' . $e->getMessage());
        }
    }

    /**
     * عرض صفحة نجاح الحجز
     */
    public function bookingSuccess($bookingId)
    {
        $booking = Booking::with(['customer', 'product', 'serviceProvider'])
            ->findOrFail($bookingId);

        \Log::info('Frontend bookingSuccess: عرض صفحة نجاح الحجز', [
            'booking_id' => $bookingId,
            'booking_data' => [
                'base_price' => $booking->base_price,
                'total_price' => $booking->total_price
            ]
        ]);

        return view('frontend.booking.success', compact('booking'));
    }

    /**
     * عرض تفاصيل الحجز
     */
    public function bookingDetails($id)
    {
        $booking = Booking::with(['customer', 'product', 'serviceProvider'])
            ->findOrFail($id);

        return view('frontend.booking.details', compact('booking'));
    }

    /**
     * إلغاء الحجز
     */
    public function cancelBooking($id)
    {
        $booking = Booking::findOrFail($id);
        
        // التحقق من إمكانية الإلغاء (مثلاً قبل 24 ساعة)
        $bookingTime = Carbon::parse($booking->booking_date . ' ' . $booking->booking_time);
        if (now()->diffInHours($bookingTime, false) < 24) {
            return back()->with('error', 'لا يمكن إلغاء الحجز قبل أقل من 24 ساعة من الموعد');
        }

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'تم إلغاء الحجز بنجاح');
    }

    /**
     * الحصول على إضافات المنتج (AJAX)
     */
    public function getProductAddOns(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id'
            ]);

            $product = Product::find($request->product_id);
            
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'المنتج غير موجود',
                    'add_ons' => []
                ], 404);
            }

            // الحصول على الإضافات مع التنسيق الصحيح مع معالجة الأخطاء
            try {
                $addOns = $product->formatted_add_ons;
                $hasAddOns = $product->hasAddOns();
            } catch (\Exception $addOnsError) {
                \Log::error('Error accessing product add-ons: ' . $addOnsError->getMessage(), [
                    'product_id' => $product->id,
                    'error' => $addOnsError->getMessage()
                ]);
                $addOns = [];
                $hasAddOns = false;
            }
            
            return response()->json([
                'success' => true,
                'message' => count($addOns) > 0 ? 'تم تحميل الإضافات بنجاح' : 'لا توجد إضافات متاحة لهذا المنتج',
                'add_ons' => $addOns,
                'product_info' => [
                    'name' => $product->name,
                    'price' => $product->price,
                    'formatted_price' => number_format($product->price, 2) . ' ر.س',
                    'has_add_ons' => $hasAddOns
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'البيانات المرسلة غير صحيحة',
                'errors' => $e->errors(),
                'add_ons' => []
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error in Frontend getProductAddOns: ' . $e->getMessage(), [
                'product_id' => $request->product_id,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'stack_trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحميل إضافات المنتج، يرجى المحاولة مرة أخرى',
                'add_ons' => []
            ], 500);
        }
    }

    /**
     * إضافة منتج إلى السلة المتروكة
     */
    public function addToCart(Request $request)
    {
        try {
            // تسجيل البيانات الواردة للتشخيص
            \Log::info('addToCart called with data:', $request->all());
            
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'customer_name' => 'required|string|max:255',
                'customer_phone' => 'required|string|max:20'
            ]);

            // التحقق من صحة رقم الجوال السعودي
            $phone = $request->customer_phone;
            $phone = preg_replace('/[^\d]/', '', $phone);
            
            if (!preg_match('/^(05\d{8}|5\d{8})$/', $phone)) {
                return response()->json([
                    'success' => false,
                    'message' => 'يجب أن يبدأ رقم الجوال بـ 05 أو 5'
                ], 422);
            }

            // الحل النهائي: استخدام DB مباشرة مع معالجة الأخطاء
            try {
                // التحقق من وجود الجدول أولاً
                if (!\Schema::hasTable('abandoned_carts')) {
                    // إنشاء الجدول إذا لم يكن موجوداً
                    \Schema::create('abandoned_carts', function (\Illuminate\Database\Schema\Blueprint $table) {
                        $table->id();
                        $table->string('customer_name');
                        $table->string('customer_phone', 20);
                        $table->unsignedBigInteger('product_id');
                        $table->boolean('is_contacted')->default(false);
                        $table->text('notes')->nullable();
                        $table->timestamps();
                        
                        $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
                        $table->index('customer_phone');
                        $table->index('is_contacted');
                    });
                }
                
                // إدراج البيانات مباشرة
                $inserted = \DB::table('abandoned_carts')->insert([
                    'customer_name' => $request->customer_name,
                    'customer_phone' => $phone,
                    'product_id' => $request->product_id,
                    'is_contacted' => false,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                if ($inserted) {
                    \Log::info('Product added to cart successfully');
                    return response()->json([
                        'success' => true,
                        'message' => 'تم إضافة المنتج إلى السلة بنجاح'
                    ]);
                } else {
                    throw new \Exception('Failed to insert data');
                }
                
            } catch (\Exception $dbError) {
                \Log::error('Database error in addToCart: ' . $dbError->getMessage());
                
                // الحل البديل: حفظ في الجلسة بدلاً من قاعدة البيانات
                session([
                    'cart_items' => array_merge(
                        session('cart_items', []),
                        [[
                            'product_id' => $request->product_id,
                            'customer_name' => $request->customer_name,
                            'customer_phone' => $phone,
                            'added_at' => now()
                        ]]
                    )
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'تم إضافة المنتج إلى السلة بنجاح (محفوظ مؤقتاً)'
                ]);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error in addToCart: ' . json_encode($e->errors()));
            return response()->json([
                'success' => false,
                'message' => 'البيانات المرسلة غير صحيحة',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('General error in addToCart: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إضافة المنتج إلى السلة، يرجى المحاولة مرة أخرى'
            ], 500);
        }
    }
} 