<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ServiceProvider extends Model
{
    use \App\Models\Concerns\BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'capacity',
        'work_type',
        'coverage_type',
        'coverage_area',
        'latitude',
        'longitude',
        'address',
        'working_schedule_id',
        'product_ids',
        'is_active'
    ];

    protected $casts = [
        'coverage_area' => 'array',
        'product_ids' => 'array',
        'is_active' => 'boolean'
    ];

    /**
     * العلاقة مع الحجوزات
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * العلاقة مع جدول العمل المرتبط
     */
    public function workingSchedule()
    {
        return $this->belongsTo(WorkingSchedule::class, 'working_schedule_id');
    }

    public function timeOffs()
    {
        return $this->hasMany(ProviderTimeOff::class);
    }

    public function isOnTimeOff($date): bool
    {
        return $this->timeOffs()->covering($date)->exists();
    }

    // عدد الحجوزات المسموح في نفس الموعد: فرد واحد = 1، فريق = السعة
    public function slotCapacity(): int
    {
        return $this->work_type === 'multiple_persons' ? max(1, (int) $this->capacity) : 1;
    }

    /**
     * العلاقة مع المنتجات (محدثة لاستخدام الحقول JSON)
     */
    public function products()
    {
        if (!$this->product_ids || !is_array($this->product_ids)) {
            return collect();
        }
        
        return Product::whereIn('id', $this->product_ids)->get();
    }

    /**
     * ربط المنتجات بمقدم الخدمة
     */
    public function attachProducts($productIds)
    {
        if (!is_array($productIds)) {
            $productIds = [$productIds];
        }
        
        $this->product_ids = $productIds;
        $this->save();
        
        return $this;
    }

    /**
     * إزالة المنتجات من مقدم الخدمة
     */
    public function detachProducts($productIds = null)
    {
        if ($productIds === null) {
            // إزالة جميع المنتجات
            $this->product_ids = [];
        } else {
            if (!is_array($productIds)) {
                $productIds = [$productIds];
            }
            
            $currentIds = $this->product_ids ?? [];
            $this->product_ids = array_diff($currentIds, $productIds);
        }
        
        $this->save();
        
        return $this;
    }

    /**
     * التحقق من وجود منتج معين
     */
    public function hasProduct($productId)
    {
        return in_array($productId, $this->product_ids ?? []);
    }

    /**
     * مقدمي الخدمة النشطين
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * التحقق من توفر مقدم الخدمة في وقت معين
     */
    public function isAvailableAt($datetime, $durationMinutes = 60)
    {
        $date = Carbon::parse($datetime);
        
        // التحقق من أيام العطل
        if ($this->isHoliday($date->toDateString())) {
            return false;
        }
        
        // التحقق من ساعات العمل
        if (!$this->isWorkingAt($date)) {
            return false;
        }
        
        // التحقق من السعة والحجوزات الموجودة
        if (!$this->hasCapacityAt($date, $durationMinutes)) {
            return false;
        }
        
        return true;
    }

    /**
     * التحقق من أيام العطل من جدول العمل المرتبط
     */
    public function isHoliday($date)
    {
        if (!$this->workingSchedule || !$this->workingSchedule->holidays) {
            return false;
        }
        
        return in_array($date, $this->workingSchedule->holidays);
    }

    /**
     * التحقق من ساعات العمل
     */
    public function isWorkingAt($datetime)
    {
        $date = Carbon::parse($datetime);
        
        // التحقق من وجود جدول عمل مرتبط
        if (!$this->workingSchedule) {
            return false;
        }
        
        $workingSchedule = $this->workingSchedule;
        
        // التحقق من صلاحية الجدول للتاريخ المحدد
        if (!$workingSchedule->isValidForDate($date)) {
            return false;
        }
        
        $dayName = strtolower($date->format('l'));
        
        // تحويل أسماء الأيام للإنجليزية
        $dayMapping = [
            'saturday' => 'saturday',
            'sunday' => 'sunday', 
            'monday' => 'monday',
            'tuesday' => 'tuesday',
            'wednesday' => 'wednesday',
            'thursday' => 'thursday',
            'friday' => 'friday'
        ];
        
        $englishDay = $dayMapping[$dayName] ?? $dayName;
        
        $workingHours = $workingSchedule->getWorkingHoursForDay($englishDay);
        
        if (!$workingHours || !$workingHours['active']) {
            return false;
        }
        
        $startTime = Carbon::createFromFormat('H:i', $workingHours['start_time']);
        $endTime = Carbon::createFromFormat('H:i', $workingHours['end_time']);
        $currentTime = $date->format('H:i');
        
        return $currentTime >= $startTime->format('H:i') && 
               $currentTime <= $endTime->format('H:i');
    }

    /**
     * التحقق من السعة المتاحة
     */
    public function hasCapacityAt($datetime, $durationMinutes = 60)
    {
        $date = Carbon::parse($datetime);
        $endTime = $date->copy()->addMinutes($durationMinutes);
        
        // إضافة الحجوزات في نفس التاريخ والوقت
        $existingBookings = $this->bookings()
            ->where('booking_date', $date->toDateString())
            ->where('booking_time', $date->format('H:i'))
            ->whereIn('status', ['pending', 'confirmed', 'in_progress'])
            ->get();

        foreach ($existingBookings as $booking) {
            $startTime = Carbon::parse($booking->booking_date . ' ' . $booking->booking_time);
            $endTime = $startTime->copy()->addMinutes($booking->product->duration_minutes ?? 60);
            
            if ($endTime > $date && $startTime < $endTime) {
                return false;
            }
        }
            
        return true; // لا توجد حجوزات متداخلة
    }

    /**
     * الحصول على الحجوزات في تاريخ معين
     */
    public function getBookingsForDate($date)
    {
        return $this->bookings()
            ->whereDate('booking_date', $date)
            ->orderBy('booking_time')
            ->get();
    }

    /**
     * الحصول على الأوقات المتاحة ليوم معين
     */
    public function getAvailableSlots($date, $productId = null)
    {
        $product = $productId ? Product::find($productId) : null;
        $duration = $product ? $product->duration_minutes : 60;
        $bufferTime = $product ? $product->buffer_time_minutes : 60;
        $interval = $product ? $product->time_slot_interval_minutes : 120;
        
        $slots = [];
        $date = Carbon::parse($date);
        
        // التحقق من وجود جدول عمل مرتبط
        if (!$this->workingSchedule) {
            return $slots;
        }
        
        $workingSchedule = $this->workingSchedule;
        
        // التحقق من صلاحية الجدول للتاريخ المحدد
        if (!$workingSchedule->isValidForDate($date)) {
            return $slots;
        }
        
        $dayName = strtolower($date->format('l'));
        $dayMapping = [
            'saturday' => 'saturday',
            'sunday' => 'sunday',
            'monday' => 'monday',
            'tuesday' => 'tuesday',
            'wednesday' => 'wednesday',
            'thursday' => 'thursday',
            'friday' => 'friday'
        ];
        
        $englishDay = $dayMapping[$dayName] ?? $dayName;
        
        $workingHours = $workingSchedule->getWorkingHoursForDay($englishDay);
        
        if (!$workingHours || !$workingHours['active']) {
            return $slots;
        }
        
        $startTime = Carbon::parse($date->format('Y-m-d') . ' ' . $workingHours['start_time']);
        $endTime = Carbon::parse($date->format('Y-m-d') . ' ' . $workingHours['end_time']);
        
        $currentSlot = $startTime->copy();
        
        while ($currentSlot->addMinutes($interval)->lte($endTime->subMinutes($duration))) {
            if ($this->isAvailableAt($currentSlot, $duration)) {
                $slots[] = [
                    'datetime' => $currentSlot->copy(),
                    'formatted' => $currentSlot->format('H:i'),
                    'available' => true
                ];
            }
        }
        
        return $slots;
    }

    /**
     * التحقق من تغطية الموقع
     */
    public function coversLocation($latitude, $longitude)
    {
        // إذا كان نوع التغطية "nearest_booking" فيقبل جميع المواقع
        if ($this->coverage_type === 'nearest_booking') {
            return true;
        }
        
        // إذا لم يكن هناك coverage_area محدد، لا يغطي أي موقع
        if (!$this->coverage_area || empty($this->coverage_area)) {
            return false;
        }
        
        // التحقق من Point-in-Polygon للمضلع المحدد
        return $this->isPointInPolygon($latitude, $longitude, $this->coverage_area);
    }

    /**
     * التحقق من وجود النقطة داخل المضلع (Point-in-Polygon Algorithm)
     */
    private function isPointInPolygon($latitude, $longitude, $polygon)
    {
        try {


            // إذا كان polygon عبارة عن string، قم بتحويله إلى array
            if (is_string($polygon)) {
                $polygon = json_decode($polygon, true);
                if (!$polygon) {
                    \Log::warning('Failed to decode polygon JSON');
                    return false;
                }

            }

            // دعم أشكال مختلفة من البيانات
            $coordinates = [];
            
            if (isset($polygon['coordinates'])) {
                $coordinates = $polygon['coordinates'];
            } elseif (isset($polygon[0]) && is_array($polygon[0])) {
                $coordinates = $polygon;
            } else {
                \Log::warning('Invalid polygon format', ['polygon' => $polygon]);
                return false;
            }

            // التأكد من وجود إحداثيات صحيحة
            if (empty($coordinates)) {
                return false;
            }

            // إذا كانت coordinates متداخلة (polygon من polygons)
            if (isset($coordinates[0]) && is_array($coordinates[0]) && isset($coordinates[0][0])) {
                $coordinates = $coordinates[0]; // أخذ أول polygon
            }

            $verticesCount = count($coordinates);
            if ($verticesCount < 3) {
                return false; // يجب أن يكون على الأقل 3 نقاط للمضلع
            }

            $intersections = 0;
            
            for ($i = 0; $i < $verticesCount; $i++) {
                $vertex1 = $coordinates[$i];
                $vertex2 = $coordinates[($i + 1) % $verticesCount];
                
                // دعم أشكال مختلفة من البيانات
                $lat1 = isset($vertex1['lat']) ? $vertex1['lat'] : (isset($vertex1['latitude']) ? $vertex1['latitude'] : null);
                $lng1 = isset($vertex1['lng']) ? $vertex1['lng'] : (isset($vertex1['longitude']) ? $vertex1['longitude'] : null);
                $lat2 = isset($vertex2['lat']) ? $vertex2['lat'] : (isset($vertex2['latitude']) ? $vertex2['latitude'] : null);
                $lng2 = isset($vertex2['lng']) ? $vertex2['lng'] : (isset($vertex2['longitude']) ? $vertex2['longitude'] : null);

                // التحقق من صحة الإحداثيات
                if ($lat1 === null || $lng1 === null || $lat2 === null || $lng2 === null ||
                    !is_numeric($lat1) || !is_numeric($lng1) || !is_numeric($lat2) || !is_numeric($lng2)) {
                    \Log::warning('Invalid coordinates found', [
                        'vertex1' => $vertex1,
                        'vertex2' => $vertex2,
                        'lat1' => $lat1,
                        'lng1' => $lng1,
                        'lat2' => $lat2,
                        'lng2' => $lng2
                    ]);
                    continue;
                }

                // Ray casting algorithm
                if (($lat1 > $latitude) !== ($lat2 > $latitude)) {
                    $intersectionLng = ($lng2 - $lng1) * ($latitude - $lat1) / ($lat2 - $lat1) + $lng1;
                    if ($longitude < $intersectionLng) {
                        $intersections++;
                    }
                }
            }

            // إذا كان عدد التقاطعات فردي، فالنقطة داخل المضلع
            return ($intersections % 2) === 1;
            
        } catch (\Exception $e) {
            \Log::error('Point-in-polygon calculation error', [
                'provider_id' => $this->id,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'polygon' => $polygon,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * الحصول على المسافة من موقع معين
     */
    public function getDistanceFrom($latitude, $longitude)
    {
        if (!$this->latitude || !$this->longitude) {
            return null;
        }

        // حساب المسافة بالكيلومتر
        $earthRadius = 6371;
        
        $latFrom = deg2rad($latitude);
        $lonFrom = deg2rad($longitude);
        $latTo = deg2rad($this->latitude);
        $lonTo = deg2rad($this->longitude);
        
        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;
        
        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
            
        return $angle * $earthRadius;
    }

    /**
     * التحقق من وجود حجوزات نشطة
     */
    public function hasActiveBookings()
    {
        return $this->bookings()
            ->whereIn('status', ['pending', 'confirmed', 'in_progress'])
            ->exists();
    }

    /**
     * Accessor للتوافق مع الكود القديم - يعيد مجموعة تحتوي على جدول العمل المرتبط
     */
    public function getWorkingSchedulesAttribute()
    {
        if ($this->workingSchedule) {
            return collect([$this->workingSchedule]);
        }
        return collect();
    }

    /**
     * الحصول على coverage_area منسق بشكل صحيح
     */
    public function getCoverageAreaAttribute($value)
    {
        if (!$value) {
            return null;
        }
        
        // إذا كان string، قم بتحويله إلى array
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return $decoded ?: $value; // إذا فشل الـ decode، أرجع الـ string الأصلي
        }
        
        return $value;
    }

    /**
     * تنظيف وتنسيق coverage_area قبل الحفظ
     */
    public function setCoverageAreaAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['coverage_area'] = json_encode($value);
        } else {
            $this->attributes['coverage_area'] = $value;
        }
    }
} 