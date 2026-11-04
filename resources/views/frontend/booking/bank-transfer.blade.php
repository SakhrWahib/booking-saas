<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>التحويل البنكي - {{ config('brand.name') }}</title>
    
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
        .bank-detail {
            transition: all 0.2s;
            cursor: pointer;
        }
        .bank-detail:hover {
            background-color: #f3f4f6;
        }
        .copy-success {
            animation: fadeInOut 2s ease-in-out;
        }
        @keyframes fadeInOut {
            0%, 100% { opacity: 0; }
            50% { opacity: 1; }
        }
        
        /* Responsive Design Enhancements */
        @media (max-width: 768px) {
            .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            
            .text-3xl {
                font-size: 1.875rem;
            }
            
            .text-2xl {
                font-size: 1.5rem;
            }
            
            .p-6 {
                padding: 1rem;
            }
            
            .gap-8 {
                gap: 1.5rem;
            }
            
            .grid-cols-1 {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 640px) {
            .text-3xl {
                font-size: 1.5rem;
            }
            
            .text-2xl {
                font-size: 1.25rem;
            }
            
            .p-6 {
                padding: 0.75rem;
            }
            
            .gap-3 {
                gap: 0.5rem;
            }
            
            .flex.items-start.gap-3 {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .flex.items-start.gap-3 > div:first-child {
                margin-bottom: 0.5rem;
            }
        }
        
        /* Tablet Optimizations */
        @media (min-width: 641px) and (max-width: 1024px) {
            .max-w-4xl {
                max-width: 100%;
            }
            
            .lg\:grid-cols-2 {
                grid-template-columns: 1fr;
            }
        }
        
        /* Animation for notices */
        .notice-animation {
            animation: slideInUp 0.5s ease-out;
        }
        
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Hover effects for better UX */
        .bank-detail:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        /* Better touch targets for mobile */
        @media (max-width: 768px) {
            .bank-detail {
                min-height: 60px;
            }
            
            button, .bank-detail {
                min-height: 44px;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="gradient-bg text-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <a href="{{ route('home') }}" class="flex items-center gap-3 hover:opacity-80 transition-opacity">
                    <div class="bg-white/20 p-2 rounded-full">
                        <i class="fas fa-cat text-2xl"></i>
                    </div>
                    <h1 class="text-xl font-bold">{{ config('brand.name') }}</h1>
                </a>
                <a href="{{ route('home') }}" class="text-pink-200 hover:text-white transition-colors">
                    <i class="fas fa-home ml-2"></i>
                    العودة للرئيسية
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="bg-green-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-university text-3xl text-green-600"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-800 mb-2">التحويل البنكي</h2>
                <p class="text-gray-600">حول المبلغ المطلوب وارفع إيصال التحويل</p>
            </div>

            <!-- Top Alert Banner -->
            <div class="bg-gradient-to-r from-red-50 to-orange-50 border-l-4 border-red-500 rounded-lg p-6 mb-6 shadow-lg ring-2 ring-red-100">
                <div class="flex items-start gap-4">
                    <div class="bg-red-100 p-3 rounded-full flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-red-800 mb-3 flex items-center gap-2">
                            <i class="fas fa-bell text-red-600"></i>
                            تنبيه مهم جداً للعملاء الكرام
                        </h3>
                        <div class="space-y-2">
                            <p class="text-red-700 font-semibold text-base">
                                ⚠️ يرجى الاحتفاظ بإيصال التحويل البنكي معكم
                            </p>
                            <p class="text-red-600 text-sm bg-red-100 p-3 rounded-lg border border-red-200">
                                <i class="fas fa-info-circle ml-1"></i>
                                <strong>سوف</strong> نتواصل معكم بعد إتمام الحجز ونطلب منكم إرفاق إيصال التحويل البنكي، لذلك من الضروري جداً الاحتفاظ بهذا الإيصال معكم حتى نتمكن من تأكيد حجزكم.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Bank Details -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <i class="fas fa-building text-green-600"></i>
                        تفاصيل التحويل البنكي
                    </h3>

                    <!-- Bank Logo/Name -->
                    <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
                        <div class="flex items-center gap-3">
                            <div class="bg-green-600 w-12 h-12 rounded-full flex items-center justify-center">
                                <i class="fas fa-university text-white text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-xl font-bold text-green-800">{{ $bankDetails['bank_name'] }}</h4>
                                <p class="text-green-600 text-sm">البنك المعتمد</p>
                            </div>
                        </div>
                    </div>

                    <!-- Bank Account Details -->
                    <div class="space-y-4">
                        <!-- Account Holder -->
                        <div class="bank-detail border rounded-lg p-4" onclick="copyToClipboard('{{ $bankDetails['account_holder'] }}', this)">
                            <div class="flex justify-between items-center">
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-1">اسم صاحب الحساب</label>
                                    <p class="text-lg font-semibold text-gray-800">{{ $bankDetails['account_holder'] }}</p>
                                </div>
                                <div class="bg-gray-100 p-2 rounded-full">
                                    <i class="fas fa-copy text-gray-600"></i>
                                </div>
                            </div>
                        </div>

                        <!-- IBAN -->
                        <div class="bank-detail border rounded-lg p-4" onclick="copyToClipboard('{{ $bankDetails['iban'] }}', this)">
                            <div class="flex justify-between items-center">
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-1">رقم الآيبان (IBAN)</label>
                                    <p class="text-lg font-mono font-semibold text-gray-800 tracking-wider">{{ $bankDetails['iban'] }}</p>
                                </div>
                                <div class="bg-gray-100 p-2 rounded-full">
                                    <i class="fas fa-copy text-gray-600"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Account Number -->
                        <div class="bank-detail border rounded-lg p-4" onclick="copyToClipboard('{{ $bankDetails['account_number'] }}', this)">
                            <div class="flex justify-between items-center">
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-1">رقم الحساب</label>
                                    <p class="text-lg font-mono font-semibold text-gray-800 tracking-wider">{{ $bankDetails['account_number'] }}</p>
                                </div>
                                <div class="bg-gray-100 p-2 rounded-full">
                                    <i class="fas fa-copy text-gray-600"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Amount Breakdown -->
                        <div class="bg-pink-50 border border-pink-200 rounded-lg p-4">
                            <label class="block text-sm font-medium text-pink-700 mb-3">تفاصيل المبلغ المطلوب تحويله</label>
                            
                            <!-- Subtotal -->
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600">المجموع الفرعي</span>
                                <span class="font-semibold">{{ number_format($bookingData['subtotal'] ?? $bookingData['price_without_tax'], 2) }} <span class="icon-saudi_riyal"></span></span>
                            </div>

                            <!-- Discount -->
                            @if(isset($bookingData['discount_amount']) && $bookingData['discount_amount'] > 0)
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600">الخصم</span>
                                <span class="font-semibold text-green-600">-{{ number_format($bookingData['discount_amount'], 2) }} <span class="icon-saudi_riyal"></span></span>
                            </div>
                            @endif

                            <!-- Price After Discount -->
                            @if(isset($bookingData['discount_amount']) && $bookingData['discount_amount'] > 0)
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600">السعر بعد الخصم</span>
                                <span class="font-semibold">{{ number_format($bookingData['price_without_tax'], 2) }} <span class="icon-saudi_riyal"></span></span>
                            </div>
                            @endif
                            
                            <!-- Tax -->
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-gray-600">قيمة الضريبة (15%)</span>
                                <span class="font-semibold">{{ number_format($bookingData['tax_amount'], 2) }} <span class="icon-saudi_riyal"></span></span>
                            </div>
                            
                            <!-- Total -->
                            <div class="border-t pt-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-bold text-pink-700">المبلغ الإجمالي</span>
                                    <span class="text-2xl font-bold text-pink-600">{{ number_format($bookingData['total_price'], 2) }} <span class="icon-saudi_riyal"></span></span>
                                </div>
                            </div>
                        </div>


                    </div>

                    <!-- Copy Success Message -->
                    <div id="copyMessage" class="hidden bg-green-100 border border-green-200 text-green-700 px-4 py-2 rounded-lg mt-4 text-center">
                        <i class="fas fa-check-circle ml-1"></i>
                        تم نسخ البيانات بنجاح!
                    </div>

                  
                </div>

                <!-- Upload Receipt -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <i class="fas fa-receipt text-pink-600"></i>
                        رفع إيصال التحويل
                    </h3>

                    <form action="{{ route('frontend.booking.upload-receipt') }}" method="POST" enctype="multipart/form-data" id="receiptForm">
                        @csrf

                        <!-- Upload Area -->
                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center mb-6" id="uploadArea">
                            <div class="mb-4">
                                <i class="fas fa-cloud-upload-alt text-6xl text-gray-400"></i>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-700 mb-2">ارفع إيصال التحويل</h4>
                            <p class="text-gray-500 mb-4">اسحب الملف هنا أو انقر للاختيار</p>
                            <input type="file" 
                                   id="receipt" 
                                   name="receipt" 
                                   accept="image/*" 
                                   class="hidden" 
                                   required>
                            <button type="button" 
                                    onclick="document.getElementById('receipt').click()" 
                                    class="bg-pink-600 text-white px-6 py-2 rounded-lg hover:bg-pink-700 transition-colors">
                                اختيار ملف
                            </button>
                        </div>

                        <!-- File Preview -->
                        <div id="filePreview" class="hidden mb-6">
                            <div class="border rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="bg-green-100 p-2 rounded-full">
                                            <i class="fas fa-image text-green-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800" id="fileName">-</p>
                                            <p class="text-sm text-gray-500" id="fileSize">-</p>
                                        </div>
                                    </div>
                                    <button type="button" 
                                            onclick="removeFile()" 
                                            class="text-red-600 hover:text-red-800 transition-colors">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <img id="imagePreview" class="w-full h-48 object-contain bg-gray-50 rounded-lg mt-3">
                            </div>
                        </div>

                        <!-- Important Notice -->
                        <div class="bg-red-50 border-2 border-red-200 rounded-lg p-4 mb-4">
                            <h4 class="font-bold text-red-800 mb-2 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle text-red-600"></i>
                                تنبيه أخير
                            </h4>
                            <p class="text-red-700 text-sm font-semibold">
                                <i class="fas fa-hand-point-right ml-1"></i>
                                <strong>احتفظوا بإيصال التحويل الأصلي معكم!</strong> سنتواصل معكم قريباً ونطلب منكم إرفاق إيصال التحويل البنكي لتأكيد حجزكم.
                            </p>
                        </div>

                        <!-- File Requirements -->
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                            <h4 class="font-semibold text-yellow-800 mb-2 flex items-center gap-2">
                                <i class="fas fa-exclamation-triangle"></i>
                                متطلبات الملف
                            </h4>
                            <ul class="text-yellow-700 text-sm space-y-1">
                                <li>• يجب أن يكون الملف صورة واضحة للإيصال</li>
                                <li>• الصيغ المقبولة: JPG, PNG, GIF</li>
                                <li>• الحد الأقصى لحجم الملف: 5 ميجابايت</li>
                                <li>• تأكد من وضوح جميع تفاصيل التحويل في الصورة</li>
                            </ul>
                        </div>



                        <!-- Customer Reference -->
                        <div class="bg-gray-50 rounded-lg p-4 mb-6">
                            <h4 class="font-semibold text-gray-800 mb-2">مرجع الحجز</h4>
                            <p class="text-gray-600 text-sm">اسم العميل: <strong>{{ $bookingData['customer_name'] }}</strong></p>
                            <p class="text-gray-600 text-sm">المبلغ الإجمالي: <strong>{{ number_format($bookingData['total_price'], 2) }} <span class="icon-saudi_riyal"></span></strong></p>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-green-600 to-green-700 text-white py-4 rounded-xl font-semibold hover:from-green-700 hover:to-green-800 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                                id="submitBtn"
                                disabled>
                            <i class="fas fa-check ml-2"></i>
                            تأكيد الحجز مع الإيصال
                        </button>
                    </form>


                    <!-- Alternative Actions -->
                    <div class="mt-6 space-y-3">
                        <a href="{{ route('frontend.booking.payment-details') }}" 
                           class="block w-full bg-gray-500 text-white py-3 rounded-xl font-semibold hover:bg-gray-600 transition-colors text-center">
                            <i class="fas fa-arrow-right ml-2"></i>
                            العودة لطرق الدفع
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        function copyToClipboard(text, element) {
            navigator.clipboard.writeText(text).then(function() {
                // Show success message
                const message = document.getElementById('copyMessage');
                message.classList.remove('hidden');
                message.classList.add('copy-success');
                
                // Hide after 2 seconds
                setTimeout(() => {
                    message.classList.add('hidden');
                    message.classList.remove('copy-success');
                }, 2000);
                
                // Visual feedback on clicked element
                element.classList.add('bg-green-50', 'border-green-300');
                setTimeout(() => {
                    element.classList.remove('bg-green-50', 'border-green-300');
                }, 1000);
            }).catch(function(err) {
                alert('فشل في نسخ النص. يرجى النسخ يدوياً.');
            });
        }

        // File upload handling
        document.getElementById('receipt').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file size (5MB max)
                if (file.size > 5 * 1024 * 1024) {
                    alert('حجم الملف كبير جداً. الحد الأقصى 5 ميجابايت.');
                    this.value = '';
                    return;
                }
                
                // Validate file type
                if (!file.type.startsWith('image/')) {
                    alert('يرجى اختيار ملف صورة صحيح.');
                    this.value = '';
                    return;
                }
                
                // Show preview
                showFilePreview(file);
                
                // Enable submit button
                document.getElementById('submitBtn').disabled = false;
            }
        });

        function showFilePreview(file) {
            const preview = document.getElementById('filePreview');
            const fileName = document.getElementById('fileName');
            const fileSize = document.getElementById('fileSize');
            const imagePreview = document.getElementById('imagePreview');
            
            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);
            
            // Show image preview
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
            };
            reader.readAsDataURL(file);
            
            preview.classList.remove('hidden');
            document.getElementById('uploadArea').classList.add('hidden');
        }

        function removeFile() {
            document.getElementById('receipt').value = '';
            document.getElementById('filePreview').classList.add('hidden');
            document.getElementById('uploadArea').classList.remove('hidden');
            document.getElementById('submitBtn').disabled = true;
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Drag and drop functionality
        const uploadArea = document.getElementById('uploadArea');
        
        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('border-pink-500', 'bg-pink-50');
        });
        
        uploadArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.classList.remove('border-pink-500', 'bg-pink-50');
        });
        
        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('border-pink-500', 'bg-pink-50');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                document.getElementById('receipt').files = files;
                document.getElementById('receipt').dispatchEvent(new Event('change'));
            }
        });

        // Form submission
        document.getElementById('receiptForm').addEventListener('submit', function(e) {
            const file = document.getElementById('receipt').files[0];
            if (!file) {
                e.preventDefault();
                alert('يرجى اختيار إيصال التحويل أولاً.');
                return;
            }
        });

        // Flash messages
        document.addEventListener('DOMContentLoaded', function() {
            // Add animation to notices
            const notices = document.querySelectorAll('.bg-gradient-to-r');
            notices.forEach((notice, index) => {
                setTimeout(() => {
                    notice.classList.add('notice-animation');
                }, index * 200);
            });

            // Show flash messages with better styling
            @if(session('success'))
                showNotification('{{ session('success') }}', 'success');
            @endif

            @if(session('error'))
                showNotification('{{ session('error') }}', 'error');
            @endif
        });

        // Custom notification function
        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm transform transition-all duration-300 ${
                type === 'success' 
                    ? 'bg-green-500 text-white' 
                    : 'bg-red-500 text-white'
            }`;
            
            notification.innerHTML = `
                <div class="flex items-center gap-3">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
                    <span>${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="text-white hover:text-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 5000);
        }

        // Add smooth scrolling for better UX
        function scrollToElement(elementId) {
            const element = document.getElementById(elementId);
            if (element) {
                element.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'center' 
                });
            }
        }
    </script>
</body>
</html> 