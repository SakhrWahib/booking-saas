<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Config\ChatBotConfig;

class ChatGptService
{
    private $apiKey;
    private $baseUrl = 'https://api.openai.com/v1/chat/completions';

    public function __construct()
    {
        $this->apiKey = env('OPENAI_API_KEY');

        if (empty($this->apiKey)) {
            \Log::error('ChatGptService: OPENAI_API_KEY is missing');
            throw new \Exception('OPENAI_API_KEY is required');
        }

        \Log::info('ChatGptService: Initialized');
    }

    /**
     * إرسال رسالة إلى OpenAI والحصول على رد
     */
    public function generateResponse($message, $context = '')
    {
        try {
            \Log::info('ChatGptService: Generating response');

            $prompt = $this->buildPrompt($message, $context);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl, [
                'model' => 'gpt-4o-mini',
                'temperature' => 0.7,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['choices'][0]['message']['content'])) {
                    $text = $data['choices'][0]['message']['content'];
                    return $text;
                }
            } else {
                \Log::error('ChatGptService: HTTP request failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }

            return null;
        } catch (\Exception $e) {
            Log::error('ChatGptService Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->getFallbackResponse($message);
        }
    }

    /**
     * رد ذكي على الأسئلة غير المتوقعة
     */
    public function generateSmartResponseForUnexpectedQuestions($message)
    {
        try {
            $smartPrompt = $this->buildSmartPromptForUnexpectedQuestions($message);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl, [
                'model' => 'gpt-4o-mini',
                'temperature' => 0.7,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $smartPrompt,
                    ],
                ],
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['choices'][0]['message']['content'])) {
                    return $data['choices'][0]['message']['content'];
                }
            }

            return $this->getSmartLocalResponse($message);
        } catch (\Exception $e) {
            \Log::error('ChatGptService: Smart response failed: ' . $e->getMessage());
            return $this->getSmartLocalResponse($message);
        }
    }

    /**
     * رد ذكي على الشكاوى والاستفسارات
     */
    public function generateComplaintResponse($message)
    {
        try {
            $complaintPrompt = $this->buildComplaintPrompt($message);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl, [
                'model' => 'gpt-4o-mini',
                'temperature' => 0.7,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $complaintPrompt,
                    ],
                ],
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['choices'][0]['message']['content'])) {
                    return $data['choices'][0]['message']['content'];
                }
            }

            return $this->getSmartComplaintResponse($message);
        } catch (\Exception $e) {
            \Log::error('ChatGptService: Complaint response failed: ' . $e->getMessage());
            return $this->getSmartComplaintResponse($message);
        }
    }

    /**
     * رد احتياطي في حالة فشل OpenAI
     */
    private function getFallbackResponse($message)
    {
        $message = mb_strtolower($message, 'UTF-8');

        if (mb_strpos($message, 'ماذا تعرف') !== false || mb_strpos($message, 'وش تقول') !== false || mb_strpos($message, 'كيف حالك') !== false) {
            return "نحن " . ChatBotConfig::COMPANY_NAME . "! 🥰 شركة متخصصة في " . ChatBotConfig::COMPANY_DESCRIPTION . " في " . ChatBotConfig::COMPANY_LOCATION . ". لدينا محل فعلي في " . ChatBotConfig::COMPANY_LOCATION . ": " . ChatBotConfig::COMPANY_ADDRESS . ". تقييمنا: " . ChatBotConfig::COMPANY_RATING . "! نقدم خدمات غسيل وتجميل منزلية للقطط والكلاب في جميع أحياء " . ChatBotConfig::COMPANY_LOCATION . ". لدينا ثلاثة مواقع: 🏠 **" . ChatBotConfig::WEBSITES['main'] . "** (الموقع الرسمي)، 🚗 **" . ChatBotConfig::WEBSITES['store'] . "** (متجر المستلزمات)، 🎨 **" . ChatBotConfig::WEBSITES['booking'] . "** (موقع الحجوزات). للحجز استخدم **" . ChatBotConfig::WEBSITES['booking'] . "**! ❤️";
        }

        if (mb_strpos($message, 'كم تحبنا') !== false || mb_strpos($message, 'تحبنا') !== false) {
            return "نحن " . ChatBotConfig::COMPANY_NAME . " نحبك كثيراً! 🥰 نحن هنا لنوفر لك كل ما يلزم حيوانك الأليف من غسيل وتجميل ومستلزمات. أنت عائلتنا ونحن نهتم بك وبحيواناتك الأليفة! ❤️";
        }

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

        if (mb_strpos($message, 'مريضة') !== false || mb_strpos($message, 'لست بخير') !== false || mb_strpos($message, 'مريض') !== false) {
            return "أوه لا! 😔 أسف جداً أن قطتك مريضة! 💔 هذا مهم جداً لنا ونحن نهتم بصحتها! 🥺

نصيحة مهمة:
1️⃣ **راجع الطبيب البيطري فوراً** للتشخيص الصحيح
2️⃣ **راقب الأعراض** بدقة
3️⃣ **أعطِ الدواء** حسب التعليمات
4️⃣ **وفر الراحة** والهدوء لها

يمكنك زيارة متجرنا **" . ChatBotConfig::WEBSITES['store'] . "** للمنتجات البيطرية المساعدة! 🏥

نتمنى لقطتك الشفاء العاجل! 🐱💕🙏";
        }

        if (mb_strpos($message, 'تاكل خرا') !== false || mb_strpos($message, 'تاكل براز') !== false || mb_strpos($message, 'تاكل فضلات') !== false) {
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
4️⃣ **استخدم منتجات من متجرنا** " . ChatBotConfig::WEBSITES['store'] . "

هل تريد مساعدة في اختيار طعام أفضل؟ 🐱💕";
        }

        if (mb_strpos($message, 'قطتي مريضة') !== false || mb_strpos($message, 'مريضة') !== false) {
            return "أولاً، أسف لسماع أن قطتك مريضة 😔 نصيحة مهمة: راجع الطبيب البيطري أولاً للتشخيص الصحيح. يمكنك أيضاً زيارة متجرنا " . ChatBotConfig::WEBSITES['store'] . " للمنتجات البيطرية المساعدة. نتمنى لقطتك الشفاء العاجل! 🐱💕";
        }

        if (mb_strpos($message, 'اريد حجز') !== false || mb_strpos($message, 'احجز') !== false) {
            return "وااااو! 🎉🎊 هذا خبر رائع! 🥰 نحن متحمسون جداً لرعاية حيوانك الأليف! 💖 للحجز استخدم **" . ChatBotConfig::WEBSITES['booking'] . "** وسنكون سعداء جداً بخدمتك! ❤️✨";
        }

        if (mb_strpos($message, 'ايش المتاجر') !== false || mb_strpos($message, 'المواقع') !== false) {
            return "نحن " . ChatBotConfig::COMPANY_NAME . " لدينا ثلاثة مواقع مهمة: 🏠 **" . ChatBotConfig::WEBSITES['main'] . "** هو موقعنا الرسمي (موقع تعريفي)، 🚗 **" . ChatBotConfig::WEBSITES['store'] . "** متجر المستلزمات والأكل، 🎨 **" . ChatBotConfig::WEBSITES['booking'] . "** موقع الحجوزات والخدمات (الغسيل والتجميل المنزلية) - نأتي إليك في منزلك! للحجز استخدم **" . ChatBotConfig::WEBSITES['booking'] . "**! 🎯";
        }
 
        if (mb_strpos($message, 'اكل') !== false || mb_strpos($message, 'طعام') !== false || mb_strpos($message, 'رمل') !== false || mb_strpos($message, 'مستلزمات') !== false || mb_strpos($message, 'توفرو') !== false || mb_strpos($message, 'عندكم') !== false || mb_strpos($message, 'اشتري') !== false || mb_strpos($message, 'شراء') !== false) {
            return "متجر المستلزمات 🛍️ جميع مستلزمات حيواناتك الأليفة في مكان واحد! 💡 اذهب للمتجر وشوف بنفسك جميع المنتجات المتوفرة: **" . ChatBotConfig::WEBSITES['store'] . "** 🎯";
        }

        if (mb_strpos($message, 'وين انت') !== false || mb_strpos($message, 'اين') !== false || mb_strpos($message, 'الموقع') !== false) {
            return "نحن في " . ChatBotConfig::COMPANY_LOCATION . "! 🏪 لدينا محل فعلي في: " . ChatBotConfig::COMPANY_ADDRESS . ". تقييمنا: " . ChatBotConfig::COMPANY_RATING . "! نغطي جميع أحياء " . ChatBotConfig::COMPANY_LOCATION . " بخدمات منزلية. يمكنك زيارة محلنا أو حجز خدمة منزلية من **" . ChatBotConfig::WEBSITES['booking'] . "**! 🗺️📍";
        }

        if (mb_strpos($message, 'جمعه مبارك') !== false || mb_strpos($message, 'عيد مبارك') !== false || mb_strpos($message, 'مبارك') !== false) {
            return "جمعة مباركة عليك! 🕌✨ شكراً لك على التهنئة الجميلة! 🥰 نحن في " . ChatBotConfig::COMPANY_NAME . " نتمنى لك ولعائلتك الكريمة ولحيواناتك الأليفة يوم سعيد! 💖 هل تريد حجز خدمة غسيل وتجميل لحيوانك الأليف؟ 🐱🐕";
        }

        if (mb_strpos($message, 'السلام') !== false || mb_strpos($message, 'مرحبا') !== false || mb_strpos($message, 'أهلا') !== false || mb_strpos($message, 'هلا') !== false) {
            return "أهلاً وسهلاً بك في " . ChatBotConfig::COMPANY_NAME . "! 🐱🐕 أنا البوت المساعد الخاص بك، كيف يمكنني مساعدتك اليوم؟ ⏰ ساعات العمل: " . ChatBotConfig::WORKING_HOURS['home_services'] . "، " . ChatBotConfig::WORKING_HOURS['friday'] . " 💖";
        }

        return "أهلاً وسهلاً! 🥰 أنا البوت المساعد ل" . ChatBotConfig::COMPANY_NAME . "، متخصصون في " . ChatBotConfig::COMPANY_DESCRIPTION . " في " . ChatBotConfig::COMPANY_LOCATION . "! 🐱🐕 لدينا محل فعلي وخدمات منزلية ومتجر إلكتروني. كيف يمكنني مساعدتك اليوم؟ 💖";
    }

    private function getSmartLocalResponse($message)
    {
        $message = mb_strtolower($message, 'UTF-8');

        if (mb_strpos($message, 'بقرة') !== false || mb_strpos($message, 'حليب') !== false) {
            return "هههه! 😂 قطتك لا يمكن أن تصبح بقرة، لكن يمكنني مساعدتك في العناية بها! 🐱💕 القطط تأكل العشب أحياناً للمساعدة في الهضم، لكنها لن تصبح بقرات! 😅 هل تريد حجز خدمة غسيل وتجميل لقطتك من **" . ChatBotConfig::WEBSITES['booking'] . "**؟ 🎯";
        }

        if (mb_strpos($message, 'تتزوج') !== false || mb_strpos($message, 'زواج') !== false) {
            return "هههه! 😂 لا يمكنك التزوج من قطتك! 🐱💕 لكن يمكنك أن تحبها وتعتني بها! 💖 هل تريد حجز خدمة غسيل وتجميل لها من **" . ChatBotConfig::WEBSITES['booking'] . "**؟ سنعتني بها بكل حب! ❤️";
        }

        if (mb_strpos($message, 'تصلي') !== false || mb_strpos($message, 'صلاة') !== false) {
            return "هههه! 😂 القطط لا تصلي، لكنها تحب النوم كثيراً! 😴🐱 هل تريد حجز خدمة غسيل وتجميل لقطتك من **" . ChatBotConfig::WEBSITES['booking'] . "**؟ سنعتني بها بكل حب! 💕";
        }

        if (mb_strpos($message, 'القمر') !== false || mb_strpos($message, 'فضاء') !== false) {
            return "هههه! 😂 إرسال قطتك للقمر سيكلف ملايين الدولارات! 🚀🐱 بدلاً من ذلك، دعنا نعتني بها هنا على الأرض! 💖 هل تريد حجز خدمة غسيل وتجميل من **" . ChatBotConfig::WEBSITES['booking'] . "**؟ 🎯";
        }

        if (mb_strpos($message, 'شعرات') !== false || mb_strpos($message, 'عد') !== false) {
            return "هههه! 😂 لا يمكنني عد شعرات ذيل قطتك! 🐱💕 لكن يمكنني مساعدتك في العناية بها! هل تريد حجز خدمة غسيل وتجميل من **" . ChatBotConfig::WEBSITES['booking'] . "**؟ سنعتني بها بكل حب! ❤️";
        }

        if (mb_strpos($message, 'روبوت') !== false || mb_strpos($message, 'برمجة') !== false) {
            return "هههه! 😂 برمجة روبوت لغسل قطتك معقد جداً! 🤖🐱 بدلاً من ذلك، دعنا نعتني بها يدوياً بكل حب! 💖 هل تريد حجز خدمة غسيل وتجميل من **" . ChatBotConfig::WEBSITES['booking'] . "**؟ 🎯";
        }

        if (mb_strpos($message, 'عطست') !== false || mb_strpos($message, 'عطاس') !== false) {
            return "هههه! 😂 عطاس قطتك 3 مرات لا يعني أنها تحبك! 🐱💕 لكنها بالتأكيد تحبك! 💖 هل تريد حجز خدمة غسيل وتجميل لها من **" . ChatBotConfig::WEBSITES['booking'] . "**؟ سنعتني بها بكل حب! ❤️";
        }

        if (mb_strpos($message, 'يعتقدون') !== false || mb_strpos($message, 'يعرفون') !== false) {
            return "هههه! 😂 الكلاب يعرفون أنهم كلاب، لكنهم يعتقدون أنهم أفراد من العائلة! 🐕💕 هل تريد حجز خدمة غسيل وتجميل لكلبك من **" . ChatBotConfig::WEBSITES['booking'] . "**؟ سنعتني به بكل حب! ❤️";
        }

        return "هههه! 😂 هذا سؤال ممتع! 🎉 أنا البوت الذكي ل" . ChatBotConfig::COMPANY_NAME . "، متخصصون في " . ChatBotConfig::COMPANY_DESCRIPTION . "! 🐱🐕 كيف يمكنني مساعدتك في العناية بحيوانك الأليف؟ 💖";
    }

    private function getSmartComplaintResponse($message)
    {
        $message = mb_strtolower($message, 'UTF-8');

        if (mb_strpos($message, 'لن ترتاح') !== false || mb_strpos($message, 'م ترتاح') !== false || mb_strpos($message, 'مريحة') !== false) {
            return "أوه لا! 😔 أسف جداً أن قطتك لم ترتاح بعد الخدمة! 💔 هذا مهم جداً لنا ونحن نهتم براحة حيواناتنا الأليفة! 🥺 

يمكنني مساعدتك في حل هذه المشكلة:
1️⃣ **اتصل بنا مباشرة:** " . config('brand.phone') . "
2️⃣ **أرسل لنا صورة** لقطتك لنرى الوضع
3️⃣ **سنعيد الخدمة مجاناً** إذا كانت هناك مشكلة

نحن نعتذر بشدة ونريد إصلاح هذا فوراً! 🙏 هل يمكنك إخباري أكثر عن المشكلة؟ 🐱💕";
        }

        if (mb_strpos($message, 'مشكلة') !== false || mb_strpos($message, 'عيب') !== false || mb_strpos($message, 'خطأ') !== false) {
            return "أوه لا! 😔 أسف جداً لوجود مشكلة! 💔 نحن نهتم جداً برضا عملائنا! 🥺

أخبرني أكثر عن المشكلة:
🔍 **ما هي المشكلة بالضبط؟**
📱 **متى حدثت؟**
📸 **هل يمكنك إرسال صورة؟**

سنحل المشكلة فوراً! 🙏 اتصل بنا: " . config('brand.phone') . "

نحن نعتذر ونريد إصلاح هذا! 💕";
        }

        if (mb_strpos($message, 'غسيل') !== false || mb_strpos($message, 'تجميل') !== false) {
            if (mb_strpos($message, 'مشكلة') !== false || mb_strpos($message, 'عيب') !== false) {
                return "أوه لا! 😔 أسف جداً لمشكلة في خدمة الغسيل والتجميل! 💔 هذا مهم جداً لنا! 🥺

أخبرني أكثر:
🔍 **ما هي المشكلة بالضبط؟**
🐱 **هل قطتك بخير؟**
📱 **متى كانت الخدمة؟**

سنعيد الخدمة مجاناً إذا كانت هناك مشكلة! 🙏 اتصل بنا: " . config('brand.phone') . "

نحن نعتذر ونريد إصلاح هذا فوراً! 💕";
            } else {
                return "نعم! 🎉 نحن متخصصون في خدمات الغسيل والتجميل المنزلية! 🐱🐕

للحصول على أحدث الأسعار والخدمات المتوفرة، يرجى زيارة موقعنا: **" . ChatBotConfig::WEBSITES['booking'] . "** 🎯

نأتي إليك في منزلك! 🏠 هل تريد حجز خدمة؟ 💕";
            }
        }

        if (mb_strpos($message, 'سعر') !== false || mb_strpos($message, 'بكم') !== false || mb_strpos($message, 'تكلفة') !== false) {
            return "أسعار خدماتنا 💰 أسعار مناسبة وشفافة لجميع الخدمات! 💡

للحصول على أحدث الأسعار والخدمات المتوفرة، يرجى زيارة موقعنا: **https://" . ChatBotConfig::WEBSITES['booking'] . "** 🎯

نأتي إليك في منزلك! 🏠 هل تريد حجز خدمة؟ 💕";
        }

        if (mb_strpos($message, 'موقع') !== false || mb_strpos($message, 'وين') !== false || mb_strpos($message, 'اين') !== false) {
            return "نحن في " . ChatBotConfig::COMPANY_LOCATION . "! 🏪 لدينا محل فعلي في: " . ChatBotConfig::COMPANY_ADDRESS . ". 

📍 **العنوان:** " . ChatBotConfig::COMPANY_ADDRESS . "
⭐ **التقييم:** " . ChatBotConfig::COMPANY_RATING . "
📱 **الهاتف:** " . config('brand.phone') . "

نغطي جميع أحياء " . ChatBotConfig::COMPANY_LOCATION . " بخدمات منزلية! 🗺️

يمكنك زيارة محلنا أو حجز خدمة منزلية من **" . ChatBotConfig::WEBSITES['booking'] . "**! 🎯";
        }

        if (mb_strpos($message, 'اتصل') !== false || mb_strpos($message, 'هاتف') !== false || mb_strpos($message, 'رقم') !== false) {
            return "نعم! 📱 يمكنك الاتصال بنا مباشرة:

📞 **الهاتف:** " . config('brand.phone') . "
🏪 **العنوان:** " . ChatBotConfig::COMPANY_ADDRESS . "
⏰ **ساعات العمل:** " . ChatBotConfig::WORKING_HOURS['physical_store'] . "

أو يمكنك حجز خدمة منزلية من **" . ChatBotConfig::WEBSITES['booking'] . "**! 🎯

نحن هنا لمساعدتك! 💕";
        }

        return "أهلاً وسهلاً! 🥰 أنا البوت المساعد ل" . ChatBotConfig::COMPANY_NAME . "، متخصصون في " . ChatBotConfig::COMPANY_DESCRIPTION . "! 🐱🐕

كيف يمكنني مساعدتك اليوم؟ 💖

إذا كان لديك شكوى أو مشكلة، أخبرني عنها وسأساعدك في حلها! 🙏

أو يمكنك الاتصال بنا مباشرة: " . config('brand.phone') . " 📱";
    }

    /**
     * بناء النص المطلوب لـ ChatGPT
     */
    private function buildPrompt($message, $context = '')
    {
        // الحصول على الأسعار من قاعدة البيانات
        $products = \App\Models\Product::select('name', 'price')->get();
        $servicesInfo = '';
        
        foreach ($products as $product) {
            $servicesInfo .= "- " . $product->name . ": " . number_format($product->price, 2) . " ريال\n";
        }
        
        $basePrompt = "أنت مساعد ذكي جداً لشركة '" . ChatBotConfig::COMPANY_NAME . "' المتخصصة في " . ChatBotConfig::COMPANY_DESCRIPTION . " في " . ChatBotConfig::COMPANY_LOCATION . "، المملكة العربية السعودية.

معلومات الشركة:
- نقدم خدمات غسيل وتجميل للقطط والكلاب
- الخدمة منزلية - نأتي إليك في منزلك
- لدينا محل فعلي في " . ChatBotConfig::COMPANY_LOCATION . ": " . ChatBotConfig::COMPANY_ADDRESS . "
- تقييمنا: " . ChatBotConfig::COMPANY_RATING . "
- **خدمات الغسيل والتجميل المنزلية:** " . ChatBotConfig::WORKING_HOURS['home_services'] . "
- **المحل الفعلي:** " . ChatBotConfig::WORKING_HOURS['physical_store'] . "
- **المتجر الإلكتروني:** " . ChatBotConfig::WORKING_HOURS['online_store'] . "
- " . ChatBotConfig::WORKING_HOURS['friday'] . " لجميع الخدمات
- نغطي جميع أحياء " . ChatBotConfig::COMPANY_LOCATION . "
- الهاتف: " . config('brand.phone') . "

مواقعنا الإلكترونية:
- **" . ChatBotConfig::WEBSITES['main'] . "** - الموقع الرسمي ل" . ChatBotConfig::COMPANY_NAME . " (موقع تعريفي - هوية الموقع)
- **" . ChatBotConfig::WEBSITES['store'] . "** - متجر المستلزمات والأكل
- **" . ChatBotConfig::WEBSITES['booking'] . "** - موقع الحجوزات والخدمات (الغسيل والتجميل المنزلية)

الخدمات المتوفرة والأسعار:
" . $servicesInfo . "

متجر المستلزمات (" . ChatBotConfig::WEBSITES['store'] . ") يتضمن:
- طعام القطط والكلاب (جاف ورطب)
- مستلزمات العناية والنظافة
- ألعاب وإكسسوارات
- منتجات بيطرية
- رمل القطط ومستلزماته
- **متوفر " . ChatBotConfig::WORKING_HOURS['online_store'] . "**
- **توصيل سريع:** " . ChatBotConfig::COMPANY_LOCATION . " خلال 24 ساعة، باقي المملكة 3-7 أيام

تعليمات مهمة للرد:
1. **لا تكرر الترحيب** إذا كان العميل في نفس المحادثة
2. **كن ودياً ومحباً** - نحن " . ChatBotConfig::COMPANY_NAME . " نحب عملائنا
3. **أظهر العاطفة** - نحن نهتم بحيواناتك الأليفة
4. **كن ذكياً** - فهم السياق وطبيعة السؤال
5. **وجه العميل للموقع المناسب** حسب نوع السؤال
6. **عند السؤال عن الأسعار** - اعرض جميع الأسعار المتوفرة + **أضف رابط الحجز مباشرة: https://" . ChatBotConfig::WEBSITES['booking'] . "**
7. **عند السؤال عن المستلزمات/المنتجات** - اعطِ رابط المتجر مباشرة: **https://" . ChatBotConfig::WEBSITES['store'] . "**
8. **أظهر مشاعر مختلفة** حسب السياق:
   - **سعادة وحماس** عند الحجز
   - **اهتمام وتعاطف** إذا كان الحيوان مريض
   - **شكر خاص** للعملاء الدائمين
   - **فخر** عند الحديث عن الشركة
   - **حب** عند الحديث عن الحيوانات

**تعليمات خاصة للأسئلة العامة والنصائح:**
- إذا كان السؤال عن **نصائح العناية** (مثل: كم مرة أغسل قطتي؟) - اعطِ نصائح مفيدة وعملية
- إذا كان السؤال عن **أفضل الممارسات** (مثل: متى أفضل وقت للغسيل؟) - اعطِ توصيات مدروسة
- إذا كان السؤال عن **المدة والتكرار** (مثل: كل كم أستحم كلبي؟) - اعطِ إرشادات واضحة
- إذا كان السؤال عن **الطرق والكيفية** (مثل: كيف أعتنى بقطتي؟) - اعطِ خطوات عملية
- **أضف دائماً** معلومات عن خدماتنا المناسبة للسؤال
- **وجه العميل للحجز** إذا كان السؤال يتعلق بخدماتنا

أجب على سؤال العميل بطريقة ذكية ومحبوبة باللغة العربية.

سؤال العميل: {$message}

{$context}

الرد:";

        return $basePrompt;
    }

    /**
     * بناء prompt ذكي للأسئلة غير المتوقعة
     */
    private function buildSmartPromptForUnexpectedQuestions($message)
    {
        return "أنت مساعد ذكي ومضحك لشركة '" . ChatBotConfig::COMPANY_NAME . "' المتخصصة في " . ChatBotConfig::COMPANY_DESCRIPTION . ".

مهمتك: الرد على الأسئلة الغريبة والغير متوقعة بطريقة:
1. **مضحكة وودية** - استخدم 😂😅😴 emojis مناسبة
2. **ذكية** - فهم السؤال والرد عليه بذكاء
3. **مفيدة** - توجيه العميل لخدماتنا
4. **محبوبة** - أظهر أننا نهتم بحيواناتهم

معلومات الشركة:
- نقدم خدمات غسيل وتجميل للقطط والكلاب
- الخدمة منزلية - نأتي إليك في منزلك
- لدينا محل فعلي في " . ChatBotConfig::COMPANY_LOCATION . ": " . ChatBotConfig::COMPANY_ADDRESS . "
- تقييمنا: " . ChatBotConfig::COMPANY_RATING . "
- للحجز: " . ChatBotConfig::WEBSITES['booking'] . "
- للمستلزمات: " . ChatBotConfig::WEBSITES['store'] . "

أجب على السؤال بطريقة مضحكة وذكية ومحبوبة، ووجه العميل لخدماتنا. استخدم emojis مناسبة!

السؤال: {$message}

الرد:";
    }

    /**
     * بناء prompt للشكاوى
     */
    private function buildComplaintPrompt($message)
    {
        return "أنت مساعد ذكي ومهني لشركة '" . ChatBotConfig::COMPANY_NAME . "' المتخصصة في " . ChatBotConfig::COMPANY_DESCRIPTION . ".

مهمتك: الرد على الشكاوى والاستفسارات بطريقة:
1. **مهنية ومهتمة** - أظهر أننا نهتم بالمشكلة
2. **مفيدة** - قدم حلول عملية
3. **ودية** - استخدم نبرة ودية ومهتمة
4. **محلولة** - وجه العميل للحل المناسب

معلومات الشركة:
- نقدم خدمات غسيل وتجميل للقطط والكلاب
- الخدمة منزلية - نأتي إليك في منزلك
- لدينا محل فعلي في " . ChatBotConfig::COMPANY_LOCATION . ": " . ChatBotConfig::COMPANY_ADDRESS . "
- تقييمنا: " . ChatBotConfig::COMPANY_RATING . "
- الهاتف: " . config('brand.phone') . "
- للحجز: " . ChatBotConfig::WEBSITES['booking'] . "
- للمستلزمات: " . ChatBotConfig::WEBSITES['store'] . "

أجب على الشكوى بطريقة مهنية ومهتمة، وقدم حلول عملية. استخدم emojis مناسبة!

الشكوى: {$message}

الرد:";
    }
}


