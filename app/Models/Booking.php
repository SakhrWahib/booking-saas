<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Events\NewBookingNotification;

class Booking extends Model
{
    use \App\Models\Concerns\BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'booking_number',
        'customer_id',
        'product_id',
        'service_provider_id',
        'booking_date',
        'booking_time',
        'quantity',
        'selected_add_ons',
        'status',
        'notes',
        'customer_latitude',
        'customer_longitude',
        'customer_address',
        'location_map_url',
        'is_riyadh_confirmed',
        'booking_source',
        'attachments',
        'total_price',
        'admin_notes',
        'customer_notes',
        'confirmed_at',
        'completed_at',
        'cancelled_at',
        'coupon_id',
        'discount_amount'
    ];

    protected $casts = [
        'booking_date' => 'date',
        'attachments' => 'array',
        'selected_add_ons' => 'array',
        'is_riyadh_confirmed' => 'boolean',
        'customer_latitude' => 'decimal:8',
        'customer_longitude' => 'decimal:8',
        'total_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'confirmed_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime'
    ];

    protected $attributes = [
        'is_riyadh_confirmed' => false,
        'booking_source' => 'admin_panel',
        'status' => 'pending',
        'quantity' => 1
    ];

    // أوقات الحجز المتاحة (سيتم استبدالها بـ TimeSlots من قاعدة البيانات)
    const AVAILABLE_TIMES = [
        '11:00', // الصباح
        '13:00', // بعد الظهر
        '15:00',
        '17:00',
        '19:00', // المساء
        '21:00'
    ];

    /**
     * العلاقة مع العميل
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * العلاقة مع المنتج
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * العلاقة مع مقدم الخدمة
     */
    public function serviceProvider()
    {
        return $this->belongsTo(ServiceProvider::class);
    }

    /**
     * العلاقة مع الفاتورة
     */
    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    /**
     * العلاقة مع الكوبون
     */
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * حساب السعر الإجمالي
     */
    public function getTotalPriceAttribute()
    {
        // إذا كان هناك إضافات محددة، احسب السعر بناءً عليها
        if ($this->selected_add_ons && is_array($this->selected_add_ons) && count($this->selected_add_ons) > 0) {
            $addOnsTotal = 0;
            foreach ($this->selected_add_ons as $addOn) {
                $addOnsTotal += isset($addOn['price']) ? floatval($addOn['price']) : 0;
            }
            $basePrice = ($this->product->price + $addOnsTotal) * $this->quantity;
        } else {
            // السعر الأساسي للمنتج فقط
            $basePrice = $this->product->price * $this->quantity;
        }
        
        // تطبيق الخصم إذا كان موجود
        if ($this->discount_amount > 0) {
            $basePrice -= $this->discount_amount;
        }
        
        return max(0, $basePrice); // لا يقل السعر عن صفر
    }
    
    /**
     * الحصول على الإضافات المحددة مع التنسيق الصحيح
     */
    public function getFormattedSelectedAddOnsAttribute()
    {
        if (!$this->selected_add_ons || !is_array($this->selected_add_ons)) {
            return [];
        }
        
        return collect($this->selected_add_ons)->map(function ($addOn) {
            return [
                'option' => $addOn['option'] ?? '',
                'name' => $addOn['name'] ?? '',
                'price' => isset($addOn['price']) ? floatval($addOn['price']) : 0,
                'image' => $addOn['image'] ?? null,
                'formatted_price' => isset($addOn['price']) ? number_format(floatval($addOn['price']), 2) . ' ر.س' : '0.00 ر.س'
            ];
        })->toArray();
    }
    
    /**
     * الحصول على المرفقات بشكل آمن
     */
    public function getSafeAttachmentsAttribute()
    {
        if (!$this->attachments || !is_array($this->attachments)) {
            return [];
        }
        
        return collect($this->attachments)->filter(function ($attachment) {
            return is_array($attachment) && isset($attachment['name']);
        })->toArray();
    }
    
    /**
     * التحقق من وجود مرفقات
     */
    public function hasAttachments()
    {
        return $this->attachments && is_array($this->attachments) && count($this->attachments) > 0;
    }
    
    /**
     * التحقق من وجود إضافات محددة
     */
    public function hasSelectedAddOns()
    {
        return $this->selected_add_ons && is_array($this->selected_add_ons) && count($this->selected_add_ons) > 0;
    }
    
    /**
     * حساب مجموع أسعار الإضافات فقط
     */
    public function getAddOnsTotalPrice()
    {
        if (!$this->hasSelectedAddOns()) {
            return 0;
        }
        
        $total = 0;
        foreach ($this->selected_add_ons as $addOn) {
            $total += isset($addOn['price']) ? floatval($addOn['price']) : 0;
        }
        
        return $total;
    }

    /**
     * توليد رقم حجز فريد
     */
    public static function generateBookingNumber()
    {
        $prefix = 'BK';
        $date = now()->format('Ymd');
        $lastBooking = static::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();
        
        $sequence = $lastBooking ? 
            (int)substr($lastBooking->booking_number, -4) + 1 : 1;
        
        return $prefix . $date . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * التحقق من صلاحية وقت الحجز
     */
    public static function isValidTime($time, $serviceProviderId = null)
    {
        if ($serviceProviderId) {
            $serviceProvider = ServiceProvider::with('workingSchedule.activeTimeSlots')->find($serviceProviderId);
            if ($serviceProvider && $serviceProvider->workingSchedule) {
                $availableTimes = $serviceProvider->workingSchedule->getAvailableTimesArray();
                return in_array($time, $availableTimes);
            }
        }
        
        // fallback للأوقات الثابتة
        return in_array($time, self::AVAILABLE_TIMES);
    }

    /**
     * التحقق من صلاحية تاريخ الحجز
     */
    public static function isValidDate($date)
    {
        $date = Carbon::parse($date);
        
        // التحقق من أن اليوم ليس جمعة
        if ($date->isFriday()) {
            return false;
        }

        // التحقق من أن التاريخ مستقبلي
        return $date->isFuture();
    }

    /**
     * التحقق من توفر الوقت للحجز
     */
    public static function isTimeSlotAvailable($date, $time, $serviceProviderId, $excludeBookingId = null)
    {
        $query = static::where('booking_date', $date)
            ->where('booking_time', $time)
            ->where('service_provider_id', $serviceProviderId)
            ->whereIn('status', ['pending', 'confirmed']);
            
        // استثناء حجز معين (مفيد عند التعديل)
        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }
        
        return !$query->exists();
    }

    /**
     * الحصول على الأوقات المتاحة لتاريخ معين
     */
    public static function getAvailableTimeSlots($date, $serviceProviderId)
    {
        $serviceProvider = ServiceProvider::with('workingSchedule.activeTimeSlots')->find($serviceProviderId);
        
        // الحصول على الأوقات المتاحة من جدول العمل
        $allAvailableTimes = [];
        if ($serviceProvider && $serviceProvider->workingSchedule) {
            $allAvailableTimes = $serviceProvider->workingSchedule->getAvailableTimesArray();
        } else {
            // fallback للأوقات الثابتة
            $allAvailableTimes = self::AVAILABLE_TIMES;
        }

        $bookedSlots = static::where('booking_date', $date)
            ->where('service_provider_id', $serviceProviderId)
            ->whereIn('status', ['pending', 'confirmed'])
            ->pluck('booking_time')
            ->map(function($time) {
                // تحويل الوقت إلى صيغة H:i
                return \Carbon\Carbon::parse($time)->format('H:i');
            })
            ->toArray();

        return array_filter($allAvailableTimes, function($time) use ($bookedSlots) {
            return !in_array($time, $bookedSlots);
        });
    }

    /**
     * الحجوزات النشطة
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'confirmed']);
    }

    /**
     * الحجوزات في تاريخ معين
     */
    public function scopeOnDate($query, $date)
    {
        return $query->where('booking_date', $date);
    }

    /**
     * الحجوزات لمقدم خدمة معين
     */
    public function scopeForProvider($query, $providerId)
    {
        return $query->where('service_provider_id', $providerId);
    }

    /**
     * تأكيد الحجز
     */
    public function confirm()
    {
        $this->update([
            'status' => 'confirmed',
            'confirmed_at' => now()
        ]);
    }

    /**
     * إكمال الحجز
     */
    public function complete()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);
    }

    /**
     * إلغاء الحجز
     */
    public function cancel()
    {
        $this->update([
            'status' => 'cancelled',
            'cancelled_at' => now()
        ]);
    }

    /**
     * التحقق من أن العميل ضمن نطاق تغطية مقدم الخدمة
     */
    public static function isWithinServiceArea($latitude, $longitude, $serviceProviderId)
    {
        $serviceProvider = ServiceProvider::find($serviceProviderId);
        
        if (!$serviceProvider || !$serviceProvider->coverage_area) {
            return false;
        }

        // التحقق من النوع الجغرافي
        if ($serviceProvider->coverage_type === 'area_based') {
            return self::isPointInPolygon($latitude, $longitude, $serviceProvider->coverage_area);
        }

        // في حالة nearest_booking، نتحقق من المسافة
        if ($serviceProvider->coverage_type === 'nearest_booking') {
            return self::isWithinDistance(
                $latitude, 
                $longitude, 
                $serviceProvider->latitude, 
                $serviceProvider->longitude,
                50 // 50 كم كحد أقصى
            );
        }

        return false;
    }

    /**
     * التحقق من وجود نقطة داخل مضلع
     */
    private static function isPointInPolygon($latitude, $longitude, $polygon)
    {
        if (!isset($polygon['coordinates']) || !is_array($polygon['coordinates'])) {
            return false;
        }

        $coordinates = $polygon['coordinates'];
        $x = $longitude;
        $y = $latitude;
        $inside = false;

        for ($i = 0, $j = count($coordinates) - 1; $i < count($coordinates); $j = $i++) {
            $xi = $coordinates[$i]['lng'];
            $yi = $coordinates[$i]['lat'];
            $xj = $coordinates[$j]['lng'];
            $yj = $coordinates[$j]['lat'];

            if ((($yi > $y) != ($yj > $y)) && ($x < ($xj - $xi) * ($y - $yi) / ($yj - $yi) + $xi)) {
                $inside = !$inside;
            }
        }

        return $inside;
    }

    /**
     * التحقق من المسافة بين نقطتين
     */
    private static function isWithinDistance($lat1, $lon1, $lat2, $lon2, $maxDistanceKm)
    {
        $earthRadius = 6371; // نصف قطر الأرض بالكيلومتر

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        $distance = $earthRadius * $c;

        return $distance <= $maxDistanceKm;
    }

    /**
     * التحقق من صلاحية التاريخ بناءً على جدول أوقات العمل
     */
    public static function isValidWorkingDate($date, $serviceProviderId)
    {
        $serviceProvider = ServiceProvider::with('workingSchedule')->find($serviceProviderId);
        
        if (!$serviceProvider || !$serviceProvider->workingSchedule) {
            return false;
        }

        $workingSchedule = $serviceProvider->workingSchedule;
        
        // استخدام method الجديدة من WorkingSchedule التي تتعامل مع فترات الإجازة
        return $workingSchedule->isValidWorkingDate($date);
    }

    /**
     * الحصول على سبب عدم صلاحية التاريخ
     */
    public static function getDateValidationMessage($date, $serviceProviderId)
    {
        $serviceProvider = ServiceProvider::with('workingSchedule')->find($serviceProviderId);
        
        if (!$serviceProvider || !$serviceProvider->workingSchedule) {
            return 'جدول العمل غير متوفر';
        }

        $workingSchedule = $serviceProvider->workingSchedule;
        $date = Carbon::parse($date);

        // التحقق من صلاحية الجدول للتاريخ المحدد
        if (!$workingSchedule->isValidForDate($date)) {
            return 'التاريخ خارج نطاق جدول العمل';
        }

        // التحقق من فترات الإجازة أولاً
        $vacationPeriod = $workingSchedule->isVacationPeriod($date);
        if ($vacationPeriod) {
            $vacationName = isset($vacationPeriod['name']) ? $vacationPeriod['name'] : 'فترة إجازة';
            return "نعتذر، نحن حالياً في {$vacationName}";
        }

        // التحقق من العطل العادية
        if ($workingSchedule->holidays && in_array($date->format('Y-m-d'), $workingSchedule->holidays)) {
            return 'هذا اليوم إجازة رسمية';
        }

        // التحقق من أيام العمل
        $dayName = strtolower($date->format('l'));
        $activeDays = $workingSchedule->active_days;
        
        if (!isset($activeDays[$dayName]) || !$activeDays[$dayName]['active']) {
            if ($dayName === 'friday') {
                return 'عذراً، يوم الجمعة إجازة';
            }
            return 'هذا اليوم ليس يوم عمل';
        }

        return 'التاريخ غير متاح للحجز';
    }

    /**
     * الحصول على الأوقات المتاحة مع مراعاة جدول العمل
     */
    public static function getAvailableTimeSlotsWithSchedule($date, $serviceProviderId)
    {
        // التحقق من صلاحية التاريخ أولاً
        if (!self::isValidWorkingDate($date, $serviceProviderId)) {
            return [];
        }

        $serviceProvider = ServiceProvider::with('workingSchedule.activeTimeSlots')->find($serviceProviderId);
        
        // الحصول على الأوقات المتاحة من جدول العمل
        $allAvailableTimes = [];
        if ($serviceProvider && $serviceProvider->workingSchedule) {
            $allAvailableTimes = $serviceProvider->workingSchedule->getAvailableTimesArray();
        } else {
            // fallback للأوقات الثابتة
            $allAvailableTimes = self::AVAILABLE_TIMES;
        }

        // الحصول على الأوقات المحجوزة (بما في ذلك الحجوزات الحديثة)
        $bookedSlots = static::where('booking_date', $date)
            ->where('service_provider_id', $serviceProviderId)
            ->whereIn('status', ['pending', 'confirmed'])
            ->pluck('booking_time')
            ->map(function($time) {
                // إذا كان الوقت بصيغة datetime، استخرج الوقت فقط
                if (strlen($time) > 5) {
                    return Carbon::parse($time)->format('H:i');
                }
                return $time;
            })
            ->unique()
            ->toArray();

        // إرجاع الأوقات المتاحة فقط
        $availableSlots = array_filter($allAvailableTimes, function($time) use ($bookedSlots) {
            return !in_array($time, $bookedSlots);
        });

        // ترتيب الأوقات
        sort($availableSlots);
        
        return array_values($availableSlots);
    }

    /**
     * الحصول على الأوقات المتاحة مع معلومات الفترة
     */
    public static function getAvailableTimeSlotsWithPeriods($date, $serviceProviderId)
    {
        // التحقق من صلاحية التاريخ أولاً
        if (!self::isValidWorkingDate($date, $serviceProviderId)) {
            return [];
        }

        $serviceProvider = ServiceProvider::with('workingSchedule.activeTimeSlots')->find($serviceProviderId);
        
        // الحصول على الأوقات المتاحة من جدول العمل مع معلومات الفترة
        $allAvailableTimeSlots = [];
        if ($serviceProvider && $serviceProvider->workingSchedule) {
            $allAvailableTimeSlots = $serviceProvider->workingSchedule->activeTimeSlots()
                ->select('time', 'period', 'label')
                ->orderBy('sort_order')
                ->orderBy('time')
                ->get()
                ->map(function($slot) {
                    return [
                        'time' => Carbon::parse($slot->time)->format('H:i'),
                        'period' => $slot->period,
                        'label' => $slot->label
                    ];
                })
                ->toArray();
        } else {
            // fallback للأوقات الثابتة مع تحديد الفترة
            $allAvailableTimeSlots = [
                ['time' => '11:00', 'period' => 'morning', 'label' => null],
                ['time' => '13:00', 'period' => 'afternoon', 'label' => null],
                ['time' => '15:00', 'period' => 'afternoon', 'label' => null],
                ['time' => '17:00', 'period' => 'afternoon', 'label' => null],
                ['time' => '19:00', 'period' => 'evening', 'label' => null],
                ['time' => '21:00', 'period' => 'evening', 'label' => null],
            ];
        }

        // مقدّم الخدمة في إجازة
        if ($serviceProvider && $serviceProvider->isOnTimeOff($date)) {
            return [];
        }

        // عدد الحجوزات القائمة في كل موعد (المدفوعة تحجز الموعد أيضًا)
        $bookedCounts = static::whereDate('booking_date', $date)
            ->where('service_provider_id', $serviceProviderId)
            ->whereIn('status', ['pending', 'confirmed', 'paid'])
            ->pluck('booking_time')
            ->map(fn ($time) => Carbon::parse($time)->format('H:i'))
            ->countBy()
            ->all();

        // الموعد متاح ما دام عدد حجوزاته أقل من الطاقة الاستيعابية
        $capacity = $serviceProvider ? $serviceProvider->slotCapacity() : 1;
        $availableSlots = [];
        foreach ($allAvailableTimeSlots as $slot) {
            $remaining = $capacity - ($bookedCounts[$slot['time']] ?? 0);
            if ($remaining > 0) {
                $availableSlots[] = $slot + ['remaining' => $remaining];
            }
        }

        return $availableSlots;
    }

    /**
     * تنسيق عرض التاريخ والوقت
     */
    public function getFormattedDateTimeAttribute()
    {
        $date = Carbon::parse($this->booking_date);
        $time = Carbon::parse($this->booking_time);
        
        // حساب الفرق بالأيام
        $diffInDays = (int) abs($date->diffInDays(now()));
        $dayText = '';
        
        if ($diffInDays == 0) {
            $dayText = 'اليوم';
        } elseif ($diffInDays == 1) {
            $dayText = 'غداً';
        } elseif ($diffInDays == 2) {
            $dayText = 'بعد يومين';
        } else {
            $dayText = 'بعد ' . $diffInDays . ' أيام';
        }

        $formattedDate = $date->format('Y/m/d') . ' • ' . $dayText;
        $formattedTime = $time->format('h:i A');

        return [
            'date' => $formattedDate,
            'time' => $formattedTime,
            'full' => $formattedDate . ' - ' . $formattedTime
        ];
    }

    /**
     * الحصول على حالة الحجز مع الألوان
     */
    public function getStatusWithColorAttribute()
    {
        $statusColors = [
            'pending' => ['text' => 'قيد الانتظار', 'color' => 'yellow'],
            'confirmed' => ['text' => 'مؤكد', 'color' => 'blue'],
            'paid' => ['text' => 'تم الدفع', 'color' => 'green'],
            'completed' => ['text' => 'مكتمل', 'color' => 'purple'],
            'cancelled' => ['text' => 'ملغي', 'color' => 'red']
        ];

        return $statusColors[$this->status] ?? ['text' => $this->status, 'color' => 'gray'];
    }

    /**
     * تأكيد الحجز مع تحديث التاريخ
     */
    public function confirmBooking()
    {
        $this->update([
            'status' => 'confirmed',
            'confirmed_at' => now()
        ]);
    }

    /**
     * إكمال الحجز مع تحديث التاريخ
     */
    public function completeBooking()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);
    }

    /**
     * حساب السعر الإجمالي وحفظه
     */
    public function calculateAndSaveTotalPrice()
    {
        // استخدام attribute الجديد الذي يحسب السعر بناءً على الإضافات
        $totalPrice = $this->total_price;
        $this->update(['total_price' => $totalPrice]);
        return $totalPrice;
    }

    /**
     * الحجوزات من الموقع الإلكتروني
     */
    public function scopeFromWebsite($query)
    {
        return $query->where('booking_source', 'website');
    }

    /**
     * الحجوزات من لوحة التحكم
     */
    public function scopeFromAdminPanel($query)
    {
        return $query->where('booking_source', 'admin_panel');
    }

    /**
     * الحجوزات المؤكدة في الرياض
     */
    public function scopeRiyadhConfirmed($query)
    {
        return $query->where('is_riyadh_confirmed', true);
    }

    /**
     * تطبيق كوبون على الحجز
     */
    public function applyCoupon($couponCode)
    {
        $coupon = Coupon::findValidByCode($couponCode);
        
        if (!$coupon) {
            return ['success' => false, 'message' => 'الكوبون غير صالح'];
        }

        // حساب السعر الأساسي قبل الخصم
        $basePrice = $this->getBasePrice();
        
        // التحقق من صلاحية الكوبون للمبلغ
        if (!$coupon->isValidForAmount($basePrice)) {
            return ['success' => false, 'message' => 'المبلغ لا يلبي الحد الأدنى للكوبون'];
        }

        // حساب قيمة الخصم
        $discountAmount = $coupon->calculateDiscount($basePrice);
        
        // تطبيق الكوبون
        $this->update([
            'coupon_id' => $coupon->id,
            'discount_amount' => $discountAmount
        ]);

        // زيادة عدد مرات استخدام الكوبون
        $coupon->incrementUsage();

        return [
            'success' => true, 
            'message' => 'تم تطبيق الكوبون بنجاح',
            'discount_amount' => $discountAmount,
            'final_price' => $this->total_price
        ];
    }

    /**
     * إزالة الكوبون من الحجز
     */
    public function removeCoupon()
    {
        $this->update([
            'coupon_id' => null,
            'discount_amount' => 0
        ]);

        return ['success' => true, 'message' => 'تم إزالة الكوبون'];
    }

    /**
     * الحصول على السعر الأساسي قبل الخصم
     */
    public function getBasePrice()
    {
        if ($this->selected_add_ons && is_array($this->selected_add_ons) && count($this->selected_add_ons) > 0) {
            $addOnsTotal = 0;
            foreach ($this->selected_add_ons as $addOn) {
                $addOnsTotal += isset($addOn['price']) ? floatval($addOn['price']) : 0;
            }
            return ($this->product->price + $addOnsTotal) * $this->quantity;
        }
        
        return $this->product->price * $this->quantity;
    }

    /**
     * إطلاق Event عند إنشاء حجز جديد
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($booking) {
            // إطلاق Event للحجوزات من الموقع فقط
            if ($booking->booking_source === 'website') {
                event(new NewBookingNotification($booking));
            }
        });
    }
} 