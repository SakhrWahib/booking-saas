@extends('layouts.app')

@section('page-title', 'الخدمات')

@section('content')
<div class="p-4 lg:p-8">
    <x-page-header title="الخدمات" subtitle="الخدمات التي يحجزها عملاؤك، بأسعارها والإضافات ومقدّمي الخدمة">
        <x-slot:actions>
            @if(auth()->user()->hasPermission('products.create'))
                <a href="{{ route('admin.products.create') }}" class="btn-primary"><i class="fa-solid fa-plus"></i> خدمة جديدة</a>
            @endif
        </x-slot:actions>
    </x-page-header>

    @if($products->isEmpty())
        <div class="card py-16 text-center text-slate-400">
            <i class="fa-solid fa-layer-group mb-3 text-4xl"></i>
            <div class="mb-4">لا توجد خدمات بعد. أضف أول خدمة ليبدأ عملاؤك الحجز.</div>
            @if(auth()->user()->hasPermission('products.create'))
                <a href="{{ route('admin.products.create') }}" class="btn-primary"><i class="fa-solid fa-plus"></i> أضف خدمة</a>
            @endif
        </div>
    @else
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @foreach($products as $product)
                <div class="card flex flex-col overflow-hidden">
                    <div class="relative aspect-[16/9] bg-slate-100">
                        @if($product->image_gallery && count($product->image_gallery) > 0)
                            <img src="{{ getProductImageUrl($product->image_gallery[0]) }}" alt="" class="h-full w-full object-cover" loading="lazy" onerror="this.src='{{ asset('images/default-product.svg') }}'">
                        @else
                            <img src="{{ asset('images/default-product.svg') }}" alt="" class="h-full w-full object-contain p-8 opacity-50">
                        @endif
                        <span class="absolute top-3 {{ app()->getLocale() === 'en' ? 'left-3' : 'right-3' }} rounded-full px-2.5 py-0.5 text-xs font-bold {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-slate-200 text-slate-600' }}">
                            {{ $product->is_active ? 'نشطة' : 'متوقفة' }}
                        </span>
                    </div>
                    <div class="flex flex-1 flex-col p-4">
                        <div class="mb-1 flex items-start justify-between gap-2">
                            <a href="{{ route('admin.products.show', $product) }}" class="font-bold text-slate-900 hover:text-brand">{{ $product->name }}</a>
                            <span class="shrink-0 font-extrabold text-brand">{{ number_format($product->price, 0) }} <span class="text-xs font-semibold">ر.س</span></span>
                        </div>
                        <p class="mb-3 line-clamp-2 flex-1 text-sm text-slate-500">{{ $product->short_description }}</p>
                        <div class="flex items-center justify-between border-t border-slate-100 pt-3">
                            <span class="text-xs text-slate-500"><i class="fa-solid fa-user-tie ms-1"></i> {{ count($product->service_provider_ids ?? []) }} {{ config('brand.provider_label') }}</span>
                            <div class="flex items-center gap-1">
                                @if(auth()->user()->hasPermission('products.edit'))
                                    <form method="POST" action="{{ route('admin.products.toggle-status', $product) }}">@csrf
                                        <button class="btn-icon {{ $product->is_active ? 'text-amber-600 hover:bg-amber-50' : 'text-green-600 hover:bg-green-50' }}" title="{{ $product->is_active ? 'إيقاف' : 'تفعيل' }}">
                                            <i class="fa-solid {{ $product->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.products.edit', $product) }}" class="btn-icon text-slate-500 hover:bg-slate-100" title="تعديل"><i class="fa-regular fa-pen-to-square"></i></a>
                                @endif
                                @if(auth()->user()->hasPermission('products.delete'))
                                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('حذف الخدمة؟ لا يمكن التراجع.')">@csrf @method('DELETE')
                                        <button class="btn-icon text-red-600 hover:bg-red-50" title="حذف"><i class="fa-regular fa-trash-can"></i></button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @if($products->hasPages())<div class="mt-6">{{ $products->links() }}</div>@endif
    @endif
</div>
@endsection
