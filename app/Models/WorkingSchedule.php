<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkingSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_name',
        'start_date',
        'end_date',
        'active_days',
        'holidays',
        'vacation_periods',
        'description',
        'is_default',
        'is_active'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'active_days' => 'array',
        'holidays' => 'array',
        'vacation_periods' => 'array',
        'is_default' => 'boolean',
        'is_active' => 'boolean'
    ];

    /**
     * العلاقة مع مقدمي الخدمة المرتبطين بهذا الجدول
     */
    public function serviceProviders()
    {
        return $this->hasMany(ServiceProvider::class, 'working_schedule_id');
    }

    /**
     * العلاقة مع الأوقات المتاحة
     */
    public function timeSlots()
    {
        return $this->hasMany(TimeSlot::class);
    }

    /**
     * الحصول على الأوقات النشطة فقط
     */
    public function activeTimeSlots()
    {
        return $this->hasMany(TimeSlot::class)->active()->ordered();
    }

    /**
     * الجداول النشطة
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * الجداول الافتراضية
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * البحث حسب التاريخ
     */
    public function scopeForDate($query, $date)
    {
        $date = is_string($date) ? \Carbon\Carbon::parse($date) : $date;
        
        return $query->where('start_date', '<=', $date->toDateString())
                     ->where('end_date', '>=', $date->toDateString());
    }

    /**
     * ترتيب حسب تاريخ الإنشاء
     */
    public function scopeByCreated($query, $direction = 'desc')
    {
        return $query->orderBy('created_at', $direction);
    }

    /**
     * التحقق من صلاحية الجدول في تاريخ معين
     */
    public function isValidForDate($date)
    {
        $date = is_string($date) ? \Carbon\Carbon::parse($date) : $date;
        
        return $date->between($this->start_date, $this->end_date) && $this->is_active;
    }

    /**
     * الحصول على ساعات العمل ليوم معين
     */
    public function getWorkingHoursForDay($dayName)
    {
        $dayName = strtolower($dayName);
        
        if (!isset($this->active_days[$dayName])) {
            return null;
        }
        
        $daySchedule = $this->active_days[$dayName];
        
        if (!$daySchedule['active']) {
            return null;
        }
        
        return [
            'start_time' => $daySchedule['start_time'],
            'end_time' => $daySchedule['end_time'],
            'active' => $daySchedule['active']
        ];
    }

    /**
     * التحقق من وجود عطلة في تاريخ معين
     */
    public function isHoliday($date)
    {
        if (!$this->holidays) {
            return false;
        }
        
        $dateString = is_string($date) ? $date : $date->format('Y-m-d');
        return in_array($dateString, $this->holidays);
    }

    /**
     * الحصول على عدد مقدمي الخدمة المرتبطين
     */
    public function getServiceProvidersCountAttribute()
    {
        return $this->serviceProviders()->count();
    }

    /**
     * التحقق من وجود إجازة في تاريخ معين (فترات الإجازة)
     */
    public function isVacationPeriod($date)
    {
        if (!$this->vacation_periods || !is_array($this->vacation_periods)) {
            return false;
        }
        
        $checkDate = is_string($date) ? \Carbon\Carbon::parse($date) : $date;
        
        foreach ($this->vacation_periods as $period) {
            if (!isset($period['start_date']) || !isset($period['end_date'])) {
                continue;
            }
            
            $startDate = \Carbon\Carbon::parse($period['start_date']);
            $endDate = \Carbon\Carbon::parse($period['end_date']);
            
            if ($checkDate->between($startDate, $endDate)) {
                return $period; // إرجاع بيانات الإجازة
            }
        }
        
        return false;
    }

    /**
     * التحقق من صلاحية التاريخ مع مراعاة فترات الإجازة بأولوية عالية
     */
    public function isValidWorkingDate($date)
    {
        $date = is_string($date) ? \Carbon\Carbon::parse($date) : $date;
        
        // التحقق من صلاحية الجدول للتاريخ المحدد
        if (!$this->isValidForDate($date)) {
            return false;
        }
        
        // التحقق من فترات الإجازة أولاً (أولوية عالية)
        if ($this->isVacationPeriod($date)) {
            return false;
        }
        
        // التحقق من العطل العادية
        if ($this->isHoliday($date)) {
            return false;
        }
        
        // التحقق من أيام العمل
        $dayName = strtolower($date->format('l'));
        $activeDays = $this->active_days;
        
        if (!isset($activeDays[$dayName]) || !$activeDays[$dayName]['active']) {
            return false;
        }
        
        return true;
    }

    /**
     * إضافة فترة إجازة جديدة
     */
    public function addVacationPeriod($startDate, $endDate, $name = null, $description = null)
    {
        $vacationPeriods = $this->vacation_periods ?: [];
        
        $newPeriod = [
            'id' => uniqid(),
            'name' => $name ?: 'فترة إجازة',
            'start_date' => $startDate,
            'end_date' => $endDate,
            'description' => $description,
            'created_at' => now()->toDateTimeString()
        ];
        
        $vacationPeriods[] = $newPeriod;
        
        $this->update(['vacation_periods' => $vacationPeriods]);
        
        return $newPeriod;
    }

    /**
     * حذف فترة إجازة
     */
    public function removeVacationPeriod($periodId)
    {
        $vacationPeriods = $this->vacation_periods ?: [];
        
        $filteredPeriods = array_filter($vacationPeriods, function($period) use ($periodId) {
            return isset($period['id']) && $period['id'] !== $periodId;
        });
        
        $this->update(['vacation_periods' => array_values($filteredPeriods)]);
        
        return $this;
    }

    /**
     * الحصول على فترات الإجازة النشطة
     */
    public function getActiveVacationPeriods($date = null)
    {
        if (!$this->vacation_periods || !is_array($this->vacation_periods)) {
            return collect();
        }
        
        $checkDate = $date ? \Carbon\Carbon::parse($date) : now();
        
        return collect($this->vacation_periods)->filter(function($period) use ($checkDate) {
            if (!isset($period['start_date']) || !isset($period['end_date'])) {
                return false;
            }
            
            $startDate = \Carbon\Carbon::parse($period['start_date']);
            $endDate = \Carbon\Carbon::parse($period['end_date']);
            
            return $checkDate->between($startDate, $endDate);
        });
    }

    /**
     * الحصول على جميع فترات الإجازة المستقبلية
     */
    public function getUpcomingVacationPeriods()
    {
        if (!$this->vacation_periods || !is_array($this->vacation_periods)) {
            return collect();
        }
        
        $today = now();
        
        return collect($this->vacation_periods)->filter(function($period) use ($today) {
            if (!isset($period['start_date'])) {
                return false;
            }
            
            $startDate = \Carbon\Carbon::parse($period['start_date']);
            return $startDate->gte($today);
                 })->sortBy('start_date');
     }

    /**
     * الحصول على الأوقات المتاحة بصيغة مصفوفة
     */
    public function getAvailableTimesArray()
    {
        return $this->activeTimeSlots->pluck('formatted_time')->toArray();
    }

    /**
     * الحصول على الأوقات المجمعة حسب الفترة
     */
    public function getTimesGroupedByPeriod()
    {
        return $this->activeTimeSlots->groupBy('period');
    }

    /**
     * إنشاء الأوقات الافتراضية عند إنشاء جدول عمل جديد
     */
    public function createDefaultTimeSlots()
    {
        TimeSlot::createDefaultTimesForSchedule($this->id);
    }

    /**
     * نسخ الأوقات من جدول عمل آخر
     */
    public function copyTimeSlotsFrom($sourceScheduleId)
    {
        TimeSlot::copyTimesFromSchedule($sourceScheduleId, $this->id);
    }

    /**
     * التحقق من وجود أوقات متاحة
     */
    public function hasAvailableTimeSlots()
    {
        return $this->activeTimeSlots()->exists();
    }

    /**
     * الحصول على عدد الأوقات المتاحة
     */
    public function getAvailableTimeSlotsCount()
    {
        return $this->activeTimeSlots()->count();
    }
}  