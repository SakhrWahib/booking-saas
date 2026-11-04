@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50" dir="rtl">
    <div class="p-6">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">إدارة الكوبونات</h1>
                        <p class="text-gray-600 mt-1">عرض وإدارة جميع الكوبونات والخصومات المتاحة في النظام</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.coupons.create') }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-plus"></i>
                            إضافة كوبون جديد
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">إجمالي الكوبونات</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $coupons->total() }}</p>
                    </div>
                    <div class="h-12 w-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-ticket-alt text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">الكوبونات النشطة</p>
                        <p class="text-2xl font-bold text-green-600">{{ $coupons->where('is_active', true)->count() }}</p>
                    </div>
                    <div class="h-12 w-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">الكوبونات المتوقفة</p>
                        <p class="text-2xl font-bold text-red-600">{{ $coupons->where('is_active', false)->count() }}</p>
                    </div>
                    <div class="h-12 w-12 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-times-circle text-red-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">إجمالي الاستخدامات</p>
                        <p class="text-2xl font-bold text-purple-600">
                            {{ $coupons->sum('used_count') }}
                        </p>
                    </div>
                    <div class="h-12 w-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Coupons Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">قائمة الكوبونات</h3>
                    <div class="flex gap-2">
                        <div class="relative">
                            <input type="text" 
                                   class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm" 
                                   placeholder="البحث في الكوبونات..." 
                                   id="searchInput">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الكوبون
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                نوع الخصم
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                القيمة
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الاستخدامات
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الصلاحية
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الحالة
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الإجراءات
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($coupons as $coupon)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $coupon->name }}</div>
                                    <div class="text-sm text-gray-500 font-mono">{{ $coupon->code }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $coupon->type === 'percentage' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $coupon->type === 'percentage' ? 'نسبة مئوية' : 'مبلغ ثابت' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    @if($coupon->type === 'percentage')
                                        {{ $coupon->value }}%
                                    @else
                                        {{ number_format($coupon->value, 2) }} ر.س
                                    @endif
                                </div>
                                @if($coupon->min_amount > 0)
                                    <div class="text-xs text-gray-500">الحد الأدنى: {{ number_format($coupon->min_amount, 2) }} ر.س</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $coupon->used_count }}</div>
                                @if($coupon->max_uses)
                                    <div class="text-xs text-gray-500">من {{ $coupon->max_uses }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($coupon->start_date || $coupon->end_date)
                                    <div class="text-sm text-gray-900">
                                        @if($coupon->start_date)
                                            <div>من: {{ $coupon->start_date->format('Y/m/d') }}</div>
                                        @endif
                                        @if($coupon->end_date)
                                            <div>إلى: {{ $coupon->end_date->format('Y/m/d') }}</div>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-sm text-gray-500">غير محدد</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $coupon->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $coupon->is_active ? 'نشط' : 'متوقف' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.coupons.show', $coupon) }}" 
                                       class="text-blue-600 hover:text-blue-900" 
                                       title="عرض التفاصيل">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.coupons.edit', $coupon) }}" 
                                       class="text-yellow-600 hover:text-yellow-900" 
                                       title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.coupons.toggle-status', $coupon) }}" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="text-{{ $coupon->is_active ? 'red' : 'green' }}-600 hover:text-{{ $coupon->is_active ? 'red' : 'green' }}-900"
                                                title="{{ $coupon->is_active ? 'إيقاف' : 'تفعيل' }}">
                                            <i class="fas fa-{{ $coupon->is_active ? 'pause' : 'play' }}"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا الكوبون؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900" 
                                                title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <i class="fas fa-ticket-alt text-4xl mb-4"></i>
                                    <p class="text-lg font-medium">لا توجد كوبونات</p>
                                    <p class="text-sm">قم بإضافة كوبون جديد للبدء</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($coupons->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $coupons->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<script>
// البحث في الجدول
document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>
@endsection
