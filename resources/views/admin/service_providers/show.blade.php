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
                            <div class="h-12 w-12 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold text-lg">
                                {{ substr($serviceProvider->name, 0, 1) }}
                            </div>
                            {{ $serviceProvider->name }}
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $serviceProvider->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $serviceProvider->is_active ? 'نشط' : 'غير نشط' }}
                            </span>
                        </h1>
                        <p class="text-gray-600 mt-1">مقدم الخدمة رقم #{{ $serviceProvider->id }} - تاريخ التسجيل: {{ $serviceProvider->created_at->format('Y/m/d') }}</p>
                    </div>
                    <div class="flex gap-3">
                        <button onclick="toggleStatus()" 
                                class="bg-{{ $serviceProvider->is_active ? 'red' : 'green' }}-600 hover:bg-{{ $serviceProvider->is_active ? 'red' : 'green' }}-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-power-off"></i>
                            {{ $serviceProvider->is_active ? 'إلغاء التفعيل' : 'تفعيل' }}
                        </button>
                        <a href="{{ route('admin.service-providers.edit', $serviceProvider) }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-edit"></i>
                            تعديل البيانات
                        </a>
                        <a href="{{ route('admin.service-providers.index') }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-arrow-right"></i>
                            العودة للقائمة
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <!-- Provider Info & Details -->
            <div class="xl:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-user-cog text-blue-600"></i>
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
                                        <p class="text-sm text-gray-600">رقم مقدم الخدمة</p>
                                        <p class="font-semibold text-gray-900">#{{ $serviceProvider->id }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-3">
                                    <div class="bg-green-100 p-2 rounded-lg">
                                        <i class="fas fa-phone text-green-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">رقم الجوال</p>
                                        <a href="tel:{{ $serviceProvider->phone }}" class="font-semibold text-blue-600 hover:text-blue-800">
                                            {{ $serviceProvider->phone }}
                                        </a>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div class="bg-purple-100 p-2 rounded-lg">
                                        <i class="fas fa-envelope text-purple-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">البريد الإلكتروني</p>
                                        @if($serviceProvider->email)
                                            <a href="mailto:{{ $serviceProvider->email }}" class="font-semibold text-blue-600 hover:text-blue-800">
                                                {{ $serviceProvider->email }}
                                            </a>
                                        @else
                                            <p class="font-semibold text-gray-400">غير محدد</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div class="bg-indigo-100 p-2 rounded-lg">
                                        <i class="fas fa-users text-indigo-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">السعة</p>
                                        <p class="font-semibold text-gray-900">{{ $serviceProvider->capacity }} حجز متزامن</p>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="flex items-center gap-3">
                                    <div class="bg-yellow-100 p-2 rounded-lg">
                                        <i class="fas fa-cogs text-yellow-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">نوع العمل</p>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $serviceProvider->work_type == 'single_person' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                            {{ $serviceProvider->work_type == 'single_person' ? 'شخص واحد فقط' : 'عدة أشخاص' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div class="bg-emerald-100 p-2 rounded-lg">
                                        <i class="fas fa-map-marked-alt text-emerald-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">نوع التغطية</p>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $serviceProvider->coverage_type == 'area_based' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                                            {{ $serviceProvider->coverage_type == 'area_based' ? 'مناطق محددة' : 'أقرب حجز' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div class="bg-orange-100 p-2 rounded-lg">
                                        <i class="fas fa-calendar-plus text-orange-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">تاريخ التسجيل</p>
                                        <p class="font-semibold text-gray-900">{{ $serviceProvider->created_at->format('Y/m/d H:i') }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div class="bg-gray-100 p-2 rounded-lg">
                                        <i class="fas fa-clock text-gray-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">آخر تحديث</p>
                                        <p class="font-semibold text-gray-900">{{ $serviceProvider->updated_at->format('Y/m/d H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Working Schedules -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-clock text-orange-600"></i>
                            جدول العمل المرتبط
                        </h3>
                    </div>
                    <div class="px-6 py-6">
                        @if($serviceProvider->workingSchedule)
                        <!-- الجدول المرتبط -->
                        <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-medium text-blue-800 flex items-center gap-2">
                                    <i class="fas fa-calendar-check"></i>
                                    {{ $serviceProvider->workingSchedule->schedule_name }}
                                </h4>
                                <div class="flex items-center gap-3">
                                    @if($serviceProvider->workingSchedule->is_default)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        افتراضي
                                    </span>
                                    @endif
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $serviceProvider->workingSchedule->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $serviceProvider->workingSchedule->is_active ? 'نشط' : 'غير نشط' }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-7 gap-2 text-sm mb-3">
                                @php
                                    $days = [
                                        'saturday' => 'السبت',
                                        'sunday' => 'الأحد', 
                                        'monday' => 'الاثنين',
                                        'tuesday' => 'الثلاثاء',
                                        'wednesday' => 'الأربعاء',
                                        'thursday' => 'الخميس',
                                        'friday' => 'الجمعة'
                                    ];
                                    $activeDays = $serviceProvider->workingSchedule->active_days;
                                @endphp
                                
                                @foreach($days as $key => $day)
                                    <div class="p-2 rounded border {{ ($activeDays[$key]['active'] ?? false) ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                                        <div class="font-medium text-center">{{ $day }}</div>
                                        @if($activeDays[$key]['active'] ?? false)
                                            <div class="text-xs text-center text-green-600 mt-1">
                                                {{ $activeDays[$key]['start_time'] ?? '' }} - {{ $activeDays[$key]['end_time'] ?? '' }}
                                            </div>
                                        @else
                                            <div class="text-xs text-center text-gray-500 mt-1">عطلة</div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <div class="text-sm text-blue-600 mb-2">
                                <i class="fas fa-calendar mr-2"></i>
                                من {{ $serviceProvider->workingSchedule->start_date }} إلى {{ $serviceProvider->workingSchedule->end_date }}
                            </div>

                            @if($serviceProvider->workingSchedule->description)
                            <div class="text-sm text-blue-600 mb-2">
                                <i class="fas fa-info-circle mr-2"></i>
                                {{ $serviceProvider->workingSchedule->description }}
                            </div>
                            @endif

                            @if($serviceProvider->workingSchedule->holidays && count($serviceProvider->workingSchedule->holidays) > 0)
                            <div class="text-sm text-red-600">
                                <i class="fas fa-calendar-times mr-2"></i>
                                أيام العطل: {{ implode(', ', $serviceProvider->workingSchedule->holidays) }}
                            </div>
                            @endif
                        </div>
                        @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-calendar-times text-gray-300 text-4xl mb-3"></i>
                            <h4 class="text-lg font-medium text-gray-700 mb-2">لا يوجد جدول عمل مرتبط</h4>
                            <p class="text-gray-500 mb-4">لم يتم ربط مقدم الخدمة بأي جدول عمل</p>
                            <a href="{{ route('admin.service-providers.edit', $serviceProvider) }}" 
                               class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 inline-flex items-center gap-2">
                                <i class="fas fa-edit"></i>
                                تعديل وربط بجدول عمل
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Location & Coverage Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-map-marker-alt text-green-600"></i>
                            معلومات الموقع والتغطية
                        </h3>
                    </div>
                    <div class="px-6 py-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Location Info -->
                            <div class="space-y-4">
                                <h4 class="font-medium text-gray-800">الموقع الأساسي</h4>
                                
                                @if($serviceProvider->address)
                                <div class="flex items-start gap-3">
                                    <div class="bg-blue-100 p-2 rounded-lg">
                                        <i class="fas fa-map-marker-alt text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">العنوان</p>
                                        <p class="font-semibold text-gray-900">{{ $serviceProvider->address }}</p>
                                    </div>
                                </div>
                                @endif

                                @if($serviceProvider->latitude && $serviceProvider->longitude)
                                <div class="flex items-center gap-3">
                                    <div class="bg-green-100 p-2 rounded-lg">
                                        <i class="fas fa-crosshairs text-green-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">الإحداثيات</p>
                                        <p class="font-semibold text-gray-900 text-sm">
                                            {{ $serviceProvider->latitude }}, {{ $serviceProvider->longitude }}
                                        </p>
                                    </div>
                                </div>
                                @endif

                                <!-- Holidays -->
                                @if($serviceProvider->holidays && count($serviceProvider->holidays) > 0)
                                <div>
                                    <h5 class="font-medium text-gray-700 mb-2 flex items-center gap-2">
                                        <i class="fas fa-calendar-times text-red-500"></i>
                                        أيام العطل
                                    </h5>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($serviceProvider->holidays as $holiday)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            {{ $holiday }}
                                        </span>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>

                            <!-- Coverage Info -->
                            <div class="space-y-4">
                                <h4 class="font-medium text-gray-800">معلومات التغطية</h4>
                                
                                <div class="flex items-center gap-3">
                                    <div class="bg-purple-100 p-2 rounded-lg">
                                        <i class="fas fa-globe text-purple-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">نوع التغطية</p>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $serviceProvider->coverage_type == 'area_based' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                                            <i class="fas {{ $serviceProvider->coverage_type == 'area_based' ? 'fa-map-marked-alt' : 'fa-location-arrow' }} mr-1"></i>
                                            {{ $serviceProvider->coverage_type == 'area_based' ? 'مناطق محددة' : 'أقرب حجز' }}
                                        </span>
                                    </div>
                                </div>

                                @if($serviceProvider->coverage_area)
                                <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                    <div class="flex items-center gap-2 mb-2">
                                        <i class="fas fa-check-circle text-green-600"></i>
                                        <span class="text-sm font-medium text-green-800">منطقة التغطية محددة</span>
                                    </div>
                                    <p class="text-xs text-green-600">تم تحديد منطقة التغطية على الخريطة</p>
                                </div>
                                @else
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                    <div class="flex items-center gap-2 mb-2">
                                        <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                                        <span class="text-sm font-medium text-yellow-800">منطقة التغطية غير محددة</span>
                                    </div>
                                    <p class="text-xs text-yellow-600">يُنصح بتحديد منطقة التغطية لتحسين دقة التعيين</p>
                                </div>
                                @endif

                                <!-- Map Display -->
                                @if($serviceProvider->latitude && $serviceProvider->longitude)
                                <div>
                                    <p class="text-sm font-medium text-gray-700 mb-2">الموقع على الخريطة</p>
                                    <div id="map" class="w-full h-40 rounded-lg border border-gray-300 bg-gray-100"></div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics & Quick Actions -->
            <div class="space-y-6">
                <!-- Statistics -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-chart-bar text-indigo-600"></i>
                            الإحصائيات
                        </h3>
                    </div>
                    <div class="px-6 py-6">
                        <div class="space-y-4">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                                <div class="bg-blue-100 p-3 rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-calendar-check text-blue-600"></i>
                                </div>
                                <div class="text-2xl font-bold text-blue-600">0</div>
                                <div class="text-xs text-blue-600">إجمالي الحجوزات</div>
                            </div>
                            
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                                <div class="bg-green-100 p-3 rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-clock text-green-600"></i>
                                </div>
                                <div class="text-2xl font-bold text-green-600">0</div>
                                <div class="text-xs text-green-600">الحجوزات النشطة</div>
                            </div>
                            
                            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 text-center">
                                <div class="bg-purple-100 p-3 rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-check-circle text-purple-600"></i>
                                </div>
                                <div class="text-2xl font-bold text-purple-600">0</div>
                                <div class="text-xs text-purple-600">الحجوزات المكتملة</div>
                            </div>
                            
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                                <div class="bg-yellow-100 p-3 rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-star text-yellow-600"></i>
                                </div>
                                <div class="text-2xl font-bold text-yellow-600">-</div>
                                <div class="text-xs text-yellow-600">التقييم العام</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-bolt text-yellow-600"></i>
                            إجراءات سريعة
                        </h3>
                    </div>
                    <div class="px-6 py-6">
                        <div class="space-y-3">
                            <button onclick="openWorkingSchedulesModal()" 
                                    class="w-full bg-orange-100 hover:bg-orange-200 text-orange-700 px-4 py-3 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                                <i class="fas fa-calendar-plus"></i>
                                إدارة جداول العمل
                            </button>
                            
                            <button onclick="openCoverageModal()" 
                                    class="w-full bg-green-100 hover:bg-green-200 text-green-700 px-4 py-3 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                                <i class="fas fa-map-marked-alt"></i>
                                تعديل منطقة التغطية
                            </button>
                            
                            <a href="tel:{{ $serviceProvider->phone }}" 
                               class="w-full bg-blue-100 hover:bg-blue-200 text-blue-700 px-4 py-3 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                                <i class="fas fa-phone"></i>
                                اتصال مباشر
                            </a>
                            
                            @if($serviceProvider->email)
                            <a href="mailto:{{ $serviceProvider->email }}" 
                               class="w-full bg-purple-100 hover:bg-purple-200 text-purple-700 px-4 py-3 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                                <i class="fas fa-envelope"></i>
                                إرسال بريد إلكتروني
                            </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Status & Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-info-circle text-blue-600"></i>
                            معلومات إضافية
                        </h3>
                    </div>
                    <div class="px-6 py-6">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">الحالة</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $serviceProvider->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $serviceProvider->is_active ? 'نشط' : 'غير نشط' }}
                                </span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">جدول العمل</span>
                                <span class="text-sm font-medium text-gray-900">
                                    {{ $serviceProvider->workingSchedule ? 'مرتبط' : 'غير مرتبط' }}
                                </span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">منطقة التغطية</span>
                                <span class="text-sm font-medium {{ $serviceProvider->coverage_area ? 'text-green-600' : 'text-yellow-600' }}">
                                    {{ $serviceProvider->coverage_area ? 'محددة' : 'غير محددة' }}
                                </span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">أيام العطل</span>
                                <span class="text-sm font-medium text-gray-900">
                                    {{ $serviceProvider->workingSchedule && $serviceProvider->workingSchedule->holidays ? count($serviceProvider->workingSchedule->holidays) : 0 }} يوم
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Working Schedules Modal -->
<div id="workingSchedulesModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">إدارة جداول العمل</h3>
                <button onclick="closeWorkingSchedulesModal()" 
                        class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="text-center py-8">
                <i class="fas fa-calendar-plus text-gray-300 text-6xl mb-4"></i>
                <h4 class="text-lg font-medium text-gray-900 mb-2">قريباً</h4>
                <p class="text-gray-500 mb-4">ستتمكن من إدارة جداول العمل من هنا</p>
                <p class="text-sm text-gray-400">سيتم إضافة هذه الميزة في التحديث القادم</p>
            </div>
        </div>
    </div>
</div>

<!-- Coverage Modal -->
<div id="coverageModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">تعديل منطقة التغطية</h3>
                <button onclick="closeCoverageModal()" 
                        class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="text-center py-8">
                <i class="fas fa-map-marked-alt text-gray-300 text-6xl mb-4"></i>
                <h4 class="text-lg font-medium text-gray-900 mb-2">قريباً</h4>
                <p class="text-gray-500 mb-4">ستتمكن من تعديل منطقة التغطية من هنا</p>
                <p class="text-sm text-gray-400">سيتم إضافة هذه الميزة في التحديث القادم</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if($serviceProvider->latitude && $serviceProvider->longitude)
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap" async defer></script>
<script>
function initMap() {
    const location = {
        lat: {{ $serviceProvider->latitude }},
        lng: {{ $serviceProvider->longitude }}
    };
    
    const map = new google.maps.Map(document.getElementById('map'), {
        zoom: 15,
        center: location,
        mapTypeId: 'roadmap'
    });

    const marker = new google.maps.Marker({
        position: location,
        map: map,
        title: '{{ $serviceProvider->name }}'
    });

    @if($serviceProvider->coverage_area && $serviceProvider->coverage_type == 'area_based')
    try {
        const coverageData = @json($serviceProvider->coverage_area);
        if (coverageData && coverageData.coordinates && coverageData.coordinates[0]) {
            const coordinates = coverageData.coordinates[0].map(coord => ({
                lat: coord.lat,
                lng: coord.lng
            }));

            const polygon = new google.maps.Polygon({
                paths: coordinates,
                fillColor: '#4F46E5',
                fillOpacity: 0.3,
                strokeColor: '#4F46E5',
                strokeWeight: 2
            });

            polygon.setMap(map);
        }
    } catch (e) {
        console.error('Error loading coverage area:', e);
    }
    @endif
}
</script>
@endif

<script>
async function toggleStatus() {
    try {
        const response = await fetch(`/admin/service-providers/{{ $serviceProvider->id }}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        const data = await response.json();
        
        if (data.success) {
            location.reload();
        } else {
            alert('حدث خطأ أثناء تغيير الحالة');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('حدث خطأ أثناء تغيير الحالة');
    }
}

function openWorkingSchedulesModal() {
    document.getElementById('workingSchedulesModal').classList.remove('hidden');
}

function closeWorkingSchedulesModal() {
    document.getElementById('workingSchedulesModal').classList.add('hidden');
}

function openCoverageModal() {
    document.getElementById('coverageModal').classList.remove('hidden');
}

function closeCoverageModal() {
    document.getElementById('coverageModal').classList.add('hidden');
}

// Close modals when clicking outside
document.getElementById('workingSchedulesModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeWorkingSchedulesModal();
    }
});

document.getElementById('coverageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCoverageModal();
    }
});
</script>
@endpush
