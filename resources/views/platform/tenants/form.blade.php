@extends('layouts.app')

@php $isNew = !$tenant->exists; @endphp
@section('page-title', $isNew ? 'نشاط جديد' : $tenant->name)

@section('content')
@php $v = fn ($k, $d = null) => old($k, $tenant->$k instanceof \Carbon\Carbon ? $tenant->$k->toDateString() : ($tenant->$k ?? $d)); @endphp
<div class="mx-auto max-w-4xl p-4 lg:p-8">
    <x-page-header :title="$isNew ? 'إضافة نشاط جديد' : 'تعديل: ' . $tenant->name" :subtitle="$isNew ? 'يُنشأ النشاط ومديره وجدول عمل افتراضي بخطوة واحدة' : null" />

    @if($errors->any())
        <div class="mb-4 rounded-xl bg-red-50 p-3 text-sm text-red-700">@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
    @endif

    <form method="POST" action="{{ $isNew ? route('platform.tenants.store') : route('platform.tenants.update', $tenant) }}" class="space-y-6">
        @csrf
        @unless($isNew) @method('PUT') @endunless

        <section class="card grid gap-4 p-5 md:grid-cols-2">
            <h3 class="font-bold md:col-span-2">النشاط</h3>
            <label class="block"><span class="label">اسم النشاط *</span><input name="name" value="{{ $v('name') }}" required class="input"></label>
            <label class="block"><span class="label">المعرّف (للرابط) *</span><input name="slug" value="{{ $v('slug') }}" required pattern="[A-Za-z0-9_-]+" dir="ltr" class="input" placeholder="salon"></label>
            <label class="block"><span class="label">نطاق خاص (اختياري)</span><input name="domain" value="{{ $v('domain') }}" dir="ltr" class="input" placeholder="booking.salon.com"></label>
            @if($isNew)
                <label class="block"><span class="label">نوع النشاط *</span>
                    <select name="business_type" class="input">
                        @foreach(config('brand.presets') as $k => $p)<option value="{{ $k }}" @selected(old('business_type') === $k)>{{ $p['label'] }}</option>@endforeach
                    </select>
                </label>
            @else
                <label class="flex items-center gap-2 self-end pb-2"><input type="hidden" name="is_active" value="0"><input type="checkbox" name="is_active" value="1" @checked($v('is_active', true)) class="h-4 w-4"> <span class="text-sm font-semibold">النشاط مفعّل</span></label>
            @endif
        </section>

        <section class="card grid gap-4 p-5 md:grid-cols-2">
            <h3 class="font-bold md:col-span-2">الاشتراك</h3>
            <label class="block"><span class="label">الباقة</span>
                <select name="plan_id" class="input">
                    <option value="">بدون باقة (بلا حدود)</option>
                    @foreach($plans as $p)<option value="{{ $p->id }}" @selected($v('plan_id') == $p->id)>{{ $p->name }} — {{ number_format($p->price_monthly) }} ر.س/شهر</option>@endforeach
                </select>
            </label>
            <label class="block"><span class="label">الحالة</span>
                <select name="subscription_status" class="input">
                    @foreach(\App\Models\Tenant::STATUSES as $k => $l)<option value="{{ $k }}" @selected($v('subscription_status') === $k)>{{ $l }}</option>@endforeach
                </select>
            </label>
            <label class="block"><span class="label">نهاية التجربة</span><input type="date" name="trial_ends_at" value="{{ $v('trial_ends_at') }}" class="input"></label>
            <label class="block"><span class="label">نهاية الاشتراك</span><input type="date" name="subscription_ends_at" value="{{ $v('subscription_ends_at') }}" class="input"></label>
        </section>

        <section class="card grid gap-4 p-5 md:grid-cols-2">
            <h3 class="font-bold md:col-span-2">صاحب النشاط</h3>
            <label class="block"><span class="label">الاسم *</span><input name="owner_name" value="{{ $v('owner_name') }}" required class="input"></label>
            <label class="block"><span class="label">الجوال</span><input name="owner_phone" value="{{ $v('owner_phone') }}" dir="ltr" class="input" placeholder="05XXXXXXXX"></label>
            @if($isNew)
                <label class="block"><span class="label">بريد الدخول للوحة *</span><input type="email" name="owner_email" value="{{ old('owner_email') }}" required dir="ltr" class="input"></label>
                <label class="block"><span class="label">كلمة المرور (8 أحرف على الأقل) *</span><input type="password" name="owner_password" required minlength="8" autocomplete="new-password" class="input"></label>
            @endif
            <label class="block md:col-span-2"><span class="label">ملاحظات داخلية</span><textarea name="notes" rows="2" class="input">{{ $v('notes') }}</textarea></label>
        </section>

        <div class="flex gap-2">
            <button class="btn-primary">{{ $isNew ? 'إنشاء النشاط' : 'حفظ' }}</button>
            <a href="{{ route('platform.tenants.index') }}" class="btn-secondary">رجوع</a>
        </div>
    </form>
</div>
@endsection
