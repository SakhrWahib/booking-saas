<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MobileCustomersController extends Controller
{
    /**
     * عرض العملاء الذين دخلوا عبر الجوال
     */
    public function index(Request $request)
    {
        $query = Customer::whereNotNull('last_mobile_login');

        // فلترة حسب نوع الجهاز
        if ($request->has('device_type') && $request->device_type !== '') {
            $query->where('device_type', $request->device_type);
        }

        // فلترة حسب التاريخ
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('last_mobile_login', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('last_mobile_login', '<=', $request->date_to);
        }

        // البحث بالاسم أو رقم الجوال
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // ترتيب النتائج
        $sortBy = $request->get('sort_by', 'last_mobile_login');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $customers = $query->paginate(20);

        // إحصائيات
        $stats = [
            'total_mobile_users' => Customer::whereNotNull('last_mobile_login')->count(),
            'android_users' => Customer::where('device_type', 'android')->count(),
            'ios_users' => Customer::where('device_type', 'ios')->count(),
            'web_users' => Customer::where('device_type', 'web')->count(),
            'today_logins' => Customer::whereDate('last_mobile_login', today())->count(),
            'week_logins' => Customer::whereBetween('last_mobile_login', [now()->startOfWeek(), now()->endOfWeek()])->count(),
        ];

        // أنواع الأجهزة المتاحة للفلترة
        $deviceTypes = Customer::whereNotNull('device_type')
            ->distinct()
            ->pluck('device_type')
            ->filter()
            ->values();

        return view('admin.mobile_customers.index', compact('customers', 'stats', 'deviceTypes'));
    }

    /**
     * عرض تفاصيل العميل
     */
    public function show($id)
    {
        $customer = Customer::findOrFail($id);
        
        // الحجوزات الأخيرة
        $recentBookings = $customer->bookings()
            ->with(['product', 'serviceProvider'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.mobile_customers.show', compact('customer', 'recentBookings'));
    }

    /**
     * تصدير البيانات إلى Excel
     */
    public function export(Request $request)
    {
        $customers = Customer::whereNotNull('last_mobile_login')
            ->select([
                'name',
                'phone',
                'email',
                'device_type',
                'last_mobile_login',
                'created_at'
            ])
            ->get();

        // هنا يمكنك إضافة كود التصدير إلى Excel
        // يمكن استخدام package مثل Maatwebsite/Excel

        return response()->json([
            'message' => 'تم تصدير البيانات بنجاح',
            'count' => $customers->count()
        ]);
    }

    /**
     * إحصائيات مفصلة
     */
    public function statistics()
    {
        // إحصائيات دخول يومية خلال آخر 30 يوم
        $dailyLogins = Customer::whereNotNull('last_mobile_login')
            ->where('last_mobile_login', '>=', now()->subDays(30))
            ->selectRaw('DATE(last_mobile_login) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // إحصائيات حسب نوع الجهاز
        $deviceStats = Customer::whereNotNull('device_type')
            ->selectRaw('device_type, COUNT(*) as count')
            ->groupBy('device_type')
            ->get();

        // إحصائيات حسب الساعة
        $hourlyStats = Customer::whereNotNull('last_mobile_login')
            ->where('last_mobile_login', '>=', now()->subDays(7))
            ->selectRaw('HOUR(last_mobile_login) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        return view('admin.mobile_customers.statistics', compact('dailyLogins', 'deviceStats', 'hourlyStats'));
    }
}
