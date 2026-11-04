@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
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
                                <div class="bg-blue-100 p-2 rounded-lg">
                                    <i class="fas fa-clock text-blue-600 text-xl"></i>
                                </div>
                                إدارة الأوقات المتاحة
                            </h1>
                            <p class="mt-2 text-gray-600">جدول العمل: {{ $workingSchedule->schedule_name }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="button" 
                                id="add-time-slot-btn"
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-plus"></i>
                            إضافة وقت جديد
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Time Slots Management Form -->
        <form id="timeSlotsForm" action="{{ route('admin.working-schedules.update-time-slots', $workingSchedule) }}" method="POST">
            @csrf
            
            <!-- Morning Times -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-sun text-yellow-500"></i>
                        الصباح
                    </h3>
                </div>
                <div class="px-6 py-6">
                    <div id="morning-slots" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @if(isset($timeSlotsByPeriod['morning']))
                            @foreach($timeSlotsByPeriod['morning'] as $timeSlot)
                                <div class="time-slot-item border border-gray-300 rounded-lg p-4" data-id="{{ $timeSlot->id }}">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-grip-vertical text-gray-400 cursor-move"></i>
                                            <span class="font-medium text-gray-900">{{ $timeSlot->formatted_time }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" 
                                                       name="time_slots[{{ $loop->index }}][is_active]" 
                                                       value="1"
                                                       {{ $timeSlot->is_active ? 'checked' : '' }}
                                                       class="form-checkbox h-4 w-4 text-blue-600">
                                                <span class="ml-2 text-sm text-gray-600">نشط</span>
                                            </label>
                                            <button type="button" 
                                                    class="delete-time-slot text-red-600 hover:text-red-800 transition-colors duration-200"
                                                    data-id="{{ $timeSlot->id }}">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="time_slots[{{ $loop->index }}][id]" value="{{ $timeSlot->id }}">
                                    <input type="hidden" name="time_slots[{{ $loop->index }}][period]" value="morning">
                                    <div class="grid grid-cols-1 gap-3">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">الوقت</label>
                                            <input type="time" 
                                                   name="time_slots[{{ $loop->index }}][time]" 
                                                   value="{{ $timeSlot->formatted_time }}"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">تسمية إضافية</label>
                                            <input type="text" 
                                                   name="time_slots[{{ $loop->index }}][label]" 
                                                   value="{{ $timeSlot->label }}"
                                                   placeholder="اختياري"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <!-- Afternoon Times -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-sun text-orange-500"></i>
                        بعد الظهر
                    </h3>
                </div>
                <div class="px-6 py-6">
                    <div id="afternoon-slots" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @if(isset($timeSlotsByPeriod['afternoon']))
                            @foreach($timeSlotsByPeriod['afternoon'] as $timeSlot)
                                <div class="time-slot-item border border-gray-300 rounded-lg p-4" data-id="{{ $timeSlot->id }}">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-grip-vertical text-gray-400 cursor-move"></i>
                                            <span class="font-medium text-gray-900">{{ $timeSlot->formatted_time }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" 
                                                       name="time_slots[{{ $loop->index + (isset($timeSlotsByPeriod['morning']) ? count($timeSlotsByPeriod['morning']) : 0) }}][is_active]" 
                                                       value="1"
                                                       {{ $timeSlot->is_active ? 'checked' : '' }}
                                                       class="form-checkbox h-4 w-4 text-blue-600">
                                                <span class="ml-2 text-sm text-gray-600">نشط</span>
                                            </label>
                                            <button type="button" 
                                                    class="delete-time-slot text-red-600 hover:text-red-800 transition-colors duration-200"
                                                    data-id="{{ $timeSlot->id }}">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="time_slots[{{ $loop->index + (isset($timeSlotsByPeriod['morning']) ? count($timeSlotsByPeriod['morning']) : 0) }}][id]" value="{{ $timeSlot->id }}">
                                    <input type="hidden" name="time_slots[{{ $loop->index + (isset($timeSlotsByPeriod['morning']) ? count($timeSlotsByPeriod['morning']) : 0) }}][period]" value="afternoon">
                                    <div class="grid grid-cols-1 gap-3">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">الوقت</label>
                                            <input type="time" 
                                                   name="time_slots[{{ $loop->index + (isset($timeSlotsByPeriod['morning']) ? count($timeSlotsByPeriod['morning']) : 0) }}][time]" 
                                                   value="{{ $timeSlot->formatted_time }}"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">تسمية إضافية</label>
                                            <input type="text" 
                                                   name="time_slots[{{ $loop->index + (isset($timeSlotsByPeriod['morning']) ? count($timeSlotsByPeriod['morning']) : 0) }}][label]" 
                                                   value="{{ $timeSlot->label }}"
                                                   placeholder="اختياري"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <!-- Evening Times -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-moon text-purple-500"></i>
                        المساء
                    </h3>
                </div>
                <div class="px-6 py-6">
                    <div id="evening-slots" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @if(isset($timeSlotsByPeriod['evening']))
                            @foreach($timeSlotsByPeriod['evening'] as $timeSlot)
                                <div class="time-slot-item border border-gray-300 rounded-lg p-4" data-id="{{ $timeSlot->id }}">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-grip-vertical text-gray-400 cursor-move"></i>
                                            <span class="font-medium text-gray-900">{{ $timeSlot->formatted_time }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" 
                                                       name="time_slots[{{ $loop->index + (isset($timeSlotsByPeriod['morning']) ? count($timeSlotsByPeriod['morning']) : 0) + (isset($timeSlotsByPeriod['afternoon']) ? count($timeSlotsByPeriod['afternoon']) : 0) }}][is_active]" 
                                                       value="1"
                                                       {{ $timeSlot->is_active ? 'checked' : '' }}
                                                       class="form-checkbox h-4 w-4 text-blue-600">
                                                <span class="ml-2 text-sm text-gray-600">نشط</span>
                                            </label>
                                            <button type="button" 
                                                    class="delete-time-slot text-red-600 hover:text-red-800 transition-colors duration-200"
                                                    data-id="{{ $timeSlot->id }}">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="time_slots[{{ $loop->index + (isset($timeSlotsByPeriod['morning']) ? count($timeSlotsByPeriod['morning']) : 0) + (isset($timeSlotsByPeriod['afternoon']) ? count($timeSlotsByPeriod['afternoon']) : 0) }}][id]" value="{{ $timeSlot->id }}">
                                    <input type="hidden" name="time_slots[{{ $loop->index + (isset($timeSlotsByPeriod['morning']) ? count($timeSlotsByPeriod['morning']) : 0) + (isset($timeSlotsByPeriod['afternoon']) ? count($timeSlotsByPeriod['afternoon']) : 0) }}][period]" value="evening">
                                    <div class="grid grid-cols-1 gap-3">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">الوقت</label>
                                            <input type="time" 
                                                   name="time_slots[{{ $loop->index + (isset($timeSlotsByPeriod['morning']) ? count($timeSlotsByPeriod['morning']) : 0) + (isset($timeSlotsByPeriod['afternoon']) ? count($timeSlotsByPeriod['afternoon']) : 0) }}][time]" 
                                                   value="{{ $timeSlot->formatted_time }}"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">تسمية إضافية</label>
                                            <input type="text" 
                                                   name="time_slots[{{ $loop->index + (isset($timeSlotsByPeriod['morning']) ? count($timeSlotsByPeriod['morning']) : 0) + (isset($timeSlotsByPeriod['afternoon']) ? count($timeSlotsByPeriod['afternoon']) : 0) }}][label]" 
                                                   value="{{ $timeSlot->label }}"
                                                   placeholder="اختياري"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 px-6 py-4">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="text-sm text-gray-600">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        يمكنك سحب وإسقاط الأوقات لإعادة ترتيبها
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

<!-- Add Time Slot Modal -->
<div id="addTimeSlotModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">إضافة وقت جديد</h3>
            </div>
            <form id="addTimeSlotForm">
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">الوقت</label>
                        <input type="time" 
                               name="time" 
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">الفترة</label>
                        <select name="period" 
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                            <option value="morning">الصباح</option>
                            <option value="afternoon">بعد الظهر</option>
                            <option value="evening">المساء</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">تسمية إضافية</label>
                        <input type="text" 
                               name="label" 
                               placeholder="اختياري"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                    <button type="button" 
                            id="cancelAddTimeSlot"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                        إلغاء
                    </button>
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                        إضافة
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add Time Slot Modal
    const addTimeSlotBtn = document.getElementById('add-time-slot-btn');
    const addTimeSlotModal = document.getElementById('addTimeSlotModal');
    const cancelAddTimeSlot = document.getElementById('cancelAddTimeSlot');
    const addTimeSlotForm = document.getElementById('addTimeSlotForm');

    addTimeSlotBtn.addEventListener('click', function() {
        addTimeSlotModal.classList.remove('hidden');
    });

    cancelAddTimeSlot.addEventListener('click', function() {
        addTimeSlotModal.classList.add('hidden');
        addTimeSlotForm.reset();
    });

    // Close modal when clicking outside
    addTimeSlotModal.addEventListener('click', function(e) {
        if (e.target === addTimeSlotModal) {
            addTimeSlotModal.classList.add('hidden');
            addTimeSlotForm.reset();
        }
    });

    // Add Time Slot Form Submission
    addTimeSlotForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('{{ route("admin.working-schedules.add-time-slot", $workingSchedule) }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'حدث خطأ أثناء إضافة الوقت');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء إضافة الوقت');
        });
    });

    // Delete Time Slot
    document.querySelectorAll('.delete-time-slot').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const timeSlotId = this.dataset.id;
            
            if (confirm('هل أنت متأكد من حذف هذا الوقت؟')) {
                fetch(`{{ route("admin.working-schedules.show", $workingSchedule) }}/time-slots/${timeSlotId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message || 'حدث خطأ أثناء حذف الوقت');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('حدث خطأ أثناء حذف الوقت');
                });
            }
        });
    });

    // Form validation
    document.getElementById('timeSlotsForm').addEventListener('submit', function(e) {
        const timeInputs = document.querySelectorAll('input[type="time"]');
        let validTimeSlots = [];
        
        timeInputs.forEach(function(input) {
            if (input.value) {
                validTimeSlots.push(input);
                input.classList.remove('border-red-500');
            } else {
                // إزالة الحقول الفارغة من النموذج
                const parentDiv = input.closest('.time-slot-item');
                if (parentDiv) {
                    parentDiv.remove();
                }
            }
        });
        
        // التحقق من وجود وقت واحد على الأقل
        if (validTimeSlots.length === 0) {
            e.preventDefault();
            alert('يجب إضافة وقت واحد على الأقل');
            return false;
        }
    });
});
</script>
@endpush
@endsection 