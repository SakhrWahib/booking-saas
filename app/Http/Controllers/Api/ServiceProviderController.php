<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceProviderController extends Controller
{
    /**
     * عرض قائمة مقدمي الخدمات مع فلاتر بسيطة
     */
    public function index(Request $request)
    {
        try {
            $query = ServiceProvider::query();

            // بحث بالاسم/الجوال/الإيميل
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            // فلاتر بسيطة
            if ($request->filled('is_active')) {
                $query->where('is_active', (bool) $request->get('is_active'));
            }
            if ($request->filled('work_type')) {
                $query->where('work_type', $request->get('work_type'));
            }
            if ($request->filled('coverage_type')) {
                $query->where('coverage_type', $request->get('coverage_type'));
            }
            if ($request->filled('working_schedule_id')) {
                $query->where('working_schedule_id', (int) $request->get('working_schedule_id'));
            }
            if ($request->filled('product_id')) {
                $query->whereJsonContains('product_ids', (int) $request->get('product_id'));
            }

            // ترتيب
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            $perPage = (int) $request->get('per_page', 15);
            $providers = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'تم جلب قائمة مقدمي الخدمات بنجاح',
                'data' => $providers
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب قائمة مقدمي الخدمات',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * عرض مقدم خدمة محدد
     */
    public function show($id)
    {
        try {
            $provider = ServiceProvider::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'تم جلب بيانات مقدم الخدمة بنجاح',
                'data' => $provider
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'مقدم الخدمة غير موجود',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * قائمة المقدمين النشطين فقط (حقول مختصرة)
     */
    public function activeList(Request $request)
    {
        try {
            $providers = ServiceProvider::where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->get(['id', 'name', 'phone', 'latitude', 'longitude', 'address', 'work_type', 'coverage_type', 'working_schedule_id']);

            return response()->json([
                'success' => true,
                'message' => 'تم جلب المقدمين النشطين بنجاح',
                'data' => $providers
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
     * بحث بالموقع: إرجاع أقرب المقدمين ضمن نصف قطر بالكيلومتر
     */
    public function searchByLocation(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'radius_km' => 'nullable|numeric|min:0.1'
            ], [
                'latitude.required' => 'خط العرض مطلوب',
                'longitude.required' => 'خط الطول مطلوب',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'بيانات غير صحيحة',
                    'errors' => $validator->errors()
                ], 422);
            }

            $lat = (float) $request->get('latitude');
            $lng = (float) $request->get('longitude');
            $radius = (float) ($request->get('radius_km', 10));

            // معادلة Haversine لحساب المسافة بالكيلومتر
            $haversine = "(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude))))";

            $providers = ServiceProvider::select('*')
                ->selectRaw("{$haversine} AS distance", [$lat, $lng, $lat])
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->where('is_active', true)
                ->having('distance', '<=', $radius)
                ->orderBy('distance')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'تم البحث بنجاح',
                'data' => [
                    'providers' => $providers,
                    'radius_km' => $radius,
                    'total_found' => $providers->count()
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء البحث بالموقع',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}


