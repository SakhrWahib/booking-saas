<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use App\Support\Brand;
use App\Support\Tenancy;
use Closure;
use Illuminate\Http\Request;

// يحدد النشاط الحالي لكل طلب:
// لوحة التحكم ← نشاط المستخدم المسجّل، والموقع العام ← حسب النطاق، وإلا النشاط الافتراضي
class IdentifyTenant
{
    public function handle(Request $request, Closure $next)
    {
        Tenancy::forget();

        $tenant = null;
        $user = $request->user();
        if ($user?->tenant_id && $request->is('admin*', 'dashboard*', 'api/*')) {
            $tenant = Tenant::find($user->tenant_id);
        }
        $tenant ??= Tenancy::fromHost($request->getHost());

        if ($tenant && !$tenant->is_active) {
            abort(503, 'هذا النشاط موقوف مؤقتًا');
        }

        Tenancy::set($tenant);
        Brand::load();

        return $next($request);
    }
}
