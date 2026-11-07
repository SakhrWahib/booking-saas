<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ServiceProvider;
use App\Models\Coupon;
use App\Support\BookingQuote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingController extends Controller
{
    // المسار كله يحتاج bookings.view، وكل إجراء يحتاج صلاحيته الخاصة
    public function __construct()
    {
        $this->middleware('permission:bookings.create')->only(['create', 'store']);
        $this->middleware('permission:bookings.edit')->only(['edit', 'update']);
        $this->middleware('permission:bookings.delete')->only(['destroy']);
        $this->middleware('permission:bookings.confirm')->only(['confirm']);
        $this->middleware('permission:bookings.complete')->only(['complete']);
        $this->middleware('permission:bookings.cancel')->only(['cancel']);
    }

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
     * نموذج إنشاء حجز جديد
     */
    public function create(Request $request)
    {
        return view('admin.bookings.form', $this->formData(new Booking([
            'booking_date' => $request->date ?? today()->toDateString(),
            'quantity' => 1,
            'status' => 'pending',
        ])));
    }

    /**
     * حفظ حجز جديد: العميل (موجود أو جديد)، والسعر من الخادم، ومنع تضارب المواعيد
     */
    public function store(Request $request)
    {
        if (!plan_allows('monthly_bookings')) {
            return back()->withInput()->with('error', plan_limit_message('monthly_bookings'));
        }

        $data = $this->validateBooking($request);
        $customerId = $this->resolveCustomer($request);
        $product = Product::findOrFail($data['product_id']);

        if ($error = $this->slotError($data)) {
            return back()->withErrors($error)->withInput();
        }

        $quote = BookingQuote::make($product, $data['add_ons'] ?? [], (int) ($data['quantity'] ?? 1), $data['coupon_code'] ?? null);

        $booking = DB::transaction(function () use ($data, $customerId, $quote) {
            $booking = Booking::create([
                'booking_number' => Booking::generateBookingNumber(),
                'customer_id' => $customerId,
                'product_id' => $data['product_id'],
                'service_provider_id' => $data['service_provider_id'],
                'booking_date' => $data['booking_date'],
                'booking_time' => $data['booking_time'],
                'quantity' => (int) ($data['quantity'] ?? 1),
                'selected_add_ons' => $quote->addOns,
                'status' => 'pending',
                'notes' => $data['notes'] ?? null,
                'admin_notes' => $data['admin_notes'] ?? null,
                'booking_source' => 'admin_panel',
                'coupon_id' => $quote->coupon?->id,
                'discount_amount' => $quote->discount,
                'total_price' => $quote->total,
            ]);
            $quote->coupon?->incrementUsage();

            if (!$booking->invoice) {
                \App\Models\Invoice::createFromBooking($booking, auth()->id());
            }

            return $booking;
        });

        return redirect()->route('admin.bookings.show', $booking)->with('success', 'تم إنشاء الحجز #' . $booking->booking_number);
    }

    /**
     * بيانات نموذج الحجز (إنشاء وتعديل) جاهزة لـ Alpine.js
     */
    private function formData(Booking $booking): array
    {
        $products = Product::where('is_active', true)->orWhere('id', $booking->product_id)->orderBy('name')
            ->get(['id', 'name', 'price', 'add_ons', 'service_provider_ids']);
        $providers = ServiceProvider::where('is_active', true)->orWhere('id', $booking->service_provider_id)->orderBy('name')
            ->get(['id', 'name', 'product_ids', 'capacity', 'work_type']);

        return [
            'booking' => $booking,
            'customers' => Customer::orderBy('name')->get(['id', 'name', 'phone']),
            'products' => $products->map(fn ($p) => [
                'id' => $p->id, 'name' => $p->name, 'price' => (float) $p->price,
                'add_ons' => array_values(array_map(fn ($a) => ['name' => $a['name'] ?? '', 'price' => (float) ($a['price'] ?? 0)], collect($p->add_ons ?? [])->map(fn ($a) => (array) $a)->all())),
            ])->values(),
            'providers' => $providers->map(fn ($s) => [
                'id' => $s->id, 'name' => $s->name, 'product_ids' => array_map('intval', $s->product_ids ?? []),
            ])->values(),
        ];
    }

    private function validateBooking(Request $request, bool $editing = false): array
    {
        return $request->validate([
            'customer_id' => ['nullable', 'required_without:new_customer_phone', 'exists:customers,id,tenant_id,' . tenant_id()],
            'new_customer_name' => 'nullable|required_with:new_customer_phone|string|max:100',
            'new_customer_phone' => ['nullable', 'regex:/^05\d{8}$/'],
            'product_id' => 'required|exists:products,id,tenant_id,' . tenant_id(),
            'service_provider_id' => 'required|exists:service_providers,id,tenant_id,' . tenant_id(),
            'booking_date' => $editing ? 'required|date' : 'required|date|after_or_equal:today',
            'booking_time' => 'required|date_format:H:i',
            'quantity' => 'nullable|integer|min:1|max:20',
            'add_ons' => 'nullable|array',
            'add_ons.*' => 'string|max:150',
            'coupon_code' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:2000',
            'admin_notes' => 'nullable|string|max:2000',
            'status' => $editing ? 'required|in:pending,confirmed,paid,completed,cancelled' : 'nullable',
        ], [
            'customer_id.required_without' => 'اختر عميلًا أو أدخل بيانات عميل جديد',
            'new_customer_phone.regex' => 'رقم الجوال يجب أن يكون بصيغة 05XXXXXXXX',
            'booking_date.after_or_equal' => 'لا يمكن الحجز في تاريخ سابق',
            'booking_time.required' => 'اختر وقت الموعد',
        ]);
    }

    // عميل موجود، أو عميل جديد (أو موجود بنفس الجوال في هذا النشاط)
    private function resolveCustomer(Request $request): int
    {
        if ($request->filled('new_customer_phone')) {
            return Customer::firstOrCreate(
                ['phone' => $request->new_customer_phone],
                ['name' => $request->new_customer_name]
            )->id;
        }

        return (int) $request->customer_id;
    }

    // رسالة خطأ إن كان الموعد غير صالح أو ممتلئ، وإلا null
    private function slotError(array $data, ?int $excludeId = null): ?array
    {
        $provider = ServiceProvider::find($data['service_provider_id']);
        $product = (int) $data['product_id'];

        if ($provider && !empty($provider->product_ids) && !in_array($product, array_map('intval', $provider->product_ids), true)) {
            return ['service_provider_id' => 'مقدّم الخدمة المختار لا يقدّم هذه الخدمة'];
        }
        if (!Booking::isValidWorkingDate($data['booking_date'], $data['service_provider_id'])) {
            return ['booking_date' => Booking::getDateValidationMessage($data['booking_date'], $data['service_provider_id'])];
        }
        $times = array_column(Booking::getAvailableTimeSlotsWithPeriods($data['booking_date'], $data['service_provider_id'], $excludeId), 'time');
        if (!in_array($data['booking_time'], $times, true)) {
            return ['booking_time' => 'هذا الموعد غير متاح (ممتلئ أو خارج أوقات العمل أو في إجازة)'];
        }

        return null;
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
     * نموذج تعديل الحجز
     */
    public function edit(Booking $booking)
    {
        $booking->load('coupon');

        return view('admin.bookings.form', $this->formData($booking));
    }

    /**
     * تحديث الحجز: نفس قواعد الإنشاء، والموعد يُتحقق منه فقط إذا تغيّر
     */
    public function update(Request $request, Booking $booking)
    {
        $data = $this->validateBooking($request, editing: true);
        $customerId = $this->resolveCustomer($request);
        $product = Product::findOrFail($data['product_id']);

        $slotChanged = $booking->service_provider_id != $data['service_provider_id']
            || Carbon::parse($booking->booking_date)->toDateString() !== $data['booking_date']
            || Carbon::parse($booking->booking_time)->format('H:i') !== $data['booking_time'];
        $occupies = in_array($data['status'], Booking::OCCUPYING_STATUSES, true);

        if ($slotChanged && $occupies && ($error = $this->slotError($data, $booking->id))) {
            return back()->withErrors($error)->withInput();
        }

        // الكوبون الحالي يبقى إن لم يُغيَّر، والجديد يُحتسب استخدامه مرة واحدة
        $couponCode = $data['coupon_code'] ?? $booking->coupon?->code;
        $quote = BookingQuote::make($product, $data['add_ons'] ?? [], (int) ($data['quantity'] ?? 1), $couponCode);

        DB::transaction(function () use ($booking, $data, $customerId, $quote) {
            $isNewCoupon = $quote->coupon && $quote->coupon->id !== $booking->coupon_id;

            $changes = [
                'customer_id' => $customerId,
                'product_id' => $data['product_id'],
                'service_provider_id' => $data['service_provider_id'],
                'booking_date' => $data['booking_date'],
                'booking_time' => $data['booking_time'],
                'quantity' => (int) ($data['quantity'] ?? 1),
                'selected_add_ons' => $quote->addOns,
                'status' => $data['status'],
                'notes' => $data['notes'] ?? null,
                'admin_notes' => $data['admin_notes'] ?? null,
                'coupon_id' => $quote->coupon?->id,
                'discount_amount' => $quote->discount,
                'total_price' => $quote->total,
            ];
            if ($booking->status !== $data['status']) {
                $stamp = ['confirmed' => 'confirmed_at', 'completed' => 'completed_at', 'cancelled' => 'cancelled_at'][$data['status']] ?? null;
                if ($stamp) {
                    $changes[$stamp] = now();
                }
            }

            $booking->update($changes);
            if ($isNewCoupon) {
                $quote->coupon->incrementUsage();
            }
            $booking->invoice?->updateFromBooking($booking);
        });

        return redirect()->route('admin.bookings.show', $booking)->with('success', 'تم تحديث الحجز');
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
    // الانتقالات المسموحة بين الحالات
    private const TRANSITIONS = [
        'confirm' => ['pending'],
        'complete' => ['confirmed', 'paid'],
        'cancel' => ['pending', 'confirmed', 'paid'],
    ];

    private function guardTransition(Booking $booking, string $action)
    {
        return in_array($booking->status, self::TRANSITIONS[$action], true)
            ? null
            : back()->with('error', 'لا يمكن تنفيذ هذا الإجراء على حجز حالته الحالية');
    }

    /**
     * تأكيد الحجز (وإنشاء أو تحديث فاتورته)
     */
    public function confirm(Booking $booking)
    {
        if ($blocked = $this->guardTransition($booking, 'confirm')) {
            return $blocked;
        }

        $booking->confirmBooking();
        $booking->invoice
            ? $booking->invoice->updateFromBooking($booking)
            : \App\Models\Invoice::createFromBooking($booking, auth()->id());

        return back()->with('success', 'تم تأكيد الحجز #' . $booking->booking_number);
    }

    /**
     * إكمال الحجز (وتسجيل الفاتورة مدفوعة نقدًا)
     */
    public function complete(Booking $booking)
    {
        if ($blocked = $this->guardTransition($booking, 'complete')) {
            return $blocked;
        }

        // الدفع أولًا: تسجيل الفاتورة مدفوعة يغيّر حالة الحجز إلى paid، ثم الإكمال يضبطها نهائيًا
        $booking->invoice?->markAsPaid('cash', 'تم الدفع عند إكمال الخدمة');
        $booking->refresh()->completeBooking();

        return back()->with('success', 'تم إكمال الحجز #' . $booking->booking_number);
    }

    /**
     * إلغاء الحجز (يحرّر الموعد)
     */
    public function cancel(Booking $booking)
    {
        if ($blocked = $this->guardTransition($booking, 'cancel')) {
            return $blocked;
        }

        $booking->cancel();

        return back()->with('success', 'تم إلغاء الحجز #' . $booking->booking_number);
    }

    /**
     * المواعيد المتاحة (AJAX): حسب جدول مقدّم الخدمة وإجازاته وطاقته، مع استثناء الحجز عند التعديل
     */
    public function getAvailableTimeSlots(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'service_provider_id' => 'required|exists:service_providers,id,tenant_id,' . tenant_id(),
            'exclude_booking_id' => 'nullable|exists:bookings,id,tenant_id,' . tenant_id(),
        ]);

        $date = Carbon::parse($request->date);
        if ($date->isPast() && !$date->isToday() && !$request->filled('exclude_booking_id')) {
            return response()->json(['available_slots' => [], 'is_valid_date' => false, 'message' => 'لا يمكن الحجز في تاريخ سابق']);
        }

        if (!Booking::isValidWorkingDate($date->toDateString(), $request->service_provider_id)) {
            return response()->json([
                'available_slots' => [],
                'is_valid_date' => false,
                'message' => Booking::getDateValidationMessage($date->toDateString(), $request->service_provider_id),
            ]);
        }

        $slots = Booking::getAvailableTimeSlotsWithPeriods($date->toDateString(), $request->service_provider_id, $request->exclude_booking_id);

        return response()->json([
            'available_slots' => $slots,
            'is_valid_date' => true,
            'message' => $slots ? 'المواعيد المتاحة' : 'لا توجد مواعيد متاحة في هذا اليوم',
        ]);
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