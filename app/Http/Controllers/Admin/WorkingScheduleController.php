<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkingSchedule;
use App\Models\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class WorkingScheduleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * عرض قائمة جداول العمل
     */
    public function index(Request $request)
    {
        $query = WorkingSchedule::withCount('serviceProviders');

        // البحث بالاسم
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('schedule_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // فلترة حسب الحالة
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        // فلترة حسب الافتراضي
        if ($request->filled('is_default')) {
            $query->where('is_default', $request->is_default);
        }

        // فلترة حسب التاريخ
        if ($request->filled('date_from')) {
            $query->where('end_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('start_date', '<=', $request->date_to);
        }

        $workingSchedules = $query->byCreated()
                                 ->paginate(15);

        // إحصائيات للصفحة
        $stats = [
            'total' => WorkingSchedule::count(),
            'active' => WorkingSchedule::active()->count(),
            'default' => WorkingSchedule::default()->count(),
            'linked_providers' => ServiceProvider::whereNotNull('working_schedule_id')->count()
        ];

        return view('admin.working_schedules.index', compact('workingSchedules', 'stats'));
    }

    /**
     * عرض صفحة إضافة جدول عمل جديد
     */
    public function create()
    {
        // أيام الأسبوع
        $weekDays = [
            'saturday' => 'السبت',
            'sunday' => 'الأحد', 
            'monday' => 'الاثنين',
            'tuesday' => 'الثلاثاء',
            'wednesday' => 'الأربعاء',
            'thursday' => 'الخميس',
            'friday' => 'الجمعة'
        ];

        return view('admin.working_schedules.create', compact('weekDays'));
    }

    /**
     * حفظ جدول العمل الجديد
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'schedule_name' => 'required|string|max:255|unique:working_schedules,schedule_name,NULL,id,tenant_id,' . tenant_id(),
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'description' => 'nullable|string|max:1000',
            'is_default' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'holidays' => 'nullable|array',
            'holidays.*' => 'date',
            'active_days' => 'required|array',
            'active_days.*.active' => 'nullable|in:0,1',
            'active_days.*.start_time' => 'nullable|date_format:H:i',
            'active_days.*.end_time' => 'nullable|date_format:H:i',
            'vacation_periods' => 'nullable|array',
            'vacation_periods.*.name' => 'nullable|string|max:255',
            'vacation_periods.*.start_date' => 'nullable|date',
            'vacation_periods.*.end_date' => 'nullable|date|after_or_equal:vacation_periods.*.start_date',
            'vacation_periods.*.description' => 'nullable|string|max:500'
        ], [
            'schedule_name.required' => 'اسم الجدول مطلوب',
            'schedule_name.unique' => 'اسم الجدول موجود مسبقاً',
            'start_date.required' => 'تاريخ البداية مطلوب',
            'start_date.after_or_equal' => 'تاريخ البداية يجب أن يكون من اليوم فما بعد',
            'end_date.required' => 'تاريخ النهاية مطلوب',
            'end_date.after' => 'تاريخ النهاية يجب أن يكون بعد تاريخ البداية',
            'active_days.required' => 'أيام العمل مطلوبة',
            'active_days.*.start_time.date_format' => 'صيغة وقت البداية غير صحيحة',
            'active_days.*.end_time.date_format' => 'صيغة وقت النهاية غير صحيحة',
            'active_days.*.end_time.after' => 'وقت النهاية يجب أن يكون بعد وقت البداية'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        // التحقق من وجود يوم واحد على الأقل نشط
        $hasActiveDay = false;
        if ($request->has('active_days') && is_array($request->active_days)) {
            foreach ($request->active_days as $dayData) {
                if (isset($dayData['active']) && $dayData['active'] == '1') {
                    $hasActiveDay = true;
                    break;
                }
            }
        }

        if (!$hasActiveDay) {
            return redirect()->back()
                           ->withErrors(['active_days' => 'يجب تحديد يوم واحد على الأقل للعمل'])
                           ->withInput();
        }

        try {
            DB::beginTransaction();

            // إذا كان هذا الجدول افتراضي، إلغاء الافتراضي من الباقي
            if ($request->is_default) {
                WorkingSchedule::where('is_default', true)->update(['is_default' => false]);
            }

            // تحضير بيانات أيام العمل
            $activeDays = [];
            if ($request->has('active_days') && is_array($request->active_days)) {
                foreach ($request->active_days as $day => $dayData) {
                    $isActive = isset($dayData['active']) && $dayData['active'] == '1';
                    $activeDays[$day] = [
                        'active' => $isActive,
                        'start_time' => $isActive && isset($dayData['start_time']) ? $dayData['start_time'] : null,
                        'end_time' => $isActive && isset($dayData['end_time']) ? $dayData['end_time'] : null
                    ];
                }
            }

            // تحضير بيانات فترات الإجازة
            $vacationPeriods = [];
            if ($request->has('vacation_periods') && is_array($request->vacation_periods)) {
                foreach ($request->vacation_periods as $period) {
                    if (!empty($period['start_date']) && !empty($period['end_date'])) {
                        $vacationPeriods[] = [
                            'id' => uniqid(),
                            'name' => $period['name'] ?: 'فترة إجازة',
                            'start_date' => $period['start_date'],
                            'end_date' => $period['end_date'],
                            'description' => $period['description'] ?: '',
                            'created_at' => now()->toDateTimeString()
                        ];
                    }
                }
            }

            $workingSchedule = WorkingSchedule::create([
                'schedule_name' => $request->schedule_name,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'active_days' => $activeDays,
                'holidays' => $request->holidays ?? [],
                'vacation_periods' => $vacationPeriods,
                'description' => $request->description,
                'is_default' => (bool)$request->is_default,
                'is_active' => (bool)($request->is_active ?? true)
            ]);

            // إنشاء الأوقات الافتراضية للجدول الجديد
            $workingSchedule->createDefaultTimeSlots();

            DB::commit();

            return redirect()
                ->route('admin.working-schedules.show', $workingSchedule)
                ->with('success', 'تم إنشاء جدول العمل بنجاح');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating working schedule: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء إنشاء جدول العمل. يرجى المحاولة مرة أخرى.');
        }
    }

    /**
     * عرض تفاصيل جدول العمل
     */
    public function show(WorkingSchedule $workingSchedule)
    {
        $workingSchedule->load(['serviceProviders' => function($query) {
            $query->select('id', 'name', 'phone', 'working_schedule_id', 'is_active')
                  ->latest()
                  ->take(10);
        }, 'activeTimeSlots']);

        // إحصائيات هذا الجدول
        $stats = [
            'linked_providers' => $workingSchedule->serviceProviders()->count(),
            'active_providers' => $workingSchedule->serviceProviders()->where('is_active', true)->count(),
            'working_days' => collect($workingSchedule->active_days)->where('active', true)->count(),
            'holidays_count' => $workingSchedule->holidays ? count($workingSchedule->holidays) : 0,
            'time_slots_count' => $workingSchedule->getAvailableTimeSlotsCount()
        ];

        // أيام الأسبوع للعرض
        $weekDays = [
            'saturday' => 'السبت',
            'sunday' => 'الأحد', 
            'monday' => 'الاثنين',
            'tuesday' => 'الثلاثاء',
            'wednesday' => 'الأربعاء',
            'thursday' => 'الخميس',
            'friday' => 'الجمعة'
        ];

        return view('admin.working_schedules.show', compact('workingSchedule', 'stats', 'weekDays'));
    }

    /**
     * عرض صفحة تعديل جدول العمل
     */
    public function edit(WorkingSchedule $workingSchedule)
    {
        // أيام الأسبوع
        $weekDays = [
            'saturday' => 'السبت',
            'sunday' => 'الأحد', 
            'monday' => 'الاثنين',
            'tuesday' => 'الثلاثاء',
            'wednesday' => 'الأربعاء',
            'thursday' => 'الخميس',
            'friday' => 'الجمعة'
        ];

        return view('admin.working_schedules.edit', compact('workingSchedule', 'weekDays'));
    }

    /**
     * تحديث جدول العمل
     */
    public function update(Request $request, WorkingSchedule $workingSchedule)
    {
        $validator = Validator::make($request->all(), [
            'schedule_name' => 'required|string|max:255|unique:working_schedules,schedule_name,' . $workingSchedule->id . ',id,tenant_id,' . tenant_id(),
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'description' => 'nullable|string|max:1000',
            'is_default' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'holidays' => 'nullable|array',
            'holidays.*' => 'date',
            'active_days' => 'required|array',
            'active_days.*.active' => 'nullable|in:0,1',
            'active_days.*.start_time' => 'nullable|date_format:H:i',
            'active_days.*.end_time' => 'nullable|date_format:H:i',
            'vacation_periods' => 'nullable|array',
            'vacation_periods.*.name' => 'nullable|string|max:255',
            'vacation_periods.*.start_date' => 'nullable|date',
            'vacation_periods.*.end_date' => 'nullable|date|after_or_equal:vacation_periods.*.start_date',
            'vacation_periods.*.description' => 'nullable|string|max:500'
        ], [
            'schedule_name.required' => 'اسم الجدول مطلوب',
            'schedule_name.unique' => 'اسم الجدول موجود مسبقاً',
            'start_date.required' => 'تاريخ البداية مطلوب',
            'end_date.required' => 'تاريخ النهاية مطلوب',
            'end_date.after' => 'تاريخ النهاية يجب أن يكون بعد تاريخ البداية',
            'active_days.required' => 'أيام العمل مطلوبة',
            'active_days.*.start_time.date_format' => 'صيغة وقت البداية غير صحيحة',
            'active_days.*.end_time.date_format' => 'صيغة وقت النهاية غير صحيحة',
            'active_days.*.end_time.after' => 'وقت النهاية يجب أن يكون بعد وقت البداية'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        // التحقق من وجود يوم واحد على الأقل نشط
        $hasActiveDay = false;
        if ($request->has('active_days') && is_array($request->active_days)) {
            foreach ($request->active_days as $dayData) {
                if (isset($dayData['active']) && $dayData['active'] == '1') {
                    $hasActiveDay = true;
                    break;
                }
            }
        }

        if (!$hasActiveDay) {
            return redirect()->back()
                           ->withErrors(['active_days' => 'يجب تحديد يوم واحد على الأقل للعمل'])
                           ->withInput();
        }

        try {
            DB::beginTransaction();

            // إذا كان هذا الجدول افتراضي، إلغاء الافتراضي من الباقي
            if ($request->is_default && !$workingSchedule->is_default) {
                WorkingSchedule::where('is_default', true)->update(['is_default' => false]);
            }

            // تحضير بيانات أيام العمل
            $activeDays = [];
            if ($request->has('active_days') && is_array($request->active_days)) {
                foreach ($request->active_days as $day => $dayData) {
                    $isActive = isset($dayData['active']) && $dayData['active'] == '1';
                    $activeDays[$day] = [
                        'active' => $isActive,
                        'start_time' => $isActive && isset($dayData['start_time']) ? $dayData['start_time'] : null,
                        'end_time' => $isActive && isset($dayData['end_time']) ? $dayData['end_time'] : null
                    ];
                }
            }

            // تحضير بيانات فترات الإجازة - الاحتفاظ بالـ ID الموجود أو إنشاء جديد
            $vacationPeriods = [];
            if ($request->has('vacation_periods') && is_array($request->vacation_periods)) {
                foreach ($request->vacation_periods as $period) {
                    if (!empty($period['start_date']) && !empty($period['end_date'])) {
                        $vacationPeriods[] = [
                            'id' => isset($period['id']) ? $period['id'] : uniqid(),
                            'name' => $period['name'] ?: 'فترة إجازة',
                            'start_date' => $period['start_date'],
                            'end_date' => $period['end_date'],
                            'description' => $period['description'] ?: '',
                            'created_at' => isset($period['created_at']) ? $period['created_at'] : now()->toDateTimeString()
                        ];
                    }
                }
            }

            $workingSchedule->update([
                'schedule_name' => $request->schedule_name,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'active_days' => $activeDays,
                'holidays' => $request->holidays ?? [],
                'vacation_periods' => $vacationPeriods,
                'description' => $request->description,
                'is_default' => (bool)$request->is_default,
                'is_active' => (bool)($request->is_active ?? true)
            ]);

            DB::commit();

            return redirect()
                ->route('admin.working-schedules.show', $workingSchedule)
                ->with('success', 'تم تحديث جدول العمل بنجاح');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating working schedule: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء تحديث جدول العمل. يرجى المحاولة مرة أخرى.');
        }
    }

    /**
     * حذف جدول العمل
     */
    public function destroy(WorkingSchedule $workingSchedule)
    {
        try {
            // التحقق من وجود مقدمي خدمة مرتبطين
            if ($workingSchedule->serviceProviders()->count() > 0) {
                return redirect()->back()
                               ->with('error', 'لا يمكن حذف جدول العمل لوجود مقدمي خدمة مرتبطين به');
            }

            // لا يمكن حذف الجدول الافتراضي إذا كان الوحيد
            if ($workingSchedule->is_default && WorkingSchedule::count() > 1) {
                return redirect()->back()
                               ->with('error', 'يجب تعيين جدول آخر كافتراضي قبل حذف هذا الجدول');
            }

            $workingSchedule->delete();

            return redirect()
                ->route('admin.working-schedules.index')
                ->with('success', 'تم حذف جدول العمل بنجاح');

        } catch (\Exception $e) {
            Log::error('Error deleting working schedule: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء حذف جدول العمل');
        }
    }

    /**
     * تبديل حالة النشاط
     */
    public function toggleStatus(WorkingSchedule $workingSchedule)
    {
        try {
            $workingSchedule->update([
                'is_active' => !$workingSchedule->is_active
            ]);

            $status = $workingSchedule->is_active ? 'تم تفعيل' : 'تم إلغاء تفعيل';

            return redirect()->back()
                           ->with('success', $status . ' جدول العمل بنجاح');

        } catch (\Exception $e) {
            Log::error('Error toggling working schedule status: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء تغيير حالة جدول العمل');
        }
    }

    /**
     * تعيين كافتراضي
     */
    public function setDefault(WorkingSchedule $workingSchedule)
    {
        try {
            DB::beginTransaction();

            // إلغاء الافتراضي من جميع الجداول
            WorkingSchedule::where('is_default', true)->update(['is_default' => false]);

            // تعيين هذا الجدول كافتراضي
            $workingSchedule->update(['is_default' => true, 'is_active' => true]);

            DB::commit();

            return redirect()->back()
                           ->with('success', 'تم تعيين جدول العمل كافتراضي بنجاح');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error setting default working schedule: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء تعيين جدول العمل كافتراضي');
        }
    }

    /**
     * نسخ جدول العمل
     */
    public function duplicate(WorkingSchedule $workingSchedule)
    {
        try {
            $newSchedule = $workingSchedule->replicate();
            $newSchedule->schedule_name = $workingSchedule->schedule_name . ' - نسخة';
            $newSchedule->is_default = false;
            $newSchedule->save();

            return redirect()
                ->route('admin.working-schedules.edit', $newSchedule)
                ->with('success', 'تم نسخ جدول العمل بنجاح. يمكنك الآن تعديله');

        } catch (\Exception $e) {
            Log::error('Error duplicating working schedule: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء نسخ جدول العمل');
        }
    }

    /**
     * جلب الجداول النشطة (Ajax)
     */
    public function getActive(Request $request)
    {
        $date = $request->get('date', now()->toDateString());
        
        $schedules = WorkingSchedule::active()
                                   ->forDate($date)
                                   ->byCreated()
                                   ->get(['id', 'schedule_name', 'start_date', 'end_date']);

        return response()->json($schedules);
    }

    /**
     * عرض صفحة إدارة الأوقات المتاحة
     */
    public function manageTimeSlots(WorkingSchedule $workingSchedule)
    {
        $workingSchedule->load('activeTimeSlots');
        
        // تجميع الأوقات حسب الفترة
        $timeSlotsByPeriod = $workingSchedule->getTimesGroupedByPeriod();
        
        return view('admin.working_schedules.time_slots', compact('workingSchedule', 'timeSlotsByPeriod'));
    }

    /**
     * تحديث الأوقات المتاحة
     */
    public function updateTimeSlots(Request $request, WorkingSchedule $workingSchedule)
    {
        $request->validate([
            'time_slots' => 'required|array|min:1',
            'time_slots.*.time' => 'required|date_format:H:i',
            'time_slots.*.period' => 'required|in:morning,afternoon,evening',
            'time_slots.*.label' => 'nullable|string|max:50',
            'time_slots.*.is_active' => 'nullable|boolean',
            'time_slots.*.id' => 'nullable|exists:time_slots,id,tenant_id,' . tenant_id()
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->time_slots as $index => $timeSlotData) {
                $data = [
                    'working_schedule_id' => $workingSchedule->id,
                    'time' => $timeSlotData['time'],
                    'period' => $timeSlotData['period'],
                    'label' => $timeSlotData['label'] ?? null,
                    'is_active' => isset($timeSlotData['is_active']) ? (bool)$timeSlotData['is_active'] : true,
                    'sort_order' => $index
                ];

                if (isset($timeSlotData['id']) && $timeSlotData['id']) {
                    // تحديث الوقت الموجود
                    $timeSlot = \App\Models\TimeSlot::find($timeSlotData['id']);
                    if ($timeSlot && $timeSlot->working_schedule_id == $workingSchedule->id) {
                        $timeSlot->update($data);
                    }
                } else {
                    // إنشاء وقت جديد
                    \App\Models\TimeSlot::create($data);
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'تم تحديث الأوقات المتاحة بنجاح');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating time slots: ' . $e->getMessage());
            
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث الأوقات المتاحة');
        }
    }

    /**
     * إضافة وقت جديد
     */
    public function addTimeSlot(Request $request, WorkingSchedule $workingSchedule)
    {
        $request->validate([
            'time' => 'required|date_format:H:i',
            'period' => 'required|in:morning,afternoon,evening',
            'label' => 'nullable|string|max:50'
        ]);

        try {
            // التحقق من عدم وجود وقت مشابه
            $existingSlot = \App\Models\TimeSlot::where('working_schedule_id', $workingSchedule->id)
                                               ->where('time', $request->time)
                                               ->first();

            if ($existingSlot) {
                return response()->json([
                    'success' => false,
                    'message' => 'هذا الوقت موجود مسبقاً'
                ]);
            }

            // الحصول على آخر ترتيب
            $lastOrder = \App\Models\TimeSlot::where('working_schedule_id', $workingSchedule->id)
                                             ->max('sort_order') ?? 0;

            $timeSlot = \App\Models\TimeSlot::create([
                'working_schedule_id' => $workingSchedule->id,
                'time' => $request->time,
                'period' => $request->period,
                'label' => $request->label,
                'is_active' => true,
                'sort_order' => $lastOrder + 1
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة الوقت بنجاح',
                'time_slot' => $timeSlot
            ]);

        } catch (\Exception $e) {
            Log::error('Error adding time slot: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إضافة الوقت'
            ]);
        }
    }

    /**
     * حذف وقت معين
     */
    public function deleteTimeSlot(WorkingSchedule $workingSchedule, \App\Models\TimeSlot $timeSlot)
    {
        try {
            // التحقق من أن الوقت ينتمي لهذا الجدول
            if ($timeSlot->working_schedule_id != $workingSchedule->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'الوقت غير موجود في هذا الجدول'
                ]);
            }

            // التحقق من عدم وجود حجوزات مرتبطة بهذا الوقت
            $hasBookings = \App\Models\Booking::where('booking_time', $timeSlot->formatted_time)
                                             ->whereHas('serviceProvider', function($query) use ($workingSchedule) {
                                                 $query->where('working_schedule_id', $workingSchedule->id);
                                             })
                                             ->exists();

            if ($hasBookings) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا يمكن حذف هذا الوقت لوجود حجوزات مرتبطة به'
                ]);
            }

            $timeSlot->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف الوقت بنجاح'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting time slot: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف الوقت'
            ]);
        }
    }

    /**
     * إعادة ترتيب الأوقات
     */
    public function updateTimeSlotsOrder(Request $request, WorkingSchedule $workingSchedule)
    {
        $request->validate([
            'time_slots' => 'required|array',
            'time_slots.*.id' => 'required|exists:time_slots,id,tenant_id,' . tenant_id(),
            'time_slots.*.sort_order' => 'required|integer|min:0'
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->time_slots as $timeSlotData) {
                $timeSlot = \App\Models\TimeSlot::find($timeSlotData['id']);
                if ($timeSlot && $timeSlot->working_schedule_id == $workingSchedule->id) {
                    $timeSlot->update(['sort_order' => $timeSlotData['sort_order']]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم إعادة ترتيب الأوقات بنجاح'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error reordering time slots: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إعادة ترتيب الأوقات'
            ]);
        }
    }
} 