<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Permission;
use App\Models\Product;
use App\Models\Role;
use App\Models\ServiceProvider;
use App\Models\Tenant;
use App\Models\User;
use App\Support\Tenancy;
use Database\Seeders\DemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

// دورة الحجز كاملة من لوحة التحكم على النشاط التجريبي
class BookingFlowTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    private Tenant $tenant;
    private User $admin;
    private string $date;

    protected function setUp(): void
    {
        parent::setUp();
        config(['platform.demo_admin_password' => 'demo-test-pass']);
        $this->seed(DemoSeeder::class);
        $this->tenant = Tenant::where('slug', DemoSeeder::SLUG)->firstOrFail();
        $this->admin = User::where('email', DemoSeeder::ADMIN_EMAIL)->firstOrFail();

        // يوم عمل بعيد عن الحجوزات التجريبية (الجمعة إجازة في الجدول التجريبي)
        $day = today()->addDays(30);
        while ($day->isFriday()) {
            $day->addDay();
        }
        $this->date = $day->toDateString();
        Tenancy::forget();
    }

    private function inTenant(callable $fn)
    {
        Tenancy::set($this->tenant);
        $result = $fn();
        Tenancy::forget();

        return $result;
    }

    private function provider(string $name): ServiceProvider
    {
        return $this->inTenant(fn () => ServiceProvider::where('name', $name)->firstOrFail());
    }

    private function service(string $name): Product
    {
        return $this->inTenant(fn () => Product::where('name', $name)->firstOrFail());
    }

    private function book(array $overrides = [])
    {
        $data = $overrides + [
            'customer_id' => $this->inTenant(fn () => Customer::firstOrFail()->id),
            'product_id' => $this->service('قص وتصفيف شعر')->id,
            'service_provider_id' => $this->provider('نورة')->id,
            'booking_date' => $this->date,
            'booking_time' => '10:00',
        ];

        return $this->actingAs($this->admin)->post('/admin/bookings', $data);
    }

    private function reload(Booking $booking): Booking
    {
        return Booking::withoutGlobalScope('tenant')->findOrFail($booking->id);
    }

    private function latestBooking(): Booking
    {
        return $this->inTenant(fn () => Booking::latest('id')->firstOrFail());
    }

    public function test_form_pages_render(): void
    {
        $this->actingAs($this->admin)->get('/admin/bookings/create')->assertOk()->assertSee('bookingForm', false)->assertSee('قص وتصفيف شعر');
        $booking = $this->inTenant(fn () => Booking::where('status', 'pending')->firstOrFail());
        $this->actingAs($this->admin)->get("/admin/bookings/{$booking->id}/edit")->assertOk()->assertSee($booking->booking_number);
    }

    public function test_create_uses_server_prices_and_creates_invoice(): void
    {
        $this->book([
            'add_ons' => ['غسيل بالبخار'],
            'quantity' => 2,
            // محاولة تلاعب: هذه الحقول تُتجاهل
            'total_price' => 1, 'selected_add_ons' => [['name' => 'غسيل بالبخار', 'price' => 0]],
        ])->assertRedirect();

        $booking = $this->latestBooking();
        $this->assertSame('pending', $booking->status);
        $this->assertSame('admin_panel', $booking->booking_source);
        $this->assertEquals(300.0, (float) $booking->getRawOriginal('total_price')); // (120 + 30) × 2
        $this->assertSame(30.0, (float) $booking->selected_add_ons[0]['price']);
        $this->assertNotNull($this->inTenant(fn () => $this->reload($booking)->invoice));
    }

    public function test_create_with_new_customer_reuses_existing_phone(): void
    {
        $this->book(['customer_id' => null, 'new_customer_name' => 'عميلة جديدة', 'new_customer_phone' => '0512345678'])->assertRedirect();
        $this->book(['customer_id' => null, 'new_customer_name' => 'اسم آخر', 'new_customer_phone' => '0512345678', 'booking_time' => '11:00'])->assertRedirect();

        $this->assertSame(1, $this->inTenant(fn () => Customer::where('phone', '0512345678')->count()));
    }

    public function test_single_person_slot_cannot_be_double_booked(): void
    {
        $this->book()->assertRedirect();
        $this->book()->assertSessionHasErrors('booking_time');
        $this->assertSame(1, $this->inTenant(fn () => Booking::whereDate('booking_date', $this->date)->count()));
    }

    public function test_team_capacity_allows_two_then_blocks(): void
    {
        $team = ['product_id' => $this->service('مانيكير وبديكير')->id, 'service_provider_id' => $this->provider('فريق العناية')->id];

        $this->book($team)->assertSessionHasNoErrors();
        $this->book($team)->assertSessionHasNoErrors();
        $this->book($team)->assertSessionHasErrors('booking_time');
    }

    public function test_provider_must_offer_the_service(): void
    {
        // ريم لا تقدّم "تنظيف بشرة"
        $this->book(['product_id' => $this->service('تنظيف بشرة')->id, 'service_provider_id' => $this->provider('ريم')->id])
            ->assertSessionHasErrors('service_provider_id');
    }

    public function test_cannot_book_outside_working_hours_or_on_day_off(): void
    {
        $this->book(['booking_time' => '23:00'])->assertSessionHasErrors('booking_time');

        $friday = today()->next(\Carbon\Carbon::FRIDAY)->toDateString();
        $this->book(['booking_date' => $friday])->assertSessionHasErrors('booking_date');
    }

    public function test_edit_reschedule_and_conflicts(): void
    {
        $this->book()->assertRedirect();
        $first = $this->latestBooking();
        $this->book(['booking_time' => '11:00'])->assertRedirect();
        $second = $this->latestBooking();

        $payload = fn (array $o) => $o + [
            'customer_id' => $first->customer_id, 'product_id' => $first->product_id,
            'service_provider_id' => $first->service_provider_id, 'booking_date' => $this->date,
            'booking_time' => '10:00', 'status' => 'pending',
        ];

        // حفظ بدون تغيير الموعد لا يعتبر تعارضًا مع نفسه
        $this->actingAs($this->admin)->put("/admin/bookings/{$first->id}", $payload(['admin_notes' => 'ملاحظة']))->assertSessionHasNoErrors();
        // نقل الحجز الأول إلى موعد الثاني ممنوع
        $this->actingAs($this->admin)->put("/admin/bookings/{$first->id}", $payload(['booking_time' => '11:00']))->assertSessionHasErrors('booking_time');
        // نقله إلى موعد فارغ مسموح
        $this->actingAs($this->admin)->put("/admin/bookings/{$first->id}", $payload(['booking_time' => '13:00']))->assertSessionHasNoErrors();

        $this->assertSame('13:00', \Carbon\Carbon::parse($this->reload($first)->booking_time)->format('H:i'));
        $this->assertSame('ملاحظة', $this->reload($first)->admin_notes);
        $this->assertSame('11:00', \Carbon\Carbon::parse($this->reload($second)->booking_time)->format('H:i'));
    }

    public function test_confirm_complete_cancel_transitions(): void
    {
        $this->book()->assertRedirect();
        $booking = $this->latestBooking();

        $this->actingAs($this->admin)->post("/admin/bookings/{$booking->id}/complete")->assertSessionHas('error'); // لا يكتمل قبل التأكيد
        $this->actingAs($this->admin)->post("/admin/bookings/{$booking->id}/confirm")->assertSessionHas('success');
        $this->assertSame('confirmed', $this->reload($booking)->status);
        $this->actingAs($this->admin)->post("/admin/bookings/{$booking->id}/complete")->assertSessionHas('success');
        $this->assertSame('completed', $this->reload($booking)->status);
        $this->actingAs($this->admin)->post("/admin/bookings/{$booking->id}/cancel")->assertSessionHas('error'); // المكتمل لا يُلغى

        // الإلغاء يحرّر الموعد
        $this->book(['booking_time' => '13:00'])->assertRedirect();
        $other = $this->latestBooking();
        $this->actingAs($this->admin)->post("/admin/bookings/{$other->id}/cancel")->assertSessionHas('success');
        $this->book(['booking_time' => '13:00'])->assertSessionHasNoErrors();
    }

    public function test_slots_endpoint_reports_remaining_and_excludes_own_booking(): void
    {
        $team = $this->provider('فريق العناية');
        $this->book(['product_id' => $this->service('مانيكير وبديكير')->id, 'service_provider_id' => $team->id])->assertRedirect();
        $booking = $this->latestBooking();

        $slots = $this->actingAs($this->admin)
            ->getJson("/admin/bookings/api/available-time-slots?date={$this->date}&service_provider_id={$team->id}")
            ->assertOk()->json('available_slots');
        $this->assertSame(1, collect($slots)->firstWhere('time', '10:00')['remaining']);

        $own = $this->actingAs($this->admin)
            ->getJson("/admin/bookings/api/available-time-slots?date={$this->date}&service_provider_id={$team->id}&exclude_booking_id={$booking->id}")
            ->json('available_slots');
        $this->assertSame(2, collect($own)->firstWhere('time', '10:00')['remaining']);
    }

    public function test_other_tenant_cannot_see_or_use_demo_bookings(): void
    {
        $this->book()->assertRedirect();
        $booking = $this->latestBooking();
        $mainAdmin = User::where('tenant_id', 1)->firstOrFail();

        $this->actingAs($mainAdmin)->get("/admin/bookings/{$booking->id}")->assertNotFound();
        $this->actingAs($mainAdmin)->get("/admin/bookings/{$booking->id}/edit")->assertNotFound();
        $this->actingAs($mainAdmin)->post("/admin/bookings/{$booking->id}/cancel")->assertNotFound();
        $this->actingAs($mainAdmin)->post('/admin/bookings', [
            'customer_id' => $booking->customer_id, 'product_id' => $booking->product_id,
            'service_provider_id' => $booking->service_provider_id, 'booking_date' => $this->date, 'booking_time' => '14:00',
        ])->assertSessionHasErrors(['customer_id', 'product_id', 'service_provider_id']);
    }

    public function test_view_only_user_cannot_change_bookings(): void
    {
        $role = Role::create(['name' => 'viewer', 'display_name' => 'مشاهد']);
        $role->permissions()->attach(Permission::where('name', 'bookings.view')->value('id'));
        $viewer = $this->inTenant(fn () => User::create([
            'name' => 'مشاهد', 'email' => 'viewer@example.test', 'password' => bcrypt('x-secret-123'),
            'role_id' => $role->id, 'is_active' => true,
        ]));
        $booking = $this->inTenant(fn () => Booking::where('status', 'pending')->firstOrFail());

        $this->actingAs($viewer)->get('/admin/bookings')->assertOk();
        $this->actingAs($viewer)->get('/admin/bookings/create')->assertForbidden();
        $this->actingAs($viewer)->post("/admin/bookings/{$booking->id}/confirm")->assertForbidden();
        $this->actingAs($viewer)->delete("/admin/bookings/{$booking->id}")->assertForbidden();
        $this->assertSame('pending', $this->reload($booking)->status);
    }
}
