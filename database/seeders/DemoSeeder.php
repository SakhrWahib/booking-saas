<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Plan;
use App\Models\Product;
use App\Models\Role;
use App\Models\ServiceProvider;
use App\Models\Tenant;
use App\Models\TimeSlot;
use App\Models\User;
use App\Models\WorkingSchedule;
use App\Support\Brand;
use App\Support\Tenancy;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * نشاط تجريبي مستقل (صالون) ببيانات وهمية لتجربة اللوحة:
 *   php artisan db:seed --class=DemoSeeder
 * لا يلمس أي نشاط آخر، ويمكن تشغيله أكثر من مرة (يعيد بناء النشاط التجريبي فقط).
 * ممنوع في الإنتاج إلا مع ALLOW_DEMO_SEED_IN_PRODUCTION=true.
 */
class DemoSeeder extends Seeder
{
    public const SLUG = 'demo';
    public const ADMIN_EMAIL = 'demo@mawidcloud.test';

    public function run(): void
    {
        if (app()->environment('production') && !config('platform.allow_demo_in_production')) {
            $this->command?->error('DemoSeeder ممنوع في الإنتاج. فعّل ALLOW_DEMO_SEED_IN_PRODUCTION صراحةً إن كنت متأكدًا.');
            return;
        }

        // إعادة بناء النشاط التجريبي فقط (حذفه يحذف بياناته عبر cascade)
        Tenant::where('slug', self::SLUG)->delete();

        $tenant = Tenant::create([
            'name' => 'صالون لمسة (تجريبي)', 'slug' => self::SLUG, 'is_active' => true,
            'plan_id' => Plan::where('slug', 'pro')->value('id'),
            'subscription_status' => 'active', 'subscription_ends_at' => today()->addYear(),
            'owner_name' => 'مدير تجريبي', 'notes' => 'نشاط تجريبي — بيانات وهمية',
        ]);

        $previous = Tenancy::current();
        Tenancy::set($tenant);

        $fixed = config('platform.demo_admin_password');
        $password = $fixed ?: Str::password(12, symbols: false);
        User::create([
            'name' => 'مدير الصالون', 'email' => self::ADMIN_EMAIL, 'password' => Hash::make($password),
            'role_id' => Role::where('name', 'admin')->value('id'), 'is_active' => true,
        ]);

        Brand::save([
            'name' => 'صالون لمسة', 'name_en' => 'Lamsa Salon', 'business_type' => 'salon',
            'tagline' => 'جمالك يبدأ بموعد', 'primary_color' => '#db2777',
            'phone' => '0500000000', 'whatsapp' => '966500000000', 'address' => 'الرياض — عنوان تجريبي',
        ]);

        $schedule = $this->schedule();
        $services = $this->services();
        $staff = $this->staff($schedule, $services);
        $customers = $this->customers();
        $this->bookings($services, $staff, $customers);

        Tenancy::set($previous);

        $this->command?->info("النشاط التجريبي جاهز: {$tenant->name}");
        $this->command?->info('الدخول: ' . self::ADMIN_EMAIL . ($fixed ? ' (كلمة المرور من DEMO_ADMIN_PASSWORD)' : " / {$password}"));
    }

    private function schedule(): WorkingSchedule
    {
        $days = collect(['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday'])
            ->mapWithKeys(fn ($d) => [$d => ['active' => $d !== 'friday']])->all();

        $schedule = WorkingSchedule::create([
            'schedule_name' => 'دوام الصالون', 'start_date' => today()->subMonths(2), 'end_date' => today()->addYear(),
            'active_days' => $days, 'is_default' => true, 'is_active' => true,
        ]);

        foreach (['10:00' => 'morning', '11:00' => 'morning', '13:00' => 'afternoon', '14:00' => 'afternoon', '16:00' => 'afternoon', '17:00' => 'evening', '19:00' => 'evening', '20:00' => 'evening'] as $time => $period) {
            TimeSlot::create(['working_schedule_id' => $schedule->id, 'time' => $time, 'period' => $period, 'sort_order' => (int) $time, 'is_active' => true]);
        }

        return $schedule;
    }

