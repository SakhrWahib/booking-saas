<!-- Chat Bot Widget -->
<div id="chatbot-widget" class="fixed bottom-6 left-6 z-50">
    <!-- Chat Bot Icon -->
    <div id="chatbot-toggle" class="flex flex-col items-center cursor-pointer hover:scale-105 transition-all duration-300">
        <!-- الأيقونة -->
        <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full shadow-lg flex items-center justify-center relative">
            <i class="fas fa-robot text-white text-2xl"></i>
            <!-- مؤشر الاتصال -->
            <div class="absolute -top-1 -right-1 w-4 h-4 bg-green-500 rounded-full animate-pulse shadow-lg"></div>
        </div>
        <!-- نص "الذكاء الاصطناعي" -->
        <div class="bg-green-500 text-white text-xs px-2 py-1 rounded-full shadow-lg whitespace-nowrap mt-2">
            الذكاء الاصطناعي
        </div>
    </div>
    
    <!-- Chat Window -->
    <div id="chatbot-window" class="hidden absolute bottom-20 left-0 w-96 h-[500px] bg-white rounded-2xl shadow-2xl border border-gray-200 md:left-0 left-0 transform -translate-x-0 w-[95vw] max-w-sm">
        <!-- Chat Header -->
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white p-4 rounded-t-2xl flex items-center justify-between">
            <div class="flex items-center space-x-3 space-x-reverse">
                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-robot text-white"></i>
                </div>
                <div>
                    <h3 class="font-semibold">البوت الذكي</h3>
                    <p class="text-sm text-green-100 font-medium">متصل الآن</p>
                </div>
            </div>
            <button id="chatbot-close" class="text-white hover:text-blue-100 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <!-- Chat Messages -->
        <div id="chatbot-messages" class="p-4 h-80 overflow-y-auto bg-gray-50">
            <!-- Welcome Message -->
            <div class="flex items-start space-x-3 space-x-reverse mb-4">
                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-robot text-white text-sm"></i>
                </div>
                <div class="bg-white rounded-2xl rounded-tr-sm px-3 py-2 max-w-xs shadow-sm">
                    <p class="text-gray-800 text-sm font-medium mb-1">أهلاً وسهلاً بك في {{ config('brand.name') }}! 🐱🐕</p>
                    <p class="text-gray-600 text-xs mb-2">أنا البوت المساعد الخاص بك، كيف يمكنني مساعدتك اليوم؟</p>
                    <p class="text-blue-600 text-xs italic mb-2">⏰ ساعات العمل: السبت-الخميس 11ص-9م، الجمعة إجازة</p>
                    <div class="flex flex-wrap gap-1">
                        <button onclick="sendChatbotMessage('عرض الخدمات')" class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-2 py-1 rounded-full text-xs transition-all">
                            عرض الخدمات
                        </button>
                        <button onclick="sendChatbotMessage('الأسعار')" class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-2 py-1 rounded-full text-xs transition-all">
                            الأسعار
                        </button>
                        <button onclick="sendChatbotMessage('ساعات العمل')" class="bg-orange-100 hover:bg-orange-200 text-orange-700 px-2 py-1 rounded-full text-xs transition-all">
                            ساعات العمل
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Chat Input -->
        <div class="p-4 border-t border-gray-200">
            <div class="flex items-center space-x-2 space-x-reverse">
                <input 
                    type="text" 
                    id="chatbot-input"
                    placeholder="اكتب رسالتك هنا..."
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    onkeypress="handleChatbotKeyPress(event)"
                >
                <button 
                    onclick="sendChatbotMessageFromInput()"
                    class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-full flex items-center justify-center hover:from-blue-600 hover:to-purple-700 transition-all"
                >
                    <i class="fas fa-paper-plane text-xs"></i>
                </button>
            </div>
            
            <!-- Quick Actions -->
            <div class="flex flex-wrap gap-2 mt-3 justify-center">
                <button onclick="sendChatbotMessage('عرض الخدمات')" class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-3 py-1 rounded-full text-xs font-medium">
                    الخدمات
                </button>
                <button onclick="sendChatbotMessage('الأسعار')" class="bg-gradient-to-r from-green-500 to-blue-500 text-white px-3 py-1 rounded-full text-xs font-medium">
                    الأسعار
                </button>
                <button onclick="sendChatbotMessage('الحجز')" class="bg-gradient-to-r from-purple-500 to-pink-500 text-white px-3 py-1 rounded-full text-xs font-medium">
                    الحجز
                </button>
                <button onclick="sendChatbotMessage('ساعات العمل')" class="bg-gradient-to-r from-orange-500 to-red-500 text-white px-3 py-1 rounded-full text-xs font-medium">
                    ساعات العمل
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Chat Bot JavaScript -->
<script>
    let isChatbotTyping = false;
    
    // تبديل نافذة المحادثة
    document.getElementById('chatbot-toggle').addEventListener('click', function() {
        const chatWindow = document.getElementById('chatbot-window');
        chatWindow.classList.toggle('hidden');
        
        // إضافة تأثير ظهور
        if (!chatWindow.classList.contains('hidden')) {
            chatWindow.style.opacity = '0';
            chatWindow.style.transform = 'scale(0.8) translateY(20px)';
            setTimeout(() => {
                chatWindow.style.opacity = '1';
                chatWindow.style.transform = 'scale(1) translateY(0)';
            }, 10);
        }
    });
    
    // إغلاق نافذة المحادثة
    document.getElementById('chatbot-close').addEventListener('click', function() {
        document.getElementById('chatbot-window').classList.add('hidden');
    });
    
    // إرسال رسالة للبوت
    function sendChatbotMessage(message) {
        if (isChatbotTyping) return;
        
        // إضافة رسالة المستخدم
        addChatbotUserMessage(message);
        
        // إظهار مؤشر الكتابة
        showChatbotTypingIndicator();
        
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
                hideChatbotTypingIndicator();
                
                // إضافة رد البوت
                setTimeout(() => {
                    addChatbotBotMessage(data.response);
                }, 500);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            hideChatbotTypingIndicator();
            addChatbotBotMessage({
                type: 'error',
                text: 'عذراً، حدث خطأ في الاتصال',
                subtext: 'يرجى المحاولة مرة أخرى'
            });
        });
    }
    
    // إرسال رسالة من حقل الإدخال
    function sendChatbotMessageFromInput() {
        const input = document.getElementById('chatbot-input');
        const message = input.value.trim();
        
        if (message) {
            sendChatbotMessage(message);
            input.value = '';
        }
    }
    
    // معالجة الضغط على Enter
    function handleChatbotKeyPress(event) {
        if (event.key === 'Enter') {
            sendChatbotMessageFromInput();
        }
    }
    
    // إضافة رسالة المستخدم
    function addChatbotUserMessage(message) {
        const chatMessages = document.getElementById('chatbot-messages');
        const messageDiv = document.createElement('div');
        messageDiv.className = 'flex items-start space-x-3 space-x-reverse mb-4 justify-end';
        messageDiv.innerHTML = `
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-2xl rounded-tl-sm px-3 py-2 max-w-xs shadow-sm">
                <p class="font-medium text-sm">${message}</p>
            </div>
            <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-user text-gray-600 text-sm"></i>
            </div>
        `;
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    // إضافة رد البوت
    function addChatbotBotMessage(response) {
        const chatMessages = document.getElementById('chatbot-messages');
        const messageDiv = document.createElement('div');
        messageDiv.className = 'flex items-start space-x-3 space-x-reverse mb-4';
        
        let content = '';
        
        // بناء المحتوى حسب نوع الرد
        switch(response.type) {
            case 'greeting':
            case 'services':
            case 'pricing':
            case 'booking':
            case 'booking_start':
            case 'booking_info':
            case 'cat_services':
            case 'dog_services':
            case 'cat_selection':
            case 'dog_selection':
            case 'service_selection':
            case 'size_selection':
            case 'addons_confirmation':
            case 'addons_rejection':
            case 'location':
            case 'contact':
            case 'working_hours':
            case 'chatgpt_response':
            case 'default':
                content = buildChatbotResponseContent(response);
                break;
            case 'error':
                content = `
                    <p class="text-red-600 font-medium mb-1 text-sm">${response.text}</p>
                    <p class="text-gray-600 text-xs">${response.subtext}</p>
                `;
                break;
        }
        
        messageDiv.innerHTML = `
            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-robot text-white text-sm"></i>
            </div>
            <div class="bg-white rounded-2xl rounded-tr-sm px-3 py-2 max-w-xs shadow-sm">
                ${content}
            </div>
        `;
        
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    // بناء محتوى الرد
    function buildChatbotResponseContent(response) {
        let content = `
            <p class="text-gray-800 font-medium mb-1 text-sm">${response.text}</p>
            <p class="text-gray-600 text-xs mb-2">${response.subtext}</p>
        `;
        
        // إضافة الخدمات
        if (response.services) {
            content += '<div class="space-y-1 mb-2">';
            response.services.forEach(service => {
                content += `<p class="text-gray-700 text-xs">${service}</p>`;
            });
            content += '</div>';
        }
        
        // إضافة الأسعار
        if (response.prices) {
            content += '<div class="space-y-1 mb-2">';
            response.prices.forEach(price => {
                if (price.trim()) {
                    content += `<p class="text-gray-700 text-xs">${price}</p>`;
                }
            });
            content += '</div>';
        }
        
        // إضافة خطوات الحجز
        if (response.steps) {
            content += '<div class="space-y-1 mb-2">';
            response.steps.forEach(step => {
                content += `<p class="text-gray-700 text-xs">${step}</p>`;
            });
            content += '</div>';
        }
        
        // إضافة الإضافات
        if (response.addons) {
            content += '<div class="space-y-1 mb-2">';
            response.addons.forEach(addon => {
                content += `<p class="text-gray-700 text-xs">${addon}</p>`;
            });
            content += '</div>';
        }
        
        // إضافة معلومات الموقع
        if (response.location) {
            content += '<div class="space-y-1 mb-2">';
            response.location.forEach(loc => {
                content += `<p class="text-gray-700 text-xs">${loc}</p>`;
            });
            content += '</div>';
        }
        
        // إضافة معلومات الاتصال
        if (response.contact) {
            content += '<div class="space-y-1 mb-2">';
            response.contact.forEach(contact => {
                content += `<p class="text-gray-700 text-xs">${contact}</p>`;
            });
            content += '</div>';
        }
        
        // إضافة ساعات العمل
        if (response.hours) {
            content += '<div class="space-y-1 mb-2">';
            response.hours.forEach(hour => {
                content += `<p class="text-gray-700 text-xs">${hour}</p>`;
            });
            content += '</div>';
        }
        
        // إضافة معلومات الموقع للحجز
        if (response.location_info) {
            content += '<div class="space-y-1 mb-2">';
            response.location_info.forEach(loc => {
                content += `<p class="text-gray-700 text-xs">${loc}</p>`;
            });
            content += '</div>';
        }
        
        // إضافة ملاحظة
        if (response.note) {
            content += `<p class="text-blue-600 text-xs italic mb-2">${response.note}</p>`;
        }
        
        // إضافة الاقتراحات
        if (response.suggestions) {
            content += '<div class="flex flex-wrap gap-1">';
            response.suggestions.forEach(suggestion => {
                content += `
                    <button onclick="sendChatbotMessage('${suggestion}')" class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-2 py-1 rounded-full text-xs transition-all">
                        ${suggestion}
                    </button>
                `;
            });
            content += '</div>';
        }
        
        return content;
    }
    
    // إظهار مؤشر الكتابة
    function showChatbotTypingIndicator() {
        isChatbotTyping = true;
        const chatMessages = document.getElementById('chatbot-messages');
        const typingDiv = document.createElement('div');
        typingDiv.id = 'chatbot-typing';
        typingDiv.className = 'flex items-start space-x-3 space-x-reverse mb-4';
        typingDiv.innerHTML = `
            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-robot text-white text-sm"></i>
            </div>
            <div class="bg-white rounded-2xl rounded-tr-sm px-3 py-2">
                <div class="flex space-x-1 space-x-reverse">
                    <div class="w-2 h-2 bg-blue-500 rounded-full animate-bounce"></div>
                    <div class="w-2 h-2 bg-blue-500 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                    <div class="w-2 h-2 bg-blue-500 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                </div>
            </div>
        `;
        chatMessages.appendChild(typingDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    // إخفاء مؤشر الكتابة
    function hideChatbotTypingIndicator() {
        isChatbotTyping = false;
        const typingDiv = document.getElementById('chatbot-typing');
        if (typingDiv) {
            typingDiv.remove();
        }
    }
    
    // إضافة تأثيرات CSS
    const chatWindow = document.getElementById('chatbot-window');
    chatWindow.style.transition = 'all 0.3s ease';
    chatWindow.style.opacity = '0';
    chatWindow.style.transform = 'scale(0.8) translateY(20px)';
</script>
