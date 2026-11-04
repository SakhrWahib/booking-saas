<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use App\Services\ChatGptService;
use App\Config\ChatBotConfig;

class ChatBotController extends Controller
{
    /**
     * عرض واجهة البوت
     */
    public function index()
    {
        return view('chatbot.index');
    }

    /**
     * معالجة رسائل المستخدم
     */
    public function processMessage(Request $request)
    {
        $message = $request->input('message', '');
        $message = trim($message);
        
        if (empty($message)) {
            return response()->json([
                'success' => false,
                'message' => 'يرجى كتابة رسالة'
            ]);
        }

        // تحليل الرسالة وتحديد الرد المناسب
        $response = $this->analyzeMessage($message);
        
        return response()->json([
            'success' => true,
            'response' => $response,
            'timestamp' => now()->format('H:i')
        ]);
    }

    /**
     * تحليل الرسالة وتحديد الرد
     */
    private function analyzeMessage($message)
    {
        $message = mb_strtolower($message, 'UTF-8');
        
        // أولاً: التحقق من نوع السؤال (أولوية عالية)
        if ($this->isGeneralQuestion($message)) {
            return $this->getChatGptResponse($message);
        }
        
        // ثانياً: البحث عن الكلمات المفتاحية
        if ($this->containsKeywords($message, ChatBotConfig::KEYWORDS['greeting'])) {
            return $this->getGreetingResponse();
        }
        
        if ($this->containsKeywords($message, ChatBotConfig::KEYWORDS['services'])) {
            return $this->getServicesResponse();
        }
        
        // التعرف على أسئلة المستلزمات والمنتجات (لكن ليس الأسئلة العامة)
        if ($this->isProductQuestion($message)) {
            return $this->getStoreLinkResponse();
        }
        
        if ($this->containsKeywords($message, ChatBotConfig::KEYWORDS['pricing'])) {
            return $this->getPricingResponse();
        }
        
        if ($this->containsKeywords($message, ChatBotConfig::KEYWORDS['booking'])) {
            return $this->startBookingFlow();
        }
        
        // فقط إذا كان السؤال يتعلق بالخدمات المحددة
        if ($this->containsKeywords($message, ChatBotConfig::KEYWORDS['cat_services'])) {
            return $this->getCatServicesResponse();
        }
        
        if ($this->containsKeywords($message, ChatBotConfig::KEYWORDS['dog_services'])) {
            return $this->getDogServicesResponse();
        }
        
        if ($this->containsKeywords($message, ChatBotConfig::KEYWORDS['location'])) {
            return $this->getLocationResponse();
        }
        
        if ($this->containsKeywords($message, ChatBotConfig::KEYWORDS['contact'])) {
            return $this->getContactResponse();
        }
        
        if ($this->containsKeywords($message, ChatBotConfig::KEYWORDS['working_hours'])) {
            return $this->getWorkingHoursResponse();
        }
        
        // إذا لم يتم التعرف على الرسالة، استخدم ChatGPT
        return $this->getChatGptResponse($message);
    }

