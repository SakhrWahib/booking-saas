@extends('layouts.app')

@section('page-title', 'الإجازات')

@section('content')
<div class="p-4 lg:p-8">
    <x-page-header title="إجازات {{ config('brand.provider_label') === 'المختص' ? 'المختصين' : 'الفريق' }}" subtitle="خلال الإجازة لا تظهر مواعيد متاحة للحجز لهذا الشخص." />

    @if($errors->any())
        <div class="mb-4 rounded-xl bg-red-50 p-3 text-sm text-red-700">@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
    @endif

    <div class="grid gap-6 lg:grid-cols-3">
        @if(auth()->user()->hasPermission('service_providers.edit'))
            <form method="POST" action="{{ route('admin.time-offs.store') }}" class="card h-fit space-y-4 p-5">
                @csrf
                <h3 class="font-bold">إضافة إجازة</h3>
                <label class="block">
                    <span class="label">{{ config('brand.provider_label') }}</span>
                    <select name="service_provider_id" class="input" required>
                        <option value="">اختر...</option>
                        @foreach($providers as $p)<option value="{{ $p->id }}" @selected(old('service_provider_id') == $p->id)>{{ $p->name }}</option>@endforeach
                    </select>
                </label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="block"><span class="label">من</span><input type="date" name="start_date" value="{{ old('start_date', today()->toDateString()) }}" min="{{ today()->toDateString() }}" class="input" required></label>
                    <label class="block"><span class="label">إلى</span><input type="date" name="end_date" value="{{ old('end_date', today()->toDateString()) }}" min="{{ today()->toDateString() }}" class="input" required></label>
                </div>
                <label class="block"><span class="label">السبب (اختياري)</span><input name="reason" value="{{ old('reason') }}" maxlength="150" class="input" placeholder="إجازة سنوية، مرض..."></label>
                <button class="btn-primary w-full"><i class="fa-solid fa-plus"></i> إضافة</button>
            </form>
        @endif

        <div class="space-y-6 {{ auth()->user()->hasPermission('service_providers.edit') ? 'lg:col-span-2' : 'lg:col-span-3' }}">
            @foreach(['upcoming' => 'الحالية والقادمة', 'past' => 'السابقة'] as $key => $title)
                <section class="card overflow-hidden">
                    <div class="border-b border-slate-100 px-5 py-3 font-bold">{{ $title }}</div>
                    <table class="data-table">
                        <tbody>
                            @forelse($$key as $off)
                                <tr>
                                    <td class="font-semibold">{{ $off->serviceProvider->name ?? '—' }}</td>
                                    <td>{{ $off->start_date->format('Y/m/d') }} ← {{ $off->end_date->format('Y/m/d') }}
                                        <span class="ms-1 text-xs text-slate-400">({{ (int) $off->start_date->diffInDays($off->end_date) + 1 }} يوم)</span>
                                    </td>
                                    <td class="text-slate-500">{{ $off->reason }}</td>
                                    <td class="text-end">
                                        @if($key === 'upcoming' && $off->start_date->lte(today()))
                                            <span class="rounded-full bg-amber-100 px-2 py-0.5 text-xs font-bold text-amber-700">في إجازة الآن</span>
                                        @endif
                                        @if(auth()->user()->hasPermission('service_providers.edit'))
                                            <form method="POST" action="{{ route('admin.time-offs.destroy', $off) }}" class="inline" onsubmit="return confirm('حذف الإجازة؟')">@csrf @method('DELETE')
                                                <button class="btn-icon text-red-600 hover:bg-red-50" title="حذف"><i class="fa-regular fa-trash-can"></i></button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td class="py-8 text-center text-slate-400">لا توجد إجازات</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </section>
            @endforeach
        </div>
    </div>
</div>
@endsection
