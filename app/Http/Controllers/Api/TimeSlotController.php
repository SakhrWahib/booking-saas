<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TimeSlot;
use Illuminate\Http\Request;

class TimeSlotController extends Controller
{
    /**
     * قائمة الأوقات مع فلاتر بسيطة
     */
    public function index(Request $request)
    {
        try {
            $query = TimeSlot::query();

            if ($request->filled('working_schedule_id')) {
                $query->where('working_schedule_id', (int) $request->get('working_schedule_id'));
            }

            if ($request->boolean('active', null) !== null) {
                $query->where('is_active', $request->boolean('active'));
            }

            if ($request->filled('period')) {
                $query->where('period', $request->get('period'));
            }

            $query->orderBy($request->get('sort_by', 'sort_order'))
                  ->orderBy('time', $request->get('sort_order', 'asc'));

            // group_by_period=1 => تجميع حسب الفترة
            if ($request->boolean('group_by_period')) {
                $items = $query->get();
                return response()->json([
                    'success' => true,
                    'message' => 'تم جلب الأوقات وتجميعها حسب الفترة بنجاح',
                    'data' => $items->groupBy('period')
                ], 200);
            }

            $perPage = (int) $request->get('per_page', 50);
            $items = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'تم جلب قائمة الأوقات بنجاح',
                'data' => $items
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء الجلب',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * تفاصيل وقت محدد
     */
    public function show($id)
    {
        try {
            $slot = TimeSlot::findOrFail($id);
            return response()->json([
                'success' => true,
                'message' => 'تم جلب بيانات الوقت بنجاح',
                'data' => $slot
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'الوقت غير موجود',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * أوقات جدول عمل معيّن
     */
    public function listBySchedule(Request $request, $workingScheduleId)
    {
        try {
            $query = TimeSlot::where('working_schedule_id', (int) $workingScheduleId);

            if ($request->boolean('active', null) !== null) {
                $query->where('is_active', $request->boolean('active'));
            }
            if ($request->filled('period')) {
                $query->where('period', $request->get('period'));
            }

            $query->orderBy('sort_order')->orderBy('time');

            if ($request->boolean('group_by_period')) {
                $items = $query->get();
                return response()->json([
                    'success' => true,
                    'message' => 'تم جلب الأوقات وتجميعها حسب الفترة بنجاح',
                    'data' => $items->groupBy('period')
                ], 200);
            }

            $items = $query->get();
            return response()->json([
                'success' => true,
                'message' => 'تم جلب أوقات جدول العمل بنجاح',
                'data' => $items
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء الجلب',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * الأوقات النشطة فقط لجدول عمل معيّن
     */
    public function activeBySchedule($workingScheduleId)
    {
        try {
            $items = TimeSlot::where('working_schedule_id', (int) $workingScheduleId)
                ->active()
                ->ordered()
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'تم جلب الأوقات النشطة بنجاح',
                'data' => $items
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء الجلب',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}


