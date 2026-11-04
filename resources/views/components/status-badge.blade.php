@props(['status'])
@php
    $map = [
        'pending' => ['قيد الانتظار', 'Pending', 'bg-amber-100 text-amber-700'],
        'confirmed' => ['مؤكد', 'Confirmed', 'bg-blue-100 text-blue-700'],
        'paid' => ['مدفوع', 'Paid', 'bg-emerald-100 text-emerald-700'],
        'completed' => ['مكتمل', 'Completed', 'bg-green-100 text-green-700'],
        'cancelled' => ['ملغي', 'Cancelled', 'bg-red-100 text-red-700'],
    ];
    [$ar, $en, $class] = $map[$status] ?? [$status, $status, 'bg-slate-100 text-slate-600'];
@endphp
<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-bold $class"]) }}>{{ app()->getLocale() === 'en' ? $en : $ar }}</span>
