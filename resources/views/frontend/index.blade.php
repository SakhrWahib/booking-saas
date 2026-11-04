@extends('layouts.main-layout')

@section('head')
    <title>{{ config('brand.name') }} الرياض | رقم 1 في غسيل وتجميل القطط والكلاب | خدمة منزلية فورية | احجز الآن</title>
    <meta name="description" content="{{ config('brand.name') }} الرياض - رقم 1 في غسيل وتجميل القطط والكلاب. خدمة منزلية فورية ومضمونة: غسيل قطط الرياض، غسيل كلاب الرياض، قص أظافر، تنظيف أذنين، حلاقة شعر. احجز موعدك الآن {{ config('brand.phone') }} - الأفضل في الرياض">
    <meta name="keywords" content="{{ config('brand.name') }}, غسيل قطط, غسيل كلاب, تجميل قطط, تجميل كلاب, خدمة منزلية, الرياض, العناية بالحيوانات, قص أظافر, تنظيف أذنين, حلاقة شعر, حجز مواعيد, Pet Grooming Riyadh, {{ config('brand.name_en') }}, Dog Grooming, غسيل حيوانات أليفة, صالون حيوانات, خدمات قطط, خدمات كلاب, {{ config('brand.name') }} الرياض, غسيل قطط الرياض, غسيل كلاب الرياض, تجميل قطط الرياض, تجميل كلاب الرياض, خدمة غسيل قطط, خدمة غسيل كلاب, صالون قطط, صالون كلاب, غسيل حيوانات, قص شعر قطط, قص شعر كلاب, تنظيف قطط, تنظيف كلاب, فحص قطط, فحص كلاب, رعاية حيوانات, حيوانات أليفة, قطط, كلاب, الرياض, السعودية, خدمة منزلية, مواعيد, حجز, احجز, الآن, فوري, سريع, مضمون, احترافي, متخصص, أفضل, رقم واحد, الأول, الأول في الرياض, الأفضل في الرياض">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="author" content="{{ config('brand.name') }} - {{ config('brand.name_en') }} Riyadh">
    <meta name="language" ء="ar">
    <meta name="revisit-after" content="1 days">
    <meta name="distribution" content="global">
    <meta name="rating" content="general">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="google-site-verification" content="sRYBbzoimux9mpSlkyHMPIgWBRy1wwxtGPPZcQlqNnI" />
    
    <!-- Enhا anced Geo-targeting for Riyadh -->

    <meta name="geo.region" content="SA-RI">
    <meta name="geo.placename" content="الرياض, المملكة العربية السعودية">
    <meta name="geo.position" content="24.7136;46.6753">
    <meta name="ICBM" content="24.7136, 46.6753">
    <meta name="geo.country" content="SA">
    <meta name="geo.city" content="الرياض">
    
    <!-- Enhanced Local Business SEO -->
    <meta name="business:contact_data:street_address" content="الرياض">
    <meta name="business:contact_data:locality" content="الرياض">
    <meta name="business:contact_data:region" content="الرياض">
    <meta name="business:contact_data:postal_code" content="12345">
    <meta name="business:contact_data:country_name" content="المملكة العربية السعودية">
    <meta name="business:contact_data:phone_number" content="{{ config('brand.whatsapp') }}">
    
    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="//www.google-analytics.com">
    <link rel="dns-prefetch" href="//www.googletagmanager.com">
    
    <!-- تحسينات الأداء - تحميل سريع -->
    <link rel="preload" href="{{ asset('css/frontend-styles.css') }}" as="style">
    <link rel="preload" href="/banar.php" as="image" type="image/png">
    
    <!-- تحسين تحميل الصور -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // تحميل الصور عند الحاجة - محسن للسرعة
        const lazyImages = document.querySelectorAll('.lazy-image');
        if (lazyImages.length > 0) {
            const imageObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.classList.remove('lazy-image');
                            imageObserver.unobserve(img);
                        }
                    }
                });
            }, { rootMargin: '50px' });
            
            lazyImages.forEach(img => imageObserver.observe(img));
        }
        
        // تحميل صورة البنر مسبقاً للأداء الفائق
        const bannerImg = new Image();
        bannerImg.onload = function() {
            console.log('Banner image loaded successfully');
        };
        bannerImg.src = '/banar.php';
        
        // تحسين الأداء للجوال
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js').catch(function(err) {
                console.log('ServiceWorker registration failed: ', err);
            });
        }
    });
    </script>
    
    <!-- Enhanced Open Graph -->
    <meta property="og:title" content="{{ config('brand.name') }} الرياض | أفضل خدمات غسيل وتجميل القطط والكلاب">
    <meta property="og:description" content="{{ config('brand.name') }} الرياض - أفضل صالون غسيل وتجميل القطط والكلاب. خدمات منزلية: غسيل قطط، غسيل كلاب، قص أظافر، تنظيف أذنين، حلاقة شعر، فحص فطريات. احجز الآن">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ url('/logo.php') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="{{ config('brand.name') }} الرياض">
    <meta property="og:locale" content="ar_SA">
    <meta property="og:locale:alternate" content="en_US">
    
    <!-- Enhanced Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ config('brand.name') }} الرياض | أفضل خدمات غسيل وتجميل القطط والكلاب">
    <meta name="twitter:description" content="{{ config('brand.name') }} الرياض - أفضل صالون غسيل وتجميل القطط والكلاب. خدمات منزلية: غسيل قطط، غسيل كلاب، قص أظافر، تنظيف أذنين، حلاقة شعر، فحص فطريات. احجز الآن">
    <meta name="twitter:image" content="{{ url('/logo.php') }}">
    <meta name="twitter:site" content="@catshop_riyadh">
    <meta name="twitter:creator" content="@catshop_riyadh">
    
    <!-- Enhanced Structured Data - Organization -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "{{ config('brand.name') }} الرياض",
      "alternateName": "{{ config('brand.name_en') }} Riyadh",
      "url": "{{ url('/') }}",
      "logo": "{{ url('/logo.php') }}",
      "description": "أفضل خدمات العناية بالحيوانات الأليفة في الرياض. غسيل، تجميل، قص أظافر، تنظيف أذنين للقطط والكلاب. خدمة منزلية مضمونة.",
      "foundingDate": "2024",
      "address": {
        "@type": "PostalAddress",
        "addressLocality": "الرياض",
        "addressRegion": "الرياض",
        "addressCountry": "SA",
        "postalCode": "12345"
      },
      "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "{{ config('brand.whatsapp') }}",
        "contactType": "customer service",
        "availableLanguage": "Arabic",
        "areaServed": "SA",
        "serviceArea": {
          "@type": "City",
          "name": "الرياض"
        }
      },
      "sameAs": [
        "https://catshoop1.com/"
      ],
      "hasOfferCatalog": {
        "@type": "OfferCatalog",
        "name": "خدمات العناية بالحيوانات الأليفة",
        "itemListElement": [
          {
            "@type": "Offer",
            "itemOffered": {
              "@type": "Service",
              "name": "غسيل القطط",
              "description": "خدمة غسيل وتجميل القطط مع شامبو بدون ماء",
              "category": "خدمات العناية بالقطط"
            },
            "hasMerchantReturnPolicy": {
              "@type": "MerchantReturnPolicy",
              "returnPolicyCategory": "https://schema.org/MerchantReturnFiniteReturnWindow",
              "merchantReturnDays": 7,
              "returnMethod": "https://schema.org/ReturnByMail",
              "returnFees": "https://schema.org/FreeReturn"
            }
          },
          {
            "@type": "Offer",
            "itemOffered": {
              "@type": "Service",
              "name": "غسيل الكلاب",
              "description": "خدمة غسيل وتجميل الكلاب مع شامبو بدون ماء",
              "category": "خدمات العناية بالكلاب"
            },
            "hasMerchantReturnPolicy": {
              "@type": "MerchantReturnPolicy",
              "returnPolicyCategory": "https://schema.org/MerchantReturnFiniteReturnWindow",
              "merchantReturnDays": 7,
              "returnMethod": "https://schema.org/ReturnByMail",
              "returnFees": "https://schema.org/FreeReturn"
            }
          }
        ]
      }
    }
    </script>
    
    <!-- Enhanced Structured Data - LocalBusiness -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "LocalBusiness",
      "name": "{{ config('brand.name') }} الرياض",
      "image": "{{ url('/logo.php') }}",
      "description": "أفضل خدمات العناية بالحيوانات الأليفة في الرياض - غسيل وتجميل القطط والكلاب",
      "url": "{{ url('/') }}",
      "telephone": "{{ config('brand.whatsapp') }}",
      "address": {
        "@type": "PostalAddress",
        "addressLocality": "الرياض",
        "addressRegion": "الرياض",
        "addressCountry": "SA"
      },
      "geo": {
        "@type": "GeoCoordinates",
        "latitude": 24.7136,
        "longitude": 46.6753
      },
      "openingHoursSpecification": {
        "@type": "OpeningHoursSpecification",
        "dayOfWeek": [
          "Monday",
          "Tuesday", 
          "Wednesday",
          "Thursday",
          "Friday",
          "Saturday"
        ],
        "opens": "08:00",
        "closes": "22:00"
      },
      "priceRange": "$$",
      "currenciesAccepted": "SAR",
      "paymentAccepted": "Cash, Credit Card",
      "areaServed": {
        "@type": "City",
        "name": "الرياض"
      },
      "serviceType": "Pet Grooming Services",
      "hasOfferCatalog": {
        "@type": "OfferCatalog",
        "name": "خدمات العناية بالحيوانات الأليفة",
        "itemListElement": [
          {
            "@type": "Offer",
            "itemOffered": {
              "@type": "Service",
              "name": "غسيل وتجميل القطط",
              "description": "خدمة شاملة لغسيل وتجميل القطط مع شامبو بدون ماء",
              "category": "خدمات العناية بالقطط"
            },
            "priceCurrency": "SAR",
            "availability": "https://schema.org/InStock",
            "hasMerchantReturnPolicy": {
              "@type": "MerchantReturnPolicy",
              "returnPolicyCategory": "https://schema.org/MerchantReturnFiniteReturnWindow",
              "merchantReturnDays": 7,
              "returnMethod": "https://schema.org/ReturnByMail",
              "returnFees": "https://schema.org/FreeReturn"
            }
          },
          {
            "@type": "Offer",
            "itemOffered": {
              "@type": "Service",
              "name": "غسيل وتجميل الكلاب",
              "description": "خدمة شاملة لغسيل وتجميل الكلاب مع شامبو بدون ماء",
              "category": "خدمات العناية بالكلاب"
            },
            "priceCurrency": "SAR",
            "availability": "https://schema.org/InStock",
            "hasMerchantReturnPolicy": {
              "@type": "MerchantReturnPolicy",
              "returnPolicyCategory": "https://schema.org/MerchantReturnFiniteReturnWindow",
              "merchantReturnDays": 7,
              "returnMethod": "https://schema.org/ReturnByMail",
              "returnFees": "https://schema.org/FreeReturn"
            }
          }
        ]
      }
    }
    </script>
    
    <!-- Enhanced Structured Data - Service -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Service",
      "name": "خدمات غسيل وتجميل الحيوانات الأليفة",
      "description": "خدمات شاملة للعناية بالحيوانات الأليفة تشمل غسيل القطط والكلاب، قص الأظافر، تنظيف الأذنين، حلاقة الشعر، فحص الفطريات",
      "provider": {
        "@type": "Organization",
        "name": "{{ config('brand.name') }} الرياض"
      },
      "areaServed": {
        "@type": "City",
        "name": "الرياض"
      },
      "serviceType": "Pet Grooming",
      "category": "Pet Services",
      "offers": {
        "@type": "Offer",
        "priceCurrency": "SAR",
        "availability": "https://schema.org/InStock",
        "hasMerchantReturnPolicy": {
          "@type": "MerchantReturnPolicy",
          "returnPolicyCategory": "https://schema.org/MerchantReturnFiniteReturnWindow",
          "merchantReturnDays": 7,
          "returnMethod": "https://schema.org/ReturnByMail",
          "returnFees": "https://schema.org/FreeReturn"
        }
      }
    }
    </script>
    
    <!-- Enhanced Structured Data - FAQ -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "FAQPage",
      "mainEntity": [
        {
          "@type": "Question",
          "name": "ما هي خدمات {{ config('brand.name') }} المتاحة؟",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "{{ config('brand.name') }} تقدم خدمات شاملة للعناية بالحيوانات الأليفة تشمل: غسيل وتجميل القطط والكلاب، قص الأظافر، تنظيف الأذنين، حلاقة الشعر، فحص الفطريات بأشعة UV، والعديد من الخدمات الإضافية."
          }
        },
        {
          "@type": "Question", 
          "name": "هل تقدمون خدمة منزلية؟",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "نعم، نقدم خدمة منزلية مريحة في جميع أنحاء الرياض. فريقنا المحترف يأتي إلى منزلك مع جميع المعدات اللازمة لضمان راحة حيوانك الأليف."
          }
        },
        {
          "@type": "Question",
          "name": "كيف يمكنني حجز موعد؟",
          "acceptedAnswer": {
            "@type": "Answer", 
            "text": "يمكنك الحجز بسهولة من خلال موقعنا الإلكتروني أو تطبيق الهاتف. اختر الخدمة المطلوبة، حدد التاريخ والوقت المناسب، وسنأتي إليك في الموعد المحدد."
          }
        },
        {
          "@type": "Question",
          "name": "ما هي أسعار الخدمات؟",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "نقدم أسعار تنافسية ومناسبة لجميع الخدمات. تبدأ أسعار غسيل القطط من 50 ريال، وغسيل الكلاب من 80 ريال. تواصل معنا للحصول على عرض سعر دقيق."
          }
        }
      ]
    }
    </script>
    
    <!-- Enhanced Structured Data - BreadcrumbList -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      "itemListElement": [
        {
          "@type": "ListItem",
          "position": 1,
          "name": "الرئيسية",
          "item": "{{ url('/') }}"
        }
      ]
    }
    </script>
    
    <!-- Additional SEO Meta Tags -->
    <meta name="application-name" content="{{ config('brand.name') }} الرياض">
    <meta name="msapplication-TileColor" content="#ec4899">
    <meta name="theme-color" content="#ec4899">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="{{ config('brand.name') }} الرياض">
    <meta name="format-detection" content="telephone=no">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="msapplication-config" content="/browserconfig.xml">
    <meta name="msapplication-tap-highlight" content="no">
    
    <!-- Social Media Meta Tags -->
    <meta property="fb:app_id" content="your-facebook-app-id">
    <meta property="fb:pages" content="your-facebook-page-id">
    <meta name="twitter:app:name:iphone" content="{{ config('brand.name') }} الرياض">
    <meta name="twitter:app:id:iphone" content="your-app-store-id">
    <meta name="twitter:app:name:ipad" content="{{ config('brand.name') }} الرياض">
    <meta name="twitter:app:id:ipad" content="your-app-store-id">
    <meta name="twitter:app:name:googleplay" content="{{ config('brand.name') }} الرياض">
    <meta name="twitter:app:id:googleplay" content="your-play-store-id">
    
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ config('brand.name') }} الرياض - أفضل خدمات غسيل وتجميل القطط والكلاب">
    <meta itemprop="description" content="{{ config('brand.name') }} الرياض - أفضل صالون غسيل وتجميل القطط والكلاب. خدمات منزلية: غسيل قطط، غسيل كلاب، قص أظافر، تنظيف أذنين، حلاقة شعر، فحص فطريات. احجز الآن">
    <meta itemprop="image" content="{{ url('/logo.php') }}">
    
    <!-- Additional structured data for better SEO -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "name": "{{ config('brand.name') }} الرياض",
      "url": "{{ url('/') }}",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "{{ url('/search?q={search_term_string}') }}",
        "query-input": "required name=search_term_string"
      }
    }
    </script>
    
    <!-- Hreflang for Arabic -->
    <link rel="alternate" hreflang="ar" href="{{ url()->current() }}">
    <link rel="alternate" hreflang="x-default" href="{{ url()->current() }}">
    
    <!-- Sitemap Links -->
    <link rel="sitemap" type="application/xml" title="Sitemap" href="{{ url('/sitemap.xml') }}">
    <link rel="sitemap" type="application/xml" title="Products Sitemap" href="{{ url('/sitemap-products.xml') }}">
    
    <!-- RSS Feed -->
    <link rel="alternate" type="application/rss+xml" title="{{ config('brand.name') }} الرياض - آخر المنتجات والخدمات" href="{{ url('/feed.xml') }}">
