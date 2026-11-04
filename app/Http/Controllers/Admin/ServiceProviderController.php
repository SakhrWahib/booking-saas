<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceProvider;
use App\Models\WorkingSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ServiceProviderController extends Controller
{
    /**
     * Display a listing of service providers.
     */
    public function index(Request $request)
    {
        $query = ServiceProvider::with(['workingSchedule'])
            ->orderBy('created_at', 'desc');

        // البحث
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // فلترة حسب نوع التغطية
        if ($request->filled('coverage_type')) {
            $query->where('coverage_type', $request->coverage_type);
        }

        // فلترة حسب حالة النشاط
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $serviceProviders = $query->paginate(15);

        return view('admin.service_providers.index', compact('serviceProviders'));
    }

    /**
     * Show the form for creating a new service provider.
     */
    public function create()
    {
        // جلب جداول العمل المتاحة
        $workingSchedules = WorkingSchedule::active()->orderBy('is_default', 'desc')->orderBy('created_at', 'desc')->get();
        
        return view('admin.service_providers.create', compact('workingSchedules'));
    }

    /**
     * Store a newly created service provider.
     */
    public function store(Request $request)
    {
        if (!plan_allows('staff')) {
            return back()->withInput()->with('error', plan_limit_message('staff'));
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:service_providers,phone,NULL,id,tenant_id,' . tenant_id(),
            'email' => 'nullable|email|unique:service_providers,email,NULL,id,tenant_id,' . tenant_id(),
            'capacity' => 'required|integer|min:1|max:50',
            'work_type' => 'required|in:single_person,multiple_persons',
            'coverage_type' => 'required|in:area_based,nearest_booking',
            'coverage_area' => 'nullable|json',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'address' => 'nullable|string|max:500',
            'working_schedule_id' => 'nullable|exists:working_schedules,id,tenant_id,' . tenant_id(),
            'is_active' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            // إنشاء مقدم الخدمة
            $serviceProvider = ServiceProvider::create($validatedData);

            DB::commit();

            return redirect()
                ->route('admin.service-providers.show', $serviceProvider)
                ->with('success', 'تم إنشاء مقدم الخدمة بنجاح');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating service provider: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء إنشاء مقدم الخدمة. يرجى المحاولة مرة أخرى.');
        }
    }

    /**
     * Display the specified service provider.
     */
    public function show(ServiceProvider $serviceProvider)
    {
        $serviceProvider->load(['workingSchedule']);

        return view('admin.service_providers.show', compact('serviceProvider'));
    }

    /**
     * Show the form for editing the specified service provider.
     */
    public function edit(ServiceProvider $serviceProvider)
    {
        $serviceProvider->load(['workingSchedule']);
        
        // جلب جداول العمل المتاحة
        $workingSchedules = WorkingSchedule::active()->orderBy('is_default', 'desc')->orderBy('created_at', 'desc')->get();

        return view('admin.service_providers.edit', compact('serviceProvider', 'workingSchedules'));
    }

    /**
     * Update the specified service provider.
     */
    public function update(Request $request, ServiceProvider $serviceProvider)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => [
                'required',
                'string',
                'max:20',
                Rule::unique('service_providers')->ignore($serviceProvider->id)->where('tenant_id', tenant_id())
            ],
            'email' => [
                'nullable',
                'email',
                Rule::unique('service_providers')->ignore($serviceProvider->id)->where('tenant_id', tenant_id())
            ],
            'capacity' => 'required|integer|min:1|max:50',
            'work_type' => 'required|in:single_person,multiple_persons',
            'coverage_type' => 'required|in:area_based,nearest_booking',
            'coverage_area' => 'nullable|json',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'address' => 'nullable|string|max:500',
            'working_schedule_id' => 'nullable|exists:working_schedules,id,tenant_id,' . tenant_id(),
            'is_active' => 'boolean'
        ]);

        try {
            $serviceProvider->update($validatedData);

            return redirect()
                ->route('admin.service-providers.show', $serviceProvider)
                ->with('success', 'تم تحديث بيانات مقدم الخدمة بنجاح');

        } catch (\Exception $e) {
            Log::error('Error updating service provider: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء تحديث البيانات. يرجى المحاولة مرة أخرى.');
        }
    }

    /**
     * Remove the specified service provider from storage.
     */
    public function destroy(ServiceProvider $serviceProvider)
    {
        try {
            DB::beginTransaction();

            // التحقق من وجود حجوزات نشطة
            if ($serviceProvider->hasActiveBookings()) {
                return redirect()
                    ->back()
                    ->with('error', 'لا يمكن حذف مقدم الخدمة لوجود حجوزات نشطة مرتبطة به');
            }
            
            // حذف مقدم الخدمة
            $serviceProvider->delete();

            DB::commit();

            return redirect()
                ->route('admin.service-providers.index')
                ->with('success', 'تم حذف مقدم الخدمة بنجاح');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error deleting service provider: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء حذف مقدم الخدمة. يرجى المحاولة مرة أخرى.');
        }
    }

    /**
     * Toggle service provider status.
     */
    public function toggleStatus(ServiceProvider $serviceProvider)
    {
        try {
            $serviceProvider->update([
                'is_active' => !$serviceProvider->is_active
            ]);

            $status = $serviceProvider->is_active ? 'نشط' : 'غير نشط';
            
            return response()->json([
                'success' => true,
                'message' => "تم تغيير حالة مقدم الخدمة إلى: {$status}",
                'is_active' => $serviceProvider->is_active
            ]);

        } catch (\Exception $e) {
            Log::error('Error toggling service provider status: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تغيير الحالة'
            ], 500);
        }
    }

    /**
     * Get working schedules for selection.
     */
    public function getWorkingSchedules()
    {
        $schedules = WorkingSchedule::active()
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'schedules' => $schedules
        ]);
    }

    /**
     * Update coverage area for service provider.
     */
    public function updateCoverageArea(Request $request, ServiceProvider $serviceProvider)
    {
        $request->validate([
            'coverage_area' => 'required|json',
            'coverage_type' => 'required|in:area_based,nearest_booking'
        ]);

        try {
            $serviceProvider->update([
                'coverage_area' => $request->coverage_area,
                'coverage_type' => $request->coverage_type
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث منطقة التغطية بنجاح'
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating coverage area: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث منطقة التغطية'
            ], 500);
        }
    }

    /**
     * Check if location is within service provider coverage.
     */
    public function checkCoverage(Request $request, ServiceProvider $serviceProvider)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180'
        ]);

        try {
            $isWithinCoverage = $serviceProvider->coversLocation(
                $request->latitude,
                $request->longitude
            );

            return response()->json([
                'success' => true,
                'within_coverage' => $isWithinCoverage,
                'message' => $isWithinCoverage 
                    ? 'الموقع ضمن نطاق التغطية' 
                    : 'الموقع خارج نطاق التغطية'
            ]);

        } catch (\Exception $e) {
            Log::error('Error checking coverage: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء التحقق من التغطية'
            ], 500);
        }
    }

    /**
     * Get available time slots for a specific date.
     */
    public function getAvailableTimeSlots(Request $request, ServiceProvider $serviceProvider)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today'
        ]);

        try {
            $availableSlots = $serviceProvider->getAvailableTimeSlots($request->date);

            return response()->json([
                'success' => true,
                'available_slots' => $availableSlots
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting available time slots: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب الأوقات المتاحة'
            ], 500);
        }
    }
} 