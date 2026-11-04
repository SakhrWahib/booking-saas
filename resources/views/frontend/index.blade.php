@extends('layouts.site')

@section('content')
<section class="relative overflow-hidden bg-white">
    <div class="absolute inset-0 bg-brand opacity-[0.06]"></div>
    <div class="relative mx-auto grid max-w-6xl items-center gap-10 px-4 py-16 md:grid-cols-2 md:py-24">
        <div>
            <span class="mb-4 inline-block rounded-full bg-white px-3 py-1 text-xs font-bold text-brand shadow-sm">{{ config('brand.business_label') }}</span>
            <h1 class="mb-4 text-4xl font-extrabold leading-tight md:text-5xl">{{ config('brand.name') }}</h1>
            <p class="mb-8 text-lg text-slate-600">{{ config('brand.tagline') }}</p>
            <div class="flex flex-wrap gap-3">
                <a href="#services" class="rounded-xl bg-brand px-6 py-3 font-bold text-white shadow hover:opacity-90">احجز موعدك</a>
                @if(config('brand.whatsapp'))
                    <a href="https://wa.me/{{ config('brand.whatsapp') }}" target="_blank" rel="noopener" class="rounded-xl border border-slate-300 bg-white px-6 py-3 font-bold hover:bg-slate-50"><i class="fa-brands fa-whatsapp ms-1 text-green-500"></i> تواصل معنا</a>
                @endif
            </div>
        </div>
        <div class="hidden justify-center md:flex">
            <img src="{{ config('brand.logo') }}" alt="{{ config('brand.name') }}" class="h-64 w-64 rounded-3xl bg-white object-contain p-6 shadow-xl">
        </div>
    </div>
</section>

<section class="mx-auto max-w-6xl px-4 py-14">
    <div class="grid gap-4 sm:grid-cols-3">
        @foreach([
            ['fa-list-check', 'اختر ' . config('brand.service_label'), 'تصفّح الخدمات واختر المناسب لك.'],
            ['fa-calendar-days', 'حدد الموعد', 'اختر اليوم والوقت المتاح.'],
            ['fa-circle-check', 'أكّد الحجز', 'أدخل بياناتك ويصلك التأكيد مباشرة.'],
        ] as $i => [$icon, $title, $text])
            <div class="rounded-2xl border border-slate-200 bg-white p-6">
                <div class="mb-3 flex h-11 w-11 items-center justify-center rounded-xl bg-brand text-white"><i class="fa-solid {{ $icon }}"></i></div>
                <div class="mb-1 font-bold">{{ $i + 1 }}. {{ $title }}</div>
                <p class="text-sm text-slate-500">{{ $text }}</p>
            </div>
        @endforeach
    </div>
</section>

<section id="services" class="mx-auto max-w-6xl px-4">
    <div class="mb-6 flex items-end justify-between">
        <h2 class="text-2xl font-extrabold">الخدمات</h2>
        <a href="{{ route('products') }}" class="text-sm font-bold text-brand hover:underline">عرض الكل</a>
    </div>
    @if($products->isEmpty())
        <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center text-slate-500">لا توجد خدمات متاحة حاليًا.</div>
    @else
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($products as $product)
                @include('frontend.partials.service-card')
            @endforeach
        </div>
    @endif
</section>
@endsection
