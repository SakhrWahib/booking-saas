# سجل التقدم (Booking SaaS)

ملف مختصر لتجنّب إعادة التحليل. حدّثه بعد كل دفعة عمل.

## الوضع الحالي
- Laravel 13.40 وPHP 8.3 وMySQL 8 وTailwind 3 عبر Vite (بدون CDN).
- CI في `.github/workflows/ci.yml`: `SmokeTest` يفتح كل صفحات GET كزائر وكأدمن، ومعه اختبارات الإعدادات ولوحة التحكم.
- إعدادات النشاط: `App\Support\Brand` و`config/brand.php`، ومحفوظة في `system_settings` (category=brand).
- قائمة لوحة التحكم: `config/admin-menu.php`، مرتبطة بالصلاحيات والأدوار.
- مكوّنات موحّدة: `x-stat-card` و`x-status-badge`.

## المراحل
- [x] **1. التصميم وتجربة المستخدم:**
  - [x] قالب لوحة جديد (`layouts/app`) فيه قائمة مجمّعة وشريط علوي، ويدعم RTL/LTR والجوال.
  - [x] رئيسية جديدة (`Admin\OverviewController`).
  - [x] التحليلات القديمة نُقلت إلى `/admin/reports`.
  - [x] نظام تصميم موحّد في `resources/css/app.css`: card وbtn-* وinput وlabel وdata-table، ومعه `x-page-header`.
  - [x] صفحات الحجوزات والعملاء والخدمات أُعيد تصميمها. في الحجوزات تبويبات حالة وفلاتر وإجراءات سريعة، والبحث صار بالجوال كمان.
  - [ ] باقي الصفحات الداخلية (عرض/تعديل/إنشاء) تستخدم التصميم القديم داخل القالب الجديد. وحّدها تدريجيًا.
- [x] **2. الحجوزات والفريق:**
  - [x] إجازات مقدّمي الخدمة: جدول `provider_time_offs`، و`AdminTimeOffController`، وصفحة `/admin/time-offs`.
  - [x] الطاقة الاستيعابية: `ServiceProvider::slotCapacity()` (فرد = 1، فريق = capacity). الحجز المدفوع صار يحجز الموعد، وكل موعد يرجع معه `remaining`.
  - [x] التقويم انعاد بناؤه: فلتر المقدّم يشتغل الحين، والإجازات ظاهرة، وألوان حسب الحالة، والأسبوع يبدأ السبت.
  - [x] الصفحات القديمة صارت موحّدة تلقائيًا عن طريق `.admin-main` في app.css: الأزرق تحوّل للون النشاط، وتوحّد شكل الحقول.
  - [x] صفحة تفاصيل الحجز انعاد تصميمها.
  - [x] نموذج الحجز يعرض عدد الأماكن المتبقية.
  - [x] safelist في Tailwind للألوان اللي تنبني في JS.
- [x] **3. Multi-tenancy:** عزل البيانات، والتحقق مقيّد بالنشاط، والإعدادات لكل نشاط، وتحديد النشاط بالنطاق. 17 اختبار (76 تحقق) كلها نجحت. القسم تحت فيه التفاصيل.
- [ ] **4.** لوحة مالك المنصة والباقات والاشتراكات.
- [ ] **5.** الأمان والأداء والنسخ الاحتياطي والإطلاق.

## ملاحظات
- منطق المواعيد الموحّد: `Booking::getAvailableTimeSlotsWithPeriods()`، وتستخدمه اللوحة والموقع والـ API.
- لا تستخدم `"@context"` في Blade. اكتبها `"@@context"`.
- `asset_url` افتراضيه null (كان catshoop.store ويكسر CSS).
- `Product::serviceProviders()` مو علاقة Eloquent. المعرّفات محفوظة كـ JSON في `service_provider_ids`.
- الاختبارات: 12 اختبار (51 تحقق) كلها نجحت.

