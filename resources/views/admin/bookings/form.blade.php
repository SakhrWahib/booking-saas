@extends('layouts.app')

@php
    $editing = $booking->exists;
    $ar = app()->getLocale() !== 'en';
    $t = fn ($a, $e) => $ar ? $a : $e;
    $initial = [
        'customerMode' => old('new_customer_phone') ? 'new' : 'existing',
        'customerId' => (string) old('customer_id', $booking->customer_id ?? ''),
        'newName' => old('new_customer_name', ''),
        'newPhone' => old('new_customer_phone', ''),
        'productId' => (string) old('product_id', $booking->product_id ?? ''),
        'providerId' => (string) old('service_provider_id', $booking->service_provider_id ?? ''),
        'date' => old('booking_date', $booking->booking_date ? \Carbon\Carbon::parse($booking->booking_date)->toDateString() : today()->toDateString()),
        'time' => old('booking_time', $booking->booking_time ? \Carbon\Carbon::parse($booking->booking_time)->format('H:i') : ''),
        'quantity' => (int) old('quantity', $booking->quantity ?: 1),
        'addOns' => old('add_ons', array_column($booking->selected_add_ons ?? [], 'name')),
    ];
@endphp

@section('page-title', $editing ? $t('تعديل الحجز', 'Edit booking') . ' #' . $booking->booking_number : $t('حجز جديد', 'New booking'))

