<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

// حماية الـ API وحدود المحاولات وترويسات الأمان
class SecurityTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_admin_api_requires_token(): void
    {
        foreach (['/api/customers', '/api/bookings', '/api/abandoned-carts'] as $uri) {
            $this->getJson($uri)->assertUnauthorized();
        }
        $this->deleteJson('/api/products/1')->assertUnauthorized();
        $this->postJson('/api/working-schedules', [])->assertUnauthorized();

        // القراءة العامة للتطبيق تبقى متاحة
        $this->getJson('/api/products')->assertOk();
    }

    public function test_admin_login_is_rate_limited(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/admin/login', ['email' => 'x@example.test', 'password' => 'wrong-password']);
        }
        $this->post('/admin/login', ['email' => 'x@example.test', 'password' => 'wrong-password'])->assertStatus(429);
    }

    public function test_security_headers_and_no_wildcard_cors_on_pages(): void
    {
        $res = $this->get('/');
        $res->assertOk()->assertHeader('X-Frame-Options', 'SAMEORIGIN')->assertHeader('X-Content-Type-Options', 'nosniff');
        $this->assertNull($res->headers->get('Access-Control-Allow-Origin'));
    }

    public function test_health_endpoint_and_removed_debug_routes(): void
    {
        $this->get('/up')->assertOk()->assertJson(['status' => 'ok']);
        $this->get('/frontend/test-booking')->assertNotFound();
    }

    public function test_otp_is_compared_safely(): void
    {
        $future = now()->addMinute();
        $this->assertTrue(\App\Services\SmsService::verifyOtp('1234', 1234, $future));
        $this->assertFalse(\App\Services\SmsService::verifyOtp('1235', '1234', $future));
        $this->assertFalse(\App\Services\SmsService::verifyOtp('1234', '1234', now()->subMinute()));
    }
}
