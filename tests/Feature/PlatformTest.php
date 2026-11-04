<?php

namespace Tests\Feature;

use App\Models\Plan;
use App\Models\Product;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use App\Support\Tenancy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

// لوحة مالك المنصة والباقات وتطبيق حدودها وخصائصها
class PlatformTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    private function owner(): User
    {
        return User::where('is_super_admin', true)->firstOrFail();
    }

    private function newTenant(string $plan = 'basic', array $extra = []): array
    {
        $this->actingAs($this->owner())->post('/platform/tenants', [
            'name' => 'مغسلة البحر', 'slug' => 'sea-wash', 'plan_id' => Plan::where('slug', $plan)->value('id'),
            'subscription_status' => 'trial', 'trial_ends_at' => today()->addDays(14)->toDateString(),
            'owner_name' => 'أبو خالد', 'owner_email' => 'sea@example.test', 'owner_password' => 'secret-pass',
            'business_type' => 'car_wash',
        ] + $extra)->assertRedirect('/platform/tenants');

        return [Tenant::where('slug', 'sea-wash')->firstOrFail(), User::where('email', 'sea@example.test')->firstOrFail()];
    }

    public function test_only_platform_owner_can_open_platform(): void
    {
        $this->actingAs($this->owner())->get('/platform')->assertOk()->assertSee('لوحة مالك المنصة');
        $this->actingAs($this->owner())->get('/platform/plans')->assertOk()->assertSee('الاحترافية');
        $this->actingAs($this->owner())->get('/dashboard')->assertOk()->assertSee(route('platform.tenants.index'), false);

        $staff = User::create(['name' => 'موظف', 'email' => 'staff@example.test', 'password' => bcrypt('x-secret-1'), 'role_id' => Role::where('name', 'admin')->value('id'), 'is_active' => true]);
        $this->actingAs($staff)->get('/platform')->assertForbidden();
        $this->actingAs($staff)->get('/dashboard')->assertOk()->assertDontSee(route('platform.tenants.index'), false);
    }

    public function test_owner_creates_tenant_with_admin_schedule_and_settings(): void
    {
        [$tenant, $admin] = $this->newTenant();

        $this->assertSame($tenant->id, $admin->tenant_id);
        $this->assertFalse((bool) $admin->is_super_admin);
        $this->actingAs($admin)->get('/dashboard')->assertOk()->assertSee('مغسلة البحر');
        $this->actingAs($admin)->get('/admin/settings')->assertOk()->assertSee('باقتك: الأساسية');
        $this->actingAs($admin)->get('/platform')->assertForbidden();
    }

    public function test_plan_features_hide_and_block_sections(): void
    {
        [, $admin] = $this->newTenant('basic'); // الأساسية: بدون كوبونات

        $this->actingAs($admin)->get('/dashboard')->assertOk()->assertDontSee(route('admin.coupons.index'), false);
        $this->actingAs($admin)->get('/admin/coupons')->assertRedirect('/dashboard');
        $this->actingAs($admin)->get('/admin/invoices')->assertOk(); // الفواتير مشمولة
    }

    public function test_plan_limits_block_new_services(): void
    {
        [$tenant, $admin] = $this->newTenant();
        Plan::where('slug', 'basic')->update(['max_services' => 1]);

        Tenancy::set($tenant);
        Product::create(['name' => 'غسيل خارجي', 'short_description' => 'و', 'description' => 'و', 'price' => 30, 'is_active' => true]);
        Tenancy::forget();

        $this->actingAs($admin)->from('/admin/products/create')
            ->post('/admin/products', ['name' => 'غسيل داخلي', 'price' => 40])
            ->assertRedirect('/admin/products/create')
            ->assertSessionHas('error', plan_limit_message('services'));
        $this->assertSame(1, Product::withoutGlobalScope('tenant')->where('tenant_id', $tenant->id)->count());
    }

    public function test_expired_subscription_locks_dashboard_but_keeps_data(): void
    {
        [$tenant, $admin] = $this->newTenant();
        $tenant->update(['trial_ends_at' => today()->subDay()]);

        $this->actingAs($admin)->get('/dashboard')->assertStatus(402)->assertSee('انتهى اشتراك');

        $tenant->update(['subscription_status' => 'active', 'subscription_ends_at' => today()->addMonth()]);
        $this->actingAs($admin)->get('/dashboard')->assertOk();
    }
}
