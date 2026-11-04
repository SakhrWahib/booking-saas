@extends('layouts.app')

@section('page-title', 'حجز #' . $booking->booking_number)

@php
    $date = \Carbon\Carbon::parse($booking->booking_date);
    $time = \Carbon\Carbon::parse($booking->booking_time);
    $phone = $booking->customer->phone ?? null;
    $wa = $phone ? preg_replace('/^0/', '966', preg_replace('/\D/', '', $phone)) : null;
    $timeline = array_filter([
        ['أُنشئ', $booking->created_at],
        ['تأكّد', $booking->confirmed_at],
        ['اكتمل', $booking->completed_at],
        ['أُلغي', $booking->cancelled_at],
    ], fn ($t) => $t[1]);
@endphp

@section('content')
<div class="p-4 lg:p-8">
    <x-page-header :title="'حجز #' . $booking->booking_number" :subtitle="$date->translatedFormat('l d F Y') . ' · ' . $time->format('H:i')">
        <x-slot:actions>
            <x-status-badge :status="$booking->status" class="self-center px-3 py-1 text-sm" />
            @if($booking->status === 'pending')
                <form action="{{ route('admin.bookings.confirm', $booking) }}" method="POST">@csrf<button class="btn-primary"><i class="fa-solid fa-check"></i> تأكيد</button></form>
            @endif
            @if(in_array($booking->status, ['confirmed', 'paid']))
                <form action="{{ route('admin.bookings.complete', $booking) }}" method="POST">@csrf<button class="btn-primary"><i class="fa-solid fa-flag-checkered"></i> إكمال</button></form>
            @endif
            <a href="{{ route('admin.bookings.edit', $booking) }}" class="btn-secondary"><i class="fa-regular fa-pen-to-square"></i> تعديل</a>
            @if(in_array($booking->status, ['pending', 'confirmed']))
                <form action="{{ route('admin.bookings.cancel', $booking) }}" method="POST" onsubmit="return confirm('إلغاء هذا الحجز؟')">@csrf<button class="btn-secondary text-red-600"><i class="fa-solid fa-ban"></i> إلغاء</button></form>
            @endif
        </x-slot:actions>
    </x-page-header>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            {{-- الخدمة والمبلغ --}}
            <section class="card p-5">
                <h3 class="mb-4 font-bold">الخدمة</h3>
                <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-3">
                    <div>
                        <div class="font-semibold">{{ $booking->product->name ?? '—' }}</div>
                        @if(($booking->quantity ?? 1) > 1)<div class="text-xs text-slate-500">الكمية: {{ $booking->quantity }}</div>@endif
                    </div>
                    <div class="font-bold">{{ number_format($booking->product->price ?? 0, 2) }} ر.س</div>
                </div>
                @if($booking->hasSelectedAddOns())
                    @foreach($booking->formatted_selected_add_ons as $addOn)
                        <div class="flex justify-between border-b border-slate-100 py-2 text-sm">
                            <span class="text-slate-600"><i class="fa-solid fa-plus me-1 text-xs text-slate-400"></i>{{ $addOn['name'] }} @if($addOn['option'])<span class="text-slate-400">({{ $addOn['option'] }})</span>@endif</span>
                            <span>{{ $addOn['formatted_price'] }}</span>
                        </div>
                    @endforeach
                @endif
                @if($booking->discount_amount > 0)
                    <div class="flex justify-between py-2 text-sm text-green-700">
                        <span>خصم @if($booking->coupon)({{ $booking->coupon->code }})@endif</span><span>- {{ number_format($booking->discount_amount, 2) }} ر.س</span>
                    </div>
                @endif
                <div class="flex items-center justify-between pt-3">
                    <span class="font-bold">الإجمالي</span>
                    <span class="text-2xl font-extrabold text-brand">{{ number_format($booking->total_price, 2) }} <span class="text-sm">ر.س</span></span>
                </div>
            </section>

            {{-- الملاحظات --}}
            @if($booking->customer_notes || $booking->notes || $booking->admin_notes)
                <section class="card space-y-3 p-5">
                    <h3 class="font-bold">ملاحظات</h3>
                    @foreach(['customer_notes' => 'من العميل', 'notes' => 'عامة', 'admin_notes' => 'داخلية'] as $field => $label)
                        @if($booking->$field)
                            <div class="rounded-xl bg-slate-50 p-3 text-sm"><div class="mb-1 text-xs font-bold text-slate-500">{{ $label }}</div>{{ $booking->$field }}</div>
                        @endif
                    @endforeach
                </section>
            @endif

            {{-- المرفقات --}}
            @if($booking->hasAttachments())
                <section class="card p-5">
                    <h3 class="mb-3 font-bold">المرفقات</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($booking->safe_attachments as $attachment)
                            <a href="{{ asset('storage/' . $attachment['path']) }}" target="_blank" rel="noopener" class="btn-secondary"><i class="fa-regular fa-file"></i> {{ $attachment['name'] }}</a>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>

        <div class="space-y-6">
            {{-- العميل --}}
            <section class="card p-5">
                <h3 class="mb-3 font-bold">العميل</h3>
                @if($booking->customer)
                    <a href="{{ route('admin.customers.show', $booking->customer) }}" class="font-semibold hover:text-brand">{{ $booking->customer->name }}</a>
                    <div class="mt-1 text-sm text-slate-500" dir="ltr">{{ $phone }}</div>
                    <div class="mt-1 text-xs text-slate-400">{{ $booking->customer->bookings->count() }} حجز سابق</div>
                    <div class="mt-3 flex gap-2">
                        @if($phone)<a href="tel:{{ $phone }}" class="btn-secondary flex-1"><i class="fa-solid fa-phone"></i> اتصال</a>@endif
                        @if($wa)<a href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener" class="btn-secondary flex-1 text-green-700"><i class="fa-brands fa-whatsapp"></i> واتساب</a>@endif
                    </div>
                @else
                    <div class="text-sm text-slate-400">—</div>
                @endif
            </section>

            {{-- مقدّم الخدمة والموقع --}}
            <section class="card space-y-3 p-5 text-sm">
                <div>
                    <div class="text-xs font-bold text-slate-500">{{ config('brand.provider_label') }}</div>
                    <div class="font-semibold">{{ $booking->serviceProvider->name ?? 'غير محدد' }}</div>
                </div>
                @if($booking->customer_address || $booking->customer_latitude)
                    <div>
                        <div class="text-xs font-bold text-slate-500">الموقع</div>
                        <div>{{ $booking->customer_address }}</div>
                        @if($booking->customer_latitude && $booking->customer_longitude)
                            <a href="{{ $booking->location_map_url ?: 'https://maps.google.com/?q=' . $booking->customer_latitude . ',' . $booking->customer_longitude }}" target="_blank" rel="noopener" class="mt-1 inline-flex items-center gap-1 font-bold text-brand hover:underline"><i class="fa-solid fa-location-dot"></i> فتح في الخرائط</a>
                        @endif
                    </div>
                @endif
                <div>
                    <div class="text-xs font-bold text-slate-500">المصدر</div>
                    <div>{{ $booking->booking_source === 'website' ? 'الموقع الإلكتروني' : 'لوحة التحكم' }}</div>
                </div>
                @if($booking->invoice)
                    <a href="{{ route('admin.invoices.show', $booking->invoice) }}" class="btn-secondary w-full"><i class="fa-solid fa-file-invoice"></i> عرض الفاتورة</a>
                @endif
            </section>

            {{-- السجل --}}
            <section class="card p-5">
                <h3 class="mb-3 font-bold">السجل</h3>
                <ol class="space-y-3 border-s-2 border-slate-100 ps-4 text-sm">
                    @foreach($timeline as [$label, $at])
                        <li class="relative">
                            <span class="absolute -start-[1.4rem] top-1.5 h-2.5 w-2.5 rounded-full bg-brand"></span>
                            <div class="font-semibold">{{ $label }}</div>
                            <div class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($at)->format('Y/m/d H:i') }}</div>
                        </li>
                    @endforeach
                </ol>
            </section>

            @if(auth()->user()->hasPermission('bookings.delete'))
                <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" onsubmit="return confirm('حذف الحجز نهائيًا؟ لا يمكن التراجع.')">
                    @csrf @method('DELETE')
                    <button class="w-full text-center text-sm font-semibold text-red-600 hover:underline">حذف الحجز نهائيًا</button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
