@extends('layouts.main-layout')

@section('head')
    @php
        // إنشاء keywords ديناميكية من أسماء المنتجات
        $productKeywords = $products->pluck('name')->implode(', ');
        $allKeywords = "" . config('brand.name') . ", " . config('brand.name') . " الرياض, رقم 1, الأول, الأفضل, غسيل قطط, غسيل كلاب, تجميل قطط, تجميل كلاب, خدمة منزلية, الرياض, السعودية, احجز الآن, فوري, مضمون, احترافي, متخصص, أفضل, الأول في الرياض, الأفضل في الرياض, غسيل قطط الرياض, غسيل كلاب الرياض, تجميل قطط الرياض, تجميل كلاب الرياض, خدمة غسيل قطط, خدمة غسيل كلاب, صالون قطط, صالون كلاب, غسيل حيوانات, قص شعر قطط, قص شعر كلاب, تنظيف قطط, تنظيف كلاب, فحص قطط, فحص كلاب, رعاية حيوانات, حيوانات أليفة, قطط, كلاب, مواعيد, حجز, احجز, الآن, فوري, سريع, مضمون, احترافي, متخصص, أفضل, رقم واحد, الأول, الأول في الرياض, الأفضل في الرياض, " . $productKeywords;
        
        // إنشاء description ديناميكية
        $productNames = $products->take(5)->pluck('name')->implode('، ');
        $dynamicDescription = "" . config('brand.name') . " الرياض - رقم 1 في غسيل وتجميل القطط والكلاب. خدمة منزلية فورية ومضمونة: {$productNames} وأكثر. احجز الآن - الأفضل في الرياض!";
    @endphp
    
    <title>" . config('brand.name') . " الرياض | رقم 1 في غسيل وتجميل القطط والكلاب | احجز الآن</title>
    <meta name="description" content="{{ $dynamicDescription }}">
    <meta name="keywords" content="{{ $allKeywords }}">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="author" content="" . config('brand.name') . " الرياض - " . config('brand.name_en') . " Riyadh">
    <meta name="language" content="ar">
    <meta name="revisit-after" content="1 days">
    <meta name="distribution" content="global">
    <meta name="rating" content="general">
    <link rel="canonical" href="{{ url()->current() }}">
    
    <!-- Enhanced Geo-targeting for Riyadh -->
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
    
    <!-- Enhanced Open Graph -->
    <meta property="og:title" content="{{ config('brand.name') }} الرياض | رقم 1 في غسيل وتجميل القطط والكلاب | احجز الآن">
    <meta property="og:description" content="{{ $dynamicDescription }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/logo.png') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="{{ config('brand.name') }} الرياض">
    <meta property="og:locale" content="ar_SA">
    <meta property="og:locale:alternate" content="en_US">
    
    <!-- Enhanced Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ config('brand.name') }} الرياض | رقم 1 في غسيل وتجميل القطط والكلاب | احجز الآن">
    <meta name="twitter:description" content="{{ $dynamicDescription }}">
    <meta name="twitter:image" content="{{ asset('images/logo.png') }}">
    <meta name="twitter:site" content="@catshop_riyadh">
    <meta name="twitter:creator" content="@catshop_riyadh">
    
    <!-- Additional SEO Meta Tags -->
    <meta name="DC.title" content="جميع منتجات وخدمات {{ config('brand.name') }} الرياض">
    <meta name="DC.creator" content="{{ config('brand.name') }} الرياض">
    <meta name="DC.subject" content="خدمات العناية بالحيوانات الأليفة">
    <meta name="DC.description" content="{{ $dynamicDescription }}">
    <meta name="DC.publisher" content="{{ config('brand.name') }} الرياض">
    <meta name="DC.contributor" content="{{ config('brand.name') }} الرياض">
    <meta name="DC.date" content="{{ now()->format('Y-m-d') }}">
    <meta name="DC.type" content="Service">
    <meta name="DC.format" content="text/html">
    <meta name="DC.identifier" content="{{ url()->current() }}">
    <meta name="DC.language" content="ar">
    <meta name="DC.coverage" content="الرياض، المملكة العربية السعودية">
    <meta name="DC.rights" content="جميع الحقوق محفوظة ل{{ config('brand.name') }} الرياض">
    
    <!-- Preconnect to external domains for better performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="//www.google-analytics.com">
    <link rel="dns-prefetch" href="//www.googletagmanager.com">
    
    <!-- Enhanced Structured Data - Organization -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "{{ config('brand.name') }} الرياض",
      "alternateName": "{{ config('brand.name_en') }} Riyadh",
      "url": "{{ url('/') }}",
      "logo": "{{ asset('images/logo.png') }}",
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
          @foreach($products as $index => $product)
          {
            "@type": "Offer",
            "itemOffered": {
              "@type": "Service",
              "name": "{{ addslashes($product->name) }}",
              "description": "{{ addslashes(strip_tags($product->short_description ?? $product->name)) }}",
              "image": "{{ getProductImageUrl($product->image_gallery[0] ?? null) }}",
              "url": "{{ route('frontend.products') }}",
              "category": "خدمات العناية بالحيوانات الأليفة",
              "provider": {
                "@type": "Organization",
                "name": "{{ config('brand.name') }} الرياض"
              }
            },
            "price": "{{ $product->price }}",
            "priceCurrency": "SAR",
            "availability": "https://schema.org/InStock"
          }@if(!$loop->last),@endif
          @endforeach
        ]
      }
    }
    </script>
    
    <!-- Enhanced Structured Data - Product List -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "ItemList",
      "name": "جميع منتجات وخدمات {{ config('brand.name') }} الرياض",
      "description": "قائمة شاملة بجميع خدمات العناية بالحيوانات الأليفة المتاحة في {{ config('brand.name') }} الرياض - غسيل وتجميل القطط والكلاب، قص أظافر، تنظيف أذنين، حلاقة شعر، فحص فطريات",
      "url": "{{ url()->current() }}",
      "numberOfItems": {{ $products->count() }},
      "itemListElement": [
        @foreach($products as $index => $product)
        {
          "@type": "Product",
          "position": {{ $index + 1 }},
          "name": "{{ addslashes($product->name) }}",
          "description": "{{ addslashes(strip_tags($product->short_description ?? $product->name)) }}",
          "image": "{{ getProductImageUrl($product->image_gallery[0] ?? null) }}",
          "url": "{{ route('frontend.products') }}",
          "category": "خدمات العناية بالحيوانات الأليفة",
          "brand": {
            "@type": "Brand",
            "name": "{{ config('brand.name') }} الرياض"
          },
          "offers": {
            "@type": "Offer",
            "priceCurrency": "SAR",
            "price": "{{ $product->price }}",
            "priceValidUntil": "{{ now()->addYear()->format('Y-m-d') }}",
            "availability": "https://schema.org/InStock",
            "seller": {
              "@type": "Organization",
              "name": "{{ config('brand.name') }} الرياض"
            },
            "areaServed": {
              "@type": "City",
              "name": "الرياض"
            }
          },
          "aggregateRating": {
            "@type": "AggregateRating",
            "ratingValue": "4.8",
            "reviewCount": "150",
            "bestRating": "5",
            "worstRating": "1"
          },
          "review": {
            "@type": "Review",
            "reviewRating": {
              "@type": "Rating",
              "ratingValue": "5",
              "bestRating": "5"
            },
            "author": {
              "@type": "Person",
              "name": "عميل راضي"
            },
            "reviewBody": "خدمة ممتازة وأسعار معقولة"
          }
        }@if(!$loop->last),@endif
        @endforeach
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
        },
        {
          "@type": "ListItem",
          "position": 2,
          "name": "المنتجات والخدمات",
          "item": "{{ url()->current() }}"
        }
      ]
    }
    </script>
    
    <!-- Enhanced Structured Data - LocalBusiness -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "LocalBusiness",
      "name": "{{ config('brand.name') }} الرياض",
      "image": "{{ asset('images/logo.png') }}",
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
        "name": "خدمات العناية بالحيوانات الأليفة"
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
        },
        {
          "@type": "Question",
          "name": "هل تقدمون خدمات خاصة للقطط؟",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "نعم، نقدم خدمات خاصة للقطط تشمل: غسيل شامبو بدون ماء، فحص الفطريات بأشعة UV، قص الأظافر، تنظيف الأذنين والعيون، حلاقة الشعر، تمشيط الشعر، وإضافة البودرة والعطر."
          }
        },
        {
          "@type": "Question",
          "name": "هل تقدمون خدمات خاصة للكلاب؟",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "نعم، نقدم خدمات خاصة للكلاب تشمل: غسيل الكلاب الصغيرة والكبيرة، شامبو بدون ماء، فحص الفطريات، تنظيف الأذنين والعيون، قص الأظافر، حلاقة الشعر، تمشيط الشعر، وإضافة البودرة والعطر."
          }
        }
      ]
    }
    </script>
    
    <!-- Enhanced Structured Data - Service -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Service",
      "name": "خدمات غسيل وتجميل الحيوانات الأليفة",
      "description": "خدمات شاملة للعناية بالحيوانات الأليفة تشمل غسيل القطط والكلاب، قص الأظافر، تنظيف الأذنين، حلاقة الشعر، فحص الفطريات، تمشيط الشعر، وإضافة البودرة والعطر",
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
        "availability": "https://schema.org/InStock"
      }
    }
    </script>
    
    <!-- Sitemap Links -->
    <link rel="sitemap" type="application/xml" title="Sitemap" href="{{ url('/sitemap.xml') }}">
    <link rel="sitemap" type="application/xml" title="Products Sitemap" href="{{ url('/sitemap-products.xml') }}">
    
    <!-- RSS Feed -->
    <link rel="alternate" type="application/rss+xml" title="{{ config('brand.name') }} الرياض - آخر المنتجات والخدمات" href="{{ url('/feed.xml') }}">
    
    <!-- Hreflang for Arabic -->
    <link rel="alternate" hreflang="ar" href="{{ url()->current() }}">
    <link rel="alternate" hreflang="x-default" href="{{ url()->current() }}">
    
    <!-- Preload critical resources -->
    <link rel="preload" href="{{ asset('css/frontend-styles.css') }}" as="style">
    <link rel="preload" href="{{ asset('js/frontend.js') }}" as="script">
    
    <!-- Additional SEO improvements -->
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
    <meta itemprop="name" content="جميع منتجات وخدمات {{ config('brand.name') }} الرياض">
    <meta itemprop="description" content="{{ $dynamicDescription }}">
    <meta itemprop="image" content="{{ asset('images/logo.png') }}">
    
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
@endsection

