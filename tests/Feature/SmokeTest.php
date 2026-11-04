<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

// يفتح كل صفحة GET بدون متغيرات (كزائر ثم كأدمن) ويتأكد أنه لا يوجد خطأ 500
class SmokeTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    private function getUris(): array
    {
        return collect(Route::getRoutes())
            ->filter(fn ($r) => in_array('GET', $r->methods()) && !str_contains($r->uri(), '{'))
            ->map(fn ($r) => '/' . ltrim($r->uri(), '/'))
            ->reject(fn ($u) => str_starts_with($u, '/_ignition') || str_starts_with($u, '/sanctum'))
            ->unique()->values()->all();
    }

    private function failures(): array
    {
        $failed = [];
        foreach ($this->getUris() as $uri) {
            $res = $this->get($uri);
            if ($res->getStatusCode() >= 500) {
                $msg = $res->exception ? $res->exception->getMessage() : '';
                $failed[] = $uri . ' => ' . $res->getStatusCode() . ' ' . mb_substr($msg, 0, 200);
            }
        }
        return $failed;
    }

    public function test_guest_pages_have_no_server_errors(): void
    {
        $failed = $this->failures();
        $this->assertEmpty($failed, "Guest errors:\n" . implode("\n", $failed));
    }

    public function test_admin_pages_have_no_server_errors(): void
    {
        $this->actingAs(User::firstOrFail());
        $failed = $this->failures();
        $this->assertEmpty($failed, "Admin errors:\n" . implode("\n", $failed));
    }
}
