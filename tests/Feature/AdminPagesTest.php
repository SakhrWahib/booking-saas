<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

// الصفحات الأساسية تعرض البيانات بالتصميم الجديد، والفلاتر والإجراءات تعمل
class AdminPagesTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    private function service(): Product
    {
        return Product::create([
            'name' => 'خدمة الاختبار', 'short_description' => 'وصف', 'description' => 'وصف',
            'price' => 120, 'is_active' => true,
        ]);
    }

    public function test_bookings_list_filters_and_actions(): void
    {
        $customer = Customer::create(['name' => 'عميل الاختبار', 'phone' => '0511111111']);
        $booking = Booking::create([
            'booking_number' => 'T-1001', 'customer_id' => $customer->id, 'product_id' => $this->service()->id,
            'service_provider_id' => ServiceProvider::firstOrFail()->id,
            'booking_date' => today(), 'booking_time' => '10:00', 'status' => 'pending', 'total_price' => 120,
        ]);

        $admin = User::firstOrFail();
        $this->actingAs($admin)->get('/admin/bookings')->assertOk()->assertSee('T-1001')->assertSee('عميل الاختبار');
        $this->actingAs($admin)->get('/admin/bookings?search=0511111111')->assertOk()->assertSee('T-1001');
        $this->actingAs($admin)->get('/admin/bookings?status=cancelled')->assertOk()->assertDontSee('T-1001');
        $this->actingAs($admin)->get('/dashboard')->assertOk()->assertSee('عميل الاختبار');

        $this->actingAs($admin)->get("/admin/bookings/{$booking->id}")->assertOk()->assertSee('T-1001')->assertSee('عميل الاختبار')->assertSee('الإجمالي');
        $this->actingAs($admin)->get('/admin/bookings/create')->assertOk();

        $this->actingAs($admin)->post("/admin/bookings/{$booking->id}/confirm")->assertRedirect();
        $this->assertSame('confirmed', $booking->fresh()->status);
    }

    public function test_customers_and_services_pages(): void
    {
        Customer::create(['name' => 'سارة', 'phone' => '0522222222']);
        $this->service();

        $admin = User::firstOrFail();
        $this->actingAs($admin)->get('/admin/customers?search=0522')->assertOk()->assertSee('سارة');
        $this->actingAs($admin)->get('/admin/products')->assertOk()->assertSee('خدمة الاختبار');
    }
}