@section('content')
<div class="mx-auto max-w-5xl p-4 lg:p-8" x-data="bookingForm(@js($initial), @js($customers), @js($products), @js($providers))" x-init="init()">
    <x-page-header :title="$editing ? $t('تعديل الحجز', 'Edit booking') . ' #' . $booking->booking_number : $t('حجز جديد', 'New booking')"
                   :subtitle="$t('اختر العميل والخدمة والموعد، والسعر يُحسب تلقائيًا', 'Pick the customer, service and time; the price is calculated for you')">
        <x-slot:actions>
            <a href="{{ $editing ? route('admin.bookings.show', $booking) : route('admin.bookings.index') }}" class="btn-secondary">{{ $t('رجوع', 'Back') }}</a>
        </x-slot:actions>
    </x-page-header>

    @if($errors->any())
        <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <div class="mb-1 font-bold">{{ $t('راجع الحقول التالية:', 'Please fix the following:') }}</div>
            <ul class="list-inside list-disc">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ $editing ? route('admin.bookings.update', $booking) : route('admin.bookings.store') }}" class="grid gap-6 lg:grid-cols-3" @submit="submitting = true">
        @csrf
        @if($editing) @method('PUT') @endif

        <div class="space-y-6 lg:col-span-2">
            {{-- 1. العميل --}}
            <section class="card p-5">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="font-bold"><span class="me-2 inline-flex h-6 w-6 items-center justify-center rounded-full bg-brand text-xs text-white">1</span>{{ $t('العميل', 'Customer') }}</h3>
                    <div class="flex rounded-xl bg-slate-100 p-1 text-sm">
                        <button type="button" @click="customerMode = 'existing'" :class="customerMode === 'existing' ? 'bg-white shadow' : ''" class="rounded-lg px-3 py-1 font-semibold">{{ $t('عميل حالي', 'Existing') }}</button>
                        <button type="button" @click="customerMode = 'new'" :class="customerMode === 'new' ? 'bg-white shadow' : ''" class="rounded-lg px-3 py-1 font-semibold">{{ $t('عميل جديد', 'New') }}</button>
                    </div>
                </div>
                <div x-show="customerMode === 'existing'" class="space-y-2">
                    <input type="search" x-model="customerSearch" placeholder="{{ $t('ابحث بالاسم أو الجوال', 'Search name or phone') }}" class="input">
                    <select name="customer_id" x-model="customerId" :disabled="customerMode !== 'existing'" size="5" class="input h-auto">
                        <template x-for="c in filteredCustomers()" :key="c.id">
                            <option :value="String(c.id)" x-text="c.name + ' — ' + (c.phone || '')"></option>
                        </template>
                    </select>
                </div>
                <div x-show="customerMode === 'new'" x-cloak class="grid gap-3 sm:grid-cols-2">
                    <label><span class="label">{{ $t('الاسم', 'Name') }}</span><input name="new_customer_name" x-model="newName" :disabled="customerMode !== 'new'" maxlength="100" class="input"></label>
                    <label><span class="label">{{ $t('الجوال', 'Phone') }}</span><input name="new_customer_phone" x-model="newPhone" :disabled="customerMode !== 'new'" placeholder="05XXXXXXXX" pattern="05[0-9]{8}" dir="ltr" class="input"></label>
                    <p class="text-xs text-slate-500 sm:col-span-2">{{ $t('إن كان الرقم مسجلًا مسبقًا يُستخدم العميل نفسه', 'If the phone already exists, that customer is used') }}</p>
                </div>
            </section>

            {{-- 2. الخدمة والإضافات --}}
            <section class="card p-5">
                <h3 class="mb-4 font-bold"><span class="me-2 inline-flex h-6 w-6 items-center justify-center rounded-full bg-brand text-xs text-white">2</span>{{ $t('الخدمة', 'Service') }}</h3>
                <div class="grid gap-2 sm:grid-cols-2">
                    <template x-for="p in products" :key="p.id">
                        <label class="flex cursor-pointer items-center justify-between rounded-xl border p-3 transition" :class="productId === String(p.id) ? 'border-brand ring-2 ring-[color:var(--brand-color)]' : 'border-slate-200 hover:bg-slate-50'">
                            <span class="flex items-center gap-2">
                                <input type="radio" name="product_id" :value="String(p.id)" x-model="productId" @change="onProductChange()" class="h-4 w-4">
                                <span class="font-semibold" x-text="p.name"></span>
                            </span>
                            <span class="text-sm font-bold text-brand" x-text="money(p.price)"></span>
                        </label>
                    </template>
                </div>
                <template x-if="product() && product().add_ons.length">
                    <div class="mt-4">
                        <span class="label">{{ $t('إضافات اختيارية', 'Optional add-ons') }}</span>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="a in product().add_ons" :key="a.name">
                                <label class="flex cursor-pointer items-center gap-2 rounded-xl border border-slate-200 px-3 py-2 text-sm" :class="addOns.includes(a.name) ? 'bg-slate-50 font-semibold' : ''">
                                    <input type="checkbox" name="add_ons[]" :value="a.name" x-model="addOns" class="h-4 w-4">
                                    <span x-text="a.name"></span><span class="text-slate-500" x-text="'+' + money(a.price)"></span>
                                </label>
                            </template>
                        </div>
                    </div>
                </template>
                <label class="mt-4 block w-32"><span class="label">{{ $t('الكمية', 'Quantity') }}</span><input type="number" name="quantity" x-model.number="quantity" min="1" max="20" class="input"></label>
            </section>

            {{-- 3. مقدّم الخدمة والموعد --}}
            <section class="card p-5">
                <h3 class="mb-4 font-bold"><span class="me-2 inline-flex h-6 w-6 items-center justify-center rounded-full bg-brand text-xs text-white">3</span>{{ config('brand.provider_label') }} {{ $t('والموعد', '& time') }}</h3>
                <div class="grid gap-3 sm:grid-cols-2">
                    <label><span class="label">{{ config('brand.provider_label') }}</span>
                        <select name="service_provider_id" x-model="providerId" @change="loadSlots()" class="input">
                            <option value="">{{ $t('اختر...', 'Choose...') }}</option>
                            <template x-for="s in availableProviders()" :key="s.id"><option :value="String(s.id)" x-text="s.name"></option></template>
                        </select>
                    </label>
                    <label><span class="label">{{ $t('التاريخ', 'Date') }}</span><input type="date" name="booking_date" x-model="date" @change="loadSlots()" @if(!$editing) min="{{ today()->toDateString() }}" @endif class="input"></label>
                </div>
                <input type="hidden" name="booking_time" :value="time">
                <div class="mt-4">
                    <span class="label">{{ $t('الوقت', 'Time') }}</span>
                    <p x-show="!providerId || !date" class="text-sm text-slate-400">{{ $t('اختر مقدّم الخدمة والتاريخ لعرض المواعيد', 'Choose a provider and date to see times') }}</p>
                    <p x-show="loading" class="text-sm text-slate-500"><i class="fa-solid fa-spinner fa-spin"></i> {{ $t('جاري التحميل...', 'Loading...') }}</p>
                    <p x-show="!loading && message && slots.length === 0" x-text="message" class="rounded-xl bg-amber-50 p-3 text-sm text-amber-800"></p>
                    <div class="grid grid-cols-3 gap-2 sm:grid-cols-4 md:grid-cols-6" x-show="!loading && slots.length">
                        <template x-for="s in slots" :key="s.time">
                            <button type="button" @click="time = s.time" class="rounded-xl border px-2 py-2 text-center text-sm font-bold transition"
                                    :class="time === s.time ? 'border-transparent bg-brand text-white' : 'border-slate-200 hover:bg-slate-50'">
                                <span x-text="s.time"></span>
                                <span x-show="s.remaining > 1" class="block text-[10px] font-normal opacity-80" x-text="s.remaining + ' {{ $t('متاح', 'left') }}'"></span>
                            </button>
                        </template>
                    </div>
                </div>
            </section>

            <section class="card grid gap-3 p-5 sm:grid-cols-2">
                <label><span class="label">{{ $t('ملاحظات للعميل', 'Notes') }}</span><textarea name="notes" rows="2" class="input">{{ old('notes', $booking->notes) }}</textarea></label>
                <label><span class="label">{{ $t('ملاحظات داخلية', 'Internal notes') }}</span><textarea name="admin_notes" rows="2" class="input">{{ old('admin_notes', $booking->admin_notes) }}</textarea></label>
            </section>
        </div>

        {{-- الملخص --}}
        <aside class="space-y-4 lg:sticky lg:top-20 lg:self-start">
            <section class="card p-5">
                <h3 class="mb-3 font-bold">{{ $t('الملخص', 'Summary') }}</h3>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between"><dt class="text-slate-500">{{ $t('الخدمة', 'Service') }}</dt><dd x-text="product() ? money(product().price) : '—'"></dd></div>
                    <div class="flex justify-between" x-show="addOnsTotal() > 0"><dt class="text-slate-500">{{ $t('الإضافات', 'Add-ons') }}</dt><dd x-text="money(addOnsTotal())"></dd></div>
                    <div class="flex justify-between" x-show="quantity > 1"><dt class="text-slate-500">{{ $t('الكمية', 'Qty') }}</dt><dd x-text="'× ' + quantity"></dd></div>
                    <div class="flex justify-between border-t border-slate-100 pt-2 text-base font-extrabold"><dt>{{ $t('الإجمالي', 'Total') }}</dt><dd class="text-brand" x-text="money(total())"></dd></div>
                </dl>
                <label class="mt-4 block"><span class="label">{{ $t('كوبون خصم', 'Coupon') }}</span><input name="coupon_code" value="{{ old('coupon_code', $booking->coupon?->code) }}" class="input" dir="ltr"></label>
                <p class="mt-1 text-xs text-slate-400">{{ $t('الخصم يُطبَّق ويُتحقق منه عند الحفظ', 'The discount is checked when saving') }}</p>
            </section>

            @if($editing)
                <section class="card p-5">
                    <label><span class="label">{{ $t('الحالة', 'Status') }}</span>
                        <select name="status" class="input">
                            @foreach(['pending' => $t('قيد الانتظار', 'Pending'), 'confirmed' => $t('مؤكد', 'Confirmed'), 'paid' => $t('مدفوع', 'Paid'), 'completed' => $t('مكتمل', 'Completed'), 'cancelled' => $t('ملغي', 'Cancelled')] as $k => $l)
                                <option value="{{ $k }}" @selected(old('status', $booking->status) === $k)>{{ $l }}</option>
                            @endforeach
                        </select>
                    </label>
                </section>
            @endif

            <button class="btn-primary w-full py-3" :disabled="submitting || !canSubmit()">
                <i class="fa-solid" :class="submitting ? 'fa-spinner fa-spin' : 'fa-check'"></i>
                {{ $editing ? $t('حفظ التعديلات', 'Save changes') : $t('تأكيد الحجز', 'Create booking') }}
            </button>
            <p x-show="!canSubmit()" class="text-center text-xs text-slate-400">{{ $t('أكمل العميل والخدمة ومقدّم الخدمة والوقت', 'Complete customer, service, provider and time') }}</p>
        </aside>
    </form>
