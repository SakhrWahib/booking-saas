@extends('layouts.app')

@section('page-title', 'الأنشطة')

@section('content')
@php
    $badge = ['trial' => 'bg-sky-100 text-sky-700', 'active' => 'bg-green-100 text-green-700', 'past_due' => 'bg-amber-100 text-amber-700', 'cancelled' => 'bg-red-100 text-red-700'];
    $central = config('tenancy.central_domain');
@endphp
<div class="p-4 lg:p-8">
    <x-page-header title="الأنشطة" :subtitle="'إجمالي: ' . $tenants->total()">
        <x-slot:actions><a href="{{ route('platform.tenants.create') }}" class="btn-primary"><i class="fa-solid fa-plus"></i> نشاط جديد</a></x-slot:actions>
    </x-page-header>

    <form method="GET" class="card mb-4 flex flex-wrap gap-2 p-4">
        <input type="search" name="search" value="{{ request('search') }}" placeholder="الاسم أو المعرّف أو الجوال" class="input flex-1">
        <select name="status" class="input w-auto" onchange="this.form.submit()">
            <option value="">كل الحالات</option>
            @foreach(\App\Models\Tenant::STATUSES as $k => $l)<option value="{{ $k }}" @selected(request('status') === $k)>{{ $l }}</option>@endforeach
        </select>
        <button class="btn-primary">بحث</button>
    </form>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead><tr><th>النشاط</th><th>الباقة</th><th>الحالة</th><th>ينتهي</th><th>المستخدمون</th><th></th></tr></thead>
                <tbody>
                    @forelse($tenants as $t)
                        @php $end = $t->subscription_status === 'trial' ? $t->trial_ends_at : $t->subscription_ends_at; @endphp
                        <tr>
                            <td>
                                <div class="font-semibold">{{ $t->name }} @unless($t->is_active)<span class="ms-1 rounded bg-slate-200 px-1.5 text-[10px] text-slate-600">موقوف</span>@endunless</div>
                                <div class="text-xs text-slate-400" dir="ltr">{{ $t->domain ?: ($central ? $t->slug . '.' . $central : $t->slug) }}</div>
                            </td>
                            <td>{{ $t->plan->name ?? '—' }}</td>
                            <td><span class="rounded-full px-2.5 py-0.5 text-xs font-bold {{ $badge[$t->subscription_status] ?? '' }}">{{ \App\Models\Tenant::STATUSES[$t->subscription_status] ?? $t->subscription_status }}</span></td>
                            <td class="{{ $end && $end->isPast() ? 'font-bold text-red-600' : 'text-slate-500' }}">{{ $end?->format('Y/m/d') ?? '—' }}</td>
                            <td>{{ $t->users_count }}</td>
                            <td class="text-end"><a href="{{ route('platform.tenants.edit', $t) }}" class="btn-icon text-slate-500 hover:bg-slate-100" title="تعديل"><i class="fa-regular fa-pen-to-square"></i></a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-12 text-center text-slate-400">لا توجد أنشطة</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($tenants->hasPages())<div class="border-t border-slate-100 px-4 py-3">{{ $tenants->links() }}</div>@endif
    </div>
</div>
@endsection
