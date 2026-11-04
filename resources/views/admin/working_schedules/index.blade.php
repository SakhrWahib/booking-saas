@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                            <div class="bg-orange-100 p-2 rounded-lg">
                                <i class="fas fa-clock text-orange-600 text-xl"></i>
                            </div>
                            إدارة أوقات العمل
                        </h1>
                        <p class="mt-2 text-gray-600">عرض وإدارة جميع جداول العمل المسجلة في النظام</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('admin.working-schedules.create') }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-plus"></i>
                            إضافة جدول جديد
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Section -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <i class="fas fa-calendar-alt text-yellow-600 text-lg"></i>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm text-gray-600">جداول العمل الكلية</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-check-circle text-green-600 text-lg"></i>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm text-gray-600">الجداول النشطة</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['active'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-star text-blue-600 text-lg"></i>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm text-gray-600">الجداول الافتراضية</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['default'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="bg-purple-100 p-3 rounded-full">
                        <i class="fas fa-users text-purple-600 text-lg"></i>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm text-gray-600">المقدمين المرتبطين</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['linked_providers'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4">
                <form method="GET" action="{{ route('admin.working-schedules.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- البحث -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">البحث</label>
                            <div class="relative">
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}"
                                       placeholder="البحث بالاسم أو رقم الجوال أو رقم العميل" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            </div>
                        </div>

                        <!-- الحالة -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">الحالة</label>
                            <select name="is_active" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                <option value="">جميع الحالات</option>
                                <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>نشط</option>
                                <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>غير نشط</option>
                            </select>
                        </div>

                        <!-- من تاريخ -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">من تاريخ</label>
                            <input type="date" 
                                   name="date_from" 
                                   value="{{ request('date_from') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        </div>

                        <!-- إلى تاريخ -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">إلى تاريخ</label>
                            <input type="date" 
                                   name="date_to" 
                                   value="{{ request('date_to') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-search"></i>
                            بحث
                        </button>
                        <a href="{{ route('admin.working-schedules.index') }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-times"></i>
                            إعادة تعيين
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">قائمة جداول العمل</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">اسم الجدول</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">من تاريخ</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">إلى تاريخ</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المقدمين المرتبطين</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($workingSchedules as $schedule)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="bg-orange-100 p-2 rounded-lg mr-3">
                                        <i class="fas fa-clock text-orange-600"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 flex items-center gap-2">
                                            {{ $schedule->schedule_name }}
                                            @if($schedule->is_default)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-star mr-1"></i>
                                                    افتراضي
                                                </span>
                                            @endif
                                        </div>
                                        @if($schedule->description)
                                            <div class="text-sm text-gray-500">{{ Str::limit($schedule->description, 50) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $schedule->start_date->format('Y-m-d') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $schedule->end_date->format('Y-m-d') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $schedule->service_providers_count }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($schedule->is_active)
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
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-reverse space-x-2">
                                    <a href="{{ route('admin.working-schedules.show', $schedule) }}" 
                                       class="text-blue-600 hover:text-blue-900 transition-colors duration-200"
                                       title="عرض">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.working-schedules.edit', $schedule) }}" 
                                       class="text-indigo-600 hover:text-indigo-900 transition-colors duration-200"
                                       title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if(!$schedule->is_default)
                                        <form action="{{ route('admin.working-schedules.set-default', $schedule) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('هل أنت متأكد من تعيين هذا الجدول كافتراضي؟')">
                                            @csrf
                                            <button type="submit" 
                                                    class="text-yellow-600 hover:text-yellow-900 transition-colors duration-200"
                                                    title="تعيين كافتراضي">
                                                <i class="fas fa-star"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.working-schedules.duplicate', $schedule) }}" 
                                          method="POST" 
                                          class="inline"
                                          onsubmit="return confirm('هل تريد نسخ هذا الجدول؟')">
                                        @csrf
                                        <button type="submit" 
                                                class="text-green-600 hover:text-green-900 transition-colors duration-200"
                                                title="نسخ">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.working-schedules.toggle-status', $schedule) }}" 
                                          method="POST" 
                                          class="inline"
                                          onsubmit="return confirm('هل أنت متأكد من تغيير حالة الجدول؟')">
                                        @csrf
                                        <button type="submit" 
                                                class="{{ $schedule->is_active ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900' }} transition-colors duration-200"
                                                title="{{ $schedule->is_active ? 'إلغاء تفعيل' : 'تفعيل' }}">
                                            <i class="fas {{ $schedule->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                                        </button>
                                    </form>
                                    @if($schedule->service_providers_count == 0)
                                        <form action="{{ route('admin.working-schedules.destroy', $schedule) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('هل أنت متأكد من حذف هذا الجدول؟ هذا الإجراء لا يمكن التراجع عنه.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900 transition-colors duration-200"
                                                    title="حذف">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="bg-gray-100 p-6 rounded-full mb-4">
                                        <i class="fas fa-clock text-gray-400 text-3xl"></i>
                                    </div>
                                    <p class="text-gray-500 text-lg font-medium">لا توجد جداول عمل</p>
                                    <p class="text-gray-400 mt-2">ابدأ بإضافة أول جدول عمل</p>
                                    <a href="{{ route('admin.working-schedules.create') }}" 
                                       class="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                                        <i class="fas fa-plus"></i>
                                        إضافة جدول العمل الأول
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($workingSchedules->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        عرض {{ $workingSchedules->firstItem() }} إلى {{ $workingSchedules->lastItem() }} من {{ $workingSchedules->total() }} جدول
                    </div>
                    <div class="flex items-center space-x-2">
                        {{ $workingSchedules->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
