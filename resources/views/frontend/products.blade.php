@extends('layouts.site')

@section('title', 'الخدمات | ' . config('brand.name'))

@section('content')
<section class="mx-auto max-w-6xl px-4 py-10">
    <h1 class="mb-2 text-3xl font-extrabold">الخدمات</h1>
    <p class="mb-8 text-slate-500">اختر {{ config('brand.service_label') }} المناسبة واحجز موعدك في خطوات بسيطة.</p>

    @if($products->isEmpty())
        <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center text-slate-500">لا توجد خدمات متاحة حاليًا.</div>
    @else
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($products as $product)
                @include('frontend.partials.service-card')
            @endforeach
        </div>
        <div class="mt-8">{{ $products->links() }}</div>
    @endif
</section>
@endsection
