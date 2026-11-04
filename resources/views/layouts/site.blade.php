<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('brand.name') . ' | ' . config('brand.business_label'))</title>
    <meta name="description" content="@yield('description', config('brand.tagline'))">
    <link rel="icon" href="{{ config('brand.logo') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css'])
    <style>:root { --brand-color: {{ config('brand.primary_color') }}; }</style>
    @stack('head')
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased">
    <header class="sticky top-0 z-30 border-b border-slate-200 bg-white/90 backdrop-blur">
        <div class="mx-auto flex h-16 max-w-6xl items-center justify-between px-4">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <img src="{{ config('brand.logo') }}" alt="{{ config('brand.name') }}" class="h-9 w-9 rounded-lg object-contain">
                <span class="text-lg font-extrabold">{{ config('brand.name') }}</span>
            </a>
            <nav class="flex items-center gap-1 text-sm font-semibold sm:gap-3">
                <a href="{{ route('products') }}" class="rounded-lg px-3 py-2 hover:bg-slate-100">الخدمات</a>
                @if(session('customer_id'))
                    <a href="{{ route('frontend.customer.profile') }}" class="rounded-lg px-3 py-2 hover:bg-slate-100"><i class="fa-regular fa-user ms-1"></i> حسابي</a>
                @else
                    <a href="{{ route('frontend.customer.login') }}" class="rounded-lg px-3 py-2 hover:bg-slate-100">دخول</a>
                @endif
                <a href="{{ route('products') }}" class="rounded-lg bg-brand px-4 py-2 text-white hover:opacity-90">احجز الآن</a>
            </nav>
        </div>
    </header>

    @if(session('success'))
        <div class="mx-auto mt-4 max-w-6xl px-4"><div class="rounded-lg bg-green-50 p-3 text-green-700">{{ session('success') }}</div></div>
    @endif
    @if(session('error'))
        <div class="mx-auto mt-4 max-w-6xl px-4"><div class="rounded-lg bg-red-50 p-3 text-red-700">{{ session('error') }}</div></div>
    @endif

    <main>@yield('content')</main>

    <footer class="mt-16 border-t border-slate-200 bg-white">
        <div class="mx-auto grid max-w-6xl gap-6 px-4 py-10 text-sm sm:grid-cols-3">
            <div>
                <div class="mb-2 text-base font-extrabold">{{ config('brand.name') }}</div>
                <p class="text-slate-500">{{ config('brand.tagline') }}</p>
            </div>
            <div class="space-y-2 text-slate-600">
                @if(config('brand.phone'))<div><i class="fa-solid fa-phone ms-2 text-brand"></i><a href="tel:{{ config('brand.phone') }}" dir="ltr">{{ config('brand.phone') }}</a></div>@endif
                @if(config('brand.email'))<div><i class="fa-regular fa-envelope ms-2 text-brand"></i>{{ config('brand.email') }}</div>@endif
                @if(config('brand.address'))<div><i class="fa-solid fa-location-dot ms-2 text-brand"></i>{{ config('brand.address') }}</div>@endif
            </div>
            <div class="text-slate-400 sm:text-left">© {{ date('Y') }} {{ config('brand.name') }}</div>
        </div>
    </footer>

    @if(config('brand.whatsapp'))
        <a href="https://wa.me/{{ config('brand.whatsapp') }}" target="_blank" rel="noopener" aria-label="واتساب"
           class="fixed bottom-5 left-5 z-40 flex h-14 w-14 items-center justify-center rounded-full bg-green-500 text-2xl text-white shadow-lg hover:bg-green-600">
            <i class="fa-brands fa-whatsapp"></i>
        </a>
    @endif
    @stack('scripts')
</body>
</html>
