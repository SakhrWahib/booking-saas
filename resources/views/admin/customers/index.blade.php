@extends('layouts.app')

@section('page-title', 'العملاء')

@section('content')
<div class="p-4 lg:p-8">
    <x-page-header title="العملاء" :subtitle="'إجمالي العملاء: ' . number_format($customers->total())">
        <x-slot:actions>
            @if(auth()->user()->hasPermission('customers.create'))
                <a href="{{ route('admin.customers.create') }}" class="btn-primary"><i class="fa-solid fa-user-plus"></i> عميل جديد</a>
            @endif
        </x-slot:actions>
    </x-page-header>

    <form method="GET" action="{{ route('admin.customers.index') }}" class="card mb-4 flex gap-2 p-4">
        <div class="relative flex-1">
            <i class="fa-solid fa-magnifying-glass pointer-events-none absolute top-1/2 -translate-y-1/2 text-slate-400 {{ app()->getLocale() === 'en' ? 'left-3' : 'right-3' }}"></i>
            <input type="search" name="search" value="{{ request('search') }}" placeholder="الاسم أو رقم الجوال" class="input {{ app()->getLocale() === 'en' ? 'pl-9' : 'pr-9' }}">
        </div>
        <button class="btn-primary">بحث</button>
        @if(request('search'))<a href="{{ route('admin.customers.index') }}" class="btn-secondary">مسح</a>@endif
    </form>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>العميل</th>
                        <th>الجوال</th>
                        <th>الحجوزات</th>
                        <th>تاريخ التسجيل</th>
                        <th class="text-end">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                        <tr>
                            <td>
                                <a href="{{ route('admin.customers.show', $customer) }}" class="flex items-center gap-3">
                                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-slate-100 font-bold text-slate-600">{{ mb_substr($customer->name ?? '?', 0, 1) }}</span>
                                    <span>
                                        <span class="block font-semibold text-slate-900 hover:text-brand">{{ $customer->name }}</span>
                                        @if($customer->email)<span class="block text-xs text-slate-500">{{ $customer->email }}</span>@endif
                                    </span>
                                </a>
                            </td>
                            <td dir="ltr" class="text-end md:text-start">
                                <a href="tel:{{ $customer->phone }}" class="hover:text-brand">{{ $customer->phone }}</a>
                            </td>
                            <td>
                                <span class="font-bold">{{ $customer->bookings_count }}</span>
                                @if($customer->active_bookings_count)
                                    <span class="ms-1 rounded-full bg-blue-50 px-2 py-0.5 text-xs font-bold text-blue-700">{{ $customer->active_bookings_count }} نشط</span>
                                @endif
                            </td>
                            <td class="text-slate-500">{{ $customer->created_at?->format('Y/m/d') }}</td>
                            <td>
                                <div class="flex items-center justify-end gap-1">
                                    @if($customer->phone)
                                        <a href="https://wa.me/{{ preg_replace('/^0/', '966', preg_replace('/\D/', '', $customer->phone)) }}" target="_blank" rel="noopener" class="btn-icon text-green-600 hover:bg-green-50" title="واتساب"><i class="fa-brands fa-whatsapp"></i></a>
                                    @endif
                                    <a href="{{ route('admin.customers.show', $customer) }}" class="btn-icon text-slate-500 hover:bg-slate-100" title="عرض"><i class="fa-regular fa-eye"></i></a>
                                    <a href="{{ route('admin.customers.edit', $customer) }}" class="btn-icon text-slate-500 hover:bg-slate-100" title="تعديل"><i class="fa-regular fa-pen-to-square"></i></a>
                                    @if(auth()->user()->hasPermission('customers.delete'))
                                        <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" onsubmit="return confirm('حذف العميل {{ addslashes($customer->name) }}؟ لا يمكن التراجع.')">
                                            @csrf @method('DELETE')
                                            <button class="btn-icon text-red-600 hover:bg-red-50" title="حذف"><i class="fa-regular fa-trash-can"></i></button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-16 text-center text-slate-400">
                                <i class="fa-solid fa-users mb-2 text-4xl"></i>
                                <div>لا يوجد عملاء</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($customers->hasPages())
            <div class="border-t border-slate-100 px-4 py-3">{{ $customers->withQueryString()->links() }}</div>
        @endif
    </div>
</div>
@endsection
