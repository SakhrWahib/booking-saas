<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>اختيار الموعد - {{ config('brand.name') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('css/frontend-styles.css') }}">
    <style>
        body { font-family: 'Cairo', sans-serif; }
        .gradient-bg {
            background: linear-gradient(135deg, #ec4899 0%, #a855f7 100%);
        }
        
        /* Calendar Styles */
        .calendar-day {
            @apply p-3 text-center text-sm font-medium cursor-pointer transition-all duration-200 border border-transparent;
        }
        
        .calendar-day.other-month {
            @apply text-gray-300 cursor-default;
        }
        
        .calendar-day.today {
            @apply bg-blue-100 text-blue-700 border-blue-300 font-semibold;
        }
        
        .calendar-day.past {
            @apply text-gray-400 bg-gray-100 cursor-not-allowed opacity-50;
            text-decoration: line-through;
        }
        
        .calendar-day.available {
            @apply text-gray-700 hover:bg-gray-100 hover:border-gray-300;
        }
        
        .calendar-day.selected {
            @apply bg-gradient-to-r from-pink-600 to-purple-600 text-white border-pink-600 font-semibold;
            box-shadow: 0 4px 12px rgba(236, 72, 153, 0.4);
            transform: scale(1.05);
            animation: selected-date-pulse 2s infinite;
        }
        
        @keyframes selected-date-pulse {
            0%, 100% {
                box-shadow: 0 4px 12px rgba(236, 72, 153, 0.4);
            }
            50% {
                box-shadow: 0 6px 16px rgba(236, 72, 153, 0.6);
            }
        }
        
        .calendar-day.friday {
            @apply text-red-500 bg-red-100 border-red-300 cursor-not-allowed font-bold;
            position: relative;
            overflow: hidden;
        }
        
        .calendar-day.friday::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: repeating-linear-gradient(
                45deg,
                rgba(255, 0, 0, 0.1),
                rgba(255, 0, 0, 0.1) 5px,
                rgba(255, 255, 255, 0.5) 5px,
                rgba(255, 255, 255, 0.5) 10px
            );
            pointer-events: none;
        }
        
        /* Time Slot Styles */
        .time-slot-btn {
            @apply transition-all duration-300 ease-in-out;
            position: relative;
            overflow: hidden;
        }
        
        .time-slot-btn:hover {
            @apply transform scale-105;
        }
        
        .time-slot-btn.selected {
            @apply bg-gradient-to-r from-pink-600 to-purple-600 text-white border-pink-600 font-bold shadow-xl;
            transform: scale(1.1);
            animation: pulse-glow 2s infinite;
        }
        
        .time-slot-btn.selected::before {
            content: '✓';
            position: absolute;
            top: -5px;
            right: -5px;
            background: #10b981;
            color: white;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
        @keyframes pulse-glow {
            0%, 100% {
                box-shadow: 0 0 20px rgba(236, 72, 153, 0.4);
            }
            50% {
                box-shadow: 0 0 30px rgba(236, 72, 153, 0.6);
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="gradient-bg text-white shadow-lg">
        <div class="container mx-auto px-3 sm:px-4">
            <div class="flex items-center justify-between h-14 sm:h-16">
                <a href="{{ route('home') }}" class="flex items-center gap-2 sm:gap-3 hover:opacity-80 transition-opacity">
                    <div class="bg-white/20 p-1.5 sm:p-2 rounded-full">
                        <i class="fas fa-cat text-xl sm:text-2xl"></i>
                    </div>
                    <h1 class="text-lg sm:text-xl font-bold">{{ config('brand.name') }}</h1>
                </a>
                <a href="{{ route('home') }}" class="text-pink-200 hover:text-white transition-colors text-sm sm:text-base">
                    <i class="fas fa-home ml-1 sm:ml-2"></i>
                    <span class="hidden sm:inline">العودة للرئيسية</span>
                    <span class="sm:hidden">الرئيسية</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Progress Bar -->
    <div class="bg-white border-b">
        <div class="container mx-auto px-3 sm:px-4 py-3 sm:py-4">
            <div class="flex items-center justify-center">
                <div class="flex items-center gap-2 sm:gap-4">
                    <!-- Step 1: Completed -->
                    <div class="flex items-center gap-1 sm:gap-2">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-xs sm:text-sm font-semibold">
                            <i class="fas fa-check text-xs"></i>
                        </div>
                        <span class="text-xs sm:text-sm font-medium text-green-600 hidden sm:inline">اختيار الإضافات</span>
                        <span class="text-xs font-medium text-green-600 sm:hidden">الإضافات</span>
                    </div>
                    
                    <div class="w-4 sm:w-8 h-px bg-green-300"></div>
                    
                    <!-- Step 2: Completed -->
                    <div class="flex items-center gap-1 sm:gap-2">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-xs sm:text-sm font-semibold">
                            <i class="fas fa-check text-xs"></i>
                        </div>
                        <span class="text-xs sm:text-sm font-medium text-green-600 hidden sm:inline">تحديد الموقع</span>
                        <span class="text-xs font-medium text-green-600 sm:hidden">الموقع</span>
                    </div>
                    
                    <div class="w-4 sm:w-8 h-px bg-green-300"></div>
                    
                    <!-- Step 3: Active -->
                    <div class="flex items-center gap-1 sm:gap-2">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-pink-600 text-white rounded-full flex items-center justify-center text-xs sm:text-sm font-semibold">
                            3
                        </div>
                        <span class="text-xs sm:text-sm font-medium text-pink-600 hidden sm:inline">اختيار الموعد</span>
                        <span class="text-xs font-medium text-pink-600 sm:hidden">الموعد</span>
                    </div>
                    
                    <div class="w-4 sm:w-8 h-px bg-gray-300"></div>
                    
                    <!-- Step 4: Pending -->
                    <div class="flex items-center gap-1 sm:gap-2">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gray-300 text-gray-500 rounded-full flex items-center justify-center text-xs sm:text-sm font-semibold">
                            4
                        </div>
                        <span class="text-xs sm:text-sm font-medium text-gray-500 hidden sm:inline">تأكيد الحجز</span>
                        <span class="text-xs font-medium text-gray-500 sm:hidden">التأكيد</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-3 sm:px-4 py-6 sm:py-8">
        <div class="max-w-4xl mx-auto">
            <!-- إعلان التنبيه للحجز في الرياض فقط -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-3 sm:p-4 mb-4 sm:mb-6">
                <div class="flex items-center gap-2 sm:gap-3">
                    <div class="bg-blue-100 p-1.5 sm:p-2 rounded-full">
                        <i class="fas fa-map-marker-alt text-blue-600 text-sm sm:text-base"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-blue-800 mb-1 text-sm sm:text-base">خدمة الحجز متاحة في الرياض فقط</h4>
                        <p class="text-blue-700 text-xs sm:text-sm">نقدم خدماتنا حاليًا في مدينة الرياض.</p>
                    </div>
                </div>
            </div>

            <!-- إعلان نطاق الحجز المسموح -->
            <div class="bg-green-50 border border-green-200 rounded-xl p-3 sm:p-4 mb-4 sm:mb-6">
                <div class="flex items-center gap-2 sm:gap-3">
                    <div class="bg-green-100 p-1.5 sm:p-2 rounded-full">
                        <i class="fas fa-calendar-check text-green-600 text-sm sm:text-base"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-green-800 mb-1 text-sm sm:text-base">نطاق الحجز المسموح</h4>
                        <p class="text-green-700 text-xs sm:text-sm">يمكن الحجز خلال 15 يوم من اليوم الحالي فقط. لا يمكن الحجز في يوم الجمعة.</p>
                        <p class="text-green-600 text-xs mt-1 font-medium" id="bookingRangeInfo">
                            <!-- سيتم تحديث هذا النص بواسطة JavaScript -->
                        </p>
                    </div>
                </div>
            </div>

            <!-- Service Summary -->
            <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 mb-6 sm:mb-8">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-pink-100 to-purple-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-paw text-xl sm:text-2xl text-pink-400"></i>
                    </div>

                    <div class="flex-1">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-800">{{ session('booking.product_name') }}</h2>
                        <p class="text-gray-600 mt-1 text-sm sm:text-base">اختر التاريخ والوقت المناسب لك</p>
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-4 mt-2">
                        
                            @if(session('booking.addons_price', 0) > 0)
                                <span class="bg-purple-100 text-purple-600 px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-semibold">
                                    الإضافات: {{ number_format(session('booking.addons_price', 0), 2) }} ر.س
                                </span>
                            @endif
                            <span class="bg-green-100 text-green-600 px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-semibold">
                                <i class="fas fa-map-marker-alt text-xs ml-1"></i>
                                تم تحديد الموقع
                            </span>
                        </div>
                        </div>
                    </div>
                </div>

            <!-- Selected Add-ons Summary -->
            @if(session('booking.add_ons') && count(session('booking.add_ons')) > 0)
                <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 mb-6 sm:mb-8">
                    <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-3 sm:mb-4 flex items-center gap-2">
                        <i class="fas fa-plus-circle text-purple-600"></i>
                        الإضافات المختارة
                    </h3>
                    <div class="space-y-3">
                        @foreach(session('booking.add_ons') as $addon)
                            <div class="flex items-center justify-between p-2 sm:p-3 bg-purple-50 rounded-lg">
                                <div class="flex items-center gap-2 sm:gap-3">
                                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-plus text-purple-600 text-sm sm:text-base"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-800 text-sm sm:text-base">{{ $addon['name'] }}</h4>
                                        <p class="text-xs sm:text-sm text-gray-600">{{ $addon['option'] }}</p>
                                    </div>
                                </div>
                                <div class="text-left">
                                    <div class="font-semibold text-purple-600 text-sm sm:text-base">
                                        {{ number_format($addon['total'], 2) }} ر.س
                                    </div>
                                    <div class="text-xs sm:text-sm text-gray-500">
                                        {{ $addon['quantity'] }} × {{ number_format($addon['price'], 2) }} ر.س
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="border-t pt-3 mt-3">
                            <div class="flex justify-between items-center">
                                <span class="font-semibold text-gray-800 text-sm sm:text-base">مجموع الإضافات:</span>
                                <span class="font-bold text-purple-600 text-sm sm:text-base">{{ number_format(session('booking.addons_price', 0), 2) }} ر.س</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- DateTime Selection -->
                <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6">
                    <div class="mb-6">
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-800 mb-2 flex items-center gap-2">
                        <i class="fas fa-calendar-alt text-pink-600"></i>
                        اختر التاريخ والوقت
                    </h3>
                    <p class="text-gray-600 text-sm sm:text-base">حدد الموعد المناسب لتقديم الخدمة</p>
                    </div>

                <form action="{{ route('frontend.booking.save-datetime') }}" method="POST" id="datetimeForm">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 lg:gap-8">
                        <!-- Date Selection -->
                        <div>
                            <label for="booking_date" class="block text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4">
                                <i class="fas fa-calendar text-pink-600 ml-2"></i>
                                اختر التاريخ
                            </label>
                            
                            <!-- Hidden Input for Form Submission -->
                            <input type="hidden" id="booking_date" name="date" value="{{ old('date') }}" required>
                            
                            <!-- Custom Calendar Picker -->
                            <div class="custom-calendar-container bg-white border-2 border-gray-300 rounded-xl shadow-sm @error('date') border-red-500 @enderror">
                                <!-- Calendar Header -->
                                <div class="calendar-header bg-gradient-to-r from-pink-600 to-purple-600 text-white p-4 rounded-t-xl flex items-center justify-between">
                                    <button type="button" id="prevMonth" class="p-2 hover:bg-white/20 rounded-lg transition-colors">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                    <h3 class="text-lg font-semibold" id="currentMonthYear"></h3>
                                    <button type="button" id="nextMonth" class="p-2 hover:bg-white/20 rounded-lg transition-colors">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                </div>
                                
                                <!-- Calendar Days Header -->
                                <div class="calendar-days-header grid grid-cols-7 bg-gray-50 border-b">
                                    <div class="p-3 text-center text-sm font-semibold text-gray-600">سبت</div>
                                    <div class="p-3 text-center text-sm font-semibold text-gray-600">أحد</div>
                                    <div class="p-3 text-center text-sm font-semibold text-gray-600">اثنين</div>
                                    <div class="p-3 text-center text-sm font-semibold text-gray-600">ثلاثاء</div>
                                    <div class="p-3 text-center text-sm font-semibold text-gray-600">أربعاء</div>
                                    <div class="p-3 text-center text-sm font-semibold text-gray-600">خميس</div>
                                    <div class="p-3 text-center text-sm font-semibold text-gray-600">جمعة</div>
                                </div>
                                
                                <!-- Calendar Days Grid -->
                                <div id="calendarDays" class="calendar-days grid grid-cols-7 gap-1 p-4">
                                    <!-- Days will be populated by JavaScript -->
                    </div>

                    <!-- Selected Date Display -->
                                <div class="calendar-footer bg-gray-50 p-4 rounded-b-xl border-t">
                                    <div class="flex items-center justify-center">
                                        <span class="text-gray-600" id="selectedDateDisplay">لم يتم اختيار تاريخ</span>
                                    </div>
                                </div>
                            </div>
                            
                            @error('date')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            
                            <!-- Date Validation Message -->
                            <div id="dateValidation" class="mt-2 text-sm"></div>


                    </div>

                        <!-- Time Selection -->
                        <div>
                            <label class="block text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4">
                                <i class="fas fa-clock text-pink-600 ml-2"></i>
                                اختر الوقت
                            </label>

                            <!-- Available Times Container -->
                            <div id="availableTimesContainer" class="min-h-[200px] sm:min-h-[300px]">
                                <div class="text-center text-gray-500 py-6 sm:py-12">
                                    <i class="fas fa-calendar-day text-2xl sm:text-4xl mb-2 sm:mb-4 text-gray-300"></i>
                                    <p class="text-sm sm:text-lg font-medium">اختر التاريخ أولاً</p>
                                    <p class="text-xs sm:text-sm text-gray-400 mt-1">سيتم عرض الأوقات المتاحة بعد اختيار التاريخ</p>
                                </div>
                            </div>

                            <input type="hidden" name="time" id="selectedTime" required>
                            @error('time')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                </div>
            </div>

            <!-- Action Buttons -->
                    <div class="mt-8 flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('frontend.booking.select-location') }}" 
                           class="flex-1 bg-gray-500 text-white py-3 sm:py-4 rounded-xl font-semibold hover:bg-gray-600 transition-colors text-center text-sm sm:text-base">
                            <i class="fas fa-arrow-right ml-2"></i>
                            <span class="hidden sm:inline">العودة لتحديد الموقع</span>
                            <span class="sm:hidden">العودة</span>
                        </a>
                        <button type="submit" 
                                class="flex-1 bg-gradient-to-r from-pink-600 to-purple-600 text-white py-3 sm:py-4 rounded-xl font-semibold hover:from-pink-700 hover:to-purple-700 transition-all disabled:opacity-50 disabled:cursor-not-allowed text-sm sm:text-base"
                                id="submitBtn" 
                                disabled>
                            <i class="fas fa-arrow-left ml-2"></i>
                            <span class="hidden sm:inline">المتابعة لبيانات العميل</span>
                            <span class="sm:hidden">المتابعة</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let selectedTimeSlot = null;
            let currentMonth = new Date().getMonth();
            let currentYear = new Date().getFullYear();
            let selectedDate = null;
            
            // تحديد الحد الأقصى للحجز (15 يوم من اليوم الحالي)
            const MAX_BOOKING_DAYS = 15;

            // Get elements
            const dateInput = document.getElementById('booking_date');
            const timesContainer = document.getElementById('availableTimesContainer');
            const validationDiv = document.getElementById('dateValidation');
            const submitBtn = document.getElementById('submitBtn');
            const selectedTimeInput = document.getElementById('selectedTime');
            const currentMonthYearElement = document.getElementById('currentMonthYear');
            const calendarDaysElement = document.getElementById('calendarDays');
            const selectedDateDisplay = document.getElementById('selectedDateDisplay');
            const prevMonthBtn = document.getElementById('prevMonth');
            const nextMonthBtn = document.getElementById('nextMonth');
        
        // Arabic month names
            const arabicMonths = [
            'يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو',
            'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'
        ];
        
            // Load pre-selected date if exists
            const preSelectedDate = dateInput.value;
            if (preSelectedDate) {
                selectedDate = preSelectedDate;
                const preSelectedDateObj = new Date(preSelectedDate);
                currentMonth = preSelectedDateObj.getMonth();
                currentYear = preSelectedDateObj.getFullYear();
                updateSelectedDateDisplay(preSelectedDate);
            }
        
        // Initialize calendar
            renderCalendar();
            
            // Update booking range info
            updateBookingRangeInfo();

            // Load times for pre-selected date
            if (preSelectedDate) {
                validateDateAndLoadTimes();
            }

            // Navigation event listeners
            prevMonthBtn.addEventListener('click', function() {
                if (currentMonth === 0) {
                    currentMonth = 11;
                    currentYear--;
                } else {
                    currentMonth--;
                }
                renderCalendar();
            });

            nextMonthBtn.addEventListener('click', function() {
                if (currentMonth === 11) {
                    currentMonth = 0;
                    currentYear++;
                } else {
                    currentMonth++;
                }
                renderCalendar();
            });

            // Date change handler (now from calendar selection)
            function onDateSelected(dateStr) {
                selectedDate = dateStr;
                dateInput.value = dateStr;
                updateSelectedDateDisplay(dateStr);
                validateDateAndLoadTimes();
            }

            // Render calendar function
            function renderCalendar() {
                // Update header
                currentMonthYearElement.textContent = `${arabicMonths[currentMonth]} ${currentYear}`;
                
                // Clear previous calendar
                calendarDaysElement.innerHTML = '';
                
                // Get first day of month and number of days
                const firstDay = new Date(currentYear, currentMonth, 1);
                const lastDay = new Date(currentYear, currentMonth + 1, 0);
            const daysInMonth = lastDay.getDate();
                const startingDayOfWeek = firstDay.getDay(); // 0 = Sunday, 6 = Saturday
                
                // Previous month's ending days
                const prevMonth = new Date(currentYear, currentMonth, 0);
                const daysInPrevMonth = prevMonth.getDate();
                
                // Calculate starting offset (Saturday = 0 in our Arabic calendar)
                const offset = (startingDayOfWeek + 1) % 7;
                
                // Add previous month's days
                for (let i = offset - 1; i >= 0; i--) {
                    const day = daysInPrevMonth - i;
                    const dayElement = createDayElement(day, true, false);
                    calendarDaysElement.appendChild(dayElement);
                }
                
                // Add current month's days
                for (let day = 1; day <= daysInMonth; day++) {
                    const dayElement = createDayElement(day, false, false);
                    calendarDaysElement.appendChild(dayElement);
                }
                
                // Add next month's days to fill the grid
                const totalCells = calendarDaysElement.children.length;
                const remainingCells = 42 - totalCells; // 6 rows × 7 days
                for (let day = 1; day <= remainingCells; day++) {
                    const dayElement = createDayElement(day, false, true);
                    calendarDaysElement.appendChild(dayElement);
                }
            }

            // Create day element
            function createDayElement(day, isPrevMonth, isNextMonth) {
                const dayElement = document.createElement('div');
                dayElement.className = 'calendar-day';
                dayElement.textContent = day;
                
                let dateToCheck, displayMonth, displayYear;
                
                if (isPrevMonth) {
                    displayMonth = currentMonth === 0 ? 11 : currentMonth - 1;
                    displayYear = currentMonth === 0 ? currentYear - 1 : currentYear;
                    dayElement.classList.add('other-month');
                    dateToCheck = new Date(displayYear, displayMonth, day);
                } else if (isNextMonth) {
                    displayMonth = currentMonth === 11 ? 0 : currentMonth + 1;
                    displayYear = currentMonth === 11 ? currentYear + 1 : currentYear;
                    dayElement.classList.add('other-month');
                    dateToCheck = new Date(displayYear, displayMonth, day);
                } else {
                    displayMonth = currentMonth;
                    displayYear = currentYear;
                    dateToCheck = new Date(displayYear, displayMonth, day);
                }
                
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                dateToCheck.setHours(0, 0, 0, 0);
                
                // حساب الحد الأقصى للحجز (15 يوم من اليوم)
                const maxBookingDate = new Date(today);
                maxBookingDate.setDate(today.getDate() + MAX_BOOKING_DAYS);
                maxBookingDate.setHours(23, 59, 59, 999);
                
                const dateStr = formatDateForInput(dateToCheck);
                
                // Check if it's today
                if (dateToCheck.getTime() === today.getTime() && !isPrevMonth && !isNextMonth) {
                    dayElement.classList.add('today');
                }
                
                // Check if it's in the past or beyond 15 days limit
                if (dateToCheck < today || dateToCheck > maxBookingDate) {
                    dayElement.classList.add('past');
                    // Add visual indicator for unavailable dates
                    dayElement.style.position = 'relative';
                    dayElement.innerHTML = `
                        <span style="text-decoration: line-through; opacity: 0.5;">${day}</span>
                        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 100%; height: 100%; background: rgba(0,0,0,0.1); border-radius: 4px; pointer-events: none;"></div>
                    `;
                } else if (!isPrevMonth && !isNextMonth) {
                    // Check if it's Friday
                    if (dateToCheck.getDay() === 5) {
                        dayElement.classList.add('friday');
                    } else {
                        dayElement.classList.add('available');
                        dayElement.addEventListener('click', function() {
                            selectDate(dateStr, dayElement);
                        });
                    }
                }
                
                // Check if this date is currently selected
                if (selectedDate === dateStr && !isPrevMonth && !isNextMonth) {
                    dayElement.classList.add('selected');
                    // Add enhanced visual feedback for pre-selected date
                    dayElement.style.transform = 'scale(1.05)';
                    dayElement.style.boxShadow = '0 4px 12px rgba(236, 72, 153, 0.4)';
                }
                
                return dayElement;
            }

            // Select date function
            function selectDate(dateStr, dayElement) {
                // Remove previous selection
                document.querySelectorAll('.calendar-day.selected').forEach(el => {
                    el.classList.remove('selected');
                    el.style.transform = '';
                    el.style.boxShadow = '';
                });
                
                // Add selection to clicked day
                dayElement.classList.add('selected');
                // Add enhanced visual feedback
                dayElement.style.transform = 'scale(1.05)';
                dayElement.style.boxShadow = '0 4px 12px rgba(236, 72, 153, 0.4)';
                
                // Update selected date
                onDateSelected(dateStr);
            }

            // Format date for input
            function formatDateForInput(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }

            // Update selected date display
            function updateSelectedDateDisplay(dateStr) {
                if (dateStr) {
                    const date = new Date(dateStr);
                    const arabicDays = ['الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'];
                    const dayName = arabicDays[date.getDay()];
                    const day = date.getDate();
                    const month = arabicMonths[date.getMonth()];
                    const year = date.getFullYear();
                    
                    selectedDateDisplay.innerHTML = `
                        <i class="fas fa-calendar-check text-green-600 ml-2"></i>
                        <span class="text-green-600 font-semibold">${dayName} ${day} ${month} ${year}</span>
                    `;
                } else {
                    selectedDateDisplay.innerHTML = '<span class="text-gray-600">لم يتم اختيار تاريخ</span>';
                }
            }

            // Update booking range info
            function updateBookingRangeInfo() {
                const today = new Date();
                const maxBookingDate = new Date(today);
                maxBookingDate.setDate(today.getDate() + MAX_BOOKING_DAYS);
                
                const todayStr = today.toLocaleDateString('ar-SA');
                const maxDateStr = maxBookingDate.toLocaleDateString('ar-SA');
                
                const rangeInfoElement = document.getElementById('bookingRangeInfo');
                if (rangeInfoElement) {
                    rangeInfoElement.innerHTML = `
                        <i class="fas fa-calendar-alt text-xs ml-1"></i>
                        نطاق الحجز: من ${todayStr} إلى ${maxDateStr}
                    `;
                }
            }

            // Validate date and load available times
            function validateDateAndLoadTimes() {
                const date = dateInput.value;
                
                if (!date) {
                    resetTimesContainer();
                    validationDiv.innerHTML = '';
                    return;
                }

                const selectedDateObj = new Date(date);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                
                // حساب الحد الأقصى للحجز (15 يوم من اليوم)
                const maxBookingDate = new Date(today);
                maxBookingDate.setDate(today.getDate() + MAX_BOOKING_DAYS);
                maxBookingDate.setHours(23, 59, 59, 999);
                
                // Check if date is in the past
                if (selectedDateObj < today) {
                    validationDiv.innerHTML = '<i class="fas fa-exclamation-triangle text-red-500"></i> <span class="text-red-600">لا يمكن الحجز في تاريخ سابق</span>';
                    resetTimesContainer();
                    return;
                }

                // Check if date is beyond 15 days limit
                if (selectedDateObj > maxBookingDate) {
                    const todayStr = today.toLocaleDateString('ar-SA');
                    const maxDateStr = maxBookingDate.toLocaleDateString('ar-SA');
                    validationDiv.innerHTML = `<i class="fas fa-calendar-times text-red-500"></i> <span class="text-red-600">لا يمكن الحجز بعد ${MAX_BOOKING_DAYS} يوم من اليوم (من ${todayStr} إلى ${maxDateStr})</span>`;
                    timesContainer.innerHTML = `
                        <div class="text-center text-red-500 py-12">
                            <i class="fas fa-calendar-times text-4xl mb-4"></i>
                            <p class="text-lg font-medium">التاريخ خارج نطاق الحجز المسموح</p>
                            <p class="text-sm mt-1">يمكن الحجز من ${todayStr} إلى ${maxDateStr} فقط</p>
                        </div>
                    `;
                    disableSubmit();
                    return;
                }

                // Check if it's Friday
                if (selectedDateObj.getDay() === 5) {
                    validationDiv.innerHTML = '<i class="fas fa-times-circle text-red-500"></i> <span class="text-red-600">لا يمكن الحجز في يوم الجمعة</span>';
                    timesContainer.innerHTML = `
                        <div class="text-center text-red-500 py-12">
                            <i class="fas fa-calendar-times text-4xl mb-4"></i>
                            <p class="text-lg font-medium">يوم الجمعة إجازة</p>
                            <p class="text-sm mt-1">يرجى اختيار يوم آخر</p>
                        </div>
                    `;
                    disableSubmit();
                    return;
                }

                validationDiv.innerHTML = '<i class="fas fa-spinner fa-spin text-blue-500"></i> <span class="text-blue-600">جاري التحقق من التاريخ...</span>';
                
                loadAvailableTimeSlots(date);
            }

            // Load available time slots
            function loadAvailableTimeSlots(date) {
                // Show loading state
                timesContainer.innerHTML = `
                    <div class="text-center text-blue-500 py-6 sm:py-12">
                        <i class="fas fa-spinner fa-spin text-2xl sm:text-4xl mb-2 sm:mb-4"></i>
                        <p class="text-sm sm:text-lg font-medium">جاري تحميل الأوقات المتاحة...</p>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">يرجى الانتظار</p>
                    </div>
                `;

                // Fetch available slots
                fetch(`{{ route('frontend.booking.available-slots') }}?date=${date}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (!data.is_valid_date) {
                        validationDiv.innerHTML = `<i class="fas fa-times-circle text-red-500"></i> <span class="text-red-600">${data.message || 'هذا التاريخ غير متاح للحجز'}</span>`;
                                            timesContainer.innerHTML = `
                        <div class="text-center text-red-500 py-6 sm:py-12">
                            <i class="fas fa-calendar-times text-2xl sm:text-4xl mb-2 sm:mb-4"></i>
                            <p class="text-sm sm:text-lg font-medium">${data.message || 'هذا التاريخ غير متاح للحجز'}</p>
                            <p class="text-xs sm:text-sm mt-1">يرجى اختيار تاريخ آخر</p>
                        </div>
                    `;
                        disableSubmit();
                        return;
                    }

                    if (!data.available_slots || data.available_slots.length === 0) {
                        validationDiv.innerHTML = '<i class="fas fa-exclamation-triangle text-yellow-500"></i> <span class="text-yellow-600">لا توجد أوقات متاحة في هذا اليوم</span>';
                        timesContainer.innerHTML = `
                            <div class="text-center text-yellow-500 py-6 sm:py-12">
                                <i class="fas fa-clock text-2xl sm:text-4xl mb-2 sm:mb-4"></i>
                                <p class="text-sm sm:text-lg font-medium">لا توجد أوقات متاحة</p>
                                <p class="text-xs sm:text-sm mt-1">جميع الأوقات محجوزة في هذا اليوم</p>
                            </div>
                        `;
                        disableSubmit();
                        return;
                    }

                    displayAvailableTimeSlots(data.available_slots);
                    validationDiv.innerHTML = `<i class="fas fa-check-circle text-green-500"></i> <span class="text-green-600">التاريخ متاح للحجز (${data.available_slots.length} وقت متاح)</span>`;
                })
                .catch(error => {
                    console.error('Error loading time slots:', error);
                    validationDiv.innerHTML = '<i class="fas fa-exclamation-triangle text-red-500"></i> <span class="text-red-600">خطأ في تحميل الأوقات المتاحة</span>';
                    timesContainer.innerHTML = `
                        <div class="text-center text-red-500 py-6 sm:py-12">
                            <i class="fas fa-exclamation-triangle text-2xl sm:text-4xl mb-2 sm:mb-4"></i>
                            <p class="text-sm sm:text-lg font-medium">حدث خطأ في التحميل</p>
                            <p class="text-xs sm:text-sm mt-1">تحقق من اتصال الإنترنت وأعد المحاولة</p>
                            <button onclick="validateDateAndLoadTimes()" class="mt-2 sm:mt-4 bg-red-100 hover:bg-red-200 text-red-700 px-3 py-1 sm:px-4 sm:py-2 rounded-lg text-xs sm:text-sm transition-colors">
                                <i class="fas fa-sync-alt ml-1"></i>
                                إعادة المحاولة
                            </button>
                        </div>
                    `;
                    disableSubmit();
                });
            }

            // Display available time slots
            function displayAvailableTimeSlots(availableSlots) {
                // Group times by period
                const periods = {
                    morning: { title: 'الصباح', times: [], color: 'yellow', icon: 'sun' },
                    afternoon: { title: 'بعد الظهر', times: [], color: 'blue', icon: 'cloud-sun' },
                    evening: { title: 'المساء', times: [], color: 'purple', icon: 'moon' }
                };

                // تجميع الأوقات حسب الفترة المخزنة في قاعدة البيانات
                availableSlots.forEach(timeSlot => {
                    // إذا كان timeSlot كائن يحتوي على period، استخدمه
                    if (typeof timeSlot === 'object' && timeSlot.period) {
                        const period = timeSlot.period;
                        const timeFormatted = formatTime(timeSlot.time);
                        const timeValue = timeSlot.time;
                        
                        if (periods[period]) {
                            periods[period].times.push({ value: timeValue, display: timeFormatted });
                        }
                    } else {
                        // fallback للطريقة القديمة (بناءً على الساعة)
                        const hour = parseInt(timeSlot.split(':')[0]);
                        const timeFormatted = formatTime(timeSlot);
                        
                        if (hour === 11) {
                            periods.morning.times.push({ value: timeSlot, display: timeFormatted });
                        } else if (hour >= 13 && hour <= 17) {
                            periods.afternoon.times.push({ value: timeSlot, display: timeFormatted });
                        } else if (hour >= 19 && hour <= 21) {
                            periods.evening.times.push({ value: timeSlot, display: timeFormatted });
                        } else {
                            // إذا كان الوقت خارج النطاقات المعروفة، ضعه في الفترة المناسبة حسب الساعة
                            if (hour < 12) {
                                periods.morning.times.push({ value: timeSlot, display: timeFormatted });
                            } else if (hour < 18) {
                                periods.afternoon.times.push({ value: timeSlot, display: timeFormatted });
                            } else {
                                periods.evening.times.push({ value: timeSlot, display: timeFormatted });
                            }
                        }
                    }
                });

                let html = '<div class="space-y-6">';

                Object.entries(periods).forEach(([key, period]) => {
                    if (period.times.length > 0) {
                        html += `
                            <div class="bg-${period.color}-50 border border-${period.color}-200 rounded-xl p-2 sm:p-4">
                                <h4 class="font-semibold text-${period.color}-800 mb-2 sm:mb-4 flex items-center gap-2 text-sm sm:text-base">
                                    <i class="fas fa-${period.icon}"></i>
                                    ${period.title}
                                </h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3">
                        `;
                        
                        period.times.forEach(timeObj => {
                            html += `
                                <button type="button" 
                                        class="time-slot-btn p-2 sm:p-3 border-2 border-${period.color}-300 rounded-lg text-${period.color}-700 hover:bg-${period.color}-100 transition-all font-medium text-sm sm:text-base hover:scale-105"
                                        data-time="${timeObj.value}">
                                    ${timeObj.display}
                                </button>
                            `;
                        });
                        
                        html += '</div></div>';
                    }
                });

                if (html === '<div class="space-y-6">') {
                    html = `
                        <div class="text-center text-gray-500 py-6 sm:py-12">
                            <i class="fas fa-clock text-2xl sm:text-4xl mb-2 sm:mb-4 text-gray-300"></i>
                            <p class="text-sm sm:text-lg font-medium">لا توجد أوقات متاحة</p>
                        </div>
                    `;
                } else {
                    html += '</div>';
                }

                timesContainer.innerHTML = html;

                // Add click handlers to time buttons
                document.querySelectorAll('.time-slot-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        selectTimeSlot(this);
                    });
                });
            }

            // Select time slot
            function selectTimeSlot(button) {
                // Remove selection from all buttons
                document.querySelectorAll('.time-slot-btn').forEach(btn => {
                    btn.classList.remove('selected');
                    // Reset button styles
                    btn.style.transform = '';
                    btn.style.boxShadow = '';
                });

                // Add selection to clicked button
                button.classList.add('selected');
                // Add enhanced visual feedback
                button.style.transform = 'scale(1.05)';
                button.style.boxShadow = '0 4px 12px rgba(236, 72, 153, 0.3)';

                selectedTimeSlot = button.getAttribute('data-time');
                selectedTimeInput.value = selectedTimeSlot;

                // Enable submit button
                enableSubmit();
            }

            // Format time for display
            function formatTime(time) {
                const [hour, minute] = time.split(':');
                const hourInt = parseInt(hour);
                const ampm = hourInt >= 12 ? 'م' : 'ص';
                const hour12 = hourInt === 0 ? 12 : hourInt > 12 ? hourInt - 12 : hourInt;
                return `${hour12}:${minute} ${ampm}`;
            }

            // Reset times container
            function resetTimesContainer() {
                timesContainer.innerHTML = `
                    <div class="text-center text-gray-500 py-6 sm:py-12">
                        <i class="fas fa-calendar-day text-2xl sm:text-4xl mb-2 sm:mb-4 text-gray-300"></i>
                        <p class="text-sm sm:text-lg font-medium">اختر التاريخ أولاً</p>
                        <p class="text-xs sm:text-sm text-gray-400 mt-1">سيتم عرض الأوقات المتاحة بعد اختيار التاريخ</p>
                    </div>
                `;
                disableSubmit();
            }

            // Enable submit button
            function enableSubmit() {
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }

            // Disable submit button
            function disableSubmit() {
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                selectedTimeSlot = null;
                selectedTimeInput.value = '';
            }

            // Form validation before submit
            document.getElementById('datetimeForm').addEventListener('submit', function(e) {
                const date = dateInput.value;
                const time = selectedTimeInput.value;

                if (!date) {
                    e.preventDefault();
                    alert('يرجى اختيار التاريخ');
                    dateInput.focus();
                    return;
                }

                if (!time) {
                    e.preventDefault();
                    alert('يرجى اختيار الوقت');
                    return;
                }

                // Disable submit button to prevent double submission
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin ml-2"></i> جاري الحفظ...';
            });
            
            // Flash messages
            @if(session('success'))
                alert('{{ session('success') }}');
            @endif

            @if(session('error'))
                alert('{{ session('error') }}');
            @endif
        });
    </script>
</body>
</html> 
