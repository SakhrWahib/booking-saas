# Architecture, database and routes map

## Tenancy (core idea)
- One database. `tenants` table, and `tenant_id` on 13 tables: users, customers, products, service_providers, working_schedules, time_slots, bookings, invoices, coupons, abandoned_carts, notifications, provider_time_offs, system_settings.
- `app/Models/Concerns/BelongsToTenant.php`: a global scope `tenant` that filters by the current tenant, and a `creating` hook that fills `tenant_id`. Bypass it with `withoutGlobalScope('tenant')`.
- `User` has **no** global scope, because auth loads the user before the tenant is known. User queries are scoped manually in `Admin/UserController` and `UserManagementController`.
- `app/Support/Tenancy.php` holds the current tenant (default: first tenant). The `tenant_id()` helper is in `app/helpers.php`.
- Middleware `IdentifyTenant` (web+api, before SubstituteBindings) picks the tenant:
  - admin paths: the logged-in user's tenant.
  - otherwise: the request host (`tenants.domain`, or `slug.TENANCY_CENTRAL_DOMAIN`).
  - if nothing matches: the default tenant.
- Middleware `EnforcePlan` (web): an expired subscription returns 402 (`errors.subscription`). `ROUTE_FEATURES` blocks routes by plan feature, and the menu hides them.
- Validation `unique:`/`exists:` on tenant tables carry `tenant_id` (for example `exists:products,id,tenant_id,' . tenant_id()`).
- Raw `DB::table()` on tenant tables must set or filter `tenant_id` by hand.

## Branding
- `config/brand.php` holds defaults. Per-tenant values live in `system_settings` (category=brand) and are loaded by `App\Support\Brand::load()` (cache key per tenant). Saved with `Brand::save()`.
- The default name is the tenant record's name. Presets (business types) set the `service_label`/`provider_label` wording.
- `config/platform.php` is the platform's own identity (MawidCloud), with the chatbot and demo-seed flags.

## Key files
| Concern | Files |
|---|---|
| Availability (shared by admin, web, API) | `Booking::getAvailableTimeSlotsWithPeriods()`, `ServiceProvider::slotCapacity()/isOnTimeOff()` |
| Admin layout and menu | `resources/views/layouts/app.blade.php`, `config/admin-menu.php` |
| Design system | `resources/css/app.css` (`.card .btn-* .input .label .data-table`, `.admin-main` legacy overrides), components `x-page-header x-stat-card x-status-badge` |
| Public site (new) | `layouts/site.blade.php`, `frontend/index`, `frontend/products`, `frontend/partials/service-card` |
| Public booking steps (old) | `frontend/booking/*.blade.php` on `layouts/main-layout.blade.php` (5000+ lines) |
| Platform | `app/Http/Controllers/Platform/PlatformController.php`, `resources/views/platform/*` |
| Plans | `app/Models/Plan.php` (FEATURES, LIMITS), `Tenant::canAdd()/usage()/hasValidSubscription()`, helpers `plan_allows()`, `plan_limit_message()` |
| SMS (logs only) | `app/Services/SmsService.php` (`send()` is the single hook) |

## Database notes
- `products.service_provider_ids` and `service_providers.product_ids` are JSON arrays, not pivot tables. `Product::serviceProviders()` is not an Eloquent relation.
- `working_schedules.active_days` is JSON `{saturday: {active: bool}, ...}`. The slots are in `time_slots` (time, period).
- Booking statuses: pending, confirmed, paid, completed, cancelled. pending, confirmed and paid occupy a slot.
- Unique per tenant: customers.phone, coupons.code, system_settings.key. `booking_number` and `users.email` are globally unique.
- Reports use MySQL date functions, so SQLite is not supported.

## Routes map (routes/web.php)
- `/dashboard` → `Admin\OverviewController`. `/admin/reports` → `DashboardController` (old analytics).
- `/admin/*`: resources per module, guarded by `permission:<module>.<action>` groups. Time off is at `/admin/time-offs`, settings at `/admin/settings`.
- `/platform/*`: super admin only (`super_admin` middleware, `users.is_super_admin`).
- Public: `/`, `/products`, `/frontend/booking/*` (8 steps), `/frontend/customer/*` (OTP login). `/up` is the health check.
- `routes/api.php`: admin and customer-data operations need `auth:sanctum`. Catalog, availability and OTP are public but throttled.
- Rate limiters are in `RouteServiceProvider`: `login`, `otp`, `public-forms`, `api`.
