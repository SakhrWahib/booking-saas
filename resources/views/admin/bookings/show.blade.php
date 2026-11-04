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
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            حجز رقم {{ $booking->booking_number }}
                        </h1>
                        <div class="flex items-center gap-4 mt-2">
                            <p class="text-gray-600">تاريخ الإنشاء: {{ $booking->created_at->format('Y/m/d H:i') }}</p>
                            @if($booking->booking_source === 'website')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-globe mr-1"></i>
                                    من الموقع الإلكتروني
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-desktop mr-1"></i>
                                    من لوحة التحكم
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.bookings.edit', $booking) }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-edit"></i>
                            تعديل الحجز
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

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Booking Info & Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-info-circle text-blue-600"></i>
                            معلومات الحجز
                        </h3>
                    </div>
                    <div class="px-6 py-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="flex items-center gap-3">
                                    <div class="bg-blue-100 p-2 rounded-lg">
                                        <i class="fas fa-hashtag text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">رقم الحجز</p>
                                        <p class="font-semibold text-gray-900">{{ $booking->booking_number }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-3">
                                    <div class="bg-green-100 p-2 rounded-lg">
                                        <i class="fas fa-calendar-day text-green-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">التاريخ والوقت</p>
                                        @php
                                            $formattedDateTime = $booking->formatted_date_time;
                                        @endphp
                                        <p class="font-semibold text-gray-900">{{ $formattedDateTime['date'] }}</p>
                                        <p class="text-sm text-gray-600">{{ $formattedDateTime['time'] }}</p>
                                    </div>
                                </div>


                            </div>

                            <div class="space-y-4">
                                <div class="flex items-center gap-3">
                                    <div class="bg-yellow-100 p-2 rounded-lg">
                                        <i class="fas fa-dollar-sign text-yellow-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">الفاتورة الإجمالية</p>
                                        <p class="font-semibold text-green-600">{{ number_format($booking->total_price, 2) }} ر.س</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div class="bg-gray-100 p-2 rounded-lg">
                                        @php
                                            $statusWithColor = $booking->status_with_color;
                                            $statusIcons = [
                                                'yellow' => 'fas fa-clock text-yellow-600',
                                                'blue' => 'fas fa-check-circle text-blue-600',
                                                'green' => 'fas fa-star text-green-600',
                                                'red' => 'fas fa-times-circle text-red-600'
                                            ];
                                        @endphp
                                        <i class="{{ $statusIcons[$statusWithColor['color']] ?? 'fas fa-question-circle text-gray-600' }}"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">حالة الحجز</p>
                                        @php
                                            $statusClasses = [
                                                'yellow' => 'bg-yellow-100 text-yellow-800',
                                                'blue' => 'bg-blue-100 text-blue-800',
                                                'green' => 'bg-green-100 text-green-800',
                                                'red' => 'bg-red-100 text-red-800'
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClasses[$statusWithColor['color']] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $statusWithColor['text'] }}
                                        </span>
                                    </div>
                                </div>

                                <!-- تواريخ الحالة -->
                                @if($booking->confirmed_at || $booking->completed_at || $booking->cancelled_at)
                                <div class="flex items-center gap-3">
                                    <div class="bg-purple-100 p-2 rounded-lg">
                                        <i class="fas fa-history text-purple-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">تاريخ آخر تحديث</p>
                                        @if($booking->cancelled_at)
                                            <p class="font-semibold text-red-600">ألغي في: {{ $booking->cancelled_at->format('Y/m/d H:i') }}</p>
                                        @elseif($booking->completed_at)
                                            <p class="font-semibold text-green-600">أكمل في: {{ $booking->completed_at->format('Y/m/d H:i') }}</p>
                                        @elseif($booking->confirmed_at)
                                            <p class="font-semibold text-blue-600">أكد في: {{ $booking->confirmed_at->format('Y/m/d H:i') }}</p>
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customer Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-user text-green-600"></i>
                            معلومات العميل
                        </h3>
                    </div>
                    <div class="px-6 py-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="h-16 w-16 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold text-xl">
                                {{ substr($booking->customer->name, 0, 1) }}
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900">{{ $booking->customer->name }}</h4>
                                <p class="text-gray-600">رقم العميل: #{{ $booking->customer->id }}</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <i class="fas fa-phone text-green-600"></i>
                                    <span class="text-sm font-medium text-gray-700">رقم الجوال</span>
                                </div>
                                <a href="tel:+966{{ $booking->customer->phone }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                    +966{{ $booking->customer->phone }}
                                </a>
                            </div>
                            
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <i class="fas fa-envelope text-purple-600"></i>
                                    <span class="text-sm font-medium text-gray-700">البريد الإلكتروني</span>
                                </div>
                                @if($booking->customer->email)
                                    <a href="mailto:{{ $booking->customer->email }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $booking->customer->email }}
                                    </a>
                                @else
                                    <span class="text-gray-400">غير محدد</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <a href="{{ route('admin.customers.show', $booking->customer) }}" 
                               class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 inline-flex items-center gap-2">
                                <i class="fas fa-eye"></i>
                                عرض ملف العميل
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Service Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-concierge-bell text-purple-600"></i>
                            تفاصيل الخدمة
                        </h3>
                    </div>
                    <div class="px-6 py-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-2">الخدمة/المنتج</h4>
                                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                                    <div class="flex items-center gap-3 mb-2">
                                        <i class="fas fa-box text-purple-600"></i>
                                        <h5 class="font-medium text-purple-900">{{ $booking->product->name }}</h5>
                                    </div>
                                    <p class="text-sm text-purple-700">{{ $booking->product->description ?? 'لا يوجد وصف' }}</p>
                                    <div class="mt-3 flex justify-between items-center">
                                        <span class="text-sm text-purple-600">سعر الوحدة:</span>
                                        <span class="font-semibold text-purple-900">{{ number_format($booking->product->price, 2) }} ر.س</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-2">مقدم الخدمة</h4>
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                    <div class="flex items-center gap-3 mb-2">
                                        <i class="fas fa-user-tie text-green-600"></i>
                                        <h5 class="font-medium text-green-900">{{ $booking->serviceProvider->name }}</h5>
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-phone text-green-600 text-sm"></i>
                                            <a href="tel:+966{{ $booking->serviceProvider->phone }}" class="text-green-700 hover:text-green-900">
                                                +966{{ $booking->serviceProvider->phone }}
                                            </a>
                                        </div>
                                        @if($booking->serviceProvider->email)
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-envelope text-green-600 text-sm"></i>
                                            <a href="mailto:{{ $booking->serviceProvider->email }}" class="text-green-700 hover:text-green-900">
                                                {{ $booking->serviceProvider->email }}
                                            </a>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Selected Add-ons -->
                @if($booking->hasSelectedAddOns())
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-plus-circle text-indigo-600"></i>
                            الإضافات المحددة
                        </h3>
                    </div>
                    <div class="px-6 py-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($booking->formatted_selected_add_ons as $addOn)
                            <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-medium text-indigo-900">{{ $addOn['option'] }}</h4>
                                        <p class="text-sm text-indigo-700">{{ $addOn['name'] }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="font-semibold text-green-600">{{ $addOn['formatted_price'] }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <div class="flex justify-between items-center">
                                    <span class="font-medium text-green-800">مجموع الإضافات:</span>
                                    <span class="font-bold text-green-600">{{ number_format($booking->getAddOnsTotalPrice(), 2) }} ر.س</span>
                                </div>
                                <div class="flex justify-between items-center mt-2 text-sm">
                                    <span class="text-green-700">سعر المنتج الأساسي:</span>
                                    <span class="text-green-700">{{ number_format($booking->product->price, 2) }} ر.س</span>
                                </div>

                                <div class="border-t border-green-300 mt-2 pt-2 flex justify-between items-center">
                                    <span class="font-semibold text-green-800">المجموع الكلي:</span>
                                    <span class="font-bold text-green-600 text-lg">{{ number_format($booking->total_price, 2) }} ر.س</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Location Information (for website bookings) -->
                @if($booking->booking_source === 'website' && ($booking->customer_latitude || $booking->customer_address))
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-map-marker-alt text-green-600"></i>
                            معلومات الموقع
                        </h3>
                    </div>
                    <div class="px-6 py-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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

                            @if($booking->customer_address)
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <h4 class="font-medium text-green-800 mb-2">العنوان</h4>
                                <p class="text-green-700 text-sm">{{ $booking->customer_address }}</p>
                            </div>
                            @endif
                        </div>

                        @if($booking->location_map_url)
                        <div class="mt-4 bg-purple-50 border border-purple-200 rounded-lg p-4">
                            <h4 class="font-medium text-purple-800 mb-2">رابط الموقع</h4>
                            <a href="{{ $booking->location_map_url }}" target="_blank" 
                               class="text-purple-700 hover:text-purple-900 underline flex items-center gap-2">
                                <i class="fas fa-external-link-alt"></i>
                                فتح الموقع في خرائط Google
                            </a>
                        </div>
                        @endif

                        <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
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

                <!-- Attachments (for website bookings) -->
                @if($booking->booking_source === 'website' && $booking->hasAttachments())
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
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
                                @if(isset($attachment['uploaded_at']))
                                    <p class="text-xs text-gray-500 mt-1">رفع في: {{ $attachment['uploaded_at'] }}</p>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Notes -->
                @if($booking->notes || $booking->admin_notes || $booking->customer_notes)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-sticky-note text-yellow-600"></i>
                            الملاحظات
                        </h3>
                    </div>
                    <div class="px-6 py-6 space-y-4">
                        @if($booking->notes)
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <h4 class="font-medium text-yellow-800 mb-2">ملاحظات عامة</h4>
                            <p class="text-gray-700">{{ $booking->notes }}</p>
                        </div>
                        @endif

                        @if($booking->admin_notes)
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h4 class="font-medium text-blue-800 mb-2">ملاحظات الإدارة</h4>
                            <p class="text-gray-700">{{ $booking->admin_notes }}</p>
                        </div>
                        @endif

                        @if($booking->customer_notes)
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <h4 class="font-medium text-green-800 mb-2">ملاحظات العميل</h4>
                            <p class="text-gray-700">{{ $booking->customer_notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Status Actions -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-tasks text-blue-600"></i>
                            إجراءات الحجز
                        </h3>
                    </div>
                    <div class="px-6 py-6 space-y-3">
                        @if($booking->status == 'pending')
                            <form action="{{ route('admin.bookings.confirm', $booking) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                                    <i class="fas fa-check"></i>
                                    تأكيد الحجز
                                </button>
                            </form>
                        @endif
                        
                        @if(in_array($booking->status, ['confirmed', 'paid']))
                            <form action="{{ route('admin.bookings.complete', $booking) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                                    <i class="fas fa-star"></i>
                                    إكمال الخدمة
                                </button>
                            </form>
                        @endif
                        
                        @if(in_array($booking->status, ['pending', 'confirmed']))
                            <form action="{{ route('admin.bookings.cancel', $booking) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="w-full bg-red-100 hover:bg-red-200 text-red-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2"
                                        onclick="return confirm('هل أنت متأكد من إلغاء هذا الحجز؟')">
                                    <i class="fas fa-times"></i>
                                    إلغاء الحجز
                                </button>
                            </form>
                        @endif
                        
                        <a href="{{ route('admin.bookings.edit', $booking) }}" 
                           class="w-full bg-yellow-100 hover:bg-yellow-200 text-yellow-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-edit"></i>
                            تعديل الحجز
                        </a>
                        
                        @if($booking->invoice)
                        <a href="{{ route('admin.invoices.show', $booking->invoice) }}" 
                           class="w-full bg-purple-100 hover:bg-purple-200 text-purple-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-file-invoice"></i>
                            عرض الفاتورة
                        </a>
                        @endif
                    </div>
                </div>

                <!-- Quick Info -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-info text-gray-600"></i>
                            معلومات سريعة
                        </h3>
                    </div>
                    <div class="px-6 py-6 space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">تاريخ الإنشاء:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $booking->created_at->format('Y/m/d H:i') }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">آخر تحديث:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $booking->updated_at->format('Y/m/d H:i') }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">اليوم:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $booking->booking_date->format('l') }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">الفترة:</span>
                            <span class="text-sm font-medium text-gray-900">
                                @php
                                    $hour = date('H', strtotime($booking->booking_time));
                                    if ($hour == 11) echo 'الصباح';
                                    elseif (in_array($hour, [13, 15, 17])) echo 'بعد الظهر';
                                    elseif (in_array($hour, [19, 21])) echo 'المساء';
                                    else echo 'غير محدد';
                                @endphp
                            </span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">المصدر:</span>
                            <span class="text-sm font-medium text-gray-900">
                                {{ $booking->booking_source === 'website' ? 'الموقع الإلكتروني' : 'لوحة التحكم' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-phone text-green-600"></i>
                            معلومات الاتصال
                        </h3>
                    </div>
                    <div class="px-6 py-6 space-y-3">
                        <a href="tel:+966{{ $booking->customer->phone }}" 
                           class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-phone"></i>
                            اتصال بالعميل
                        </a>
                        
                        <a href="tel:+966{{ $booking->serviceProvider->phone }}" 
                           class="w-full bg-blue-100 hover:bg-blue-200 text-blue-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-phone"></i>
                            اتصال بمقدم الخدمة
                        </a>
                        
                        @if($booking->customer->email)
                        <a href="mailto:{{ $booking->customer->email }}" 
                           class="w-full bg-purple-100 hover:bg-purple-200 text-purple-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-envelope"></i>
                            إرسال بريد للعميل
                        </a>
                        @endif

                        @if($booking->booking_source === 'website' && $booking->location_map_url)
                        <a href="{{ $booking->location_map_url }}" target="_blank"
                           class="w-full bg-orange-100 hover:bg-orange-200 text-orange-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-map-marker-alt"></i>
                            عرض الموقع
                        </a>
                        @endif
                    </div>
                </div>

                <!-- Statistics -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-chart-bar text-indigo-600"></i>
                            إحصائيات
                        </h3>
                    </div>
                    <div class="px-6 py-6 space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">حجوزات العميل:</span>
                            <span class="text-sm font-medium text-blue-600">{{ $booking->customer->bookings()->count() }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">حجوزات المقدم:</span>
                            <span class="text-sm font-medium text-green-600">{{ $booking->serviceProvider->bookings()->count() }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">حجوزات اليوم:</span>
                            <span class="text-sm font-medium text-purple-600">{{ $booking->serviceProvider->bookings()->whereDate('booking_date', today())->count() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Danger Zone -->
                <div class="bg-white rounded-lg shadow-sm border border-red-200">
                    <div class="px-6 py-4 border-b border-red-200">
                        <h3 class="text-lg font-semibold text-red-900 flex items-center gap-2">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                            منطقة الخطر
                        </h3>
                    </div>
                    <div class="px-6 py-6">
                        <button onclick="confirmDelete()" 
                                class="w-full bg-red-100 hover:bg-red-200 text-red-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-trash"></i>
                            حذف الحجز نهائياً
                        </button>
                    </div>
                </div>
            </div>
        </div>
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
                        class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 text-base font-medium rounded-md shadow-sm hover:bg-gray-400">
                    إلغاء
                </button>
                <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="w-full px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700">
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
function confirmDelete() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
@endpush
