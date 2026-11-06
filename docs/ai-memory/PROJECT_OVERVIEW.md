# Project overview and feature status

**MawidCloud / موعد كلاود**: an independent software product owned by the owner (Sakhr). Sold as SaaS to any appointment-based business (salons, car wash, cleaning, pet care, maintenance, consulting, clinics). It is not tied to any earlier store. Each business (tenant) brands itself.

Repo: `SakhrWahib/booking-saas` (currently **public**; changing visibility needs the owner's OK).

## Feature status (✅ done and tested, 🟡 works but old UI or partial, ❌ missing)
| Area | Status | Notes |
|---|---|---|
| Multi-tenancy (data isolation, per-tenant settings, domain/subdomain) | ✅ | `TenancyTest` |
| Platform owner panel `/platform` (tenants, plans, MRR) | ✅ | `PlatformTest` |
| Plans: limits (staff/services/monthly bookings) and features | ✅ | manual subscription activation, no online billing |
| Admin shell (grouped menu, topbar, RTL/LTR, mobile) | ✅ | `config/admin-menu.php` |
| Dashboard home, bookings list, booking details, customers list, services list, calendar, time off, business settings | ✅ new UI | |
| Availability: capacity per slot, time off, paid bookings block the slot | ✅ | `AvailabilityTest` |
| Booking create/edit forms | 🟡 old UI | Day 2 |
| Service, staff, schedule, customer create/edit | 🟡 old UI | Day 3 |
| Service categories | ❌ | no table yet (Day 3) |
| Invoices, coupons, users and roles, reports, notifications | 🟡 old UI | Day 4 |
| Customer booking flow (8 steps) | 🟡 old UI | Day 5 |
| Security: API behind Sanctum, rate limits, headers, OTP | ✅ | `SecurityTest` |
| Daily DB backup, `/up` health check | ✅ | |
| Demo tenant seeder | ✅ | `DemoSeeder` |
| Online payments (Moyasar/Tap), SMS provider, production launch | ❌ postponed | after the 5 days |
| Chatbot | disabled | written for a previous business; `CHATBOT_ENABLED=false` |
