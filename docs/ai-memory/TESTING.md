# Testing

- CI: `.github/workflows/ci.yml` on every push, using MySQL 8 and PHP 8.3. It runs `view:cache`, the Vite build, `php artisan test`, production caches (config/route/event) and `backup:database`.
- `upgrade-*` branches run `composer update` and upload `composer.lock` as an artifact.
- No local PHP on the owner's PC: push a branch, then `gh run watch <id> --exit-status`. Get failures with `gh run view <id> --log-failed`.
- Tests use `RefreshDatabase` + `$seed = true`. `tests/TestCase.php` resets `Tenancy` before each test.

| Test | Covers |
|---|---|
| SmokeTest | every GET route without params, as guest and as admin: no 500s |
| DashboardTest | new dashboard, grouped menu, reports page, guest redirect |
| AdminPagesTest | bookings list, search by phone, status filter, details, create page, confirm; customers and services lists |
| AvailabilityTest | capacity per slot, paid blocks, cancelled frees, single person = 1, time off + calendar |
| SettingsTest | business settings save and the public site shows them |
| TenancyTest | isolation between tenants, per-tenant phone uniqueness, per-tenant site, inactive tenant 503 |
| PlatformTest | super admin only, one-step tenant onboarding, plan features hide/block, limits, expired subscription 402 |
| SecurityTest | API needs a token, login rate limit, security headers, `/up`, removed debug routes, OTP compare |
| DemoSeederTest | demo tenant data counts, idempotent, demo admin login + dashboard pages, blocked in production, no previous-business branding on `/` and login |

## Last results
| Date | Branch | Result |
|---|---|---|
| 2026-11-05 | main (c88cf61) | 27 passed, 131 assertions |
| 2026-11-06 | day1-foundation | see SESSION_HANDOFF.md |
