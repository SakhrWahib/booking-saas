@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50" dir="rtl">
    <div class="p-6">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">إضافة حجز جديد</h1>
                        <p class="text-gray-600 mt-1">قم بملء البيانات التالية لإضافة حجز جديد للنظام</p>
                    </div>
                    <a href="{{ route('admin.bookings.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                        <i class="fas fa-arrow-right"></i>
                        العودة للقائمة
                    </a>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('admin.bookings.store') }}" id="bookingForm" enctype="multipart/form-data">
            @csrf
            
            <!-- مصدر الحجز -->
            <input type="hidden" name="booking_source" value="admin_panel">
            <!-- العدد (قيمة ثابتة) -->
            <input type="hidden" name="quantity" value="1">
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- البيانات الأساسية -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-calendar-alt text-blue-600"></i>
                            بيانات الحجز
                        </h3>
                    </div>
                    
                    <div class="px-6 py-6 space-y-6">
                        <!-- العميل -->
                        <div>
                            <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-2">
                                العميل <span class="text-red-500">*</span>
                            </label>
                            <select class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right @error('customer_id') border-red-500 @enderror" 
                                    id="customer_id" name="customer_id" required>
                                <option value="">اختر العميل</option>
                                @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }} - {{ $customer->phone }}
                                </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- الخدمة/المنتج -->
                        <div>
                            <label for="product_id" class="block text-sm font-medium text-gray-700 mb-2">
                                الخدمة/المنتج <span class="text-red-500">*</span>
                            </label>
                            <select class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right @error('product_id') border-red-500 @enderror" 
                                    id="product_id" name="product_id" required>
                                <option value="">اختر الخدمة</option>
                                @foreach($products as $product)
                                <option value="{{ $product->id }}" data-price="{{ $product->price }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }} - {{ number_format($product->price, 2) }} ر.س
                                </option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- مقدم الخدمة -->
                        <div>
                            <label for="service_provider_id" class="block text-sm font-medium text-gray-700 mb-2">
                                مقدم الخدمة <span class="text-red-500">*</span>
                            </label>
                            <select class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right @error('service_provider_id') border-red-500 @enderror" 
                                    id="service_provider_id" name="service_provider_id" required>
                                <option value="">اختر مقدم الخدمة</option>
                                @foreach($serviceProviders as $provider)
                                <option value="{{ $provider->id }}" {{ old('service_provider_id') == $provider->id ? 'selected' : '' }}>
                                    {{ $provider->name }} - {{ $provider->phone }}
                                </option>
                                @endforeach
                            </select>
                            @error('service_provider_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- الإضافات المتاحة -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                الإضافات المتاحة
                            </label>
                            <div id="productAddOnsContainer" class="space-y-3">
                                <div class="text-center text-gray-500 py-8">
                                    <i class="fas fa-plus-circle text-4xl mb-2"></i>
                                    <p>اختر المنتج لعرض الإضافات المتاحة</p>
                                </div>
                            </div>
                            <!-- Hidden inputs للإضافات المحددة -->
                            <div id="selectedAddOnsInputs"></div>
                            @error('selected_add_ons')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- الكوبون -->
                        <div>
                            <label for="coupon_code" class="block text-sm font-medium text-gray-700 mb-2">
                                كوبون الخصم
                            </label>
                            <div class="flex gap-2">
                                <input type="text" 
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right @error('coupon_code') border-red-500 @enderror" 
                                       id="coupon_code" name="coupon_code" 
                                       placeholder="أدخل كود الكوبون" 
                                       value="{{ old('coupon_code') }}">
                                <button type="button" 
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200"
                                        onclick="validateCoupon()">
                                    <i class="fas fa-check"></i>
                                    تحقق
                                </button>
                            </div>
                            <div id="couponMessage" class="mt-2 text-sm"></div>
                            @error('coupon_code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- السعر الإجمالي -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                الفاتورة المتوقعة
                            </label>
                            <div class="bg-green-50 border border-green-300 rounded-lg px-3 py-2">
                                <span id="totalPrice" class="text-lg font-semibold text-green-600">0.00 ر.س</span>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">سيتم حساب السعر تلقائياً بناءً على الخدمة والعدد</p>
                        </div>
                    </div>
                </div>

                <!-- تفاصيل الحجز -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-clock text-green-600"></i>
                            تفاصيل الوقت والتاريخ
                        </h3>
                    </div>
                    
                    <div class="px-6 py-6 space-y-6">
                        <!-- تاريخ الحجز -->
                        <div>
                            <label for="booking_date" class="block text-sm font-medium text-gray-700 mb-2">
                                تاريخ الحجز <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right @error('booking_date') border-red-500 @enderror" 
                                   id="booking_date" name="booking_date" value="{{ old('booking_date') }}" 
                                   min="{{ date('Y-m-d') }}" required>
                            @error('booking_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <div id="dateValidation" class="mt-2 text-sm"></div>
                        </div>

                        <!-- الأوقات المتاحة -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                الأوقات المتاحة <span class="text-red-500">*</span>
                            </label>
                            <div id="availableTimesContainer" class="space-y-3">
                                <div class="text-center text-gray-500 py-8">
                                    <i class="fas fa-clock text-4xl mb-2"></i>
                                    <p>اختر التاريخ ومقدم الخدمة لعرض الأوقات المتاحة</p>
                                </div>
                            </div>
                            <input type="hidden" name="booking_time" id="selectedTime" required>
                            @error('booking_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- معلومات الأوقات -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h4 class="font-medium text-blue-800 mb-3">معلومات الأوقات</h4>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-blue-700 font-medium">الصباح</p>
                                    <p class="text-blue-600">11:00 ص</p>
                                </div>
                                <div>
                                    <p class="text-blue-700 font-medium">بعد الظهر</p>
                                    <p class="text-blue-600">01:00 م - 05:00 م</p>
                                </div>
                                <div>
                                    <p class="text-blue-700 font-medium">المساء</p>
                                    <p class="text-blue-600">07:00 م - 09:00 م</p>
                                </div>
                                <div>
                                    <p class="text-blue-700 font-medium">أيام العمل</p>
                                    <p class="text-blue-600">السبت - الخميس</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ملاحظات -->
            <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-sticky-note text-yellow-600"></i>
                        ملاحظات إضافية
                    </h3>
                </div>
                <div class="px-6 py-6 space-y-4">
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            ملاحظات عامة
                        </label>
                        <textarea class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right @error('notes') border-red-500 @enderror" 
                                  id="notes" name="notes" rows="3" 
                                  placeholder="أضف أي ملاحظات عامة حول الحجز">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-2">
                            ملاحظات الإدارة
                        </label>
                        <textarea class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right @error('admin_notes') border-red-500 @enderror" 
                                  id="admin_notes" name="admin_notes" rows="2" 
                                  placeholder="ملاحظات خاصة بالإدارة">{{ old('admin_notes') }}</textarea>
                        @error('admin_notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200 px-6 py-4">
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-600">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        تأكد من صحة البيانات وتوفر الوقت قبل الحفظ
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.bookings.index') }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-times"></i>
                            إلغاء
                        </a>
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2"
                                id="submitBtn">
                            <i class="fas fa-save"></i>
                            حفظ الحجز
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
let selectedTimeSlot = null;
let selectedAddOns = [];
let productPrice = 0;

// حساب السعر الإجمالي
function calculateTotalPrice() {
    const productSelect = document.getElementById('product_id');
    const totalPriceSpan = document.getElementById('totalPrice');
    
    if (productSelect.value) {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        productPrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        const quantity = 1; // قيمة ثابتة
        
        // حساب مجموع أسعار الإضافات
        let addOnsTotal = 0;
        selectedAddOns.forEach(addOn => {
            addOnsTotal += parseFloat(addOn.price) || 0;
        });
        
        const total = (productPrice + addOnsTotal) * quantity;
        
        totalPriceSpan.textContent = total.toFixed(2) + ' ر.س';
        
        // عرض تفصيل السعر
        updatePriceBreakdown(productPrice, addOnsTotal, quantity, total);
    } else {
        totalPriceSpan.textContent = '0.00 ر.س';
    }
}

// عرض تفصيل السعر
function updatePriceBreakdown(basePrice, addOnsTotal, quantity, total) {
    const totalPriceContainer = document.getElementById('totalPrice').parentElement;
    
    // إزالة التفصيل السابق إن وجد
    const existingBreakdown = totalPriceContainer.querySelector('.price-breakdown');
    if (existingBreakdown) {
        existingBreakdown.remove();
    }
    
            if (addOnsTotal > 0) {
            const breakdown = document.createElement('div');
            breakdown.className = 'price-breakdown mt-2 text-sm text-gray-600 space-y-1';
            
            let html = `
                <div class="flex justify-between">
                    <span>سعر المنتج:</span>
                    <span>${basePrice.toFixed(2)} ر.س</span>
                </div>
            `;
            
            if (addOnsTotal > 0) {
                html += `
                    <div class="flex justify-between">
                        <span>الإضافات (${selectedAddOns.length}):</span>
                        <span>${addOnsTotal.toFixed(2)} ر.س</span>
                    </div>
                `;
            }
            
            html += `
                <div class="border-t pt-1 flex justify-between font-semibold">
                    <span>الإجمالي:</span>
                    <span>${total.toFixed(2)} ر.س</span>
                </div>
            `;
            
            breakdown.innerHTML = html;
            totalPriceContainer.appendChild(breakdown);
        }
}

// تحميل إضافات المنتج
function loadProductAddOns() {
    const productId = document.getElementById('product_id').value;
    const container = document.getElementById('productAddOnsContainer');
    
    if (!productId) {
        container.innerHTML = `
            <div class="text-center text-gray-500 py-8">
                <i class="fas fa-plus-circle text-4xl mb-2"></i>
                <p>اختر المنتج لعرض الإضافات المتاحة</p>
            </div>
        `;
        selectedAddOns = [];
        updateSelectedAddOnsInputs();
        calculateTotalPrice();
        return;
    }
    
    // إظهار حالة التحميل
    container.innerHTML = `
        <div class="text-center text-blue-500 py-8">
            <i class="fas fa-spinner fa-spin text-4xl mb-2"></i>
            <p>جاري تحميل الإضافات المتاحة...</p>
        </div>
    `;
    
    // استدعاء API للحصول على إضافات المنتج
    fetch(`{{ route('admin.bookings.api.product-add-ons') }}?product_id=${productId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success && data.add_ons && data.add_ons.length > 0) {
                displayProductAddOns(data.add_ons);
            } else {
                container.innerHTML = `
                    <div class="text-center text-gray-500 py-8">
                        <i class="fas fa-info-circle text-4xl mb-2"></i>
                        <p>لا توجد إضافات متاحة لهذا المنتج</p>
                        <p class="text-sm mt-1">يمكنك المتابعة بدون إضافات</p>
                    </div>
                `;
                selectedAddOns = [];
                updateSelectedAddOnsInputs();
                calculateTotalPrice();
            }
        })
        .catch(error => {
            console.error('Error loading product add-ons:', error);
            container.innerHTML = `
                <div class="text-center text-red-500 py-8">
                    <i class="fas fa-exclamation-triangle text-4xl mb-2"></i>
                    <p>حدث خطأ في تحميل الإضافات</p>
                    <button onclick="loadProductAddOns()" class="mt-4 bg-blue-100 hover:bg-blue-200 text-blue-700 px-4 py-2 rounded-lg text-sm transition-colors duration-200">
                        <i class="fas fa-sync-alt"></i>
                        إعادة المحاولة
                    </button>
                </div>
            `;
        });
}

// عرض إضافات المنتج
function displayProductAddOns(addOns) {
    const container = document.getElementById('productAddOnsContainer');
    
    let html = '<div class="space-y-3">';
    html += '<h4 class="font-medium text-gray-900 mb-3">اختر الإضافات المطلوبة:</h4>';
    
    addOns.forEach((addOn, index) => {
        // استخدام index فريد لكل إضافة بدلاً من التحقق من option + name
        const uniqueId = `${addOn.option}_${addOn.name}_${index}`;
        const isSelected = selectedAddOns.some(selected => selected.uniqueId === uniqueId);
        
        html += `
            <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors duration-200 ${isSelected ? 'bg-blue-50 border-blue-300' : 'bg-white'}">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" 
                               id="addon_${index}" 
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                               ${isSelected ? 'checked' : ''}
                               onchange="toggleAddOn(${index}, this.checked)">
                        <label for="addon_${index}" class="cursor-pointer">
                            <div class="font-medium text-gray-900">${addOn.option}</div>
                            <div class="text-sm text-gray-600">${addOn.name}</div>
                        </label>
                    </div>
                    <div class="text-right">
                        <div class="font-semibold text-green-600">${addOn.formatted_price}</div>
                    </div>
                </div>
            </div>
        `;
    });
    
    html += '</div>';
    
    // حفظ الإضافات في متغير global للوصول إليها
    window.availableAddOns = addOns;
    
    container.innerHTML = html;
}

// تبديل حالة الإضافة
function toggleAddOn(index, isChecked) {
    const addOn = window.availableAddOns[index];
    const uniqueId = `${addOn.option}_${addOn.name}_${index}`;
    
    if (isChecked) {
        // إضافة الإضافة للقائمة المحددة (بدون التحقق من التكرار)
        selectedAddOns.push({
            uniqueId: uniqueId,
            option: addOn.option,
            name: addOn.name,
            price: addOn.price,
            image: addOn.image || null
        });
    } else {
        // إزالة الإضافة من القائمة المحددة باستخدام uniqueId
        selectedAddOns = selectedAddOns.filter(selected => selected.uniqueId !== uniqueId);
    }
    
    updateSelectedAddOnsInputs();
    calculateTotalPrice();
}

// تحديث hidden inputs للإضافات المحددة
function updateSelectedAddOnsInputs() {
    const container = document.getElementById('selectedAddOnsInputs');
    
    let html = '';
    selectedAddOns.forEach((addOn, index) => {
        html += `
            <input type="hidden" name="selected_add_ons[${index}][option]" value="${addOn.option}">
            <input type="hidden" name="selected_add_ons[${index}][name]" value="${addOn.name}">
            <input type="hidden" name="selected_add_ons[${index}][price]" value="${addOn.price}">
            <input type="hidden" name="selected_add_ons[${index}][image]" value="${addOn.image || ''}">
        `;
    });
    
    container.innerHTML = html;
}

// التحقق من صحة التاريخ
function validateDate() {
    const dateInput = document.getElementById('booking_date');
    const validationDiv = document.getElementById('dateValidation');
    const serviceProviderId = document.getElementById('service_provider_id').value;
    
    if (!dateInput.value) {
        validationDiv.innerHTML = '';
        return;
    }
    
    const selectedDate = new Date(dateInput.value);
    const today = new Date();
    
    // إزالة الوقت من التاريخ الحالي للمقارنة العادلة
    today.setHours(0, 0, 0, 0);
    selectedDate.setHours(0, 0, 0, 0);
    
    // التحقق من أن التاريخ مستقبلي أو اليوم الحالي
    if (selectedDate < today) {
        validationDiv.innerHTML = '<i class="fas fa-exclamation-triangle text-red-500"></i> <span class="text-red-600">لا يمكن الحجز في تاريخ ماضي</span>';
        return false;
    }
    
    // التحقق من يوم الجمعة
    if (selectedDate.getDay() === 5) {
        validationDiv.innerHTML = '<i class="fas fa-times-circle text-red-500"></i> <span class="text-red-600">لا يمكن الحجز في يوم الجمعة</span>';
        return false;
    }
    
    validationDiv.innerHTML = '<i class="fas fa-check-circle text-green-500"></i> <span class="text-green-600">التاريخ صحيح</span>';
    
    return true;
}

// تحميل الأوقات المتاحة
function loadAvailableTimeSlots() {
    const date = document.getElementById('booking_date').value;
    const serviceProviderId = document.getElementById('service_provider_id').value;
    const container = document.getElementById('availableTimesContainer');
    const validationDiv = document.getElementById('dateValidation');
    
    if (!date || !serviceProviderId) {
        container.innerHTML = `
            <div class="text-center text-gray-500 py-8">
                <i class="fas fa-clock text-4xl mb-2"></i>
                <p>اختر التاريخ ومقدم الخدمة لعرض الأوقات المتاحة</p>
            </div>
        `;
        return;
    }
    
    // إظهار حالة التحميل
    container.innerHTML = `
        <div class="text-center text-blue-500 py-8">
            <i class="fas fa-spinner fa-spin text-4xl mb-2"></i>
            <p>جاري تحميل الأوقات المتاحة...</p>
        </div>
    `;
    
    // استدعاء API للحصول على الأوقات المتاحة
    fetch(`{{ route('admin.bookings.api.available-time-slots') }}?date=${date}&service_provider_id=${serviceProviderId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (!data.is_valid_date) {
                container.innerHTML = `
                    <div class="text-center text-red-500 py-8">
                        <i class="fas fa-calendar-times text-4xl mb-2"></i>
                        <p>${data.message || 'هذا التاريخ غير متاح للحجز'}</p>
                    </div>
                `;
                validationDiv.innerHTML = `<i class="fas fa-times-circle text-red-500"></i> <span class="text-red-600">${data.message || 'هذا التاريخ غير متاح للحجز'}</span>`;
                return;
            }
            
            if (!data.available_slots || data.available_slots.length === 0) {
                container.innerHTML = `
                    <div class="text-center text-yellow-500 py-8">
                        <i class="fas fa-clock text-4xl mb-2"></i>
                        <p>${data.message || 'لا توجد أوقات متاحة في هذا اليوم'}</p>
                    </div>
                `;
                return;
            }
            
            displayAvailableTimeSlots(data.available_slots, true);
            validationDiv.innerHTML = `<i class="fas fa-check-circle text-green-500"></i> <span class="text-green-600">التاريخ متاح للحجز (${data.available_slots.length} وقت متاح)</span>`;
        })
        .catch(error => {
            console.error('Error loading time slots:', error);
            container.innerHTML = `
                <div class="text-center text-red-500 py-8">
                    <i class="fas fa-exclamation-triangle text-4xl mb-2"></i>
                    <p>حدث خطأ في تحميل الأوقات المتاحة</p>
                    <p class="text-sm text-gray-500 mt-2">تحقق من اتصال الإنترنت وأعد المحاولة</p>
                    <button onclick="loadAvailableTimeSlots()" class="mt-4 bg-blue-100 hover:bg-blue-200 text-blue-700 px-4 py-2 rounded-lg text-sm transition-colors duration-200">
                        <i class="fas fa-sync-alt"></i>
                        إعادة المحاولة
                    </button>
                </div>
            `;
            validationDiv.innerHTML = `<i class="fas fa-exclamation-triangle text-red-500"></i> <span class="text-red-600">خطأ في تحميل الأوقات المتاحة</span>`;
        });
}

// عرض الأوقات المتاحة
function displayAvailableTimeSlots(availableSlots, isValidDate) {
    const container = document.getElementById('availableTimesContainer');
    
    if (!isValidDate) {
        container.innerHTML = `
            <div class="text-center text-red-500 py-8">
                <i class="fas fa-calendar-times text-4xl mb-2"></i>
                <p>هذا التاريخ غير متاح للحجز (إجازة أو خارج أوقات العمل)</p>
            </div>
        `;
        return;
    }
    
    if (availableSlots.length === 0) {
        container.innerHTML = `
            <div class="text-center text-yellow-500 py-8">
                <i class="fas fa-clock text-4xl mb-2"></i>
                <p>لا توجد أوقات متاحة في هذا التاريخ</p>
            </div>
        `;
        return;
    }
    
    // تجميع الأوقات حسب الفترة
    const periods = {
        morning: { title: 'الصباح', times: [], color: 'yellow' },
        afternoon: { title: 'بعد الظهر', times: [], color: 'blue' },
        evening: { title: 'المساء', times: [], color: 'purple' }
    };
    
    // تجميع الأوقات حسب الفترة المخزنة في قاعدة البيانات
    availableSlots.forEach(timeSlot => {
        // إذا كان timeSlot كائن يحتوي على period، استخدمه
        if (typeof timeSlot === 'object' && timeSlot.period) {
            const period = timeSlot.period;
            const timeFormatted = formatTime(timeSlot.time);
            const timeValue = timeSlot.time;
            
            if (periods[period]) {
                periods[period].times.push({ value: timeValue, display: timeFormatted + (timeSlot.remaining > 1 ? ` <span class="block text-[10px] opacity-70">${timeSlot.remaining} متاح</span>` : "") });
            }
        } else {
            // fallback للطريقة القديمة (بناءً على الساعة)
            const hour = parseInt(timeSlot.split(':')[0]);
            const timeFormatted = formatTime(timeSlot);
            
            if (hour === 11) {
                periods.morning.times.push({ value: timeSlot, display: timeFormatted });
            } else if (hour >= 13 && hour <= 17) {
                periods.afternoon.times.push({ value: timeSlot, display: timeFormatted });
            } else if (hour >= 19 && hour <= 21) {
                periods.evening.times.push({ value: timeSlot, display: timeFormatted });
            } else {
                // إذا كان الوقت خارج النطاقات المعروفة، ضعه في الفترة المناسبة حسب الساعة
                if (hour < 12) {
                    periods.morning.times.push({ value: timeSlot, display: timeFormatted });
                } else if (hour < 18) {
                    periods.afternoon.times.push({ value: timeSlot, display: timeFormatted });
                } else {
                    periods.evening.times.push({ value: timeSlot, display: timeFormatted });
                }
            }
        }
    });
    
    let html = '<div class="space-y-4">';
    
    Object.entries(periods).forEach(([key, period]) => {
        if (period.times.length > 0) {
            html += `
                <div class="bg-${period.color}-50 border border-${period.color}-200 rounded-lg p-4">
                    <h4 class="font-medium text-${period.color}-800 mb-3">${period.title}</h4>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
            `;
            
            period.times.forEach(timeObj => {
                html += `
                    <button type="button" 
                            class="time-slot-btn p-3 border-2 border-${period.color}-300 rounded-lg text-${period.color}-700 hover:bg-${period.color}-100 transition-colors duration-200"
                            data-time="${timeObj.value}">
                        ${timeObj.display}
                    </button>
                `;
            });
            
            html += '</div></div>';
        }
    });
    
    if (html === '<div class="space-y-4">') {
        html = `
            <div class="text-center text-gray-500 py-8">
                <i class="fas fa-clock text-4xl mb-2"></i>
                <p>لا توجد أوقات متاحة في هذا التاريخ</p>
            </div>
        `;
    } else {
        html += '</div>';
    }
    
    container.innerHTML = html;
    
    // إضافة event listeners للأزرار
    document.querySelectorAll('.time-slot-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            selectTimeSlot(this);
        });
    });
}

// تحديد الوقت المختار
function selectTimeSlot(button) {
    // إزالة التحديد من جميع الأزرار
    document.querySelectorAll('.time-slot-btn').forEach(btn => {
        btn.classList.remove('bg-green-500', 'text-white', 'border-green-500');
        btn.classList.add('border-gray-300');
    });
    
    // تحديد الزر المختار
    button.classList.add('bg-green-500', 'text-white', 'border-green-500');
    button.classList.remove('border-gray-300');
    
    selectedTimeSlot = button.getAttribute('data-time');
    document.getElementById('selectedTime').value = selectedTimeSlot;
}

// تنسيق الوقت
function formatTime(time) {
    const [hour, minute] = time.split(':');
    const hourInt = parseInt(hour);
    const ampm = hourInt >= 12 ? 'م' : 'ص';
    const hour12 = hourInt === 0 ? 12 : hourInt > 12 ? hourInt - 12 : hourInt;
    return `${hour12}:${minute} ${ampm}`;
}

// Event listeners
document.getElementById('product_id').addEventListener('change', function() {
    loadProductAddOns();
    calculateTotalPrice();
});
document.getElementById('booking_date').addEventListener('change', function() {
    validateDate();
    if (this.value && document.getElementById('service_provider_id').value) {
        loadAvailableTimeSlots();
        startAutoRefresh();
    }
});
document.getElementById('service_provider_id').addEventListener('change', function() {
    if (this.value && document.getElementById('booking_date').value) {
        loadAvailableTimeSlots();
        startAutoRefresh();
    }
});

// التحقق من النموذج قبل الإرسال
document.getElementById('bookingForm').addEventListener('submit', function(e) {
    const customerId = document.getElementById('customer_id').value;
    const productId = document.getElementById('product_id').value;
    const serviceProviderId = document.getElementById('service_provider_id').value;
    const bookingDate = document.getElementById('booking_date').value;
    const selectedTime = document.getElementById('selectedTime').value;

    if (!customerId) {
        e.preventDefault();
        alert('يرجى اختيار العميل');
        document.getElementById('customer_id').focus();
        return;
    }

    if (!productId) {
        e.preventDefault();
        alert('يرجى اختيار الخدمة/المنتج');
        document.getElementById('product_id').focus();
        return;
    }

    if (!serviceProviderId) {
        e.preventDefault();
        alert('يرجى اختيار مقدم الخدمة');
        document.getElementById('service_provider_id').focus();
        return;
    }

    if (!bookingDate) {
        e.preventDefault();
        alert('يرجى اختيار تاريخ الحجز');
        document.getElementById('booking_date').focus();
        return;
    }

    if (!selectedTime) {
        e.preventDefault();
        alert('يرجى اختيار وقت الحجز');
        return;
    }

    // لا حاجة للتحقق من الإضافات لأنها اختيارية
    // يمكن للمستخدم إنشاء حجز بدون إضافات

    // تعطيل الزر لمنع الإرسال المتكرر
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';
});

// تحديث تلقائي للأوقات المتاحة كل 30 ثانية
let autoRefreshInterval = null;

function startAutoRefresh() {
    stopAutoRefresh(); // إيقاف أي تحديث سابق
    
    autoRefreshInterval = setInterval(function() {
        const date = document.getElementById('booking_date').value;
        const serviceProviderId = document.getElementById('service_provider_id').value;
        
        if (date && serviceProviderId) {
            // تحديث صامت للأوقات المتاحة
            fetch(`{{ route('admin.bookings.api.available-time-slots') }}?date=${date}&service_provider_id=${serviceProviderId}`)
                .then(response => {
                    if (!response.ok) return;
                    return response.json();
                })
                .then(data => {
                    if (data && data.is_valid_date && data.available_slots) {
                        // تحديث الأوقات المتاحة إذا تغيرت
                        const currentSlots = Array.from(document.querySelectorAll('.time-slot-btn')).map(btn => btn.getAttribute('data-time'));
                        const newSlots = data.available_slots;
                        
                        if (JSON.stringify(currentSlots.sort()) !== JSON.stringify(newSlots.sort())) {
                            displayAvailableTimeSlots(newSlots, true);
                            // إظهار تنبيه بسيط للمستخدم
                            const validationDiv = document.getElementById('dateValidation');
                            validationDiv.innerHTML = `<i class="fas fa-sync text-blue-500"></i> <span class="text-blue-600">تم تحديث الأوقات المتاحة (${newSlots.length} وقت متاح)</span>`;
                            setTimeout(() => {
                                validationDiv.innerHTML = `<i class="fas fa-check-circle text-green-500"></i> <span class="text-green-600">التاريخ متاح للحجز (${newSlots.length} وقت متاح)</span>`;
                            }, 3000);
                        }
                    }
                })
                .catch(error => {
                    // تجاهل الأخطاء في التحديث التلقائي
                    console.log('Auto-refresh error (ignored):', error);
                });
        }
    }, 30000); // كل 30 ثانية
}

function stopAutoRefresh() {
    if (autoRefreshInterval) {
        clearInterval(autoRefreshInterval);
        autoRefreshInterval = null;
    }
}

// إيقاف التحديث عند مغادرة الصفحة
window.addEventListener('beforeunload', stopAutoRefresh);

// التحقق من صلاحية الكوبون
function validateCoupon() {
    const couponCode = document.getElementById('coupon_code').value.trim();
    const totalAmount = calculateTotalAmount();
    const messageDiv = document.getElementById('couponMessage');
    
    if (!couponCode) {
        messageDiv.innerHTML = '<i class="fas fa-exclamation-triangle text-red-500"></i> <span class="text-red-600">يرجى إدخال كود الكوبون</span>';
        return;
    }
    
    // إظهار حالة التحميل
    messageDiv.innerHTML = '<i class="fas fa-spinner fa-spin text-blue-500"></i> <span class="text-blue-600">جاري التحقق من الكوبون...</span>';
    
    // الحصول على CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken) {
        messageDiv.innerHTML = '<i class="fas fa-exclamation-triangle text-red-500"></i> <span class="text-red-600">خطأ في الأمان، يرجى إعادة تحميل الصفحة</span>';
        return;
    }
    
    fetch('{{ route("admin.bookings.validate-coupon") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            coupon_code: couponCode,
            total_amount: totalAmount
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Coupon validation response:', data);
        if (data.success) {
            const discountAmount = parseFloat(data.discount_amount) || 0;
            messageDiv.innerHTML = `<i class="fas fa-check-circle text-green-500"></i> <span class="text-green-600">${data.message} - خصم: ${discountAmount.toFixed(2)} ر.س</span>`;
            // تحديث السعر الإجمالي مع الخصم
            updateTotalPriceWithDiscount(discountAmount);
        } else {
            messageDiv.innerHTML = `<i class="fas fa-times-circle text-red-500"></i> <span class="text-red-600">${data.message}</span>`;
            // إزالة الخصم
            updateTotalPriceWithDiscount(0);
        }
    })
    .catch(error => {
        console.error('Error validating coupon:', error);
        messageDiv.innerHTML = '<i class="fas fa-exclamation-triangle text-red-500"></i> <span class="text-red-600">حدث خطأ أثناء التحقق من الكوبون: ' + error.message + '</span>';
    });
}

