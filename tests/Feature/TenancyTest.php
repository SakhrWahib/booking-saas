<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Role;
use App\Models\ServiceProvider;
use App\Models\Tenant;
use App\Models\User;
use App\Support\Brand;
use App\Support\Tenancy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

// كل نشاط يرى بياناته فقط، والبيانات القديمة تبقى في النشاط الأول
class TenancyTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    private Tenant $salon;
    private User $salonAdmin;
    private Booking $salonBooking;

    protected function setUp(): void
    {
        parent::setUp();

        $this->salon = Tenant::create(['name' => 'صالون الورد', 'slug' => 'salon']);
        Tenancy::set($this->salon);

        $this->salonAdmin = User::create([
            'name' => 'مدير الصالون', 'email' => 'salon@example.test', 'password' => bcrypt('secret-pass'),
            'role_id' => Role::where('name', 'admin')->value('id'), 'is_active' => true,
        ]);
        $customer = Customer::create(['name' => 'زبونة الصالون', 'phone' => '0599999999']);
        $product = Product::create(['name' => 'قص شعر', 'short_description' => 'و', 'description' => 'و', 'price' => 80, 'is_active' => true]);
        $provider = ServiceProvider::create(['name' => 'مصففة', 'phone' => '0588888888']);
        $this->salonBooking = Booking::create([
            'booking_number' => 'SALON-1', 'customer_id' => $customer->id, 'product_id' => $product->id,
            'service_provider_id' => $provider->id, 'booking_date' => today(), 'booking_time' => '10:00',
            'status' => 'pending', 'total_price' => 80,
        ]);

        Tenancy::forget();
    }

    public function test_new_records_get_tenant_and_existing_data_stays_in_main_tenant(): void
    {
        $this->assertSame($this->salon->id, $this->salonBooking->tenant_id);
        $this->assertSame($this->salon->id, $this->salonAdmin->tenant_id);
        $this->assertSame(1, User::where('email', 'admin@example.com')->value('tenant_id'));
    }

    public function test_each_admin_sees_only_own_data(): void
    {
        $mainAdmin = User::where('tenant_id', 1)->firstOrFail();

        $this->actingAs($mainAdmin)->get('/admin/bookings')->assertOk()->assertDontSee('SALON-1');
        $this->actingAs($mainAdmin)->get('/admin/customers')->assertOk()->assertDontSee('زبونة الصالون');
        $this->actingAs($mainAdmin)->get('/admin/users')->assertOk()->assertDontSee('salon@example.test');
        $this->actingAs($mainAdmin)->get("/admin/bookings/{$this->salonBooking->id}")->assertNotFound();

        $this->actingAs($this->salonAdmin)->get('/admin/bookings')->assertOk()->assertSee('SALON-1');
        $this->actingAs($this->salonAdmin)->get('/admin/customers')->assertOk()->assertSee('زبونة الصالون');
        $this->actingAs($this->salonAdmin)->get('/admin/users')->assertOk()->assertDontSee('admin@example.com');
    }

    public function test_same_phone_allowed_in_different_tenants(): void
    {
        Customer::create(['name' => 'نفس الرقم', 'phone' => '0599999999']); // في النشاط الأول
        $this->assertSame(2, Customer::withoutGlobalScope('tenant')->where('phone', '0599999999')->count());
    }

    public function test_settings_and_public_site_are_per_tenant(): void
    {
        $this->actingAs($this->salonAdmin)->put('/admin/settings', [
            'name' => 'صالون الورد الفاخر', 'business_type' => 'salon', 'primary_color' => '#db2777',
        ])->assertRedirect();

        config(['tenancy.central_domain' => 'example.test']);
        $this->get('http://salon.example.test/')->assertOk()->assertSee('صالون الورد الفاخر')->assertSee('قص شعر');
        $this->get('http://localhost/')->assertOk()->assertDontSee('صالون الورد الفاخر')->assertDontSee('قص شعر');
    }

    public function test_inactive_tenant_is_blocked(): void
    {
        $this->salon->update(['is_active' => false]);
        config(['tenancy.central_domain' => 'example.test']);
        $this->actingAs($this->salonAdmin)->get('/dashboard')->assertStatus(503);
    }
}
