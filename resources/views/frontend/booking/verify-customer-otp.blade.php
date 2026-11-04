<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>التحقق من رمز OTP - {{ config('brand.name') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">
    
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css'])
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
        .otp-input {
            width: 60px;
            height: 60px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            direction: ltr !important;
            unicode-bidi: bidi-override;
            text-align-last: center;
        }
        .otp-container {
            direction: ltr !important;
        }
        @media (max-width: 640px) {
            .otp-input {
                width: 50px;
                height: 50px;
                font-size: 20px;
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
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-pink-600 text-white rounded-full flex items-center justify-center text-xs sm:text-sm font-semibold">
                            <i class="fas fa-check text-xs"></i>
                        </div>
                        <span class="text-xs sm:text-sm font-medium text-pink-600 hidden sm:inline">اختيار الإضافات</span>
                        <span class="text-xs font-medium text-pink-600 sm:hidden">الإضافات</span>
                    </div>
                    
                    <div class="w-4 sm:w-8 h-px bg-pink-300"></div>
                    
                    <!-- Step 2: Completed -->
                    <div class="flex items-center gap-1 sm:gap-2">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-pink-600 text-white rounded-full flex items-center justify-center text-xs sm:text-sm font-semibold">
                            <i class="fas fa-check text-xs"></i>
                        </div>
                        <span class="text-xs sm:text-sm font-medium text-pink-600 hidden sm:inline">تحديد الموقع</span>
                        <span class="text-xs font-medium text-pink-600 sm:hidden">الموقع</span>
                    </div>
                    
                    <div class="w-4 sm:w-8 h-px bg-pink-300"></div>
                    
                    <!-- Step 3: Completed -->
                    <div class="flex items-center gap-1 sm:gap-2">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-pink-600 text-white rounded-full flex items-center justify-center text-xs sm:text-sm font-semibold">
                            <i class="fas fa-check text-xs"></i>
                        </div>
                        <span class="text-xs sm:text-sm font-medium text-pink-600 hidden sm:inline">اختيار الموعد</span>
                        <span class="text-xs font-medium text-pink-600 sm:hidden">الموعد</span>
                    </div>
                    
                    <div class="w-4 sm:w-8 h-px bg-purple-300"></div>
                    
                    <!-- Step 4: Active -->
                    <div class="flex items-center gap-1 sm:gap-2">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-purple-600 text-white rounded-full flex items-center justify-center text-xs sm:text-sm font-semibold">
                            <i class="fas fa-shield-alt text-xs"></i>
                        </div>
                        <span class="text-xs sm:text-sm font-medium text-purple-600 hidden sm:inline">التحقق من الهاتف</span>
                        <span class="text-xs font-medium text-purple-600 sm:hidden">التحقق</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-3 sm:px-4 py-6 sm:py-8">
        <div class="max-w-md mx-auto">
            <!-- OTP Verification Form -->
            <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 lg:p-8">
                <div class="text-center mb-6 sm:mb-8">
                    <div class="bg-pink-100 w-16 h-16 sm:w-20 sm:h-20 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                        <i class="fas fa-shield-alt text-2xl sm:text-3xl text-pink-600"></i>
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2">التحقق من رقم الجوال</h3>
                    <p class="text-gray-600 text-sm sm:text-base">أدخل الرمز المرسل إلى رقم {{ session('temp_booking_customer_phone') }}</p>
                </div>

                <form action="{{ route('frontend.booking.confirm-customer-otp') }}" method="POST" id="otpForm">
                    @csrf

                    <!-- OTP Input -->
                    <div class="mb-6 sm:mb-8">
                        <label class="block text-sm font-semibold text-gray-700 mb-3 sm:mb-4 text-center">
                            <i class="fas fa-key ml-1 text-pink-600"></i>
                            رمز التحقق
                        </label>
                        
                        <div class="otp-container flex justify-center gap-2 sm:gap-3 mb-4" dir="ltr">
                            <input type="text" maxlength="1" class="otp-input form-input border border-gray-300 rounded-xl focus:outline-none @error('otp_code') border-red-500 @enderror" id="otp-1" dir="ltr" inputmode="numeric" pattern="[0-9]">
                            <input type="text" maxlength="1" class="otp-input form-input border border-gray-300 rounded-xl focus:outline-none @error('otp_code') border-red-500 @enderror" id="otp-2" dir="ltr" inputmode="numeric" pattern="[0-9]">
                            <input type="text" maxlength="1" class="otp-input form-input border border-gray-300 rounded-xl focus:outline-none @error('otp_code') border-red-500 @enderror" id="otp-3" dir="ltr" inputmode="numeric" pattern="[0-9]">
                            <input type="text" maxlength="1" class="otp-input form-input border border-gray-300 rounded-xl focus:outline-none @error('otp_code') border-red-500 @enderror" id="otp-4" dir="ltr" inputmode="numeric" pattern="[0-9]">
                        </div>
                        
                        <!-- Hidden input للإرسال -->
                        <input type="hidden" name="otp_code" id="otp_code_hidden">
                        
                        @error('otp_code')
                            <p class="mt-2 text-xs sm:text-sm text-red-600 flex items-center gap-1 justify-center">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Countdown Timer -->
                    <div class="text-center mb-4 sm:mb-6">
                        <p class="text-sm text-gray-600" id="countdown-text">
                            الرمز صالح لمدة: <span id="countdown" class="font-bold text-pink-600">01:30</span>
                        </p>
                    </div>

                    <!-- Resend OTP -->
                    <div class="text-center mb-6 sm:mb-8">
                        <button type="button" id="resend-btn" class="text-pink-600 hover:text-pink-700 text-sm font-medium disabled:text-gray-400 disabled:cursor-not-allowed" disabled>
                            <i class="fas fa-redo ml-1"></i>
                            إعادة إرسال الرمز
                        </button>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                        <a href="{{ route('frontend.booking.customer-info') }}" 
                           class="flex-1 bg-gray-500 text-white py-3 sm:py-4 rounded-xl font-semibold hover:bg-gray-600 transition-colors text-center text-sm sm:text-base">
                            <i class="fas fa-arrow-right ml-1 sm:ml-2"></i>
                            <span class="hidden sm:inline">العودة</span>
                            <span class="sm:hidden">العودة</span>
                        </a>
                        <button type="submit" id="verify-btn" 
                                class="flex-1 bg-gradient-to-r from-pink-600 to-purple-600 text-white py-3 sm:py-4 rounded-xl font-semibold hover:from-pink-700 hover:to-purple-700 transition-all text-sm sm:text-base disabled:opacity-50 disabled:cursor-not-allowed" 
                                disabled>
                            <i class="fas fa-check ml-1 sm:ml-2"></i>
                            <span class="hidden sm:inline">تأكيد والمتابعة</span>
                            <span class="sm:hidden">تأكيد</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const otpInputs = document.querySelectorAll('.otp-input');
            const hiddenInput = document.getElementById('otp_code_hidden');
            const verifyBtn = document.getElementById('verify-btn');
            const resendBtn = document.getElementById('resend-btn');
            const countdownElement = document.getElementById('countdown');
            let countdownTime = 90; // 1.5 minutes in seconds

            // OTP input handling
            otpInputs.forEach((input, index) => {
                // Force LTR direction for OTP inputs
                input.setAttribute('dir', 'ltr');
                input.style.direction = 'ltr';
                input.style.unicodeBidi = 'bidi-override';
                input.style.textAlign = 'center';
                
                input.addEventListener('input', function() {
                    // Only allow numbers
                    this.value = this.value.replace(/[^0-9]/g, '');
                    
                    // Auto focus next input
                    if (this.value && index < otpInputs.length - 1) {
                        otpInputs[index + 1].focus();
                    }
                    
                    updateOtpCode();
                });

                input.addEventListener('keydown', function(e) {
                    // Handle backspace
                    if (e.key === 'Backspace' && !this.value && index > 0) {
                        otpInputs[index - 1].focus();
                    }
                    
                    // Handle arrow keys for better navigation
                    if (e.key === 'ArrowLeft' && index > 0) {
                        otpInputs[index - 1].focus();
                    }
                    if (e.key === 'ArrowRight' && index < otpInputs.length - 1) {
                        otpInputs[index + 1].focus();
                    }
                });

                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const pastedData = e.clipboardData.getData('text');
                    const numbers = pastedData.replace(/[^0-9]/g, '').slice(0, 4);
                    
                    numbers.split('').forEach((num, i) => {
                        if (otpInputs[i]) {
                            otpInputs[i].value = num;
                        }
                    });
                    
                    // Focus on the last filled input or next empty input
                    const lastFilledIndex = Math.min(numbers.length - 1, otpInputs.length - 1);
                    if (lastFilledIndex < otpInputs.length - 1) {
                        otpInputs[lastFilledIndex + 1].focus();
                    } else {
                        otpInputs[lastFilledIndex].focus();
                    }
                    
                    updateOtpCode();
                });
            });

            function updateOtpCode() {
                const otpCode = Array.from(otpInputs).map(input => input.value).join('');
                hiddenInput.value = otpCode;
                verifyBtn.disabled = otpCode.length !== 4;
                
                // Visual feedback
                if (otpCode.length === 4) {
                    otpInputs.forEach(input => {
                        input.classList.remove('border-gray-300');
                        input.classList.add('border-pink-500');
                    });
                } else {
                    otpInputs.forEach(input => {
                        input.classList.remove('border-pink-500');
                        input.classList.add('border-gray-300');
                    });
                }
            }

            // Countdown timer
            function updateCountdown() {
                const minutes = Math.floor(countdownTime / 60);
                const seconds = countdownTime % 60;
                countdownElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                
                if (countdownTime <= 0) {
                    document.getElementById('countdown-text').textContent = 'انتهت صلاحية الرمز';
                    resendBtn.disabled = false;
                    return;
                }
                
                countdownTime--;
                setTimeout(updateCountdown, 1000);
            }
            
            updateCountdown();

            // Resend OTP
            resendBtn.addEventListener('click', function() {
                this.disabled = true;
                this.innerHTML = '<i class="fas fa-spinner fa-spin ml-1"></i> جاري الإرسال...';
                
                fetch('{{ route("frontend.booking.resend-customer-otp") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        // Reset countdown
                        countdownTime = 90;
                        updateCountdown();
                        // Clear OTP inputs
                        otpInputs.forEach(input => input.value = '');
                        updateOtpCode();
                        otpInputs[0].focus();
                    } else {
                        alert(data.message);
                        this.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('حدث خطأ أثناء إعادة الإرسال');
                    this.disabled = false;
                })
                .finally(() => {
                    this.innerHTML = '<i class="fas fa-redo ml-1"></i> إعادة إرسال الرمز';
                });
            });

            // Auto focus first input
            otpInputs[0].focus();

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
