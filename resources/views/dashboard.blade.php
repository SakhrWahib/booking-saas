@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 lazy-load" dir="rtl">
    <div class="p-6">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">لوحة التحكم</h1>
                        <p class="text-gray-600 mt-1">مرحباً بك في لوحة تحكم {{ config('brand.name') }} - نظرة عامة على أداء النظام</p>
                        
                        <!-- عرض الفترة المحددة -->
                        @if(request('date_range') && request('date_range') != 'today')
                            <div class="mt-2 p-2 bg-blue-50 border border-blue-200 rounded-lg">
                                <p class="text-sm text-blue-800">
                                    <i class="fas fa-filter text-blue-600 ml-1"></i>
                                    الفترة المحددة: 
                                    @if(request('date_range') == 'yesterday')
                                        أمس
                                    @elseif(request('date_range') == 'last_7_days')
                                        آخر 7 أيام
                                    @elseif(request('date_range') == 'last_30_days')
                                        آخر 30 يوم
                                    @elseif(request('date_range') == 'week')
                                        الأسبوع الحالي
                                    @elseif(request('date_range') == 'last_week')
                                        الأسبوع الماضي
                                    @elseif(request('date_range') == 'month')
                                        الشهر الحالي
                                    @elseif(request('date_range') == 'last_month')
                                        الشهر الماضي
                                    @elseif(request('date_range') == 'year')
                                        السنة الحالية
                                    @elseif(request('date_range') == 'last_year')
                                        السنة الماضية
                                    @elseif(request('date_range') == 'custom')
                                        من {{ request('start_date') }} إلى {{ request('end_date') }}
                                    @endif
                                    
                                    @if(request('service_provider_id'))
                                        | مقدم الخدمة: {{ $filters['service_providers']->where('id', request('service_provider_id'))->first()->name ?? 'غير محدد' }}
                                    @endif
                                    
                                    @if(request('product_id'))
                                        | المنتج: {{ $filters['products']->where('id', request('product_id'))->first()->name ?? 'غير محدد' }}
                                    @endif
                                    
                                    @if(request('region'))
                                        | المنطقة: {{ request('region') }}
                                    @endif
                                    
                                    @if(request('status'))
                                        | الحالة: {{ $filters['statuses'][request('status')] ?? request('status') }}
                                    @endif
                                </p>
                            </div>
                        @endif
                    </div>
                    <div class="flex gap-3">
                        <button onclick="refreshStats()" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-sync-alt"></i>
                            تحديث البيانات
                        </button>
                   
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="px-6 py-4">
                <form method="GET" action="{{ route('dashboard') }}" class="grid grid-cols-1 md:grid-cols-6 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">الفترة الزمنية</label>
                        <select name="date_range" id="date_range" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-right">
                            <option value="today" {{ request('date_range', 'today') == 'today' ? 'selected' : '' }}>اليوم</option>
                            <option value="yesterday" {{ request('date_range') == 'yesterday' ? 'selected' : '' }}>أمس</option>
                            <option value="last_7_days" {{ request('date_range') == 'last_7_days' ? 'selected' : '' }}>آخر 7 أيام</option>
                            <option value="last_30_days" {{ request('date_range') == 'last_30_days' ? 'selected' : '' }}>آخر 30 يوم</option>
                            <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>الأسبوع الحالي</option>
                            <option value="last_week" {{ request('date_range') == 'last_week' ? 'selected' : '' }}>الأسبوع الماضي</option>
                            <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>الشهر الحالي</option>
                            <option value="last_month" {{ request('date_range') == 'last_month' ? 'selected' : '' }}>الشهر الماضي</option>
                            <option value="year" {{ request('date_range') == 'year' ? 'selected' : '' }}>السنة الحالية</option>
                            <option value="last_year" {{ request('date_range') == 'last_year' ? 'selected' : '' }}>السنة الماضية</option>
                            <option value="custom" {{ request('date_range') == 'custom' ? 'selected' : '' }}>تاريخ مخصص</option>
                        </select>
                    </div>
                    
                    <!-- حقول التاريخ المخصص - تظهر فقط عند اختيار "تاريخ مخصص" -->
                    <div id="custom_date_fields" class="md:col-span-2" style="display: {{ request('date_range') == 'custom' ? 'block' : 'none' }};">
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">من تاريخ</label>
                                <input type="date" name="start_date" value="{{ request('start_date') }}" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-right">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">إلى تاريخ</label>
                                <input type="date" name="end_date" value="{{ request('end_date') }}" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-right">
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">مقدم الخدمة</label>
                        <select name="service_provider_id" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-right">
                            <option value="">جميع مقدمي الخدمة</option>
                            @foreach($filters['service_providers'] ?? [] as $provider)
                                <option value="{{ $provider->id }}" {{ request('service_provider_id') == $provider->id ? 'selected' : '' }}>
                                    {{ $provider->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">المنتج</label>
                        <select name="product_id" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-right">
                            <option value="">جميع المنتجات</option>
                            @foreach($filters['products'] ?? [] as $product)
                                <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">المنطقة</label>
                        <select name="region" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-right">
                            <option value="">جميع المناطق</option>
                            @foreach($filters['regions'] ?? [] as $region)
                                <option value="{{ $region }}" {{ request('region') == $region ? 'selected' : '' }}>
                                    {{ $region }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">حالة الحجز</label>
                        <select name="status" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-right">
                            <option value="">جميع الحالات</option>
                            @foreach($filters['statuses'] ?? [] as $key => $status)
                                <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                            تطبيق
                        </button>
                        <a href="{{ route('dashboard') }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 text-center">
                            إعادة تعيين
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Financial Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">إجمالي الإيرادات للفترة المحددة</p>
                        <p class="text-2xl font-bold text-green-600">{{ number_format($financialStats['today_revenue'] ?? 0, 2) }} ر.س</p>
                        <p class="text-xs text-gray-500 mt-1">
                            @if(request('date_range') == 'today')
                                اليوم الحالي
                            @elseif(request('date_range') == 'yesterday')
                                أمس
                            @elseif(request('date_range') == 'last_7_days')
                                آخر 7 أيام
                            @elseif(request('date_range') == 'last_30_days')
                                آخر 30 يوم
                            @elseif(request('date_range') == 'week')
                                الأسبوع الحالي
                            @elseif(request('date_range') == 'last_week')
                                الأسبوع الماضي
                            @elseif(request('date_range') == 'month')
                                الشهر الحالي
                            @elseif(request('date_range') == 'last_month')
                                الشهر الماضي
                            @elseif(request('date_range') == 'year')
                                السنة الحالية
                            @elseif(request('date_range') == 'last_year')
                                السنة الماضية
                            @elseif(request('date_range') == 'custom')
                                من {{ request('start_date') }} إلى {{ request('end_date') }}
                            @else
                                اليوم الحالي
                            @endif
                        </p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-money-bill-wave text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">إجمالي الإيرادات الشهر</p>
                        <p class="text-2xl font-bold text-blue-600">{{ number_format($financialStats['month_revenue'] ?? 0, 2) }} ر.س</p>
                        <p class="text-xs text-gray-500 mt-1">
                            @if(request('date_range') == 'month')
                                الشهر الحالي
                            @elseif(request('date_range') == 'last_month')
                                الشهر الماضي
                            @elseif(request('date_range') == 'custom')
                                شهر الفترة المحددة
                            @else
                                الشهر الحالي
                            @endif
                        </p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-chart-line text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">إجمالي الإيرادات السنة</p>
                        <p class="text-2xl font-bold text-purple-600">{{ number_format($financialStats['year_revenue'] ?? 0, 2) }} ر.س</p>
                        <p class="text-xs text-gray-500 mt-1">
                            @if(request('date_range') == 'year')
                                السنة الحالية
                            @elseif(request('date_range') == 'last_year')
                                السنة الماضية
                            @elseif(request('date_range') == 'custom')
                                سنة الفترة المحددة
                            @else
                                السنة الحالية
                            @endif
                        </p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <i class="fas fa-calendar-alt text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">متوسط قيمة الحجز</p>
                        <p class="text-2xl font-bold text-orange-600">{{ number_format($financialStats['avg_booking_value'] ?? 0, 2) }} ر.س</p>
                        <p class="text-xs text-gray-500 mt-1">آخر 30 يوم</p>
                    </div>
                    <div class="bg-orange-100 p-3 rounded-full">
                        <i class="fas fa-calculator text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>
        
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">الفواتير المدفوعة</p>
                        <p class="text-2xl font-bold text-green-600">{{ $financialStats['paid_invoices'] ?? 0 }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            @if(request('date_range') == 'custom')
                                من {{ request('start_date') }} إلى {{ request('end_date') }}
                            @else
                                في الفترة المحددة
                            @endif
                        </p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">الفواتير غير المدفوعة</p>
                        <p class="text-2xl font-bold text-red-600">{{ $financialStats['unpaid_invoices'] ?? 0 }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            @if(request('date_range') == 'custom')
                                من {{ request('start_date') }} إلى {{ request('end_date') }}
                            @else
                                في الفترة المحددة
                            @endif
                        </p>
                    </div>
                    <div class="bg-red-100 p-3 rounded-full">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Customer Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">إحصائيات العملاء</h3>
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ $customerStats['total_customers'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">إجمالي العملاء</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">{{ $customerStats['new_customers_this_month'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">عملاء جدد هذا الشهر</div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">الخدمات الأكثر طلباً</h3>
                    <i class="fas fa-star text-yellow-600 text-xl"></i>
                </div>
                <div class="space-y-3">
                    @forelse($productStats['most_requested_services'] ?? [] as $service)
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-700">{{ $service->name }}</span>
                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded-full">
                            {{ $service->booking_count }} حجز
                        </span>
                    </div>
                    @empty
                    <div class="text-center text-gray-500 text-sm py-4">
                        <i class="fas fa-chart-bar text-gray-300 text-2xl mb-2"></i>
                        <p>لا توجد بيانات متاحة</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
        
        <!-- Geographic Statistics -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-green-600"></i>
                        توزيع الحجوزات حسب المناطق
                    </h3>
                </div>
                <div class="px-6 py-6">
                    <div class="space-y-4">
                        @forelse($geographicStats['bookings_by_region'] ?? [] as $region)
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-700">{{ $region->region }}</span>
                            <div class="flex items-center gap-2">
                                <div class="w-24 bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ ($region->booking_count / max(1, ($geographicStats['bookings_by_region']->sum('booking_count')))) * 100 }}%"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ $region->booking_count }}</span>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-gray-500 text-sm py-8">
                            <i class="fas fa-map text-gray-300 text-3xl mb-2"></i>
                            <p>لا توجد بيانات جغرافية متاحة</p>
                </div>
                        @endforelse
            </div>
        </div>
    </div>
    
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-route text-blue-600"></i>
                        إحصائيات جغرافية
                    </h3>
                </div>
                <div class="px-6 py-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-map-marker-alt text-blue-600"></i>
                                <span class="text-sm text-gray-700">متوسط المسافة للحجوزات</span>
                            </div>
                            <span class="font-semibold text-blue-600">{{ number_format($geographicStats['avg_distance'] ?? 0, 1) }} كم</span>
                        </div>
                        
                        <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-chart-pie text-green-600"></i>
                                <span class="text-sm text-gray-700">المناطق الأكثر طلباً</span>
                            </div>
                            <span class="font-semibold text-green-600">{{ count($geographicStats['most_requested_areas'] ?? []) }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center p-3 bg-purple-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-globe text-purple-600"></i>
                                <span class="text-sm text-gray-700">إجمالي المناطق المغطاة</span>
                            </div>
                            <span class="font-semibold text-purple-600">{{ count($geographicStats['bookings_by_region'] ?? []) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6" data-defer="content">
            <!-- Revenue Chart -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 lazy-load">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-chart-line text-green-600"></i>
                        الإيرادات الشهرية
                    </h3>
                </div>
                <div class="px-6 py-6">
                    <canvas id="revenueChart" height="300"></canvas>
                </div>
            </div>
            
            <!-- Bookings Status Chart -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 lazy-load">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-chart-pie text-blue-600"></i>
                        حالة الحجوزات
                    </h3>
                </div>
                <div class="px-6 py-6">
                    <canvas id="bookingsChart" height="300"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Additional Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Customer Growth Chart -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-users text-purple-600"></i>
                        نمو العملاء
                    </h3>
                </div>
                <div class="px-6 py-6">
                    <canvas id="customersChart" height="300"></canvas>
                </div>
            </div>
            
            <!-- Service Performance Chart -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-star text-yellow-600"></i>
                        أداء الخدمات
                    </h3>
                </div>
                <div class="px-6 py-6">
                    <canvas id="servicesChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Chart.js - محسن للسرعة -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js" defer crossorigin="anonymous"></script>

<script>
// تحسين الأداء - تأجيل تحميل الرسوم البيانية
let chartsLoaded = false;

// تفعيل حقول التاريخ المخصص
document.addEventListener('DOMContentLoaded', function() {
    const dateRangeSelect = document.getElementById('date_range');
    const customDateFields = document.getElementById('custom_date_fields');
    const startDateInput = document.querySelector('input[name="start_date"]');
    const endDateInput = document.querySelector('input[name="end_date"]');
    
    function toggleCustomDateFields() {
        if (dateRangeSelect.value === 'custom') {
            customDateFields.style.display = 'block';
            
            // تعيين التاريخ الافتراضي إذا لم يتم تحديد تاريخ
            if (!startDateInput.value) {
                const today = new Date();
                const lastMonth = new Date(today.getFullYear(), today.getMonth() - 1, today.getDate());
                startDateInput.value = lastMonth.toISOString().split('T')[0];
            }
            
            if (!endDateInput.value) {
                const today = new Date();
                endDateInput.value = today.toISOString().split('T')[0];
            }
        } else {
            customDateFields.style.display = 'none';
        }
    }
    
    // تفعيل عند تحميل الصفحة
    toggleCustomDateFields();
    
    // تفعيل عند تغيير القيمة
    dateRangeSelect.addEventListener('change', toggleCustomDateFields);
    
    // التحقق من صحة التواريخ
    if (startDateInput && endDateInput) {
        endDateInput.addEventListener('change', function() {
            if (startDateInput.value && endDateInput.value) {
                if (new Date(startDateInput.value) > new Date(endDateInput.value)) {
                    alert('تاريخ البداية يجب أن يكون قبل تاريخ النهاية');
                    endDateInput.value = '';
                }
            }
        });
        
        startDateInput.addEventListener('change', function() {
            if (startDateInput.value && endDateInput.value) {
                if (new Date(startDateInput.value) > new Date(endDateInput.value)) {
                    alert('تاريخ البداية يجب أن يكون قبل تاريخ النهاية');
                    startDateInput.value = '';
                }
            }
        });
    }
});

// تحسين الأداء - تحميل الرسوم البيانية عند الحاجة
function loadCharts() {
    if (chartsLoaded) return;
    
    // Chart.js Configuration
    Chart.defaults.font.family = 'Cairo, sans-serif';
    Chart.defaults.font.size = 12;
    Chart.defaults.color = '#6B7280';

    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart');
    if (!revenueCtx) return;
    
    const revenueCtx2d = revenueCtx.getContext('2d');
    const revenueChart = new Chart(revenueCtx2d, {
        type: 'line',
        data: {
            labels: @json($chartData['monthly_revenue']['labels'] ?? []),
            datasets: [{
                label: 'الإيرادات (ر.س)',
                data: @json($chartData['monthly_revenue']['data'] ?? []),
                borderColor: '#10B981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#10B981',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                titleColor: '#ffffff',
                bodyColor: '#ffffff',
                borderColor: '#10B981',
                borderWidth: 1,
                cornerRadius: 8,
                displayColors: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)'
                },
                ticks: {
                    callback: function(value) {
                        return value.toLocaleString() + ' ر.س';
                    }
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});

    // Bookings Status Chart
    const bookingsCtx = document.getElementById('bookingsChart');
    if (!bookingsCtx) return;
    
    const bookingsCtx2d = bookingsCtx.getContext('2d');
    const bookingsChart = new Chart(bookingsCtx2d, {
    type: 'doughnut',
    data: {
        labels: @json($chartData['booking_statuses']['labels'] ?? []),
        datasets: [{
            data: @json($chartData['booking_statuses']['data'] ?? []),
            backgroundColor: @json($chartData['booking_statuses']['colors'] ?? []),
            borderWidth: 0,
            hoverOffset: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 20,
                    usePointStyle: true,
                    pointStyle: 'circle'
                }
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                titleColor: '#ffffff',
                bodyColor: '#ffffff',
                borderColor: '#10B981',
                borderWidth: 1,
                cornerRadius: 8,
                callbacks: {
                    label: function(context) {
                        return context.label + ': ' + context.parsed + '%';
                    }
                }
            }
        }
    }
});

    // Customer Growth Chart
    const customersCtx = document.getElementById('customersChart');
    if (!customersCtx) return;
    
    const customersCtx2d = customersCtx.getContext('2d');
    const customersChart = new Chart(customersCtx2d, {
    type: 'bar',
    data: {
        labels: @json($chartData['customer_growth']['labels'] ?? []),
        datasets: [{
            label: 'عملاء جدد',
            data: @json($chartData['customer_growth']['data'] ?? []),
            backgroundColor: 'rgba(139, 92, 246, 0.8)',
            borderColor: '#8B5CF6',
            borderWidth: 2,
            borderRadius: 8,
            borderSkipped: false
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                titleColor: '#ffffff',
                bodyColor: '#ffffff',
                borderColor: '#8B5CF6',
                borderWidth: 1,
                cornerRadius: 8,
                displayColors: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});

    // Service Performance Chart
    const servicesCtx = document.getElementById('servicesChart');
    if (!servicesCtx) return;
    
    const servicesCtx2d = servicesCtx.getContext('2d');
    const servicesChart = new Chart(servicesCtx2d, {
        type: 'bar',
        data: {
            labels: @json($chartData['service_performance']['labels'] ?? []),
            datasets: [{
                label: 'عدد الحجوزات',
                data: @json($chartData['service_performance']['data'] ?? []),
                backgroundColor: 'rgba(251, 191, 36, 0.8)',
                borderColor: '#FBBF24',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderColor: '#FBBF24',
                    borderWidth: 1,
                    cornerRadius: 8
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
    
    chartsLoaded = true;
}
}

// تحميل الرسوم البيانية عند الحاجة
function loadChartsWhenVisible() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                loadCharts();
                observer.disconnect();
            }
        });
    });
    
    const chartsSection = document.querySelector('.grid.grid-cols-1.lg\\:grid-cols-2.gap-6.mb-6');
    if (chartsSection) {
        observer.observe(chartsSection);
    }
}

// تحميل الرسوم البيانية بعد تحميل الصفحة - محسن للسرعة
document.addEventListener('DOMContentLoaded', function() {
    // تحميل الرسوم البيانية عند الحاجة فقط
    if ('IntersectionObserver' in window) {
        loadChartsWhenVisible();
    } else {
        // fallback للمتصفحات القديمة
        setTimeout(loadCharts, 2000);
    }
});

function refreshStats() {
    location.reload();
}

function exportReport() {
    // يمكن إضافة منطق تصدير التقرير هنا
    alert('سيتم إضافة ميزة تصدير التقرير قريباً');
}

// تحسين الأداء - تحديث البيانات عند الحاجة فقط
let updateInterval;

function startDataUpdates() {
    // تحديث البيانات كل 10 دقائق بدلاً من 5
    updateInterval = setInterval(function() {
        // يمكن إضافة AJAX call لتحديث البيانات بدون إعادة تحميل الصفحة
    }, 600000); // 10 دقائق
}

function stopDataUpdates() {
    if (updateInterval) {
        clearInterval(updateInterval);
    }
}

// بدء التحديثات عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    startDataUpdates();
});

// إيقاف التحديثات عند عدم رؤية الصفحة
document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        stopDataUpdates();
    } else {
        startDataUpdates();
    }
});
</script>
@endpush