    /**
     * التحقق من وجود كلمات مفتاحية
     */
    private function containsKeywords($message, $keywords)
    {
        foreach ($keywords as $keyword) {
            if (mb_strpos($message, $keyword) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * رد الترحيب
     */
    private function getGreetingResponse()
    {
        return [
            'type' => 'greeting',
            'text' => 'أهلاً وسهلاً بك في ' . ChatBotConfig::COMPANY_NAME . '! 🐱🐕',
            'subtext' => 'أنا البوت المساعد الخاص بك، كيف يمكنني مساعدتك اليوم؟',
            'note' => '⏰ ساعات العمل: ' . ChatBotConfig::WORKING_HOURS['home_services'] . '، ' . ChatBotConfig::WORKING_HOURS['friday'] . ' إجازة',
            'suggestions' => [
                'عرض الخدمات',
                'الأسعار',
                'الحجز',
                'ساعات العمل',
                'معلومات الاتصال'
            ]
        ];
    }

    /**
     * عرض الخدمات
     */
    private function getServicesResponse()
    {
        // جلب الخدمات من قاعدة البيانات
        $products = Product::where('is_active', true)
                          ->orderBy('name')
                          ->get();
        
        $services = [];
        foreach ($products as $product) {
            $icon = $this->getProductIcon($product->name);
            $services[] = "{$icon} {$product->name}";
        }
        
        return [
            'type' => 'services',
            'text' => 'خدماتنا المتوفرة 🎯',
            'subtext' => 'نقدم مجموعة شاملة من خدمات العناية بالحيوانات الأليفة:',
            'services' => $services,
            'suggestions' => [
                'الأسعار',
                'الحجز',
                'معلومات أكثر'
            ]
        ];
    }

    /**
     * رابط متجر المستلزمات
     */
    private function getStoreLinkResponse()
    {
        return [
            'type' => 'store_link',
            'text' => 'متجر المستلزمات 🛍️',
            'subtext' => 'جميع مستلزمات حيواناتك الأليفة في مكان واحد!',
            'note' => '💡 اذهب للمتجر وشوف بنفسك جميع المنتجات المتوفرة',
            'store_link' => 'https://' . ChatBotConfig::WEBSITES['store'] . '/',
            'suggestions' => [
                'عرض الخدمات',
                'الأسعار',
                'الحجز',
                'معلومات الاتصال'
            ]
        ];
    }

    /**
     * عرض الأسعار
     */
    private function getPricingResponse()
    {
        // جلب الخدمات والأسعار من قاعدة البيانات
        $products = Product::where('is_active', true)
                          ->orderBy('name')
                          ->get();
        
        $prices = [];
        $currentCategory = '';
        
        foreach ($products as $product) {
            $icon = $this->getProductIcon($product->name);
            $category = $this->getProductCategory($product->name);
            
            // إضافة عنوان الفئة إذا تغيرت
            if ($category !== $currentCategory) {
                if ($currentCategory !== '') {
                    $prices[] = ''; // سطر فارغ بين الفئات
                }
                $prices[] = "{$icon} {$category}:";
                $currentCategory = $category;
            }
            
            $prices[] = "   • {$product->name}: " . number_format($product->price, 2) . " ريال";
        }
        
        // إضافة رابط الحجز مباشرة
        $prices[] = '';
        $prices[] = '💡 للحجز: **' . ChatBotConfig::WEBSITES['booking'] . '** 🎯';
        
        return [
            'type' => 'pricing',
            'text' => 'أسعار خدماتنا 💰',
            'subtext' => 'أسعار مناسبة وشفافة لجميع الخدمات:',
            'prices' => $prices,
            'note' => '🎯 للحجز: https://' . ChatBotConfig::WEBSITES['booking'],
            'suggestions' => [
                'الحجز الآن',
                'عرض الخدمات',
                'معلومات الإضافات'
            ]
        ];
    }

    /**
     * بدء عملية الحجز التفاعلية
     */
    private function startBookingFlow()
    {
        return [
            'type' => 'booking_start',
            'text' => 'ممتاز! سأساعدك في الحجز 🎯',
            'subtext' => 'سأوجهك خطوة بخطوة لإتمام الحجز بسهولة',
            'steps' => [
                '1️⃣ اختر نوع الحيوان (قطط/كلاب)',
                '2️⃣ اختر الخدمة المطلوبة',
                '3️⃣ اختر الإضافات (اختياري)',
                '4️⃣ حدد موقعك',
                '5️⃣ اختر التاريخ والوقت',
                '6️⃣ أدخل بياناتك',
                '7️⃣ أكد الحجز'
            ],
            'note' => '💡 الخدمة منزلية - نأتي إليك في المكان المحدد',
            'suggestions' => [
                'ابدأ الحجز الآن',
                'عرض الخدمات أولاً',
                'عرض الأسعار'
            ]
        ];
    }

    /**
     * معلومات الحجز العامة
     */
    private function getBookingResponse()
    {
        return [
            'type' => 'booking_info',
            'text' => 'كيفية الحجز 📅',
            'subtext' => 'الحجز سهل وبسيط:',
            'steps' => [
                '1️⃣ اختر الخدمة المطلوبة',
                '   اختر الإضافات (اختياري)',
                '   وحدد موقعك',
                '2️⃣ حدد التاريخ والوقت المناسب',
                '3️⃣ أدخل بياناتك في حاله لن تقوم بتسجيل الدخول',
                '4️⃣ أكد الحجز'
            ],
            'note' => '💡 الخدمة منزلية - نأتي إليك في المكان المحدد',
            'suggestions' => [
                'ابدأ الحجز',
                'عرض الخدمات',
                'الأسعار'
            ]
        ];
    }

    /**
     * خدمات القطط
     */
    private function getCatServicesResponse()
    {
        return [
            'type' => 'cat_services',
            'text' => 'خدمات القطط 🐱',
            'subtext' => 'خدمات متخصصة للقطط:',
            'services' => [
                '🛁 غسيل شامل مع شامبو مخصص للقطط',
                '💨 تجفيف آمن ومريح',
                '✂️ قص الشعر حسب الطلب',
                '💅 قص الأظافر',
                '👂 تنظيف الأذنين',
                '🦷 تنظيف الأسنان',
                '💆 مساج مهدئ'
            ],
            'prices' => [
                '💰 الأسعار:',
                '• عناية كات شوب: 55 ريال',
                '• شاور مائي: 50 ريال',
                '• عناية جافة: 48.33 ريال'
            ],
            'suggestions' => [
                'الحجز الآن',
                'خدمات الكلاب',
                'الأسعار'
            ]
        ];
    }

    /**
     * خدمات الكلاب
     */
    private function getDogServicesResponse()
    {
        return [
            'type' => 'dog_services',
            'text' => 'خدمات الكلاب 🐕',
            'subtext' => 'خدمات شاملة للكلاب بجميع الأحجام:',
            'services' => [
                '🛁 غسيل شامل مع شامبو علاجي',
                '💨 تجفيف بالمجفف المناسب',
                '✂️ قص الشعر والتجميل',
                '💅 قص الأظافر',
                '👂 تنظيف الأذنين',
                '🦷 تنظيف الأسنان واللثة',
                '💆 مساج مهدئ'
            ],
            'prices' => [
                '💰 الأسعار:',
                '• الكلاب الكبيرة: 70-99 ريال',
                '• الكلاب الصغيرة: 55-56.01 ريال'
            ],
            'suggestions' => [
                'الحجز الآن',
                'خدمات القطط',
                'الأسعار'
            ]
        ];
    }

    /**
     * معلومات الموقع
     */
    private function getLocationResponse()
    {
        return [
            'type' => 'location',
            'text' => 'موقعنا 📍',
            'subtext' => 'نقدم خدماتنا في ' . ChatBotConfig::COMPANY_LOCATION . ':',
            'location' => [
                '🏙️ المدينة: ' . ChatBotConfig::COMPANY_LOCATION,
                '🌍 المنطقة: جميع أحياء ' . ChatBotConfig::COMPANY_LOCATION,
                '🚗 التغطية: خدمة منزلية شاملة',
                '📍 نأتي إليك في منزلك'
            ],
            'note' => '💡 لا تحتاج للذهاب إلينا - نحن نأتي إليك!',
            'suggestions' => [
                'الحجز الآن',
                'معلومات الاتصال',
                'ساعات العمل'
            ]
        ];
    }

    /**
     * معلومات الاتصال
     */
    private function getContactResponse()
    {
        return [
            'type' => 'contact',
            'text' => 'معلومات الاتصال 📞',
            'subtext' => 'تواصل معنا:',
            'contact' => [
                '📱 الهاتف: ' . config('brand.phone'),
                '📧 البريد الإلكتروني: ' . ChatBotConfig::COMPANY_EMAIL,
                '🌐 الموقع: ' . ChatBotConfig::WEBSITES['main'],
                '💬 واتساب: متوفر'
            ],
            'note' => '💡 يمكنك الحجز مباشرة من الموقع أو الاتصال بنا',
            'suggestions' => [
                'الحجز الآن',
                'عرض الخدمات',
                'الموقع'
            ]
        ];
    }

    /**
     * ساعات العمل
     */
    private function getWorkingHoursResponse()
    {
        return [
            'type' => 'working_hours',
            'text' => 'ساعات العمل ⏰',
            'subtext' => 'نعمل من السبت للخميس (' . ChatBotConfig::WORKING_HOURS['friday'] . '):',
            'hours' => [
                '🌅 السبت: ' . ChatBotConfig::WORKING_HOURS['home_services'],
                '🌅 الأحد: ' . ChatBotConfig::WORKING_HOURS['home_services'],
                '🌅 الاثنين: ' . ChatBotConfig::WORKING_HOURS['home_services'],
                '🌅 الثلاثاء: ' . ChatBotConfig::WORKING_HOURS['home_services'],
                '🌅 الأربعاء: ' . ChatBotConfig::WORKING_HOURS['home_services'],
                '🌅 الخميس: ' . ChatBotConfig::WORKING_HOURS['home_services'],
                '🌅 الجمعة: ' . ChatBotConfig::WORKING_HOURS['friday']
            ],
            'note' => '💡 يمكن الحجز في أي وقت من خلال الموقع، حتى خارج ساعات العمل!',
            'suggestions' => [
                'الحجز الآن',
                'عرض الخدمات',
                'الأسعار',
                'معلومات الاتصال'
            ]
        ];
    }

    /**
     * اختيار القطط
     */
    private function getCatSelectionResponse()
    {
        // جلب خدمات القطط من قاعدة البيانات
        $catProducts = Product::where('is_active', true)
                             ->where(function($query) {
                                 $query->where('name', 'like', '%قطط%')
                                       ->orWhere('name', 'like', '%قط%');
                             })
                             ->orderBy('name')
                             ->get();
        
        $services = [];
        $suggestions = [];
        
        foreach ($catProducts as $index => $product) {
            $number = $index + 1;
            $services[] = "{$number}️⃣ {$product->name} - " . number_format($product->price, 2) . " ريال";
            $suggestions[] = $product->name;
        }
        
        return [
            'type' => 'cat_selection',
            'text' => 'ممتاز! اخترت القطط 🐱',
            'subtext' => 'الآن اختر نوع الخدمة:',
            'services' => $services,
            'suggestions' => $suggestions
        ];
    }

    /**
     * اختيار الكلاب
     */
    private function getDogSelectionResponse()
    {
        // جلب خدمات الكلاب من قاعدة البيانات
        $dogProducts = Product::where('is_active', true)
                             ->where(function($query) {
                                 $query->where('name', 'like', '%كلاب%')
                                       ->orWhere('name', 'like', '%كلب%');
                             })
                             ->orderBy('name')
                             ->get();
        
        $services = [];
        $suggestions = [];
        
        foreach ($dogProducts as $index => $product) {
            $number = $index + 1;
            $services[] = "{$number}️⃣ {$product->name} - " . number_format($product->price, 2) . " ريال";
            $suggestions[] = $product->name;
        }
        
        return [
            'type' => 'dog_selection',
            'text' => 'ممتاز! اخترت الكلاب 🐕',
            'subtext' => 'الآن اختر نوع الخدمة:',
            'services' => $services,
            'suggestions' => $suggestions
        ];
    }

    /**
     * اختيار الخدمة
     */
    private function getServiceSelectionResponse($serviceType)
    {
        // البحث عن الخدمات التي تحتوي على النوع المطلوب
        $matchingProducts = Product::where('is_active', true)
                                  ->where('name', 'like', "%{$serviceType}%")
                                  ->orderBy('name')
                                  ->get();
        
        $services = [];
        $suggestions = [];
        
        foreach ($matchingProducts as $product) {
            $icon = $this->getProductIcon($product->name);
            $services[] = "{$icon} {$product->name}: " . number_format($product->price, 2) . " ريال";
            $suggestions[] = $product->name;
        }
        
        // إذا لم توجد خدمات مطابقة، استخدم الرد الافتراضي
        if ($matchingProducts->isEmpty()) {
            $services[] = "🐱 القطط: أسعار متغيرة";
            $services[] = "🐕 الكلاب الصغيرة: أسعار متغيرة";
            $services[] = "🐕 الكلاب الكبيرة: أسعار متغيرة";
            $suggestions = ['صغيرة', 'كبيرة', 'قطط'];
        }
        
        return [
            'type' => 'service_selection',
            'text' => "ممتاز! اخترت {$serviceType} 🎯",
            'subtext' => 'الآن اختر حجم الحيوان:',
            'services' => $services,
            'suggestions' => $suggestions
        ];
    }

    /**
     * اختيار الحجم
     */
    private function getSizeSelectionResponse($size)
    {
        return [
            'type' => 'size_selection',
            'text' => "ممتاز! اخترت الحجم {$size} 📏",
            'subtext' => 'الآن اختر الإضافات (اختياري):',
            'addons' => [
                '✅ قص أظافر - 15 ريال',
                '✅ تنظيف أذنين - 20 ريال',
                '✅ تنظيف أسنان - 25 ريال',
                '✅ حلاقة شعر - 30 ريال',
                '✅ مساج مهدئ - 35 ريال'
            ],
            'note' => '💡 يمكنك اختيار إضافة واحدة أو أكثر أو عدم اختيار أي إضافات',
            'suggestions' => [
                'نعم، أريد إضافات',
                'لا، بدون إضافات',
                'عرض الأسعار'
            ]
        ];
    }

    /**
     * تأكيد الإضافات
     */
    private function getConfirmationResponse()
    {
        return [
            'type' => 'addons_confirmation',
            'text' => 'ممتاز! 🎉',
            'subtext' => 'الآن حدد موقعك:',
            'location_info' => [
                '📍 نقدم الخدمة في الرياض فقط',
                '🏠 الخدمة منزلية - نأتي إليك',
                '📝 أدخل عنوانك بالتفصيل'
            ],
            'suggestions' => [
                'أدخل موقعي',
                'عرض الخدمات',
                'الأسعار'
            ]
        ];
    }

    /**
     * رفض الإضافات
     */
    private function getRejectionResponse()
    {
        return [
            'type' => 'addons_rejection',
            'text' => 'حسناً، بدون إضافات 👍',
            'subtext' => 'الآن حدد موقعك:',
            'location_info' => [
                '📍 نقدم الخدمة في الرياض فقط',
                '🏠 الخدمة منزلية - نأتي إليك',
                '📝 أدخل عنوانك بالتفصيل'
            ],
            'suggestions' => [
                'أدخل موقعي',
                'عرض الخدمات',
                'الأسعار'
            ]
        ];
    }

    /**
     * الرد الافتراضي
     */
    private function getDefaultResponse()
    {
        return [
            'type' => 'default',
            'text' => 'عذراً، لم أفهم طلبك 🤔',
            'subtext' => 'يمكنني مساعدتك في:',
            'suggestions' => [
                'عرض الخدمات',
                'الأسعار',
                'ابدأ الحجز',
                'معلومات الاتصال',
                'الموقع'
            ]
        ];
    }

    /**
     * الحصول على رد من ChatGPT للأسئلة الجديدة
     */
    private function getChatGptResponse($message)
    {
        try {
            \Log::info('ChatGPT: Starting to get response for message: ' . $message);
            
            $chatGptService = new ChatGptService();
            
            // التحقق من نوع الرسالة وتوجيهها للدالة المناسبة
            $message = mb_strtolower($message, 'UTF-8');
            
            // إذا كانت شكوى، استخدم generateComplaintResponse
            if ($this->isComplaint($message)) {
                \Log::info('ChatGPT: Message identified as complaint, using complaint response');
                $response = $chatGptService->generateComplaintResponse($message);
            }
            // إذا كانت سؤال صحي أو طبي، استخدم generateHealthResponse
            elseif ($this->isHealthQuestion($message)) {
                \Log::info('ChatGPT: Message identified as health question, using health response');
                $response = $this->generateHealthResponse($message);
            }
            // إذا كانت سؤال عن السلوك، استخدم generateBehaviorResponse
            elseif ($this->isBehaviorQuestion($message)) {
                \Log::info('ChatGPT: Message identified as behavior question, using behavior response');
                $response = $this->generateBehaviorResponse($message);
            }
            // إذا كانت سؤال عن التغذية، استخدم generateNutritionResponse
            elseif ($this->isNutritionQuestion($message)) {
                \Log::info('ChatGPT: Message identified as nutrition question, using nutrition response');
                $response = $this->generateNutritionResponse($message);
            }
            // إذا كانت سؤال غريب، استخدم generateSmartResponseForUnexpectedQuestions
            elseif ($this->isUnexpectedQuestion($message)) {
                \Log::info('ChatGPT: Message identified as unexpected question, using smart response');
                $response = $chatGptService->generateSmartResponseForUnexpectedQuestions($message);
            }
            // إذا كانت سؤال عام أو نصيحة، استخدم generateResponse مباشرة
            elseif ($this->isGeneralQuestion($message)) {
                \Log::info('ChatGPT: Message identified as general question, using standard response');
                $response = $chatGptService->generateResponse($message);
            }
            // وإلا استخدم generateResponse العادية
            else {
                \Log::info('ChatGPT: Using standard response for unknown question type');
                $response = $chatGptService->generateResponse($message);
            }
            
            \Log::info('ChatGPT: Response received: ' . ($response ? 'YES' : 'NO'));
            \Log::info('ChatGPT: Response content: ' . $response);
            
            if ($response && !empty(trim($response))) {
                \Log::info('ChatGPT: Returning chatgpt response');
                return [
                    'type' => 'chatgpt_response',
                    'text' => 'إجابة ذكية 🤖',
                    'subtext' => $response,
                    'note' => '💡 هذه الإجابة مقدمة من ChatGPT',
                    'suggestions' => [
                        'عرض الخدمات',
                        'الأسعار',
                        'الحجز',
                        'ساعات العمل'
                    ]
                ];
            } else {
                \Log::warning('ChatGPT: Response is empty or null, using fallback');
                return [
                    'type' => 'chatgpt_response',
                    'text' => 'إجابة ذكية 🤖',
                    'subtext' => 'أهلاً وسهلاً! 🥰 أنا البوت المساعد لكات شوب، متخصصون في رعاية الحيوانات الأليفة في الرياض! 🐱🐕 لدينا محل فعلي وخدمات منزلية ومتجر إلكتروني. كيف يمكنني مساعدتك اليوم؟ 💖',
                    'note' => '💡 هذه الإجابة مقدمة من ChatGPT',
                    'suggestions' => [
                        'عرض الخدمات',
                        'الأسعار',
                        'الحجز',
                        'ساعات العمل'
                    ]
                ];
            }
        } catch (\Exception $e) {
            \Log::error('ChatGPT Error: ' . $e->getMessage());
            \Log::error('ChatGPT Error Trace: ' . $e->getTraceAsString());
        }
        
        \Log::info('ChatGPT: Falling back to default response');
        // في حالة حدوث خطأ أو عدم وجود رد، استخدم الرد الافتراضي
        return $this->getDefaultResponse();
    }
    
    /**
     * رد على الأسئلة الصحية والطبية
     */
    private function generateHealthResponse($message)
    {
        $message = mb_strtolower($message, 'UTF-8');
        
        // أسئلة عن الموت
        if (mb_strpos($message, 'ماتت') !== false || mb_strpos($message, 'توفت') !== false) {
            return "أوه لا! 😢💔 أسف جداً لسماع هذا الخبر المؤلم! 💔 قطتك كانت جزء من عائلتك ونحن نشعر معك! 🥺

نصيحة مهمة:
1️⃣ **تذكر الأوقات الجميلة** التي قضيتها معها
2️⃣ **لا تلوم نفسك** - الموت جزء من الحياة
3️⃣ **خذ وقتك** للحزن والتعافي
4️⃣ **تحدث مع أصدقائك** عن مشاعرك

إذا كنت تريد حيوان أليف جديد، نحن هنا لمساعدتك! 🐱💕

نحن نعتذر ونشعر معك! 🙏";
        }
        
        // أسئلة عن المرض
        if (mb_strpos($message, 'مريضة') !== false || mb_strpos($message, 'لست بخير') !== false) {
            return "أوه لا! 😔 أسف جداً أن قطتك مريضة! 💔 هذا مهم جداً لنا ونحن نهتم بصحتها! 🥺

نصيحة مهمة:
1️⃣ **راجع الطبيب البيطري فوراً** للتشخيص الصحيح
2️⃣ **راقب الأعراض** بدقة
3️⃣ **أعطِ الدواء** حسب التعليمات
4️⃣ **وفر الراحة** والهدوء لها

يمكنك زيارة متجرنا **catshoop1.com** للمنتجات البيطرية المساعدة! 🏥

نتمنى لقطتك الشفاء العاجل! 🐱💕🙏";
        }
        
        // أسئلة عن أكل الفضلات
        if (mb_strpos($message, 'تاكل خرا') !== false || mb_strpos($message, 'تاكل براز') !== false) {
            return "هذا سلوك طبيعي في بعض الأحيان! 😅 القطط تأكل الفضلات أحياناً لعدة أسباب:

🔍 **الأسباب المحتملة:**
- نقص في التغذية
- مشاكل في الهضم
- سلوك طبيعي في القطط الصغيرة
- مشاكل سلوكية

💡 **الحلول:**
1️⃣ **حسن التغذية** - طعام عالي الجودة
2️⃣ **نظف الفضلات فوراً** 
3️⃣ **استشر الطبيب البيطري** إذا استمر
4️⃣ **استخدم منتجات من متجرنا** catshoop1.com

هل تريد مساعدة في اختيار طعام أفضل؟ 🐱💕";
        }
        
        // رد عام للأسئلة الصحية
        return "أهلاً وسهلاً! 🥰 أنا البوت المساعد لكات شوب، متخصصون في رعاية الحيوانات الأليفة! 🐱🐕

أرى أن لديك سؤال صحي مهم! 💊

نصيحة مهمة: **راجع الطبيب البيطري أولاً** للتشخيص الصحيح! 🏥

يمكنك أيضاً زيارة متجرنا **catshoop1.com** للمنتجات البيطرية المساعدة! 💡

كيف يمكنني مساعدتك أكثر؟ 💕";
    }
    
    /**
     * رد على الأسئلة السلوكية
     */
    private function generateBehaviorResponse($message)
    {
        return "أهلاً وسهلاً! 🥰 أنا البوت المساعد لكات شوب، متخصصون في رعاية الحيوانات الأليفة! 🐱🐕

أرى أن لديك سؤال عن سلوك حيوانك الأليف! 🧠

المشاكل السلوكية شائعة ويمكن حلها! 💡

نصيحة مهمة: **استشر مدرب حيوانات أليفة أو طبيب بيطري** للحصول على نصائح متخصصة! 🎯

يمكنك أيضاً زيارة متجرنا **catshoop1.com** للمنتجات المساعدة في حل المشاكل السلوكية! 🛍️

هل تريد مساعدة في اختيار منتجات مناسبة؟ 💕";
    }
    
    /**
     * رد على الأسئلة الغذائية
     */
    private function generateNutritionResponse($message)
    {
        return "أهلاً وسهلاً! 🥰 أنا البوت المساعد لكات شوب، متخصصون في رعاية الحيوانات الأليفة! 🐱🐕

أرى أن لديك سؤال عن تغذية حيوانك الأليف! 🍽️

التغذية السليمة مهمة جداً لصحة حيوانك الأليف! 💪

نصيحة مهمة: **استشر الطبيب البيطري** للحصول على نصائح غذائية متخصصة! 🏥

يمكنك زيارة متجرنا **catshoop1.com** لجميع أنواع الطعام والمكملات الغذائية! 🛍️

هل تريد مساعدة في اختيار طعام مناسب؟ 💕";
    }
    
    /**
     * تحديد ما إذا كانت الرسالة شكوى
     */
    private function isComplaint($message)
    {
        return $this->containsKeywords($message, ChatBotConfig::COMPLAINT_KEYWORDS);
    }
    
    /**
     * تحديد ما إذا كانت الرسالة سؤال غريب
     */
    private function isUnexpectedQuestion($message)
    {
        return $this->containsKeywords($message, ChatBotConfig::UNEXPECTED_KEYWORDS);
    }
    
    /**
     * تحديد ما إذا كانت الرسالة سؤال صحي أو طبي
     */
    private function isHealthQuestion($message)
    {
        return $this->containsKeywords($message, ChatBotConfig::HEALTH_KEYWORDS);
    }
    
    /**
     * تحديد ما إذا كانت الرسالة سؤال عن السلوك
     */
    private function isBehaviorQuestion($message)
    {
        return $this->containsKeywords($message, ChatBotConfig::BEHAVIOR_KEYWORDS);
    }
    
    /**
     * تحديد ما إذا كانت الرسالة سؤال عن التغذية
     */
    private function isNutritionQuestion($message)
    {
        return $this->containsKeywords($message, ChatBotConfig::NUTRITION_KEYWORDS);
    }

    /**
     * تحديد أيقونة المنتج حسب اسمه
     */
    private function getProductIcon($productName)
    {
        $name = mb_strtolower($productName, 'UTF-8');
        
        if (mb_strpos($name, 'قطط') !== false || mb_strpos($name, 'قط') !== false) {
            return '🐱';
        } elseif (mb_strpos($name, 'كلاب') !== false || mb_strpos($name, 'كلب') !== false) {
            return '🐕';
        } else {
            return '🛁';
        }
    }
    
    /**
     * تحديد فئة المنتج حسب اسمه
     */
    private function getProductCategory($productName)
    {
        $name = mb_strtolower($productName, 'UTF-8');
        
        if (mb_strpos($name, 'قطط') !== false || mb_strpos($name, 'قط') !== false) {
            return 'القطط';
        } elseif (mb_strpos($name, 'كلاب كبيرة') !== false || mb_strpos($name, 'كلب كبير') !== false) {
            return 'الكلاب الكبيرة';
        } elseif (mb_strpos($name, 'كلاب صغيرة') !== false || mb_strpos($name, 'كلب صغير') !== false) {
            return 'الكلاب الصغيرة';
        } elseif (mb_strpos($name, 'كلاب') !== false || mb_strpos($name, 'كلب') !== false) {
            return 'الكلاب';
        } else {
            return 'خدمات أخرى';
        }
    }

    /**
     * تحديد ما إذا كانت الرسالة سؤال عام يحتاج لـ ChatGPT
     */
    private function isGeneralQuestion($message)
    {
        return $this->containsKeywords($message, ChatBotConfig::GENERAL_QUESTION_KEYWORDS);
    }

    /**
     * تحديد ما إذا كانت الرسالة سؤال عن المنتجات (وليس سؤال عام)
     */
    private function isProductQuestion($message)
    {
        // إذا كان السؤال يحتوي على كلمات منتجات، فهو سؤال منتجات
        foreach (ChatBotConfig::PRODUCT_KEYWORDS['products'] as $keyword) {
            if (mb_strpos($message, $keyword) !== false) {
                return true;
            }
        }
        
        // إذا كان السؤال يحتوي على كلمات حيوانات + كلمات شراء/منتجات، فهو سؤال منتجات
        $hasAnimalKeyword = false;
        $hasProductIntent = false;
        
        foreach (ChatBotConfig::PRODUCT_KEYWORDS['animals'] as $keyword) {
            if (mb_strpos($message, $keyword) !== false) {
                $hasAnimalKeyword = true;
                break;
            }
        }
        
        if ($hasAnimalKeyword) {
            foreach (ChatBotConfig::PRODUCT_KEYWORDS['intent'] as $keyword) {
                if (mb_strpos($message, $keyword) !== false) {
                    $hasProductIntent = true;
                    break;
                }
            }
        }
        
        return $hasAnimalKeyword && $hasProductIntent;
    }
}
