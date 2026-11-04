<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>الاشتراك منتهي | {{ $tenant->name }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="flex min-h-screen items-center justify-center bg-slate-100 p-4 font-sans">
    <div class="card max-w-md p-8 text-center">
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-amber-100 text-2xl">⏳</div>
        <h1 class="mb-2 text-xl font-extrabold">انتهى اشتراك «{{ $tenant->name }}»</h1>
        <p class="mb-6 text-sm text-slate-500">بياناتك محفوظة بالكامل. جدّد الاشتراك لتعود لوحة التحكم للعمل فورًا.</p>
        <form method="POST" action="{{ route('admin.logout') }}">@csrf<button class="btn-secondary">تسجيل الخروج</button></form>
    </div>
</body>
</html>
