@extends('layouts.app')

@section('page-title', 'الباقات')

@section('content')
<div class="p-4 lg:p-8" x-data="{ adding: false }">
    <x-page-header title="الباقات" subtitle="الحدود الفارغة = بلا حد. الخصائص غير المختارة تُخفى من لوحة النشاط.">
        <x-slot:actions><button type="button" @click="adding = true" class="btn-primary"><i class="fa-solid fa-plus"></i> باقة جديدة</button></x-slot:actions>
    </x-page-header>

    @if($errors->any())
        <div class="mb-4 rounded-xl bg-red-50 p-3 text-sm text-red-700">@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
    @endif

    <div class="grid gap-4 lg:grid-cols-3">
        @foreach([...$plans, null] as $plan)
            <form method="POST" action="{{ $plan ? route('platform.plans.update', $plan) : route('platform.plans.store') }}" class="card flex flex-col p-5" @if(!$plan) x-show="adding" x-cloak @endif>
                @csrf
                @if($plan) @method('PUT') @endif
                <div class="mb-3 flex items-start justify-between">
                    <div>
                        <div class="text-lg font-extrabold">{{ $plan->name ?? 'باقة جديدة' }}</div>
                        @if($plan)<div class="text-xs text-slate-500">{{ $plan->tenants_count }} نشاط مشترك</div>@endif
                    </div>
                    @if($plan)
                        <div class="text-end"><div class="text-xl font-extrabold text-brand">{{ number_format($plan->price_monthly) }}</div><div class="text-xs text-slate-400">ر.س / شهر</div></div>
                    @endif
                </div>
                <div class="grid flex-1 grid-cols-2 gap-3 text-sm">
                    <label><span class="label">الاسم</span><input name="name" value="{{ $plan->name ?? '' }}" required class="input"></label>
                    <label><span class="label">المعرّف</span><input name="slug" value="{{ $plan->slug ?? '' }}" required dir="ltr" class="input"></label>
                    <label><span class="label">السعر الشهري</span><input type="number" step="0.01" min="0" name="price_monthly" value="{{ $plan->price_monthly ?? 0 }}" class="input"></label>
                    <label><span class="label">السعر السنوي</span><input type="number" step="0.01" min="0" name="price_yearly" value="{{ $plan->price_yearly ?? 0 }}" class="input"></label>
                    @foreach(\App\Models\Plan::LIMITS as [$col, $label])
                        <label><span class="label">حد {{ $label }}</span><input type="number" min="1" name="{{ $col }}" value="{{ $plan?->$col }}" placeholder="∞" class="input"></label>
                    @endforeach
                    <div class="col-span-2">
                        <span class="label">الخصائص</span>
                        <div class="grid grid-cols-2 gap-1">
                            @foreach(\App\Models\Plan::FEATURES as $f => $label)
                                <label class="flex items-center gap-2"><input type="checkbox" name="features[]" value="{{ $f }}" @checked($plan?->hasFeature($f)) class="h-4 w-4"> {{ $label }}</label>
                            @endforeach
                        </div>
                    </div>
                </div>
                <button class="btn-primary mt-4">حفظ</button>
            </form>
        @endforeach
    </div>
</div>
@endsection
