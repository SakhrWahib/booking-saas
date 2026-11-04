@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50" dir="rtl">
    <div class="p-6">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">تقويم الحجوزات</h1>
                        <p class="text-gray-600 mt-1">عرض الحجوزات في شكل تقويمي</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.bookings.daily') }}" 
                           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-list"></i>
                            العرض اليومي
                        </a>
                        <a href="{{ route('admin.bookings.create') }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-plus"></i>
                            إضافة حجز جديد
                        </a>
                        <a href="{{ route('admin.bookings.index') }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-table"></i>
                            عرض الجدول
                        </a>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="px-6 py-4">
                <form method="GET" action="{{ route('admin.bookings.calendar') }}" class="flex items-center gap-4">
                    <div>
                        <select name="service_provider_id" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-right">
                            <option value="">جميع مقدمي الخدمة</option>
                            @foreach($serviceProviders as $provider)
                                <option value="{{ $provider->id }}" {{ request('service_provider_id') == $provider->id ? 'selected' : '' }}>
                                    {{ $provider->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                        تطبيق الفلتر
                    </button>
                </form>
            </div>
        </div>

        <!-- Calendar Navigation -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.bookings.calendar', ['month' => $month == 1 ? 12 : $month - 1, 'year' => $month == 1 ? $year - 1 : $year]) }}" 
                       class="bg-gray-100 hover:bg-gray-200 text-gray-700 p-2 rounded-lg transition-colors duration-200">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                    <h2 class="text-xl font-bold text-gray-900">
                        {{ \Carbon\Carbon::create($year, $month, 1)->locale('ar')->translatedFormat('F Y') }}
                    </h2>
                    <a href="{{ route('admin.bookings.calendar', ['month' => $month == 12 ? 1 : $month + 1, 'year' => $month == 12 ? $year + 1 : $year]) }}" 
                       class="bg-gray-100 hover:bg-gray-200 text-gray-700 p-2 rounded-lg transition-colors duration-200">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                </div>
                
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.bookings.calendar') }}" 
                       class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                        الشهر الحالي
                    </a>
                </div>
            </div>
        </div>

        <!-- Calendar -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6">
                @php
                    $startDate = \Carbon\Carbon::create($year, $month, 1);
                    $endDate = $startDate->copy()->endOfMonth();
                    $firstDayOfWeek = $startDate->dayOfWeek;
                    $daysInMonth = $startDate->daysInMonth;
                    
                    // تعديل اليوم الأول للأسبوع ليبدأ من السبت
                    $firstDayOfWeek = ($firstDayOfWeek + 1) % 7;
                @endphp
                
                <!-- Calendar Header -->
                <div class="grid grid-cols-7 gap-2 mb-4">
                    @foreach(['السبت', 'الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة'] as $day)
                        <div class="text-center font-medium text-gray-700 py-2">
                            {{ $day }}
                        </div>
                    @endforeach
                </div>

                <!-- Calendar Body -->
                <div class="grid grid-cols-7 gap-2">
                    <!-- Empty cells for days before the first day of the month -->
                    @for($i = 0; $i < $firstDayOfWeek; $i++)
                        <div class="h-32 bg-gray-50 rounded-lg"></div>
                    @endfor

                    <!-- Days of the month -->
                    @for($day = 1; $day <= $daysInMonth; $day++)
                        @php
                            $currentDate = \Carbon\Carbon::create($year, $month, $day);
                            $dateKey = $currentDate->format('Y-m-d');
                            $dayBookings = $bookings->get($dateKey, collect());
                            $isToday = $currentDate->isToday();
                            $isPast = $currentDate->isPast();
                            $isFriday = $currentDate->isFriday();
                        @endphp
                        
                        <div class="h-32 border rounded-lg p-2 {{ $isToday ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }} {{ $isFriday ? 'bg-red-50' : '' }}">
                            <!-- Day Number -->
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium {{ $isToday ? 'text-blue-600' : ($isPast ? 'text-gray-400' : 'text-gray-900') }}">
                                    {{ $day }}
                                </span>
                                @if($isFriday)
                                    <i class="fas fa-ban text-red-500 text-xs" title="يوم إجازة"></i>
                                @endif
                            </div>

                            <!-- Bookings for this day -->
                            @if($dayBookings->count() > 0)
                                <div class="space-y-1">
                                    @foreach($dayBookings->take(3) as $booking)
                                        @php
                                            $statusColors = [
                                                'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                                'confirmed' => 'bg-blue-100 text-blue-800 border-blue-200',
                                                'completed' => 'bg-green-100 text-green-800 border-green-200',
                                                'cancelled' => 'bg-red-100 text-red-800 border-red-200'
                                            ];
                                        @endphp
                                        <div class="text-xs p-1 rounded border {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}" 
                                             title="{{ $booking->customer->name }} - {{ $booking->product->name }} - انقر لعرض الملف الشخصي">
                                            <div class="flex items-center justify-between">
                                                <a href="{{ route('admin.customers.show', $booking->customer->id) }}" 
                                                   class="truncate hover:text-blue-600 hover:underline cursor-pointer transition-colors duration-200"
                                                   title="انقر لعرض الملف الشخصي لـ {{ $booking->customer->name }}">
                                                    {{ Str::limit($booking->customer->name, 10) }}
                                                </a>
                                                <span>{{ \Carbon\Carbon::parse($booking->booking_time)->format('H:i') }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                    
                                    @if($dayBookings->count() > 3)
                                        <div class="text-xs text-gray-500 text-center">
                                            +{{ $dayBookings->count() - 3 }} أخرى
                                        </div>
                                    @endif
                                </div>
                            @else
                                @if(!$isFriday && !$isPast)
                                    <div class="flex items-center justify-center h-full">
                                        <a href="{{ route('admin.bookings.create', ['date' => $dateKey]) }}" 
                                           class="text-gray-400 hover:text-blue-600 transition-colors duration-200">
                                            <i class="fas fa-plus text-lg"></i>
                                        </a>
                                    </div>
                                @endif
                            @endif

                            <!-- Day click for details -->
                            @if($dayBookings->count() > 0)
                                <a href="{{ route('admin.bookings.daily', ['date' => $dateKey]) }}" 
                                   class="absolute inset-0 rounded-lg hover:bg-black hover:bg-opacity-5 transition-colors duration-200"></a>
                            @endif
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        <!-- Legend -->
        <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">دليل الألوان</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-yellow-100 border border-yellow-200 rounded"></div>
                        <span class="text-sm text-gray-700">قيد الانتظار</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-blue-100 border border-blue-200 rounded"></div>
                        <span class="text-sm text-gray-700">مؤكد</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-green-100 border border-green-200 rounded"></div>
                        <span class="text-sm text-gray-700">مكتمل</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-red-100 border border-red-200 rounded"></div>
                        <span class="text-sm text-gray-700">ملغي</span>
                    </div>
                </div>
                <div class="mt-4 grid grid-cols-2 md:grid-cols-3 gap-4">
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-blue-50 border border-blue-500 rounded"></div>
                        <span class="text-sm text-gray-700">اليوم</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-red-50 border border-red-200 rounded"></div>
                        <span class="text-sm text-gray-700">يوم الجمعة (إجازة)</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-plus text-gray-400"></i>
                        <span class="text-sm text-gray-700">انقر لإضافة حجز</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-user text-blue-500"></i>
                        <span class="text-sm text-gray-700">انقر على اسم العميل لعرض ملفه الشخصي</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// تحديث الصفحة كل دقيقة لإظهار الحجوزات الجديدة
setInterval(function() {
    if (document.visibilityState === 'visible') {
        location.reload();
    }
}, 60000);

// إضافة tooltip للحجوزات
document.addEventListener('DOMContentLoaded', function() {
    const bookingElements = document.querySelectorAll('[title]');
    bookingElements.forEach(element => {
        element.addEventListener('mouseenter', function() {
            // يمكن إضافة tooltip مخصص هنا
        });
    });
    
    // إضافة تأثيرات بصرية لأسماء العملاء
    const customerLinks = document.querySelectorAll('a[href*="/customers/"]');
    customerLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // إضافة تأثير بصري عند النقر
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
        });
        
        // إضافة تأثير hover
        link.addEventListener('mouseenter', function() {
            this.style.textDecoration = 'underline';
        });
        
        link.addEventListener('mouseleave', function() {
            this.style.textDecoration = 'none';
        });
    });
});
</script>
@endpush 