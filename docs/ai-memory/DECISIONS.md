# Engineering decisions (newest first)

| Date | Decision | Why |
|---|---|---|
| 2026-11-07 | Booking prices are computed on the server (`BookingQuote`). Add-ons are picked by name, never priced from the request | The old form trusted the add-on prices sent by the browser, and admin bookings were saved without `total_price` |
| 2026-11-07 | One conflict rule everywhere: `isTimeSlotAvailable` counts pending, confirmed and paid bookings against `slotCapacity()` and respects time off. Editing excludes the booking itself | The old check ignored capacity and paid bookings, so it contradicted the slot list |
| 2026-11-07 | Opening days come only from the working schedule. The hardcoded "Friday closed" rule was removed from the admin slots endpoint | Each tenant has its own days off |
| 2026-11-07 | Per-action booking permissions (create, edit, delete, confirm, complete, cancel) in the controller constructor | The routes only checked `bookings.view` |
| 2026-11-07 | Status transitions are guarded: confirm from pending; complete from confirmed or paid; cancel from pending, confirmed or paid | Prevent invalid state changes |
| 2026-11-06 | Product identity is MawidCloud (`config/platform.php`). Brand defaults are neutral, and the old store's links became optional per-tenant settings (`brand.store_url`, `brand.android_app_url`) | The product must not be tied to any earlier business |
| 2026-11-06 | Chatbot disabled behind `CHATBOT_ENABLED` instead of being rewritten | It hardcodes an old business's prices and links. A rewrite is out of scope |
| 2026-11-06 | `DemoSeeder` builds a separate `demo` tenant, is idempotent, and refuses to run in production | Realistic test data without touching real tenants |
| 2026-11-05 | Admin API needs Sanctum. Public catalog, availability and OTP endpoints are throttled | The whole API was open, so anyone could read customers and delete bookings |
| 2026-11-05 | Custom `Cors` middleware (`*` on every page) replaced by `SecurityHeaders`. CORS only on `api/*` | Wildcard CORS on admin pages |
| 2026-11-05 | Own `backup:database` command (mysqldump + gzip, 14-day retention) instead of a package | Avoid extra dependencies |
| 2026-11-04 | Plans are enforced by `EnforcePlan` route-name prefixes, not per-route middleware | One place to maintain, and the menu reuses the same map |
| 2026-11-04 | Single-DB tenancy with a global scope, not a DB per tenant | Simple to run and sell, and enough for the target scale |
| 2026-11-04 | `User` is not globally scoped | Auth loads users before the tenant is resolved |
| 2026-11-04 | Legacy admin pages are unified by CSS (`.admin-main` overrides) before being rebuilt | Big visual win for few tokens. Rebuild page by page later |
| 2026-11-04 | Tailwind built by Vite (with a safelist for JS-built classes), not the CDN | The CDN is unsuitable for production. Dynamic classes need the safelist |
| 2026-11-04 | SMS provider removed (Taqnyat). Messages are only logged | Owner's request. The owner will plug in their own provider via `SmsService::send()` |

## Pitfalls already hit (do not repeat)
- `Product::add_ons` returns a Collection. Use `collect($p->add_ons ?? [])->map(fn ($a) => (array) $a)->all()`.
- Marking an invoice paid (`Invoice::markAsPaid`) sets the booking to `paid`. Complete the booking **after** paying the invoice.
- `Illuminate\Routing\Controller` already has a non-static `middleware()`, so `HasMiddleware` is fatal. Use `$this->middleware()` in the constructor.
- CI prints the app log summary on failure (step "Show application errors on failure").
- `"@context"` in Blade (JSON-LD) breaks on Laravel 12+. Write `"@@context"`.
- Carbon 3 `diffIn*` is signed and returns floats. Use `(int) abs(...)` or `now()->diffInHours($t, false)`.
- `config('app.asset_url')` once defaulted to an old domain, which broke CSS. It must stay `null`.
- Do not `pkill -f 'artisan serve'` inside `gh codespace ssh`, because it kills the ssh session itself.
- An SSH session in a Codespace cannot `git pull`. Use `git bundle` + `gh codespace cp` + `git pull /tmp/x.bundle main`.
