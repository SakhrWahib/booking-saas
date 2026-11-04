# Booking SaaS

نظام حجوزات مبني على Laravel 13 وPHP 8.3، فيه لوحة أدمن وواجهة حجز للعملاء.

## التشغيل من المتصفح (GitHub Codespaces)
1. من صفحة المستودع: **Code ← Codespaces ← Create codespace on main**.
2. انتظر حتى ينتهي التجهيز التلقائي (`.devcontainer/setup.sh`): تثبيت الحزم، وقاعدة بيانات MySQL، والتهجير والبيانات الأولية، وبناء الواجهة.
3. يفتح الموقع على المنفذ **8000** (من تبويب Ports).
4. لوحة الأدمن: `/admin/login`، وبيانات الدخول الأولية موجودة في `database/seeders/AdminSeeder.php`.

لو توقف السيرفر: `php artisan serve --host=0.0.0.0 --port=8000`

## SaaS مبسّط (علامة تجارية لكل عميل)
اسم النشاط والجوال والواتساب واللون والشعار كلها تتغير من `.env` (ملف `config/brand.php`):

```
BRAND_NAME="اسم النشاط"
BRAND_PHONE=05XXXXXXXX
BRAND_WHATSAPP=9665XXXXXXXX
BRAND_PRIMARY_COLOR=#7c3aed
```

## الرسائل (SMS)
النظام **غير مربوط بأي شركة رسائل**. رمز التحقق وإشعارات الحجز تُكتب في `storage/logs/laravel.log` فقط.
لربط شركة رسائل لاحقًا: عدّل الدالة `send()` في `app/Services/SmsService.php`.
