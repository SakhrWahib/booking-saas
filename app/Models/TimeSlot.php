<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TimeSlot extends Model
{
    use AppModelsConcernsBelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'working_schedule_id',
        'time',
        'label',
        'period',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'time' => 'datetime:H:i',
        'is_active' => 'boolean'
    ];

    /**
     * العلاقة مع جدول العمل
     */
    public function workingSchedule()
    {
        return $this->belongsTo(WorkingSchedule::class);
    }

    /**
     * الحصول على الأوقات النشطة فقط
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * ترتيب الأوقات
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('time');
    }

    /**
     * تجميع حسب الفترة
     */
    public function scopeByPeriod($query, $period)
    {
        return $query->where('period', $period);
    }

    /**
     * الحصول على الوقت بصيغة منسقة
     */
    public function getFormattedTimeAttribute()
    {
        return Carbon::parse($this->time)->format('H:i');
    }

    /**
     * الحصول على الوقت بصيغة 12 ساعة
     */
    public function getFormattedTime12Attribute()
    {
        return Carbon::parse($this->time)->format('h:i A');
    }

    /**
     * الحصول على تسمية الفترة باللغة العربية
     */
    public function getPeriodLabelAttribute()
    {
        $periods = [
            'morning' => 'الصباح',
            'afternoon' => 'بعد الظهر',
            'evening' => 'المساء'
        ];

        return $periods[$this->period] ?? $this->period;
    }

    /**
     * الحصول على الأوقات المتاحة لجدول عمل معين
     */
    public static function getAvailableTimesForSchedule($workingScheduleId)
    {
        return static::where('working_schedule_id', $workingScheduleId)
            ->active()
            ->ordered()
            ->get();
    }

    /**
     * الحصول على الأوقات المتاحة مجمعة حسب الفترة
     */
    public static function getTimesGroupedByPeriod($workingScheduleId)
    {
        return static::where('working_schedule_id', $workingScheduleId)
            ->active()
            ->ordered()
            ->get()
            ->groupBy('period');
    }

    /**
     * إنشاء الأوقات الافتراضية لجدول عمل جديد
     */
    public static function createDefaultTimesForSchedule($workingScheduleId)
    {
        $defaultTimes = [
            // الصباح
            ['time' => '11:00', 'label' => null, 'period' => 'morning', 'sort_order' => 1],
            
            // بعد الظهر
            ['time' => '13:00', 'label' => null, 'period' => 'afternoon', 'sort_order' => 2],
            ['time' => '15:00', 'label' => null, 'period' => 'afternoon', 'sort_order' => 3],
            ['time' => '17:00', 'label' => null, 'period' => 'afternoon', 'sort_order' => 4],
            
            // المساء
            ['time' => '19:00', 'label' => null, 'period' => 'evening', 'sort_order' => 5],
            ['time' => '21:00', 'label' => null, 'period' => 'evening', 'sort_order' => 6],
        ];

        foreach ($defaultTimes as $timeData) {
            static::create([
                'working_schedule_id' => $workingScheduleId,
                'time' => $timeData['time'],
                'label' => $timeData['label'],
                'period' => $timeData['period'],
                'sort_order' => $timeData['sort_order'],
                'is_active' => true
            ]);
        }
    }

    /**
     * نسخ الأوقات من جدول عمل آخر
     */
    public static function copyTimesFromSchedule($fromScheduleId, $toScheduleId)
    {
        $sourceTimeSlots = static::where('working_schedule_id', $fromScheduleId)->get();
        
        foreach ($sourceTimeSlots as $timeSlot) {
            static::create([
                'working_schedule_id' => $toScheduleId,
                'time' => $timeSlot->time,
                'label' => $timeSlot->label,
                'period' => $timeSlot->period,
                'sort_order' => $timeSlot->sort_order,
                'is_active' => $timeSlot->is_active
            ]);
        }
    }

    /**
     * تحديث ترتيب الأوقات
     */
    public static function updateSortOrder($timeSlotIds)
    {
        foreach ($timeSlotIds as $index => $timeSlotId) {
            static::where('id', $timeSlotId)->update(['sort_order' => $index + 1]);
        }
    }
}