</div>
@endsection

@push('scripts')
<script>
function bookingForm(initial, customers, products, providers) {
    return {
        ...initial, customers, products, providers,
        customerSearch: '', slots: [], loading: false, message: '', submitting: false,
        excludeId: @js($editing ? $booking->id : null),
        slotsUrl: @js(route('admin.bookings.api.available-time-slots')),
        init() { if (this.providerId && this.date) this.loadSlots(true); },
        money(v) { return Number(v || 0).toLocaleString('{{ $ar ? 'ar-SA' : 'en-US' }}', { maximumFractionDigits: 2 }) + ' {{ $ar ? 'ر.س' : 'SAR' }}'; },
        product() { return this.products.find(p => String(p.id) === this.productId); },
        addOnsTotal() { const p = this.product(); return p ? p.add_ons.filter(a => this.addOns.includes(a.name)).reduce((s, a) => s + a.price, 0) : 0; },
        total() { const p = this.product(); return p ? (p.price + this.addOnsTotal()) * Math.max(1, this.quantity || 1) : 0; },
        filteredCustomers() {
            const q = this.customerSearch.trim().toLowerCase();
            const list = q ? this.customers.filter(c => (c.name || '').toLowerCase().includes(q) || (c.phone || '').includes(q)) : this.customers;
            return list.slice(0, 200);
        },
        availableProviders() {
            if (!this.productId) return this.providers;
            return this.providers.filter(s => !s.product_ids.length || s.product_ids.includes(Number(this.productId)));
        },
        onProductChange() {
            this.addOns = [];
            if (this.providerId && !this.availableProviders().some(s => String(s.id) === this.providerId)) {
                this.providerId = ''; this.slots = []; this.time = '';
            }
        },
        async loadSlots(keepTime = false) {
            if (!keepTime) this.time = '';
            this.slots = []; this.message = '';
            if (!this.providerId || !this.date) return;
            this.loading = true;
            const params = new URLSearchParams({ date: this.date, service_provider_id: this.providerId });
            if (this.excludeId) params.append('exclude_booking_id', this.excludeId);
            try {
                const res = await fetch(this.slotsUrl + '?' + params, { headers: { Accept: 'application/json' } });
                const data = await res.json();
                this.slots = data.available_slots || [];
                this.message = data.message || '';
                if (keepTime && this.time && !this.slots.some(s => s.time === this.time)) this.time = '';
            } catch (e) {
                this.message = '{{ $t('تعذّر تحميل المواعيد، حاول مرة أخرى', 'Could not load times, try again') }}';
            } finally { this.loading = false; }
        },
        canSubmit() {
            const hasCustomer = this.customerMode === 'existing' ? !!this.customerId : (this.newName && /^05\d{8}$/.test(this.newPhone));
            return hasCustomer && this.productId && this.providerId && this.date && this.time;
        },
    };
}
</script>
@endpush