// حساب المبلغ الإجمالي بدون خصم
function calculateTotalAmount() {
    const productSelect = document.getElementById('product_id');
    if (!productSelect.value) return 0;
    
    const selectedOption = productSelect.options[productSelect.selectedIndex];
    const productPrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;
    const quantity = 1; // قيمة ثابتة
    
    // حساب مجموع أسعار الإضافات
    let addOnsTotal = 0;
    selectedAddOns.forEach(addOn => {
        addOnsTotal += parseFloat(addOn.price) || 0;
    });
    
    return (productPrice + addOnsTotal) * quantity;
}

// تحديث السعر الإجمالي مع الخصم
function updateTotalPriceWithDiscount(discountAmount) {
    const totalAmount = calculateTotalAmount();
    const finalAmount = totalAmount - discountAmount;
    const totalPriceSpan = document.getElementById('totalPrice');
    
    if (discountAmount > 0) {
        totalPriceSpan.textContent = finalAmount.toFixed(2) + ' ر.س';
        totalPriceSpan.parentElement.className = 'bg-green-50 border border-green-300 rounded-lg px-3 py-2';
        
        // إضافة تفصيل الخصم
        const totalPriceContainer = totalPriceSpan.parentElement;
        const existingBreakdown = totalPriceContainer.querySelector('.discount-breakdown');
        if (existingBreakdown) {
            existingBreakdown.remove();
        }
        
        const breakdown = document.createElement('div');
        breakdown.className = 'discount-breakdown mt-2 text-sm text-gray-600 space-y-1';
        breakdown.innerHTML = `
            <div class="flex justify-between">
                <span>المبلغ الأصلي:</span>
                <span>${totalAmount.toFixed(2)} ر.س</span>
            </div>
            <div class="flex justify-between text-green-600">
                <span>الخصم:</span>
                <span>-${discountAmount.toFixed(2)} ر.س</span>
            </div>
            <div class="border-t pt-1 flex justify-between font-semibold text-green-700">
                <span>المبلغ النهائي:</span>
                <span>${finalAmount.toFixed(2)} ر.س</span>
            </div>
        `;
        totalPriceContainer.appendChild(breakdown);
    } else {
        totalPriceSpan.textContent = totalAmount.toFixed(2) + ' ر.س';
        totalPriceSpan.parentElement.className = 'bg-green-50 border border-green-300 rounded-lg px-3 py-2';
        
        // إزالة تفصيل الخصم
        const totalPriceContainer = totalPriceSpan.parentElement;
        const existingBreakdown = totalPriceContainer.querySelector('.discount-breakdown');
        if (existingBreakdown) {
            existingBreakdown.remove();
        }
    }
}

// تهيئة الحسابات عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    calculateTotalPrice();
});
</script>
@endpush
