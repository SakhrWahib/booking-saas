@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50" dir="rtl">
    <div class="p-6">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">برنامج الولاء - نقاطي</h1>
                        <p class="text-gray-600 mt-1">نظام الولاء والنقاط للعملاء - إدارة المستويات والخصومات</p>
                    </div>
                    <div class="flex gap-3">
                        <button onclick="refreshLoyaltyData()" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-sync-alt"></i>
                            تحديث البيانات
                        </button>
                      
                    </div>
                </div>
            </div>
        </div>

        <!-- Loyalty Levels Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-6 mb-6">
            <!-- إجمالي العملاء -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">إجمالي العملاء</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($loyaltyStats['total_customers'] ?? 0) }}</p>
                    </div>
                    <div class="bg-gray-100 p-3 rounded-full">
                        <i class="fas fa-users text-gray-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- العملاء النشطون -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">العملاء النشطون</p>
                        <p class="text-2xl font-bold text-blue-600">{{ number_format($loyaltyStats['active_customers'] ?? 0) }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-user-check text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- برونزي -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">برونزي</p>
                        <p class="text-2xl font-bold text-amber-600">{{ number_format($loyaltyStats['bronze_customers'] ?? 0) }}</p>
                        <p class="text-xs text-gray-500">1-3 حجوزات</p>
                    </div>
                    <div class="bg-amber-100 p-3 rounded-full">
                        <i class="fas fa-medal text-amber-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- فضي -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">فضي</p>
                        <p class="text-2xl font-bold text-gray-400">{{ number_format($loyaltyStats['silver_customers'] ?? 0) }}</p>
                        <p class="text-xs text-gray-500">4-8 حجوزات</p>
                    </div>
                    <div class="bg-gray-100 p-3 rounded-full">
                        <i class="fas fa-medal text-gray-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- ذهبي -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">ذهبي</p>
                        <p class="text-2xl font-bold text-yellow-500">{{ number_format($loyaltyStats['gold_customers'] ?? 0) }}</p>
                        <p class="text-xs text-gray-500">9-15 حجوزات</p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <i class="fas fa-medal text-yellow-500 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- بلاتيني -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">بلاتيني</p>
                        <p class="text-2xl font-bold text-purple-600">{{ number_format($loyaltyStats['platinum_customers'] ?? 0) }}</p>
                        <p class="text-xs text-gray-500">16-20 حجوزات</p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <i class="fas fa-crown text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Points Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">إجمالي النقاط الموزعة</p>
                        <p class="text-2xl font-bold text-green-600">{{ number_format($pointsStats['total_points'] ?? 0) }}</p>
                        <p class="text-xs text-gray-500">نقطة</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-star text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">متوسط النقاط للعميل</p>
                        <p class="text-2xl font-bold text-blue-600">{{ number_format($pointsStats['average_points'] ?? 0, 1) }}</p>
                        <p class="text-xs text-gray-500">نقطة</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-chart-line text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">المؤهلون للكوبونات</p>
                        <p class="text-2xl font-bold text-purple-600">{{ number_format(($loyaltyStats['gold_customers'] ?? 0) + ($loyaltyStats['platinum_customers'] ?? 0)) }}</p>
                        <p class="text-xs text-gray-500">عميل (ذهبي + بلاتيني)</p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <i class="fas fa-ticket-alt text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Customers -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-trophy text-yellow-500"></i>
                    أفضل العملاء (أعلى 20)
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">العميل</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المستوى</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">عدد الحجوزات</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">النقاط</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">إجمالي الإنفاق</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">آخر حجز</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($topCustomers as $customer)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                            <i class="fas fa-user text-gray-600"></i>
                                        </div>
                                    </div>
                                    <div class="mr-4">
                                        <a href="{{ route('admin.customers.show', $customer->id) }}" class="text-sm font-medium text-gray-900 hover:text-blue-600 transition-colors duration-200 cursor-pointer">{{ $customer->name }}</a>
                                        <div class="text-sm text-gray-500">{{ $customer->phone }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($customer->loyalty_level == 'برونزي') bg-amber-100 text-amber-800
                                    @elseif($customer->loyalty_level == 'فضي') bg-gray-100 text-gray-800
                                    @elseif($customer->loyalty_level == 'ذهبي') bg-yellow-100 text-yellow-800
                                    @elseif($customer->loyalty_level == 'بلاتيني') bg-purple-100 text-purple-800
                                    @elseif($customer->loyalty_level == 'ماسي') bg-indigo-100 text-indigo-800
                                    @else bg-blue-100 text-blue-800
                                    @endif">
                                    {{ $customer->loyalty_level }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $customer->bookings_count }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="font-medium text-green-600">{{ $customer->points }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($customer->bookings_sum_total_price ?? 0, 2) }} ر.س
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($customer->bookings_count > 0)
                                    {{ \Carbon\Carbon::parse($customer->created_at)->diffForHumans() }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="viewCustomerDetails({{ $customer->id }})" 
                                        class="text-blue-600 hover:text-blue-900 ml-3">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="createCoupon({{ $customer->id }})" 
                                        class="text-green-600 hover:text-green-900">
                                    <i class="fas fa-ticket-alt"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                لا توجد بيانات متاحة
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Customers by Level -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- برونزي -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-medal text-amber-600"></i>
                        العملاء البرونزي (1-3 حجوزات)
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @forelse($customersByLevel['bronze'] ?? [] as $customer)
                        <div class="flex justify-between items-center p-3 bg-amber-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-amber-200 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-amber-600 text-sm"></i>
                                </div>
                                <div>
                                    <a href="{{ route('admin.customers.show', $customer->id) }}" class="text-sm font-medium text-gray-900 hover:text-blue-600 transition-colors duration-200 cursor-pointer">{{ $customer->name }}</a>
                                    <div class="text-xs text-gray-500">{{ $customer->phone }}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-medium text-amber-600">{{ $customer->bookings_count }} حجز</div>
                                <div class="text-xs text-gray-500">{{ number_format($customer->bookings_sum_total_price ?? 0, 0) }} ر.س</div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-gray-500 text-sm py-4">
                            لا يوجد عملاء في هذا المستوى
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- فضي -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-medal text-gray-400"></i>
                        العملاء الفضي (4-8 حجوزات)
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @forelse($customersByLevel['silver'] ?? [] as $customer)
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-gray-600 text-sm"></i>
                                </div>
                                <div>
                                    <a href="{{ route('admin.customers.show', $customer->id) }}" class="text-sm font-medium text-gray-900 hover:text-blue-600 transition-colors duration-200 cursor-pointer">{{ $customer->name }}</a>
                                    <div class="text-xs text-gray-500">{{ $customer->phone }}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-medium text-gray-600">{{ $customer->bookings_count }} حجز</div>
                                <div class="text-xs text-gray-500">{{ number_format($customer->bookings_sum_total_price ?? 0, 0) }} ر.س</div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-gray-500 text-sm py-4">
                            لا يوجد عملاء في هذا المستوى
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Gold and Platinum -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- ذهبي -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-medal text-yellow-500"></i>
                        العملاء الذهبي (9-15 حجوزات)
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @forelse($customersByLevel['gold'] ?? [] as $customer)
                        <div class="flex justify-between items-center p-3 bg-yellow-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-yellow-200 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-yellow-600 text-sm"></i>
                                </div>
                                <div>
                                    <a href="{{ route('admin.customers.show', $customer->id) }}" class="text-sm font-medium text-gray-900 hover:text-blue-600 transition-colors duration-200 cursor-pointer">{{ $customer->name }}</a>
                                    <div class="text-xs text-gray-500">{{ $customer->phone }}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-medium text-yellow-600">{{ $customer->bookings_count }} حجز</div>
                                <div class="text-xs text-gray-500">{{ number_format($customer->bookings_sum_total_price ?? 0, 0) }} ر.س</div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-gray-500 text-sm py-4">
                            لا يوجد عملاء في هذا المستوى
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- بلاتيني -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-crown text-purple-600"></i>
                        العملاء البلاتيني (16-20 حجوزات)
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @forelse($customersByLevel['platinum'] ?? [] as $customer)
                        <div class="flex justify-between items-center p-3 bg-purple-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-purple-200 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-purple-600 text-sm"></i>
                                </div>
                                <div>
                                    <a href="{{ route('admin.customers.show', $customer->id) }}" class="text-sm font-medium text-gray-900 hover:text-blue-600 transition-colors duration-200 cursor-pointer">{{ $customer->name }}</a>
                                    <div class="text-xs text-gray-500">{{ $customer->phone }}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-medium text-purple-600">{{ $customer->bookings_count }} حجز</div>
                                <div class="text-xs text-gray-500">{{ number_format($customer->bookings_sum_total_price ?? 0, 0) }} ر.س</div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-gray-500 text-sm py-4">
                            لا يوجد عملاء في هذا المستوى
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Diamond Level -->
        @if(count($customersByLevel['diamond'] ?? []) > 0)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-gem text-indigo-600"></i>
                        العملاء الماسي (أكثر من 20 حجوزة)
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($customersByLevel['diamond'] ?? [] as $customer)
                    <div class="p-4 bg-indigo-50 rounded-lg border border-indigo-200">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 bg-indigo-200 rounded-full flex items-center justify-center">
                                <i class="fas fa-gem text-indigo-600"></i>
                            </div>
                            <div>
                                <a href="{{ route('admin.customers.show', $customer->id) }}" class="text-sm font-medium text-gray-900 hover:text-blue-600 transition-colors duration-200 cursor-pointer">{{ $customer->name }}</a>
                                <div class="text-xs text-gray-500">{{ $customer->phone }}</div>
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="text-lg font-bold text-indigo-600">{{ $customer->bookings_count }} حجز</div>
                            <div class="text-sm text-gray-600">{{ number_format($customer->bookings_sum_total_price ?? 0, 0) }} ر.س</div>
                            <div class="text-xs text-indigo-500 mt-1">VIP ماسي</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Available Coupons -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-ticket-alt text-green-600"></i>
                    الكوبونات المتاحة
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($availableCoupons as $coupon)
                    <div class="p-4 bg-green-50 rounded-lg border border-green-200 coupon-card">
                        <div class="flex justify-between items-start mb-2">
                            <div class="text-sm font-medium text-green-800">{{ $coupon->name }}</div>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                {{ $coupon->type == 'percentage' ? 'نسبة مئوية' : 'مبلغ ثابت' }}
                            </span>
                        </div>
                        <div class="text-lg font-bold text-green-600 mb-2">
                            {{ $coupon->value }}{{ $coupon->type == 'percentage' ? '%' : ' ر.س' }}
                        </div>
                        <div class="text-xs text-gray-600 mb-2">
                            الكود: <span class="font-mono bg-white px-2 py-1 rounded">{{ $coupon->code }}</span>
                        </div>
                        <div class="text-xs text-gray-500 expiry-date">
                            @if($coupon->end_date)
                                ينتهي: {{ \Carbon\Carbon::parse($coupon->end_date)->format('Y-m-d') }}
                            @else
                                لا ينتهي
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full text-center text-gray-500 text-sm py-8">
                        لا توجد كوبونات متاحة حالياً
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Customer Details -->
<div id="customerModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div id="customerModalContent">
                <!-- Content will be loaded here -->
            </div>
            <div class="items-center px-4 py-3">
                <button id="closeCustomerModal" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    إغلاق
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Creating Coupon -->
<div id="couponModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">إنشاء كوبون خصم</h3>
            <form id="couponForm">
                <input type="hidden" id="customer_id" name="customer_id">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">نوع الخصم</label>
                    <select id="discount_type" name="discount_type" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="percentage">نسبة مئوية</option>
                        <option value="fixed">مبلغ ثابت</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">قيمة الخصم</label>
                    <input type="number" id="discount_value" name="discount_value" step="0.01" min="0" 
                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">مدة الصلاحية (أيام)</label>
                    <input type="number" id="expiry_days" name="expiry_days" min="1" max="365" value="30"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                        إنشاء الكوبون
                    </button>
                    <button type="button" onclick="closeCouponModal()" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium">
                        إلغاء
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function refreshLoyaltyData() {
    location.reload();
}

function exportLoyaltyReport() {
    // يمكن إضافة منطق تصدير التقرير هنا
    alert('سيتم إضافة ميزة تصدير التقرير قريباً');
}

function viewCustomerDetails(customerId) {
    fetch(`/admin/loyalty/customer/${customerId}`)
        .then(response => response.json())
        .then(data => {
            const modal = document.getElementById('customerModal');
            const content = document.getElementById('customerModalContent');
            
            content.innerHTML = `
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 mb-4">
                    <i class="fas fa-user text-blue-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">${data.name}</h3>
                <div class="text-sm text-gray-500 mb-4">
                    <p>الهاتف: ${data.phone}</p>
                    <p>المستوى: <span class="font-medium text-blue-600">${data.loyalty_level}</span></p>
                    <p>النقاط: <span class="font-medium text-green-600">${data.points}</span></p>
                    <p>إجمالي الإنفاق: <span class="font-medium text-purple-600">${data.total_spent ? data.total_spent.toFixed(2) : '0'} ر.س</span></p>
                    <p>عدد الحجوزات: <span class="font-medium text-orange-600">${data.bookings ? data.bookings.length : '0'}</span></p>
                </div>
            `;
            
            modal.classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء تحميل بيانات العميل');
        });
}

function createCoupon(customerId) {
    document.getElementById('customer_id').value = customerId;
    document.getElementById('couponModal').classList.remove('hidden');
}

function closeCouponModal() {
    document.getElementById('couponModal').classList.add('hidden');
    document.getElementById('couponForm').reset();
}

// Close modals when clicking outside
window.onclick = function(event) {
    const customerModal = document.getElementById('customerModal');
    const couponModal = document.getElementById('couponModal');
    
    if (event.target == customerModal) {
        customerModal.classList.add('hidden');
    }
    if (event.target == couponModal) {
        couponModal.classList.add('hidden');
    }
}

// Close customer modal
document.getElementById('closeCustomerModal').onclick = function() {
    document.getElementById('customerModal').classList.add('hidden');
}

// Handle coupon form submission
document.getElementById('couponForm').onsubmit = function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('/admin/loyalty/create-coupon', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            customer_id: formData.get('customer_id'),
            discount_type: formData.get('discount_type'),
            discount_value: formData.get('discount_value'),
            expiry_days: formData.get('expiry_days')
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            closeCouponModal();
            location.reload(); // Refresh to show new coupon
        } else {
            alert('حدث خطأ أثناء إنشاء الكوبون');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ أثناء إنشاء الكوبون');
    });
};

// دالة لإزالة الكوبونات منتهية الصلاحية
function removeExpiredCoupons() {
    const now = new Date();
    const couponCards = document.querySelectorAll('.coupon-card');
    
    couponCards.forEach(card => {
        const expiryDateText = card.querySelector('.expiry-date');
        if (expiryDateText) {
            const expiryText = expiryDateText.textContent;
            if (expiryText.includes('ينتهي:')) {
                const dateMatch = expiryText.match(/(\d{4})-(\d{2})-(\d{2})/);
                if (dateMatch) {
                    const expiryDate = new Date(dateMatch[1], dateMatch[2] - 1, dateMatch[3]);
                    if (expiryDate <= now) {
                        card.style.opacity = '0.5';
                        card.style.filter = 'grayscale(100%)';
                        setTimeout(() => {
                            card.remove();
                        }, 1000);
                    }
                }
            }
        }
    });
}

// تشغيل دالة إزالة الكوبونات منتهية الصلاحية كل دقيقة
setInterval(removeExpiredCoupons, 60000);

// تشغيل الدالة عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    removeExpiredCoupons();
});
</script>
@endpush
