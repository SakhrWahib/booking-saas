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
                                <i class="fas fa-file-invoice"></i>
                            </div>
                            فاتورة رقم {{ $invoice->invoice_number }}
                        </h1>
                        <div class="flex items-center gap-4 mt-2">
                            <p class="text-gray-600">تاريخ الإصدار: {{ $invoice->issue_date->format('Y/m/d') }}</p>
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
                        <a href="{{ route('admin.invoices.print', $invoice) }}" 
                           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2" 
                           target="_blank">
                            <i class="fas fa-print"></i>
                            طباعة
                        </a>
                        @if($invoice->status !== 'paid')
                        <a href="{{ route('admin.invoices.edit', $invoice) }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-edit"></i>
                            تعديل الفاتورة
                        </a>
                        @endif
                        @if($invoice->booking)
                        <a href="{{ route('admin.invoices.update-from-booking', $invoice) }}" 
                           class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-sync-alt"></i>
                            تحديث من الحجز
                        </a>
                        @endif
                        <a href="{{ route('admin.invoices.index') }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-arrow-right"></i>
                            العودة للقائمة
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice Content -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-8 py-6">
                <!-- Invoice Header -->
                <div class="flex justify-between items-start mb-8">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-2">فاتورة ضريبية</h2>
                        <div class="space-y-1 text-sm text-gray-600">
                            <p><span class="font-medium">رقم الفاتورة:</span> {{ $invoice->invoice_number }}</p>
                            <p><span class="font-medium">تاريخ الإصدار:</span> {{ $invoice->issue_date->format('Y/m/d') }}</p>
                            @if($invoice->due_date)
                                <p><span class="font-medium">تاريخ الاستحقاق:</span> {{ $invoice->due_date->format('Y/m/d') }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="text-left">
                        <h3 class="text-2xl font-bold text-blue-600 mb-2">{{ config('brand.name') }}</h3>
                        <div class="text-sm text-gray-600 space-y-1">
                            <p>نظام حجوزات {{ config('brand.name') }}</p>
                            <p>الرياض، المملكة العربية السعودية</p>
                            <p>الرقم الضريبي: 123456789</p>
                        </div>
                    </div>
                </div>

                <!-- Customer & Creator Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <!-- Customer Info -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                        <h4 class="font-bold text-blue-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-user"></i>
                            معلومات العميل
                        </h4>
                        <div class="space-y-2 text-sm">
                            <p><span class="font-medium text-blue-800">الاسم:</span> {{ $invoice->customer_name }}</p>
                            <p><span class="font-medium text-blue-800">رقم الجوال:</span> {{ $invoice->customer_phone }}</p>
                            @if($invoice->customer_address)
                                <p><span class="font-medium text-blue-800">العنوان:</span> {{ $invoice->customer_address }}</p>
                            @endif
                            @if($invoice->customer && $invoice->customer->email)
                                <p><span class="font-medium text-blue-800">البريد الإلكتروني:</span> {{ $invoice->customer->email }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Creator Info -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                        <h4 class="font-bold text-green-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-user-tie"></i>
                            أُنشئت بواسطة
                        </h4>
                        <div class="space-y-2 text-sm">
                            <p><span class="font-medium text-green-800">الاسم:</span> {{ $invoice->creator->name ?? 'غير محدد' }}</p>
                            @if($invoice->creator && $invoice->creator->email)
                                <p><span class="font-medium text-green-800">البريد الإلكتروني:</span> {{ $invoice->creator->email }}</p>
                            @endif
                            <p><span class="font-medium text-green-800">تاريخ الإنشاء:</span> {{ $invoice->created_at->format('Y/m/d H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Booking Info (if exists) -->
                @if($invoice->booking)
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-6 mb-8">
                    <h4 class="font-bold text-purple-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-calendar-alt"></i>
                        معلومات الحجز المرتبط
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <p><span class="font-medium text-purple-800">رقم الحجز:</span> {{ $invoice->booking->booking_number }}</p>
                        </div>
                        <div>
                            <p><span class="font-medium text-purple-800">تاريخ الحجز:</span> {{ $invoice->booking->booking_date->format('Y/m/d') }}</p>
                        </div>
                        <div>
                            <p><span class="font-medium text-purple-800">وقت الحجز:</span> {{ $invoice->booking->booking_time }}</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.bookings.show', $invoice->booking) }}" 
                           class="text-purple-700 hover:text-purple-900 underline text-sm">
                            عرض تفاصيل الحجز
                        </a>
                    </div>
                </div>
                @endif

                <!-- Invoice Items -->
                <div class="mb-8">
                    <h4 class="font-bold text-gray-900 mb-4 text-lg">تفاصيل المنتجات والخدمات</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-300">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-3 text-right text-sm font-medium text-gray-900 border-b border-gray-300">تفاصيل المنتج</th>
                                    <th class="px-4 py-3 text-center text-sm font-medium text-gray-900 border-b border-gray-300">سعر الوحدة</th>
                                    <th class="px-4 py-3 text-center text-sm font-medium text-gray-900 border-b border-gray-300">الكمية</th>
                                    <th class="px-4 py-3 text-center text-sm font-medium text-gray-900 border-b border-gray-300">المجموع</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoice->invoice_items as $item)
                                <tr class="border-b border-gray-200">
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-gray-900">{{ $item['name'] }}</div>
                                        @if(isset($item['description']) && $item['description'])
                                            <div class="text-sm text-gray-600">{{ $item['description'] }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center">{{ number_format($item['unit_price'], 2) }} ر.س</td>
                                    <td class="px-4 py-3 text-center">{{ $item['quantity'] }}</td>
                                    <td class="px-4 py-3 text-center font-medium">{{ number_format($item['total_price'], 2) }} ر.س</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Invoice Totals -->
                <div class="flex justify-end mb-8">
                    <div class="w-full max-w-md">
                        <div class="bg-gray-50 border border-gray-300 rounded-lg p-6">
                            <h4 class="font-bold text-gray-900 mb-4 text-lg">ملخص الفاتورة</h4>
                            
                                                         @php
                                 $taxRate = $invoice->tax_rate / 100;
                                 $priceIncludingTax = $invoice->subtotal;
                                 $taxAmount = ($priceIncludingTax * $taxRate) / (1 + $taxRate);
                                 $priceBeforeTax = $priceIncludingTax - $taxAmount;
                                 $priceAfterDiscount = $priceIncludingTax - $invoice->discount_amount;
                             @endphp
                            
                            <!-- السعر قبل الضريبة -->
                            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                <span class="text-gray-700">المجموع الفرعي (قبل الضريبة):</span>
                                <span class="font-medium">{{ number_format($priceBeforeTax, 2) }} ر.س</span>
                            </div>

                            <!-- الخصم -->
                            @if($invoice->discount_amount > 0)
                            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                <span class="text-gray-700">الخصم:</span>
                                <span class="font-medium text-red-600">-{{ number_format($invoice->discount_amount, 2) }} ر.س</span>
                            </div>
                            
                                                         <!-- السعر بعد الخصم -->
                             <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                 <span class="text-gray-700">السعر بعد الخصم:</span>
                                 <span class="font-medium">{{ number_format($priceAfterDiscount, 2) }} ر.س</span>
                             </div>
                            @endif

                            <!-- نسبة الضريبة -->
                            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                <span class="text-gray-700">نسبة الضريبة:</span>
                                <span class="font-medium">{{ number_format($invoice->tax_rate, 1) }}%</span>
                            </div>

                                                         <!-- قيمة الضريبة -->
                             <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                 <span class="text-gray-700">قيمة الضريبة:</span>
                                 <span class="font-medium">{{ number_format($invoice->tax_amount, 2) }} ر.س</span>
                             </div>

                            <!-- المجموع شامل الضريبة -->
                            <div class="flex justify-between items-center py-3 mt-3 bg-blue-100 rounded-lg px-4">
                                <span class="text-lg font-bold text-blue-900">المجموع شامل الضريبة:</span>
                                <span class="text-xl font-bold text-blue-900">{{ number_format($invoice->total_amount, 2) }} ر.س</span>
                            </div>

                            <!-- المبلغ المدفوع والمتبقي -->
                            @if($invoice->paid_amount > 0)
                            <div class="mt-4 space-y-2">
                                <div class="flex justify-between items-center py-2 bg-green-100 rounded-lg px-4">
                                    <span class="font-medium text-green-800">المبلغ المدفوع:</span>
                                    <span class="font-bold text-green-800">{{ number_format($invoice->paid_amount, 2) }} ر.س</span>
                                </div>
                                
                                @if($invoice->remaining_amount > 0)
                                <div class="flex justify-between items-center py-2 bg-red-100 rounded-lg px-4">
                                    <span class="font-medium text-red-800">المبلغ المتبقي:</span>
                                    <span class="font-bold text-red-800">{{ number_format($invoice->remaining_amount, 2) }} ر.س</span>
                                </div>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Payment Info -->
                @if($invoice->payment_date)
                <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-8">
                    <h4 class="font-bold text-green-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-credit-card"></i>
                        معلومات الدفع
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <p><span class="font-medium text-green-800">تاريخ الدفع:</span> {{ $invoice->payment_date->format('Y/m/d H:i') }}</p>
                        </div>
                        @if($invoice->payment_method)
                            <div>
                                <p><span class="font-medium text-green-800">طريقة الدفع:</span> 
                                    @switch($invoice->payment_method)
                                        @case('cash')
                                            نقدي
                                            @break
                                        @case('bank_transfer')
                                            تحويل بنكي
                                            @break
                                        @case('credit_card')
                                            بطاقة ائتمان
                                            @break
                                        @case('digital_wallet')
                                            محفظة رقمية
                                            @break
                                        @default
                                            {{ $invoice->payment_method }}
                                    @endswitch
                                </p>
                            </div>
                        @endif
                    </div>
                    
                    @if($invoice->payment_details && isset($invoice->payment_details['reference_number']))
                        <div class="mt-3">
                            <p class="text-sm"><span class="font-medium text-green-800">رقم المرجع:</span> {{ $invoice->payment_details['reference_number'] }}</p>
                        </div>
                    @endif
                </div>
                @endif

                <!-- Notes -->
                @if($invoice->notes || $invoice->terms_conditions)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($invoice->notes)
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                        <h4 class="font-bold text-yellow-900 mb-3 flex items-center gap-2">
                            <i class="fas fa-sticky-note"></i>
                            ملاحظات
                        </h4>
                        <p class="text-sm text-gray-700">{{ $invoice->notes }}</p>
                    </div>
                    @endif

                    @if($invoice->terms_conditions)
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                        <h4 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
                            <i class="fas fa-file-contract"></i>
                            الشروط والأحكام
                        </h4>
                        <p class="text-sm text-gray-700">{{ $invoice->terms_conditions }}</p>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>

        <!-- Action Panel (only for unpaid/partially paid invoices) -->
        @if(!in_array($invoice->status, ['paid', 'cancelled', 'refunded']))
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Record Payment -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-money-bill text-green-600"></i>
                        تسجيل دفعة
                    </h3>
                </div>
                <div class="px-6 py-6">
                    <form action="{{ route('admin.invoices.record-payment', $invoice) }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                                    المبلغ <span class="text-red-500">*</span>
                                    <span class="text-xs text-gray-500">(الحد الأقصى: {{ number_format($invoice->remaining_amount, 2) }} ر.س)</span>
                                </label>
                                <input type="number" step="0.01" max="{{ $invoice->remaining_amount }}" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-right" 
                                       id="amount" name="amount" value="{{ $invoice->remaining_amount }}" required>
                            </div>

                            <div>
                                <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">
                                    طريقة الدفع <span class="text-red-500">*</span>
                                </label>
                                <select class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-right" 
                                        id="payment_method" name="payment_method" required>
                                    <option value="">اختر طريقة الدفع</option>
                                    <option value="cash">نقدي</option>
                                    <option value="bank_transfer">تحويل بنكي</option>
                                    <option value="credit_card">بطاقة ائتمان</option>
                                    <option value="digital_wallet">محفظة رقمية</option>
                                    <option value="other">أخرى</option>
                                </select>
                            </div>

                            <div>
                                <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    تاريخ الدفع <span class="text-red-500">*</span>
                                </label>
                                <input type="datetime-local" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-right" 
                                       id="payment_date" name="payment_date" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                            </div>

                            <div>
                                <label for="reference_number" class="block text-sm font-medium text-gray-700 mb-2">
                                    رقم المرجع
                                </label>
                                <input type="text" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-right" 
                                       id="reference_number" name="reference_number" placeholder="رقم المرجع أو الإيصال">
                            </div>

                            <div>
                                <label for="payment_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                    ملاحظات الدفع
                                </label>
                                <textarea class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-right" 
                                          id="payment_notes" name="notes" rows="2" placeholder="ملاحظات إضافية حول الدفعة"></textarea>
                            </div>

                            <button type="submit" 
                                    class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                                <i class="fas fa-check"></i>
                                تسجيل الدفعة
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Apply Discount -->
            @if($invoice->status === 'unpaid')
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-percentage text-orange-600"></i>
                        تطبيق خصم
                    </h3>
                </div>
                <div class="px-6 py-6">
                    <form action="{{ route('admin.invoices.apply-discount', $invoice) }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="discount_amount" class="block text-sm font-medium text-gray-700 mb-2">
                                    مبلغ الخصم <span class="text-red-500">*</span>
                                    <span class="text-xs text-gray-500">(الحد الأقصى: {{ number_format($invoice->subtotal, 2) }} ر.س)</span>
                                </label>
                                <input type="number" step="0.01" max="{{ $invoice->subtotal }}" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500 text-right" 
                                       id="discount_amount" name="discount_amount" value="{{ $invoice->discount_amount }}" required>
                            </div>

                            <div>
                                <label for="discount_reason" class="block text-sm font-medium text-gray-700 mb-2">
                                    سبب الخصم
                                </label>
                                <textarea class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500 text-right" 
                                          id="discount_reason" name="discount_reason" rows="2" placeholder="سبب تطبيق الخصم"></textarea>
                            </div>

                            <button type="submit" 
                                    class="w-full bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                                <i class="fas fa-percentage"></i>
                                تطبيق الخصم
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection
