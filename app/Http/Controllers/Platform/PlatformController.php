<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Plan;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\TimeSlot;
use App\Models\User;
use App\Models\WorkingSchedule;
use App\Support\Brand;
use App\Support\Tenancy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

// لوحة مالك المنصة: الأنشطة والباقات والاشتراكات
class PlatformController extends Controller
{
    public function overview()
    {
        $tenants = Tenant::with('plan')->get();
        $paying = $tenants->where('subscription_status', 'active')->filter->hasValidSubscription();

        return view('platform.overview', [
            'stats' => [
                'tenants' => $tenants->count(),
                'active' => $paying->count(),
                'trial' => $tenants->where('subscription_status', 'trial')->count(),
                'mrr' => $paying->sum(fn ($t) => (float) ($t->plan->price_monthly ?? 0)),
                'bookings_month' => Booking::withoutGlobalScope('tenant')->where('created_at', '>=', now()->startOfMonth())->count(),
            ],
            'expiring' => $tenants->filter(function ($t) {
                $end = $t->subscription_status === 'trial' ? $t->trial_ends_at : $t->subscription_ends_at;
                return $end && $end->between(today(), today()->addDays(7));
            }),
            'latest' => Tenant::with('plan')->latest()->limit(5)->get(),
        ]);
    }

    public function tenants(Request $request)
    {
        $tenants = Tenant::with('plan')->withCount('users')
            ->when($request->filled('search'), fn ($q) => $q->where(fn ($w) => $w->where('name', 'like', "%{$request->search}%")
                ->orWhere('slug', 'like', "%{$request->search}%")->orWhere('owner_phone', 'like', "%{$request->search}%")))
            ->when($request->filled('status'), fn ($q) => $q->where('subscription_status', $request->status))
            ->latest()->paginate(20)->withQueryString();

        return view('platform.tenants.index', compact('tenants'));
    }

    public function createTenant()
    {
        return view('platform.tenants.form', ['tenant' => new Tenant(['subscription_status' => 'trial', 'trial_ends_at' => today()->addDays(14)]), 'plans' => Plan::orderBy('sort_order')->get()]);
    }

    public function storeTenant(Request $request)
    {
        $data = $request->validate($this->tenantRules() + [
            'owner_email' => 'required|email|max:150|unique:users,email',
            'owner_password' => 'required|string|min:8',
            'business_type' => ['required', Rule::in(array_keys(config('brand.presets')))],
        ]);

        $tenant = DB::transaction(function () use ($data) {
            $tenant = Tenant::create($data + ['is_active' => true]);
            $previous = Tenancy::current();
            Tenancy::set($tenant);

            // مدير النشاط
            User::create([
                'name' => $data['owner_name'], 'email' => $data['owner_email'],
                'password' => bcrypt($data['owner_password']),
                'role_id' => Role::where('name', 'admin')->value('id'), 'is_active' => true,
            ]);

            // جدول عمل افتراضي حتى يبدأ النشاط مباشرة: كل الأيام عدا الجمعة، من 10 صباحًا كل ساعتين
            $days = collect(['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday'])
                ->mapWithKeys(fn ($d) => [$d => ['active' => $d !== 'friday']])->all();
            $schedule = WorkingSchedule::create([
                'schedule_name' => 'الدوام الأساسي', 'start_date' => today(), 'end_date' => today()->addYears(5),
                'active_days' => $days, 'is_default' => true, 'is_active' => true,
            ]);
            foreach (['10:00' => 'morning', '12:00' => 'afternoon', '14:00' => 'afternoon', '16:00' => 'afternoon', '18:00' => 'evening', '20:00' => 'evening'] as $i => $period) {
                TimeSlot::create(['working_schedule_id' => $schedule->id, 'time' => $i, 'period' => $period, 'sort_order' => (int) $i, 'is_active' => true]);
            }

            Brand::save(['name' => $tenant->name, 'business_type' => $data['business_type'], 'phone' => $tenant->owner_phone ?? '']);

            Tenancy::set($previous);

            return $tenant;
        });

        return redirect()->route('platform.tenants.index')->with('success', "تم إنشاء «{$tenant->name}». يدخل المدير من /admin/login بالبريد {$data['owner_email']}");
    }

    public function editTenant(Tenant $tenant)
    {
        return view('platform.tenants.form', ['tenant' => $tenant, 'plans' => Plan::orderBy('sort_order')->get()]);
    }

    public function updateTenant(Request $request, Tenant $tenant)
    {
        $data = $request->validate($this->tenantRules($tenant) + ['is_active' => 'boolean']);
        $tenant->update($data + ['is_active' => $request->boolean('is_active')]);

        return redirect()->route('platform.tenants.index')->with('success', "تم تحديث «{$tenant->name}»");
    }

    public function plans()
    {
        return view('platform.plans', ['plans' => Plan::withCount('tenants')->orderBy('sort_order')->get()]);
    }

    public function savePlan(Request $request, ?Plan $plan = null)
    {
        $data = $request->validate([
            'name' => 'required|string|max:60',
            'slug' => ['required', 'alpha_dash', 'max:40', Rule::unique('plans', 'slug')->ignore($plan?->id)],
            'price_monthly' => 'required|numeric|min:0',
            'price_yearly' => 'required|numeric|min:0',
            'max_staff' => 'nullable|integer|min:1',
            'max_services' => 'nullable|integer|min:1',
            'max_monthly_bookings' => 'nullable|integer|min:1',
            'features' => 'array',
            'features.*' => Rule::in(array_keys(Plan::FEATURES)),
        ]);
        $data['features'] = $data['features'] ?? [];
        $data['is_active'] = $request->boolean('is_active', true);

        $plan ? $plan->update($data) : Plan::create($data + ['sort_order' => Plan::max('sort_order') + 1]);

        return redirect()->route('platform.plans.index')->with('success', 'تم حفظ الباقة');
    }

    private function tenantRules(?Tenant $tenant = null): array
    {
        return [
            'name' => 'required|string|max:100',
            'slug' => ['required', 'alpha_dash', 'max:40', Rule::unique('tenants', 'slug')->ignore($tenant?->id)],
            'domain' => ['nullable', 'string', 'max:150', Rule::unique('tenants', 'domain')->ignore($tenant?->id)],
            'plan_id' => 'nullable|exists:plans,id',
            'subscription_status' => ['required', Rule::in(array_keys(Tenant::STATUSES))],
            'trial_ends_at' => 'nullable|date',
            'subscription_ends_at' => 'nullable|date',
            'owner_name' => 'required|string|max:100',
            'owner_phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string|max:1000',
        ];
    }
}