@section('content')
<style>
    /* Enhanced Hero Section Styles */
    .gradient-bg {
        background: linear-gradient(135deg, #ec4899 0%, #8b5cf6 50%, #f97316 100%);
        position: relative;
        overflow: hidden;
    }
    
    .gradient-bg::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(236, 72, 153, 0.95) 0%, rgba(139, 92, 246, 0.95) 50%, rgba(249, 115, 22, 0.95) 100%);
        z-index: 1;
    }
    
    .gradient-bg > * {
        position: relative;
        z-index: 2;
    }
    
    /* Enhanced Text Contrast */
    .gradient-bg h2 {
        color: #ffffff !important;
        text-shadow: 0 3px 10px rgba(0, 0, 0, 0.8);
        font-weight: 800;
        letter-spacing: 1px;
        line-height: 1.3;
    }
    
    .gradient-bg p {
        color: #ffffff !important;
        text-shadow: 0 2px 6px rgba(0, 0, 0, 0.9);
        font-weight: 600;
        opacity: 1;
        line-height: 1.5;
    }
    
    /* Responsive Hero Section */
    @media (max-width: 768px) {
        .gradient-bg h2 {
            font-size: 1.75rem !important;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.9);
        }
        
        .gradient-bg p {
            font-size: 1rem !important;
            text-shadow: 0 1px 5px rgba(0, 0, 0, 0.9);
        }
    }
    
    .card-hover:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
    
    /* ضمان ظهور الصور كاملة في جميع الأجهزة */
    .h-80 img {
        object-fit: cover !important;
        object-position: center !important;
        background: linear-gradient(135deg, #fdf2f8 0%, #f3e8ff 100%) !important;
        width: 100% !important;
        height: 100% !important;
        display: block !important;
    }
    
    /* تنسيقات خاصة للجوال فقط */
    @media (max-width: 768px) {
        /* تغيير التخطيط إلى عمودين في الجوال */
        .grid {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 4px !important;
        }
        
        /* تصغير البطاقات في الجوال */
        .bg-white {
            border-radius: 12px !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
            border: 1px solid rgba(236, 72, 153, 0.1) !important;
        }
        
        /* تصغير الصور في الجوال مع ضمان ظهورها كاملة */
        .h-80 {
            height: 360px !important;
            overflow: hidden !important;
            position: relative !important;
        }
        
        .h-80 img {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            object-position: center !important;
            display: block !important;
        }
        
        /* تصغير المسافات في الجوال */
        .p-6 {
            padding: 12px !important;
        }
        
        /* تصغير النصوص في الجوال */
        .text-xl {
            font-size: 14px !important;
            line-height: 1.3 !important;
        }
        
        .text-sm {
            font-size: 12px !important;
            line-height: 1.2 !important;
        }
        
        /* تصغير المسافات بين العناصر */
        .mb-2 {
            margin-bottom: 6px !important;
        }
        
        .mb-4 {
            margin-bottom: 8px !important;
        }
        
        /* تصغير الأزرار في الجوال */
        button[type="submit"] {
            padding: 10px 12px !important;
            font-size: 13px !important;
            min-height: 40px !important;
            border-radius: 8px !important;
        }
        
        /* تقييد الوصف في الجوال فقط */
        .text-gray-600.text-sm {
            display: none !important;
        }
        
        /* تصغير شارة السعر */
        .absolute.top-4.right-4 {
            top: 8px !important;
            right: 8px !important;
            padding: 4px 8px !important;
            font-size: 12px !important;
        }
        
        /* تصغير الأيقونات */
        .text-4xl {
            font-size: 24px !important;
        }
        
        /* إخفاء النصوص الطويلة */
        .text-xs {
            font-size: 9px !important;
        }
    }
    
    /* ضمان ظهور الوصف كاملاً في الأيباد والابتوب */
    @media (min-width: 769px) {
        .text-gray-600.text-sm {
            display: block !important;
            overflow: visible !important;
            -webkit-line-clamp: unset !important;
        }
    }
</style>

<!-- Hero Section -->
<section class="gradient-bg py-16">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">{{ config('brand.name') }} الرياض - رقم 1 في غسيل وتجميل القطط والكلاب</h2>
        <p class="text-lg">احجز الآن - خدمة منزلية فورية ومضمونة</p>
    </div>
</section>

<!-- Products Section -->
<section class="py-20">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($products as $product)
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden card-hover transition-all duration-300" data-product-id="{{ $product->id }}">
                <!-- Product Image -->
                <div class="relative h-80 bg-gradient-to-br from-pink-100 to-purple-100">
                    @if($product->image_gallery && count($product->image_gallery) > 0)
                        <img src="{{ getProductImageUrl($product->image_gallery[0]) }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-full object-cover">
                    @else
                        <div class="flex items-center justify-center h-full">
                            <i class="fas fa-paw text-4xl text-pink-400"></i>
                        </div>
                    @endif
                    
                    <!-- Price Badge -->
                    <div class="absolute top-4 right-4 bg-pink-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                        {{ number_format($product->price, 2) }} <span class="icon-saudi_riyal"></span>
                    </div>
                </div>

                <!-- Product Content -->
                <div class="p-6">
                    <h4 class="text-xl font-bold text-gray-800 mb-2">{{ $product->name }}</h4>
                    
                    <!-- Add-ons Count -->
                    @if($product->hasAddOns())
                    <div class="flex items-center gap-2 mb-4">
                        <span class="bg-purple-100 text-purple-600 px-2 py-1 rounded-full text-xs font-medium">
                            <i class="fas fa-plus-circle mr-1"></i>
                            {{ $product->add_ons_count }} إضافة متاحة
                        </span>
                    </div>
                    @endif

                    <!-- Book Button -->
                    <form action="{{ route('frontend.booking.start') }}" method="POST" class="w-full mb-2">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit" class="w-full bg-gradient-to-r from-pink-600 to-purple-600 text-white py-3 rounded-lg font-semibold hover:from-pink-700 hover:to-purple-700 transition-all duration-300 flex items-center justify-center gap-2">
                            <i class="fas fa-calendar-plus"></i>
                            احجز الآن
                        </button>
                    </form>
                    
                    <!-- Add to Cart Button -->
                    <button type="button" 
                            onclick="openAddToCartModal({{ $product->id }}, '{{ $product->name }}')" 
                            class="w-full bg-gradient-to-r from-pink-600 to-purple-600 text-white py-3 rounded-lg font-semibold hover:from-pink-700 hover:to-purple-700 transition-all duration-300 flex items-center justify-center gap-2">
                        <i class="fas fa-shopping-cart"></i>
                        أضف للسلة
                    </button>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-16">
                <div class="text-gray-400 mb-4">
                    <i class="fas fa-box-open text-6xl"></i>
                </div>
                <h4 class="text-xl text-gray-600 mb-2">لا توجد منتجات متاحة حالياً</h4>
                <p class="text-gray-500">يرجى المحاولة مرة أخرى لاحقاً</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $products->links() }}
        </div>
        @endif
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
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent @if(session('customer_id')) bg-gray-100 cursor-not-allowed @endif"
                            placeholder="أدخل اسمك"
                            @if(session('customer_id')) disabled @endif>
                    <div id="nameError" class="text-red-500 text-sm mt-1 hidden"></div>
                </div>
                
                <div>
                    <label for="customerPhone" class="block text-sm font-medium text-gray-700 mb-1">رقم الجوال *</label>
                    <input type="tel" id="customerPhone" name="customer_phone" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent @if(session('customer_id')) bg-gray-100 cursor-not-allowed @endif"
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

<script>
    // Flash messages
    @if(session('success'))
        alert('{{ session('success') }}');
    @endif

    @if(session('error'))
        alert('{{ session('error') }}');
    @endif

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
                if (productCard) {
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
                }
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
                if (productCard) {
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
                }
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
</script>
@endsection
