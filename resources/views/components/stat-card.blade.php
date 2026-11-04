@props(['label', 'value', 'icon', 'hint' => null, 'href' => null])
@php $tag = $href ? 'a' : 'div'; @endphp
<{{ $tag }} @if($href) href="{{ $href }}" @endif class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm {{ $href ? 'transition hover:shadow-md' : '' }}">
    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl text-xl text-brand" style="background-color: color-mix(in srgb, var(--brand-color) 12%, transparent)">
        <i class="fa-solid {{ $icon }}"></i>
    </div>
    <div class="min-w-0">
        <div class="text-sm text-slate-500">{{ $label }}</div>
        <div class="text-2xl font-extrabold text-slate-900">{{ $value }}</div>
        @if($hint)<div class="text-xs text-slate-400">{{ $hint }}</div>@endif
    </div>
</{{ $tag }}>
