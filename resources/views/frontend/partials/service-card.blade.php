<article class="flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
    <div class="aspect-[4/3] bg-slate-100">
        @if($product->image_gallery && count($product->image_gallery) > 0)
            <img src="{{ getProductImageUrl($product->image_gallery[0]) }}" alt="{{ $product->name }}" loading="lazy"
                 class="h-full w-full object-cover" onerror="this.src='{{ asset('images/default-product.svg') }}'">
        @else
            <img src="{{ asset('images/default-product.svg') }}" alt="" class="h-full w-full object-contain p-10 opacity-60">
        @endif
    </div>
    <div class="flex flex-1 flex-col p-5">
        <h3 class="mb-1 text-lg font-bold">{{ $product->name }}</h3>
        <p class="mb-4 line-clamp-2 flex-1 text-sm text-slate-500">{{ $product->short_description ?? \Illuminate\Support\Str::limit(strip_tags($product->description), 90) }}</p>
        <div class="flex items-center justify-between">
            <span class="text-xl font-extrabold text-brand">{{ number_format($product->price, 0) }} <span class="text-sm font-semibold">ر.س</span></span>
            <form method="POST" action="{{ route('frontend.booking.start') }}">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <button class="rounded-lg bg-brand px-4 py-2 text-sm font-bold text-white hover:opacity-90">احجز</button>
            </form>
        </div>
    </div>
</article>
