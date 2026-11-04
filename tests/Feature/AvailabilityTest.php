<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProviderTimeOff;
use App\Models\ServiceProvider;
use App\Models\TimeSlot;
use App\Models\User;
use App\Models\WorkingSchedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

// المواعيد المتاحة تحترم الطاقة الاستيعابية والإجازات
class AvailabilityTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    private ServiceProvider $provider;
    private string $date;

    protected function setUp(): void
    {
        parent::setUp();

        $days = collect(['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday'])
            ->mapWithKeys(fn ($d) => [$d => ['active' => true]])->all();
        $schedule = WorkingSchedule::create([
            'schedule_name' => 'كل الأيام', 'start_date' => today()->subDay(), 'end_date' => today()->addYear(),
            'active_days' => $days, 'is_active' => true,
        ]);
        TimeSlot::create(['working_schedule_id' => $schedule->id, 'time' => '10:00', 'period' => 'morning', 'is_active' => true]);
        TimeSlot::create(['working_schedule_id' => $schedule->id, 'time' => '12:00', 'period' => 'afternoon', 'is_active' => true]);

        $this->provider = ServiceProvider::create([
            'name' => 'فريق أ', 'phone' => '0533333333', 'capacity' => 2, 'work_type' => 'multiple_persons',
            'working_schedule_id' => $schedule->id, 'is_active' => true,
        ]);
        $this->date = today()->addDays(3)->toDateString();
    }

    private function book(string $time, string $status = 'pending'): void
    {
        static $n = 0;
        $n++;
        Booking::create([
            'booking_number' => "AV-$n", 'customer_id' => Customer::create(['name' => "ع$n", 'phone' => '05000000' . str_pad($n, 2, '0', STR_PAD_LEFT)])->id,
            'product_id' => Product::create(['name' => 'خ', 'short_description' => 'و', 'description' => 'و', 'price' => 50, 'is_active' => true])->id,
            'service_provider_id' => $this->provider->id, 'booking_date' => $this->date, 'booking_time' => $time,
            'status' => $status, 'total_price' => 50,
        ]);
    }

    private function times(): array
    {
        return array_column(Booking::getAvailableTimeSlotsWithPeriods($this->date, $this->provider->id), 'remaining', 'time');
    }

    public function test_capacity_allows_multiple_bookings_per_slot(): void
    {
        $this->assertSame(['10:00' => 2, '12:00' => 2], $this->times());

        $this->book('10:00');
        $this->assertSame(['10:00' => 1, '12:00' => 2], $this->times());

        $this->book('10:00', 'paid'); // المدفوع يحجز الموعد
        $this->assertSame(['12:00' => 2], $this->times());

        $this->book('12:00', 'cancelled'); // الملغي لا يحجز
        $this->assertSame(['12:00' => 2], $this->times());
    }

    public function test_single_person_has_capacity_one(): void
    {
        $this->provider->update(['work_type' => 'single_person']);
        $this->book('10:00');
        $this->assertSame(['12:00' => 1], $this->times());
    }

    public function test_time_off_blocks_all_slots_and_admin_page_works(): void
    {
        $admin = User::firstOrFail();
        $this->actingAs($admin)->post('/admin/time-offs', [
            'service_provider_id' => $this->provider->id, 'start_date' => $this->date, 'end_date' => $this->date, 'reason' => 'سفر',
        ])->assertRedirect('/admin/time-offs');

        $this->assertSame([], $this->times());
        $this->actingAs($admin)->get('/admin/time-offs')->assertOk()->assertSee('فريق أ')->assertSee('سفر');
        $this->actingAs($admin)->get('/admin/bookings/calendar?month=' . today()->addDays(3)->month . '&year=' . today()->addDays(3)->year)->assertOk()->assertSee('في إجازة');

        $this->actingAs($admin)->delete('/admin/time-offs/' . ProviderTimeOff::firstOrFail()->id)->assertRedirect();
        $this->assertCount(2, $this->times());
    }
}
