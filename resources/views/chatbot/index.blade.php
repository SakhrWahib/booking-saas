<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ChatGPT البوت - {{ config('brand.name') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Cairo', sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .chat-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .message-bubble {
            animation: slideIn 0.3s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .bot-avatar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .suggestion-chip {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .suggestion-chip:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }
        
        .typing-indicator {
            display: none;
        }
        
        .typing-indicator.show {
            display: flex;
        }
        
        .typing-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #667eea;
            animation: typing 1.4s infinite ease-in-out;
        }
        
        .typing-dot:nth-child(1) { animation-delay: -0.32s; }
        .typing-dot:nth-child(2) { animation-delay: -0.16s; }
        
        @keyframes typing {
            0%, 80%, 100% { transform: scale(0); }
            40% { transform: scale(1); }
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-full shadow-lg mb-4">
                <img src="{{ asset('images/logo.png') }}" alt="{{ config('brand.name') }}" class="w-16 h-16 rounded-full">
            </div>
            <h1 class="text-4xl font-bold gradient-text mb-2">{{ config('brand.name') }}</h1>
            <p class="text-gray-600 text-lg">البوت الذكي المساعد 🐱🐕</p>
        </div>
        
        <!-- Chat Container -->
        <div class="chat-container p-6">
            <!-- Chat Header -->
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                <div class="flex items-center space-x-3 space-x-reverse">
                    <div class="bot-avatar w-12 h-12 rounded-full flex items-center justify-center text-white text-xl">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">البوت المساعد (ChatGPT)</h3>
                        <p class="text-sm text-gray-500">متصل الآن</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2 space-x-reverse">
                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                    <span class="text-sm text-gray-500">متصل</span>
                </div>
            </div>
            
            <!-- Chat Messages -->
            <div id="chat-messages" class="space-y-4 mb-6 max-h-96 overflow-y-auto">
                <!-- Welcome Message -->
                <div class="message-bubble flex items-start space-x-3 space-x-reverse">
                    <div class="bot-avatar w-10 h-10 rounded-full flex items-center justify-center text-white text-sm flex-shrink-0">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div class="bg-blue-50 rounded-2xl rounded-tr-sm px-4 py-3 max-w-xs lg:max-w-md">
                        <p class="text-gray-800 font-medium mb-2">أهلاً وسهلاً بك في {{ config('brand.name') }}! 🐱🐕</p>
                        <p class="text-gray-600 text-sm mb-3">أنا البوت المساعد الخاص بك، كيف يمكنني مساعدتك اليوم؟</p>
                        <div class="flex flex-wrap gap-2">
                            <button onclick="sendMessage('عرض الخدمات')" class="suggestion-chip bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-1 rounded-full text-sm transition-all">
                                عرض الخدمات
                            </button>
                            <button onclick="sendMessage('الأسعار')" class="suggestion-chip bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-1 rounded-full text-sm transition-all">
                                الأسعار
                            </button>
                            <button onclick="sendMessage('الحجز')" class="suggestion-chip bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-1 rounded-full text-sm transition-all">
                                الحجز
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Typing Indicator -->
            <div id="typing-indicator" class="typing-indicator flex items-start space-x-3 space-x-reverse mb-4">
                <div class="bot-avatar w-10 h-10 rounded-full flex items-center justify-center text-white text-sm flex-shrink-0">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="bg-gray-100 rounded-2xl rounded-tr-sm px-4 py-3">
                    <div class="flex space-x-1 space-x-reverse">
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
                    </div>
                </div>
            </div>
            
            <!-- Input Area -->
            <div class="flex items-center space-x-3 space-x-reverse">
                <div class="flex-1 relative">
                    <input 
                        type="text" 
                        id="message-input"
                        placeholder="اكتب رسالتك هنا..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        onkeypress="handleKeyPress(event)"
                    >
                    <button 
                        onclick="sendMessageFromInput()"
                        class="absolute left-3 top-1/2 transform -translate-y-1/2 bg-blue-500 hover:bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center transition-colors"
                    >
                        <i class="fas fa-paper-plane text-sm"></i>
                    </button>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="mt-4 flex flex-wrap gap-2 justify-center">
                <button onclick="sendMessage('عرض الخدمات')" class="suggestion-chip bg-gradient-to-r from-blue-500 to-purple-500 text-white px-4 py-2 rounded-full text-sm font-medium">
                    <i class="fas fa-list mr-2"></i>الخدمات
                </button>
                <button onclick="sendMessage('الأسعار')" class="suggestion-chip bg-gradient-to-r from-green-500 to-blue-500 text-white px-4 py-2 rounded-full text-sm font-medium">
                    <i class="fas fa-tags mr-2"></i>الأسعار
                </button>
                <button onclick="sendMessage('الحجز')" class="suggestion-chip bg-gradient-to-r from-purple-500 to-pink-500 text-white px-4 py-2 rounded-full text-sm font-medium">
                    <i class="fas fa-calendar-alt mr-2"></i>الحجز
                </button>
                <button onclick="sendMessage('معلومات الاتصال')" class="suggestion-chip bg-gradient-to-r from-orange-500 to-red-500 text-white px-4 py-2 rounded-full text-sm font-medium">
                    <i class="fas fa-phone mr-2"></i>اتصل بنا
                </button>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="text-center mt-6 text-gray-500 text-sm">
            <p>© 2024 {{ config('brand.name') }} - جميع الحقوق محفوظة</p>
            <p class="mt-1">خدمة منزلية مضمونة في الرياض 🏠</p>
        </div>
    </div>

    <script>
        let isTyping = false;
        
        // إرسال رسالة
        function sendMessage(message) {
            if (isTyping) return;
            
            // إضافة رسالة المستخدم
            addUserMessage(message);
            
            // إظهار مؤشر الكتابة
            showTypingIndicator();
            
            // إرسال الرسالة للخادم
            fetch('/chatbot/process-message', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ message: message })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // إخفاء مؤشر الكتابة
                    hideTypingIndicator();
                    
                    // إضافة رد البوت
                    setTimeout(() => {
                        addBotMessage(data.response);
                    }, 500);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                hideTypingIndicator();
                addBotMessage({
                    type: 'error',
                    text: 'عذراً، حدث خطأ في الاتصال',
                    subtext: 'يرجى المحاولة مرة أخرى'
                });
            });
        }
        
        // إرسال رسالة من حقل الإدخال
        function sendMessageFromInput() {
            const input = document.getElementById('message-input');
            const message = input.value.trim();
            
            if (message) {
                sendMessage(message);
                input.value = '';
            }
        }
        
        // معالجة الضغط على Enter
        function handleKeyPress(event) {
            if (event.key === 'Enter') {
                sendMessageFromInput();
            }
        }
        
        // إضافة رسالة المستخدم
        function addUserMessage(message) {
            const chatMessages = document.getElementById('chat-messages');
            const messageDiv = document.createElement('div');
            messageDiv.className = 'message-bubble flex items-start space-x-3 space-x-reverse justify-end';
            messageDiv.innerHTML = `
                <div class="bg-blue-500 text-white rounded-2xl rounded-tl-sm px-4 py-3 max-w-xs lg:max-w-md">
                    <p class="font-medium">${message}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 flex-shrink-0">
                    <i class="fas fa-user"></i>
                </div>
            `;
            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
        
        // إضافة رد البوت
        function addBotMessage(response) {
            const chatMessages = document.getElementById('chat-messages');
            const messageDiv = document.createElement('div');
            messageDiv.className = 'message-bubble flex items-start space-x-3 space-x-reverse';
            
            let content = '';
            
            // بناء المحتوى حسب نوع الرد
            switch(response.type) {
                case 'greeting':
                case 'services':
                case 'pricing':
                case 'booking':
                case 'cat_services':
                case 'dog_services':
                case 'location':
                case 'contact':
                case 'working_hours':
                case 'chatgpt_response':
                case 'default':
                    content = buildResponseContent(response);
                    break;
                case 'error':
                    content = `
                        <p class="text-red-600 font-medium mb-2">${response.text}</p>
                        <p class="text-gray-600 text-sm">${response.subtext}</p>
                    `;
                    break;
            }
            
            messageDiv.innerHTML = `
                <div class="bot-avatar w-10 h-10 rounded-full flex items-center justify-center text-white text-sm flex-shrink-0">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="bg-blue-50 rounded-2xl rounded-tr-sm px-4 py-3 max-w-xs lg:max-w-md">
                    ${content}
                </div>
            `;
            
            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
        
        // بناء محتوى الرد
        function buildResponseContent(response) {
            let content = `
                <p class="text-gray-800 font-medium mb-2">${response.text}</p>
                <p class="text-gray-600 text-sm mb-3">${response.subtext}</p>
            `;
            
            // إضافة الخدمات
            if (response.services) {
                content += '<div class="space-y-1 mb-3">';
                response.services.forEach(service => {
                    content += `<p class="text-gray-700 text-sm">${service}</p>`;
                });
                content += '</div>';
            }
            
            // إضافة الأسعار
            if (response.prices) {
                content += '<div class="space-y-1 mb-3">';
                response.prices.forEach(price => {
                    if (price.trim()) {
                        content += `<p class="text-gray-700 text-sm">${price}</p>`;
                    }
                });
                content += '</div>';
            }
            
            // إضافة خطوات الحجز
            if (response.steps) {
                content += '<div class="space-y-1 mb-3">';
                response.steps.forEach(step => {
                    content += `<p class="text-gray-700 text-sm">${step}</p>`;
                });
                content += '</div>';
            }
            
            // إضافة معلومات الموقع
            if (response.location) {
                content += '<div class="space-y-1 mb-3">';
                response.location.forEach(loc => {
                    content += `<p class="text-gray-700 text-sm">${loc}</p>`;
                });
                content += '</div>';
            }
            
            // إضافة معلومات الاتصال
            if (response.contact) {
                content += '<div class="space-y-1 mb-3">';
                response.contact.forEach(contact => {
                    content += `<p class="text-gray-700 text-sm">${contact}</p>`;
                });
                content += '</div>';
            }
            
            // إضافة ساعات العمل
            if (response.hours) {
                content += '<div class="space-y-1 mb-3">';
                response.hours.forEach(hour => {
                    content += `<p class="text-gray-700 text-sm">${hour}</p>`;
                });
                content += '</div>';
            }
            
            // إضافة ملاحظة
            if (response.note) {
                content += `<p class="text-blue-600 text-sm italic mb-3">${response.note}</p>`;
            }
            
            // إضافة الاقتراحات
            if (response.suggestions) {
                content += '<div class="flex flex-wrap gap-2">';
                response.suggestions.forEach(suggestion => {
                    content += `
                        <button onclick="sendMessage('${suggestion}')" class="suggestion-chip bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-1 rounded-full text-sm transition-all">
                            ${suggestion}
                        </button>
                    `;
                });
                content += '</div>';
            }
            
            return content;
        }
        
        // إظهار مؤشر الكتابة
        function showTypingIndicator() {
            isTyping = true;
            const indicator = document.getElementById('typing-indicator');
            indicator.classList.add('show');
            indicator.scrollIntoView({ behavior: 'smooth' });
        }
        
        // إخفاء مؤشر الكتابة
        function hideTypingIndicator() {
            isTyping = false;
            const indicator = document.getElementById('typing-indicator');
            indicator.classList.remove('show');
        }
        
        // تحميل الصفحة
        document.addEventListener('DOMContentLoaded', function() {
            // التركيز على حقل الإدخال
            document.getElementById('message-input').focus();
        });
    </script>
</body>
</html>
