@extends('layouts.app')

@section('content')
@php
    $field = 'w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500';
    $val = fn ($key) => old($key, config("brand.$key"));
@endphp
<div class="min-h-screen bg-gray-50 p-6" dir="rtl">
    <div class="mx-auto max-w-4xl">
        <h1 class="mb-1 text-2xl font-bold text-gray-900">إعدادات النشاط</h1>
        <p class="mb-6 text-sm text-gray-500">هذه البيانات تظهر للعملاء في الموقع والرسائل. غيّرها في أي وقت.</p>

        @if(session('success'))
            <div class="mb-4 rounded-lg bg-green-50 p-3 text-green-700">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="mb-4 rounded-lg bg-red-50 p-3 text-red-700">
                @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <section class="rounded-xl bg-white p-6 shadow-sm">
                <h2 class="mb-4 font-semibold text-gray-800">الهوية</h2>
                <div class="grid gap-4 md:grid-cols-2">
                    <label class="block">
                        <span class="mb-1 block text-sm text-gray-700">اسم النشاط (عربي) *</span>
                        <input name="name" value="{{ $val('name') }}" required class="{{ $field }}">
                    </label>
                    <label class="block">
                        <span class="mb-1 block text-sm text-gray-700">اسم النشاط (إنجليزي)</span>
                        <input name="name_en" value="{{ $val('name_en') }}" dir="ltr" class="{{ $field }}">
                    </label>
                    <label class="block">
                        <span class="mb-1 block text-sm text-gray-700">نوع النشاط *</span>
                        <select name="business_type" class="{{ $field }}">
                            @foreach($presets as $key => $preset)
                                <option value="{{ $key }}" @selected($val('business_type') === $key)>{{ $preset['label'] }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="block">
                        <span class="mb-1 block text-sm text-gray-700">الشعار النصي</span>
                        <input name="tagline" value="{{ $val('tagline') }}" class="{{ $field }}">
                    </label>
                    <label class="block">
                        <span class="mb-1 block text-sm text-gray-700">اللون الأساسي *</span>
                        <input type="color" name="primary_color" value="{{ $val('primary_color') }}" class="h-10 w-24 cursor-pointer rounded border border-gray-300">
                    </label>
                    <label class="block">
                        <span class="mb-1 block text-sm text-gray-700">الشعار (صورة، حتى 2MB)</span>
                        <div class="flex items-center gap-3">
                            <img src="{{ config('brand.logo') }}" alt="" class="h-10 w-10 rounded object-contain">
                            <input type="file" name="logo_file" accept="image/*" class="text-sm">
                        </div>
                    </label>
                </div>
            </section>

            <section class="rounded-xl bg-white p-6 shadow-sm">
                <h2 class="mb-4 font-semibold text-gray-800">التواصل</h2>
                <div class="grid gap-4 md:grid-cols-2">
                    <label class="block">
                        <span class="mb-1 block text-sm text-gray-700">الجوال</span>
                        <input name="phone" value="{{ $val('phone') }}" placeholder="05XXXXXXXX" dir="ltr" class="{{ $field }}">
                    </label>
                    <label class="block">
                        <span class="mb-1 block text-sm text-gray-700">واتساب</span>
                        <input name="whatsapp" value="{{ $val('whatsapp') }}" placeholder="9665XXXXXXXX" dir="ltr" class="{{ $field }}">
                    </label>
                    <label class="block">
                        <span class="mb-1 block text-sm text-gray-700">البريد</span>
                        <input type="email" name="email" value="{{ $val('email') }}" dir="ltr" class="{{ $field }}">
                    </label>
                    <label class="block">
                        <span class="mb-1 block text-sm text-gray-700">العنوان</span>
                        <input name="address" value="{{ $val('address') }}" class="{{ $field }}">
                    </label>
                </div>
            </section>

            <section class="rounded-xl bg-white p-6 shadow-sm">
                <h2 class="mb-1 font-semibold text-gray-800">المسميات</h2>
                <p class="mb-4 text-sm text-gray-500">اتركها فارغة لاستخدام مسميات نوع النشاط تلقائيًا.</p>
                <div class="grid gap-4 md:grid-cols-2">
                    <label class="block">
                        <span class="mb-1 block text-sm text-gray-700">اسم الخدمة (مثل: الغسلة، الموعد)</span>
                        <input name="service_label" value="{{ old('service_label', \App\Models\SystemSetting::getValue('service_label')) }}" placeholder="{{ config('brand.service_label') }}" class="{{ $field }}">
                    </label>
                    <label class="block">
                        <span class="mb-1 block text-sm text-gray-700">اسم مقدّم الخدمة (مثل: الفني، الطبيب)</span>
                        <input name="provider_label" value="{{ old('provider_label', \App\Models\SystemSetting::getValue('provider_label')) }}" placeholder="{{ config('brand.provider_label') }}" class="{{ $field }}">
                    </label>
                </div>
            </section>

            <button type="submit" class="rounded-lg bg-indigo-600 px-6 py-2.5 font-medium text-white hover:bg-indigo-700">حفظ الإعدادات</button>
        </form>
    </div>
</div>
@endsection
