@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50" dir="rtl">
    <div class="p-6">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                            <div class="h-12 w-12 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold text-lg">
                                <i class="fas fa-edit"></i>
                            </div>
                            تعديل الفاتورة {{ $invoice->invoice_number }}
                        </h1>
                        <div class="flex items-center gap-4 mt-2">
                            <p class="text-gray-600">تاريخ الإنشاء: {{ $invoice->created_at->format('Y/m/d') }}</p>
                            @php
                                $statusClasses = [
                                    'unpaid' => 'bg-red-100 text-red-800',
                                    'paid' => 'bg-green-100 text-green-800',
                                    'partially_paid' => 'bg-yellow-100 text-yellow-800',
                                    'overdue' => 'bg-orange-100 text-orange-800',
                                    'cancelled' => 'bg-gray-100 text-gray-800',
                                    'refunded' => 'bg-purple-100 text-purple-800'
                                ];
                                
                                $statusNames = [
                                    'unpaid' => 'غير مدفوعة',
                                    'paid' => 'مدفوعة',
                                    'partially_paid' => 'مدفوعة جزئياً',
                                    'overdue' => 'متأخرة',
                                    'cancelled' => 'ملغية',
                                    'refunded' => 'مُسترد'
                                ];
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusClasses[$invoice->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statusNames[$invoice->status] ?? $invoice->status }}
                            </span>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.invoices.show', $invoice) }}" 
                           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-eye"></i>
                            عرض الفاتورة
                        </a>
                        <a href="{{ route('admin.invoices.index') }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-arrow-right"></i>
                            العودة للقائمة
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert if invoice is paid -->
        @if($invoice->status === 'paid')
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                </div>
                <div class="mr-3">
                    <h3 class="text-sm font-medium text-yellow-800">تنبيه</h3>
                    <p class="mt-1 text-sm text-yellow-700">هذه الفاتورة مدفوعة بالكامل. أي تعديل قد يؤثر على حسابات الدفع.</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Form -->
        <form action="{{ route('admin.invoices.update', $invoice) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Customer Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-user text-blue-600"></i>
                        معلومات العميل
                    </h3>
                </div>
                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Customer Selection -->
                        <div class="lg:col-span-2">
                            <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-2">
                                العميل <span class="text-red-500">*</span>
                            </label>
                            <select class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-right" 
                                    id="customer_id" name="customer_id" required onchange="fillCustomerData()">
                                <option value="">اختر العميل</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" 
                                            data-name="{{ $customer->name }}" 
                                            data-phone="{{ $customer->phone }}" 
                                            data-address="{{ $customer->address }}"
                                            {{ old('customer_id', $invoice->customer_id) == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }} - {{ $customer->phone }}
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Customer Name -->
                        <div>
                            <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">
                                اسم العميل <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-right" 
                                   id="customer_name" name="customer_name" value="{{ old('customer_name', $invoice->customer_name) }}" required>
                            @error('customer_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Customer Phone -->
                        <div>
                            <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                رقم الجوال <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-right" 
                                   id="customer_phone" name="customer_phone" value="{{ old('customer_phone', $invoice->customer_phone) }}" required>
                            @error('customer_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Customer Address -->
                        <div class="lg:col-span-2">
                            <label for="customer_address" class="block text-sm font-medium text-gray-700 mb-2">
                                العنوان
                            </label>
                            <textarea class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-right" 
                                      id="customer_address" name="customer_address" rows="3">{{ old('customer_address', $invoice->customer_address) }}</textarea>
                            @error('customer_address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Invoice Details -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-file-invoice text-green-600"></i>
                        تفاصيل الفاتورة
                    </h3>
                </div>
                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                        <!-- Invoice Number (Read Only) -->
                        <div>
                            <label for="invoice_number" class="block text-sm font-medium text-gray-700 mb-2">
                                رقم الفاتورة
                            </label>
                            <input type="text" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100 text-right" 
                                   id="invoice_number" value="{{ $invoice->invoice_number }}" readonly>
                        </div>

                        <!-- Issue Date -->
                        <div>
                            <label for="issue_date" class="block text-sm font-medium text-gray-700 mb-2">
                                تاريخ الإصدار <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-right" 
                                   id="issue_date" name="issue_date" value="{{ old('issue_date', $invoice->issue_date->format('Y-m-d')) }}" required>
                            @error('issue_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Due Date -->
                        <div>
                            <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">
                                تاريخ الاستحقاق
                            </label>
                            <input type="date" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-right" 
                                   id="due_date" name="due_date" value="{{ old('due_date', $invoice->due_date ? $invoice->due_date->format('Y-m-d') : '') }}">
                            @error('due_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Booking Selection -->
                        <div>
                            <label for="booking_id" class="block text-sm font-medium text-gray-700 mb-2">
                                الحجز المرتبط
                            </label>
                            <select class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-right" 
                                    id="booking_id" name="booking_id">
                                <option value="">بدون حجز</option>
                                @foreach($bookings as $booking)
                                    <option value="{{ $booking->id }}" 
                                            {{ old('booking_id', $invoice->booking_id) == $booking->id ? 'selected' : '' }}>
                                        {{ $booking->customer->name }} - {{ $booking->product->name }} ({{ $booking->booking_date->format('Y/m/d') }})
                                    </option>
                                @endforeach
                            </select>
                            @error('booking_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tax Rate -->
                        <div>
                            <label for="tax_rate" class="block text-sm font-medium text-gray-700 mb-2">
                                نسبة الضريبة (%) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" step="0.01" min="0" max="100" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-right" 
                                   id="tax_rate" name="tax_rate" value="{{ old('tax_rate', $invoice->tax_rate) }}" required onchange="calculateTotals()">
                            @error('tax_rate')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Discount Amount -->
                        <div>
                            <label for="discount_amount" class="block text-sm font-medium text-gray-700 mb-2">
                                مبلغ الخصم
                            </label>
                            <input type="number" step="0.01" min="0" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-right" 
                                   id="discount_amount" name="discount_amount" value="{{ old('discount_amount', $invoice->discount_amount ?? 0) }}" onchange="updateDiscountHidden()">
                            @error('discount_amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Invoice Items -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-list text-purple-600"></i>
                            عناصر الفاتورة
                        </h3>
                        <button type="button" onclick="addItem()" 
                                class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-plus"></i>
                            إضافة عنصر
                        </button>
                    </div>
                </div>
                <div class="px-6 py-6">
                    <div id="invoice-items">
                        @foreach($invoice->invoice_items as $index => $item)
                        <div class="item-row grid grid-cols-1 lg:grid-cols-12 gap-4 mb-4 p-4 border border-gray-200 rounded-lg">
                            <div class="lg:col-span-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">اسم المنتج/الخدمة <span class="text-red-500">*</span></label>
                                <input type="text" name="invoice_items[{{ $index }}][name]" value="{{ $item['name'] }}" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-right" required>
                            </div>
                            <div class="lg:col-span-3">
                                <label class="block text-sm font-medium text-gray-700 mb-2">الوصف</label>
                                <input type="text" name="invoice_items[{{ $index }}][description]" value="{{ $item['description'] ?? '' }}" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-right">
                            </div>
                            <div class="lg:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-2">الكمية <span class="text-red-500">*</span></label>
                                <input type="number" min="1" name="invoice_items[{{ $index }}][quantity]" value="{{ $item['quantity'] }}" class="item-quantity block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-right" required onchange="calculateItemTotal(this)">
                            </div>
                            <div class="lg:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">سعر الوحدة <span class="text-red-500">*</span></label>
                                <input type="number" step="0.01" min="0" name="invoice_items[{{ $index }}][unit_price]" value="{{ $item['unit_price'] }}" class="item-price block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-right" required onchange="calculateItemTotal(this)">
                            </div>
                            <div class="lg:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-2">المجموع</label>
                                <input type="number" step="0.01" name="invoice_items[{{ $index }}][total_price]" value="{{ $item['total_price'] }}" class="item-total block w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100 text-right" readonly>
                            </div>
                            @if($index > 0)
                            <div class="lg:col-span-1 flex items-end">
                                <button type="button" onclick="removeItem(this)" class="w-full bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg transition-colors duration-200">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Totals Summary -->
                    <div class="mt-6 flex justify-end">
                        <div class="w-full max-w-md bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-700">المجموع الفرعي:</span>
                                    <span id="subtotal-display" class="font-medium">{{ number_format($invoice->subtotal, 2) }} ر.س</span>
                                </div>
                                @if($invoice->discount_amount > 0)
                                <div class="flex justify-between">
                                    <span class="text-gray-700">الخصم:</span>
                                    <span class="font-medium text-red-600">-{{ number_format($invoice->discount_amount, 2) }} ر.س</span>
                                </div>
                                @endif
                                <div class="flex justify-between">
                                    <span class="text-gray-700">الضريبة:</span>
                                    <span id="tax-display" class="font-medium">{{ number_format($invoice->tax_amount, 2) }} ر.س</span>
                                </div>
                                <div class="flex justify-between border-t pt-2">
                                    <span class="text-lg font-bold text-gray-900">المجموع الإجمالي:</span>
                                    <span id="total-display" class="text-lg font-bold text-gray-900">{{ number_format($invoice->total_amount, 2) }} ر.س</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status and Payment Info -->
            @if($invoice->status !== 'unpaid')
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-info-circle text-blue-600"></i>
                        معلومات الحالة والدفع
                    </h3>
                </div>
                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        @if($invoice->paid_amount > 0)
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <h4 class="font-medium text-green-800 mb-2">المبلغ المدفوع</h4>
                            <p class="text-2xl font-bold text-green-600">{{ number_format($invoice->paid_amount, 2) }} ر.س</p>
                        </div>
                        @endif
                        
                        @if($invoice->remaining_amount > 0)
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <h4 class="font-medium text-red-800 mb-2">المبلغ المتبقي</h4>
                            <p class="text-2xl font-bold text-red-600">{{ number_format($invoice->remaining_amount, 2) }} ر.س</p>
                        </div>
                        @endif
                        
                        @if($invoice->payment_date)
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h4 class="font-medium text-blue-800 mb-2">تاريخ آخر دفعة</h4>
                            <p class="font-semibold text-blue-600">{{ $invoice->payment_date->format('Y/m/d H:i') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Notes -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-sticky-note text-yellow-600"></i>
                        ملاحظات إضافية
                    </h3>
                </div>
                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                ملاحظات
                            </label>
                            <textarea class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-right" 
                                      id="notes" name="notes" rows="4" placeholder="ملاحظات إضافية للفاتورة">{{ old('notes', $invoice->notes) }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Terms and Conditions -->
                        <div>
                            <label for="terms_conditions" class="block text-sm font-medium text-gray-700 mb-2">
                                الشروط والأحكام
                            </label>
                            <textarea class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-right" 
                                      id="terms_conditions" name="terms_conditions" rows="4" placeholder="الشروط والأحكام الخاصة بالفاتورة">{{ old('terms_conditions', $invoice->terms_conditions) }}</textarea>
                            @error('terms_conditions')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hidden Fields for Calculations -->
            <input type="hidden" name="subtotal" id="subtotal-hidden" value="{{ $invoice->subtotal }}">
            <input type="hidden" name="discount_amount" id="discount-hidden" value="{{ $invoice->discount_amount ?? 0 }}">
            
            <!-- Action Buttons -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4">
                    <div class="flex justify-between items-center">
                        <a href="{{ route('admin.invoices.show', $invoice) }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-arrow-right"></i>
                            إلغاء التعديل
                        </a>
                        <div class="flex gap-3">
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center gap-2">
                                <i class="fas fa-save"></i>
                                حفظ التعديلات
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
let itemIndex = {{ count($invoice->invoice_items) }};

function fillCustomerData() {
    const select = document.getElementById('customer_id');
    const selectedOption = select.options[select.selectedIndex];
    
    if (selectedOption.value) {
        document.getElementById('customer_name').value = selectedOption.dataset.name || '';
        document.getElementById('customer_phone').value = selectedOption.dataset.phone || '';
        document.getElementById('customer_address').value = selectedOption.dataset.address || '';
    } else {
        document.getElementById('customer_name').value = '';
        document.getElementById('customer_phone').value = '';
        document.getElementById('customer_address').value = '';
    }
}

function addItem() {
    const container = document.getElementById('invoice-items');
    const newItem = document.createElement('div');
    newItem.className = 'item-row grid grid-cols-1 lg:grid-cols-12 gap-4 mb-4 p-4 border border-gray-200 rounded-lg';
    newItem.innerHTML = `
        <div class="lg:col-span-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">اسم المنتج/الخدمة <span class="text-red-500">*</span></label>
            <input type="text" name="invoice_items[${itemIndex}][name]" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-right" required>
        </div>
        <div class="lg:col-span-3">
            <label class="block text-sm font-medium text-gray-700 mb-2">الوصف</label>
            <input type="text" name="invoice_items[${itemIndex}][description]" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-right">
        </div>
        <div class="lg:col-span-1">
            <label class="block text-sm font-medium text-gray-700 mb-2">الكمية <span class="text-red-500">*</span></label>
            <input type="number" min="1" name="invoice_items[${itemIndex}][quantity]" class="item-quantity block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-right" value="1" required onchange="calculateItemTotal(this)">
        </div>
        <div class="lg:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">سعر الوحدة <span class="text-red-500">*</span></label>
            <input type="number" step="0.01" min="0" name="invoice_items[${itemIndex}][unit_price]" class="item-price block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-right" required onchange="calculateItemTotal(this)">
        </div>
        <div class="lg:col-span-1">
            <label class="block text-sm font-medium text-gray-700 mb-2">المجموع</label>
            <input type="number" step="0.01" name="invoice_items[${itemIndex}][total_price]" class="item-total block w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100 text-right" readonly>
        </div>
        <div class="lg:col-span-1 flex items-end">
            <button type="button" onclick="removeItem(this)" class="w-full bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg transition-colors duration-200">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
    container.appendChild(newItem);
    itemIndex++;
}

function removeItem(button) {
    button.closest('.item-row').remove();
    calculateTotals();
}

function calculateItemTotal(input) {
    const row = input.closest('.item-row');
    const quantity = parseFloat(row.querySelector('.item-quantity').value) || 0;
    const price = parseFloat(row.querySelector('.item-price').value) || 0;
    const total = quantity * price;
    
    row.querySelector('.item-total').value = total.toFixed(2);
    calculateTotals();
}

function calculateTotals() {
    let subtotal = 0;
    
    document.querySelectorAll('.item-total').forEach(function(input) {
        subtotal += parseFloat(input.value) || 0;
    });
    
    const taxRate = parseFloat(document.getElementById('tax_rate').value) || 0;
    const taxAmount = (subtotal * taxRate) / 100;
    const total = subtotal + taxAmount;
    
    // تحديث العرض
    document.getElementById('subtotal-display').textContent = subtotal.toFixed(2) + ' ر.س';
    document.getElementById('tax-display').textContent = taxAmount.toFixed(2) + ' ر.س';
    document.getElementById('total-display').textContent = total.toFixed(2) + ' ر.س';
    
    // تحديث الحقول المخفية
    document.getElementById('subtotal-hidden').value = subtotal.toFixed(2);
}

function updateDiscountHidden() {
    const discountAmount = parseFloat(document.getElementById('discount_amount').value) || 0;
    document.getElementById('discount-hidden').value = discountAmount.toFixed(2);
    calculateTotals(); // إعادة حساب المجاميع عند تغيير الخصم
}

// Initialize calculations when page loads
document.addEventListener('DOMContentLoaded', function() {
    calculateTotals();
});
</script>
@endsection
