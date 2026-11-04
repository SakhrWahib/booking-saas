<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_admin_can_save_brand_settings(): void
    {
        $this->actingAs(User::firstOrFail())
            ->put('/admin/settings', [
                'name' => 'مغسلة النجم',
                'business_type' => 'car_wash',
                'primary_color' => '#0ea5e9',
                'phone' => '0500000000',
            ])
            ->assertRedirect('/admin/settings');

        \App\Support\Brand::load();
        $this->assertSame('مغسلة النجم', config('brand.name'));
        $this->assertSame('الفني', config('brand.provider_label'));

        $this->get('/')->assertOk()->assertSee('مغسلة النجم');
    }
}
