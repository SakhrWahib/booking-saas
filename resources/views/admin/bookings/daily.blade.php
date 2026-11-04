@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50" dir="rtl">
    <div class="p-6">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">الحجوزات اليومية</h1>
                        <p class="text-gray-600 mt-1">عرض حجوزات يوم {{ \Carbon\Carbon::parse($date)->locale('ar')->translatedFormat('l، j F Y') }}</p>
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
                        <a href="{{ route('admin.bookings.index') }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-table"></i>
                            عرض الجدول
                        </a>
                    </div>
                </div>
            </div>

            <!-- Date Navigation and Filters -->
            <div class="px-6 py-4">
                <div class="flex justify-between items-center">
                    <!-- Date Navigation -->
                    <div class="flex items-center gap-4">
                        <a href="{{ route('admin.bookings.daily', ['date' => \Carbon\Carbon::parse($date)->subDay()->format('Y-m-d')]) }}" 
                           class="bg-gray-100 hover:bg-gray-200 text-gray-700 p-2 rounded-lg transition-colors duration-200">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                        
                        <div class="flex items-center gap-2">
                            <i class="fas fa-calendar-day text-blue-600"></i>
                            <span class="text-lg font-semibold text-gray-900">
                                {{ \Carbon\Carbon::parse($date)->locale('ar')->translatedFormat('l، j F Y') }}
                            </span>
                        </div>
                        
                        <a href="{{ route('admin.bookings.daily', ['date' => \Carbon\Carbon::parse($date)->addDay()->format('Y-m-d')]) }}" 
                           class="bg-gray-100 hover:bg-gray-200 text-gray-700 p-2 rounded-lg transition-colors duration-200">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                        
                        <a href="{{ route('admin.bookings.daily') }}" 
                           class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                            اليوم
                        </a>
                    </div>

                    <!-- Filters -->
                    <form method="GET" action="{{ route('admin.bookings.daily') }}" class="flex items-center gap-4">
                        <input type="hidden" name="date" value="{{ $date }}">
                        <div>
                            <select name="service_provider_id" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-right">
                                <option value="">جميع مقدمي الخدمة</option>
                                @foreach($serviceProviders as $provider)
                                    <option value="{{ $provider->id }}" {{ $serviceProviderId == $provider->id ? 'selected' : '' }}>
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
        </div>

        <!-- Statistics for the day -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">إجمالي الحجوزات</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $bookings->count() }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-calendar-alt text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">مؤكدة</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $bookings->where('status', 'confirmed')->count() }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-check-circle text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">مكتملة</p>
                        <p class="text-2xl font-bold text-green-600">{{ $bookings->where('status', 'completed')->count() }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-star text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">قيد الانتظار</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $bookings->where('status', 'pending')->count() }}</p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Time Slots -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">الأوقات المتاحة</h3>
            </div>
            
            <div class="p-6">
                @php
                    $timeSlots = ['11:00', '13:00', '15:00', '17:00', '19:00', '21:00'];
                    $groupedBookings = $bookings->groupBy('booking_time');
                @endphp
                
                <div class="space-y-6">
                    @foreach($timeSlots as $timeSlot)
                        @php
                            $slotBookings = $groupedBookings->get($timeSlot, collect());
                            $timeFormatted = \Carbon\Carbon::parse($timeSlot)->format('h:i A');
                            $periodName = '';
                            $periodColor = '';
                            
                            $hour = (int) explode(':', $timeSlot)[0];
                            if ($hour == 11) {
                                $periodName = 'الصباح';
                                $periodColor = 'yellow';
                            } elseif ($hour >= 13 && $hour <= 17) {
                                $periodName = 'بعد الظهر';
                                $periodColor = 'blue';
                            } elseif ($hour >= 19 && $hour <= 21) {
                                $periodName = 'المساء';
                                $periodColor = 'purple';
                            }
                        @endphp
                        
                        <div class="bg-{{ $periodColor }}-50 border border-{{ $periodColor }}-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="bg-{{ $periodColor }}-100 p-2 rounded-lg">
                                        <i class="fas fa-clock text-{{ $periodColor }}-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-{{ $periodColor }}-900">{{ $timeFormatted }}</h4>
                                        <p class="text-sm text-{{ $periodColor }}-700">{{ $periodName }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-2">
                                    <span class="text-sm text-{{ $periodColor }}-700">{{ $slotBookings->count() }} حجز</span>
                                    @if($slotBookings->count() == 0)
                                        <a href="{{ route('admin.bookings.create', ['date' => $date, 'time' => $timeSlot]) }}" 
                                           class="bg-{{ $periodColor }}-600 hover:bg-{{ $periodColor }}-700 text-white px-3 py-1 rounded text-sm transition-colors duration-200">
                                            إضافة حجز
                                        </a>
                                    @endif
                                </div>
                            </div>
                            
                            @if($slotBookings->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($slotBookings as $booking)
                                        <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                                            <div class="flex items-center justify-between mb-3">
                                                <div class="flex items-center gap-2">
                                                    <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold text-sm">
                                                        {{ substr($booking->customer->name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <h5 class="font-medium text-gray-900">{{ $booking->customer->name }}</h5>
                                                        <p class="text-sm text-gray-500">{{ $booking->customer->phone }}</p>
                                                    </div>
                                                </div>
                                                
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
                                            
                                            <div class="space-y-2 mb-3">
                                                <div class="flex items-center gap-2">
                                                    <i class="fas fa-box text-gray-400 text-sm"></i>
                                                    <span class="text-sm text-gray-700">{{ $booking->product->name }}</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <i class="fas fa-user-tie text-gray-400 text-sm"></i>
                                                    <span class="text-sm text-gray-700">{{ $booking->serviceProvider->name }}</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <i class="fas fa-hashtag text-gray-400 text-sm"></i>
                                                    <span class="text-sm text-gray-700">{{ $booking->booking_number }}</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <i class="fas fa-dollar-sign text-gray-400 text-sm"></i>
                                                    <span class="text-sm font-medium text-green-600">{{ number_format($booking->total_price, 2) }} ر.س</span>
                                                </div>
                                                @if($booking->booking_source === 'website')
                                                    <div class="flex items-center gap-2">
                                                        <i class="fas fa-globe text-gray-400 text-sm"></i>
                                                        <span class="text-sm text-green-600">من الموقع الإلكتروني</span>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('admin.bookings.show', $booking) }}" 
                                                   class="flex-1 bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-2 rounded text-sm text-center transition-colors duration-200">
                                                    عرض
                                                </a>
                                                <a href="{{ route('admin.bookings.edit', $booking) }}" 
                                                   class="flex-1 bg-yellow-100 hover:bg-yellow-200 text-yellow-700 px-3 py-2 rounded text-sm text-center transition-colors duration-200">
                                                    تعديل
                                                </a>
                                                
                                                @if($booking->status == 'pending')
                                                    <form action="{{ route('admin.bookings.confirm', $booking) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="bg-green-100 hover:bg-green-200 text-green-700 p-2 rounded text-sm transition-colors duration-200" 
                                                                title="تأكيد">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                
                                                @if($booking->status == 'confirmed')
                                                    <form action="{{ route('admin.bookings.complete', $booking) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="bg-purple-100 hover:bg-purple-200 text-purple-700 p-2 rounded text-sm transition-colors duration-200" 
                                                                title="إكمال">
                                                            <i class="fas fa-star"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8 text-gray-500">
                                    <i class="fas fa-calendar-times text-4xl mb-2"></i>
                                    <p>لا توجد حجوزات في هذا الوقت</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">إجراءات سريعة</h3>
            </div>
            <div class="px-6 py-4">
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.bookings.create', ['date' => $date]) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                        <i class="fas fa-plus"></i>
                        إضافة حجز لهذا اليوم
                    </a>
                    
                    <a href="{{ route('admin.bookings.daily', ['date' => \Carbon\Carbon::tomorrow()->format('Y-m-d')]) }}" 
                       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        عرض حجوزات الغد
                    </a>
                    
                    <button onclick="window.print()" 
                            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                        <i class="fas fa-print"></i>
                        طباعة
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// تحديث الصفحة كل 30 ثانية لإظهار الحجوزات الجديدة
setInterval(function() {
    if (document.visibilityState === 'visible') {
        location.reload();
    }
}, 30000);

// إضافة keyboard shortcuts
document.addEventListener('keydown', function(e) {
    if (e.key === 'ArrowRight') {
        // الانتقال لليوم السابق
        window.location.href = '{{ route('admin.bookings.daily', ['date' => \Carbon\Carbon::parse($date)->subDay()->format('Y-m-d')]) }}';
    } else if (e.key === 'ArrowLeft') {
        // الانتقال لليوم التالي
        window.location.href = '{{ route('admin.bookings.daily', ['date' => \Carbon\Carbon::parse($date)->addDay()->format('Y-m-d')]) }}';
    } else if (e.key === 'n' && e.ctrlKey) {
        // إضافة حجز جديد
        e.preventDefault();
        window.location.href = '{{ route('admin.bookings.create', ['date' => $date]) }}';
    }
});
</script>

<style>
@media print {
    .no-print {
        display: none !important;
    }
    
    body {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}
</style>
@endpush 