<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WorkingSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WorkingScheduleController extends Controller
{
    /**
     * عرض قائمة جداول العمل مع البحث والتصفية
     */
    public function index(Request $request)
    {
        try {
            $query = WorkingSchedule::query();

            // البحث بالاسم والوصف
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('schedule_name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // فلترة حسب الحالة والافتراضي
            if ($request->filled('is_active')) {
                $query->where('is_active', (bool)$request->get('is_active'));
            }
            if ($request->filled('is_default')) {
                $query->where('is_default', (bool)$request->get('is_default'));
            }

            // فلترة حسب التاريخ ضمن مدى الجدول
            if ($request->filled('date_from')) {
                $query->where('end_date', '>=', $request->get('date_from'));
            }
            if ($request->filled('date_to')) {
                $query->where('start_date', '<=', $request->get('date_to'));
            }

            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            $perPage = (int) $request->get('per_page', 15);
            $schedules = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'تم جلب قائمة جداول العمل بنجاح',
                'data' => $schedules
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب قائمة جداول العمل',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * عرض جدول عمل محدد
     */
    public function show($id)
    {
        try {
            $schedule = WorkingSchedule::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'تم جلب بيانات جدول العمل بنجاح',
                'data' => $schedule
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'جدول العمل غير موجود',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * إنشاء جدول عمل جديد
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'schedule_name' => 'required|string|max:255|unique:working_schedules,schedule_name',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'description' => 'nullable|string|max:1000',
                'is_default' => 'nullable|boolean',
                'is_active' => 'nullable|boolean',
                'holidays' => 'nullable|array',
                'holidays.*' => 'date',
                'active_days' => 'required|array',
                'active_days.*.active' => 'nullable|boolean',
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
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'بيانات غير صحيحة',
                    'errors' => $validator->errors()
                ], 422);
            }

            // التحقق من وجود يوم واحد على الأقل نشط
            $hasActiveDay = false;
            foreach ((array) $request->get('active_days', []) as $dayData) {
                if (!empty($dayData['active'])) {
                    $hasActiveDay = true;
                    break;
                }
            }
            if (!$hasActiveDay) {
                return response()->json([
                    'success' => false,
                    'message' => 'يجب تحديد يوم واحد على الأقل للعمل'
                ], 422);
            }

            DB::beginTransaction();

            // إذا تم تحديد كافتراضي قم بإلغاء الافتراضي من الباقي
            if ($request->boolean('is_default')) {
                WorkingSchedule::where('is_default', true)->update(['is_default' => false]);
            }

            // تجهيز بيانات أيام العمل
            $activeDays = [];
            foreach ($request->get('active_days', []) as $day => $dayData) {
                $isActive = !empty($dayData['active']);
                $activeDays[$day] = [
                    'active' => $isActive,
                    'start_time' => $isActive && isset($dayData['start_time']) ? $dayData['start_time'] : null,
                    'end_time' => $isActive && isset($dayData['end_time']) ? $dayData['end_time'] : null,
                ];
            }

            // تجهيز فترات الإجازة
            $vacationPeriods = [];
            foreach ((array) $request->get('vacation_periods', []) as $period) {
                if (!empty($period['start_date']) && !empty($period['end_date'])) {
                    $vacationPeriods[] = [
                        'id' => uniqid(),
                        'name' => $period['name'] ?? 'فترة إجازة',
                        'start_date' => $period['start_date'],
                        'end_date' => $period['end_date'],
                        'description' => $period['description'] ?? '',
                        'created_at' => now()->toDateTimeString(),
                    ];
                }
            }

            $schedule = WorkingSchedule::create([
                'schedule_name' => $request->get('schedule_name'),
                'start_date' => $request->get('start_date'),
                'end_date' => $request->get('end_date'),
                'active_days' => $activeDays,
                'holidays' => $request->get('holidays', []),
                'vacation_periods' => $vacationPeriods,
                'description' => $request->get('description'),
                'is_default' => (bool) $request->get('is_default', false),
                'is_active' => (bool) $request->get('is_active', true),
            ]);

            // إنشاء الأوقات الافتراضية
            $schedule->createDefaultTimeSlots();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء جدول العمل بنجاح',
                'data' => $schedule
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء جدول العمل',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * تحديث جدول عمل
     */
    public function update(Request $request, $id)
    {
        try {
            $schedule = WorkingSchedule::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'schedule_name' => 'required|string|max:255|unique:working_schedules,schedule_name,' . $schedule->id,
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'description' => 'nullable|string|max:1000',
                'is_default' => 'nullable|boolean',
                'is_active' => 'nullable|boolean',
                'holidays' => 'nullable|array',
                'holidays.*' => 'date',
                'active_days' => 'required|array',
                'active_days.*.active' => 'nullable|boolean',
                'active_days.*.start_time' => 'nullable|date_format:H:i',
                'active_days.*.end_time' => 'nullable|date_format:H:i',
                'vacation_periods' => 'nullable|array',
                'vacation_periods.*.name' => 'nullable|string|max:255',
                'vacation_periods.*.start_date' => 'nullable|date',
                'vacation_periods.*.end_date' => 'nullable|date|after_or_equal:vacation_periods.*.start_date',
                'vacation_periods.*.description' => 'nullable|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'بيانات غير صحيحة',
                    'errors' => $validator->errors()
                ], 422);
            }

            // التحقق من وجود يوم واحد على الأقل نشط
            $hasActiveDay = false;
            foreach ((array) $request->get('active_days', []) as $dayData) {
                if (!empty($dayData['active'])) {
                    $hasActiveDay = true;
                    break;
                }
            }
            if (!$hasActiveDay) {
                return response()->json([
                    'success' => false,
                    'message' => 'يجب تحديد يوم واحد على الأقل للعمل'
                ], 422);
            }

            DB::beginTransaction();

            if ($request->boolean('is_default') && !$schedule->is_default) {
                WorkingSchedule::where('is_default', true)->update(['is_default' => false]);
            }

            $activeDays = [];
            foreach ($request->get('active_days', []) as $day => $dayData) {
                $isActive = !empty($dayData['active']);
                $activeDays[$day] = [
                    'active' => $isActive,
                    'start_time' => $isActive && isset($dayData['start_time']) ? $dayData['start_time'] : null,
                    'end_time' => $isActive && isset($dayData['end_time']) ? $dayData['end_time'] : null,
                ];
            }

            $vacationPeriods = [];
            foreach ((array) $request->get('vacation_periods', []) as $period) {
                if (!empty($period['start_date']) && !empty($period['end_date'])) {
                    $vacationPeriods[] = [
                        'id' => $period['id'] ?? uniqid(),
                        'name' => $period['name'] ?? 'فترة إجازة',
                        'start_date' => $period['start_date'],
                        'end_date' => $period['end_date'],
                        'description' => $period['description'] ?? '',
                        'created_at' => $period['created_at'] ?? now()->toDateTimeString(),
                    ];
                }
            }

            $schedule->update([
                'schedule_name' => $request->get('schedule_name'),
                'start_date' => $request->get('start_date'),
                'end_date' => $request->get('end_date'),
                'active_days' => $activeDays,
                'holidays' => $request->get('holidays', []),
                'vacation_periods' => $vacationPeriods,
                'description' => $request->get('description'),
                'is_default' => (bool) $request->get('is_default', $schedule->is_default),
                'is_active' => (bool) $request->get('is_active', $schedule->is_active),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث جدول العمل بنجاح',
                'data' => $schedule
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث جدول العمل',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * حذف جدول عمل
     */
    public function destroy($id)
    {
        try {
            $schedule = WorkingSchedule::findOrFail($id);

            if ($schedule->serviceProviders()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا يمكن حذف جدول العمل لوجود مقدمي خدمة مرتبطين به'
                ], 422);
            }

            if ($schedule->is_default && WorkingSchedule::count() > 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'يجب تعيين جدول آخر كافتراضي قبل حذف هذا الجدول'
                ], 422);
            }

            $schedule->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف جدول العمل بنجاح'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف جدول العمل',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * تبديل حالة النشاط
     */
    public function toggleStatus($id)
    {
        try {
            $schedule = WorkingSchedule::findOrFail($id);
            $schedule->update(['is_active' => !$schedule->is_active]);

            return response()->json([
                'success' => true,
                'message' => $schedule->is_active ? 'تم تفعيل جدول العمل بنجاح' : 'تم إلغاء تفعيل جدول العمل بنجاح',
                'data' => $schedule
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تغيير حالة جدول العمل',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * تعيين كافتراضي
     */
    public function setDefault($id)
    {
        try {
            $schedule = WorkingSchedule::findOrFail($id);

            DB::beginTransaction();
            WorkingSchedule::where('is_default', true)->update(['is_default' => false]);
            $schedule->update(['is_default' => true, 'is_active' => true]);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم تعيين جدول العمل كافتراضي بنجاح',
                'data' => $schedule
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تعيين جدول العمل كافتراضي',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * جلب الجداول النشطة لليوم/تاريخ معين
     */
    public function activeList(Request $request)
    {
        try {
            $date = $request->get('date', now()->toDateString());
            $schedules = WorkingSchedule::active()
                ->forDate($date)
                ->orderBy('created_at', 'desc')
                ->get(['id', 'schedule_name', 'start_date', 'end_date', 'is_default']);

            return response()->json([
                'success' => true,
                'message' => 'تم جلب الجداول النشطة بنجاح',
                'data' => $schedules
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب الجداول النشطة',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * فحص توفر التاريخ ضمن جدول محدد
     */
    public function checkAvailability(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'date' => 'required|date'
            ], [
                'date.required' => 'التاريخ مطلوب'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'بيانات غير صحيحة',
                    'errors' => $validator->errors()
                ], 422);
            }

            $schedule = WorkingSchedule::findOrFail($id);
            $date = Carbon::parse($request->get('date'));

            $available = $schedule->isValidWorkingDate($date);
            $isHoliday = $schedule->isHoliday($date);
            $vacation = $schedule->isVacationPeriod($date);

            $dayName = strtolower($date->format('l'));
            $workingHours = $schedule->getWorkingHoursForDay($dayName);

            return response()->json([
                'success' => true,
                'message' => 'تم فحص التوفر بنجاح',
                'data' => [
                    'available' => (bool) $available,
                    'date' => $date->toDateString(),
                    'is_holiday' => (bool) $isHoliday,
                    'vacation_period' => $vacation ?: false,
                    'working_hours' => $workingHours,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء فحص التوفر',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}


