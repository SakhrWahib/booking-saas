@props(['title', 'subtitle' => null])
<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div>
        <h2 class="text-xl font-extrabold text-slate-900">{{ $title }}</h2>
        @if($subtitle)<p class="text-sm text-slate-500">{{ $subtitle }}</p>@endif
    </div>
    @isset($actions)<div class="flex flex-wrap gap-2">{{ $actions }}</div>@endisset
</div>
