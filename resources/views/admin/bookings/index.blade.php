@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50" dir="rtl">
    <div class="p-6">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">إدارة الحجوزات</h1>
                        <p class="text-gray-600 mt-1">عرض وإدارة جميع الحجوزات في النظام</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.bookings.calendar') }}" 
                           class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-calendar"></i>
                            عرض التقويم
                        </a>
                        <a href="{{ route('admin.bookings.create') }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-plus"></i>
                            إضافة حجز جديد
                        </a>
                    </div>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="px-6 py-4">
                <form method="GET" action="{{ route('admin.bookings.index') }}" class="grid grid-cols-1 md:grid-cols-6 gap-4">
                    <div class="md:col-span-2">
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" name="search" 
                                   class="block w-full pr-10 pl-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-right" 
                                   placeholder="البحث برقم الحجز أو اسم العميل" 
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    <div>
                        <select name="status" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-right">
                            <option value="">جميع الحالات</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>مؤكد</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>تم الدفع</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                        </select>
                    </div>
                    <div>
                        <select name="service_provider_id" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-right">
                            <option value="">جميع مقدمي الخدمة</option>
                            @foreach($serviceProviders as $provider)
                                <option value="{{ $provider->id }}" {{ request('service_provider_id') == $provider->id ? 'selected' : '' }}>
                                    {{ $provider->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <select name="booking_source" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-right">
                            <option value="">جميع المصادر</option>
                            <option value="admin_panel" {{ request('booking_source') == 'admin_panel' ? 'selected' : '' }}>لوحة التحكم</option>
                            <option value="website" {{ request('booking_source') == 'website' ? 'selected' : '' }}>الموقع الإلكتروني</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                            بحث
                        </button>
                        <a href="{{ route('admin.bookings.index') }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 text-center">
                            إعادة تعيين
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-7 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">إجمالي الحجوزات</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-calendar-alt text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">قيد الانتظار</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">مؤكد</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $stats['confirmed'] }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-check-circle text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
            

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">مكتمل</p>
                        <p class="text-2xl font-bold text-green-600">{{ $stats['completed'] }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-star text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">ملغي</p>
                        <p class="text-2xl font-bold text-red-600">{{ $stats['cancelled'] }}</p>
                    </div>
                    <div class="bg-red-100 p-3 rounded-full">
                        <i class="fas fa-times-circle text-red-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">حجوزات اليوم</p>
                        <p class="text-2xl font-bold text-purple-600">{{ $stats['today'] }}</p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <i class="fas fa-calendar-day text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">من الموقع</p>
                        <p class="text-2xl font-bold text-indigo-600">{{ $stats['website_bookings'] }}</p>
                    </div>
                    <div class="bg-indigo-100 p-3 rounded-full">
                        <i class="fas fa-globe text-indigo-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">قائمة الحجوزات</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم الحجز</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">العميل</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الخدمة</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">مقدم الخدمة</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التاريخ والوقت</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المصدر</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الفاتورة</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الكوبون</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($bookings as $booking)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $booking->booking_number }}</div>
                                <div class="text-sm text-gray-500">{{ $booking->created_at->format('Y/m/d') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8">
                                        <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold text-sm">
                                            {{ substr($booking->customer->name, 0, 1) }}
                                        </div>
                                    </div>
                                    <div class="mr-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $booking->customer->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $booking->customer->phone }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $booking->product->name }}</div>
                                <div class="text-sm text-gray-500">
                                    @if($booking->hasSelectedAddOns())
                                        عدد: {{ $booking->quantity }} | إضافات: {{ count($booking->selected_add_ons) }}
                                    @else
                                        عدد: {{ $booking->quantity }}
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $booking->serviceProvider->name }}</div>
                                <div class="text-sm text-gray-500">{{ $booking->serviceProvider->phone }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $formattedDateTime = $booking->formatted_date_time;
                                @endphp
                                <div class="text-sm font-medium text-gray-900">{{ $formattedDateTime['date'] }}</div>
                                <div class="text-sm text-gray-500">{{ $formattedDateTime['time'] }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($booking->booking_source === 'website')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-globe mr-1"></i>
                                        موقع إلكتروني
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-desktop mr-1"></i>
                                        لوحة التحكم
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusWithColor = $booking->status_with_color;
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
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-green-600">{{ number_format($booking->total_price, 2) }} <span class="icon-saudi_riyal"></span></span>
                                @if($booking->discount_amount > 0)
                                    <div class="text-xs text-green-500">خصم: {{ number_format($booking->discount_amount, 2) }} ر.س</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($booking->coupon)
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            <i class="fas fa-tag mr-1"></i>
                                            {{ $booking->coupon->code }}
                                        </span>
                                        <div class="text-xs text-gray-500">{{ $booking->coupon->name }}</div>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">لا يوجد</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.bookings.show', $booking) }}" 
                                       class="bg-blue-100 hover:bg-blue-200 text-blue-700 p-2 rounded-lg transition-colors duration-200" 
                                       title="عرض التفاصيل">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if($booking->booking_source === 'website' && ($booking->customer_latitude || $booking->location_map_url))
                                        <button onclick="showLocationModal({{ $booking->id }}, '{{ $booking->customer_latitude }}', '{{ $booking->customer_longitude }}', '{{ $booking->location_map_url }}')" 
                                                class="bg-green-100 hover:bg-green-200 text-green-700 p-2 rounded-lg transition-colors duration-200" 
                                                title="عرض الموقع">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </button>
                                    @endif
                                    
                                    @if($booking->hasSelectedAddOns())
                                        <button onclick="showAddOnsModal({{ $booking->id }}, @json($booking->formatted_selected_add_ons))" 
                                                class="bg-purple-100 hover:bg-purple-200 text-purple-700 p-2 rounded-lg transition-colors duration-200" 
                                                title="عرض الإضافات">
                                            <i class="fas fa-plus-circle"></i>
                                        </button>
                                    @endif
                                    
                                    <a href="{{ route('admin.bookings.edit', $booking) }}" 
                                       class="bg-yellow-100 hover:bg-yellow-200 text-yellow-700 p-2 rounded-lg transition-colors duration-200" 
                                       title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    @if($booking->invoice)
                                        <a href="{{ route('admin.invoices.show', $booking->invoice) }}" 
                                           class="bg-purple-100 hover:bg-purple-200 text-purple-700 p-2 rounded-lg transition-colors duration-200" 
                                           title="عرض الفاتورة">
                                            <i class="fas fa-file-invoice"></i>
                                        </a>
                                    @endif
                                    
                                    @if($booking->status == 'pending')
                                        <form action="{{ route('admin.bookings.confirm', $booking) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="bg-green-100 hover:bg-green-200 text-green-700 p-2 rounded-lg transition-colors duration-200" 
                                                    title="تأكيد">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    @if(in_array($booking->status, ['confirmed', 'paid']))
                                        <form action="{{ route('admin.bookings.complete', $booking) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="bg-purple-100 hover:bg-purple-200 text-purple-700 p-2 rounded-lg transition-colors duration-200" 
                                                    title="إكمال الخدمة">
                                                <i class="fas fa-star"></i>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    @if(in_array($booking->status, ['pending', 'confirmed']))
                                        <form action="{{ route('admin.bookings.cancel', $booking) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="bg-red-100 hover:bg-red-200 text-red-700 p-2 rounded-lg transition-colors duration-200" 
                                                    title="إلغاء"
                                                    onclick="return confirm('هل أنت متأكد من إلغاء هذا الحجز؟')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-calendar-times text-gray-300 text-6xl mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد حجوزات</h3>
                                    <p class="text-gray-500 mb-6">لم يتم العثور على أي حجوزات مطابقة لمعايير البحث</p>
                                    <a href="{{ route('admin.bookings.create') }}" 
                                       class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center gap-2">
                                        <i class="fas fa-plus"></i>
                                        إضافة حجز جديد
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($bookings->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        عرض {{ $bookings->firstItem() }} إلى {{ $bookings->lastItem() }} من {{ $bookings->total() }} نتيجة
                    </div>
                    <div class="flex gap-2">
                        {{ $bookings->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Location Modal -->
<div id="locationModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-4/5 max-w-4xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">موقع العميل</h3>
                <button onclick="closeLocationModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="locationContent" class="space-y-4">
                <!-- سيتم ملء المحتوى بواسطة JavaScript -->
            </div>
        </div>
    </div>
</div>

<!-- Add-ons Modal -->
<div id="addOnsModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-4/5 max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">الإضافات المحددة</h3>
                <button onclick="closeAddOnsModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="addOnsContent" class="space-y-4">
                <!-- سيتم ملء المحتوى بواسطة JavaScript -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showLocationModal(bookingId, latitude, longitude, mapUrl) {
    const modal = document.getElementById('locationModal');
    const content = document.getElementById('locationContent');
    
    let locationHtml = '<div class="text-center text-gray-500">جاري تحميل الموقع...</div>';
    
    if (latitude && longitude) {
        locationHtml = `
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="font-medium text-blue-800 mb-2">إحداثيات الموقع</h4>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-blue-700">خط العرض:</span>
                        <span class="font-mono text-blue-900">${latitude}</span>
                    </div>
                    <div>
                        <span class="text-blue-700">خط الطول:</span>
                        <span class="font-mono text-blue-900">${longitude}</span>
                    </div>
                </div>
            </div>
        `;
        
        if (mapUrl) {
            locationHtml += `
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <h4 class="font-medium text-green-800 mb-2">رابط الموقع</h4>
                    <a href="${mapUrl}" target="_blank" class="text-green-700 hover:text-green-900 underline">
                        <i class="fas fa-external-link-alt mr-1"></i>
                        فتح الموقع في خرائط Google
                    </a>
                </div>
            `;
        }
        
        // إضافة خريطة مبسطة
        locationHtml += `
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <h4 class="font-medium text-gray-800 mb-2">معاينة الموقع</h4>
                <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                    <iframe 
                        src="https://www.google.com/maps/embed/v1/place?key=YOUR_API_KEY&q=${latitude},${longitude}&zoom=15"
                        width="100%" 
                        height="100%" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy">
                    </iframe>
                </div>
            </div>
        `;
    } else {
        locationHtml = '<div class="text-center text-gray-500">لا توجد معلومات موقع متاحة</div>';
    }
    
    content.innerHTML = locationHtml;
    modal.classList.remove('hidden');
}

function closeLocationModal() {
    document.getElementById('locationModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('locationModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeLocationModal();
    }
});

function showAddOnsModal(bookingId, addOns) {
    const modal = document.getElementById('addOnsModal');
    const content = document.getElementById('addOnsContent');
    
    if (!addOns || addOns.length === 0) {
        content.innerHTML = '<div class="text-center text-gray-500">لا توجد إضافات محددة</div>';
        modal.classList.remove('hidden');
        return;
    }
    
    let addOnsHtml = '<div class="grid grid-cols-1 gap-4">';
    let totalAddOnsPrice = 0;
    
    addOns.forEach(addOn => {
        totalAddOnsPrice += parseFloat(addOn.price) || 0;
        
        addOnsHtml += `
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="font-medium text-gray-900">${addOn.option}</h4>
                        <p class="text-sm text-gray-600">${addOn.name}</p>
                    </div>
                    <div class="text-right">
                        <span class="font-semibold text-green-600">${addOn.formatted_price}</span>
                    </div>
                </div>
            </div>
        `;
    });
    
    addOnsHtml += `
        <div class="border-t pt-4 mt-4">
            <div class="flex justify-between items-center font-semibold text-lg">
                <span>مجموع الإضافات:</span>
                <span class="text-green-600">${totalAddOnsPrice.toFixed(2)} <span class="icon-saudi_riyal"></span></span>
            </div>
        </div>
    `;
    
    addOnsHtml += '</div>';
    
    content.innerHTML = addOnsHtml;
    modal.classList.remove('hidden');
}

function closeAddOnsModal() {
    document.getElementById('addOnsModal').classList.add('hidden');
}

// Close add-ons modal when clicking outside
document.getElementById('addOnsModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAddOnsModal();
    }
});
</script>
@endpush
