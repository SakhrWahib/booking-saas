<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ملفي الشخصي - {{ config('brand.name') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Saudi Riyal Font -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@emran-alhaddad/saudi-riyal-font/index.css">
    
    <style>
        body { font-family: 'Cairo', sans-serif; }
        .gradient-bg {
            background: linear-gradient(135deg, #ec4899 0%, #a855f7 100%);
        }
        .form-input:focus {
            border-color: #ec4899;
            box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.1);
        }
        .booking-card {
            transition: all 0.3s ease;
        }
        .booking-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="gradient-bg text-white shadow-lg">
        <div class="container mx-auto px-3 sm:px-4">
            <div class="flex items-center justify-between h-14 sm:h-16">
                <div class="flex items-center gap-2 sm:gap-3">
                    <div class="bg-white/20 p-1.5 sm:p-2 rounded-full">
                        <i class="fas fa-cat text-xl sm:text-2xl"></i>
                    </div>
                    <h1 class="text-lg sm:text-xl font-bold">{{ config('brand.name') }}</h1>
                </div>
                <div class="flex items-center gap-2 sm:gap-3">
                    <a href="{{ route('home') }}" class="text-pink-200 hover:text-white transition-colors text-sm sm:text-base">
                        <i class="fas fa-home ml-1 sm:ml-2"></i>
                        <span class="hidden sm:inline">الرئيسية</span>
                    </a>
                    <a href="{{ route('frontend.customer.logout') }}" 
                       onclick="return confirm('هل تريد تسجيل الخروج؟')"
                       class="text-pink-200 hover:text-white transition-colors text-sm sm:text-base">
                        <i class="fas fa-sign-out-alt ml-1 sm:ml-2"></i>
                        <span class="hidden sm:inline">تسجيل الخروج</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container mx-auto px-3 sm:px-4 py-6 sm:py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Welcome Section -->
            <div class="bg-gradient-to-r from-pink-50 to-purple-50 rounded-2xl p-4 sm:p-6 mb-6 sm:mb-8">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="bg-pink-100 w-12 h-12 sm:w-16 sm:h-16 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-xl sm:text-2xl text-pink-600"></i>
                    </div>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-800">مرحباً {{ $customer->name }}!</h2>
                        <p class="text-gray-600 text-sm sm:text-base">هذا ملفك الشخصي في {{ config('brand.name') }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
                <!-- Profile Info -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-4 sm:mb-6 flex items-center gap-2">
                            <i class="fas fa-user-edit text-pink-600"></i>
                            بياناتي الشخصية
                        </h3>

                        <form action="{{ route('frontend.customer.update-profile') }}" method="POST" id="profileForm">
                            @csrf
                            
                            <!-- Customer Name -->
                            <div class="mb-4 sm:mb-6">
                                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-user ml-1 text-pink-600"></i>
                                    الاسم الكامل
                                </label>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       class="form-input block w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none text-right text-base sm:text-lg @error('name') border-red-500 @enderror" 
                                       value="{{ $customer->name }}"
                                       required>
                                @error('name')
                                    <p class="mt-2 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Customer Phone -->
                            <div class="mb-4 sm:mb-6">
                                <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-phone ml-1 text-pink-600"></i>
                                    رقم الجوال
                                </label>
                                <input type="tel" 
                                       id="phone" 
                                       name="phone" 
                                       class="form-input block w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none text-right text-base sm:text-lg @error('phone') border-red-500 @enderror" 
                                       value="{{ $customer->phone }}"
                                       required>
                                @error('phone')
                                    <p class="mt-2 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Member Since -->
                            <div class="mb-4 sm:mb-6">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-calendar ml-1 text-pink-600"></i>
                                    تاريخ الانضمام
                                </label>
                                <p class="text-gray-600 text-sm sm:text-base">{{ $customer->created_at->format('Y/m/d') }}</p>
                            </div>

                            <!-- Update Button -->
                            <button type="submit" 
                                    class="w-full bg-gradient-to-r from-pink-600 to-purple-600 text-white py-3 sm:py-4 rounded-xl font-semibold hover:from-pink-700 hover:to-purple-700 transition-all text-sm sm:text-base">
                                <i class="fas fa-save ml-1 sm:ml-2"></i>
                                تحديث البيانات
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Bookings History -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-4 sm:mb-6 flex items-center gap-2">
                            <i class="fas fa-history text-pink-600"></i>
                            سجل الحجوزات
                        </h3>

                        @if($bookings->count() > 0)
                            <div class="space-y-4 sm:space-y-6">
                                @foreach($bookings as $booking)
                                    <div class="booking-card bg-gray-50 rounded-xl p-4 sm:p-6 border border-gray-200">
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 sm:gap-3 mb-2">
                                                    <h4 class="font-bold text-gray-800 text-base sm:text-lg">{{ $booking->product->name }}</h4>
                                                    <span class="bg-pink-100 text-pink-600 px-2 py-1 rounded-full text-xs font-medium">
                                                        #{{ $booking->id }}
                                                    </span>
                                                </div>
                                                
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-4 text-sm">
                                                    <div class="flex items-center gap-2">
                                                        <i class="fas fa-calendar text-pink-600"></i>
                                                        <span class="text-gray-600">{{ $booking->booking_date->format('Y/m/d') }}</span>
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <i class="fas fa-clock text-pink-600"></i>
                                                        <span class="text-gray-600">{{ $booking->booking_time }}</span>
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <i class="fas fa-money-bill text-pink-600"></i>
                                                        <span class="text-gray-600">{{ number_format($booking->total_price, 2) }} <span class="icon-saudi_riyal"></span></span>
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <i class="fas fa-info-circle text-pink-600"></i>
                                                        <span class="text-gray-600">
                                                            @switch($booking->status)
                                                                @case('pending')
                                                                    <span class="text-yellow-600">في الانتظار</span>
                                                                    @break
                                                                @case('confirmed')
                                                                    <span class="text-green-600">مؤكد</span>
                                                                    @break
                                                                @case('completed')
                                                                    <span class="text-blue-600">مكتمل</span>
                                                                    @break
                                                                @case('cancelled')
                                                                    <span class="text-red-600">ملغي</span>
                                                                    @break
                                                                @default
                                                                    <span class="text-gray-600">{{ $booking->status }}</span>
                                                            @endswitch
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="flex flex-col gap-2">
                                                <a href="{{ route('home') }}" 
                                                   class="bg-pink-600 text-white px-3 sm:px-4 py-2 rounded-lg text-sm font-medium hover:bg-pink-700 transition-colors text-center">
                                                    <i class="fas fa-plus ml-1"></i>
                                                    حجز جديد
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 sm:py-12">
                                <div class="bg-gray-100 w-16 h-16 sm:w-20 sm:h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-calendar-times text-2xl sm:text-3xl text-gray-400"></i>
                                </div>
                                <h4 class="text-lg sm:text-xl font-bold text-gray-600 mb-2">لا توجد حجوزات سابقة</h4>
                                <p class="text-gray-500 text-sm sm:text-base mb-4">ابدأ بحجز خدمتك الأولى الآن!</p>
                                <a href="{{ route('home') }}" 
                                   class="inline-flex items-center gap-2 bg-gradient-to-r from-pink-600 to-purple-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-pink-700 hover:to-purple-700 transition-all">
                                    <i class="fas fa-plus"></i>
                                    احجز الآن
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const phoneInput = document.getElementById('phone');
            const nameInput = document.getElementById('name');
            
            // Name input validation
            nameInput.addEventListener('input', function() {
                if (this.value.length >= 2) {
                    this.classList.remove('border-red-500');
                    this.classList.add('border-green-500');
                } else if (this.value.length > 0) {
                    this.classList.remove('border-green-500');
                    this.classList.add('border-red-500');
                } else {
                    this.classList.remove('border-red-500', 'border-green-500');
                }
            });
            
            // Phone input validation for Saudi numbers
            phoneInput.addEventListener('input', function() {
                let phoneNumber = this.value;
                phoneNumber = phoneNumber.replace(/[^0-9+]/g, '');
                
                let isValid = false;
                let cleanNumber = '';
                
                // Format 1: +9665XXXXXXXX
                if (phoneNumber.startsWith('+966') && phoneNumber.length === 13) {
                    cleanNumber = phoneNumber.substring(4);
                    isValid = cleanNumber.length === 9 && cleanNumber.startsWith('5');
                }
                // Format 2: 05XXXXXXXX
                else if (phoneNumber.startsWith('0') && phoneNumber.length === 10) {
                    cleanNumber = phoneNumber.substring(1);
                    isValid = cleanNumber.length === 9 && cleanNumber.startsWith('5');
                }
                // Format 3: 5XXXXXXXX
                else if (phoneNumber.length === 9 && phoneNumber.startsWith('5')) {
                    cleanNumber = phoneNumber;
                    isValid = true;
                }
                
                if (isValid && phoneNumber.length > 0) {
                    this.classList.remove('border-red-500');
                    this.classList.add('border-green-500');
                } else if (this.value.length > 0) {
                    this.classList.remove('border-green-500');
                    this.classList.add('border-red-500');
                } else {
                    this.classList.remove('border-red-500', 'border-green-500');
                }
            });
            
            // Form submission validation
            document.getElementById('profileForm').addEventListener('submit', function(e) {
                const name = nameInput.value.trim();
                const phone = phoneInput.value.trim();
                
                if (!name || name.length < 2) {
                    e.preventDefault();
                    alert('يرجى إدخال الاسم الكامل (حرفين على الأقل)');
                    nameInput.focus();
                    return;
                }
                
                let phoneNumber = phone.replace(/[^0-9+]/g, '');
                let isValidSaudiNumber = false;
                
                if (phoneNumber.startsWith('+966') && phoneNumber.length === 13) {
                    let cleanNumber = phoneNumber.substring(4);
                    isValidSaudiNumber = cleanNumber.length === 9 && cleanNumber.startsWith('5');
                } else if (phoneNumber.startsWith('0') && phoneNumber.length === 10) {
                    let cleanNumber = phoneNumber.substring(1);
                    isValidSaudiNumber = cleanNumber.length === 9 && cleanNumber.startsWith('5');
                } else if (phoneNumber.length === 9 && phoneNumber.startsWith('5')) {
                    isValidSaudiNumber = true;
                }
                
                if (!isValidSaudiNumber) {
                    e.preventDefault();
                    alert('يرجى إدخال رقم جوال سعودي صحيح (مثال: 05XXXXXXXX)');
                    phoneInput.focus();
                    return;
                }
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