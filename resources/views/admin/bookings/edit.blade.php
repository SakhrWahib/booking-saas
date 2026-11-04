@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50" dir="rtl">
    <div class="p-6">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">تعديل الحجز</h1>
                        <p class="text-gray-600 mt-1">تعديل الحجز رقم: <span class="font-semibold text-blue-600">{{ $booking->booking_number }}</span></p>
                        <div class="flex items-center gap-4 mt-2">
                            <span class="text-sm text-gray-500">تاريخ الإنشاء: {{ $booking->created_at->format('Y/m/d H:i') }}</span>
                            @if($booking->booking_source === 'website')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-globe mr-1"></i>
                                    من الموقع الإلكتروني
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.bookings.show', $booking) }}" 
                           class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-eye"></i>
                            عرض التفاصيل
                        </a>
                        <a href="{{ route('admin.bookings.index') }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-arrow-right"></i>
                            العودة للقائمة
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('admin.bookings.update', $booking) }}" id="bookingForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
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
                        <!-- معلومات الحجز -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-center gap-4">
                                <div class="h-12 w-12 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold text-lg">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ $booking->booking_number }}</h4>
                                    <div class="flex items-center gap-4 text-sm text-gray-600">
                                        <span>تاريخ الإنشاء: {{ $booking->created_at->format('Y/m/d') }}</span>
                                        @php
                                            $statusWithColor = $booking->status_with_color;
                                            $statusClasses = [
                                                'yellow' => 'bg-yellow-100 text-yellow-800',
                                                'blue' => 'bg-blue-100 text-blue-800',
                                                'green' => 'bg-green-100 text-green-800',
                                                'red' => 'bg-red-100 text-red-800'
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusClasses[$statusWithColor['color']] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $statusWithColor['text'] }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- العميل -->
                        <div>
                            <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-2">
                                العميل <span class="text-red-500">*</span>
                            </label>
                            <select class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right @error('customer_id') border-red-500 @enderror" 
                                    id="customer_id" name="customer_id" required>
                                <option value="">اختر العميل</option>
                                @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id', $booking->customer_id) == $customer->id ? 'selected' : '' }}>
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
                                <option value="{{ $product->id }}" data-price="{{ $product->price }}" {{ old('product_id', $booking->product_id) == $product->id ? 'selected' : '' }}>
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
                                <option value="{{ $provider->id }}" {{ old('service_provider_id', $booking->service_provider_id) == $provider->id ? 'selected' : '' }}>
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
                                    <p>جاري تحميل الإضافات المتاحة...</p>
                                </div>
                            </div>
                            <!-- Hidden inputs للإضافات المحددة -->
                            <div id="selectedAddOnsInputs"></div>
                            @error('selected_add_ons')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- العدد -->
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                العدد <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right @error('quantity') border-red-500 @enderror" 
                                   id="quantity" name="quantity" value="{{ old('quantity', $booking->quantity) }}" 
                                   min="1" required>
                            @error('quantity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- حالة الحجز -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                حالة الحجز <span class="text-red-500">*</span>
                            </label>
                            <select class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right @error('status') border-red-500 @enderror" 
                                    id="status" name="status" required>
                                <option value="pending" {{ old('status', $booking->status) == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                                <option value="confirmed" {{ old('status', $booking->status) == 'confirmed' ? 'selected' : '' }}>مؤكد</option>
                                <option value="paid" {{ old('status', $booking->status) == 'paid' ? 'selected' : '' }}>تم الدفع</option>
                                <option value="completed" {{ old('status', $booking->status) == 'completed' ? 'selected' : '' }}>مكتمل</option>
                                <option value="cancelled" {{ old('status', $booking->status) == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                            </select>
                            @error('status')
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
                                       value="{{ old('coupon_code', $booking->coupon->code ?? '') }}">
                                <button type="button" 
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200"
                                        onclick="validateCoupon()">
                                    <i class="fas fa-check"></i>
                                    تحقق
                                </button>
                            </div>
                            <div id="couponMessage" class="mt-2 text-sm">
                                @if($booking->coupon)
                                    <i class="fas fa-check-circle text-green-500"></i> 
                                    <span class="text-green-600">كوبون مطبق: {{ $booking->coupon->name }} - خصم: {{ number_format($booking->discount_amount ?? 0, 2) }} ر.س</span>
                                @endif
                            </div>
                            @error('coupon_code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- الفاتورة -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                الفاتورة الإجمالية
                            </label>
                            <div class="bg-green-50 border border-green-300 rounded-lg px-3 py-2">
                                <span id="totalPrice" class="text-lg font-semibold text-green-600">{{ number_format($booking->total_price ?? 0, 2) }} ر.س</span>
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
                                   id="booking_date" name="booking_date" value="{{ old('booking_date', $booking->booking_date->format('Y-m-d')) }}" 
                                   required>
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
                                <!-- سيتم تحميل الأوقات بواسطة JavaScript -->
                            </div>
                            <input type="hidden" name="booking_time" id="selectedTime" value="{{ old('booking_time', $booking->booking_time) }}" required>
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

                        <!-- إحصائيات سريعة -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 rounded-lg p-3 text-center">
                                <div class="text-lg font-bold text-blue-600">{{ $booking->customer->bookings()->count() }}</div>
                                <div class="text-xs text-gray-600">حجوزات العميل</div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3 text-center">
                                <div class="text-lg font-bold text-green-600">{{ $booking->serviceProvider->bookings()->count() }}</div>
                                <div class="text-xs text-gray-600">حجوزات المقدم</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- معلومات الموقع (للحجوزات من الموقع الإلكتروني) -->
            @if($booking->booking_source === 'website')
            <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-green-600"></i>
                        معلومات الموقع
                    </h3>
                </div>
                <div class="px-6 py-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- الموقع الجغرافي -->
                        @if($booking->customer_latitude && $booking->customer_longitude)
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h4 class="font-medium text-blue-800 mb-2">الإحداثيات الجغرافية</h4>
                            <div class="space-y-2 text-sm">
                                <div>
                                    <span class="text-blue-700">خط العرض:</span>
                                    <span class="font-mono text-blue-900">{{ $booking->customer_latitude }}</span>
                                </div>
                                <div>
                                    <span class="text-blue-700">خط الطول:</span>
                                    <span class="font-mono text-blue-900">{{ $booking->customer_longitude }}</span>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- العنوان -->
                        @if($booking->customer_address)
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <h4 class="font-medium text-green-800 mb-2">العنوان</h4>
                            <p class="text-green-700 text-sm">{{ $booking->customer_address }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- رابط الخريطة -->
                    @if($booking->location_map_url)
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                        <h4 class="font-medium text-purple-800 mb-2">رابط الموقع</h4>
                        <a href="{{ $booking->location_map_url }}" target="_blank" 
                           class="text-purple-700 hover:text-purple-900 underline flex items-center gap-2">
                            <i class="fas fa-external-link-alt"></i>
                            فتح الموقع في خرائط Google
                        </a>
                    </div>
                    @endif

                    <!-- تأكيد الرياض -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <h4 class="font-medium text-yellow-800 mb-2">تأكيد الموقع</h4>
                        <div class="flex items-center gap-2">
                            @if($booking->is_riyadh_confirmed)
                                <i class="fas fa-check-circle text-green-600"></i>
                                <span class="text-green-700">تم تأكيد أن العميل في الرياض</span>
                            @else
                                <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                                <span class="text-yellow-700">لم يتم تأكيد الموقع في الرياض</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- المرفقات (للحجوزات من الموقع الإلكتروني) -->
            @if($booking->booking_source === 'website' && $booking->hasAttachments())
            <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-paperclip text-purple-600"></i>
                        المرفقات
                    </h3>
                </div>
                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($booking->safe_attachments as $attachment)
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center gap-3 mb-2">
                                @if(isset($attachment['type']) && $attachment['type'] === 'image')
                                    <i class="fas fa-image text-blue-600"></i>
                                @elseif(isset($attachment['type']) && $attachment['type'] === 'pdf')
                                    <i class="fas fa-file-pdf text-red-600"></i>
                                @else
                                    <i class="fas fa-file text-gray-600"></i>
                                @endif
                                <span class="text-sm font-medium text-gray-900">{{ $attachment['name'] ?? 'مرفق غير محدد' }}</span>
                            </div>
                            @if(isset($attachment['path']))
                                <a href="{{ asset('storage/' . $attachment['path']) }}" target="_blank" 
                                   class="text-blue-600 hover:text-blue-800 text-sm underline">
                                    عرض المرفق
                                </a>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- ملاحظات -->
            <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-sticky-note text-yellow-600"></i>
                        الملاحظات
                    </h3>
                </div>
                <div class="px-6 py-6 space-y-4">
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            ملاحظات عامة
                        </label>
                        <textarea class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right @error('notes') border-red-500 @enderror" 
                                  id="notes" name="notes" rows="3" 
                                  placeholder="أضف أي ملاحظات عامة حول الحجز">{{ old('notes', $booking->notes) }}</textarea>
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
                                  placeholder="ملاحظات خاصة بالإدارة">{{ old('admin_notes', $booking->admin_notes) }}</textarea>
                        @error('admin_notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    @if($booking->booking_source === 'website')
                    <div>
                        <label for="customer_notes" class="block text-sm font-medium text-gray-700 mb-2">
                            ملاحظات العميل
                        </label>
                        <textarea class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right bg-gray-50" 
                                  id="customer_notes" name="customer_notes" rows="2" 
                                  placeholder="ملاحظات العميل من الموقع الإلكتروني" readonly>{{ old('customer_notes', $booking->customer_notes) }}</textarea>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200 px-6 py-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        <button type="button" 
                                class="bg-red-100 hover:bg-red-200 text-red-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2" 
                                onclick="confirmDelete()">
                            <i class="fas fa-trash"></i>
                            حذف الحجز
                        </button>
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
                            حفظ التغييرات
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-4">تأكيد حذف الحجز</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">هل أنت متأكد من حذف الحجز <strong>{{ $booking->booking_number }}</strong>؟</p>
                <p class="text-sm text-red-500 mt-2">هذا الإجراء لا يمكن التراجع عنه.</p>
            </div>
            <div class="flex gap-3 px-4 py-3">
                <button onclick="closeDeleteModal()" 
                        class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    إلغاء
                </button>
                <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="w-full px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        حذف نهائياً
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let selectedTimeSlot = '{{ old('booking_time', $booking->booking_time) }}';
let currentBookingId = {{ $booking->id }};
let selectedAddOns = @json(old('selected_add_ons', $booking->selected_add_ons ?? []));
let productPrice = 0;

// حساب السعر الإجمالي
function calculateTotalPrice() {
    const productSelect = document.getElementById('product_id');
    const quantityInput = document.getElementById('quantity');
    const totalPriceSpan = document.getElementById('totalPrice');
    
    if (productSelect.value && quantityInput.value) {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        productPrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        const quantity = parseInt(quantityInput.value) || 1;
        
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
    
    if (addOnsTotal > 0 || quantity > 1) {
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
        
        if (quantity > 1) {
            html += `
                <div class="flex justify-between">
                    <span>العدد:</span>
                    <span>× ${quantity}</span>
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
        const isSelected = selectedAddOns.some(selected => 
            selected.option === addOn.option && selected.name === addOn.name
        );
        
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
    
    if (isChecked) {
        // إضافة الإضافة للقائمة المحددة
        if (!selectedAddOns.some(selected => 
            selected.option === addOn.option && selected.name === addOn.name
        )) {
            selectedAddOns.push({
                option: addOn.option,
                name: addOn.name,
                price: addOn.price,
                image: addOn.image || null
            });
        }
    } else {
        // إزالة الإضافة من القائمة المحددة
        selectedAddOns = selectedAddOns.filter(selected => 
            !(selected.option === addOn.option && selected.name === addOn.name)
        );
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
    
    // التحقق من يوم الجمعة
    if (selectedDate.getDay() === 5) {
        validationDiv.innerHTML = '<i class="fas fa-times-circle text-red-500"></i> <span class="text-red-600">لا يمكن الحجز في يوم الجمعة</span>';
        return false;
    }
    
    validationDiv.innerHTML = '<i class="fas fa-check-circle text-green-500"></i> <span class="text-green-600">التاريخ صحيح</span>';
    
    // تحديث الأوقات المتاحة
    if (serviceProviderId) {
        loadAvailableTimeSlots();
    } else {
        // إذا لم يتم اختيار مقدم الخدمة، إظهار رسالة
        const container = document.getElementById('availableTimesContainer');
        container.innerHTML = `
            <div class="text-center text-gray-500 py-8">
                <i class="fas fa-user text-4xl mb-2"></i>
                <p>اختر مقدم الخدمة لعرض الأوقات المتاحة</p>
            </div>
        `;
    }
    
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
                <p class="text-sm text-gray-500 mt-2">سيتم عرض الأوقات المتاحة تلقائياً</p>
            </div>
        `;
        
        // إزالة رسالة الخطأ من validation div إذا كانت موجودة
        if (validationDiv && validationDiv.innerHTML.includes('خطأ في تحميل الأوقات المتاحة')) {
            validationDiv.innerHTML = '';
        }
        return;
    }
    
    // إظهار حالة التحميل
    container.innerHTML = `
        <div class="text-center text-blue-500 py-8">
            <i class="fas fa-spinner fa-spin text-4xl mb-2"></i>
            <p>جاري تحميل الأوقات المتاحة...</p>
            <p class="text-sm text-blue-400 mt-2">يرجى الانتظار</p>
        </div>
    `;
    
    // استدعاء API للحصول على الأوقات المتاحة مع استثناء الحجز الحالي
    const currentBookingId = {{ $booking->id }};
    const apiUrl = `{{ route('admin.bookings.api.available-time-slots') }}?date=${encodeURIComponent(date)}&service_provider_id=${encodeURIComponent(serviceProviderId)}&exclude_booking_id=${encodeURIComponent(currentBookingId)}`;
    
    fetch(apiUrl)
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
                
                // تحديث validation div
                if (validationDiv) {
                    validationDiv.innerHTML = `<i class="fas fa-times-circle text-red-500"></i> <span class="text-red-600">${data.message || 'هذا التاريخ غير متاح للحجز'}</span>`;
                }
                return;
            }
            
            if (!data.available_slots || data.available_slots.length === 0) {
                container.innerHTML = `
                    <div class="text-center text-yellow-500 py-8">
                        <i class="fas fa-clock text-4xl mb-2"></i>
                        <p>${data.message || 'لا توجد أوقات متاحة في هذا اليوم'}</p>
                    </div>
                `;
                if (validationDiv) {
                    validationDiv.innerHTML = `<i class="fas fa-exclamation-triangle text-yellow-500"></i> <span class="text-yellow-600">لا توجد أوقات متاحة في هذا التاريخ</span>`;
                }
                return;
            }
            
            displayAvailableTimeSlots(data.available_slots, data.is_valid_date);
            
            // إظهار عدد الأوقات المتاحة
            if (validationDiv) {
                validationDiv.innerHTML = `<i class="fas fa-check-circle text-green-500"></i> <span class="text-green-600">التاريخ متاح للحجز (${data.available_slots.length} وقت متاح)</span>`;
            }
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
            
            if (validationDiv) {
                validationDiv.innerHTML = `<i class="fas fa-exclamation-triangle text-red-500"></i> <span class="text-red-600">خطأ في تحميل الأوقات المتاحة</span>`;
            }
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
                <p class="text-sm text-gray-500 mt-2">اختر تاريخاً آخر أو تواصل مع الإدارة</p>
            </div>
        `;
        return;
    }
    
    // إضافة الوقت الحالي للحجز إلى الأوقات المتاحة (للتعديل)
    const currentTime = '{{ $booking->booking_time }}';
    if (currentTime) {
        // التحقق من وجود الوقت في المصفوفة
        const timeExists = availableSlots.some(slot => {
            if (typeof slot === 'string') {
                return slot === currentTime;
            } else if (slot && slot.time) {
                return slot.time === currentTime;
            }
            return false;
        });
        
        if (!timeExists) {
            // إضافة الوقت ككائن متوافق مع باقي البيانات
            availableSlots.push({
                time: currentTime,
                period: 'afternoon', // افتراضي
                label: null
            });
        }
    }
    
    if (availableSlots.length === 0) {
        container.innerHTML = `
            <div class="text-center text-yellow-500 py-8">
                <i class="fas fa-clock text-4xl mb-2"></i>
                <p>لا توجد أوقات متاحة في هذا التاريخ</p>
                <p class="text-sm text-gray-500 mt-2">جرب تاريخاً آخر أو تواصل مع الإدارة</p>
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
                const isSelected = selectedTimeSlot === timeObj.value;
                const buttonClass = isSelected ? 
                    'bg-green-500 text-white border-green-500' : 
                    `border-${period.color}-300 text-${period.color}-700 hover:bg-${period.color}-100`;
                
                html += `
                    <button type="button" 
                            class="time-slot-btn p-3 border-2 rounded-lg transition-colors duration-200 ${buttonClass}"
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

// تأكيد الحذف
function confirmDelete() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Event listeners
document.getElementById('product_id').addEventListener('change', function() {
    loadProductAddOns();
    calculateTotalPrice();
});
document.getElementById('quantity').addEventListener('input', calculateTotalPrice);
document.getElementById('booking_date').addEventListener('change', function() {
    validateDate();
    if (this.value && document.getElementById('service_provider_id').value) {
        loadAvailableTimeSlots();
    }
});
document.getElementById('service_provider_id').addEventListener('change', function() {
    const date = document.getElementById('booking_date').value;
    if (date) {
        loadAvailableTimeSlots();
    } else {
        // إذا لم يتم اختيار التاريخ، إظهار رسالة
        const container = document.getElementById('availableTimesContainer');
        container.innerHTML = `
            <div class="text-center text-gray-500 py-8">
                <i class="fas fa-calendar text-4xl mb-2"></i>
                <p>اختر التاريخ لعرض الأوقات المتاحة</p>
            </div>
        `;
    }
});

// التحقق من النموذج قبل الإرسال
document.getElementById('bookingForm').addEventListener('submit', function(e) {
    const selectedTime = document.getElementById('selectedTime').value;

    if (!selectedTime) {
        e.preventDefault();
        alert('يرجى اختيار وقت الحجز');
        return;
    }

    // تعطيل الزر لمنع الإرسال المتكرر
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';
});

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

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
    const quantityInput = document.getElementById('quantity');
    
    if (!productSelect.value || !quantityInput.value) return 0;
    
    const selectedOption = productSelect.options[productSelect.selectedIndex];
    const productPrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;
    const quantity = parseInt(quantityInput.value) || 1;
    
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

// تهيئة الصفحة
document.addEventListener('DOMContentLoaded', function() {
    calculateTotalPrice();
    loadProductAddOns();
    updateSelectedAddOnsInputs();
    
    // تحميل الأوقات المتاحة فقط إذا كان التاريخ ومقدم الخدمة محددين
    const date = document.getElementById('booking_date').value;
    const serviceProviderId = document.getElementById('service_provider_id').value;
    
    if (date && serviceProviderId) {
        loadAvailableTimeSlots();
    }
});
</script>
@endpush
