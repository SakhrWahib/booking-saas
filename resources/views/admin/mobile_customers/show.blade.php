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
                                {{ strtoupper(substr($customer->name, 0, 1)) }}
                            </div>
                            {{ $customer->name }}
                        </h1>
                        <p class="text-gray-600 mt-1">العميل رقم #{{ $customer->id }} - تاريخ التسجيل: {{ $customer->created_at->format('Y/m/d') }}</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.customers.edit', $customer) }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-edit"></i>
                            تعديل البيانات
                        </a>
                        <a href="{{ route('admin.mobile-customers.index') }}" 
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
            <!-- Customer Info & Statistics -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-user text-blue-600"></i>
                            المعلومات الأساسية
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
                                        <p class="text-sm text-gray-600">رقم العميل</p>
                                        <p class="font-semibold text-gray-900">#{{ $customer->id }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-3">
                                    <div class="bg-green-100 p-2 rounded-lg">
                                        <i class="fas fa-phone text-green-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">رقم الجوال</p>
                                        <a href="tel:+966{{ $customer->phone }}" class="font-semibold text-blue-600 hover:text-blue-800">
                                            +966{{ $customer->phone }}
                                        </a>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div class="bg-purple-100 p-2 rounded-lg">
                                        <i class="fas fa-envelope text-purple-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">البريد الإلكتروني</p>
                                        @if($customer->email)
                                            <a href="mailto:{{ $customer->email }}" class="font-semibold text-blue-600 hover:text-blue-800">
                                                {{ $customer->email }}
                                            </a>
                                        @else
                                            <p class="font-semibold text-gray-400">غير محدد</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="flex items-center gap-3">
                                    <div class="bg-indigo-100 p-2 rounded-lg">
                                        <i class="fas fa-user-tag text-indigo-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">نوع العميل</p>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ ucfirst($customer->customer_type) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div class="bg-orange-100 p-2 rounded-lg">
                                        <i class="fas fa-calendar text-orange-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">تاريخ التسجيل</p>
                                        <p class="font-semibold text-gray-900">{{ $customer->created_at->format('Y/m/d') }}</p>
                                    </div>
                                </div>

                                @if($customer->address)
                                <div class="flex items-center gap-3">
                                    <div class="bg-yellow-100 p-2 rounded-lg">
                                        <i class="fas fa-map-marker-alt text-yellow-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">العنوان</p>
                                        <p class="font-semibold text-gray-900">{{ $customer->address }}</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        @if($customer->latitude && $customer->longitude)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="flex items-center gap-3">
                                <div class="bg-green-100 p-2 rounded-lg">
                                    <i class="fas fa-location-arrow text-green-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">الموقع الجغرافي</p>
                                    <p class="font-semibold text-gray-900">
                                        خط العرض: {{ $customer->latitude }}<br>
                                        خط الطول: {{ $customer->longitude }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Mobile Login Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-mobile-alt text-green-600"></i>
                            معلومات الدخول عبر الجوال
                        </h3>
                    </div>
                    <div class="px-6 py-6">
                        @if($customer->hasMobileLogin())
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div class="flex items-center gap-3">
                                        <div class="bg-green-100 p-2 rounded-lg">
                                            <i class="fas fa-mobile-alt text-green-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600">نوع الجهاز</p>
                                            <div class="mt-2">
                                                @if($customer->device_type == 'android')
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                        <i class="fab fa-android mr-2"></i> Android
                                                    </span>
                                                @elseif($customer->device_type == 'ios')
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-info-100 text-info-800">
                                                        <i class="fab fa-apple mr-2"></i> iOS
                                                    </span>
                                                @elseif($customer->device_type == 'web')
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                                        <i class="fas fa-globe mr-2"></i> Web
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                                        غير محدد
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-3">
                                        <div class="bg-blue-100 p-2 rounded-lg">
                                            <i class="fas fa-clock text-blue-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600">آخر دخول</p>
                                            <p class="font-semibold text-gray-900">
                                                @if($customer->last_mobile_login)
                                                    {{ \Carbon\Carbon::parse($customer->last_mobile_login)->format('Y/m/d H:i:s') }}
                                                @else
                                                    غير محدد
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <div class="flex items-center gap-3">
                                        <div class="bg-purple-100 p-2 rounded-lg">
                                            <i class="fas fa-calendar-day text-purple-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600">عدد الأيام منذ آخر دخول</p>
                                            <div class="mt-2">
                                                @php
                                                    $days = $customer->getDaysSinceLastMobileLogin();
                                                @endphp
                                                @if($days == 0)
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                        اليوم
                                                    </span>
                                                @elseif($days == 1)
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-info-100 text-info-800">
                                                        أمس
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                                        {{ $days }} يوم
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-3">
                                        <div class="bg-orange-100 p-2 rounded-lg">
                                            <i class="fas fa-user-check text-orange-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600">حالة الحساب</p>
                                            <div class="mt-2">
                                                @if($customer->isActive())
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                        نشط
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                        غير نشط
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <div class="flex items-start gap-3">
                                        <i class="fas fa-info-circle text-blue-600 mt-1"></i>
                                        <div>
                                            <p class="text-sm font-medium text-blue-900">
                                                @if($customer->last_mobile_login)
                                                    هذا العميل دخل إلى التطبيق عبر الجوال آخر مرة في {{ \Carbon\Carbon::parse($customer->last_mobile_login)->diffForHumans() }}
                                                @else
                                                    هذا العميل لم يدخل إلى التطبيق عبر الجوال بعد
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="bg-gray-100 rounded-full p-6 w-20 h-20 mx-auto mb-4 flex items-center justify-center">
                                    <i class="fas fa-mobile-alt text-gray-400 text-2xl"></i>
                                </div>
                                <h4 class="text-lg font-medium text-gray-900 mb-2">لم يدخل هذا العميل عبر الجوال بعد</h4>
                                <p class="text-gray-500">سيتم تحديث هذه الصفحة عند دخول العميل عبر التطبيق</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Bookings -->
                @if($recentBookings->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-calendar-check text-purple-600"></i>
                            آخر الحجوزات
                        </h3>
                    </div>
                    <div class="px-6 py-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الخدمة</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">مزود الخدمة</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التاريخ</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($recentBookings as $booking)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($booking->product)
                                                <span class="text-sm font-medium text-gray-900">{{ $booking->product->name }}</span>
                                            @else
                                                <span class="text-sm text-gray-400">غير محدد</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($booking->serviceProvider)
                                                <span class="text-sm font-medium text-gray-900">{{ $booking->serviceProvider->name }}</span>
                                            @else
                                                <span class="text-sm text-gray-400">غير محدد</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $booking->created_at->format('Y/m/d') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @switch($booking->status)
                                                @case('pending')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        في الانتظار
                                                    </span>
                                                    @break
                                                @case('confirmed')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        مؤكد
                                                    </span>
                                                    @break
                                                @case('completed')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        مكتمل
                                                    </span>
                                                    @break
                                                @case('cancelled')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        ملغي
                                                    </span>
                                                    @break
                                                @default
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        {{ $booking->status }}
                                                    </span>
                                            @endswitch
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Stats -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-chart-pie text-purple-600"></i>
                            إحصائيات سريعة
                        </h3>
                    </div>
                    <div class="px-6 py-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">إجمالي الحجوزات</span>
                            <span class="text-lg font-bold text-gray-900">{{ $customer->bookings()->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">الحجوزات النشطة</span>
                            <span class="text-lg font-bold text-green-600">{{ $customer->activeBookings()->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">الحجوزات المكتملة</span>
                            <span class="text-lg font-bold text-blue-600">{{ $customer->bookings()->where('status', 'completed')->count() }}</span>
                        </div>
                        @if($customer->hasMobileLogin())
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">آخر دخول</span>
                            <span class="text-sm font-medium text-gray-900">
                                {{ \Carbon\Carbon::parse($customer->last_mobile_login)->diffForHumans() }}
                            </span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-tools text-orange-600"></i>
                            الإجراءات
                        </h3>
                    </div>
                    <div class="px-6 py-6 space-y-3">
                        <a href="{{ route('admin.customers.edit', $customer) }}" 
                           class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-edit"></i>
                            تعديل العميل
                        </a>
                        <a href="{{ route('admin.bookings.create') }}?customer_id={{ $customer->id }}" 
                           class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-plus"></i>
                            إضافة حجز
                        </a>
                        <a href="{{ route('admin.customers.index') }}" 
                           class="w-full bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-arrow-right"></i>
                            العودة للقائمة
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
