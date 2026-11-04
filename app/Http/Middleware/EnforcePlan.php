<?php

namespace App\Http\Middleware;

use App\Models\Plan;
use App\Support\Tenancy;
use Closure;
use Illuminate\Http\Request;

// يطبّق الباقة على لوحة التحكم: الاشتراك المنتهي يوقف اللوحة، والخصائص غير المشمولة تُحجب
class EnforcePlan
{
    // بداية اسم المسار ← الخاصية المطلوبة
    public const ROUTE_FEATURES = [
        'admin.coupons.' => 'coupons',
        'admin.invoices.' => 'invoices',
        'admin.loyalty.' => 'loyalty',
        'admin.reports.' => 'reports',
        'admin.abandoned-carts.' => 'abandoned_carts',
        'admin.mobile-customers.' => 'mobile_app',
    ];

    public function handle(Request $request, Closure $next)
    {
        $tenant = Tenancy::current();
        $user = $request->user();
        $route = (string) $request->route()?->getName();

        if (!$tenant || !$user || !$request->is('admin*', 'dashboard*') || $request->is('admin/login', 'admin/logout')) {
            return $next($request);
        }

        if (!$user->is_super_admin && !$tenant->hasValidSubscription()) {
            return response()->view('errors.subscription', ['tenant' => $tenant], 402);
        }

        foreach (self::ROUTE_FEATURES as $prefix => $feature) {
            if (str_starts_with($route, $prefix) && !$tenant->hasFeature($feature)) {
                return redirect()->route('dashboard')
                    ->with('error', '«' . Plan::FEATURES[$feature] . '» غير متاحة في باقتك الحالية. تواصل معنا للترقية.');
            }
        }

        return $next($request);
    }
}
