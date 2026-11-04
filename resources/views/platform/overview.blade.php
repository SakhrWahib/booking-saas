@extends('layouts.app')

@section('page-title', 'لوحة المنصة')

@section('content')
<div class="space-y-6 p-4 lg:p-8">
    <x-page-header title="لوحة مالك المنصة" subtitle="كل الأنشطة المشتركة في المنصة">
        <x-slot:actions>
            <a href="{{ route('platform.tenants.create') }}" class="btn-primary"><i class="fa-solid fa-plus"></i> نشاط جديد</a>
        </x-slot:actions>
    </x-page-header>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-stat-card label="الأنشطة" :value="$stats['tenants']" icon="fa-building" :href="route('platform.tenants.index')" />
        <x-stat-card label="اشتراكات فعّالة" :value="$stats['active']" icon="fa-circle-check" :hint="$stats['trial'] . ' في التجربة'" :href="route('platform.tenants.index', ['status' => 'active'])" />
        <x-stat-card label="الإيراد الشهري المتكرر" :value="number_format($stats['mrr']) . ' ر.س'" icon="fa-sack-dollar" hint="MRR من الاشتراكات الفعّالة" />
        <x-stat-card label="حجوزات المنصة هذا الشهر" :value="number_format($stats['bookings_month'])" icon="fa-calendar-check" />
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <section class="card overflow-hidden">
            <div class="border-b border-slate-100 px-5 py-3 font-bold">تنتهي خلال 7 أيام</div>
            @forelse($expiring as $t)
                <a href="{{ route('platform.tenants.edit', $t) }}" class="flex items-center justify-between border-b border-slate-100 px-5 py-3 text-sm last:border-0 hover:bg-slate-50">
                    <span class="font-semibold">{{ $t->name }}</span>
                    <span class="text-amber-700">{{ ($t->subscription_status === 'trial' ? $t->trial_ends_at : $t->subscription_ends_at)->format('Y/m/d') }}</span>
                </a>
            @empty
                <div class="px-5 py-10 text-center text-sm text-slate-400">لا توجد اشتراكات قريبة من الانتهاء</div>
            @endforelse
        </section>
        <section class="card overflow-hidden">
            <div class="border-b border-slate-100 px-5 py-3 font-bold">أحدث الأنشطة</div>
            @foreach($latest as $t)
                <a href="{{ route('platform.tenants.edit', $t) }}" class="flex items-center justify-between border-b border-slate-100 px-5 py-3 text-sm last:border-0 hover:bg-slate-50">
                    <span><span class="font-semibold">{{ $t->name }}</span> <span class="text-slate-400">· {{ $t->plan->name ?? 'بدون باقة' }}</span></span>
                    <span class="text-slate-500">{{ $t->created_at?->format('Y/m/d') }}</span>
                </a>
            @endforeach
        </section>
    </div>
</div>
@endsection
