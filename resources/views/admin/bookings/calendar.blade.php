@extends('layouts.app')

@section('page-title', 'التقويم')

@php
    $months = ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'];
    $prev = $startDate->copy()->subMonth();
    $next = $startDate->copy()->addMonth();
    // الأسبوع يبدأ السبت: عدد الخانات الفارغة قبل أول يوم
    $leading = ($startDate->dayOfWeek + 1) % 7;
    $days = $startDate->daysInMonth;
    $colors = [
        'pending' => 'bg-amber-100 text-amber-800',
        'confirmed' => 'bg-blue-100 text-blue-800',
        'paid' => 'bg-emerald-100 text-emerald-800',
        'completed' => 'bg-slate-100 text-slate-600',
    ];
    $q = fn ($date) => array_filter(['month' => $date->month, 'year' => $date->year, 'service_provider_id' => $providerId]);
@endphp

@section('content')
<div class="p-4 lg:p-8">
    <x-page-header :title="$months[$month - 1] . ' ' . $year" subtitle="اضغط على أي يوم لعرض حجوزاته أو إضافة حجز">
        <x-slot:actions>
            <form method="GET" action="{{ route('admin.bookings.calendar') }}" class="flex gap-2">
                <input type="hidden" name="month" value="{{ $month }}"><input type="hidden" name="year" value="{{ $year }}">
                <select name="service_provider_id" class="input w-auto" onchange="this.form.submit()" aria-label="{{ config('brand.provider_label') }}">
                    <option value="">الكل</option>
                    @foreach($serviceProviders as $p)<option value="{{ $p->id }}" @selected($providerId == $p->id)>{{ $p->name }}</option>@endforeach
                </select>
            </form>
            <div class="flex">
                <a href="{{ route('admin.bookings.calendar', $q($prev)) }}" class="btn-secondary rounded-e-none px-3" aria-label="السابق"><i class="fa-solid fa-chevron-right rtl:rotate-0 ltr:rotate-180"></i></a>
                <a href="{{ route('admin.bookings.calendar', array_filter(['service_provider_id' => $providerId])) }}" class="btn-secondary rounded-none border-x-0">اليوم</a>
                <a href="{{ route('admin.bookings.calendar', $q($next)) }}" class="btn-secondary rounded-s-none px-3" aria-label="التالي"><i class="fa-solid fa-chevron-left rtl:rotate-0 ltr:rotate-180"></i></a>
            </div>
        </x-slot:actions>
    </x-page-header>

    <div class="card overflow-hidden">
        <div class="grid grid-cols-7 border-b border-slate-200 bg-slate-50 text-center text-xs font-bold text-slate-500">
            @foreach(['السبت', 'الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة'] as $d)
                <div class="py-2"><span class="hidden sm:inline">{{ $d }}</span><span class="sm:hidden">{{ mb_substr($d, 0, 2) }}</span></div>
            @endforeach
        </div>
        <div class="grid grid-cols-7">
            @for($i = 0; $i < $leading; $i++)
                <div class="min-h-[6.5rem] border-b border-e border-slate-100 bg-slate-50/50"></div>
            @endfor
            @for($day = 1; $day <= $days; $day++)
                @php
                    $date = $startDate->copy()->day($day);
                    $key = $date->format('Y-m-d');
                    $dayBookings = $bookings[$key] ?? collect();
                    $off = $timeOffDays[$key] ?? [];
                @endphp
                <div class="group relative min-h-[6.5rem] border-b border-e border-slate-100 p-1.5 {{ $date->isPast() && !$date->isToday() ? 'bg-slate-50/60' : '' }}" @if($date->isToday()) style="background-color: color-mix(in srgb, var(--brand-color) 6%, transparent)" @endif>
                    <div class="mb-1 flex items-center justify-between">
                        <a href="{{ route('admin.bookings.daily', ['date' => $key]) }}"
                           class="flex h-7 w-7 items-center justify-center rounded-full text-sm font-bold {{ $date->isToday() ? 'bg-brand text-white' : 'text-slate-700 hover:bg-slate-100' }}">{{ $day }}</a>
                        @if(!$date->isPast() || $date->isToday())
                            <a href="{{ route('admin.bookings.create', ['date' => $key]) }}" class="hidden h-6 w-6 items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-brand group-hover:flex" title="حجز في هذا اليوم"><i class="fa-solid fa-plus text-xs"></i></a>
                        @endif
                    </div>
                    @if($off)
                        <div class="mb-1 truncate rounded bg-rose-50 px-1.5 py-0.5 text-[10px] font-bold text-rose-700" title="إجازة: {{ implode('، ', $off) }}"><i class="fa-solid fa-umbrella-beach"></i> {{ $providerId ? 'إجازة' : count($off) . ' في إجازة' }}</div>
                    @endif
                    @foreach($dayBookings->take(3) as $b)
                        <a href="{{ route('admin.bookings.show', $b) }}" class="mb-0.5 block truncate rounded px-1.5 py-0.5 text-[11px] font-semibold {{ $colors[$b->status] ?? 'bg-slate-100' }}" title="{{ $b->customer->name ?? '' }} — {{ $b->product->name ?? '' }}">
                            {{ \Carbon\Carbon::parse($b->booking_time)->format('H:i') }} <span class="hidden md:inline">{{ $b->customer->name ?? '' }}</span>
                        </a>
                    @endforeach
                    @if($dayBookings->count() > 3)
                        <a href="{{ route('admin.bookings.daily', ['date' => $key]) }}" class="block px-1.5 text-[11px] font-bold text-brand hover:underline">+{{ $dayBookings->count() - 3 }} أخرى</a>
                    @endif
                </div>
            @endfor
        </div>
    </div>

    <div class="mt-3 flex flex-wrap gap-3 text-xs text-slate-500">
        @foreach(['pending' => 'قيد الانتظار', 'confirmed' => 'مؤكد', 'paid' => 'مدفوع', 'completed' => 'مكتمل'] as $s => $l)
            <span class="flex items-center gap-1"><span class="h-3 w-3 rounded {{ $colors[$s] }}"></span>{{ $l }}</span>
        @endforeach
        <span class="flex items-center gap-1"><span class="h-3 w-3 rounded bg-rose-50"></span>إجازة</span>
    </div>
</div>
@endsection
