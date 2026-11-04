<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تم الحجز بنجاح - {{ config('brand.name') }}</title>
    
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
        .success-animation {
            animation: successPulse 2s ease-in-out infinite;
        }
        @keyframes successPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        .confetti {
            animation: confetti 3s ease-in-out infinite;
        }
        @keyframes confetti {
            0%, 100% { transform: rotate(0deg) translateY(0px); }
            25% { transform: rotate(10deg) translateY(-10px); }
            75% { transform: rotate(-10deg) translateY(-5px); }
        }
    </style>
                <script>
                // تنظيف السلة عند إتمام الحجز بنجاح
                document.addEventListener('DOMContentLoaded', function() {
                    // إزالة جميع المنتجات من localStorage
                    localStorage.removeItem('catShopCart');
                    
                    // إزالة بيانات العميل من localStorage
                    localStorage.removeItem('catShopCustomerData');
                    
                    // تحديث عرض السلة إذا كانت الدالة متاحة
                    if (typeof window.updateCartFromPage === 'function') {
                        window.updateCartFromPage();
                    }
                });
            </script>
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

    <!-- Success Message -->
    <div class="gradient-bg text-white py-8 sm:py-16">
        <div class="container mx-auto px-3 sm:px-4 text-center">
            <div class="max-w-3xl mx-auto">
                <!-- Success Icon -->
                <div class="success-animation bg-white w-20 h-20 sm:w-32 sm:h-32 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6">
                    <i class="fas fa-check text-3xl sm:text-6xl text-green-600"></i>
                </div>
                
                <!-- Success Message -->
                <h2 class="text-2xl sm:text-4xl md:text-5xl font-bold mb-3 sm:mb-4">
                    🎉 تم الحجز بنجاح!
                </h2>
                <p class="text-base sm:text-xl mb-4 sm:mb-6 text-pink-100">
                    شكراً لك! تم إرسال طلب الحجز بنجاح وسنتواصل معك قريباً
                </p>
                
                <!-- Booking Reference -->
                <div class="bg-white/20 rounded-xl p-3 sm:p-4 inline-block">
                    <p class="text-pink-100 text-xs sm:text-sm mb-1">رقم الحجز</p>
                    <p class="text-lg sm:text-2xl font-bold">#{{ $booking->id }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Details -->
    <div class="container mx-auto px-3 sm:px-4 py-6 sm:py-8">
        <div class="max-w-4xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 lg:gap-8">
                <!-- Service Details -->
                <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6">
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4 sm:mb-6 flex items-center gap-2">
                        <i class="fas fa-clipboard-check text-green-600"></i>
                        تفاصيل الحجز
                    </h3>

                    <div class="space-y-3 sm:space-y-4">
                        <!-- Service -->
                        <div class="border-b pb-3 sm:pb-4">
                            <h4 class="font-semibold text-gray-700 mb-2 text-sm sm:text-base">الخدمة المطلوبة</h4>
                            <p class="text-base sm:text-lg font-bold text-gray-800">{{ $booking->product->name }}</p>
                            <p class="text-gray-600 text-xs sm:text-sm">{{ $booking->product->short_description }}</p>
                        </div>

                        <!-- Date & Time -->
                        <div class="border-b pb-3 sm:pb-4">
                            <h4 class="font-semibold text-gray-700 mb-2 text-sm sm:text-base">موعد الخدمة</h4>
                            <div class="flex items-center gap-2 text-gray-800">
                                <i class="fas fa-calendar-alt text-pink-600"></i>
                                <span class="font-semibold text-sm sm:text-base">{{ $booking->booking_date->locale('ar')->translatedFormat('l، j F Y') }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-gray-800 mt-1">
                                <i class="fas fa-clock text-pink-600"></i>
                                <span class="font-semibold text-sm sm:text-base">{{ $booking->booking_time }}</span>
                            </div>
                        </div>

                        <!-- Customer Info -->
                        <div class="border-b pb-3 sm:pb-4">
                            <h4 class="font-semibold text-gray-700 mb-2 text-sm sm:text-base">بيانات العميل</h4>
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-user text-pink-600"></i>
                                    <span class="text-gray-800 text-sm sm:text-base">{{ $booking->customer->name }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-phone text-pink-600"></i>
                                    <span class="text-gray-800 text-sm sm:text-base" dir="ltr">+966{{ $booking->customer->phone }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Location -->
                     

                        <!-- Add-ons -->
                        @if($booking->add_ons_data && count($booking->add_ons_data) > 0)
                        <div>
                            <h4 class="font-semibold text-gray-700 mb-2 text-sm sm:text-base">الإضافات المختارة</h4>
                            <div class="space-y-2">
                                @foreach($booking->add_ons_data as $addon)
                                <div class="bg-gray-50 rounded-lg p-2 sm:p-3 flex justify-between items-center">
                                    <div>
                                        <span class="bg-purple-100 text-purple-600 px-1 sm:px-2 py-1 rounded text-xs font-medium ml-1 sm:ml-2">
                                            {{ $addon['option'] }}
                                        </span>
                                        <span class="font-medium text-sm sm:text-base">{{ $addon['name'] }}</span>
                                        <span class="text-gray-500 text-xs sm:text-sm">({{ $addon['quantity'] }}x)</span>
                                    </div>
                                    <span class="font-bold text-gray-700 text-sm sm:text-base">{{ number_format($addon['total'], 2) }} <span class="icon-saudi_riyal"></span></span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Status & Next Steps -->
                <div class="space-y-4 sm:space-y-6">
                    <!-- Current Status -->
                    <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6">
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4 sm:mb-6 flex items-center gap-2">
                            <i class="fas fa-info-circle text-blue-600"></i>
                            حالة الحجز
                        </h3>

                        <!-- Status Badge -->
                        <div class="mb-4">
                            @switch($booking->status)
                                @case('pending')
                                    <div class="bg-yellow-100 border border-yellow-300 rounded-lg p-3 sm:p-4">
                                        <div class="flex items-center gap-2 sm:gap-3">
                                            <div class="bg-yellow-500 p-1.5 sm:p-2 rounded-full">
                                                <i class="fas fa-clock text-white text-sm sm:text-base"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-yellow-800 text-sm sm:text-base">في انتظار التأكيد</h4>
                                                <p class="text-yellow-700 text-xs sm:text-sm">سيتم مراجعة طلبك وتأكيده خلال 24 ساعة</p>
                                            </div>
                                        </div>
                                    </div>
                                    @break
                                @case('confirmed')
                                    <div class="bg-green-100 border border-green-300 rounded-lg p-3 sm:p-4">
                                        <div class="flex items-center gap-2 sm:gap-3">
                                            <div class="bg-green-500 p-1.5 sm:p-2 rounded-full">
                                                <i class="fas fa-check text-white text-sm sm:text-base"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-green-800 text-sm sm:text-base">تم تأكيد الحجز</h4>
                                                <p class="text-green-700 text-xs sm:text-sm">حجزك مؤكد وسيصل مقدم الخدمة في الموعد المحدد</p>
                                            </div>
                                        </div>
                                    </div>
                                    @break
                                @default
                                    <div class="bg-blue-100 border border-blue-300 rounded-lg p-3 sm:p-4">
                                        <div class="flex items-center gap-2 sm:gap-3">
                                            <div class="bg-blue-500 p-1.5 sm:p-2 rounded-full">
                                                <i class="fas fa-info text-white text-sm sm:text-base"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-blue-800 text-sm sm:text-base">{{ ucfirst($booking->status) }}</h4>
                                                <p class="text-blue-700 text-xs sm:text-sm">تحديث حالة الحجز</p>
                                            </div>
                                        </div>
                                    </div>
                            @endswitch
                        </div>

                        <!-- Payment Status -->
                        <div class="mb-4">
                            @switch($booking->payment_status)
                                @case('pending')
                                    <div class="bg-orange-100 border border-orange-300 rounded-lg p-3 sm:p-4">
                                        <div class="flex items-center gap-2 sm:gap-3">
                                            <div class="bg-orange-500 p-1.5 sm:p-2 rounded-full">
                                                <i class="fas fa-credit-card text-white text-sm sm:text-base"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-orange-800 text-sm sm:text-base">
                                                    @if($booking->payment_method === 'pay_later')
                                                        الدفع عند الاستلام
                                                    @else
                                                        في انتظار تأكيد الدفع
                                                    @endif
                                                </h4>
                                                <p class="text-orange-700 text-xs sm:text-sm">
                                                    @if($booking->payment_method === 'pay_later')
                                                        يمكنك الدفع نقداً أو بالبطاقة عند تقديم الخدمة
                                                    @else
                                                        سيتم مراجعة إيصال التحويل وتأكيد الدفع
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    @break
                                @case('paid')
                                    <div class="bg-green-100 border border-green-300 rounded-lg p-3 sm:p-4">
                                        <div class="flex items-center gap-2 sm:gap-3">
                                            <div class="bg-green-500 p-1.5 sm:p-2 rounded-full">
                                                <i class="fas fa-check-circle text-white text-sm sm:text-base"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-green-800 text-sm sm:text-base">تم الدفع</h4>
                                                <p class="text-green-700 text-xs sm:text-sm">تم استلام وتأكيد الدفع بنجاح</p>
                                            </div>
                                        </div>
                                    </div>
                                    @break
                            @endswitch
                        </div>

                        <!-- Total Amount -->
                        <div class="bg-gray-50 rounded-lg p-3 sm:p-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 text-sm sm:text-base">المبلغ الإجمالي</span>
                                <span class="text-lg sm:text-2xl font-bold text-pink-600">{{ number_format($booking->total_price, 2) }} <span class="icon-saudi_riyal"></span></span>
                            </div>
                        </div>
                    </div>

                   

               

            <!-- Action Buttons -->
            <div class="mt-6 sm:mt-8 flex flex-col sm:flex-row gap-3 sm:gap-4">
                <a href="{{ route('home') }}" 
                   class="flex-1 bg-gradient-to-r from-pink-600 to-purple-600 text-white py-3 sm:py-4 px-4 sm:px-6 rounded-xl font-semibold hover:from-pink-700 hover:to-purple-700 transition-all text-center text-sm sm:text-base">
                    <i class="fas fa-home ml-1 sm:ml-2"></i>
                    <span class="hidden sm:inline">العودة للرئيسية</span>
                    <span class="sm:hidden">الرئيسية</span>
                </a>
                
                @if(session('customer_id'))
                    <a href="{{ route('frontend.customer.profile') }}" 
                       class="flex-1 bg-green-600 text-white py-3 sm:py-4 px-4 sm:px-6 rounded-xl font-semibold hover:bg-green-700 transition-colors text-center text-sm sm:text-base">
                        <i class="fas fa-user ml-1 sm:ml-2"></i>
                        <span class="hidden sm:inline">ملفي الشخصي</span>
                        <span class="sm:hidden">ملفي</span>
                    </a>
                @endif
                
                <button onclick="shareBooking()" 
                        class="flex-1 bg-blue-600 text-white py-3 sm:py-4 px-4 sm:px-6 rounded-xl font-semibold hover:bg-blue-700 transition-colors text-center text-sm sm:text-base">
                    <i class="fas fa-share ml-1 sm:ml-2"></i>
                    <span class="hidden sm:inline">مشاركة الحجز</span>
                    <span class="sm:hidden">مشاركة</span>
                </button>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        function shareBooking() {
            const bookingDetails = `
تم حجز خدمة من {{ config('brand.name') }} بنجاح! 🎉

📝 رقم الحجز: #{{ $booking->id }}
🏷️ الخدمة: {{ $booking->product->name }}
                                                        📅 التاريخ: {{ $booking->booking_date->locale('ar')->translatedFormat('l، j F Y') }}
                             ⏰ الوقت: {{ $booking->booking_time }}
💰 المبلغ: {{ number_format($booking->total_price, 2) }} <span class="icon-saudi_riyal"></span>

شكراً لاختياركم {{ config('brand.name') }}! 🐱
            `.trim();

            if (navigator.share) {
                navigator.share({
                    title: 'تفاصيل حجز {{ config('brand.name') }}',
                    text: bookingDetails,
                });
            } else {
                // Fallback for browsers that don't support Web Share API
                navigator.clipboard.writeText(bookingDetails).then(() => {
                    alert('تم نسخ تفاصيل الحجز. يمكنك مشاركتها الآن!');
                }).catch(() => {
                    alert('تعذر نسخ التفاصيل. يرجى النسخ يدوياً.');
                });
            }
        }

        // Auto-redirect to homepage after 5 minutes (optional)
        // setTimeout(() => {
        //     if (confirm('هل تريد العودة للصفحة الرئيسية؟')) {
        //         window.location.href = '{{ route('home') }}';
        //     }
        // }, 300000); // 5 minutes

        // Confetti effect (simple)
        document.addEventListener('DOMContentLoaded', function() {
            // Add some celebratory effects
            const successIcon = document.querySelector('.success-animation');
            if (successIcon) {
                successIcon.addEventListener('click', function() {
                    this.style.animation = 'none';
                    setTimeout(() => {
                        this.style.animation = 'successPulse 2s ease-in-out infinite';
                    }, 10);
                });
            }
        });
    </script>

    <!-- Print Styles -->
    <style media="print">
        body { font-size: 12px; }
        .gradient-bg { background: #ec4899 !important; }
        .no-print { display: none !important; }
        .bg-white { background: white !important; }
        .shadow-lg { box-shadow: none !important; }
    </style>
</body>
</html> 