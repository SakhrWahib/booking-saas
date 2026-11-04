<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_admin_sees_new_dashboard_and_grouped_menu(): void
    {
        $this->actingAs(User::firstOrFail())
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('حجوزات اليوم')
            ->assertSee('إيراد الشهر')
            ->assertSee('الخدمات والفريق')
            ->assertSee(route('admin.settings.edit'), false);
    }

    public function test_reports_page_keeps_old_analytics(): void
    {
        $this->actingAs(User::firstOrFail())->get('/admin/reports')->assertOk();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/dashboard')->assertRedirect();
    }
}