@endsection

@section('content')

    <!-- Hero Section -->
    <section class="hero-section text-white py-20 relative overflow-hidden">
        
        <!-- Overlay for better text readability -->
        <div class="absolute inset-0 bg-black bg-opacity-30"></div>
        
        <div class="container mx-auto px-4 text-center relative z-10">
            <div class="max-w-3xl mx-auto">
                <!-- Typewriter Message -->
                <div class="typewriter-container mb-6">
                    <h2 class="typewriter-text text-lg md:text-xl lg:text-2xl font-medium text-pink-200 mb-4">
                        <span class="typewriter-cursor">|</span>
                    </h2>
                </div>
                
                <!-- Welcome Message with Animation -->
                <div class="welcome-message-container mb-8">
                    <h1 class="welcome-text text-xl md:text-2xl lg:text-3xl font-bold mb-4 text-white">
                        مرحبًا بك في {{ config('brand.name') }}! خدمات غسيل متنقلة واحترافية لحيوانك الأليف... نوصل لبابك 🚐
                    </h1>
                </div>
                
                <h2 class="custom-customers-title text-4xl md:text-6xl font-bold mb-6 text-white">
                    عملائنا الأعزاء في شرق الرياض
                </h2>
                <p class="text-xl mb-8 text-pink-100">
                    لحجز المواعيد
                </p>
                <p class="text-xl mb-8 text-pink-100">
                    تواصلوا معنا على الرقم التالي
                </p>
                
                <!-- Action Buttons Container -->
                <div class="action-buttons-container">
                    <!-- Phone Button -->
                    <a href="tel:{{ config('brand.phone') }}" class="action-btn phone-btn" title="اتصل بنا">
                        <i class="fas fa-phone"></i>
                        <span class="btn-text">اتصل بنا</span>
                    </a>
                    
                    <!-- WhatsApp Button -->
                    <a href="https://wa.me/{{ config('brand.whatsapp') }}" target="_blank" rel="noopener noreferrer" class="action-btn whatsapp-btn" title="واتساب">
                        <i class="fab fa-whatsapp"></i>
                        <span class="btn-text">واتساب</span>
                    </a>
                    
                    <!-- Online Store Button -->
                    <a href="https://catshoop1.com/" target="_blank" rel="noopener noreferrer" class="action-btn store-btn" title="متجرنا الإلكتروني">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="btn-text">متجرنا الإلكتروني</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section id="products" class="py-10 md:py-20">
        <div class="container mx-auto px-4">
            <div class="text-center mb-8 md:mb-16">
                <h3 class="text-2xl md:text-3xl font-bold services-title mb-2 md:mb-4">خدمات غسيل وتجميل القطط والكلاب</h3>
                <p class="text-gray-600 max-w-2xl mx-auto text-sm md:text-base">
                    أفضل خدمات العناية بالحيوانات الأليفة في الرياض - غسيل قطط، غسيل كلاب، قص أظافر، تنظيف أذنين، حلاقة شعر
                </p>
            </div>

            <!-- Swiper -->
            <div class="swiper">
                <div class="swiper-wrapper">
                    @forelse($products as $product)
                    <div class="swiper-slide">
                        <div class="bg-white rounded-lg md:rounded-2xl shadow-md md:shadow-lg overflow-hidden card-hover transition-all duration-300" data-product-id="{{ $product->id }}">
                            <!-- Product Image -->
                          <div class="relative h-48 md:h-80 bg-gradient-to-br from-pink-100 to-purple-100 cursor-pointer" onclick="goToBooking({{ $product->id }})">
                                @if($product->image_gallery && count($product->image_gallery) > 0)
                                    <img src="{{ asset('images/default-product.svg') }}" 
                                         data-src="{{ getProductImageUrl($product->image_gallery[0]) }}" 
                                         alt="{{ $product->name }} - {{ $product->short_description }}" 
                                         title="{{ $product->name }} - خدمة {{ $product->name }} في الرياض"
                                         loading="lazy"
                                         decoding="async"
                                         width="400"
                                         height="300"
                                         class="w-full h-full object-cover lazy-image"
                                         onerror="this.onerror=null; this.src='{{ asset('images/default-product.svg') }}';">
                                @else
                                    <div class="flex items-center justify-center h-full">
                                        <i class="fas fa-paw text-xl md:text-4xl text-pink-400"></i>
                                    </div>
                                @endif
                                
                                <!-- Price Badge -->
                                <div class="absolute top-1 right-1 md:top-4 md:right-4 bg-pink-600 text-white px-1.5 py-0.5 md:px-3 md:py-1 rounded-full text-xs md:text-sm font-semibold">
                                    {{ number_format($product->price, 2) }} <span class="icon-saudi_riyal"></span>
                                </div>
                                
                                <!-- Hover Overlay -->
                                <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-20 transition-all duration-300 flex items-center justify-center">
                                    <div class="text-white opacity-0 hover:opacity-100 transition-opacity duration-300 text-center">
                                        <i class="fas fa-calendar-plus text-2xl mb-2"></i>
                                        <p class="text-sm font-semibold">اضغط للحجز</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Product Content -->
                            <div class="p-2 md:p-6 flex flex-col h-full">
                                <h4 class="text-xs md:text-xl font-bold text-gray-800 mb-1 md:mb-2 line-clamp-1 md:line-clamp-2">{{ $product->name }}</h4>
                                <p class="text-gray-600 text-xs md:text-sm mb-1 md:mb-4 line-clamp-2">{{ $product->short_description }}</p>
                                


                                <!-- Book Button -->
                                <div class="mt-auto pt-1 space-y-2">
                                    <form action="{{ route('frontend.booking.start') }}" method="POST" class="w-full">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <button type="submit" class="w-full bg-gradient-to-r from-pink-600 to-purple-600 text-white py-2 md:py-3 rounded-md md:rounded-lg font-semibold hover:from-pink-700 hover:to-purple-700 transition-all duration-300 flex items-center justify-center gap-1 md:gap-2 text-xs md:text-sm shadow-lg hover:shadow-xl border-0 outline-none">
                                            <i class="fas fa-calendar-plus text-sm md:text-base"></i>
                                            <span class="font-bold">احجز الآن</span>
                                        </button>
                                    </form>
                                    
                                    <!-- Add to Cart Button -->
                                    <button type="button" 
                                            onclick="openAddToCartModal({{ $product->id }}, '{{ $product->name }}')" 
                                            class="w-full bg-gradient-to-r from-pink-600 to-purple-600 text-white py-2 md:py-3 rounded-md md:rounded-lg font-semibold hover:from-pink-700 hover:to-purple-700 transition-all duration-300 flex items-center justify-center gap-1 md:gap-2 text-xs md:text-sm shadow-lg hover:shadow-xl border-0 outline-none">
                                        <i class="fas fa-shopping-cart text-sm md:text-base"></i>
                                        <span class="font-bold">أضف للسلة</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="swiper-slide">
                        <div class="col-span-full text-center py-8 md:py-16">
                            <div class="text-gray-400 mb-4">
                                <i class="fas fa-box-open text-4xl md:text-6xl"></i>
                            </div>
                            <h4 class="text-lg md:text-xl text-gray-600 mb-2">لا توجد منتجات متاحة حالياً</h4>
                            <p class="text-gray-500 text-sm md:text-base">يرجى المحاولة مرة أخرى لاحقاً</p>
                        </div>
                    </div>
                    @endforelse
                </div>
                
                <!-- Navigation arrows -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                
                <!-- Pagination -->
                <div class="swiper-pagination"></div>
            </div>
            
            <!-- زر عرض المزيد -->
            <div class="text-center mt-8">
                <a href="{{ route('frontend.products') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-pink-600 to-purple-600 text-white font-semibold rounded-lg hover:from-pink-700 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                    <i class="fas fa-eye ml-2"></i>
                    عرض جميع الخدمات
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="services-section">
        <div class="container mx-auto px-4">
            <div class="services-container">
                <h3 class="services-title">لماذا تختار {{ config('brand.name') }} لغسيل قططك وكلابك؟</h3>
                <p class="services-subtitle">أفضل خدمات غسيل وتجميل القطط والكلاب في الرياض - خدمة منزلية مضمونة مع فريق محترف</p>

                <div class="services-grid grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="service-card text-center">
                        <div class="icon-container">
                            <i class="fas fa-home service-icon"></i>
                        </div>
                        <h4 class="service-title">خدمة غسيل منزلية</h4>
                        <p class="service-description">نأتي إليك في المنزل لغسيل قططك وكلابك بأعلى معايير الجودة</p>
                    </div>
                    
                    <div class="service-card text-center">
                        <div class="icon-container">
                            <i class="fas fa-star service-icon"></i>
                        </div>
                        <h4 class="service-title">جودة غسيل عالية</h4>
                        <p class="service-description">فريق محترف متخصص في غسيل وتجميل القطط والكلاب مع منتجات آمنة</p>
                    </div>
                    
                    <div class="service-card text-center">
                        <div class="icon-container">
                            <i class="fas fa-clock service-icon"></i>
                        </div>
                        <h4 class="service-title">مواعيد مرنة</h4>
                        <p class="service-description">احجز الموعد الذي يناسبك في أي وقت</p>
                    </div>
                    
                    <!-- الخدمات الجديدة -->
                    <div class="service-card text-center">
                        <div class="icon-container">
                            <i class="fas fa-shield-alt service-icon"></i>
                        </div>
                        <h4 class="service-title">أمان ورعاية مضمونة</h4>
                        <p class="service-description">نهتم براحة وأمان أليفك كما لو كان فردًا من عائلتنا</p>
                    </div>
                    
                    <div class="service-card text-center">
                        <div class="icon-container">
                            <i class="fas fa-tags service-icon"></i>
                        </div>
                        <h4 class="service-title">أسعار مناسبة</h4>
                        <p class="service-description">خدمة احترافية بأسعار في متناول الجميع</p>
                    </div>
                    
                    <div class="service-card text-center">
                        <div class="icon-container">
                            <i class="fas fa-map-marker-alt service-icon"></i>
                        </div>
                        <h4 class="service-title">تغطية سريعة داخل الرياض</h4>
                        <p class="service-description">نغطي مناطق متعددة من الرياض للوصول إليك بسرعة</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Customer Reviews Section -->
    <section class="reviews-section py-16 md:py-20">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h3 class="reviews-title text-3xl md:text-4xl font-bold mb-4">آراء عملائنا</h3>
                <p class="reviews-subtitle text-gray-600 text-lg">ماذا يقول عملاؤنا عن خدماتنا</p>
            </div>

            <!-- Reviews Swiper -->
            <div class="reviews-swiper swiper">
                <div class="swiper-wrapper">
                    <!-- Review 1 -->
                    <div class="swiper-slide">
                        <div class="review-card">
                            <div class="review-header">
                                <div class="reviewer-avatar">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                <div class="reviewer-info">
                                    <h4 class="reviewer-name">نورة العتيبي</h4>
                                    <div class="review-stars">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="review-text">الخدمة ممتازة جدًا! وصلوا في الوقت المحدد وتعاملهم راقٍ مع أليفي، أنصح أي أحد يجربهم بدون تردد.</p>
                        </div>
                    </div>

                    <!-- Review 2 -->
                    <div class="swiper-slide">
                        <div class="review-card">
                            <div class="review-header">
                                <div class="reviewer-avatar">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                <div class="reviewer-info">
                                    <h4 class="reviewer-name">سارة الشهراني</h4>
                                    <div class="review-stars">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="review-text">طلبت خدمة غسيل للقطتي، كانوا حريصين واحترافيين جدًا. حبيت التنظيم والدقة.</p>
                        </div>
                    </div>

                    <!-- Review 3 -->
                    <div class="swiper-slide">
                        <div class="review-card">
                            <div class="review-header">
                                <div class="reviewer-avatar">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                <div class="reviewer-info">
                                    <h4 class="reviewer-name">ريم القحطاني</h4>
                                    <div class="review-stars">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="review-text">أول مرة أجربهم، بس أكيد مو الأخيرة! أليفتي صارت مرتاحة ومبسوطة بعد الخدمة</p>
                        </div>
                    </div>

                    <!-- Review 4 -->
                    <div class="swiper-slide">
                        <div class="review-card">
                            <div class="review-header">
                                <div class="reviewer-avatar">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                <div class="reviewer-info">
                                    <h4 class="reviewer-name">تركي العسيري</h4>
                                    <div class="review-stars">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="review-text">اليفتي كانت تتوتر من الغسيل لكن الفريق عرف يتعامل معها بهدوء واحتراف. تجربة تخليني أطلبهم دائمًا.</p>
                        </div>
                    </div>

                    <!-- Review 5 -->
                    <div class="swiper-slide">
                        <div class="review-card">
                            <div class="review-header">
                                <div class="reviewer-avatar">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                <div class="reviewer-info">
                                    <h4 class="reviewer-name">هنادي الحربي</h4>
                                    <div class="review-stars">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="review-text">الأسعار ممتازة مقابل الخدمة، وفريقهم جداً محترم. شكراً {{ config('brand.name') }}</p>
                        </div>
                    </div>

                    <!-- Review 6 -->
                    <div class="swiper-slide">
                        <div class="review-card">
                            <div class="review-header">
                                <div class="reviewer-avatar">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                <div class="reviewer-info">
                                    <h4 class="reviewer-name">مشاعل السبيعي</h4>
                                    <div class="review-stars">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="review-text">أنا من الناس اللي تهتم كثير بنظافة الحيوان الأليف، {{ config('brand.name') }} خلوني مرتاحة بكل مراحل الخدمة</p>
                        </div>
                    </div>

                    <!-- Review 7 -->
                    <div class="swiper-slide">
                        <div class="review-card">
                            <div class="review-header">
                                <div class="reviewer-avatar">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                <div class="reviewer-info">
                                    <h4 class="reviewer-name">أمل الدوسري</h4>
                                    <div class="review-stars">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="review-text">أول مرة اطلب خدمة غسيل متنقلة التجربة كانت فوق المتوقع. تعامل راقٍ وسرعة في التنفيذ</p>
                        </div>
                    </div>

                    <!-- Review 8 -->
                    <div class="swiper-slide">
                        <div class="review-card">
                            <div class="review-header">
                                <div class="reviewer-avatar">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                <div class="reviewer-info">
                                    <h4 class="reviewer-name">غادة المطيري</h4>
                                    <div class="review-stars">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="review-text">خدمة منزلية مريحة وسهلة، ما توقعت تكون التجربة بهالاحترافية. أكيد بعيد التجربة</p>
                        </div>
                    </div>

                    <!-- Review 9 -->
                    <div class="swiper-slide">
                        <div class="review-card">
                            <div class="review-header">
                                <div class="reviewer-avatar">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                <div class="reviewer-info">
                                    <h4 class="reviewer-name">جواهر العنزي</h4>
                                    <div class="review-stars">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="review-text">الطلب سهل من الموقع، والمواعيد مرنة. تعاملهم مع الحيوان كان جداً إنساني ومحترم</p>
                        </div>
                    </div>

                    <!-- Review 10 -->
                    <div class="swiper-slide">
                        <div class="review-card">
                            <div class="review-header">
                                <div class="reviewer-avatar">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                <div class="reviewer-info">
                                    <h4 class="reviewer-name">دلال اليامي</h4>
                                    <div class="review-stars">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="review-text">عجبني اهتمامهم بالتفاصيل الصغيرة حتى الروائح اللي يستخدموها كانت مريحة وآمنة</p>
                        </div>
                    </div>

                    <!-- Review 11 -->
                    <div class="swiper-slide">
                        <div class="review-card">
                            <div class="review-header">
                                <div class="reviewer-avatar">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                <div class="reviewer-info">
                                    <h4 class="reviewer-name">عبدالله الغامدي</h4>
                                    <div class="review-stars">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="review-text">ما كنت متوقع الخدمة تكون بهذا المستوى! الفريق محترف جدًا والتعامل راقٍ، أنصح فيها خصوصًا لأصحاب القطط.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Navigation arrows -->
                <div class="swiper-button-next reviews-nav-next"></div>
                <div class="swiper-button-prev reviews-nav-prev"></div>
                
                <!-- Pagination -->
                <div class="swiper-pagination reviews-pagination"></div>
            </div>
        </div>
    </section>

    <!-- Statistics Section with Video Background -->
    <section class="statistics-section py-6 md:py-12 lg:py-16 relative overflow-hidden">
        <!-- Video Background -->
        <div class="statistics-video-background">
            <video autoplay muted loop playsinline preload="none" class="statistics-video" loading="lazy" poster="{{ asset('images/logo.png') }}">
                <source src="{{ asset('images/Video.mp4') }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            <div class="statistics-video-overlay"></div>
        </div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center mb-6">
                <h3 class="statistics-title text-xl md:text-2xl font-bold mb-1">إحصائياتنا</h3>
                <p class="statistics-subtitle text-white text-sm">أرقام تتحدث عن نجاحنا وثقة عملائنا</p>
            </div>

            <div class="statistics-grid grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                <!-- Customer Satisfaction Rate -->
                <div class="statistic-icon-item text-center">
                    <div class="statistic-icon-container">
                        <i class="fas fa-heart statistic-icon"></i>
                    </div>
                    <div class="statistic-number-container">
                        <span class="statistic-number" data-target="98">0</span>
                        <span class="statistic-symbol">%</span>
                    </div>
                    <h4 class="statistic-title">معدل رضا العملاء</h4>
                </div>

                <!-- Completed Appointments -->
                <div class="statistic-icon-item text-center">
                    <div class="statistic-icon-container">
                        <i class="fas fa-calendar-check statistic-icon"></i>
                    </div>
                    <div class="statistic-number-container">
                        <span class="statistic-number" data-target="10000">0</span>
                        <span class="statistic-symbol">+</span>
                    </div>
                    <h4 class="statistic-title">المواعيد المنجزة</h4>
                </div>

                <!-- Animals Served -->
                <div class="statistic-icon-item text-center">
                    <div class="statistic-icon-container">
                        <i class="fas fa-paw statistic-icon"></i>
                    </div>
                    <div class="statistic-number-container">
                        <span class="statistic-number" data-target="12000">0</span>
                        <span class="statistic-symbol">+</span>
                    </div>
                    <h4 class="statistic-title">عدد الحيوانات التي تم خدمتها</h4>
                </div>

                <!-- Happy Customers -->
                <div class="statistic-icon-item text-center">
                    <div class="statistic-icon-container">
                        <i class="fas fa-smile statistic-icon"></i>
                    </div>
                    <div class="statistic-number-container">
                        <span class="statistic-number" data-target="20000">0</span>
                        <span class="statistic-symbol">+</span>
                    </div>
                    <h4 class="statistic-title">عدد العملاء السعداء</h4>
                </div>
            </div>
        </div>
    </section>

    <!-- SEO Content Section -->
    <section class="seo-content-section">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto text-center">
                <h2 class="seo-main-title">{{ config('brand.name') }} الرياض - رقم 1 في غسيل وتجميل القطط والكلاب | خدمة منزلية مضمونة</h2>
                
                <div class="grid md:grid-cols-2 gap-8 text-right">
                    <div class="seo-service-card">
                        <h3 class="seo-service-title">خدمات غسيل القطط</h3>
                        <p class="seo-service-description">
                            نقدم أفضل خدمات غسيل القطط في الرياض مع شامبو بدون ماء آمن ومريح لقطتك. 
                            فريقنا المحترف متخصص في التعامل مع القطط بحرص ورعاية فائقة.
                        </p>
                        <ul class="seo-service-list">
                            <li>غسيل قطط بالشامبو الجاف</li>
                            <li>قص أظافر القطط</li>
                            <li>تنظيف أذنين القطط</li>
                            <li>حلاقة شعر القطط</li>
                            <li>فحص فطريات بأشعة UV</li>
                        </ul>
                    </div>
                    
                    <div class="seo-service-card">
                        <h3 class="seo-service-title">خدمات غسيل الكلاب</h3>
                        <p class="seo-service-description">
                            خدمات شاملة لغسيل وتجميل الكلاب في الرياض. نستخدم أفضل المنتجات 
                            الآمنة لضمان راحة كلبك والحصول على أفضل النتائج.
                        </p>
                        <ul class="seo-service-list">
                            <li>غسيل كلاب بالشامبو الجاف</li>
                            <li>قص أظافر الكلاب</li>
                            <li>تنظيف أذنين الكلاب</li>
                            <li>حلاقة شعر الكلاب</li>
                            <li>تمشيط وتصفيف الشعر</li>
                        </ul>
                    </div>
                </div>
                
                <div class="seo-why-choose">
                    <h3 class="seo-why-choose-title">لماذا تختار {{ config('brand.name') }} الرياض؟</h3>
                    <p class="seo-why-choose-description">
                        {{ config('brand.name') }} الرياض هو الخيار الأول لأصحاب القطط والكلاب في الرياض. نحن نقدم خدمة منزلية مريحة 
                        مع فريق محترف متخصص في العناية بالحيوانات الأليفة. جميع خدماتنا مضمونة ونستخدم 
                        أفضل المنتجات الآمنة لضمان راحة حيوانك الأليف. {{ config('brand.name') }} الرياض - الأفضل في غسيل وتجميل القطط والكلاب.
                    </p>
                </div>
                
                <!-- Additional SEO Content -->
                <div class="mt-12 grid md:grid-cols-3 gap-6 text-right">
                    <div class="seo-service-card">
                        <h3 class="seo-service-title">{{ config('brand.name') }} الرياض</h3>
                        <p class="seo-service-description">
                            {{ config('brand.name') }} الرياض هو أفضل صالون غسيل وتجميل القطط والكلاب في الرياض. 
                            نقدم خدمات احترافية بأسعار مناسبة مع ضمان الجودة.
                        </p>
                    </div>
                    
                    <div class="seo-service-card">
                        <h3 class="seo-service-title">غسيل قطط الرياض</h3>
                        <p class="seo-service-description">
                            خدمة غسيل قطط متخصصة في الرياض. نستخدم شامبو آمن ومريح للقطط 
                            مع فريق محترف متخصص في التعامل مع القطط.
                        </p>
                    </div>
                    
                    <div class="seo-service-card">
                        <h3 class="seo-service-title">غسيل كلاب الرياض</h3>
                        <p class="seo-service-description">
                            خدمة غسيل كلاب شاملة في الرياض. نقدم جميع خدمات العناية بالكلاب 
                            مع منتجات آمنة ومضمونة الجودة.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="bg-gray-100 text-gray-800 py-16">
        <div class="container mx-auto px-4 text-center">
            <h3 class="text-3xl font-bold mb-4">هل تحتاج مساعدة؟</h3>
            <p class="text-gray-600 mb-8">فريق خدمة العملاء لدينا مستعد لمساعدتك في أي وقت</p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="tel:{{ config('brand.phone') }}" class="bg-white text-pink-600 px-6 py-3 rounded-full font-semibold hover:bg-pink-50 transition-colors flex items-center gap-2">
                    <i class="fas fa-phone"></i>
                    اتصل بنا: {{ config('brand.phone') }}
                </a>
                <a href="https://wa.me/{{ config('brand.whatsapp') }}" class="bg-green-500 text-white px-6 py-3 rounded-full font-semibold hover:bg-green-600 transition-colors flex items-center gap-2">
                    <i class="fab fa-whatsapp"></i>
                    واتساب
                </a>

            </div>
        </div>
    </section>

    <!-- Add to Cart Modal -->
    <div id="addToCartModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="p-6">
                                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-800">أضف للسلة</h3>
                            <button onclick="closeAddToCartModal()" class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                        
                        @if(session('customer_id'))
                            <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-4">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-check-circle text-green-600"></i>
                                    <span class="text-green-800 text-sm">مرحباً {{ session('customer_name') }}! سيتم استخدام بياناتك المسجلة</span>
                                </div>
                            </div>
                        @endif
                
                <form id="addToCartForm" class="space-y-4">
                    @csrf
                    <input type="hidden" id="cartProductId" name="product_id">
                    
                                                <div>
                                <label for="customerName" class="block text-sm font-medium text-gray-700 mb-1">اسم العميل *</label>
                                <input type="text" id="customerName" name="customer_name" required 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent @if(session('customer_id')) bg-gray-100 cursor-not-allowed @endif"
                                        placeholder="أدخل اسمك"
                                        @if(session('customer_id')) disabled @endif>
                                <div id="nameError" class="text-red-500 text-sm mt-1 hidden"></div>
                            </div>
                            
                            <div>
                                <label for="customerPhone" class="block text-sm font-medium text-gray-700 mb-1">رقم الجوال *</label>
                                <input type="tel" id="customerPhone" name="customer_phone" required 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent @if(session('customer_id')) bg-gray-100 cursor-not-allowed @endif"
                                        placeholder="05xxxxxxxx"
                                        maxlength="10"
                                        @if(session('customer_id')) disabled @endif>
                                <div id="phoneError" class="text-red-500 text-sm mt-1 hidden">يجب أن يبدأ الرقم بـ 05 أو 5</div>
                            </div>
                    
                    <div class="flex gap-3 pt-4">
                        <button type="button" onclick="closeAddToCartModal()" 
                                class="flex-1 px-4 py-2 text-gray-600 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors">
                            إلغاء
                        </button>
                                                        <button type="submit" 
                                        class="flex-1 px-4 py-2 bg-gradient-to-r from-pink-600 to-purple-600 text-white rounded-md hover:from-pink-700 hover:to-purple-700 transition-all duration-300">
                                    @if(session('customer_id'))
                                        أضف للسلة (باستخدام بياناتك)
                                    @else
                                        أضف للسلة
                                    @endif
                                </button>
                    </div>
                    
                                            <!-- زر مسح البيانات المحفوظة -->
                        <div class="mt-3 text-center">
                            @if(session('customer_id'))
                                <a href="{{ route('frontend.customer.logout') }}" 
                                   onclick="return confirm('هل تريد تسجيل الخروج؟')"
                                   class="text-xs text-gray-500 hover:text-red-500 transition-colors">
                                    تسجيل الخروج
                                </a>
                            @else
                                <button type="button" onclick="clearCustomerData()" 
                                        class="text-xs text-gray-500 hover:text-red-500 transition-colors">
                                    مسح البيانات المحفوظة
                                </button>
                            @endif
                        </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript for Add to Cart -->
    <script>
        // دالة الحصول على بيانات العميل من localStorage
        function getCustomerData() {
            const savedData = localStorage.getItem('catShopCustomerData');
            return savedData ? JSON.parse(savedData) : { name: '', phone: '' };
        }
        
        // دالة حفظ بيانات العميل في localStorage
        function saveCustomerData(name, phone) {
            const customerData = { name: name, phone: phone };
            localStorage.setItem('catShopCustomerData', JSON.stringify(customerData));
        }
        
        // دالة التحقق من صحة رقم الجوال السعودي
        function validateSaudiPhone(phone) {
            // إزالة المسافات والرموز
            phone = phone.replace(/\s/g, '').replace(/[^\d]/g, '');
            
            // التحقق من أن الرقم يبدأ بـ 05 أو 5
            if (phone.startsWith('05') && phone.length === 10) {
                return true;
            } else if (phone.startsWith('5') && phone.length === 9) {
                return true;
            }
            return false;
        }
        
        // دالة إظهار/إخفاء رسائل الخطأ
        function showError(elementId, message) {
            const errorElement = document.getElementById(elementId);
            errorElement.textContent = message;
            errorElement.classList.remove('hidden');
        }
        
        function hideError(elementId) {
            const errorElement = document.getElementById(elementId);
            errorElement.classList.add('hidden');
        }
        
                        function openAddToCartModal(productId, productName) {
                    document.getElementById('cartProductId').value = productId;
                    
                    // التحقق من حالة تسجيل الدخول أولاً
                    @if(session('customer_id'))
                        // إذا كان العميل مسجل الدخول، أضف المنتج مباشرة
                        addProductToCartDirectly(productId);
                    @else
                        // التحقق من وجود بيانات العميل المحفوظة في localStorage
                        const customerData = getCustomerData();
                        if (customerData.name && customerData.phone) {
                            // إذا كانت البيانات موجودة، أضف المنتج مباشرة بدون إظهار النافذة
                            addProductToCartDirectly(productId);
                        } else {
                            // إظهار النافذة لإدخال البيانات
                            document.getElementById('addToCartModal').classList.remove('hidden');
                            document.body.style.overflow = 'hidden';
                            // تحميل البيانات المحفوظة في النموذج إذا كانت موجودة
                            loadSavedCustomerData();
                        }
                    @endif
                }
        
                        // دالة إضافة المنتج مباشرة للسلة (بدون إظهار النافذة)
                function addProductToCartDirectly(productId) {
                    @if(session('customer_id'))
                        // إذا كان العميل مسجل الدخول، استخدم بياناته من الجلسة
                        const customerName = '{{ session("customer_name") }}';
                        const customerPhone = '{{ session("customer_phone") }}';
                    @else
                        // إذا لم يكن مسجل الدخول، استخدم البيانات من localStorage
                        const customerData = getCustomerData();
                        const customerName = customerData.name;
                        const customerPhone = customerData.phone;
                    @endif
                    
                    const formData = new FormData();
                    formData.append('product_id', productId);
                    formData.append('customer_name', customerName);
                    formData.append('customer_phone', customerPhone);
                    formData.append('_token', document.querySelector('input[name="_token"]').value);
                    
                    fetch('{{ route("frontend.add-to-cart") }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // إضافة المنتج إلى localStorage
                            const productCard = document.querySelector(`[data-product-id="${productId}"]`);
                            const productName = productCard.querySelector('h4').textContent;
                            const productPrice = parseFloat(productCard.querySelector('.bg-pink-600').textContent.replace(/[^\d.]/g, ''));
                            const productImage = productCard.querySelector('img').src || '{{ asset("images/default-product.svg") }}';
                            
                            // الحصول على السلة الحالية من localStorage
                            let cart = JSON.parse(localStorage.getItem('catShopCart')) || [];
                            
                            // إضافة المنتج إلى السلة
                            cart.push({
                                id: productId,
                                name: productName,
                                price: productPrice,
                                image: productImage,
                                quantity: 1
                            });
                            
                            // حفظ السلة في localStorage
                            localStorage.setItem('catShopCart', JSON.stringify(cart));
                            
                            // تحديث عرض السلة في النافيجيشن بار
                            if (typeof window.updateCartFromPage === 'function') {
                                window.updateCartFromPage();
                            }
                            
                            // إظهار رسالة نجاح
                            @if(session('customer_id'))
                                alert('تم إضافة المنتج إلى السلة بنجاح (باستخدام بياناتك المسجلة)');
                            @else
                                alert('تم إضافة المنتج إلى السلة بنجاح (باستخدام البيانات المحفوظة)');
                            @endif
                        } else {
                            alert(data.message || 'حدث خطأ أثناء إضافة المنتج إلى السلة');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('حدث خطأ أثناء إضافة المنتج إلى السلة');
                    });
                }
        
        function closeAddToCartModal() {
            document.getElementById('addToCartModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            // لا نمسح البيانات من النموذج للحفاظ عليها
        }
        
                        // دالة لتحميل بيانات العميل المحفوظة في النموذج
                function loadSavedCustomerData() {
                    @if(session('customer_id'))
                        // إذا كان العميل مسجل الدخول، استخدم بياناته من الجلسة
                        document.getElementById('customerName').value = '{{ session("customer_name") }}';
                        document.getElementById('customerPhone').value = '{{ session("customer_phone") }}';
                    @else
                        // إذا لم يكن مسجل الدخول، استخدم البيانات من localStorage
                        const customerData = getCustomerData();
                        if (customerData.name) {
                            document.getElementById('customerName').value = customerData.name;
                        }
                        if (customerData.phone) {
                            document.getElementById('customerPhone').value = customerData.phone;
                        }
                    @endif
                }
        
                        // دالة مسح بيانات العميل المحفوظة
                function clearCustomerData() {
                    @if(session('customer_id'))
                        // إذا كان مسجل الدخول، توجيه لتسجيل الخروج
                        if (confirm('هل تريد تسجيل الخروج؟')) {
                            window.location.href = '{{ route("frontend.customer.logout") }}';
                        }
                    @else
                        // إذا لم يكن مسجل الدخول، مسح البيانات من localStorage
                        localStorage.removeItem('catShopCustomerData');
                        document.getElementById('customerName').value = '';
                        document.getElementById('customerPhone').value = '';
                        alert('تم مسح البيانات المحفوظة بنجاح');
                    @endif
                }
        
        // التحقق من صحة رقم الجوال أثناء الكتابة
        document.getElementById('customerPhone').addEventListener('input', function(e) {
            const phone = e.target.value;
            hideError('phoneError');
            
            // إزالة أي أحرف غير رقمية
            e.target.value = phone.replace(/[^\d]/g, '');
            
            // التحقق من الطول الأقصى
            if (e.target.value.length > 10) {
                e.target.value = e.target.value.substring(0, 10);
            }
        });
        
        // التحقق من صحة الاسم
        document.getElementById('customerName').addEventListener('input', function() {
            hideError('nameError');
        });
        
        document.getElementById('addToCartForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // إخفاء رسائل الخطأ السابقة
            hideError('nameError');
            hideError('phoneError');
            
            const customerName = document.getElementById('customerName').value.trim();
            const customerPhone = document.getElementById('customerPhone').value.trim();
            
            // التحقق من صحة البيانات
            let isValid = true;
            
            if (!customerName) {
                showError('nameError', 'يرجى إدخال اسم العميل');
                isValid = false;
            }
            
            if (!customerPhone) {
                showError('phoneError', 'يرجى إدخال رقم الجوال');
                isValid = false;
            } else if (!validateSaudiPhone(customerPhone)) {
                showError('phoneError', 'يجب أن يبدأ الرقم بـ 05 أو 5');
                isValid = false;
            }
            
            if (!isValid) {
                return;
            }
            
                                // حفظ بيانات العميل في localStorage (فقط إذا لم يكن مسجل الدخول)
                    @if(!session('customer_id'))
                        saveCustomerData(customerName, customerPhone);
                    @endif
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            
            submitBtn.textContent = 'جاري الإضافة...';
            submitBtn.disabled = true;
            
            fetch('{{ route("frontend.add-to-cart") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // إضافة المنتج إلى localStorage
                    const productId = document.getElementById('cartProductId').value;
                    const productCard = document.querySelector(`[data-product-id="${productId}"]`);
                    const productName = productCard.querySelector('h4').textContent;
                    const productPrice = parseFloat(productCard.querySelector('.bg-pink-600').textContent.replace(/[^\d.]/g, ''));
                    const productImage = productCard.querySelector('img').src || '{{ asset("images/default-product.svg") }}';
                    
                    // الحصول على السلة الحالية من localStorage
                    let cart = JSON.parse(localStorage.getItem('catShopCart')) || [];
                    
                    // إضافة المنتج إلى السلة
                    cart.push({
                        id: productId,
                        name: productName,
                        price: productPrice,
                        image: productImage,
                        quantity: 1
                    });
                    
                    // حفظ السلة في localStorage
                    localStorage.setItem('catShopCart', JSON.stringify(cart));
                    
                    // تحديث عرض السلة في النافيجيشن بار
                    if (typeof window.updateCartFromPage === 'function') {
                        window.updateCartFromPage();
                    }
                    
                                                // إظهار رسالة نجاح
                            @if(session('customer_id'))
                                alert('تم إضافة المنتج إلى السلة بنجاح (باستخدام بياناتك المسجلة)');
                            @else
                                alert(data.message);
                            @endif
                            closeAddToCartModal();
                } else {
                    alert(data.message || 'حدث خطأ أثناء إضافة المنتج إلى السلة');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ أثناء إضافة المنتج إلى السلة');
            })
            .finally(() => {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        });
        
        // إغلاق modal عند النقر خارجه
        document.getElementById('addToCartModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAddToCartModal();
            }
        });
        
        // دالة الانتقال لصفحة الحجز
        function goToBooking(productId) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("frontend.booking.start") }}';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('input[name="_token"]').value;
            
            const productIdInput = document.createElement('input');
            productIdInput.type = 'hidden';
            productIdInput.name = 'product_id';
            productIdInput.value = productId;
            
            form.appendChild(csrfToken);
            form.appendChild(productIdInput);
            document.body.appendChild(form);
            form.submit();
        }
    </script>

@endsection 