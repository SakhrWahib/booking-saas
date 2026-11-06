<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ServiceProvider;
use App\Models\Tenant;
use Database\Seeders\DemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

// البيانات التجريبية: نشاط مستقل كامل، الدخول واللوحة يعملان، والإنتاج محمي
class DemoSeederTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    private function seedDemo(): Tenant
    {
        config(['platform.demo_admin_password' => 'demo-test-pass']);
        $this->seed(DemoSeeder::class);

        return Tenant::where('slug', DemoSeeder::SLUG)->firstOrFail();
    }

    private function rows(string $model, Tenant $tenant): int
    {
        return $model::withoutGlobalScope('tenant')->where('tenant_id', $tenant->id)->count();
    }

    public function test_demo_tenant_has_complete_isolated_data(): void
    {
        $mainBookings = Booking::withoutGlobalScope('tenant')->where('tenant_id', 1)->count();
        $tenant = $this->seedDemo();

        $this->assertSame(5, $this->rows(Product::class, $tenant));
        $this->assertSame(3, $this->rows(ServiceProvider::class, $tenant));
        $this->assertSame(12, $this->rows(Customer::class, $tenant));
        $this->assertSame(23, $this->rows(Booking::class, $tenant));
        $this->assertSame($mainBookings, Booking::withoutGlobalScope('tenant')->where('tenant_id', 1)->count());

        // إعادة التشغيل لا تكرر البيانات
        $tenant = $this->seedDemo();
        $this->assertSame(1, Tenant::where('slug', DemoSeeder::SLUG)->count());
        $this->assertSame(23, $this->rows(Booking::class, $tenant));
    }

    public function test_demo_admin_can_login_and_use_dashboard(): void
    {
        $this->seedDemo();

        $this->post('/admin/login', ['email' => DemoSeeder::ADMIN_EMAIL, 'password' => 'demo-test-pass'])->assertRedirect();
        $this->assertAuthenticated();

        $this->get('/dashboard')->assertOk()
            ->assertSee('صالون لمسة')->assertSee('حجوزات اليوم')->assertSee('إيراد الشهر')
            ->assertSee('الخدمات والفريق')->assertSee('الحجوزات القادمة');
        $this->get('/admin/bookings?search=DEMO-0012')->assertOk()->assertSee('DEMO-0012');
        $this->get('/admin/products')->assertOk()->assertSee('قص وتصفيف شعر');
        $this->get('/admin/customers')->assertOk()->assertSee('نوف القحطاني');
        $this->get('/admin/bookings/calendar')->assertOk();
        $this->get('/admin/bookings/create')->assertOk();
        $this->get('/platform')->assertForbidden();
    }

    public function test_demo_seeder_is_blocked_in_production(): void
    {
        $this->app['env'] = 'production';
        $this->seed(DemoSeeder::class);
        $this->assertSame(0, Tenant::where('slug', DemoSeeder::SLUG)->count());
    }

    public function test_platform_identity_has_no_previous_business(): void
    {
        $this->get('/')->assertOk()->assertDontSee('catshoop')->assertDontSee('كات شوب');
        $this->get('/admin/login')->assertOk()->assertDontSee('كات شوب');
    }
}
