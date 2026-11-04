<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ServiceProvider;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * عرض قائمة الحجوزات
     */
    public function index(Request $request)
    {
        $query = Booking::with(['customer', 'product', 'serviceProvider', 'coupon', 'invoice']);

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // فلترة حسب التاريخ
        if ($request->filled('date')) {
            $query->whereDate('booking_date', $request->date);
        }

        // فلترة حسب مقدم الخدمة
        if ($request->filled('service_provider_id')) {
            $query->where('service_provider_id', $request->service_provider_id);
        }

        // فلترة حسب مصدر الحجز
        if ($request->filled('booking_source')) {
            $query->where('booking_source', $request->booking_source);
        }

        // البحث في اسم العميل أو رقم الحجز
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('booking_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($customerQuery) use ($search) {
                      $customerQuery->where('name', 'like', "%{$search}%")
                          ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        $bookings = $query->orderBy('created_at', 'desc')
            ->paginate(10);

        // إحصائيات للوحة التحكم
        $stats = [
            'total' => Booking::count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'paid' => Booking::where('status', 'paid')->count(),
            'completed' => Booking::where('status', 'completed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
            'today' => Booking::whereDate('booking_date', today())->count(),
            'website_bookings' => Booking::where('booking_source', 'website')->count(),
        ];

        $serviceProviders = ServiceProvider::orderBy('name')->get();

        return view('admin.bookings.index', compact('bookings', 'stats', 'serviceProviders'));
    }

    /**
     * عرض نموذج إنشاء حجز جديد
     */
    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        $serviceProviders = ServiceProvider::orderBy('name')->get();
        $coupons = Coupon::active()->get();
        
        // الحصول على الأوقات المتاحة من أول مقدم خدمة أو الأوقات الثابتة
        $availableTimes = Booking::AVAILABLE_TIMES; // fallback
        if ($serviceProviders->isNotEmpty()) {
            $firstProvider = $serviceProviders->first();
            $serviceProvider = ServiceProvider::with('workingSchedule.activeTimeSlots')->find($firstProvider->id);
            if ($serviceProvider && $serviceProvider->workingSchedule) {
                $dbTimes = $serviceProvider->workingSchedule->getAvailableTimesArray();
                if (!empty($dbTimes)) {
                    $availableTimes = $dbTimes;
                }
            }
        }

        return view('admin.bookings.create', compact('customers', 'products', 'serviceProviders', 'availableTimes', 'coupons'));
    }

    /**
     * حفظ حجز جديد
     */
    public function store(Request $request)
    {
        // الحصول على الأوقات المتاحة من قاعدة البيانات
        $availableTimes = [];
        if ($request->service_provider_id) {
            $serviceProvider = ServiceProvider::with('workingSchedule.activeTimeSlots')->find($request->service_provider_id);
            if ($serviceProvider && $serviceProvider->workingSchedule) {
                $availableTimes = $serviceProvider->workingSchedule->getAvailableTimesArray();
            }
        }
        
        // إذا لم توجد أوقات متاحة، استخدم الأوقات الثابتة كـ fallback
        if (empty($availableTimes)) {
            $availableTimes = Booking::AVAILABLE_TIMES;
        }

        $rules = [
            'customer_id' => 'required|exists:customers,id,tenant_id,' . tenant_id(),
            'product_id' => 'required|exists:products,id,tenant_id,' . tenant_id(),
            'service_provider_id' => 'required|exists:service_providers,id,tenant_id,' . tenant_id(),
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required|in:' . implode(',', $availableTimes),
            'quantity' => 'nullable|integer|min:1',
            'selected_add_ons' => 'nullable|array',
            'selected_add_ons.*.option' => 'required_with:selected_add_ons|string',
            'selected_add_ons.*.name' => 'required_with:selected_add_ons|string',
            'selected_add_ons.*.price' => 'required_with:selected_add_ons|numeric|min:0',
            'selected_add_ons.*.image' => 'nullable|string',
            'coupon_code' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
            'admin_notes' => 'nullable|string',
            'booking_source' => 'nullable|in:admin_panel,website'
        ];

        // تعيين قيمة افتراضية لـ booking_source
        if (!$request->has('booking_source') || empty($request->booking_source)) {
            $request->merge(['booking_source' => 'admin_panel']);
        }

        // إضافة قواعد إضافية للحجوزات من الموقع الإلكتروني
        if ($request->booking_source === 'website') {
            $rules = array_merge($rules, [
                'customer_latitude' => 'required|numeric|between:-90,90',
                'customer_longitude' => 'required|numeric|between:-180,180',
                'customer_address' => 'required|string|max:500',
                'location_map_url' => 'nullable|url',
                'is_riyadh_confirmed' => 'required|boolean',
                'customer_notes' => 'nullable|string',
                'attachments' => 'nullable|array'
            ]);
        }

        $request->validate($rules);

        // التحقق من صلاحية الوقت مع قاعدة البيانات
        if (!Booking::isValidTime($request->booking_time, $request->service_provider_id)) {
            return back()->withErrors(['booking_time' => 'وقت الحجز المحدد غير صالح'])->withInput();
        }

        // التحقق من صلاحية التاريخ مع جدول أوقات العمل
        if (!Booking::isValidWorkingDate($request->booking_date, $request->service_provider_id)) {
            $errorMessage = Booking::getDateValidationMessage($request->booking_date, $request->service_provider_id);
            return back()->withErrors(['booking_date' => $errorMessage])->withInput();
        }

        // التحقق من توفر الوقت
        if (!Booking::isTimeSlotAvailable($request->booking_date, $request->booking_time, $request->service_provider_id)) {
            return back()->withErrors(['booking_time' => 'هذا الوقت محجوز مسبقاً'])->withInput();
        }

        // التحقق من نطاق التغطية للحجوزات من الموقع الإلكتروني
        if ($request->booking_source === 'website') {
            if (!Booking::isWithinServiceArea($request->customer_latitude, $request->customer_longitude, $request->service_provider_id)) {
                return back()->withErrors(['customer_address' => 'نعتذر، موقعك خارج نطاق التغطية المتاح'])->withInput();
            }
        }

        // معالجة الكوبون
        $couponId = null;
        $discountAmount = 0;
        
        if ($request->filled('coupon_code')) {
            $coupon = Coupon::findValidByCode($request->coupon_code);
            if ($coupon) {
                // حساب السعر الإجمالي للمقارنة مع الحد الأدنى للكوبون
                $product = Product::find($request->product_id);
                $addOnsTotal = 0;
                if ($request->selected_add_ons) {
                    foreach ($request->selected_add_ons as $addOn) {
                        $addOnsTotal += $addOn['price'] ?? 0;
                    }
                }
                $totalAmount = ($product->price + $addOnsTotal) * ($request->quantity ?? 1);
                
                if ($coupon->isValidForAmount($totalAmount)) {
                    $couponId = $coupon->id;
                    $discountAmount = $coupon->calculateDiscount($totalAmount);
                    // زيادة عدد مرات الاستخدام
                    $coupon->incrementUsage();
                }
            }
        }

        // إنشاء الحجز
        $booking = Booking::create([
            'booking_number' => Booking::generateBookingNumber(),
            'customer_id' => $request->customer_id,
            'product_id' => $request->product_id,
            'service_provider_id' => $request->service_provider_id,
            'booking_date' => $request->booking_date,
            'booking_time' => $request->booking_time,
            'quantity' => $request->quantity ?? 1,
            'selected_add_ons' => $request->selected_add_ons,
            'status' => 'pending',
            'notes' => $request->notes,
            'admin_notes' => $request->admin_notes,
            'booking_source' => $request->booking_source ?? 'admin_panel',
            'customer_latitude' => $request->customer_latitude,
            'customer_longitude' => $request->customer_longitude,
            'customer_address' => $request->customer_address,
            'location_map_url' => $request->location_map_url,
            'is_riyadh_confirmed' => $request->is_riyadh_confirmed ?? false,
            'customer_notes' => $request->customer_notes,
            'attachments' => $request->attachments,
            'coupon_id' => $couponId,
            'discount_amount' => $discountAmount
        ]);

        // إنشاء فاتورة تلقائياً للحجز الجديد
        if (!$booking->invoice) {
            $invoice = \App\Models\Invoice::createFromBooking($booking, auth()->id());
        }

        return redirect()->route('admin.bookings.index')->with('success', 'تم إنشاء الحجز بنجاح');
    }

    /**
     * عرض تفاصيل حجز معين
     */
    public function show(Booking $booking)
    {
        $booking->load(['customer.bookings:id,customer_id', 'product', 'serviceProvider', 'coupon', 'invoice']);
        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * عرض نموذج تعديل الحجز
     */
    public function edit(Booking $booking)
    {
        $customers = Customer::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        $serviceProviders = ServiceProvider::orderBy('name')->get();
        $coupons = Coupon::active()->get();
        
        // تحميل علاقة الكوبون مع الحجز
        $booking->load('coupon');
        
        // الحصول على الأوقات المتاحة من مقدم الخدمة الحالي أو الأوقات الثابتة
        $availableTimes = Booking::AVAILABLE_TIMES; // fallback
        if ($booking->service_provider_id) {
            $serviceProvider = ServiceProvider::with('workingSchedule.activeTimeSlots')->find($booking->service_provider_id);
            if ($serviceProvider && $serviceProvider->workingSchedule) {
                $dbTimes = $serviceProvider->workingSchedule->getAvailableTimesArray();
                if (!empty($dbTimes)) {
                    $availableTimes = $dbTimes;
                }
            }
        }

        return view('admin.bookings.edit', compact('booking', 'customers', 'products', 'serviceProviders', 'availableTimes', 'coupons'));
    }

    /**
     * تحديث الحجز
     */
    public function update(Request $request, Booking $booking)
    {
        // الحصول على الأوقات المتاحة من قاعدة البيانات
        $availableTimes = [];
        if ($request->service_provider_id) {
            $serviceProvider = ServiceProvider::with('workingSchedule.activeTimeSlots')->find($request->service_provider_id);
            if ($serviceProvider && $serviceProvider->workingSchedule) {
                $availableTimes = $serviceProvider->workingSchedule->getAvailableTimesArray();
            }
        }
        
        // إذا لم توجد أوقات متاحة، استخدم الأوقات الثابتة كـ fallback
        if (empty($availableTimes)) {
            $availableTimes = Booking::AVAILABLE_TIMES;
        }

        $rules = [
            'customer_id' => 'required|exists:customers,id,tenant_id,' . tenant_id(),
            'product_id' => 'required|exists:products,id,tenant_id,' . tenant_id(),
            'service_provider_id' => 'required|exists:service_providers,id,tenant_id,' . tenant_id(),
            'booking_date' => 'required|date',
            'booking_time' => 'required|in:' . implode(',', $availableTimes),
            'quantity' => 'nullable|integer|min:1',
            'selected_add_ons' => 'nullable|array',
            'selected_add_ons.*.option' => 'required_with:selected_add_ons|string',
            'selected_add_ons.*.name' => 'required_with:selected_add_ons|string',
            'selected_add_ons.*.price' => 'required_with:selected_add_ons|numeric|min:0',
            'selected_add_ons.*.image' => 'nullable|string',
            'coupon_code' => 'nullable|string|max:50',
            'status' => 'required|in:pending,confirmed,paid,completed,cancelled',
            'notes' => 'nullable|string',
            'admin_notes' => 'nullable|string',
            'customer_notes' => 'nullable|string'
        ];

        // إضافة قواعد إضافية للحجوزات من الموقع الإلكتروني
        if ($booking->booking_source === 'website') {
            $rules = array_merge($rules, [
                'customer_latitude' => 'nullable|numeric|between:-90,90',
                'customer_longitude' => 'nullable|numeric|between:-180,180',
                'customer_address' => 'nullable|string|max:500',
                'location_map_url' => 'nullable|url',
                'is_riyadh_confirmed' => 'nullable|boolean',
                'attachments' => 'nullable|array'
            ]);
        }

        $request->validate($rules);

        // التحقق من صلاحية الوقت مع قاعدة البيانات
        if (!Booking::isValidTime($request->booking_time, $request->service_provider_id)) {
            return back()->withErrors(['booking_time' => 'وقت الحجز المحدد غير صالح'])->withInput();
        }

        // التحقق من صلاحية التاريخ مع جدول أوقات العمل
        if (!Booking::isValidWorkingDate($request->booking_date, $request->service_provider_id)) {
            $errorMessage = Booking::getDateValidationMessage($request->booking_date, $request->service_provider_id);
            return back()->withErrors(['booking_date' => $errorMessage])->withInput();
        }

        // التحقق من توفر الوقت (إذا تم تغيير التاريخ أو الوقت أو مقدم الخدمة)
        if ($booking->booking_date != $request->booking_date || 
            $booking->booking_time != $request->booking_time || 
            $booking->service_provider_id != $request->service_provider_id) {
            
            if (!Booking::isTimeSlotAvailable($request->booking_date, $request->booking_time, $request->service_provider_id, $booking->id)) {
                return back()->withErrors(['booking_time' => 'هذا الوقت محجوز مسبقاً'])->withInput();
            }
        }

        // التحقق من نطاق التغطية للحجوزات من الموقع الإلكتروني
        if ($booking->booking_source === 'website' && $request->filled('customer_latitude') && $request->filled('customer_longitude')) {
            if (!Booking::isWithinServiceArea($request->customer_latitude, $request->customer_longitude, $request->service_provider_id)) {
                return back()->withErrors(['customer_address' => 'نعتذر، موقعك خارج نطاق التغطية المتاح'])->withInput();
            }
        }

        DB::transaction(function () use ($request, $booking) {
            // معالجة الكوبون
            $couponId = null;
            $discountAmount = 0;
            
            if ($request->filled('coupon_code')) {
                $coupon = Coupon::findValidByCode($request->coupon_code);
                if ($coupon) {
                    // حساب السعر الإجمالي للمقارنة مع الحد الأدنى للكوبون
                    $product = Product::find($request->product_id);
                    $addOnsTotal = 0;
                    if ($request->selected_add_ons) {
                        foreach ($request->selected_add_ons as $addOn) {
                            $addOnsTotal += $addOn['price'] ?? 0;
                        }
                    }
                    $totalAmount = ($product->price + $addOnsTotal) * ($request->quantity ?? 1);
                    
                    if ($coupon->isValidForAmount($totalAmount)) {
                        $couponId = $coupon->id;
                        $discountAmount = $coupon->calculateDiscount($totalAmount);
                        // زيادة عدد مرات الاستخدام
                        $coupon->incrementUsage();
                    }
                }
            }

            $updateData = [
                'customer_id' => $request->customer_id,
                'product_id' => $request->product_id,
                'service_provider_id' => $request->service_provider_id,
                'booking_date' => $request->booking_date,
                'booking_time' => $request->booking_time,
                'quantity' => $request->quantity ?? 1,
                'selected_add_ons' => $request->selected_add_ons,
                'status' => $request->status,
                'notes' => $request->notes,
                'admin_notes' => $request->admin_notes,
                'customer_notes' => $request->customer_notes,
                'coupon_id' => $couponId,
                'discount_amount' => $discountAmount
            ];

            // إضافة بيانات الموقع للحجوزات من الموقع الإلكتروني
            if ($booking->booking_source === 'website') {
                $updateData = array_merge($updateData, [
                    'customer_latitude' => $request->customer_latitude,
                    'customer_longitude' => $request->customer_longitude,
                    'customer_address' => $request->customer_address,
                    'location_map_url' => $request->location_map_url,
                    'is_riyadh_confirmed' => $request->is_riyadh_confirmed ?? false,
                    'attachments' => $request->attachments
                ]);
            }

            // إضافة تواريخ تغيير الحالة
            if ($booking->status !== $request->status) {
                switch ($request->status) {
                    case 'confirmed':
                        $updateData['confirmed_at'] = now();
                        break;
                    case 'completed':
                        $updateData['completed_at'] = now();
                        break;
                    case 'cancelled':
                        $updateData['cancelled_at'] = now();
                        break;
                }
            }

            $booking->update($updateData);
            
            // إعادة حساب السعر الإجمالي
            $booking->calculateAndSaveTotalPrice();
            
            // تحديث الفاتورة المرتبطة إذا وجدت
            if ($booking->invoice) {
                $booking->invoice->updateFromBooking($booking);
            }
        });

        return redirect()->route('admin.bookings.index')->with('success', 'تم تحديث الحجز بنجاح');
    }

    /**
     * حذف الحجز
     */
    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->route('admin.bookings.index')->with('success', 'تم حذف الحجز بنجاح');
    }

    /**
     * تأكيد الحجز
     */
    public function confirm(Booking $booking)
    {
        $booking->confirmBooking();
        
        // إنشاء فاتورة تلقائياً عند تأكيد الحجز
        if (!$booking->invoice) {
            $invoice = \App\Models\Invoice::createFromBooking($booking, auth()->id());
            
            return redirect()->route('admin.invoices.show', $invoice)
                ->with('success', 'تم تأكيد الحجز وإنشاء الفاتورة بنجاح');
        } else {
            // تحديث الفاتورة الموجودة
            $booking->invoice->updateFromBooking($booking);
            
            return redirect()->route('admin.invoices.show', $booking->invoice)
                ->with('success', 'تم تأكيد الحجز وتحديث الفاتورة بنجاح');
        }
    }

    /**
     * إكمال الحجز
     */
    public function complete(Booking $booking)
    {
        $booking->completeBooking();
        
        // إذا كان هناك فاتورة، قم بدفعها تلقائياً
        if ($booking->invoice) {
            $booking->invoice->markAsPaid('cash', 'تم الدفع عند إكمال الخدمة');
            
            return redirect()->route('admin.invoices.show', $booking->invoice)
                ->with('success', 'تم إكمال الحجز ودفع الفاتورة بنجاح');
        }
        
        return redirect()->back()->with('success', 'تم إكمال الحجز بنجاح');
    }

    /**
     * إلغاء الحجز
     */
    public function cancel(Booking $booking)
    {
        $booking->cancel();
        return redirect()->back()->with('success', 'تم إلغاء الحجز بنجاح');
    }

    /**
     * الحصول على الأوقات المتاحة (AJAX)
     */
    public function getAvailableTimeSlots(Request $request)
    {
        try {
            $request->validate([
                'date' => 'required|date|after_or_equal:today',
                'service_provider_id' => 'required|exists:service_providers,id,tenant_id,' . tenant_id(),
                'exclude_booking_id' => 'nullable|exists:bookings,id,tenant_id,' . tenant_id()
            ]);

            $date = $request->date;
            $serviceProviderId = $request->service_provider_id;
            $excludeBookingId = $request->exclude_booking_id;

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

            // الحصول على الأوقات المتاحة مع معلومات الفترة (مع استثناء حجز معين إذا كان التعديل)
            $availableSlots = Booking::getAvailableTimeSlotsWithPeriods($date, $serviceProviderId);
            
            // إذا كان هناك حجز مستثنى (في حالة التعديل)، أضف وقته إلى الأوقات المتاحة
            if ($excludeBookingId) {
                $excludedBooking = Booking::find($excludeBookingId);
                if ($excludedBooking && 
                    $excludedBooking->booking_date->format('Y-m-d') === $date &&
                    $excludedBooking->service_provider_id == $serviceProviderId) {
                    
                    $excludedTime = Carbon::parse($excludedBooking->booking_time)->format('H:i');
                    
                    // البحث عن معلومات الفترة للوقت المستثنى
                    $serviceProvider = ServiceProvider::with('workingSchedule.activeTimeSlots')->find($serviceProviderId);
                    $excludedTimeSlot = null;
                    
                    if ($serviceProvider && $serviceProvider->workingSchedule) {
                        $excludedTimeSlot = $serviceProvider->workingSchedule->activeTimeSlots()
                            ->where('time', $excludedTime)
                            ->first();
                    }
                    
                    // التحقق من عدم وجود الوقت في القائمة
                    $timeExists = false;
                    foreach ($availableSlots as $slot) {
                        if ($slot['time'] === $excludedTime) {
                            $timeExists = true;
                            break;
                        }
                    }
                    
                    if (!$timeExists) {
                        $excludedSlot = [
                            'time' => $excludedTime,
                            'period' => $excludedTimeSlot ? $excludedTimeSlot->period : 'afternoon',
                            'label' => $excludedTimeSlot ? $excludedTimeSlot->label : null
                        ];
                        $availableSlots[] = $excludedSlot;
                        
                        // ترتيب الأوقات
                        usort($availableSlots, function($a, $b) {
                            return strcmp($a['time'], $b['time']);
                        });
                    }
                }
            }
            
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
            \Log::error('Error in getAvailableTimeSlots: ' . $e->getMessage(), [
                'date' => $request->date,
                'service_provider_id' => $request->service_provider_id,
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
     * عرض تقويم الحجوزات
     */
    public function calendar(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        
        $startDate = Carbon::create($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();
        
        $providerId = $request->integer('service_provider_id') ?: null;

        $bookings = Booking::with(['customer:id,name', 'product:id,name'])
            ->whereBetween('booking_date', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->when($providerId, fn ($q) => $q->where('service_provider_id', $providerId))
            ->orderBy('booking_time')
            ->get()
            ->groupBy(fn ($booking) => Carbon::parse($booking->booking_date)->format('Y-m-d'));

        // أيام الإجازات خلال الشهر (للمقدّم المختار أو للجميع)
        $timeOffDays = [];
        \App\Models\ProviderTimeOff::with('serviceProvider:id,name')
            ->whereDate('start_date', '<=', $endDate)->whereDate('end_date', '>=', $startDate)
            ->when($providerId, fn ($q) => $q->where('service_provider_id', $providerId))
            ->get()
            ->each(function ($off) use (&$timeOffDays, $startDate, $endDate) {
                $from = $off->start_date->max($startDate);
                $to = $off->end_date->min($endDate);
                for ($d = $from->copy(); $d->lte($to); $d->addDay()) {
                    $timeOffDays[$d->format('Y-m-d')][] = $off->serviceProvider->name ?? '';
                }
            });

        $serviceProviders = ServiceProvider::orderBy('name')->get(['id', 'name']);

        return view('admin.bookings.calendar', compact('bookings', 'month', 'year', 'serviceProviders', 'providerId', 'timeOffDays', 'startDate'));
    }

    /**
     * عرض الحجوزات اليومية
     */
    public function dailyBookings(Request $request)
    {
        $date = $request->get('date', now()->format('Y-m-d'));
        $serviceProviderId = $request->get('service_provider_id');
        
        $query = Booking::with(['customer', 'product', 'serviceProvider'])
            ->whereDate('booking_date', $date);
            
        if ($serviceProviderId) {
            $query->where('service_provider_id', $serviceProviderId);
        }
        
        $bookings = $query->orderBy('booking_time')->get();
        $serviceProviders = ServiceProvider::orderBy('name')->get();
        
        return view('admin.bookings.daily', compact('bookings', 'date', 'serviceProviders', 'serviceProviderId'));
    }

    /**
     * التحقق من نطاق التغطية (AJAX)
     */
    public function checkCoverageArea(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'service_provider_id' => 'required|exists:service_providers,id,tenant_id,' . tenant_id()
        ]);

        $isWithinArea = Booking::isWithinServiceArea(
            $request->latitude,
            $request->longitude,
            $request->service_provider_id
        );

        return response()->json([
            'is_within_area' => $isWithinArea,
            'message' => $isWithinArea ? 'موقعك ضمن نطاق التغطية' : 'نعتذر، موقعك خارج نطاق التغطية المتاح'
        ]);
    }

    /**
     * رفع المرفقات
     */
    public function uploadAttachment(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx',
            'booking_id' => 'required|exists:bookings,id,tenant_id,' . tenant_id(),
            'type' => 'required|in:receipt,document,other'
        ]);

        $booking = Booking::findOrFail($request->booking_id);
        $file = $request->file('file');
        
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('bookings/attachments', $fileName, 'public');
        
        $attachments = $booking->attachments ?? [];
        $attachments[] = [
            'type' => $request->type,
            'path' => $filePath,
            'name' => $file->getClientOriginalName(),
            'uploaded_at' => now()->toISOString()
        ];
        
        $booking->update(['attachments' => $attachments]);
        
        return response()->json([
            'success' => true,
            'message' => 'تم رفع المرفق بنجاح',
            'attachment' => end($attachments)
        ]);
    }

    /**
     * الحصول على إضافات المنتج (AJAX)
     */
    public function getProductAddOns(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id,tenant_id,' . tenant_id()
            ]);

            $product = Product::find($request->product_id);
            
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'المنتج غير موجود',
                    'add_ons' => []
                ], 404);
            }

            // الحصول على الإضافات مع التنسيق الصحيح
            $addOns = $product->formatted_add_ons;
            
            return response()->json([
                'success' => true,
                'message' => count($addOns) > 0 ? 'تم تحميل الإضافات بنجاح' : 'لا توجد إضافات متاحة لهذا المنتج',
                'add_ons' => $addOns,
                'product_info' => [
                    'name' => $product->name,
                    'price' => $product->price,
                    'formatted_price' => number_format($product->price, 2) . ' ر.س',
                    'has_add_ons' => $product->hasAddOns()
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
            \Log::error('Error in getProductAddOns: ' . $e->getMessage(), [
                'product_id' => $request->product_id,
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
     * التحقق من صلاحية الكوبون (AJAX)
     */
    public function validateCoupon(Request $request)
    {
        try {
            $request->validate([
                'coupon_code' => 'required|string|max:50',
                'total_amount' => 'required|numeric|min:0'
            ]);

            // البحث عن الكوبون بالكود
            $coupon = Coupon::where('code', $request->coupon_code)->first();
            
            if (!$coupon) {
                return response()->json([
                    'success' => false,
                    'message' => 'الكوبون غير موجود',
                    'discount_amount' => 0
                ]);
            }

            // التحقق من حالة الكوبون
            if (!$coupon->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'الكوبون متوقف',
                    'discount_amount' => 0
                ]);
            }

            // التحقق من تاريخ الصلاحية
            $now = \Carbon\Carbon::now();
            if ($coupon->start_date && $now->lt($coupon->start_date)) {
                return response()->json([
                    'success' => false,
                    'message' => 'الكوبون لم يبدأ بعد',
                    'discount_amount' => 0
                ]);
            }

            if ($coupon->end_date && $now->gt($coupon->end_date)) {
                return response()->json([
                    'success' => false,
                    'message' => 'الكوبون منتهي الصلاحية',
                    'discount_amount' => 0
                ]);
            }

            // التحقق من عدد مرات الاستخدام
            if ($coupon->max_uses && $coupon->used_count >= $coupon->max_uses) {
                return response()->json([
                    'success' => false,
                    'message' => 'تم استنفاذ عدد مرات استخدام الكوبون',
                    'discount_amount' => 0
                ]);
            }

            // التحقق من الحد الأدنى للمبلغ
            if ($request->total_amount < $coupon->min_amount) {
                return response()->json([
                    'success' => false,
                    'message' => 'المبلغ لا يلبي الحد الأدنى المطلوب للكوبون (' . number_format($coupon->min_amount, 2) . ' ر.س)',
                    'discount_amount' => 0
                ]);
            }

            // حساب قيمة الخصم
            $discountAmount = 0;
            if ($coupon->type === 'percentage') {
                $discountAmount = ($request->total_amount * $coupon->value) / 100;
            } else {
                $discountAmount = min($coupon->value, $request->total_amount);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'الكوبون صالح',
                'coupon' => [
                    'id' => $coupon->id,
                    'code' => $coupon->code,
                    'name' => $coupon->name,
                    'type' => $coupon->type,
                    'value' => $coupon->value,
                    'min_amount' => $coupon->min_amount
                ],
                'discount_amount' => $discountAmount,
                'final_amount' => $request->total_amount - $discountAmount
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'البيانات المرسلة غير صحيحة',
                'errors' => $e->errors(),
                'discount_amount' => 0
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error in validateCoupon: ' . $e->getMessage(), [
                'coupon_code' => $request->coupon_code,
                'total_amount' => $request->total_amount,
                'stack_trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء التحقق من الكوبون، يرجى المحاولة مرة أخرى',
                'discount_amount' => 0
            ], 500);
        }
    }
} 