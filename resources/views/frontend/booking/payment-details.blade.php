<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تفاصيل الدفع - {{ config('brand.name') }}</title>
    
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
        .payment-option {
            transition: all 0.3s;
            cursor: pointer;
        }
        .payment-option:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 0;
            border-radius: 1rem;
            width: 90%;
            max-width: 600px;
            max-height: 80vh;
            overflow-y: auto;
        }
        .modal.show {
            display: block;
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
                    <!-- All Steps Completed -->
                    <div class="flex items-center gap-1 sm:gap-2">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-xs sm:text-sm font-semibold">
                            <i class="fas fa-check text-xs"></i>
                        </div>
                        <span class="text-xs sm:text-sm font-medium text-green-600 hidden sm:inline">اختيار الإضافات</span>
                        <span class="text-xs font-medium text-green-600 sm:hidden">الإضافات</span>
                    </div>
                    
                    <div class="w-4 sm:w-8 h-px bg-green-300"></div>
                    
                    <div class="flex items-center gap-1 sm:gap-2">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-xs sm:text-sm font-semibold">
                            <i class="fas fa-check text-xs"></i>
                        </div>
                        <span class="text-xs sm:text-sm font-medium text-green-600 hidden sm:inline">تحديد الموقع</span>
                        <span class="text-xs font-medium text-green-600 sm:hidden">الموقع</span>
                    </div>
                    
                    <div class="w-4 sm:w-8 h-px bg-green-300"></div>
                    
                    <div class="flex items-center gap-1 sm:gap-2">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-xs sm:text-sm font-semibold">
                            <i class="fas fa-check text-xs"></i>
                        </div>
                        <span class="text-xs sm:text-sm font-medium text-green-600 hidden sm:inline">اختيار الموعد</span>
                        <span class="text-xs font-medium text-green-600 sm:hidden">الموعد</span>
                    </div>
                    
                    <div class="w-4 sm:w-8 h-px bg-green-300"></div>
                    
                    <div class="flex items-center gap-1 sm:gap-2">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-pink-600 text-white rounded-full flex items-center justify-center text-xs sm:text-sm font-semibold">
                            <i class="fas fa-credit-card text-xs"></i>
                        </div>
                        <span class="text-xs sm:text-sm font-medium text-pink-600 hidden sm:inline">تفاصيل الدفع</span>
                        <span class="text-xs font-medium text-pink-600 sm:hidden">الدفع</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-3 sm:px-4 py-6 sm:py-8">
        <div class="max-w-5xl mx-auto">
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
                <!-- Booking Summary -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 mb-4 sm:mb-6">
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4 sm:mb-6 flex items-center gap-2">
                            <i class="fas fa-clipboard-list text-pink-600"></i>
                            ملخص الحجز
                        </h3>

                        <!-- Service Details -->
                        <div class="space-y-3 sm:space-y-4">
                            <div class="border-b pb-3 sm:pb-4">
                                <h4 class="font-semibold text-gray-800 mb-2 text-sm sm:text-base">الخدمة المطلوبة</h4>
                                <p class="text-base sm:text-lg text-gray-700">{{ $bookingData['product_name'] }}</p>
                                <p class="text-pink-600 font-bold text-sm sm:text-base">{{ number_format($bookingData['base_price'], 2) }} <span class="icon-saudi_riyal"></span></p>
                            </div>

                            <!-- Add-ons -->
                            @if($bookingData['selected_add_ons'] && count($bookingData['selected_add_ons']) > 0)
                            <div class="border-b pb-3 sm:pb-4">
                                <h4 class="font-semibold text-gray-800 mb-2 sm:mb-3 text-sm sm:text-base">الإضافات المختارة</h4>
                                <div class="space-y-2">
                                    @foreach($bookingData['selected_add_ons'] as $addon)
                                    <div class="flex justify-between items-center bg-gray-50 rounded-lg p-2 sm:p-3">
                                        <div>
                                            <span class="bg-purple-100 text-purple-600 px-1 sm:px-2 py-1 rounded text-xs font-medium ml-1 sm:ml-2">
                                                {{ $addon['option'] }}
                                            </span>
                                            <span class="font-medium text-sm sm:text-base">{{ $addon['name'] }}</span>
                                        </div>
                                        <span class="font-bold text-gray-700 text-sm sm:text-base">{{ number_format($addon['price'], 2) }} <span class="icon-saudi_riyal"></span></span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <!-- DateTime -->
                            <div class="border-b pb-3 sm:pb-4">
                                <h4 class="font-semibold text-gray-800 mb-2 text-sm sm:text-base">موعد الخدمة</h4>
                                <div class="flex items-center gap-2 text-gray-700">
                                    <i class="fas fa-calendar text-pink-600"></i>
                                    <span class="text-sm sm:text-base">{{ \Carbon\Carbon::parse($bookingData['datetime'])->locale('ar')->translatedFormat('l، j F Y - h:i A') }}</span>
                                </div>
                            </div>

                            <!-- Customer Info -->
                            <div class="border-b pb-3 sm:pb-4">
                                <h4 class="font-semibold text-gray-800 mb-2 text-sm sm:text-base">بيانات العميل</h4>
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2 text-gray-700">
                                        <i class="fas fa-user text-pink-600"></i>
                                        <span class="text-sm sm:text-base">{{ $bookingData['customer_name'] }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-gray-700">
                                        <i class="fas fa-phone text-pink-600"></i>
                                        <span class="text-sm sm:text-base">+966{{ $bookingData['customer_phone'] }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Location -->
                            <div>
                                <h4 class="font-semibold text-gray-800 mb-2 text-sm sm:text-base">موقع الخدمة</h4>
                                <div class="flex items-start gap-2 text-gray-700">
                                    <i class="fas fa-map-marker-alt text-pink-600 mt-1"></i>
                                    <span class="text-sm sm:text-base">{{ $bookingData['address'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Options -->
                    <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6">
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4 sm:mb-6 flex items-center gap-2">
                            <i class="fas fa-credit-card text-pink-600"></i>
                            طرق الدفع المتاحة
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                            <!-- Pay Later Option -->
                            <div class="payment-option bg-blue-50 border-2 border-blue-200 rounded-xl p-4 sm:p-6">
                                <div class="text-center mb-3 sm:mb-4">
                                    <div class="bg-blue-100 w-12 h-12 sm:w-16 sm:h-16 rounded-full flex items-center justify-center mx-auto mb-2 sm:mb-3">
                                        <i class="fas fa-clock text-xl sm:text-2xl text-blue-600"></i>
                                    </div>
                                    <h4 class="text-lg sm:text-xl font-bold text-blue-800 mb-1 sm:mb-2">ادفع لاحقاً</h4>
                                    <p class="text-blue-600 text-xs sm:text-sm">يمكنك الدفع عند استلام الخدمة</p>
                                </div>
                                <button type="button" onclick="showDisclaimer('later')" class="w-full bg-blue-600 text-white py-2 sm:py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors text-sm sm:text-base">
                                    تأكيد الحجز - ادفع لاحقاً
                                </button>
                            </div>

                            <!-- Bank Transfer Option -->
                            <div class="payment-option bg-green-50 border-2 border-green-200 rounded-xl p-4 sm:p-6">
                                <div class="text-center mb-3 sm:mb-4">
                                    <div class="bg-green-100 w-12 h-12 sm:w-16 sm:h-16 rounded-full flex items-center justify-center mx-auto mb-2 sm:mb-3">
                                        <i class="fas fa-university text-xl sm:text-2xl text-green-600"></i>
                                    </div>
                                    <h4 class="text-lg sm:text-xl font-bold text-green-800 mb-1 sm:mb-2">تحويل بنكي</h4>
                                    <p class="text-green-600 text-xs sm:text-sm">حول المبلغ وارفع الإيصال</p>
                                </div>
                                <button type="button" onclick="showDisclaimer('bank')" class="w-full bg-green-600 text-white py-2 sm:py-3 rounded-lg font-semibold hover:bg-green-700 transition-colors text-sm sm:text-base">
                                    التحويل البنكي
                                </button>
                            </div>
                        </div>

                        <!-- Payment Notes -->
                        <div class="mt-4 sm:mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-3 sm:p-4">
                            <div class="flex items-start gap-2 sm:gap-3">
                                <div class="bg-yellow-500 p-1.5 sm:p-2 rounded-full mt-1">
                                    <i class="fas fa-info text-white text-xs sm:text-sm"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-yellow-800 mb-1 text-sm sm:text-base">ملاحظات الدفع</h4>
                                    <ul class="text-yellow-700 text-xs sm:text-sm space-y-1">
                                        <li>• في حالة الدفع لاحقاً، يمكن الدفع نقداً أو بالبطاقة عند تقديم الخدمة</li>
                                        <li>• في حالة التحويل البنكي، يجب إرفاق إيصال التحويل</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Price Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 sticky top-4">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-4 sm:mb-6 flex items-center gap-2">
                            <i class="fas fa-receipt text-pink-600"></i>
                            ملخص المبلغ
                        </h3>

                        <div class="space-y-3 sm:space-y-4">
                            <!-- Base Service -->
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 text-sm sm:text-base">الخدمة الأساسية</span>
                                <span class="font-semibold text-sm sm:text-base">{{ number_format($bookingData['base_price'], 2) }} <span class="icon-saudi_riyal"></span></span>
                            </div>

                            <!-- Add-ons Total -->
                            @if($bookingData['add_ons_total'] > 0)
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 text-sm sm:text-base">الإضافات</span>
                                <span class="font-semibold text-sm sm:text-base">{{ number_format($bookingData['add_ons_total'], 2) }} <span class="icon-saudi_riyal"></span></span>
                            </div>
                            @endif

                            <!-- Subtotal -->
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 text-sm sm:text-base">المجموع الفرعي</span>
                                <span class="font-semibold text-sm sm:text-base">{{ number_format($bookingData['subtotal'], 2) }} <span class="icon-saudi_riyal"></span></span>
                            </div>

                            <!-- Discount -->
                            @if($bookingData['discount_amount'] > 0)
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 text-sm sm:text-base">الخصم</span>
                                <span class="font-semibold text-green-600 text-sm sm:text-base">-{{ number_format($bookingData['discount_amount'], 2) }} <span class="icon-saudi_riyal"></span></span>
                            </div>
                            @endif

                            <!-- Price After Discount -->
                            @if($bookingData['discount_amount'] > 0)
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 text-sm sm:text-base">السعر بعد الخصم</span>
                                <span class="font-semibold text-sm sm:text-base">{{ number_format($bookingData['price_without_tax'], 2) }} <span class="icon-saudi_riyal"></span></span>
                            </div>
                            @endif

                            <!-- Tax -->
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 text-sm sm:text-base">قيمة الضريبة (15%)</span>
                                <span class="font-semibold text-sm sm:text-base">{{ number_format($bookingData['tax_amount'], 2) }} <span class="icon-saudi_riyal"></span></span>
                            </div>

                            <div class="border-t pt-3 sm:pt-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-base sm:text-lg font-bold text-gray-800">المجموع الكلي</span>
                                    <span class="text-xl sm:text-2xl font-bold text-pink-600">{{ number_format($bookingData['total_price'], 2) }} <span class="icon-saudi_riyal"></span></span>
                                </div>
                            </div>

                            <!-- Money Back Guarantee -->
                        

                            <!-- Contact Support -->
                          
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 sm:mt-8 bg-white rounded-2xl shadow-lg p-4 sm:p-6">
                <div class="flex gap-3 sm:gap-4">
                    <a href="{{ route('frontend.booking.customer-info') }}" 
                       class="flex-1 bg-gray-500 text-white py-3 sm:py-4 rounded-xl font-semibold hover:bg-gray-600 transition-colors text-center text-sm sm:text-base">
                        <i class="fas fa-arrow-right ml-1 sm:ml-2"></i>
                        <span class="hidden sm:inline">العودة لتعديل البيانات</span>
                        <span class="sm:hidden">تعديل البيانات</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Disclaimer Modal -->
    <div id="disclaimerModal" class="modal">
        <div class="modal-content">
            <!-- Header with gradient and icon -->
            <div class="text-white p-4 sm:p-6 rounded-t-xl relative overflow-hidden" style="background: linear-gradient(135deg, #ec4899 0%, #a855f7 100%);">
                <!-- Background pattern -->
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute top-0 left-0 w-20 h-20 bg-white rounded-full -translate-x-10 -translate-y-10"></div>
                    <div class="absolute bottom-0 right-0 w-16 h-16 bg-white rounded-full translate-x-8 translate-y-8"></div>
                </div>
                
                <div class="flex items-center justify-between relative z-10">
                    <div class="flex items-center gap-3">
                        <div class="bg-white/20 p-2 sm:p-3 rounded-full backdrop-blur-sm">
                            <i class="fas fa-exclamation-triangle text-xl sm:text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg sm:text-xl font-bold">تنبيه إخلاء مسؤولية</h3>
                            <p class="text-pink-100 text-xs sm:text-sm mt-1">يرجى قراءة الشروط بعناية</p>
                        </div>
                    </div>
                    <button onclick="closeDisclaimer()" class="text-white hover:text-pink-200 text-xl transition-colors bg-white/10 p-2 rounded-full backdrop-blur-sm">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            
            <div class="p-4 sm:p-6">
                <!-- Warning Alert -->
                <div class="bg-orange-50 border border-orange-200 rounded-xl p-3 sm:p-4 mb-4 sm:mb-6">
                    <div class="flex items-start gap-3">
                        <div class="bg-orange-100 p-2 rounded-full flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-orange-600"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-orange-800 mb-1 text-sm sm:text-base">تنبيه مهم</h4>
                            <p class="text-orange-700 text-xs sm:text-sm">هذه الشروط مهمة لحماية جميع الأطراف. يرجى قراءتها بعناية قبل المتابعة.</p>
                        </div>
                    </div>
                </div>

                <!-- Disclaimer Content -->
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-4 sm:p-6 mb-4 sm:mb-6 max-h-64 overflow-y-auto border border-gray-200">
                    <div class="bg-white rounded-lg p-3 sm:p-4 shadow-sm">
                        <p class="text-sm sm:text-base text-gray-700 leading-relaxed mb-4 font-medium">
                            <i class="fas fa-handshake text-purple-600 ml-2"></i>
                            أُقر أنا الموقع أدناه بأنني قد طلبت خدمة غسيل للحيوان الموضح أعلاه من قبل فريق {{ config('brand.name') }}، وأفهم وأوافق على ما يلي:
                        </p>
                        <ol class="text-sm sm:text-base text-gray-700 space-y-3 list-none">
                            <li class="flex items-start gap-3 p-2 bg-gray-50 rounded-lg">
                                <span class="bg-purple-100 text-purple-600 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">1</span>
                                <span>في حال كانت حالة الحيوان الصحية أو النفسية لا تسمح بتقديم خدمة الغسيل بشكل آمن، تحتفظ إدارة {{ config('brand.name') }} بحق رفض تقديم الخدمة حفاظًا على سلامة الحيوان والفريق. وفي حال إصرار العميل على تقديم الخدمة رغم التنبيه، فإنه يتحمل كامل المسؤولية عن أي نتائج أو مضاعفات قد تحدث.</span>
                            </li>
                            <li class="flex items-start gap-3 p-2 bg-gray-50 rounded-lg">
                                <span class="bg-purple-100 text-purple-600 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">2</span>
                                <span>أُقر بأن الحيوان الأليف في عهدتي وأنني المسؤول الأول عن حالته الصحية.</span>
                            </li>
                            <li class="flex items-start gap-3 p-2 bg-gray-50 rounded-lg">
                                <span class="bg-purple-100 text-purple-600 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">3</span>
                                <span>أُدرك أن فريق العمل غير مؤهل لتشخيص أو علاج أي حالة صحية وقد لا يكون قادرًا على اكتشاف وجود أمراض أو إصابات غير ظاهرة.</span>
                            </li>
                            <li class="flex items-start gap-3 p-2 bg-gray-50 rounded-lg">
                                <span class="bg-purple-100 text-purple-600 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">4</span>
                                <span>أُخلي مسؤولية متجر {{ config('brand.name') }} وفريقه من أي ضرر أو تدهور صحي قد يظهر على الحيوان قبل أو بعد الخدمة.</span>
                            </li>
                            <li class="flex items-start gap-3 p-2 bg-gray-50 rounded-lg">
                                <span class="bg-purple-100 text-purple-600 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">5</span>
                                <span>أُخلي مسؤولية المتجر في حال تركت الحيوان في الموقع ولم أقم باستلامه خلال 3 ساعات من انتهاء الخدمة، ويحق للمتجر في هذه الحالة اتخاذ الإجراء المناسب بما في ذلك تسليمه لجمعيات الرفق بالحيوان دون أي مسؤولية قانونية عليه.</span>
                            </li>
                            <li class="flex items-start gap-3 p-2 bg-gray-50 rounded-lg">
                                <span class="bg-purple-100 text-purple-600 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">6</span>
                                <span>أتعهد بعدم الإساءة لفظيًا أو جسديًا لأي فرد من فريق العمل، وفي حال مخالفة ذلك يحق للمتجر إلغاء الخدمة فورًا واتخاذ الإجراءات النظامية اللازمة.</span>
                            </li>
                        </ol>
                    </div>
                </div>

                <!-- Checkbox with enhanced design -->
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 border border-purple-200 rounded-xl p-3 sm:p-4 mb-4 sm:mb-6">
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <input type="checkbox" id="disclaimerCheckbox" class="w-5 h-5 text-purple-600 bg-white border-2 border-purple-300 rounded focus:ring-purple-500 focus:ring-2 transition-all">
                            <div class="absolute inset-0 pointer-events-none">
                                <i class="fas fa-check text-white text-xs absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 opacity-0 transition-opacity" id="checkIcon"></i>
                            </div>
                        </div>
                        <label for="disclaimerCheckbox" class="text-sm sm:text-base text-gray-700 font-semibold cursor-pointer">
                            <i class="fas fa-shield-alt text-purple-600 ml-2"></i>
                            أقر بأنني قد قرأت وفهمت جميع الشروط أعلاه وأوافق عليها
                        </label>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 sm:gap-4">
                    <button onclick="closeDisclaimer()" class="flex-1 bg-gray-500 text-white py-3 sm:py-4 rounded-xl font-semibold hover:bg-gray-600 transition-all duration-300 text-sm sm:text-base shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                        <i class="fas fa-times ml-2"></i>
                        إلغاء
                    </button>
                    <button id="confirmButton" onclick="confirmBooking()" disabled class="flex-1 text-white py-3 sm:py-4 rounded-xl font-semibold transition-all duration-300 text-sm sm:text-base shadow-lg hover:shadow-xl transform hover:-translate-y-1 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none disabled:shadow-lg" style="background: linear-gradient(135deg, #ec4899 0%, #a855f7 100%);">
                        <i class="fas fa-check ml-2"></i>
                        تأكيد الحجز
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden Forms -->
    <form id="payLaterForm" action="{{ route('frontend.booking.confirm-later') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- JavaScript -->
    <script>
        let currentPaymentType = '';

        document.addEventListener('DOMContentLoaded', function() {
            // Flash messages
            @if(session('success'))
                alert('{{ session('success') }}');
            @endif

            @if(session('error'))
                alert('{{ session('error') }}');
            @endif

            // Disclaimer checkbox functionality
            const checkbox = document.getElementById('disclaimerCheckbox');
            const confirmButton = document.getElementById('confirmButton');
            const checkIcon = document.getElementById('checkIcon');
            
            checkbox.addEventListener('change', function() {
                confirmButton.disabled = !this.checked;
                
                if (this.checked) {
                    checkIcon.style.opacity = '1';
                    this.style.backgroundColor = '#a855f7';
                    this.style.borderColor = '#a855f7';
                } else {
                    checkIcon.style.opacity = '0';
                    this.style.backgroundColor = 'white';
                    this.style.borderColor = '#d1d5db';
                }
            });
        });

        function showDisclaimer(paymentType) {
            currentPaymentType = paymentType;
            document.getElementById('disclaimerModal').classList.add('show');
            
            // Reset checkbox state
            const checkbox = document.getElementById('disclaimerCheckbox');
            const checkIcon = document.getElementById('checkIcon');
            checkbox.checked = false;
            checkIcon.style.opacity = '0';
            checkbox.style.backgroundColor = 'white';
            checkbox.style.borderColor = '#d1d5db';
            
            document.getElementById('confirmButton').disabled = true;
        }

        function closeDisclaimer() {
            document.getElementById('disclaimerModal').classList.remove('show');
            currentPaymentType = '';
        }

        function confirmBooking() {
            if (currentPaymentType === 'later') {
                document.getElementById('payLaterForm').submit();
            } else if (currentPaymentType === 'bank') {
                window.location.href = "{{ route('frontend.booking.bank-transfer') }}";
            }
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('disclaimerModal');
            if (event.target === modal) {
                closeDisclaimer();
            }
        }
    </script>
</body>
</html> 