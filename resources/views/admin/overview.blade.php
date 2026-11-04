@extends('layouts.app')

@php $ar = app()->getLocale() !== 'en'; @endphp
@section('page-title', $ar ? 'لوحة المعلومات' : 'Dashboard')

@section('content')
<div class="space-y-6 p-4 lg:p-8">
    {{-- إجراءات سريعة --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900">{{ $ar ? 'أهلًا' : 'Welcome' }}، {{ auth()->user()->name }} 👋</h2>
            <p class="text-sm text-slate-500">{{ now()->translatedFormat('l، d F Y') }}</p>
        </div>
        <div class="flex flex-wrap gap-2">
            @if(auth()->user()->hasPermission('bookings.create'))
                <a href="{{ route('admin.bookings.create') }}" class="rounded-xl bg-brand px-4 py-2.5 text-sm font-bold text-white shadow hover:opacity-90"><i class="fa-solid fa-plus ms-1"></i> {{ $ar ? 'حجز جديد' : 'New booking' }}</a>
            @endif
            @if(auth()->user()->hasPermission('bookings.view'))
                <a href="{{ route('admin.bookings.calendar') }}" class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-bold hover:bg-slate-50"><i class="fa-regular fa-calendar ms-1"></i> {{ $ar ? 'التقويم' : 'Calendar' }}</a>
            @endif
        </div>
    </div>

    {{-- الأرقام --}}
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-stat-card :label="$ar ? 'حجوزات اليوم' : 'Today'" :value="$stats['today']" icon="fa-calendar-day" :href="route('admin.bookings.daily')" />
        <x-stat-card :label="$ar ? 'بانتظار التأكيد' : 'Pending'" :value="$stats['pending']" icon="fa-hourglass-half" :href="route('admin.bookings.index', ['status' => 'pending'])" />
        <x-stat-card :label="$ar ? 'إيراد الشهر' : 'Month revenue'" :value="number_format($stats['month_revenue'], 0) . ($ar ? ' ر.س' : ' SAR')" icon="fa-sack-dollar" :hint="$stats['month_bookings'] . ($ar ? ' حجز هذا الشهر' : ' bookings this month')" />
        <x-stat-card :label="$ar ? 'العملاء' : 'Customers'" :value="number_format($stats['customers'])" icon="fa-users" :hint="'+' . $stats['new_customers'] . ($ar ? ' هذا الشهر' : ' this month')" :href="route('admin.customers.index')" />
    </div>

    <div class="grid gap-6 xl:grid-cols-3">
        {{-- حجوزات اليوم --}}
        <section class="rounded-2xl border border-slate-200 bg-white shadow-sm xl:col-span-2">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                <h3 class="font-bold">{{ $ar ? 'حجوزات اليوم' : "Today's bookings" }}</h3>
                <a href="{{ route('admin.bookings.daily') }}" class="text-sm font-bold text-brand hover:underline">{{ $ar ? 'عرض الكل' : 'View all' }}</a>
            </div>
            @forelse($todayBookings as $booking)
                <a href="{{ route('admin.bookings.show', $booking) }}" class="flex items-center gap-4 border-b border-slate-100 px-5 py-3 last:border-0 hover:bg-slate-50">
                    <div class="w-16 shrink-0 text-center">
                        <div class="text-lg font-extrabold text-slate-900">{{ \Carbon\Carbon::parse($booking->booking_time)->format('H:i') }}</div>
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="truncate font-semibold">{{ $booking->customer->name ?? '—' }}</div>
                        <div class="truncate text-sm text-slate-500">{{ $booking->product->name ?? '—' }}</div>
                    </div>
                    <x-status-badge :status="$booking->status" />
                </a>
            @empty
                <div class="px-5 py-12 text-center text-slate-400">
                    <i class="fa-regular fa-calendar mb-2 text-3xl"></i>
                    <div>{{ $ar ? 'لا توجد حجوزات اليوم' : 'No bookings today' }}</div>
                </div>
            @endforelse
        </section>

        {{-- القادمة --}}
        <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-4"><h3 class="font-bold">{{ $ar ? 'الحجوزات القادمة' : 'Upcoming' }}</h3></div>
            @forelse($upcoming as $booking)
                <a href="{{ route('admin.bookings.show', $booking) }}" class="flex items-center justify-between gap-3 border-b border-slate-100 px-5 py-3 last:border-0 hover:bg-slate-50">
                    <div class="min-w-0">
                        <div class="truncate text-sm font-semibold">{{ $booking->customer->name ?? '—' }}</div>
                        <div class="truncate text-xs text-slate-500">{{ $booking->product->name ?? '—' }}</div>
                    </div>
                    <div class="shrink-0 text-end text-xs text-slate-500">
                        <div class="font-bold text-slate-700">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m') }}</div>
                        <div>{{ \Carbon\Carbon::parse($booking->booking_time)->format('H:i') }}</div>
                    </div>
                </a>
            @empty
                <div class="px-5 py-12 text-center text-sm text-slate-400">{{ $ar ? 'لا توجد حجوزات قادمة' : 'Nothing upcoming' }}</div>
            @endforelse
        </section>
    </div>

    {{-- آخر 14 يومًا --}}
    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="mb-4 flex items-center justify-between">
            <h3 class="font-bold">{{ $ar ? 'الحجوزات آخر 14 يومًا' : 'Bookings, last 14 days' }}</h3>
            @if(Route::has('admin.reports.index'))
                <a href="{{ route('admin.reports.index') }}" class="text-sm font-bold text-brand hover:underline">{{ $ar ? 'التقارير التفصيلية' : 'Detailed reports' }}</a>
            @endif
        </div>
        @php $max = max(1, $chart->max('value')); @endphp
        <div class="flex h-40 items-end gap-1.5" dir="ltr">
            @foreach($chart as $point)
                <div class="group flex flex-1 flex-col items-center gap-1">
                    <span class="text-[10px] font-bold text-slate-500 opacity-0 group-hover:opacity-100">{{ $point['value'] }}</span>
                    <div class="w-full rounded-t-md bg-brand transition group-hover:opacity-80" style="height: {{ max(4, $point['value'] / $max * 120) }}px"></div>
                    <span class="text-[10px] text-slate-400">{{ $point['label'] }}</span>
                </div>
            @endforeach
        </div>
    </section>
</div>
@endsection
