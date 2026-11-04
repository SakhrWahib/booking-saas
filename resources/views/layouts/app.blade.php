@php
    $locale = App::getLocale() === 'en' ? 'en' : 'ar';
    $isRtl = $locale === 'ar';
    $user = auth()->user();
    $canSee = function (array $item) use ($user) {
        if (!$user) return false;
        if (isset($item['role']) && !$user->hasRole($item['role'])) return false;
        if (isset($item['permission']) && !$user->hasPermission($item['permission'])) return false;
        return \Illuminate\Support\Facades\Route::has($item['route']);
    };
    $menu = collect(config('admin-menu'))
        ->map(fn ($group) => [...$group, 'items' => array_values(array_filter($group['items'], $canSee))])
        ->filter(fn ($group) => count($group['items']) > 0);
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', $isRtl ? 'لوحة التحكم' : 'Dashboard') | {{ config('brand.name') }}</title>
    <link rel="icon" href="{{ config('brand.logo') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@emran-alhaddad/saudi-riyal-font/index.css">
    @vite(['resources/css/app.css'])
    <style>
        @include('layouts.app-styles')
        :root { --brand-color: {{ config('brand.primary_color') }}; }
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-100 font-sans text-slate-800 antialiased" x-data="{ menuOpen: false }">

    {{-- القائمة الجانبية --}}
    <aside class="fixed inset-y-0 {{ $isRtl ? 'right-0' : 'left-0' }} z-40 flex w-64 flex-col bg-slate-900 text-slate-300 transition-transform duration-200 lg:translate-x-0"
           :class="menuOpen ? 'translate-x-0' : '{{ $isRtl ? 'translate-x-full' : '-translate-x-full' }}'">
        <a href="{{ route('dashboard') }}" class="flex h-16 shrink-0 items-center gap-3 border-b border-white/10 px-5">
            <img src="{{ config('brand.logo') }}" alt="" class="h-9 w-9 rounded-lg bg-white object-contain p-1">
            <div class="min-w-0">
                <div class="truncate font-bold text-white">{{ $isRtl ? config('brand.name') : (config('brand.name_en') ?: config('brand.name')) }}</div>
                <div class="truncate text-xs text-slate-400">{{ config('brand.business_label') }}</div>
            </div>
        </a>
        <nav class="flex-1 overflow-y-auto px-3 py-4">
            @foreach($menu as $group)
                <div class="mb-1 mt-4 px-3 text-[11px] font-bold uppercase tracking-wider text-slate-500 first:mt-0">{{ $group['title'][$locale] }}</div>
                @foreach($group['items'] as $item)
                    @php $active = request()->routeIs(...explode('|', $item['active'])); @endphp
                    <a href="{{ route($item['route']) }}"
                       class="mb-0.5 flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-semibold transition {{ $active ? 'bg-brand text-white shadow' : 'hover:bg-white/5 hover:text-white' }}">
                        <i class="fa-solid {{ $item['icon'] }} w-5 text-center {{ $active ? '' : 'text-slate-500' }}"></i>
                        <span>{{ $item['label'][$locale] }}</span>
                    </a>
                @endforeach
            @endforeach
        </nav>
        <a href="{{ route('home') }}" target="_blank" class="m-3 flex items-center justify-center gap-2 rounded-lg border border-white/10 px-3 py-2 text-sm font-semibold hover:bg-white/5 hover:text-white">
            <i class="fa-solid fa-arrow-up-right-from-square"></i> {{ $isRtl ? 'عرض الموقع' : 'View site' }}
        </a>
    </aside>
    <div x-cloak x-show="menuOpen" @click="menuOpen = false" class="fixed inset-0 z-30 bg-slate-900/50 lg:hidden"></div>

    {{-- الشريط العلوي --}}
    <div class="{{ $isRtl ? 'lg:mr-64' : 'lg:ml-64' }}">
        <header class="sticky top-0 z-20 flex h-16 items-center justify-between border-b border-slate-200 bg-white/90 px-4 backdrop-blur lg:px-8">
            <div class="flex items-center gap-3">
                <button type="button" @click="menuOpen = true" class="rounded-lg p-2 text-slate-600 hover:bg-slate-100 lg:hidden" aria-label="menu">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
                <h1 class="text-base font-bold text-slate-900 sm:text-lg">@yield('page-title', $isRtl ? 'لوحة التحكم' : 'Dashboard')</h1>
            </div>
            <div class="flex items-center gap-1 sm:gap-2">
                <a href="{{ route('switch.language', $isRtl ? 'en' : 'ar') }}" class="rounded-lg px-3 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100">{{ $isRtl ? 'EN' : 'ع' }}</a>
                @if(Route::has('admin.notifications.index') && $user?->hasPermission('system.notifications'))
                    <a href="{{ route('admin.notifications.index') }}" class="relative rounded-lg p-2 text-slate-600 hover:bg-slate-100" aria-label="notifications">
                        <i class="fa-regular fa-bell text-lg"></i>
                        <span id="unread-badge" class="absolute -top-0.5 {{ $isRtl ? '-left-0.5' : '-right-0.5' }} hidden min-w-[18px] rounded-full bg-red-500 px-1 text-center text-[10px] font-bold leading-[18px] text-white"></span>
                    </a>
                @endif
                <div class="relative" x-data="{ open: false }">
                    <button type="button" @click="open = !open" @click.outside="open = false" class="flex items-center gap-2 rounded-lg px-2 py-1.5 hover:bg-slate-100">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-brand text-sm font-bold text-white">{{ mb_substr($user?->name ?? '?', 0, 1) }}</span>
                        <span class="hidden text-sm font-semibold sm:inline">{{ $user?->name }}</span>
                        <i class="fa-solid fa-chevron-down text-xs text-slate-400"></i>
                    </button>
                    <div x-cloak x-show="open" class="absolute {{ $isRtl ? 'left-0' : 'right-0' }} mt-2 w-48 overflow-hidden rounded-xl border border-slate-200 bg-white py-1 shadow-lg">
                        <div class="border-b border-slate-100 px-4 py-2 text-xs text-slate-500">{{ $user?->email }}</div>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button class="flex w-full items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                <i class="fa-solid fa-arrow-right-from-bracket"></i> {{ $isRtl ? 'تسجيل الخروج' : 'Log out' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="admin-main min-h-[calc(100vh-4rem)]">
            @yield('content')
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
    <script>
        window.currentLocale = '{{ $locale }}';
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        function fetchUnreadCount() {
            const badge = document.getElementById('unread-badge');
            if (!badge) return;
            fetch('/admin/notifications/unread-count')
                .then(r => r.json())
                .then(d => { badge.textContent = d.count; badge.classList.toggle('hidden', !d.count); })
                .catch(() => {});
        }
        fetchUnreadCount();

        function formatDate(dateString) {
            const diff = Math.floor((new Date() - new Date(dateString)) / 60000);
            if (diff < 1) return 'الآن';
            if (diff < 60) return `منذ ${diff} دقيقة`;
            if (diff < 1440) return `منذ ${Math.floor(diff / 60)} ساعة`;
            return `منذ ${Math.floor(diff / 1440)} يوم`;
        }

        function postNotification(url, body) {
            return fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: body ? JSON.stringify(body) : null,
            }).then(r => r.json());
        }

        function markRead(el) {
            el.classList.add('read');
            const btn = el.querySelector('.mark-read-btn');
            if (btn) btn.style.display = 'none';
        }

        function markAsRead(id) {
            postNotification('/admin/notifications/mark-as-read', { notification_id: id }).then(d => {
                if (!d.success) return;
                const el = document.querySelector(`[data-notification-id="${id}"]`);
                if (el) markRead(el);
                fetchUnreadCount();
            });
        }

        function markAllAsRead() {
            postNotification('/admin/notifications/mark-all-as-read').then(d => {
                if (!d.success) return;
                document.querySelectorAll('[data-notification-id]').forEach(markRead);
                fetchUnreadCount();
            });
        }
    </script>
    @stack('scripts')

    @if (session('success') || session('error'))
        <script>
            Swal.fire({
                icon: '{{ session('success') ? 'success' : 'error' }}',
                text: @json(session('success') ?? session('error')),
                confirmButtonText: '{{ $isRtl ? 'حسنًا' : 'OK' }}',
                confirmButtonColor: getComputedStyle(document.documentElement).getPropertyValue('--brand-color').trim(),
            });
        </script>
    @endif
</body>
</html>
