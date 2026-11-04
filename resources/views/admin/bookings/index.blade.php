@extends('layouts.app')

@section('page-title', 'الحجوزات')

@php
    $tabs = [
        '' => ['الكل', $stats['total']],
        'pending' => ['قيد الانتظار', $stats['pending']],
        'confirmed' => ['مؤكد', $stats['confirmed']],
        'paid' => ['مدفوع', $stats['paid']],
        'completed' => ['مكتمل', $stats['completed']],
        'cancelled' => ['ملغي', $stats['cancelled']],
    ];
    $current = request('status', '');
    $hasFilters = request()->hasAny(['search', 'date', 'service_provider_id', 'booking_source']) && collect(request()->only(['search', 'date', 'service_provider_id', 'booking_source']))->filter()->isNotEmpty();
@endphp

@section('content')
<div class="p-4 lg:p-8">
    <x-page-header title="الحجوزات" :subtitle="'اليوم: ' . $stats['today'] . ' حجز · من الموقع: ' . $stats['website_bookings']">
        <x-slot:actions>
            <a href="{{ route('admin.bookings.calendar') }}" class="btn-secondary"><i class="fa-regular fa-calendar"></i> التقويم</a>
            @if(auth()->user()->hasPermission('bookings.create'))
                <a href="{{ route('admin.bookings.create') }}" class="btn-primary"><i class="fa-solid fa-plus"></i> حجز جديد</a>
            @endif
        </x-slot:actions>
    </x-page-header>

    {{-- تبويبات الحالة --}}
    <div class="mb-4 flex gap-2 overflow-x-auto pb-1">
        @foreach($tabs as $key => [$label, $count])
            <a href="{{ route('admin.bookings.index', array_filter([...request()->except('status', 'page'), 'status' => $key])) }}"
               class="flex shrink-0 items-center gap-2 rounded-xl px-4 py-2 text-sm font-bold transition {{ $current === $key ? 'bg-brand text-white shadow' : 'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50' }}">
                {{ $label }}
                <span class="rounded-full px-2 text-xs {{ $current === $key ? 'bg-white/25' : 'bg-slate-100' }}">{{ $count }}</span>
            </a>
        @endforeach
    </div>

    {{-- الفلاتر --}}
    <form method="GET" action="{{ route('admin.bookings.index') }}" class="card mb-4 grid gap-3 p-4 md:grid-cols-5">
        @if($current)<input type="hidden" name="status" value="{{ $current }}">@endif
        <div class="relative md:col-span-2">
            <i class="fa-solid fa-magnifying-glass pointer-events-none absolute top-1/2 -translate-y-1/2 text-slate-400 {{ app()->getLocale() === 'en' ? 'left-3' : 'right-3' }}"></i>
            <input type="search" name="search" value="{{ request('search') }}" placeholder="رقم الحجز، اسم العميل، الجوال" class="input {{ app()->getLocale() === 'en' ? 'pl-9' : 'pr-9' }}">
        </div>
        <input type="date" name="date" value="{{ request('date') }}" class="input" aria-label="التاريخ">
        <select name="service_provider_id" class="input" aria-label="{{ config('brand.provider_label') }}">
            <option value="">كل {{ config('brand.provider_label') === 'المختص' ? 'المختصين' : 'مقدّمي الخدمة' }}</option>
            @foreach($serviceProviders as $provider)
                <option value="{{ $provider->id }}" @selected(request('service_provider_id') == $provider->id)>{{ $provider->name }}</option>
            @endforeach
        </select>
        <div class="flex gap-2">
            <select name="booking_source" class="input" aria-label="المصدر">
                <option value="">كل المصادر</option>
                <option value="website" @selected(request('booking_source') === 'website')>الموقع</option>
                <option value="admin_panel" @selected(request('booking_source') === 'admin_panel')>لوحة التحكم</option>
            </select>
            <button class="btn-primary shrink-0 px-3" aria-label="بحث"><i class="fa-solid fa-filter"></i></button>
            @if($hasFilters)
                <a href="{{ route('admin.bookings.index', array_filter(['status' => $current])) }}" class="btn-secondary shrink-0 px-3" aria-label="مسح"><i class="fa-solid fa-xmark"></i></a>
            @endif
        </div>
    </form>

    {{-- الجدول --}}
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>الحجز</th>
                        <th>العميل</th>
                        <th>الخدمة</th>
                        <th>الموعد</th>
                        <th>المبلغ</th>
                        <th>الحالة</th>
                        <th class="text-end">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td>
                                <a href="{{ route('admin.bookings.show', $booking) }}" class="font-bold text-slate-900 hover:text-brand">#{{ $booking->booking_number }}</a>
                                <div class="text-xs text-slate-400">{{ $booking->booking_source === 'website' ? 'من الموقع' : 'من اللوحة' }}</div>
                            </td>
                            <td>
                                <div class="font-semibold">{{ $booking->customer->name ?? '—' }}</div>
                                <div class="text-xs text-slate-500" dir="ltr">{{ $booking->customer->phone ?? '' }}</div>
                            </td>
                            <td>
                                <div>{{ $booking->product->name ?? '—' }}</div>
                                <div class="text-xs text-slate-500">{{ $booking->serviceProvider->name ?? '' }}</div>
                            </td>
                            <td>
                                <div class="font-semibold">{{ \Carbon\Carbon::parse($booking->booking_date)->format('Y/m/d') }}</div>
                                <div class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($booking->booking_time)->format('H:i') }}</div>
                            </td>
                            <td class="font-bold">{{ number_format($booking->total_price, 2) }} <span class="text-xs font-normal text-slate-400">ر.س</span></td>
                            <td><x-status-badge :status="$booking->status" /></td>
                            <td>
                                <div class="flex items-center justify-end gap-1">
                                    @if($booking->status === 'pending')
                                        <form action="{{ route('admin.bookings.confirm', $booking) }}" method="POST">@csrf
                                            <button class="btn-icon bg-green-50 text-green-700 hover:bg-green-100" title="تأكيد"><i class="fa-solid fa-check"></i></button>
                                        </form>
                                    @endif
                                    @if(in_array($booking->status, ['confirmed', 'paid']))
                                        <form action="{{ route('admin.bookings.complete', $booking) }}" method="POST">@csrf
                                            <button class="btn-icon bg-blue-50 text-blue-700 hover:bg-blue-100" title="إكمال"><i class="fa-solid fa-flag-checkered"></i></button>
                                        </form>
                                    @endif
                                    @if($booking->customer_latitude && $booking->customer_longitude)
                                        <a href="{{ $booking->location_map_url ?: 'https://maps.google.com/?q=' . $booking->customer_latitude . ',' . $booking->customer_longitude }}" target="_blank" rel="noopener" class="btn-icon text-slate-500 hover:bg-slate-100" title="الموقع"><i class="fa-solid fa-location-dot"></i></a>
                                    @endif
                                    @if($booking->invoice)
                                        <a href="{{ route('admin.invoices.show', $booking->invoice) }}" class="btn-icon text-slate-500 hover:bg-slate-100" title="الفاتورة"><i class="fa-solid fa-file-invoice"></i></a>
                                    @endif
                                    <a href="{{ route('admin.bookings.show', $booking) }}" class="btn-icon text-slate-500 hover:bg-slate-100" title="عرض"><i class="fa-regular fa-eye"></i></a>
                                    <a href="{{ route('admin.bookings.edit', $booking) }}" class="btn-icon text-slate-500 hover:bg-slate-100" title="تعديل"><i class="fa-regular fa-pen-to-square"></i></a>
                                    @if(in_array($booking->status, ['pending', 'confirmed']))
                                        <form action="{{ route('admin.bookings.cancel', $booking) }}" method="POST" onsubmit="return confirm('إلغاء هذا الحجز؟')">@csrf
                                            <button class="btn-icon text-red-600 hover:bg-red-50" title="إلغاء"><i class="fa-solid fa-ban"></i></button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-16 text-center text-slate-400">
                                <i class="fa-regular fa-calendar-xmark mb-2 text-4xl"></i>
                                <div class="mb-4">لا توجد حجوزات مطابقة</div>
                                @if(auth()->user()->hasPermission('bookings.create'))
                                    <a href="{{ route('admin.bookings.create') }}" class="btn-primary"><i class="fa-solid fa-plus"></i> أضف أول حجز</a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($bookings->hasPages())
            <div class="border-t border-slate-100 px-4 py-3">{{ $bookings->withQueryString()->links() }}</div>
        @endif
    </div>
</div>
@endsection