    private function services(): array
    {
        $items = [
            ['قص وتصفيف شعر', 'قص احترافي مع تصفيف', 120, [['option' => 'إضافة', 'name' => 'غسيل بالبخار', 'price' => 30]]],
            ['صبغة شعر كاملة', 'صبغة بألوان عالمية', 350, [['option' => 'إضافة', 'name' => 'علاج بروتين', 'price' => 150]]],
            ['مكياج سهرة', 'مكياج كامل للمناسبات', 400, []],
            ['مانيكير وبديكير', 'عناية كاملة بالأظافر', 180, [['option' => 'إضافة', 'name' => 'طلاء جل', 'price' => 60]]],
            ['تنظيف بشرة', 'جلسة تنظيف عميق للبشرة', 250, []],
        ];

        return array_map(fn ($s) => Product::create([
            'name' => $s[0], 'short_description' => $s[1], 'description' => $s[1] . '. (خدمة تجريبية)',
            'price' => $s[2], 'add_ons' => $s[3], 'is_active' => true,
        ]), $items);
    }

    private function staff(WorkingSchedule $schedule, array $services): array
    {
        $ids = array_map(fn ($p) => $p->id, $services);
        $staff = [
            ServiceProvider::create(['name' => 'نورة', 'phone' => '0500000101', 'capacity' => 1, 'work_type' => 'single_person', 'working_schedule_id' => $schedule->id, 'product_ids' => $ids, 'is_active' => true]),
            ServiceProvider::create(['name' => 'ريم', 'phone' => '0500000102', 'capacity' => 1, 'work_type' => 'single_person', 'working_schedule_id' => $schedule->id, 'product_ids' => array_slice($ids, 0, 3), 'is_active' => true]),
            ServiceProvider::create(['name' => 'فريق العناية', 'phone' => '0500000103', 'capacity' => 2, 'work_type' => 'multiple_persons', 'working_schedule_id' => $schedule->id, 'product_ids' => array_slice($ids, 3), 'is_active' => true]),
        ];

        foreach ($services as $service) {
            $providerIds = collect($staff)->filter(fn ($s) => in_array($service->id, $s->product_ids))->pluck('id')->values()->all();
            $service->forceFill(['service_provider_ids' => $providerIds])->save();
        }

        return $staff;
    }

    private function customers(): array
    {
        $names = ['سارة العتيبي', 'نوف القحطاني', 'هند الشمري', 'لمى الحربي', 'جود الدوسري', 'ريما المطيري', 'أمل الزهراني', 'دانة الغامدي', 'رهف السبيعي', 'شهد العنزي', 'منيرة البقمي', 'وعد الشهري'];

        return array_map(fn ($name, $i) => Customer::create([
            'name' => $name, 'phone' => '05999' . str_pad((string) ($i + 1), 5, '0', STR_PAD_LEFT),
        ]), $names, array_keys($names));
    }

    private function bookings(array $services, array $staff, array $customers): void
    {
        // [فرق الأيام عن اليوم، الوقت، الحالة]
        $plan = [
            [-20, '10:00', 'completed'], [-18, '13:00', 'completed'], [-15, '16:00', 'completed'], [-12, '11:00', 'cancelled'],
            [-10, '14:00', 'completed'], [-8, '17:00', 'completed'], [-6, '19:00', 'completed'], [-5, '10:00', 'paid'],
            [-3, '13:00', 'completed'], [-2, '16:00', 'cancelled'], [-1, '20:00', 'completed'],
            [0, '10:00', 'confirmed'], [0, '13:00', 'pending'], [0, '16:00', 'paid'], [0, '19:00', 'pending'],
            [1, '11:00', 'confirmed'], [1, '14:00', 'pending'], [2, '17:00', 'pending'], [3, '10:00', 'confirmed'],
            [4, '13:00', 'pending'], [6, '16:00', 'confirmed'], [8, '19:00', 'pending'], [10, '11:00', 'pending'],
        ];

        foreach ($plan as $i => [$offset, $time, $status]) {
            $date = today()->addDays($offset);
            if ($date->isFriday()) {
                $date->addDay(); // الجمعة إجازة في الجدول التجريبي
            }
            $service = $services[$i % count($services)];
            $provider = collect($staff)->first(fn ($s) => in_array($service->id, $s->product_ids));

            Booking::create([
                'booking_number' => 'DEMO-' . str_pad((string) ($i + 1), 4, '0', STR_PAD_LEFT),
                'customer_id' => $customers[$i % count($customers)]->id,
                'product_id' => $service->id, 'service_provider_id' => $provider->id,
                'booking_date' => $date->toDateString(), 'booking_time' => $time,
                'status' => $status, 'total_price' => $service->price,
                'booking_source' => $i % 3 === 0 ? 'admin_panel' : 'website',
                'confirmed_at' => in_array($status, ['confirmed', 'paid', 'completed']) ? $date->copy()->subDay() : null,
                'completed_at' => $status === 'completed' ? $date : null,
                'cancelled_at' => $status === 'cancelled' ? $date->copy()->subDays(2) : null,
            ]);
        }
    }
}
