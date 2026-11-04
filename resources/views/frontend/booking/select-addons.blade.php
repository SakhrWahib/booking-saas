<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>اختيار الإضافات - {{ config('brand.name') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">
    
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css'])
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
        .addon-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .addon-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        .addon-card.selected {
            border-color: #ec4899;
            background: linear-gradient(135deg, #fdf2f8 0%, #f3e8ff 100%);
        }
        .price-summary {
            position: sticky;
            top: 2rem;
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
                    <!-- Step 1: Active -->
                    <div class="flex items-center gap-1 sm:gap-2">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-pink-600 text-white rounded-full flex items-center justify-center text-xs sm:text-sm font-semibold">
                            1
                        </div>
                        <span class="text-xs sm:text-sm font-medium text-pink-600 hidden sm:inline">اختيار الإضافات</span>
                        <span class="text-xs font-medium text-pink-600 sm:hidden">الإضافات</span>
                    </div>
                    
                    <div class="w-4 sm:w-8 h-px bg-gray-300"></div>
                    
                    <!-- Step 2: Pending -->
                    <div class="flex items-center gap-1 sm:gap-2">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gray-300 text-gray-500 rounded-full flex items-center justify-center text-xs sm:text-sm font-semibold">
                            2
                        </div>
                        <span class="text-xs sm:text-sm font-medium text-gray-500 hidden sm:inline">تحديد الموقع</span>
                        <span class="text-xs font-medium text-gray-500 sm:hidden">الموقع</span>
                    </div>
                    
                    <div class="w-4 sm:w-8 h-px bg-gray-300"></div>
                    
                    <!-- Step 3: Pending -->
                    <div class="flex items-center gap-1 sm:gap-2">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gray-300 text-gray-500 rounded-full flex items-center justify-center text-xs sm:text-sm font-semibold">
                            3
                        </div>
                        <span class="text-xs sm:text-sm font-medium text-gray-500 hidden sm:inline">اختيار الموعد</span>
                        <span class="text-xs font-medium text-gray-500 sm:hidden">الموعد</span>
                    </div>
                    
                    <div class="w-4 sm:w-8 h-px bg-gray-300"></div>
                    
                    <!-- Step 4: Pending -->
                    <div class="flex items-center gap-1 sm:gap-2">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gray-300 text-gray-500 rounded-full flex items-center justify-center text-xs sm:text-sm font-semibold">
                            4
                        </div>
                        <span class="text-xs sm:text-sm font-medium text-gray-500 hidden sm:inline">بيانات العميل</span>
                        <span class="text-xs font-medium text-gray-500 sm:hidden">البيانات</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-3 sm:px-4 py-6 sm:py-8">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
                <!-- Product & Add-ons Selection -->
                <div class="lg:col-span-2">
                    <!-- Product Info -->
                    <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 mb-4 sm:mb-6">
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

                        <div class="text-center mb-4 sm:mb-6">
                            <!-- صورة المنتج -->
                            @if($product->image)
                                <div class="mb-3 sm:mb-4">
                                    <img src="{{ asset('storage/' . $product->image) }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-24 h-24 sm:w-32 sm:h-32 object-cover rounded-2xl mx-auto shadow-lg">
                                </div>
                            @endif
                        </div>

                        <!-- Selected Product -->
                        <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl p-4 sm:p-6 mb-4 sm:mb-6">
                            <h4 class="font-bold text-base sm:text-lg text-gray-800 mb-2 flex items-center gap-2">
                                <i class="fas fa-star text-yellow-500"></i>
                                الخدمة المختارة
                            </h4>
                            <div class="flex items-center justify-between">
                                <div>
                                    <h5 class="text-lg sm:text-xl font-bold text-purple-800">{{ $product->name }}</h5>
                                    <p class="text-gray-600 text-xs sm:text-sm mt-1">{{ $product->description ?? 'خدمة عالية الجودة لحيواناتك الأليفة' }}</p>
                                </div>
                                <div class="text-left">
                                    <span class="text-xl sm:text-2xl font-bold text-purple-600">{{ number_format($product->price, 2) }}</span>
                                    <span class="text-gray-500 text-xs sm:text-sm"><span class="icon-saudi_riyal"></span></span>
                                </div>
                            </div>
                        </div>

                        <!-- Add-ons Form -->
                        <form action="{{ route('frontend.booking.save-addons') }}" method="POST" id="addonsForm">
                            @csrf
                            
                            <!-- Loading State -->
                            <div id="loading-addons" class="text-center py-6 sm:py-8">
                                <div class="inline-flex items-center gap-2 text-gray-600">
                                    <i class="fas fa-spinner fa-spin text-pink-600"></i>
                                    <span class="text-sm sm:text-base">جاري تحميل الإضافات المتاحة...</span>
                                </div>
                            </div>

                            <!-- No Add-ons Available -->
                            <div id="no-addons" class="hidden text-center py-6 sm:py-8">
                                <div class="bg-gray-50 rounded-xl p-6 sm:p-8">
                                    <div class="bg-gray-200 w-12 h-12 sm:w-16 sm:h-16 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                                        <i class="fas fa-info-circle text-xl sm:text-2xl text-gray-500"></i>
                                    </div>
                                    <h4 class="text-base sm:text-lg font-bold text-gray-600 mb-2">لا توجد إضافات متاحة</h4>
                                    <p class="text-gray-500 text-sm sm:text-base">يمكنك المتابعة للخطوة التالية مباشرة</p>
                                </div>
                            </div>

                            <!-- Add-ons Grid -->
                            <div id="addons-grid" class="hidden">
                                <h4 class="text-lg sm:text-xl font-bold text-gray-800 mb-3 sm:mb-4 flex items-center gap-2">
                                    <i class="fas fa-puzzle-piece text-pink-600"></i>
                                    الإضافات المتاحة
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4" id="addons-container">
                                    <!-- Add-ons will be loaded here via JavaScript -->
                                </div>
                            </div>

                            <!-- Error Message -->
                            <div id="error-message" class="hidden bg-red-50 border border-red-200 rounded-xl p-3 sm:p-4 mb-3 sm:mb-4">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                                    <span class="text-red-800 font-medium text-sm sm:text-base">حدث خطأ في تحميل الإضافات</span>
                                </div>
                                <p class="text-red-700 text-xs sm:text-sm mt-1" id="error-text"></p>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 mt-6 sm:mt-8">
                                <a href="{{ route('home') }}" 
                                   class="flex-1 bg-gray-500 text-white py-3 sm:py-4 rounded-xl font-semibold hover:bg-gray-600 transition-colors text-center text-sm sm:text-base">
                                    <i class="fas fa-arrow-right ml-1 sm:ml-2"></i>
                                    <span class="hidden sm:inline">العودة للمنتجات</span>
                                    <span class="sm:hidden">العودة</span>
                                </a>
                                <button type="submit" 
                                        class="flex-1 bg-gradient-to-r from-pink-600 to-purple-600 text-white py-3 sm:py-4 rounded-xl font-semibold hover:from-pink-700 hover:to-purple-700 transition-all text-sm sm:text-base">
                                    <i class="fas fa-arrow-left ml-1 sm:ml-2"></i>
                                    <span class="hidden sm:inline">المتابعة لتحديد الموقع</span>
                                    <span class="sm:hidden">المتابعة</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Price Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 price-summary">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-4 sm:mb-6 flex items-center gap-2">
                            <i class="fas fa-receipt text-pink-600"></i>
                            ملخص الأسعار
                        </h3>

                        <div class="space-y-3 sm:space-y-4">
                            <!-- Base Service -->
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 text-sm sm:text-base">الخدمة الأساسية</span>
                                <span class="font-semibold text-sm sm:text-base" id="base-price">{{ number_format($product->price, 2) }} <span class="icon-saudi_riyal"></span></span>
                            </div>

                            <!-- Selected Add-ons -->
                            <div id="selected-addons-summary" class="hidden">
                                <div class="border-t pt-3 sm:pt-4">
                                    <h4 class="font-semibold text-gray-700 mb-2 text-sm sm:text-base">الإضافات المختارة</h4>
                                    <div id="addon-items" class="space-y-2">
                                        <!-- Selected add-ons will appear here -->
                                    </div>
                                </div>
                            </div>

                            <!-- Subtotal -->
                            <div class="border-t pt-3 sm:pt-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 text-sm sm:text-base">المجموع الفرعي</span>
                                    <span class="font-semibold text-sm sm:text-base" id="subtotal">{{ number_format($product->price, 2) }} <span class="icon-saudi_riyal"></span></span>
                                </div>
                            </div>

                            <!-- Coupon Section -->
                            <div class="border-t pt-3 sm:pt-4">
                                <div class="mb-3">
                                    <label for="coupon_code" class="block text-sm font-medium text-gray-700 mb-2">كوبون الخصم</label>
                                    <div class="flex gap-2">
                                        <input type="text" 
                                               id="coupon_code" 
                                               name="coupon_code" 
                                               placeholder="أدخل كود الكوبون"
                                               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500 text-sm">
                                        <button type="button" 
                                                id="validate-coupon-btn"
                                                class="bg-pink-600 text-white px-4 py-2 rounded-lg hover:bg-pink-700 transition-colors text-sm font-medium">
                                            تحقق
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Coupon Message -->
                                <div id="coupon-message" class="hidden mb-3">
                                    <div id="coupon-success" class="hidden bg-green-50 border border-green-200 rounded-lg p-3">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-check-circle text-green-600"></i>
                                            <span class="text-green-800 text-sm" id="coupon-success-text"></span>
                                        </div>
                                    </div>
                                    <div id="coupon-error" class="hidden bg-red-50 border border-red-200 rounded-lg p-3">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                                            <span class="text-red-800 text-sm" id="coupon-error-text"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Applied Coupon -->
                                <div id="applied-coupon" class="hidden mb-3">
                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                <i class="fas fa-tag text-blue-600"></i>
                                                <span class="text-blue-800 text-sm" id="applied-coupon-name"></span>
                                            </div>
                                            <button type="button" 
                                                    id="remove-coupon-btn"
                                                    class="text-blue-600 hover:text-blue-800 text-sm">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Discount -->
                            <div id="discount-section" class="hidden border-t pt-3 sm:pt-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 text-sm sm:text-base">الخصم</span>
                                    <span class="font-semibold text-green-600 text-sm sm:text-base" id="discount-amount">-0.00 <span class="icon-saudi_riyal"></span></span>
                                </div>
                            </div>

                            <!-- Total -->
                            <div class="border-t pt-3 sm:pt-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-base sm:text-lg font-bold text-gray-800">المجموع الكلي</span>
                                    <span class="text-xl sm:text-2xl font-bold text-pink-600" id="total-price">{{ number_format($product->price, 2) }} <span class="icon-saudi_riyal"></span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const productId = {{ $product->id }};
            const basePrice = {{ $product->price }};
            let selectedAddOns = [];
            let appliedCoupon = @json(session('booking.coupon_code') ? [
                'code' => session('booking.coupon_code'),
                'id' => session('booking.coupon_id'),
                'name' => \App\Models\Coupon::find(session('booking.coupon_id'))->name ?? 'كوبون الخصم'
            ] : null);
            let discountAmount = {{ session('booking.discount_amount', 0) }};
            
            // Load product add-ons
            loadProductAddOns();
            
            // Show applied coupon if exists
            if (appliedCoupon) {
                showAppliedCoupon(appliedCoupon.name);
                showDiscountSection();
                updatePriceSummary();
            }
            
                                     function loadProductAddOns() {
                fetch(`{{ route('frontend.booking.api.product-addons') }}?product_id=${productId}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
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
                    document.getElementById('loading-addons').classList.add('hidden');
                    
                    if (data.success && data.add_ons && data.add_ons.length > 0) {
                        displayAddOns(data.add_ons);
                    } else {
                        document.getElementById('no-addons').classList.remove('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error loading add-ons:', error);
                    document.getElementById('loading-addons').classList.add('hidden');
                    showError('حدث خطأ في تحميل الإضافات. يرجى إعادة تحميل الصفحة. الخطأ: ' + error.message);
                });
            }
            
            function displayAddOns(addOns) {
                const container = document.getElementById('addons-container');
                const grid = document.getElementById('addons-grid');
                
                container.innerHTML = '';
                
                // عرض أول إضافتين كبطاقات كبيرة
                const firstTwoAddOns = addOns.slice(0, 2);
                const remainingAddOns = addOns.slice(2);
                
                // إنشاء البطاقات الكبيرة للإضافتين الأوليين
                firstTwoAddOns.forEach((addon, index) => {
                    const addonCard = createAddOnCard(addon, index);
                    container.appendChild(addonCard);
                });
                
                // إنشاء قائمة منسدلة للإضافات المتبقية (إذا وجدت)
                if (remainingAddOns.length > 0) {
                    const dropdownContainer = createAddOnsDropdown(remainingAddOns, 2);
                    container.appendChild(dropdownContainer);
                }
                
                grid.classList.remove('hidden');
            }
            
            function createAddOnCard(addon, index) {
                const div = document.createElement('div');
                div.className = 'addon-card bg-white border-2 border-gray-200 rounded-xl p-3 sm:p-4';
                div.setAttribute('data-addon-index', index);
                
                // إنشاء معرف فريد لكل إضافة
                const uniqueId = `${addon.option}_${addon.name}_${index}`;
                
                div.innerHTML = `
                    <div class="flex items-start gap-2 sm:gap-3">
                        <div class="flex-shrink-0 mt-1">
                            <input type="checkbox" 
                                   id="addon_${uniqueId}" 
                                   name="selected_add_ons[]" 
                                   value='${JSON.stringify(addon)}'
                                   data-addon-id="${uniqueId}"
                                   class="w-4 h-4 sm:w-5 sm:h-5 text-pink-600 border-gray-300 rounded focus:ring-pink-500">
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-1 sm:gap-2 mb-1 sm:mb-2">
                                <span class="bg-purple-100 text-purple-600 px-1 sm:px-2 py-1 rounded text-xs font-medium">
                                    ${addon.option}
                                </span>
                            </div>
                            <h5 class="font-bold text-gray-800 mb-1 text-sm sm:text-base">${addon.name}</h5>
                            <p class="text-gray-600 text-xs sm:text-sm mb-2">${addon.description || 'إضافة مميزة لتحسين الخدمة'}</p>
                            <div class="flex items-center justify-between">
                                <span class="text-base sm:text-lg font-bold text-pink-600">${parseFloat(addon.price).toFixed(2)} <span class="icon-saudi_riyal"></span></span>
                                <span class="text-xs sm:text-sm text-gray-500">إضافة اختيارية</span>
                            </div>
                        </div>
                    </div>
                `;
                
                // Add click event
                const checkbox = div.querySelector('input[type="checkbox"]');
                checkbox.addEventListener('change', function() {
                    toggleAddOn(addon, this.checked, div, uniqueId);
                });
                
                div.addEventListener('click', function(e) {
                    if (e.target.type !== 'checkbox') {
                        checkbox.checked = !checkbox.checked;
                        checkbox.dispatchEvent(new Event('change'));
                    }
                });
                
                return div;
            }
            
            function createAddOnsDropdown(addOns, startIndex) {
                const div = document.createElement('div');
                div.className = 'addon-dropdown bg-white border-2 border-gray-200 rounded-xl p-3 sm:p-4';
                
                const dropdownId = `addons-dropdown-${startIndex}`;
                
                div.innerHTML = `
                    <div class="mb-3">
                        <button type="button" 
                                class="w-full text-left flex items-center justify-between p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors"
                                onclick="toggleDropdown('${dropdownId}')">
                            <span class="font-semibold text-gray-700">
                                <i class="fas fa-chevron-down ml-2"></i>
                                إضافة منتجات شراء للعناية بحيوانك الأليف
                            </span>
                            <span class="text-sm text-gray-500">اضغط للعرض</span>
                        </button>
                    </div>
                    <div id="${dropdownId}" class="hidden space-y-3">
                        ${addOns.map((addon, index) => {
                            const actualIndex = startIndex + index;
                            const uniqueId = `${addon.option}_${addon.name}_${actualIndex}`;
                            return `
                                <div class="addon-item bg-gray-50 rounded-lg p-3 border border-gray-200">
                                    <div class="flex items-start gap-3">
                                        <div class="flex-shrink-0 mt-1">
                                            <input type="checkbox" 
                                                   id="addon_${uniqueId}" 
                                                   name="selected_add_ons[]" 
                                                   value='${JSON.stringify(addon)}'
                                                   data-addon-id="${uniqueId}"
                                                   class="w-4 h-4 text-pink-600 border-gray-300 rounded focus:ring-pink-500">
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-2">
                                                <span class="bg-purple-100 text-purple-600 px-2 py-1 rounded text-xs font-medium">
                                                    ${addon.option}
                                                </span>
                                                <img src="{{ url('addon-images.php') }}?name=${encodeURIComponent(addon.name)}" alt="${addon.name}" class="w-28 h-28 rounded-3xl object-cover shadow-2xl">
                                            </div>
                                            <h6 class="font-semibold text-gray-800 mb-1 text-sm">${addon.name}</h6>
                                            <p class="text-gray-600 text-xs mb-2">${addon.description || 'إضافة مميزة لتحسين الخدمة'}</p>
                                            <div class="flex items-center justify-between">
                                                <span class="text-sm font-bold text-pink-600">${parseFloat(addon.price).toFixed(2)} <span class="icon-saudi_riyal"></span></span>
                                                <span class="text-xs text-gray-500">إضافة اختيارية</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        }).join('')}
                    </div>
                `;
                
                // إضافة أحداث النقر للإضافات في القائمة المنسدلة
                addOns.forEach((addon, index) => {
                    const actualIndex = startIndex + index;
                    const uniqueId = `${addon.option}_${addon.name}_${actualIndex}`;
                    
                    // استخدام event delegation بدلاً من setTimeout
                    div.addEventListener('change', function(e) {
                        if (e.target && e.target.id === `addon_${uniqueId}`) {
                            const addonItem = e.target.closest('.addon-item');
                            toggleAddOn(addon, e.target.checked, addonItem, uniqueId);
                        }
                    });
                    
                    // إضافة حدث النقر على العنصر
                    div.addEventListener('click', function(e) {
                        const addonItem = e.target.closest('.addon-item');
                        if (addonItem) {
                            const checkbox = addonItem.querySelector(`#addon_${uniqueId}`);
                            if (checkbox && e.target !== checkbox) {
                                checkbox.checked = !checkbox.checked;
                                checkbox.dispatchEvent(new Event('change'));
                            }
                        }
                    });
                });
                
                return div;
            }
            
            // تعريف دالة toggleDropdown في النطاق العام
            window.toggleDropdown = function(dropdownId) {
                const dropdown = document.getElementById(dropdownId);
                const button = dropdown.previousElementSibling;
                const icon = button.querySelector('i');
                const statusText = button.querySelector('span:last-child');
                
                if (dropdown.classList.contains('hidden')) {
                    dropdown.classList.remove('hidden');
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                    statusText.textContent = 'اضغط للإخفاء';
                } else {
                    dropdown.classList.add('hidden');
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                    statusText.textContent = 'اضغط للعرض';
                }
            };
            
            function toggleAddOn(addon, isSelected, cardElement, uniqueId) {
                if (isSelected) {
                    // إضافة الإضافة مع المعرف الفريد
                    const addonWithId = {
                        ...addon,
                        uniqueId: uniqueId
                    };
                    selectedAddOns.push(addonWithId);
                    if (cardElement) {
                        cardElement.classList.add('selected');
                        // إضافة تأثير بصري للعناصر في القائمة المنسدلة
                        if (cardElement.classList.contains('addon-item')) {
                            cardElement.style.backgroundColor = '#fdf2f8';
                            cardElement.style.borderColor = '#ec4899';
                        }
                    }
                } else {
                    // إزالة الإضافة المحددة فقط باستخدام المعرف الفريد
                    selectedAddOns = selectedAddOns.filter(item => item.uniqueId !== uniqueId);
                    if (cardElement) {
                        cardElement.classList.remove('selected');
                        // إزالة التأثير البصري للعناصر في القائمة المنسدلة
                        if (cardElement.classList.contains('addon-item')) {
                            cardElement.style.backgroundColor = '';
                            cardElement.style.borderColor = '';
                        }
                    }
                }
                
                updatePriceSummary();
            }
            
                         function updatePriceSummary() {
                 const addOnsTotal = selectedAddOns.reduce((sum, addon) => sum + parseFloat(addon.price), 0);
                 const subtotal = basePrice + addOnsTotal;
                 const finalTotal = subtotal - discountAmount;
                 
                 // Update price display
                 document.getElementById('subtotal').innerHTML = subtotal.toFixed(2) + ' <span class="icon-saudi_riyal"></span>';
                 document.getElementById('total-price').innerHTML = finalTotal.toFixed(2) + ' <span class="icon-saudi_riyal"></span>';
                
                // Update selected add-ons summary
                const summaryContainer = document.getElementById('selected-addons-summary');
                const itemsContainer = document.getElementById('addon-items');
                
                if (selectedAddOns.length > 0) {
                    // تجميع الإضافات المتطابقة مع عداد
                    const addonCounts = {};
                    selectedAddOns.forEach(addon => {
                        const key = `${addon.option}_${addon.name}`;
                        if (!addonCounts[key]) {
                            addonCounts[key] = {
                                name: addon.name,
                                price: parseFloat(addon.price),
                                count: 0
                            };
                        }
                        addonCounts[key].count++;
                    });
                    
                    itemsContainer.innerHTML = Object.values(addonCounts).map(addon => {
                        const totalPrice = addon.price * addon.count;
                        const displayText = addon.count > 1 ? `${addon.name} (${addon.count})` : addon.name;
                        return `
                            <div class="flex justify-between items-center text-xs sm:text-sm">
                                <span class="text-gray-600">${displayText}</span>
                                <span class="font-medium">${totalPrice.toFixed(2)} <span class="icon-saudi_riyal"></span></span>
                            </div>
                        `;
                    }).join('');
                    summaryContainer.classList.remove('hidden');
                } else {
                    summaryContainer.classList.add('hidden');
                }
            }
            
            function showError(message) {
                const errorDiv = document.getElementById('error-message');
                const errorText = document.getElementById('error-text');
                errorText.textContent = message;
                errorDiv.classList.remove('hidden');
                console.error('Error:', message);
            }
            
                         // Form submission
             document.getElementById('addonsForm').addEventListener('submit', function(e) {
                 e.preventDefault(); // منع الإرسال الافتراضي
                 
                 // Show loading state
                 const submitBtn = this.querySelector('button[type="submit"]');
                 const originalText = submitBtn.innerHTML;
                 submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin ml-1 sm:ml-2"></i><span class="hidden sm:inline">جاري الحفظ...</span><span class="sm:hidden">جاري الحفظ</span>';
                 submitBtn.disabled = true;
                 
                 // Create form data
                 const formData = new FormData();
                 formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                 
                 selectedAddOns.forEach((addon, index) => {
                     formData.append(`selected_add_ons[${index}][option]`, addon.option);
                     formData.append(`selected_add_ons[${index}][name]`, addon.name);
                     formData.append(`selected_add_ons[${index}][price]`, addon.price);
                     if (addon.image) {
                         formData.append(`selected_add_ons[${index}][image]`, addon.image);
                     }
                     // إزالة المعرف الفريد قبل الإرسال
                     delete addon.uniqueId;
                 });

                 // Add coupon data if applied
                 if (appliedCoupon) {
                     formData.append('coupon_code', appliedCoupon.code);
                     formData.append('coupon_id', appliedCoupon.id);
                     formData.append('discount_amount', discountAmount);
                 }
                 
                 // Submit using fetch
                 fetch(this.action, {
                     method: 'POST',
                     body: formData,
                     headers: {
                         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                     }
                 })
                 .then(response => {
                     if (response.ok) {
                         // If successful, redirect to location page
                         window.location.href = "{{ route('frontend.booking.select-location') }}";
                     } else {
                         throw new Error('Network response was not ok');
                     }
                 })
                 .catch(error => {
                     console.error('Error:', error);
                     alert('حدث خطأ أثناء حفظ الإضافات. يرجى المحاولة مرة أخرى.');
                     
                     // Restore button state
                     submitBtn.innerHTML = originalText;
                     submitBtn.disabled = false;
                 });
             });
            
            // Flash messages
            @if(session('success'))
                alert('{{ session('success') }}');
            @endif

            @if(session('error'))
                alert('{{ session('error') }}');
            @endif

            // Coupon functionality
            document.getElementById('validate-coupon-btn').addEventListener('click', validateCoupon);
            document.getElementById('remove-coupon-btn').addEventListener('click', removeCoupon);
            
            function validateCoupon() {
                const couponCode = document.getElementById('coupon_code').value.trim();
                if (!couponCode) {
                    showCouponError('يرجى إدخال كود الكوبون');
                    return;
                }

                const addOnsTotal = selectedAddOns.reduce((sum, addon) => sum + parseFloat(addon.price), 0);
                const totalAmount = basePrice + addOnsTotal;

                // Show loading state
                const btn = document.getElementById('validate-coupon-btn');
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                btn.disabled = true;

                fetch('{{ route("frontend.validate-coupon") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        coupon_code: couponCode,
                        total_amount: totalAmount
                    })
                })
                .then(response => response.json())
                .then(data => {
                    btn.innerHTML = originalText;
                    btn.disabled = false;

                    if (data.success) {
                        appliedCoupon = data.coupon;
                        discountAmount = parseFloat(data.discount_amount) || 0;
                        
                        showCouponSuccess(data.message);
                        showAppliedCoupon(data.coupon.name);
                        showDiscountSection();
                        updatePriceSummary();
                        
                        // Hide coupon input
                        document.getElementById('coupon_code').value = '';
                    } else {
                        showCouponError(data.message);
                        removeCoupon();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    showCouponError('حدث خطأ أثناء التحقق من الكوبون');
                });
            }

            function removeCoupon() {
                appliedCoupon = null;
                discountAmount = 0;
                
                hideCouponMessage();
                hideAppliedCoupon();
                hideDiscountSection();
                updatePriceSummary();
            }

            function showCouponSuccess(message) {
                document.getElementById('coupon-message').classList.remove('hidden');
                document.getElementById('coupon-success').classList.remove('hidden');
                document.getElementById('coupon-error').classList.add('hidden');
                document.getElementById('coupon-success-text').textContent = message;
            }

            function showCouponError(message) {
                document.getElementById('coupon-message').classList.remove('hidden');
                document.getElementById('coupon-error').classList.remove('hidden');
                document.getElementById('coupon-success').classList.add('hidden');
                document.getElementById('coupon-error-text').textContent = message;
            }

            function hideCouponMessage() {
                document.getElementById('coupon-message').classList.add('hidden');
            }

            function showAppliedCoupon(couponName) {
                document.getElementById('applied-coupon').classList.remove('hidden');
                document.getElementById('applied-coupon-name').textContent = couponName;
            }

            function hideAppliedCoupon() {
                document.getElementById('applied-coupon').classList.add('hidden');
            }

            function showDiscountSection() {
                document.getElementById('discount-section').classList.remove('hidden');
                document.getElementById('discount-amount').innerHTML = `-${discountAmount.toFixed(2)} <span class="icon-saudi_riyal"></span>`;
            }

            function hideDiscountSection() {
                document.getElementById('discount-section').classList.add('hidden');
            }
        });
    </script>
</body>
</html>
