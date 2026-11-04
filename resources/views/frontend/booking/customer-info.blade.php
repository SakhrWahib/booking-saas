<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>بيانات العميل - {{ config('brand.name') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Cairo', sans-serif; }
        .gradient-bg {
            background: linear-gradient(135deg, #ec4899 0%, #a855f7 100%);
        }
        .form-input:focus {
            border-color: #ec4899;
            box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.1);
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
                    
                    <!-- Step 3: Completed -->
                    <div class="flex items-center gap-1 sm:gap-2">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-xs sm:text-sm font-semibold">
                            <i class="fas fa-check text-xs"></i>
                        </div>
                        <span class="text-xs sm:text-sm font-medium text-green-600 hidden sm:inline">اختيار الموعد</span>
                        <span class="text-xs font-medium text-green-600 sm:hidden">الموعد</span>
                    </div>
                    
                    <div class="w-4 sm:w-8 h-px bg-green-300"></div>
                    
                    <!-- Step 4: Active -->
                    <div class="flex items-center gap-1 sm:gap-2">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-pink-600 text-white rounded-full flex items-center justify-center text-xs sm:text-sm font-semibold">
                            4
                        </div>
                        <span class="text-xs sm:text-sm font-medium text-pink-600 hidden sm:inline">بيانات العميل</span>
                        <span class="text-xs font-medium text-pink-600 sm:hidden">البيانات</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-3 sm:px-4 py-6 sm:py-8">
        <div class="max-w-2xl mx-auto">
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

            <!-- Customer Info Form -->
            <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 lg:p-8">
                <div class="text-center mb-6 sm:mb-8">
                    <div class="bg-pink-100 w-16 h-16 sm:w-20 sm:h-20 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                        <i class="fas fa-user text-2xl sm:text-3xl text-pink-600"></i>
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2">بيانات العميل</h3>
                    <p class="text-gray-600 text-sm sm:text-base">أدخل بياناتك وسيتم إرسال رمز التحقق لجوالك</p>
                    
                    @if(session('customer_id'))
                        <div class="bg-green-50 border border-green-200 rounded-xl p-3 sm:p-4 mt-4">
                            <div class="flex items-center gap-2 sm:gap-3">
                                <div class="bg-green-500 p-1.5 sm:p-2 rounded-full">
                                    <i class="fas fa-check text-white text-xs sm:text-sm"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-green-800 mb-1 text-sm sm:text-base">مرحباً {{ session('customer_name') }}!</h4>
                                    <p class="text-green-700 text-xs sm:text-sm">تم تعبئة بياناتك تلقائياً من ملفك الشخصي</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>



                <form action="{{ route('frontend.booking.save-customer-info') }}" method="POST" id="customerForm">
                    @csrf

                    <!-- Customer Name -->
                    <div class="mb-4 sm:mb-6">
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2 sm:mb-3">
                            <i class="fas fa-user ml-1 text-pink-600"></i>
                            الاسم الكامل <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               class="form-input block w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none text-right text-base sm:text-lg @error('name') border-red-500 @enderror" 
                               placeholder="أدخل اسمك الكامل"
                               value="{{ old('name', session('customer_name')) }}"
                               required>
                        @error('name')
                            <p class="mt-2 text-xs sm:text-sm text-red-600 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Customer Phone -->
                    <div class="mb-4 sm:mb-6">
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2 sm:mb-3">
                            <i class="fas fa-phone ml-1 text-pink-600"></i>
                            رقم الجوال <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="tel" 
                                   id="phone" 
                                   name="phone" 
                                   class="form-input block w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none text-right text-base sm:text-lg @error('phone') border-red-500 @enderror" 
                                   placeholder="أدخل رقم الجوال"
                                   value="{{ old('phone', session('customer_phone')) }}"
                                   required>
                        </div>
                        <p class="mt-2 text-xs sm:text-sm text-gray-500 flex items-center gap-1">
                            <i class="fas fa-info-circle"></i>
                            يمكن إدخال الرقم بأي تنسيق (055، 55، +966)
                        </p>
                        @error('phone')
                            <p class="mt-2 text-xs sm:text-sm text-red-600 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- OTP Security Notice -->
                    <div class="mb-4 sm:mb-6">
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-3 sm:p-4">
                            <div class="flex items-start gap-2 sm:gap-3">
                                <div class="bg-blue-500 p-1.5 sm:p-2 rounded-full mt-1">
                                    <i class="fas fa-shield-alt text-white text-xs sm:text-sm"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-blue-800 mb-1 text-sm sm:text-base">التحقق الآمن</h4>
                                    <p class="text-blue-700 text-xs sm:text-sm">سيتم إرسال رمز تحقق مكون من 4 أرقام إلى جوالك لضمان أمان حجزك</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Preferences -->
                    <div class="mb-4 sm:mb-6">
                        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-3 sm:p-4">
                            <div class="flex items-start gap-2 sm:gap-3">
                                <div class="bg-yellow-500 p-1.5 sm:p-2 rounded-full mt-1">
                                    <i class="fas fa-bell text-white text-xs sm:text-sm"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-yellow-800 mb-1 text-sm sm:text-base">التواصل معك</h4>
                                    <p class="text-yellow-700 text-xs sm:text-sm">سنتواصل معك عبر الرقم المدخل لتأكيد الموعد وإرسال تحديثات الحجز</p>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                        <a href="{{ route('frontend.booking.select-datetime') }}" 
                           class="flex-1 bg-gray-500 text-white py-3 sm:py-4 rounded-xl font-semibold hover:bg-gray-600 transition-colors text-center text-sm sm:text-base">
                            <i class="fas fa-arrow-right ml-1 sm:ml-2"></i>
                            <span class="hidden sm:inline">العودة</span>
                            <span class="sm:hidden">العودة</span>
                        </a>
                        <button type="submit" 
                                class="flex-1 bg-gradient-to-r from-pink-600 to-purple-600 text-white py-3 sm:py-4 rounded-xl font-semibold hover:from-pink-700 hover:to-purple-700 transition-all text-sm sm:text-base">
                            <i class="fas fa-mobile-alt ml-1 sm:ml-2"></i>
                            <span class="hidden sm:inline">إرسال رمز التحقق</span>
                            <span class="sm:hidden">إرسال الرمز</span>
                        </button>
                    </div>
                </form>
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
                // Allow spaces between words
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
            
            // Phone input validation for Saudi numbers only
            phoneInput.addEventListener('input', function() {
                let phoneNumber = this.value;
                
                // Remove any non-numeric characters except +
                phoneNumber = phoneNumber.replace(/[^0-9+]/g, '');
                
                // Check if it's a valid Saudi number format
                let isValid = false;
                let cleanNumber = '';
                
                // Format 1: +9665XXXXXXXX
                if (phoneNumber.startsWith('+966') && phoneNumber.length === 13) {
                    cleanNumber = phoneNumber.substring(4); // Remove +966
                    isValid = cleanNumber.length === 9 && cleanNumber.startsWith('5');
                }
                // Format 2: 05XXXXXXXX
                else if (phoneNumber.startsWith('0') && phoneNumber.length === 10) {
                    cleanNumber = phoneNumber.substring(1); // Remove 0
                    isValid = cleanNumber.length === 9 && cleanNumber.startsWith('5');
                }
                // Format 3: 5XXXXXXXX
                else if (phoneNumber.length === 9 && phoneNumber.startsWith('5')) {
                    cleanNumber = phoneNumber;
                    isValid = true;
                }
                
                // Update visual feedback
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
            document.getElementById('customerForm').addEventListener('submit', function(e) {
                const name = nameInput.value.trim();
                const phone = phoneInput.value.trim();
                
                if (!name || name.length < 2) {
                    e.preventDefault();
                    alert('يرجى إدخال الاسم الكامل (حرفين على الأقل)');
                    nameInput.focus();
                    return;
                }
                
                // Validate Saudi phone number format
                let phoneNumber = phone.replace(/[^0-9+]/g, '');
                let isValidSaudiNumber = false;
                
                // Format 1: +9665XXXXXXXX
                if (phoneNumber.startsWith('+966') && phoneNumber.length === 13) {
                    let cleanNumber = phoneNumber.substring(4);
                    isValidSaudiNumber = cleanNumber.length === 9 && cleanNumber.startsWith('5');
                }
                // Format 2: 05XXXXXXXX
                else if (phoneNumber.startsWith('0') && phoneNumber.length === 10) {
                    let cleanNumber = phoneNumber.substring(1);
                    isValidSaudiNumber = cleanNumber.length === 9 && cleanNumber.startsWith('5');
                }
                // Format 3: 5XXXXXXXX
                else if (phoneNumber.length === 9 && phoneNumber.startsWith('5')) {
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