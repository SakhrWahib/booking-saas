<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Invoice;
use App\Models\ServiceProvider;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // الفلاتر
        $filters = $this->getFilters($request);
        
        // بيانات الفلاتر للفيو
        $filterData = $this->getFilterDataForView();
        
        // الإحصائيات المالية
        $financialStats = $this->getFinancialStats($filters);
        
        // إحصائيات العملاء
        $customerStats = $this->getCustomerStats($filters);
        
        // إحصائيات المنتجات/الخدمات
        $productStats = $this->getProductStats($filters);
        
        // الإحصائيات الجغرافية
        $geographicStats = $this->getGeographicStats($filters);
        
        // بيانات الرسوم البيانية
        $chartData = $this->getChartData($filters);
        
        return view('admin.reports', compact(
            'financialStats',
            'customerStats', 
            'productStats',
            'geographicStats',
            'chartData',
            'filters',
            'filterData'
        ));
    }
    
    private function getFilters(Request $request)
    {
        return [
            'date_range' => $request->get('date_range', 'today'), // today, week, month, year, custom
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
            'service_provider_id' => $request->get('service_provider_id') ? (int)$request->get('service_provider_id') : null,
            'product_id' => $request->get('product_id') ? (int)$request->get('product_id') : null,
            'region' => $request->get('region'),
            'status' => $request->get('status')
        ];
    }
    
    private function getFinancialStats($filters)
    {
        $dateRange = $this->getDateRange($filters);
        
        // تطبيق الفلاتر الإضافية
        $query = Invoice::query();
        
        // فلتر مقدم الخدمة
        if (!empty($filters['service_provider_id'])) {
            $query->whereHas('booking.serviceProvider', function($q) use ($filters) {
                $q->where('id', $filters['service_provider_id']);
            });
        }
        
        // فلتر المنتج
        if (!empty($filters['product_id'])) {
            $query->whereHas('booking.product', function($q) use ($filters) {
                $q->where('id', $filters['product_id']);
            });
        }
        
        // فلتر المنطقة
        if (!empty($filters['region'])) {
            $query->whereHas('booking', function($q) use ($filters) {
                $this->applyRegionFilter($q, $filters['region']);
            });
        }
        
        // فلتر الحالة
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        
        // إجمالي الإيرادات للفترة المحددة (من الفواتير المدفوعة)
        $filteredRevenue = (clone $query)->where('status', 'paid')
        ->sum('total_amount');
        
        // إجمالي الإيرادات اليوم - حسب الفترة المحددة
        $todayRevenue = $this->getTodayRevenue($query, $filters);
        
        // إجمالي الإيرادات الشهر - حسب الفترة المحددة
        $monthRevenue = $this->getMonthRevenue($query, $filters);
        
        // إجمالي الإيرادات السنة - حسب الفترة المحددة
        $yearRevenue = $this->getYearRevenue($query, $filters);
        
        // متوسط قيمة الحجز (من الحجوزات المكتملة) - تطبيق الفلتر الزمني
        $bookingQuery = Booking::where('status', 'completed');
        
        // تطبيق الفلتر الزمني على متوسط قيمة الحجز
        if ($filters['date_range'] == 'today') {
            $bookingQuery->whereRaw('DATE(completed_at) = ?', [Carbon::today()->format('Y-m-d')]);
        } elseif ($filters['date_range'] == 'yesterday') {
            $bookingQuery->whereRaw('DATE(completed_at) = ?', [Carbon::yesterday()->format('Y-m-d')]);
        } elseif ($filters['date_range'] == 'last_7_days') {
            $bookingQuery->whereRaw('completed_at >= ?', [Carbon::now()->subDays(7)->format('Y-m-d')]);
        } elseif ($filters['date_range'] == 'last_30_days') {
            $bookingQuery->whereRaw('completed_at >= ?', [Carbon::now()->subDays(30)->format('Y-m-d')]);
        } elseif ($filters['date_range'] == 'month') {
            $bookingQuery->whereRaw('MONTH(completed_at) = ? AND YEAR(completed_at) = ?', [
                Carbon::now()->format('n'),
                Carbon::now()->format('Y')
            ]);
        } elseif ($filters['date_range'] == 'last_month') {
            $bookingQuery->whereRaw('MONTH(completed_at) = ? AND YEAR(completed_at) = ?', [
                Carbon::now()->subMonth()->format('n'),
                Carbon::now()->subMonth()->format('Y')
            ]);
        } elseif ($filters['date_range'] == 'year') {
            $bookingQuery->whereRaw('YEAR(completed_at) = ?', [Carbon::now()->format('Y')]);
        } elseif ($filters['date_range'] == 'last_year') {
            $bookingQuery->whereRaw('YEAR(completed_at) = ?', [Carbon::now()->subYear()->format('Y')]);
        } elseif ($filters['date_range'] == 'custom' && $filters['start_date'] && $filters['end_date']) {
            $bookingQuery->whereRaw('DATE(completed_at) BETWEEN ? AND ?', [
                $filters['start_date'],
                $filters['end_date']
            ]);
        }
        
        if (!empty($filters['service_provider_id'])) {
            $bookingQuery->where('service_provider_id', $filters['service_provider_id']);
        }
        if (!empty($filters['product_id'])) {
            $bookingQuery->where('product_id', $filters['product_id']);
        }
        if (!empty($filters['region'])) {
            $this->applyRegionFilter($bookingQuery, $filters['region']);
        }
        $avgBookingValue = $bookingQuery->avg('total_price');
        
        // الفواتير المدفوعة - تطبيق الفلتر الزمني
        $paidInvoices = (clone $query)->where('status', 'paid');
        if ($filters['date_range'] == 'today') {
            $paidInvoices->whereRaw('DATE(payment_date) = ?', [Carbon::today()->format('Y-m-d')]);
        } elseif ($filters['date_range'] == 'yesterday') {
            $paidInvoices->whereRaw('DATE(payment_date) = ?', [Carbon::yesterday()->format('Y-m-d')]);
        } elseif ($filters['date_range'] == 'last_7_days') {
            $paidInvoices->whereRaw('payment_date >= ?', [Carbon::now()->subDays(7)->format('Y-m-d')]);
        } elseif ($filters['date_range'] == 'last_30_days') {
            $paidInvoices->whereRaw('payment_date >= ?', [Carbon::now()->subDays(30)->format('Y-m-d')]);
        } elseif ($filters['date_range'] == 'month') {
            $paidInvoices->whereRaw('MONTH(payment_date) = ? AND YEAR(payment_date) = ?', [
                Carbon::now()->format('n'),
                Carbon::now()->format('Y')
            ]);
        } elseif ($filters['date_range'] == 'last_month') {
            $paidInvoices->whereRaw('MONTH(payment_date) = ? AND YEAR(payment_date) = ?', [
                Carbon::now()->subMonth()->format('n'),
                Carbon::now()->subMonth()->format('Y')
            ]);
        } elseif ($filters['date_range'] == 'year') {
            $paidInvoices->whereRaw('YEAR(payment_date) = ?', [Carbon::now()->format('Y')]);
        } elseif ($filters['date_range'] == 'last_year') {
            $paidInvoices->whereRaw('YEAR(payment_date) = ?', [Carbon::now()->subYear()->format('Y')]);
        } elseif ($filters['date_range'] == 'custom' && $filters['start_date'] && $filters['end_date']) {
            $paidInvoices->whereRaw('DATE(payment_date) BETWEEN ? AND ?', [
                $filters['start_date'],
                $filters['end_date']
            ]);
        }
        $paidInvoices = $paidInvoices->count();
        
        // الفواتير غير المدفوعة (معلقة + متأخرة + مدفوعة جزئياً) - تطبيق الفلتر الزمني
        $unpaidInvoices = (clone $query)->whereIn('status', ['unpaid', 'overdue', 'partially_paid']);
        if ($filters['date_range'] == 'today') {
            $unpaidInvoices->whereRaw('DATE(created_at) = ?', [Carbon::today()->format('Y-m-d')]);
        } elseif ($filters['date_range'] == 'yesterday') {
            $unpaidInvoices->whereRaw('DATE(created_at) = ?', [Carbon::yesterday()->format('Y-m-d')]);
        } elseif ($filters['date_range'] == 'last_7_days') {
            $unpaidInvoices->whereRaw('created_at >= ?', [Carbon::now()->subDays(7)->format('Y-m-d')]);
        } elseif ($filters['date_range'] == 'last_30_days') {
            $unpaidInvoices->whereRaw('created_at >= ?', [Carbon::now()->subDays(30)->format('Y-m-d')]);
        } elseif ($filters['date_range'] == 'month') {
            $unpaidInvoices->whereRaw('MONTH(created_at) = ? AND YEAR(created_at) = ?', [
                Carbon::now()->format('n'),
                Carbon::now()->format('Y')
            ]);
        } elseif ($filters['date_range'] == 'last_month') {
            $unpaidInvoices->whereRaw('MONTH(created_at) = ? AND YEAR(created_at) = ?', [
                Carbon::now()->subMonth()->format('n'),
                Carbon::now()->subMonth()->format('Y')
            ]);
        } elseif ($filters['date_range'] == 'year') {
            $unpaidInvoices->whereRaw('YEAR(created_at) = ?', [Carbon::now()->format('Y')]);
        } elseif ($filters['date_range'] == 'last_year') {
            $unpaidInvoices->whereRaw('YEAR(created_at) = ?', [Carbon::now()->subYear()->format('Y')]);
        } elseif ($filters['date_range'] == 'custom' && $filters['start_date'] && $filters['end_date']) {
            $unpaidInvoices->whereRaw('DATE(created_at) BETWEEN ? AND ?', [
                $filters['start_date'],
                $filters['end_date']
            ]);
        }
        $unpaidInvoices = $unpaidInvoices->count();
        
        // الحجوزات المعلقة (الإيرادات المتوقعة) - تطبيق الفلتر الزمني
        $pendingBookingsQuery = Booking::where('status', 'pending');
        
        // تطبيق الفلتر الزمني على الحجوزات المعلقة
        if ($filters['date_range'] == 'today') {
            $pendingBookingsQuery->whereRaw('DATE(created_at) = ?', [Carbon::today()->format('Y-m-d')]);
        } elseif ($filters['date_range'] == 'yesterday') {
            $pendingBookingsQuery->whereRaw('DATE(created_at) = ?', [Carbon::yesterday()->format('Y-m-d')]);
        } elseif ($filters['date_range'] == 'last_7_days') {
            $pendingBookingsQuery->whereRaw('created_at >= ?', [Carbon::now()->subDays(7)->format('Y-m-d')]);
        } elseif ($filters['date_range'] == 'last_30_days') {
            $pendingBookingsQuery->whereRaw('created_at >= ?', [Carbon::now()->subDays(30)->format('Y-m-d')]);
        } elseif ($filters['date_range'] == 'month') {
            $pendingBookingsQuery->whereRaw('MONTH(created_at) = ? AND YEAR(created_at) = ?', [
                Carbon::now()->format('n'),
                Carbon::now()->format('Y')
            ]);
        } elseif ($filters['date_range'] == 'last_month') {
            $pendingBookingsQuery->whereRaw('MONTH(created_at) = ? AND YEAR(created_at) = ?', [
                Carbon::now()->subMonth()->format('n'),
                Carbon::now()->subMonth()->format('Y')
            ]);
        } elseif ($filters['date_range'] == 'year') {
            $pendingBookingsQuery->whereRaw('YEAR(created_at) = ?', [Carbon::now()->format('Y')]);
        } elseif ($filters['date_range'] == 'last_year') {
            $pendingBookingsQuery->whereRaw('YEAR(created_at) = ?', [Carbon::now()->subYear()->format('Y')]);
        } elseif ($filters['date_range'] == 'custom' && $filters['start_date'] && $filters['end_date']) {
            $pendingBookingsQuery->whereRaw('DATE(created_at) BETWEEN ? AND ?', [
                $filters['start_date'],
                $filters['end_date']
            ]);
        }
        
        if (!empty($filters['service_provider_id'])) {
            $pendingBookingsQuery->where('service_provider_id', $filters['service_provider_id']);
        }
        if (!empty($filters['product_id'])) {
            $pendingBookingsQuery->where('product_id', $filters['product_id']);
        }
        if (!empty($filters['region'])) {
            $this->applyRegionFilter($pendingBookingsQuery, $filters['region']);
        }
        $pendingBookings = $pendingBookingsQuery->sum('total_price');
        
        // الحجوزات المكتملة اليوم
        $completedBookingsTodayQuery = Booking::whereRaw('DATE(completed_at) = ?', [
            Carbon::today()->format('Y-m-d')
        ])->where('status', 'completed');
        if (!empty($filters['service_provider_id'])) {
            $completedBookingsTodayQuery->where('service_provider_id', $filters['service_provider_id']);
        }
        if (!empty($filters['product_id'])) {
            $completedBookingsTodayQuery->where('product_id', $filters['product_id']);
        }
        if (!empty($filters['region'])) {
            $this->applyRegionFilter($completedBookingsTodayQuery, $filters['region']);
        }
        $completedBookingsToday = $completedBookingsTodayQuery->count();
        
        return [
            'today_revenue' => $todayRevenue,
            'month_revenue' => $monthRevenue,
            'year_revenue' => $yearRevenue,
            'avg_booking_value' => $avgBookingValue,
            'paid_invoices' => $paidInvoices,
            'unpaid_invoices' => $unpaidInvoices,
            'pending_bookings' => $pendingBookings,
            'completed_bookings_today' => $completedBookingsToday
        ];
    }
    
    private function getCustomerStats($filters)
    {
        $dateRange = $this->getDateRange($filters);
        
        // إجمالي العملاء المسجلين
        $totalCustomers = Customer::count();
        
        // العملاء الجدد هذا الشهر
        $newCustomersThisMonth = Customer::whereRaw('MONTH(created_at) = ? AND YEAR(created_at) = ?', [
            Carbon::now()->format('n'), // رقم الشهر
            Carbon::now()->format('Y')  // السنة
        ])->count();
        
        // العملاء النشطون (الذين لديهم حجوزات)
        $activeCustomersQuery = Customer::whereHas('bookings', function($query) use ($dateRange, $filters) {
            $query->whereBetween('created_at', $dateRange);
            
            // تطبيق الفلاتر على الحجوزات
            if (!empty($filters['service_provider_id'])) {
                $query->where('service_provider_id', $filters['service_provider_id']);
            }
            if (!empty($filters['product_id'])) {
                $query->where('product_id', $filters['product_id']);
            }
            if (!empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }
            if (!empty($filters['region'])) {
                $this->applyRegionFilter($query, $filters['region']);
            }
        });
        $activeCustomers = $activeCustomersQuery->count();
        
        // العملاء الجدد اليوم
        $newCustomersToday = Customer::whereRaw('DATE(created_at) = ?', [
            Carbon::today()->format('Y-m-d')
        ])->count();
        
        return [
            'total_customers' => $totalCustomers,
            'new_customers_this_month' => $newCustomersThisMonth,
            'active_customers' => $activeCustomers,
            'new_customers_today' => $newCustomersToday
        ];
    }
    
    private function getProductStats($filters)
    {
        $dateRange = $this->getDateRange($filters);
        
        // إحصائيات الحجوزات الأساسية
        $totalBookings = Booking::query();
        $pendingBookings = Booking::query();
        $confirmedBookings = Booking::query();
        $completedBookings = Booking::query();
        $cancelledBookings = Booking::query();
        
        // تطبيق الفلاتر على جميع الاستعلامات
        if (!empty($filters['service_provider_id'])) {
            $totalBookings->where('service_provider_id', $filters['service_provider_id']);
            $pendingBookings->where('service_provider_id', $filters['service_provider_id']);
            $confirmedBookings->where('service_provider_id', $filters['service_provider_id']);
            $completedBookings->where('service_provider_id', $filters['service_provider_id']);
            $cancelledBookings->where('service_provider_id', $filters['service_provider_id']);
        }
        if (!empty($filters['product_id'])) {
            $totalBookings->where('product_id', $filters['product_id']);
            $pendingBookings->where('product_id', $filters['product_id']);
            $confirmedBookings->where('product_id', $filters['product_id']);
            $completedBookings->where('product_id', $filters['product_id']);
            $cancelledBookings->where('product_id', $filters['product_id']);
        }
        if (!empty($filters['status'])) {
            $totalBookings->where('status', $filters['status']);
            $pendingBookings->where('status', $filters['status']);
            $confirmedBookings->where('status', $filters['status']);
            $completedBookings->where('status', $filters['status']);
            $cancelledBookings->where('status', $filters['status']);
        }
        if (!empty($filters['region'])) {
            $this->applyRegionFilter($totalBookings, $filters['region']);
            $this->applyRegionFilter($pendingBookings, $filters['region']);
            $this->applyRegionFilter($confirmedBookings, $filters['region']);
            $this->applyRegionFilter($completedBookings, $filters['region']);
            $this->applyRegionFilter($cancelledBookings, $filters['region']);
        }
        
        // الخدمات الأكثر طلباً - استعلام منفصل لتجنب مشكلة العمود الغامض
        $mostRequestedServices = Booking::select('products.name', DB::raw('COUNT(*) as booking_count'))
        ->join('products', 'bookings.product_id', '=', 'products.id')
        ->where('bookings.status', '!=', 'cancelled');
        
        // تطبيق الفلاتر على استعلام الخدمات الأكثر طلباً
        if (!empty($filters['service_provider_id'])) {
            $mostRequestedServices->where('bookings.service_provider_id', $filters['service_provider_id']);
        }
        if (!empty($filters['product_id'])) {
            $mostRequestedServices->where('bookings.product_id', $filters['product_id']);
        }
        if (!empty($filters['status'])) {
            $mostRequestedServices->where('bookings.status', $filters['status']);
        }
        if (!empty($filters['region'])) {
            $this->applyRegionFilter($mostRequestedServices, $filters['region']);
        }
        
        $mostRequestedServices = $mostRequestedServices
        ->groupBy('products.id', 'products.name')
        ->orderBy('booking_count', 'desc')
        ->limit(5)
        ->get();
        
        // تنفيذ الاستعلامات
        $totalBookingsCount = $totalBookings->count();
        $pendingBookingsCount = $pendingBookings->where('status', 'pending')->count();
        $confirmedBookingsCount = $confirmedBookings->where('status', 'confirmed')->count();
        $completedBookingsCount = $completedBookings->where('status', 'completed')->count();
        $cancelledBookingsCount = $cancelledBookings->where('status', 'cancelled')->count();
        
        return [
            'most_requested_services' => $mostRequestedServices,
            'total_bookings' => $totalBookingsCount,
            'pending_bookings' => $pendingBookingsCount,
            'confirmed_bookings' => $confirmedBookingsCount,
            'completed_bookings' => $completedBookingsCount,
            'cancelled_bookings' => $cancelledBookingsCount
        ];
    }
    
    private function getGeographicStats($filters)
    {
        $dateRange = $this->getDateRange($filters);
        
        // المناطق الأكثر طلباً للحجوزات
        $mostRequestedAreas = Booking::select(
            DB::raw('ROUND(AVG(customer_latitude), 4) as avg_lat'),
            DB::raw('ROUND(AVG(customer_longitude), 4) as avg_lng'),
            DB::raw('COUNT(*) as booking_count')
        )
        ->where('status', '!=', 'cancelled')
        ->whereNotNull('customer_latitude')
        ->whereNotNull('customer_longitude');
        
        // تطبيق الفلاتر على المناطق الأكثر طلباً
        if (!empty($filters['service_provider_id'])) {
            $mostRequestedAreas->where('service_provider_id', $filters['service_provider_id']);
        }
        if (!empty($filters['product_id'])) {
            $mostRequestedAreas->where('product_id', $filters['product_id']);
        }
        if (!empty($filters['status'])) {
            $mostRequestedAreas->where('status', $filters['status']);
        }
        if (!empty($filters['region'])) {
            $this->applyRegionFilter($mostRequestedAreas, $filters['region']);
        }
        
        $mostRequestedAreas = $mostRequestedAreas
        ->groupBy(
            DB::raw('ROUND(customer_latitude, 2)'),
            DB::raw('ROUND(customer_longitude, 2)')
        )
        ->orderBy('booking_count', 'desc')
        ->limit(5)
        ->get();
        
        // توزيع الحجوزات حسب المناطق
        $bookingsByRegion = Booking::select(
            DB::raw('CASE 
                WHEN customer_latitude BETWEEN 24.6 AND 24.8 AND customer_longitude BETWEEN 46.6 AND 46.8 THEN "الرياض المركزية"
                WHEN customer_latitude BETWEEN 24.7 AND 24.9 AND customer_longitude BETWEEN 46.6 AND 46.8 THEN "الرياض الشمالية"
                WHEN customer_latitude BETWEEN 24.5 AND 24.7 AND customer_longitude BETWEEN 46.6 AND 46.8 THEN "الرياض الجنوبية"
                WHEN customer_latitude BETWEEN 24.6 AND 24.8 AND customer_longitude BETWEEN 46.4 AND 46.6 THEN "الرياض الغربية"
                WHEN customer_latitude BETWEEN 24.6 AND 24.8 AND customer_longitude BETWEEN 46.8 AND 47.0 THEN "الرياض الشرقية"
                ELSE "مناطق أخرى"
            END as region'),
            DB::raw('COUNT(*) as booking_count')
        )
        ->where('status', '!=', 'cancelled')
        ->whereNotNull('customer_latitude')
        ->whereNotNull('customer_longitude');
        
        // تطبيق الفلاتر على توزيع المناطق
        if (!empty($filters['service_provider_id'])) {
            $bookingsByRegion->where('service_provider_id', $filters['service_provider_id']);
        }
        if (!empty($filters['product_id'])) {
            $bookingsByRegion->where('product_id', $filters['product_id']);
        }
        if (!empty($filters['status'])) {
            $bookingsByRegion->where('status', $filters['status']);
        }
        if (!empty($filters['region'])) {
            $this->applyRegionFilter($bookingsByRegion, $filters['region']);
        }
        
        $bookingsByRegion = $bookingsByRegion
        ->groupBy('region')
        ->orderBy('booking_count', 'desc')
        ->get();
        
        // متوسط المسافة للحجوزات
        $avgDistance = Booking::select(
            DB::raw('AVG(
                SQRT(
                    POW(69.1 * (customer_latitude - 24.7136), 2) + 
                    POW(69.1 * (customer_longitude - 46.6753) * COS(customer_latitude / 57.3), 2)
                )
            ) as avg_distance_km')
        )
        ->where('status', '!=', 'cancelled')
        ->whereNotNull('customer_latitude')
        ->whereNotNull('customer_longitude');
        
        // تطبيق الفلاتر على متوسط المسافة
        if (!empty($filters['service_provider_id'])) {
            $avgDistance->where('service_provider_id', $filters['service_provider_id']);
        }
        if (!empty($filters['product_id'])) {
            $avgDistance->where('product_id', $filters['product_id']);
        }
        if (!empty($filters['status'])) {
            $avgDistance->where('status', $filters['status']);
        }
        if (!empty($filters['region'])) {
            $this->applyRegionFilter($avgDistance, $filters['region']);
        }
        
        $avgDistance = $avgDistance->first();
        
        return [
            'most_requested_areas' => $mostRequestedAreas,
            'bookings_by_region' => $bookingsByRegion,
            'avg_distance' => $avgDistance->avg_distance_km ?? 0
        ];
    }
    
    private function getDateRange($filters)
    {
        switch ($filters['date_range']) {
            case 'today':
                return [
                    Carbon::today()->startOfDay(),
                    Carbon::today()->endOfDay()
                ];
            case 'yesterday':
                return [
                    Carbon::yesterday()->startOfDay(),
                    Carbon::yesterday()->endOfDay()
                ];
            case 'last_2_days':
                return [
                    Carbon::now()->subDays(2)->startOfDay(),
                    Carbon::now()->endOfDay()
                ];
            case 'last_3_days':
                return [
                    Carbon::now()->subDays(3)->startOfDay(),
                    Carbon::now()->endOfDay()
                ];
            case 'last_7_days':
                return [
                    Carbon::now()->subDays(7)->startOfDay(),
                    Carbon::now()->endOfDay()
                ];
            case 'last_30_days':
                return [
                    Carbon::now()->subDays(30)->startOfDay(),
                    Carbon::now()->endOfDay()
                ];
            case 'week':
                return [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ];
            case 'last_week':
                return [
                    Carbon::now()->subWeek()->startOfWeek(),
                    Carbon::now()->subWeek()->endOfWeek()
                ];
            case 'month':
                return [
                    Carbon::now()->startOfMonth(),
                    Carbon::now()->endOfMonth()
                ];
            case 'last_month':
                return [
                    Carbon::now()->subMonth()->startOfMonth(),
                    Carbon::now()->subMonth()->endOfMonth()
                ];
            case 'year':
                return [
                    Carbon::now()->startOfYear(),
                    Carbon::now()->endOfYear()
                ];
            case 'last_year':
                return [
                    Carbon::now()->subYear()->startOfYear(),
                    Carbon::now()->subYear()->endOfYear()
                ];
            case 'custom':
                if ($filters['start_date'] && $filters['end_date']) {
                    try {
                        $startDate = Carbon::parse($filters['start_date'])->startOfDay();
                        $endDate = Carbon::parse($filters['end_date'])->endOfDay();
                        
                        // التأكد من أن تاريخ البداية قبل تاريخ النهاية
                        if ($startDate->gt($endDate)) {
                            // إذا كان تاريخ البداية بعد تاريخ النهاية، نبدل بينهما
                            return [
                                $endDate,
                                $startDate
                            ];
                        }
                        
                        return [
                            $startDate,
                            $endDate
                        ];
                    } catch (\Exception $e) {
                        // في حالة خطأ في التاريخ، نرجع اليوم الحالي
                        return [
                            Carbon::today()->startOfDay(),
                            Carbon::today()->endOfDay()
                        ];
                    }
                }
                break;
        }
        
        // افتراضي: اليوم
        return [
            Carbon::today()->startOfDay(),
            Carbon::today()->endOfDay()
        ];
    }
    
    private function getChartData($filters)
    {
        $dateRange = $this->getDateRange($filters);
        
        // بيانات الإيرادات الشهرية (آخر 6 أشهر)
        $monthlyRevenue = [];
        $monthlyLabels = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthlyLabels[] = $month->format('M'); // يناير، فبراير، إلخ
            
            $revenueQuery = Invoice::whereRaw('MONTH(payment_date) = ? AND YEAR(payment_date) = ?', [
                $month->format('n'), // رقم الشهر
                $month->format('Y')  // السنة
            ])->where('status', 'paid');
            
            // تطبيق الفلاتر على الإيرادات
            if (!empty($filters['service_provider_id'])) {
                $revenueQuery->whereHas('booking.serviceProvider', function($q) use ($filters) {
                    $q->where('id', $filters['service_provider_id']);
                });
            }
            if (!empty($filters['product_id'])) {
                $revenueQuery->whereHas('booking.product', function($q) use ($filters) {
                    $q->where('id', $filters['product_id']);
                });
            }
            if (!empty($filters['region'])) {
                $revenueQuery->whereHas('booking', function($q) use ($filters) {
                    $this->applyRegionFilter($q, $filters['region']);
                });
            }
            
            $revenue = $revenueQuery->sum('total_amount');
            $monthlyRevenue[] = $revenue;
        }
        
        // بيانات حالة الحجوزات
        $bookingStatuses = Booking::select('status', DB::raw('COUNT(*) as count'));
        
        // تطبيق الفلاتر على حالة الحجوزات
        if (!empty($filters['service_provider_id'])) {
            $bookingStatuses->where('service_provider_id', $filters['service_provider_id']);
        }
        if (!empty($filters['product_id'])) {
            $bookingStatuses->where('product_id', $filters['product_id']);
        }
        if (!empty($filters['status'])) {
            $bookingStatuses->where('status', $filters['status']);
        }
        if (!empty($filters['region'])) {
            $this->applyRegionFilter($bookingStatuses, $filters['region']);
        }
        
        $bookingStatuses = $bookingStatuses->groupBy('status')->get();
        
        $statusLabels = [];
        $statusData = [];
        $statusColors = [
            'completed' => '#10B981',
            'pending' => '#F59E0B', 
            'confirmed' => '#3B82F6',
            'cancelled' => '#EF4444'
        ];
        
        foreach ($bookingStatuses as $status) {
            $statusLabels[] = $this->getStatusLabel($status->status);
            $statusData[] = $status->count;
        }
        
        // بيانات نمو العملاء (آخر 6 أشهر)
        $monthlyCustomers = [];
        $customerLabels = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $customerLabels[] = $month->format('M');
            
            // استخدام استعلام يدوي لتجنب مشكلة whereBetween مع Carbon
            $customers = Customer::whereRaw('MONTH(created_at) = ? AND YEAR(created_at) = ?', [
                $month->format('n'), // رقم الشهر
                $month->format('Y')  // السنة
            ])->count();
            
            $monthlyCustomers[] = $customers;
        }
        
        // بيانات أداء الخدمات
        $servicePerformance = Booking::select('products.name', DB::raw('COUNT(*) as booking_count'))
        ->join('products', 'bookings.product_id', '=', 'products.id')
        ->where('bookings.status', '!=', 'cancelled');
        
        // تطبيق الفلاتر على أداء الخدمات
        if (!empty($filters['service_provider_id'])) {
            $servicePerformance->where('bookings.service_provider_id', $filters['service_provider_id']);
        }
        if (!empty($filters['product_id'])) {
            $servicePerformance->where('bookings.product_id', $filters['product_id']);
        }
        if (!empty($filters['status'])) {
            $servicePerformance->where('bookings.status', $filters['status']);
        }
        if (!empty($filters['region'])) {
            $this->applyRegionFilter($servicePerformance, $filters['region']);
        }
        
        $servicePerformance = $servicePerformance
        ->groupBy('products.id', 'products.name')
        ->orderBy('booking_count', 'desc')
        ->limit(6)
        ->get();
        
        $serviceLabels = [];
        $serviceData = [];
        
        foreach ($servicePerformance as $service) {
            $serviceLabels[] = $service->name;
            $serviceData[] = $service->booking_count;
        }
        
        return [
            'monthly_revenue' => [
                'labels' => $monthlyLabels,
                'data' => $monthlyRevenue
            ],
            'booking_statuses' => [
                'labels' => $statusLabels,
                'data' => $statusData,
                'colors' => array_values($statusColors)
            ],
            'customer_growth' => [
                'labels' => $customerLabels,
                'data' => $monthlyCustomers
            ],
            'service_performance' => [
                'labels' => $serviceLabels,
                'data' => $serviceData
            ]
        ];
    }
    
    private function getStatusLabel($status)
    {
        $labels = [
            'pending' => 'معلقة',
            'confirmed' => 'مؤكدة',
            'completed' => 'مكتملة',
            'cancelled' => 'ملغية'
        ];
        
        return $labels[$status] ?? $status;
    }
    
    private function applyRegionFilter($query, $region)
    {
        switch ($region) {
            case 'الرياض المركزية':
                $query->whereBetween('customer_latitude', [24.6, 24.8])
                      ->whereBetween('customer_longitude', [46.6, 46.8]);
                break;
            case 'الرياض الشمالية':
                $query->whereBetween('customer_latitude', [24.7, 24.9])
                      ->whereBetween('customer_longitude', [46.6, 46.8]);
                break;
            case 'الرياض الجنوبية':
                $query->whereBetween('customer_latitude', [24.5, 24.7])
                      ->whereBetween('customer_longitude', [46.6, 46.8]);
                break;
            case 'الرياض الغربية':
                $query->whereBetween('customer_latitude', [24.6, 24.8])
                      ->whereBetween('customer_longitude', [46.4, 46.6]);
                break;
            case 'الرياض الشرقية':
                $query->whereBetween('customer_latitude', [24.6, 24.8])
                      ->whereBetween('customer_longitude', [46.8, 47.0]);
                break;
            case 'مناطق أخرى':
                $query->where(function($q) {
                    $q->whereNull('customer_latitude')
                      ->orWhereNull('customer_longitude')
                      ->orWhere(function($subQ) {
                          $subQ->whereNotBetween('customer_latitude', [24.5, 24.9])
                               ->orWhereNotBetween('customer_longitude', [46.4, 47.0]);
                      });
                });
                break;
        }
    }

    // API method للحصول على البيانات للفلاتر
    public function getFilterData()
    {
        $serviceProviders = ServiceProvider::where('is_active', true)
        ->select('id', 'name')
        ->get();
        
        $products = Product::where('is_active', true)
        ->select('id', 'name')
        ->get();
        
        $regions = [
            'الرياض المركزية',
            'الرياض الشمالية', 
            'الرياض الجنوبية',
            'الرياض الغربية',
            'الرياض الشرقية',
            'مناطق أخرى'
        ];
        
        $statuses = [
            'pending' => 'قيد الانتظار',
            'confirmed' => 'مؤكد',
            'paid' => 'مدفوع',
            'completed' => 'مكتمل',
            'cancelled' => 'ملغي'
        ];
        
        return response()->json([
            'service_providers' => $serviceProviders,
            'products' => $products,
            'regions' => $regions,
            'statuses' => $statuses
        ]);
    }

    private function getFilterDataForView()
    {
        $serviceProviders = ServiceProvider::where('is_active', true)
        ->select('id', 'name')
        ->get();
        
        $products = Product::where('is_active', true)
        ->select('id', 'name')
        ->get();
        
        $regions = [
            'الرياض المركزية',
            'الرياض الشمالية', 
            'الرياض الجنوبية',
            'الرياض الغربية',
            'الرياض الشرقية',
            'مناطق أخرى'
        ];
        
        $statuses = [
            'pending' => 'قيد الانتظار',
            'confirmed' => 'مؤكد',
            'paid' => 'مدفوع',
            'completed' => 'مكتمل',
            'cancelled' => 'ملغي'
        ];
        
        return [
            'service_providers' => $serviceProviders,
            'products' => $products,
            'regions' => $regions,
            'statuses' => $statuses
        ];
    }
    
    /**
     * حساب إجمالي الإيرادات اليوم حسب الفترة المحددة
     */
    private function getTodayRevenue($query, $filters)
    {
        $dateRange = $this->getDateRange($filters);
        $startDate = $dateRange[0];
        $endDate = $dateRange[1];
        
        // إذا كانت الفترة المحددة هي اليوم
        if ($filters['date_range'] == 'today') {
            return (clone $query)->whereBetween('payment_date', [
                Carbon::today()->startOfDay(),
                Carbon::today()->endOfDay()
            ])->where('status', 'paid')
            ->sum('total_amount');
        } elseif ($filters['date_range'] == 'yesterday') {
            return (clone $query)->whereBetween('payment_date', [
                Carbon::yesterday()->startOfDay(),
                Carbon::yesterday()->endOfDay()
            ])->where('status', 'paid')
            ->sum('total_amount');
        } elseif ($filters['date_range'] == 'last_2_days') {
            return (clone $query)->whereBetween('payment_date', [
                Carbon::now()->subDays(2)->startOfDay(),
                Carbon::now()->endOfDay()
            ])->where('status', 'paid')
            ->sum('total_amount');
        } elseif ($filters['date_range'] == 'last_3_days') {
            return (clone $query)->whereBetween('payment_date', [
                Carbon::now()->subDays(3)->startOfDay(),
                Carbon::now()->endOfDay()
            ])->where('status', 'paid')
            ->sum('total_amount');
        } elseif ($filters['date_range'] == 'last_7_days') {
            return (clone $query)->whereBetween('payment_date', [
                Carbon::now()->subDays(7)->startOfDay(),
                Carbon::now()->endOfDay()
            ])->where('status', 'paid')
            ->sum('total_amount');
        } elseif ($filters['date_range'] == 'last_30_days') {
            return (clone $query)->whereBetween('payment_date', [
                Carbon::now()->subDays(30)->startOfDay(),
                Carbon::now()->endOfDay()
            ])->where('status', 'paid')
            ->sum('total_amount');
        } elseif ($filters['date_range'] == 'week') {
            return (clone $query)->whereBetween('payment_date', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])->where('status', 'paid')
            ->sum('total_amount');
        } elseif ($filters['date_range'] == 'last_week') {
            return (clone $query)->whereBetween('payment_date', [
                Carbon::now()->subWeek()->startOfWeek(),
                Carbon::now()->subWeek()->endOfWeek()
            ])->where('status', 'paid')
            ->sum('total_amount');
        } elseif ($filters['date_range'] == 'month') {
            return (clone $query)->whereBetween('payment_date', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ])->where('status', 'paid')
            ->sum('total_amount');
        } elseif ($filters['date_range'] == 'last_month') {
            return (clone $query)->whereBetween('payment_date', [
                Carbon::now()->subMonth()->startOfMonth(),
                Carbon::now()->subMonth()->endOfMonth()
            ])->where('status', 'paid')
            ->sum('total_amount');
        } elseif ($filters['date_range'] == 'year') {
            return (clone $query)->whereBetween('payment_date', [
                Carbon::now()->startOfYear(),
                Carbon::now()->endOfYear()
            ])->where('status', 'paid')
            ->sum('total_amount');
        } elseif ($filters['date_range'] == 'last_year') {
            return (clone $query)->whereBetween('payment_date', [
                Carbon::now()->subYear()->startOfYear(),
                Carbon::now()->subYear()->endOfYear()
            ])->where('status', 'paid')
            ->sum('total_amount');
        } elseif ($filters['date_range'] == 'custom' && $filters['start_date'] && $filters['end_date']) {
            return (clone $query)->whereBetween('payment_date', $dateRange)
            ->where('status', 'paid')
            ->sum('total_amount');
        }
        
        // افتراضي: اليوم
        return (clone $query)->whereBetween('payment_date', [
            Carbon::today()->startOfDay(),
            Carbon::today()->endOfDay()
        ])->where('status', 'paid')
        ->sum('total_amount');
    }
    
    /**
     * حساب إجمالي الإيرادات الشهر حسب الفترة المحددة
     */
    private function getMonthRevenue($query, $filters)
    {
        $dateRange = $this->getDateRange($filters);
        $startDate = $dateRange[0];
        $endDate = $dateRange[1];
        
        // إذا كانت الفترة المحددة هي شهر محدد
        if ($filters['date_range'] == 'month') {
            // الشهر الحالي
            return (clone $query)->whereBetween('payment_date', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ])->where('status', 'paid')
            ->sum('total_amount');
        } elseif ($filters['date_range'] == 'last_month') {
            // الشهر الماضي
            return (clone $query)->whereBetween('payment_date', [
                Carbon::now()->subMonth()->startOfMonth(),
                Carbon::now()->subMonth()->endOfMonth()
            ])->where('status', 'paid')
            ->sum('total_amount');
        } elseif ($filters['date_range'] == 'custom' && $filters['start_date'] && $filters['end_date']) {
            // تاريخ مخصص - نحسب الشهر الذي يقع فيه معظم الفترة
            $startMonth = Carbon::parse($filters['start_date'])->startOfMonth();
            $endMonth = Carbon::parse($filters['end_date'])->startOfMonth();
            
            if ($startMonth->eq($endMonth)) {
                // نفس الشهر
                return (clone $query)->whereBetween('payment_date', [
                    $startMonth->startOfMonth(),
                    $startMonth->endOfMonth()
                ])->where('status', 'paid')
                ->sum('total_amount');
            } else {
                // فترات متعددة - نرجع إجمالي الفترة المخصصة
                return (clone $query)->whereBetween('payment_date', $dateRange)
                ->where('status', 'paid')
                ->sum('total_amount');
            }
        } else {
            // فترات أخرى - نرجع إجمالي الفترة المحددة
            return (clone $query)->whereBetween('payment_date', $dateRange)
            ->where('status', 'paid')
            ->sum('total_amount');
        }
    }
    
    /**
     * حساب إجمالي الإيرادات السنة حسب الفترة المحددة
     */
    private function getYearRevenue($query, $filters)
    {
        $dateRange = $this->getDateRange($filters);
        $startDate = $dateRange[0];
        $endDate = $dateRange[1];
        
        // إذا كانت الفترة المحددة هي سنة محددة
        if ($filters['date_range'] == 'year') {
            // السنة الحالية
            return (clone $query)->whereBetween('payment_date', [
                Carbon::now()->startOfYear(),
                Carbon::now()->endOfYear()
            ])->where('status', 'paid')
            ->sum('total_amount');
        } elseif ($filters['date_range'] == 'last_year') {
            // السنة الماضية
            return (clone $query)->whereBetween('payment_date', [
                Carbon::now()->subYear()->startOfYear(),
                Carbon::now()->subYear()->endOfYear()
            ])->where('status', 'paid')
            ->sum('total_amount');
        } elseif ($filters['date_range'] == 'custom' && $filters['start_date'] && $filters['end_date']) {
            // تاريخ مخصص - نحسب السنة التي يقع فيها معظم الفترة
            $startYear = Carbon::parse($filters['start_date'])->startOfYear();
            $endYear = Carbon::parse($filters['end_date'])->startOfYear();
            
            if ($startYear->eq($endYear)) {
                // نفس السنة
                return (clone $query)->whereBetween('payment_date', [
                    $startYear->startOfYear(),
                    $startYear->endOfYear()
                ])->where('status', 'paid')
                ->sum('total_amount');
            } else {
                // فترات متعددة - نرجع إجمالي الفترة المخصصة
                return (clone $query)->whereBetween('payment_date', $dateRange)
                ->where('status', 'paid')
                ->sum('total_amount');
            }
        } else {
            // فترات أخرى - نرجع إجمالي الفترة المحددة
            return (clone $query)->whereBetween('payment_date', $dateRange)
            ->where('status', 'paid')
            ->sum('total_amount');
        }
    }
}