## تعدد الأنشطة (المرحلة 3)
- جدول `tenants`، وعمود `tenant_id` في 13 جدول (migration `2026_11_05_000002`). البيانات القديمة كلها في النشاط رقم 1.
- `App\Models\Concerns\BelongsToTenant`: نطاق عام (global scope) يفلتر بالنشاط، وكل سجل جديد يأخذ tenant_id تلقائيًا.
- `User` ما عليه نطاق عام، لأن تسجيل الدخول يقرأ المستخدم قبل ما يتحدد النشاط. استعلامات المستخدمين مقيّدة يدويًا في UserController وUserManagementController.
- `App\Support\Tenancy` يحدد النشاط الحالي، والدالة `tenant_id()` ترجع رقمه. الميدلوير `IdentifyTenant` يشتغل قبل SubstituteBindings:
  - لوحة التحكم: نشاط المستخدم المسجّل.
  - الموقع العام: النطاق (`domain`) أو `slug.TENANCY_CENTRAL_DOMAIN`.
  - إذا ما انطبق شي: النشاط الأول.
- قواعد التحقق `unique` و`exists` على جداول النشاط صارت مقيّدة بـ `tenant_id`.
- الإعدادات (Brand) لها كاش منفصل لكل نشاط.
- أي `DB::table` جديد على جداول النشاط لازم يضيف `tenant_id` يدويًا.
- على ويندوز: Git Bash يحذف الشرطة المائلة (\) من الأوامر المضمّنة في heredoc. لكتابة namespaces استخدم Edit، أو `B=$(printf "\x5c")` مع sed.

## المنصة والباقات (المرحلة 4)
- جداول: `plans`، وفي tenants حقول الاشتراك (plan_id وsubscription_status وtrial_ends_at وsubscription_ends_at وowner_*)، وفي users الحقل `is_super_admin`. الـ migration رقمه `2026_11_05_000003`. النشاط 1 على باقة "الأعمال" ومفعّل.
- لوحة `/platform` (الميدلوير `super_admin`)، والكنترولر `Platform\PlatformController`:
  - نظرة عامة: MRR، والاشتراكات اللي تنتهي قريبًا.
  - الأنشطة: إنشاء نشاط مع مديره وجدول دوام افتراضي وإعداداته بخطوة وحدة.
  - الباقات.
- `EnforcePlan` (ضمن مجموعة web):
  - الاشتراك المنتهي يوقف اللوحة (402، `errors.subscription`)، والبيانات تبقى.
  - الخصائص تنحجب حسب `ROUTE_FEATURES` (coupons وinvoices وloyalty وreports وabandoned_carts وmobile_app)، والقائمة تخفيها تلقائيًا.
- الحدود: `plan_allows('services'|'staff'|'monthly_bookings')` داخل store للخدمات والمقدّمين والحجز (اللوحة والموقع).
- ما فيه دفع إلكتروني للاشتراكات. مالك المنصة يفعّل الاشتراك يدويًا من صفحة النشاط.

## الأمان والأداء والإطلاق (المرحلة 5)
- API: عمليات الإدارة وبيانات العملاء (26 مسار) تحتاج `auth:sanctum`. القراءة العامة والحجز ورموز OTP بدون توكن، لكن عليها حد للمحاولات.
- حدود المحاولات في `RouteServiceProvider`:
  - `login`: 5 في الدقيقة.
  - `otp`: 5 في الدقيقة لكل IP، و20 في الساعة لكل جوال.
  - `public-forms`: 30 في الدقيقة.
- حذفنا مسارات الاختبار `test-booking` (كانت تكشف الجلسة) و`test-start-booking`.
- رمز OTP صار يتولّد بـ `random_int` ويتقارن بـ `hash_equals`.
- `SecurityHeaders` صار مكان `Cors` المخصص (كان يرسل `*` لكل الصفحات). CORS للـ api/* فقط عن طريق config/cors.php.
- أداء: فهارس مركّبة تبدأ بـ tenant_id (migration رقم `2026_11_05_000004`). الـ CI يتأكد إن config وroute وevent cache تشتغل.
- نسخ احتياطي: الأمر `backup:database --keep=14` يشتغل يوميًا 03:00، والملفات في `storage/app/backups`. الـ CI يجرّبه.
- فحص الصحة: `/up`. دليل التشغيل في `DEPLOYMENT.md`.
