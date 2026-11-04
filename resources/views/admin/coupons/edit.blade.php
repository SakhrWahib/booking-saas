@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50" dir="rtl">
    <div class="p-6">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">تعديل الكوبون</h1>
                        <p class="text-gray-600 mt-1">تعديل بيانات الكوبون: {{ $coupon->name }}</p>
                    </div>
                    <a href="{{ route('admin.coupons.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                        <i class="fas fa-arrow-right"></i>
                        العودة للقائمة
                    </a>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('admin.coupons.update', $coupon) }}" id="couponForm">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 xl:grid-cols-4 gap-6">
                <!-- القائمة الجانبية -->
                <div class="xl:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 sticky top-6">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-list text-blue-600"></i>
                                معلومات الكوبون
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">تفاصيل الكوبون الأساسية</p>
                        </div>
                        
                        <div class="px-6 py-4 space-y-4">
                            <!-- رمز الكوبون -->
                            <div class="bg-blue-50 rounded-lg p-4">
                                <h4 class="font-medium text-blue-900 mb-3 flex items-center gap-2">
                                    <i class="fas fa-ticket-alt text-blue-600"></i>
                                    رمز الكوبون
                                </h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-blue-700">رمز فريد للكوبون</span>
                                    </div>
                                </div>
                            </div>

                            <!-- نوع الخصم -->
                            <div class="bg-green-50 rounded-lg p-4">
                                <h4 class="font-medium text-green-900 mb-3 flex items-center gap-2">
                                    <i class="fas fa-percentage text-green-600"></i>
                                    نوع الخصم
                                </h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-green-700">نسبة مئوية أو مبلغ ثابت</span>
                                    </div>
                                </div>
                            </div>

                            <!-- الصلاحية -->
                            <div class="bg-orange-50 rounded-lg p-4">
                                <h4 class="font-medium text-orange-900 mb-3 flex items-center gap-2">
                                    <i class="fas fa-calendar-alt text-orange-600"></i>
                                    الصلاحية
                                </h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-orange-700">تواريخ البداية والنهاية</span>
                                    </div>
                                </div>
                            </div>

                            <!-- الاستخدامات -->
                            <div class="bg-red-50 rounded-lg p-4">
                                <h4 class="font-medium text-red-900 mb-3 flex items-center gap-2">
                                    <i class="fas fa-users text-red-600"></i>
                                    الاستخدامات
                                </h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-red-700">الحد الأقصى لمرات الاستخدام</span>
                                    </div>
                                    <div class="text-xs text-red-600">
                                        المستخدم حالياً: {{ $coupon->used_count }}
                                    </div>
                                </div>
                            </div>

                            <!-- حالة الكوبون -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="font-medium text-gray-900 mb-3 flex items-center gap-2">
                                    <i class="fas fa-toggle-on text-gray-600"></i>
                                    حالة الكوبون
                                </h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-700">تفعيل الكوبون</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- المحتوى الرئيسي -->
                <div class="xl:col-span-3 space-y-6">
                    <!-- البيانات الأساسية -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-info-circle text-blue-600"></i>
                                البيانات الأساسية
                            </h3>
                        </div>
                        
                        <div class="px-6 py-6 space-y-6">
                            <!-- اسم الكوبون -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    اسم الكوبون <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $coupon->name) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                                       placeholder="مثال: خصم الصيف 2024">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- رمز الكوبون -->
                            <div>
                                <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                                    رمز الكوبون <span class="text-red-500">*</span>
                                </label>
                                <div class="flex gap-2">
                                    <input type="text" 
                                           id="code" 
                                           name="code" 
                                           value="{{ old('code', $coupon->code) }}"
                                           class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('code') border-red-500 @enderror"
                                           placeholder="مثال: SUMMER2024">
                                    <button type="button" 
                                            id="generateCode"
                                            class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg text-sm font-medium transition-colors duration-200">
                                        توليد تلقائي
                                    </button>
                                </div>
                                @error('code')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- نوع الخصم والقيمة -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-percentage text-green-600"></i>
                                نوع الخصم والقيمة
                            </h3>
                        </div>
                        
                        <div class="px-6 py-6 space-y-6">
                            <!-- نوع الخصم -->
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                                    نوع الخصم <span class="text-red-500">*</span>
                                </label>
                                <select id="type" 
                                        name="type" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('type') border-red-500 @enderror">
                                    <option value="percentage" {{ old('type', $coupon->type) === 'percentage' ? 'selected' : '' }}>نسبة مئوية</option>
                                    <option value="fixed" {{ old('type', $coupon->type) === 'fixed' ? 'selected' : '' }}>مبلغ ثابت</option>
                                </select>
                                @error('type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- قيمة الخصم -->
                            <div>
                                <label for="value" class="block text-sm font-medium text-gray-700 mb-2">
                                    قيمة الخصم <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="number" 
                                           id="value" 
                                           name="value" 
                                           value="{{ old('value', $coupon->value) }}"
                                           step="0.01"
                                           min="0"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('value') border-red-500 @enderror"
                                           placeholder="0.00">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 text-sm" id="valueSuffix">{{ $coupon->type === 'percentage' ? '%' : 'ر.س' }}</span>
                                    </div>
                                </div>
                                @error('value')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- الحد الأدنى للطلب -->
                            <div>
                                <label for="min_amount" class="block text-sm font-medium text-gray-700 mb-2">
                                    الحد الأدنى للطلب
                                </label>
                                <div class="relative">
                                    <input type="number" 
                                           id="min_amount" 
                                           name="min_amount" 
                                           value="{{ old('min_amount', $coupon->min_amount) }}"
                                           step="0.01"
                                           min="0"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('min_amount') border-red-500 @enderror"
                                           placeholder="0.00">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 text-sm">ر.س</span>
                                    </div>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">اتركه صفر إذا لم يكن هناك حد أدنى</p>
                                @error('min_amount')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- الصلاحية والاستخدامات -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-calendar-alt text-orange-600"></i>
                                الصلاحية والاستخدامات
                            </h3>
                        </div>
                        
                        <div class="px-6 py-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- تاريخ البداية -->
                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                                        تاريخ البداية
                                    </label>
                                    <input type="date" 
                                           id="start_date" 
                                           name="start_date" 
                                           value="{{ old('start_date', $coupon->start_date ? $coupon->start_date->format('Y-m-d') : '') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('start_date') border-red-500 @enderror">
                                    <p class="mt-1 text-sm text-gray-500">اتركه فارغاً إذا كان الكوبون صالح من الآن</p>
                                    @error('start_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- تاريخ النهاية -->
                                <div>
                                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                                        تاريخ النهاية
                                    </label>
                                    <input type="date" 
                                           id="end_date" 
                                           name="end_date" 
                                           value="{{ old('end_date', $coupon->end_date ? $coupon->end_date->format('Y-m-d') : '') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('end_date') border-red-500 @enderror">
                                    <p class="mt-1 text-sm text-gray-500">اتركه فارغاً إذا كان الكوبون صالح إلى الأبد</p>
                                    @error('end_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- الحد الأقصى لمرات الاستخدام -->
                            <div>
                                <label for="max_uses" class="block text-sm font-medium text-gray-700 mb-2">
                                    الحد الأقصى لمرات الاستخدام
                                </label>
                                <input type="number" 
                                       id="max_uses" 
                                       name="max_uses" 
                                       value="{{ old('max_uses', $coupon->max_uses) }}"
                                       min="1"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('max_uses') border-red-500 @enderror"
                                       placeholder="اتركه فارغاً لاستخدام غير محدود">
                                <p class="mt-1 text-sm text-gray-500">اتركه فارغاً إذا كان الكوبون صالح لاستخدام غير محدود</p>
                                @if($coupon->used_count > 0)
                                    <p class="mt-1 text-sm text-orange-600">تحذير: الكوبون مستخدم {{ $coupon->used_count }} مرات</p>
                                @endif
                                @error('max_uses')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- حالة الكوبون -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-toggle-on text-gray-600"></i>
                                حالة الكوبون
                            </h3>
                        </div>
                        
                        <div class="px-6 py-6">
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1"
                                       {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="is_active" class="mr-2 block text-sm text-gray-900">
                                    تفعيل الكوبون
                                </label>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">سيتم تفعيل الكوبون تلقائياً عند حفظ التعديلات</p>
                        </div>
                    </div>

                    <!-- أزرار الحفظ -->
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('admin.coupons.index') }}" 
                           class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                            إلغاء
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors duration-200">
                            حفظ التعديلات
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// تغيير نوع الخصم
document.getElementById('type').addEventListener('change', function() {
    const valueSuffix = document.getElementById('valueSuffix');
    const valueInput = document.getElementById('value');
    
    if (this.value === 'percentage') {
        valueSuffix.textContent = '%';
        valueInput.max = '100';
        valueInput.placeholder = '0-100';
    } else {
        valueSuffix.textContent = 'ر.س';
        valueInput.removeAttribute('max');
        valueInput.placeholder = '0.00';
    }
});

// توليد رمز تلقائي
document.getElementById('generateCode').addEventListener('click', function() {
    fetch('{{ route("admin.coupons.generate-code") }}')
        .then(response => response.json())
        .then(data => {
            document.getElementById('code').value = data.code;
        })
        .catch(error => {
            console.error('Error generating code:', error);
        });
});

// التحقق من التواريخ
document.getElementById('end_date').addEventListener('change', function() {
    const startDate = document.getElementById('start_date').value;
    const endDate = this.value;
    
    if (startDate && endDate && startDate > endDate) {
        alert('تاريخ النهاية يجب أن يكون بعد تاريخ البداية');
        this.value = '';
    }
});

// تعيين القيمة الأولية للنوع
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const valueSuffix = document.getElementById('valueSuffix');
    const valueInput = document.getElementById('value');
    
    if (typeSelect.value === 'percentage') {
        valueSuffix.textContent = '%';
        valueInput.max = '100';
        valueInput.placeholder = '0-100';
    } else {
        valueSuffix.textContent = 'ر.س';
        valueInput.removeAttribute('max');
        valueInput.placeholder = '0.00';
    }
});
</script>
@endsection
