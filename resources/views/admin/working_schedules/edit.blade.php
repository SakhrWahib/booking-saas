@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('admin.working-schedules.show', $workingSchedule) }}" 
                           class="text-gray-600 hover:text-gray-900 transition-colors duration-200">
                            <i class="fas fa-arrow-right text-xl"></i>
                        </a>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                                <div class="bg-orange-100 p-2 rounded-lg">
                                    <i class="fas fa-edit text-orange-600 text-xl"></i>
                                </div>
                                تعديل جدول العمل
                            </h1>
                            <p class="mt-2 text-gray-600">تعديل جدول العمل: {{ $workingSchedule->schedule_name }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.working-schedules.update', $workingSchedule) }}" method="POST" id="scheduleForm">
            @csrf
            @method('PUT')
            
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-info-circle text-blue-600"></i>
                        المعلومات الأساسية
                    </h3>
                </div>
                <div class="px-6 py-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- اسم الجدول -->
                        <div class="md:col-span-2">
                            <label for="schedule_name" class="block text-sm font-medium text-gray-700 mb-2">
                                اسم الجدول <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="schedule_name" 
                                   name="schedule_name" 
                                   value="{{ old('schedule_name', $workingSchedule->schedule_name) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('schedule_name') border-red-500 @enderror" 
                                   placeholder="مثال: جدول العمل الأساسي"
                                   required>
                            @error('schedule_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- من تاريخ -->
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                                من تاريخ <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   id="start_date" 
                                   name="start_date" 
                                   value="{{ old('start_date', $workingSchedule->start_date->format('Y-m-d')) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('start_date') border-red-500 @enderror" 
                                   required>
                            @error('start_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- إلى تاريخ -->
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                                إلى تاريخ <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   id="end_date" 
                                   name="end_date" 
                                   value="{{ old('end_date', $workingSchedule->end_date->format('Y-m-d')) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('end_date') border-red-500 @enderror" 
                                   required>
                            @error('end_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- الوصف -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            الوصف
                        </label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="3" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror" 
                                  placeholder="وصف اختياري لجدول العمل">{{ old('description', $workingSchedule->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- الخيارات -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="is_active" 
                                   name="is_active" 
                                   value="1" 
                                   {{ old('is_active', $workingSchedule->is_active) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_active" class="mr-3 text-sm font-medium text-gray-700">
                                جدول نشط
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="is_default" 
                                   name="is_default" 
                                   value="1" 
                                   {{ old('is_default', $workingSchedule->is_default) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_default" class="mr-3 text-sm font-medium text-gray-700">
                                جدول افتراضي
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Working Days Schedule -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-calendar-week text-orange-600"></i>
                        جدول أيام العمل
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">حدد أيام العمل والأوقات لكل يوم</p>
                </div>
                <div class="px-6 py-6">
                    <div class="space-y-4">
                        @foreach($weekDays as $dayKey => $dayName)
                        @php
                            $daySchedule = old("active_days.{$dayKey}", $workingSchedule->active_days[$dayKey] ?? null);
                            $isActive = $daySchedule && ($daySchedule['active'] ?? false);
                        @endphp
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex flex-col lg:flex-row lg:items-center gap-4">
                                <!-- Day Toggle -->
                                <div class="flex items-center min-w-0 lg:w-32">
                                    <input type="checkbox" 
                                           id="day_{{ $dayKey }}" 
                                           name="active_days[{{ $dayKey }}][active]" 
                                           value="1"
                                           {{ $isActive ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded day-toggle"
                                           data-day="{{ $dayKey }}">
                                    <label for="day_{{ $dayKey }}" class="mr-3 text-sm font-medium text-gray-900">
                                        {{ $dayName }}
                                    </label>
                                </div>

                                <!-- Time Inputs -->
                                <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-4" id="times_{{ $dayKey }}" style="{{ $isActive ? 'display: block;' : 'display: none;' }}">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">وقت البداية</label>
                                        <input type="time" 
                                               name="active_days[{{ $dayKey }}][start_time]" 
                                               value="{{ old("active_days.{$dayKey}.start_time", $daySchedule['start_time'] ?? '09:00') }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">وقت النهاية</label>
                                        <input type="time" 
                                               name="active_days[{{ $dayKey }}][end_time]" 
                                               value="{{ old("active_days.{$dayKey}.end_time", $daySchedule['end_time'] ?? '17:00') }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Quick Presets -->
                    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                        <h4 class="text-sm font-medium text-blue-900 mb-3">إعدادات سريعة:</h4>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" 
                                    class="preset-btn bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs"
                                    data-preset="weekdays">
                                أيام الأسبوع (السبت-الخميس)
                            </button>
                            <button type="button" 
                                    class="preset-btn bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs"
                                    data-preset="weekend">
                                نهاية الأسبوع (الجمعة-السبت)
                            </button>
                            <button type="button" 
                                    class="preset-btn bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs"
                                    data-preset="everyday">
                                كل يوم
                            </button>
                            <button type="button" 
                                    class="preset-btn bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded text-xs"
                                    data-preset="clear">
                                مسح الكل
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Holidays Section -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-calendar-times text-red-600"></i>
                        أيام العطل
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">إضافة أيام العطل الثابتة (اختياري)</p>
                </div>
                <div class="px-6 py-6">
                    <div id="holidays-container" class="space-y-3">
                        @if(old('holidays') || $workingSchedule->holidays)
                            @foreach(old('holidays', $workingSchedule->holidays ?? []) as $holiday)
                                <div class="flex items-center gap-3">
                                    <input type="date" 
                                           name="holidays[]" 
                                           value="{{ $holiday }}"
                                           class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    <button type="button" 
                                            class="remove-holiday bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-md text-sm transition-colors duration-200">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <button type="button" 
                            id="add-holiday" 
                            class="mt-4 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                        <i class="fas fa-plus"></i>
                        إضافة يوم عطلة
                    </button>
                </div>
            </div>

            <!-- Vacation Periods Section -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-umbrella-beach text-purple-600"></i>
                        فترات الإجازة
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">إضافة فترات إجازة (مثل عيد الأضحى، عيد الفطر، إجازات أخرى)</p>
                </div>
                <div class="px-6 py-6">
                    <div id="vacation-periods-container" class="space-y-4">
                        @if(old('vacation_periods') || $workingSchedule->vacation_periods)
                            @foreach(old('vacation_periods', $workingSchedule->vacation_periods ?? []) as $index => $period)
                                <div class="border border-gray-300 rounded-lg p-4 vacation-period-item">
                                    <div class="flex items-center justify-between mb-3">
                                        <h4 class="text-sm font-medium text-gray-900">فترة إجازة {{ $index + 1 }}</h4>
                                        <button type="button" 
                                                class="remove-vacation-period bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs transition-colors duration-200">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    <input type="hidden" name="vacation_periods[{{ $index }}][id]" value="{{ $period['id'] ?? '' }}">
                                    <input type="hidden" name="vacation_periods[{{ $index }}][created_at]" value="{{ $period['created_at'] ?? '' }}">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">اسم الإجازة</label>
                                            <input type="text" 
                                                   name="vacation_periods[{{ $index }}][name]" 
                                                   value="{{ $period['name'] ?? '' }}"
                                                   placeholder="مثال: عيد الأضحى"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">وصف الإجازة</label>
                                            <input type="text" 
                                                   name="vacation_periods[{{ $index }}][description]" 
                                                   value="{{ $period['description'] ?? '' }}"
                                                   placeholder="وصف اختياري"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">من تاريخ <span class="text-red-500">*</span></label>
                                            <input type="date" 
                                                   name="vacation_periods[{{ $index }}][start_date]" 
                                                   value="{{ $period['start_date'] ?? '' }}"
                                                   required
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">إلى تاريخ <span class="text-red-500">*</span></label>
                                            <input type="date" 
                                                   name="vacation_periods[{{ $index }}][end_date]" 
                                                   value="{{ $period['end_date'] ?? '' }}"
                                                   required
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <button type="button" 
                            id="add-vacation-period" 
                            class="mt-4 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                        <i class="fas fa-plus"></i>
                        إضافة فترة إجازة
                    </button>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 px-6 py-4">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="text-sm text-gray-600">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        تأكد من صحة البيانات قبل الحفظ
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                        <a href="{{ route('admin.working-schedules.show', $workingSchedule) }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-times"></i>
                            إلغاء
                        </a>
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-save"></i>
                            حفظ التغييرات
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Day toggle functionality
    document.querySelectorAll('.day-toggle').forEach(function(checkbox) {
        const day = checkbox.dataset.day;
        const timesContainer = document.getElementById('times_' + day);
        
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                timesContainer.style.display = 'block';
            } else {
                timesContainer.style.display = 'none';
            }
        });
    });

    // Preset functionality
    document.querySelectorAll('.preset-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const preset = this.dataset.preset;
            
            // Clear all first
            document.querySelectorAll('.day-toggle').forEach(function(cb) {
                cb.checked = false;
                const day = cb.dataset.day;
                document.getElementById('times_' + day).style.display = 'none';
            });
            
            let daysToActivate = [];
            
            switch(preset) {
                case 'weekdays':
                    daysToActivate = ['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday'];
                    break;
                case 'weekend':
                    daysToActivate = ['friday', 'saturday'];
                    break;
                case 'everyday':
                    daysToActivate = ['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
                    break;
                case 'clear':
                    return;
            }
            
            daysToActivate.forEach(function(day) {
                const checkbox = document.getElementById('day_' + day);
                if (checkbox) {
                    checkbox.checked = true;
                    document.getElementById('times_' + day).style.display = 'block';
                }
            });
        });
    });

    // Holiday management
    document.getElementById('add-holiday').addEventListener('click', function() {
        const container = document.getElementById('holidays-container');
        const holidayDiv = document.createElement('div');
        holidayDiv.className = 'flex items-center gap-3';
        holidayDiv.innerHTML = `
            <input type="date" 
                   name="holidays[]" 
                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
            <button type="button" 
                    class="remove-holiday bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-md text-sm transition-colors duration-200">
                <i class="fas fa-trash"></i>
            </button>
        `;
        container.appendChild(holidayDiv);
        
        // Add remove functionality
        holidayDiv.querySelector('.remove-holiday').addEventListener('click', function() {
            holidayDiv.remove();
        });
    });

    // Existing remove holiday buttons
    document.querySelectorAll('.remove-holiday').forEach(function(btn) {
        btn.addEventListener('click', function() {
            this.closest('.flex.items-center.gap-3').remove();
        });
    });

    // Vacation Periods management
    let vacationPeriodCount = document.querySelectorAll('.vacation-period-item').length;
    
    document.getElementById('add-vacation-period').addEventListener('click', function() {
        const container = document.getElementById('vacation-periods-container');
        const vacationDiv = document.createElement('div');
        vacationDiv.className = 'border border-gray-300 rounded-lg p-4 vacation-period-item';
        vacationDiv.innerHTML = `
            <div class="flex items-center justify-between mb-3">
                <h4 class="text-sm font-medium text-gray-900">فترة إجازة ${vacationPeriodCount + 1}</h4>
                <button type="button" 
                        class="remove-vacation-period bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs transition-colors duration-200">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">اسم الإجازة</label>
                    <input type="text" 
                           name="vacation_periods[${vacationPeriodCount}][name]" 
                           placeholder="مثال: عيد الأضحى"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">وصف الإجازة</label>
                    <input type="text" 
                           name="vacation_periods[${vacationPeriodCount}][description]" 
                           placeholder="وصف اختياري"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">من تاريخ <span class="text-red-500">*</span></label>
                    <input type="date" 
                           name="vacation_periods[${vacationPeriodCount}][start_date]" 
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">إلى تاريخ <span class="text-red-500">*</span></label>
                    <input type="date" 
                           name="vacation_periods[${vacationPeriodCount}][end_date]" 
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm">
                </div>
            </div>
        `;
        container.appendChild(vacationDiv);
        vacationPeriodCount++;
        
        // Add remove functionality
        vacationDiv.querySelector('.remove-vacation-period').addEventListener('click', function() {
            vacationDiv.remove();
        });
        
        // Add date validation
        const startDateInput = vacationDiv.querySelector('input[name*="[start_date]"]');
        const endDateInput = vacationDiv.querySelector('input[name*="[end_date]"]');
        
        startDateInput.addEventListener('change', function() {
            endDateInput.min = this.value;
        });
        
        endDateInput.addEventListener('change', function() {
            if (this.value && startDateInput.value && this.value < startDateInput.value) {
                alert('تاريخ النهاية يجب أن يكون بعد تاريخ البداية');
                this.value = '';
            }
        });
    });

    // Existing remove vacation period buttons
    document.querySelectorAll('.remove-vacation-period').forEach(function(btn) {
        btn.addEventListener('click', function() {
            this.closest('.vacation-period-item').remove();
        });
    });

    // Form validation
    document.getElementById('scheduleForm').addEventListener('submit', function(e) {
        const hasActiveDay = Array.from(document.querySelectorAll('.day-toggle')).some(cb => cb.checked);
        
        if (!hasActiveDay) {
            e.preventDefault();
            alert('يجب تحديد يوم واحد على الأقل للعمل');
            return false;
        }
        
        // Validate times for active days
        let isValid = true;
        document.querySelectorAll('.day-toggle:checked').forEach(function(checkbox) {
            const day = checkbox.dataset.day;
            const startTime = document.querySelector(`input[name="active_days[${day}][start_time]"]`).value;
            const endTime = document.querySelector(`input[name="active_days[${day}][end_time]"]`).value;
            
            if (!startTime || !endTime) {
                isValid = false;
                alert(`يجب تحديد وقت البداية والنهاية لـ ${day}`);
                return false;
            }
            
            if (startTime >= endTime) {
                isValid = false;
                alert(`وقت النهاية يجب أن يكون بعد وقت البداية في ${day}`);
                return false;
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            return false;
        }
    });
});
</script>
@endpush
@endsection
