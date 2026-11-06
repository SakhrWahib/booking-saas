# Known issues (open)

| # | Issue | Impact | Planned |
|---|---|---|---|
| 1 | Booking create/edit forms use the old UI (1000+ line Blade with inline JS) | Hard to use | Day 2 |
| 2 | No service categories table | Services can't be grouped | Day 3 |
| 3 | Service/staff/schedule/customer forms use the old UI | Setup is harder for owners | Day 3 |
| 4 | Invoices, coupons, users and roles, reports pages use the old UI | Inconsistent look | Day 4 |
| 5 | Public booking flow (8 steps) uses `layouts/main-layout` (5000+ lines, old styling) | Customer experience | Day 5 |
| 6 | Static SEO files in `public/` (sitemap*.xml, feed.xml, structured-data.json, faq/reviews-schema.json, humans.txt, logo.php, logo1.php, banar.php) and unused `frontend/legacy-*.blade.php` still describe a previous business | Wrong SEO for tenants | **Needs owner OK to delete or replace** with per-tenant dynamic SEO |
| 7 | `app/Config/ChatBotConfig.php` and `ChatBotController` hardcode a previous business | Chatbot is disabled | Rewrite or remove (owner decision) |
| 8 | API booking show/cancel now need a staff token, so a mobile customer app can't use them yet | Only if a mobile app exists | Customer API auth later |
| 9 | PR #6 (`fix/codespaces-port-8000`, publishes port 8000 in docker-compose) is open from another session | Without it the port link may 502. Workaround: `gh codespace ssh -c <name> -- -N -L 8000:127.0.0.1:8000`, then open http://localhost:8000 | Owner/other session to merge |
| 10 | Old Codespace `super-duper-memory-…` is on the pre-rewrite history (stopped) | Shows an old version | **Needs owner OK to delete** |
| 11 | Old public repo `SakhrWahib/-beginning1` still holds old phone numbers and Taqnyat code in history | Privacy | **Owner decision** (make private or delete) |
| 12 | Repo `booking-saas` is public | Code is visible | **Owner decision** |
| 13 | Online payments, SMS provider, production server | Not launch-ready | After the 5-day plan |
