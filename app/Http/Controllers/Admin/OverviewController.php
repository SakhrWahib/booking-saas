<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;

// الصفحة الرئيسية للوحة التحكم: أرقام اليوم والشهر، وحجوزات اليوم والقادمة، وآخر 14 يومًا
class OverviewController extends Controller
{
    private const REVENUE_STATUSES = ['confirmed', 'paid', 'completed'];

    public function index()
    {
        $today = today();
        $monthStart = $today->copy()->startOfMonth();

        $stats = [
            'today' => Booking::whereDate('booking_date', $today)->where('status', '!=', 'cancelled')->count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'month_revenue' => (float) Booking::whereIn('status', self::REVENUE_STATUSES)
                ->whereBetween('booking_date', [$monthStart, $today->copy()->endOfMonth()])->sum('total_price'),
            'month_bookings' => Booking::whereBetween('booking_date', [$monthStart, $today->copy()->endOfMonth()])
                ->where('status', '!=', 'cancelled')->count(),
            'customers' => Customer::count(),
            'new_customers' => Customer::where('created_at', '>=', $monthStart)->count(),
        ];

        $todayBookings = Booking::with(['customer:id,name,phone', 'product:id,name'])
            ->whereDate('booking_date', $today)->orderBy('booking_time')->limit(10)->get();

        $upcoming = Booking::with(['customer:id,name', 'product:id,name'])
            ->whereDate('booking_date', '>', $today)->where('status', '!=', 'cancelled')
            ->orderBy('booking_date')->orderBy('booking_time')->limit(6)->get();

        // عدد الحجوزات لآخر 14 يومًا
        $from = $today->copy()->subDays(13);
        $counts = Booking::where('status', '!=', 'cancelled')->whereBetween('booking_date', [$from, $today->copy()->endOfDay()])
            ->get(['booking_date'])->groupBy(fn ($b) => \Carbon\Carbon::parse($b->booking_date)->toDateString())->map->count();
        $chart = collect(range(0, 13))->map(function ($i) use ($from, $counts) {
            $day = $from->copy()->addDays($i);
            return ['label' => $day->format('d/m'), 'value' => $counts[$day->toDateString()] ?? 0];
        });

        return view('admin.overview', compact('stats', 'todayBookings', 'upcoming', 'chart'));
    }
}
