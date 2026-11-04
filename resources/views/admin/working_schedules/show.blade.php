@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('admin.working-schedules.index') }}" 
                           class="text-gray-600 hover:text-gray-900 transition-colors duration-200">
                            <i class="fas fa-arrow-right text-xl"></i>
                        </a>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                                <div class="bg-orange-100 p-2 rounded-lg">
                                    <i class="fas fa-clock text-orange-600 text-xl"></i>
                                </div>
                                {{ $workingSchedule->schedule_name }}
                                @if($workingSchedule->is_default)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-star mr-1"></i>
                                        افتراضي
                                    </span>
                                @endif
                            </h1>
                            @if($workingSchedule->description)
                                <p class="mt-2 text-gray-600">{{ $workingSchedule->description }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('admin.working-schedules.manage-time-slots', $workingSchedule) }}" 
                           class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-clock"></i>
                            إدارة الأوقات
                        </a>
                        <a href="{{ route('admin.working-schedules.edit', $workingSchedule) }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-edit"></i>
                            تعديل الجدول
                        </a>
                        @if(!$workingSchedule->is_default)
                            <form action="{{ route('admin.working-schedules.set-default', $workingSchedule) }}" 
                                  method="POST" 
                                  class="inline"
                                  onsubmit="return confirm('هل أنت متأكد من تعيين هذا الجدول كافتراضي؟')">
                                @csrf
                                <button type="submit" 
                                        class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                                    <i class="fas fa-star"></i>
                                    تعيين كافتراضي
                                </button>
                            </form>
                        @endif
                        <form action="{{ route('admin.working-schedules.duplicate', $workingSchedule) }}" 
                              method="POST" 
                              class="inline"
                              onsubmit="return confirm('هل تريد نسخ هذا الجدول؟')">
                            @csrf
                            <button type="submit" 
                                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                                <i class="fas fa-copy"></i>
                                نسخ الجدول
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Section -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-users text-blue-600 text-lg"></i>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm text-gray-600">المقدمين المرتبطين</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['linked_providers'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-user-check text-green-600 text-lg"></i>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm text-gray-600">المقدمين النشطين</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['active_providers'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="bg-orange-100 p-3 rounded-full">
                        <i class="fas fa-calendar-day text-orange-600 text-lg"></i>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm text-gray-600">أيام العمل</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['working_days'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="bg-red-100 p-3 rounded-full">
                        <i class="fas fa-calendar-times text-red-600 text-lg"></i>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm text-gray-600">أيام العطل</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['holidays_count'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-clock text-green-600 text-lg"></i>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm text-gray-600">الأوقات المتاحة</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['time_slots_count'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-info-circle text-blue-600"></i>
                        المعلومات الأساسية
                    </h3>
                </div>
                <div class="px-6 py-6 space-y-4">
                    <div class="grid grid-cols-1 gap-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">اسم الجدول</span>
                            <span class="text-sm font-medium text-gray-900">{{ $workingSchedule->schedule_name }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">من تاريخ</span>
                            <span class="text-sm font-medium text-gray-900">{{ $workingSchedule->start_date->format('Y-m-d') }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">إلى تاريخ</span>
                            <span class="text-sm font-medium text-gray-900">{{ $workingSchedule->end_date->format('Y-m-d') }}</span>
                        </div>
                        

                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">الحالة</span>
                            @if($workingSchedule->is_active)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <span class="w-2 h-2 bg-green-400 rounded-full mr-1"></span>
                                    نشط
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <span class="w-2 h-2 bg-red-400 rounded-full mr-1"></span>
                                    غير نشط
                                </span>
                            @endif
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">جدول افتراضي</span>
                            @if($workingSchedule->is_default)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-star mr-1"></i>
                                    نعم
                                </span>
                            @else
                                <span class="text-sm text-gray-900">لا</span>
                            @endif
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">تاريخ الإنشاء</span>
                            <span class="text-sm font-medium text-gray-900">{{ $workingSchedule->created_at->format('Y-m-d H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Working Days Schedule -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-calendar-week text-orange-600"></i>
                        جدول أيام العمل
                    </h3>
                </div>
                <div class="px-6 py-6">
                    <div class="space-y-3">
                        @foreach($weekDays as $dayKey => $dayName)
                            @php
                                $daySchedule = $workingSchedule->active_days[$dayKey] ?? null;
                                $isActive = $daySchedule && $daySchedule['active'];
                            @endphp
                            <div class="flex items-center justify-between p-3 rounded-lg {{ $isActive ? 'bg-green-50 border border-green-200' : 'bg-gray-50 border border-gray-200' }}">
                                <div class="flex items-center gap-3">
                                    @if($isActive)
                                        <i class="fas fa-check-circle text-green-600"></i>
                                    @else
                                        <i class="fas fa-times-circle text-gray-400"></i>
                                    @endif
                                    <span class="text-sm font-medium {{ $isActive ? 'text-green-900' : 'text-gray-500' }}">
                                        {{ $dayName }}
                                    </span>
                                </div>
                                <div class="text-sm {{ $isActive ? 'text-green-700' : 'text-gray-500' }}">
                                    @if($isActive)
                                        {{ $daySchedule['start_time'] }} - {{ $daySchedule['end_time'] }}
                                    @else
                                        غير نشط
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Holidays Section -->
        @if($workingSchedule->holidays && count($workingSchedule->holidays) > 0)
        <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-calendar-times text-red-600"></i>
                    أيام العطل
                </h3>
            </div>
            <div class="px-6 py-6">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                    @foreach($workingSchedule->holidays as $holiday)
                        <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-center">
                            <div class="text-sm font-medium text-red-900">
                                {{ \Carbon\Carbon::parse($holiday)->format('Y-m-d') }}
                            </div>
                            <div class="text-xs text-red-600 mt-1">
                                {{ \Carbon\Carbon::parse($holiday)->translatedFormat('l') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Linked Service Providers -->
        @if($workingSchedule->serviceProviders->count() > 0)
        <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-users text-blue-600"></i>
                        مقدمو الخدمة المرتبطون
                    </h3>
                    <span class="text-sm text-gray-600">
                        {{ $workingSchedule->serviceProviders->count() }} مقدم خدمة
                    </span>
                </div>
            </div>
            <div class="px-6 py-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($workingSchedule->serviceProviders as $provider)
                        <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors duration-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="bg-green-100 p-2 rounded-lg">
                                        <i class="fas fa-user-tie text-green-600"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $provider->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $provider->phone }}</div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if($provider->is_active)
                                        <span class="w-2 h-2 bg-green-400 rounded-full"></span>
                                    @else
                                        <span class="w-2 h-2 bg-red-400 rounded-full"></span>
                                    @endif
                                    <a href="{{ route('admin.service-providers.show', $provider) }}" 
                                       class="text-blue-600 hover:text-blue-900 transition-colors duration-200">
                                        <i class="fas fa-external-link-alt text-xs"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if($workingSchedule->serviceProviders->count() >= 10)
                    <div class="mt-4 text-center">
                        <a href="{{ route('admin.service-providers.index', ['working_schedule_id' => $workingSchedule->id]) }}" 
                           class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                            عرض جميع مقدمي الخدمة المرتبطين
                        </a>
                    </div>
                @endif
            </div>
        </div>
        @else
        <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-12 text-center">
                <div class="flex flex-col items-center justify-center">
                    <div class="bg-gray-100 p-6 rounded-full mb-4">
                        <i class="fas fa-users text-gray-400 text-3xl"></i>
                    </div>
                    <p class="text-gray-500 text-lg font-medium">لا يوجد مقدمو خدمة مرتبطون</p>
                    <p class="text-gray-400 mt-2">لم يتم ربط أي مقدم خدمة بهذا الجدول بعد</p>
                    <a href="{{ route('admin.service-providers.create') }}" 
                       class="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                        <i class="fas fa-plus"></i>
                        إضافة مقدم خدمة جديد
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200 px-6 py-4">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="text-sm text-gray-600">
                    <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                    آخر تحديث: {{ $workingSchedule->updated_at->format('Y-m-d H:i') }}
                </div>
                <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                    <form action="{{ route('admin.working-schedules.toggle-status', $workingSchedule) }}" 
                          method="POST" 
                          class="inline"
                          onsubmit="return confirm('هل أنت متأكد من تغيير حالة الجدول؟')">
                        @csrf
                        <button type="submit" 
                                class="{{ $workingSchedule->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                            <i class="fas {{ $workingSchedule->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                            {{ $workingSchedule->is_active ? 'إلغاء تفعيل' : 'تفعيل' }}
                        </button>
                    </form>
                    @if($workingSchedule->serviceProviders->count() == 0)
                        <form action="{{ route('admin.working-schedules.destroy', $workingSchedule) }}" 
                              method="POST" 
                              class="inline"
                              onsubmit="return confirm('هل أنت متأكد من حذف هذا الجدول؟ هذا الإجراء لا يمكن التراجع عنه.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                                <i class="fas fa-trash"></i>
                                حذف الجدول
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('admin.working-schedules.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                        <i class="fas fa-list"></i>
                        العودة للقائمة
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
