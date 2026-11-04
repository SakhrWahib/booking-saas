<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    @yield('head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Primary Meta Tags -->
    <title>{{ config('brand.name') }} - خدمات العناية بالحيوانات الأليفة في الرياض  غسيل وتجميل القطط والكلاب</title>
    <meta name="title" content="{{ config('brand.name') }} - خدمات العناية بالحيوانات الأليفة في الرياض | غسيل وتجميل القطط والكلاب">
    <meta name="description" content="{{ config('brand.name') }} - أفضل خدمات العناية بالحيوانات الأليفة في الرياض. غسيل، تجميل، قص أظافر، تنظيف أذنين للقطط والكلاب. خدمة منزلية مضمونة بأسعار مناسبة. احجز الآن {{ config('brand.phone') }}">
    <meta name="keywords" content="{{ config('brand.name') }}, خدمات حيوانات أليفة, غسيل قطط, تجميل كلاب, خدمة منزلية, الرياض, العناية بالحيوانات, قص أظافر, تنظيف أذنين, حجز مواعيد">
    <meta name="author" content="{{ config('brand.name') }}">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="language" content="Arabic">
    <meta name="geo.region" content="SA">
    <meta name="geo.placename" content="الرياض">
    <meta name="geo.position" content="24.7136;46.6753">
    <meta name="ICBM" content="24.7136, 46.6753">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ config('brand.name') }} - خدمات العناية بالحيوانات الأليفة في الرياض">
    <meta property="og:description" content="أفضل خدمات العناية بالحيوانات الأليفة في الرياض. غسيل، تجميل، قص أظافر، تنظيف أذنين للقطط والكلاب. خدمة منزلية مضمونة.">
    <meta property="og:image" content="{{ url('/logo.php') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale" content="ar_SA">
    <meta property="og:site_name" content="{{ config('brand.name') }}">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ config('brand.name') }} - خدمات العناية بالحيوانات الأليفة في الرياض">
    <meta property="twitter:description" content="أفضل خدمات العناية بالحيوانات الأليفة في الرياض. غسيل، تجميل، قص أظافر، تنظيف أذنين للقطط والكلاب.">
    <meta property="twitter:image" content="{{ url('/logo.php') }}">
    
    <!-- Additional SEO Meta Tags -->
    <meta name="theme-color" content="#ec4899">
    <meta name="msapplication-TileColor" content="#ec4899">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="{{ config('brand.name') }}">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ url('/logo.php') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ url('/logo.php') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ url('/logo.php') }}">
    
        <!-- Resource Hints for Performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- تحسينات الأداء للصورة -->
    <link rel="preload" href="/banar.php" as="image" type="image/webp">
    <link rel="preload" href="/banar.php" as="image" type="image/png">
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="//cdn.jsdelivr.net">
    
    <!-- تحسينات الأداء الفائق -->
    <link rel="preload" href="/banar.php" as="image" type="image/png">
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="//cdn.jsdelivr.net">
    
    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=GA_MEASUREMENT_ID"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'GA_MEASUREMENT_ID', {
            'page_title': '{{ config('brand.name') }} - خدمات العناية بالحيوانات الأليفة في الرياض',
            'page_location': '{{ url()->current() }}',
            'custom_map': {
                'dimension1': 'service_type',
                'dimension2': 'location'
            }
        });
    </script>
    
    <!-- تحسينات الأداء للصورة - بدون التأثير على الجودة -->
    <script>
        // تحميل الصورة مسبقاً للأداء الفائق
        const preloadBanner = () => {
            const img = new Image();
            img.onload = function() {
                // الصورة محملة بنجاح
                console.log('Banner preloaded successfully');
            };
            img.src = '/banar.php';
        };
        
        // تحميل فوري
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', preloadBanner);
        } else {
            preloadBanner();
        }
    </script>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Saudi Riyal Font -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@emran-alhaddad/saudi-riyal-font/index.css">
    
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    
    <!-- Custom CSS -->
    @php
        $cssPath = public_path('css/frontend-styles.css');
        $cssExists = file_exists($cssPath);
        $cssUrl = $cssExists ? '/css/frontend-styles.css' : asset('css/frontend-styles.css');
        
        // Debug info
        if (!$cssExists) {
            error_log('CSS file not found at: ' . $cssPath);
        } else {
            error_log('CSS file found at: ' . $cssPath . ' - Size: ' . filesize($cssPath) . ' bytes');
        }
    @endphp
    <link rel="stylesheet" href="{{ $cssUrl }}?v={{ time() }}">
    <!-- Alternative CSS Path -->
    <link rel="stylesheet" href="css/frontend-styles.css?v={{ time() }}">
    
    <!-- Complete CSS Styles -->
    <style>
        /* ===== FRONTEND STYLES ===== */



        /* Tailwind CSS Fallback - Basic Styles */
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', sans-serif;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* Basic Utility Classes */
        .flex { display: flex; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .justify-center { justify-content: center; }
        .text-center { text-align: center; }
        .text-white { color: white; }
        .bg-white { background-color: white; }
        .bg-gray-50 { background-color: #f9fafb; }
        .bg-gray-800 { background-color: #1f2937; }
        .hidden { display: none; }
        .block { display: block; }
        .relative { position: relative; }
        .absolute { position: absolute; }
        .fixed { position: fixed; }
        .top-0 { top: 0; }
        .left-0 { left: 0; }
        .right-0 { right: 0; }
        .z-50 { z-index: 50; }
        .shadow-lg { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
        .rounded-lg { border-radius: 0.5rem; }
        .p-4 { padding: 1rem; }
        .px-4 { padding-left: 1rem; padding-right: 1rem; }
        .py-12 { padding-top: 3rem; padding-bottom: 3rem; }
        .mb-8 { margin-bottom: 2rem; }
        .gap-4 { gap: 1rem; }
        .gap-8 { gap: 2rem; }
        .w-full { width: 100%; }
        .h-16 { height: 4rem; }
        .text-lg { font-size: 1.125rem; }
        .text-xl { font-size: 1.25rem; }
        .font-bold { font-weight: 700; }
        .font-semibold { font-weight: 600; }
        .transition-colors { transition: color 0.15s ease-in-out; }
        .hover\:text-pink-200:hover { color: #fbcfe8; }
        .hover\:bg-white\/30:hover { background-color: rgba(255, 255, 255, 0.3); }

        /* Fix Horizontal Scrollbar */
        html, body {
            overflow-x: hidden;
            max-width: 100%;
        }

        /* تحسينات النافيجيشن الثابت */
        header {
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        /* تحسين النافيجيشن عند التمرير */
        header.scrolled {
            background: rgba(236, 72, 153, 0.95);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        /* تحسين النافيجيشن للجوال */
        @media (max-width: 768px) {
            header {
                backdrop-filter: blur(15px);
                -webkit-backdrop-filter: blur(15px);
            }
        }

        /* Typewriter Effect Styles */
        .typewriter-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 60px;
            margin-bottom: 1rem;
        }

        .typewriter-text {
            position: relative;
            display: inline-block;
            color: #fce7f3;
            font-weight: 600;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
            opacity: 0;
            animation: fadeInTypewriter 0.8s ease-in-out 0.5s forwards;
            letter-spacing: 0.5px;
            line-height: 1.6;
        }

        .typewriter-cursor {
            display: inline-block;
            width: 3px;
            height: 1.3em;
            background: linear-gradient(45deg, #ec4899, #a855f7);
            margin-left: 3px;
            animation: blink 1.2s infinite;
            vertical-align: middle;
            border-radius: 2px;
            box-shadow: 0 0 10px rgba(236, 72, 153, 0.6);
        }

        @keyframes fadeInTypewriter {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes blink {
            0%, 50% {
                opacity: 1;
                transform: scaleY(1);
            }
            51%, 100% {
                opacity: 0.3;
                transform: scaleY(0.8);
            }
        }

        /* Typewriter text animation */
        .typewriter-text.typing {
            animation: typewriter 3s steps(40, end) 1s forwards;
        }

        @keyframes typewriter {
            from {
                width: 0;
            }
            to {
                width: 100%;
            }
        }

        /* Responsive Typewriter */
        @media (max-width: 768px) {
            .typewriter-container {
                min-height: 50px;
            }
            
            .typewriter-text {
                font-size: 1rem !important;
                line-height: 1.4;
                letter-spacing: 0.3px;
            }
            
            .typewriter-cursor {
                width: 2px;
                height: 1.2em;
            }
        }

        @media (max-width: 480px) {
            .typewriter-container {
                min-height: 40px;
            }
            
            .typewriter-text {
                font-size: 0.9rem !important;
                line-height: 1.3;
                letter-spacing: 0.2px;
            }
            
            .typewriter-cursor {
                width: 2px;
                height: 1.1em;
                margin-left: 2px;
            }
        }

        /* Animation for typewriter text scaling */
        .typewriter-text {
            transition: transform 0.1s ease-in-out;
        }

        /* Welcome Message Animation */
        .welcome-message-container {
            overflow: hidden;
            position: relative;
            margin-bottom: 2rem;
            max-width: 100%;
            width: 100%;
        }

        .welcome-text {
            background: linear-gradient(45deg, #ffffff, #fbbf24, #ffffff, #fbbf24);
            background-size: 300% 300%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: welcomeGradient 3s ease-in-out infinite, welcomeSlide 4s ease-in-out infinite;
            text-shadow: 0 0 30px rgba(255, 255, 255, 0.3);
            position: relative;
            display: inline-block;
            max-width: 100%;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .welcome-text::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            animation: welcomeShine 2s ease-in-out infinite;
        }

        @keyframes welcomeGradient {
            0%, 100% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
        }

        @keyframes welcomeSlide {
            0% {
                transform: translateX(-100%);
                opacity: 0;
            }
            20% {
                transform: translateX(0);
                opacity: 1;
            }
            80% {
                transform: translateX(0);
                opacity: 1;
            }
            100% {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        @keyframes welcomeShine {
            0% {
                left: -100%;
            }
            50% {
                left: 100%;
            }
            100% {
                left: 100%;
            }
        }

        /* Hero Section with Banner Background */
        .hero-section {
            background-image: url('/banar.php');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        
        /* Ensure banner image is fully visible on all devices */
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('/banar.php');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: -2;
        }
        
        /* Optimize banner display for different screen orientations */
        @media (orientation: portrait) {
            .hero-section {
                background-size: contain;
                background-position: center top;
            }
            
            .hero-section::before {
                background-size: contain;
                background-position: center top;
            }
        }
        
        @media (orientation: landscape) and (max-height: 500px) {
            .hero-section {
                background-size: cover;
                background-position: center;
            }
            
            .hero-section::before {
                background-size: cover;
                background-position: center;
            }
        }
        
        /* Responsive Banner Background */
        @media (max-width: 768px) {
            .hero-section {
                background-size: contain;
                background-position: center top;
                min-height: 70vh;
                position: relative;
            }
            
            .hero-section::after {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: linear-gradient(135deg, rgba(139, 92, 246, 0.7) 0%, rgba(236, 72, 153, 0.7) 50%, rgba(168, 85, 247, 0.7) 100%);
                z-index: -1;
            }
        }
        
        @media (max-width: 480px) {
            .hero-section {
                background-size: contain;
                background-position: center top;
                min-height: 60vh;
                position: relative;
            }
            
            .hero-section::after {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: linear-gradient(135deg, rgba(139, 92, 246, 0.7) 0%, rgba(236, 72, 153, 0.7) 50%, rgba(168, 85, 247, 0.7) 100%);
                z-index: -1;
            }
            
            /* Ensure full image visibility on small screens */
            .hero-section::before {
                background-size: contain;
                background-position: center top;
            }
        }
        
        /* Extra small devices */
        @media (max-width: 360px) {
            .hero-section {
                background-size: contain;
                background-position: center top;
                min-height: 50vh;
            }
            
            .hero-section::before {
                background-size: contain;
                background-position: center top;
            }
        }
        
        /* Responsive Welcome Text */
        @media (max-width: 768px) {
            .welcome-text {
                font-size: 1.2rem !important;
                line-height: 1.4;
            }
            
            
            
            /* Ensure no horizontal scroll on mobile */
            .welcome-message-container {
                padding: 0 10px;
            }
            
            .welcome-text {
                padding: 0 5px;
            }
        }

        @media (max-width: 480px) {
            .welcome-text {
                font-size: 1rem !important;
                line-height: 1.3;
            }
            
            
            
            /* Extra padding for very small screens */
            .welcome-message-container {
                padding: 0 15px;
            }
            
            .welcome-text {
                padding: 0 10px;
                font-size: 0.9rem !important;
            }
        }

        /* Custom Customers Title - Smaller on Mobile */
        .custom-customers-title {
            font-size: 2rem !important;
            line-height: 1.2;
        }

        @media (max-width: 768px) {
            .custom-customers-title {
                font-size: 1.5rem !important;
                line-height: 1.3;
            }
        }

        @media (max-width: 480px) {
            .custom-customers-title {
                font-size: 1.25rem !important;
                line-height: 1.4;
            }
        }

        /* Font Family */
        body { 
            font-family: 'Cairo', sans-serif; 
        }

        /* Mobile Navigation Styles */
        #mobile-menu {
            transition: all 0.3s ease-in-out;
            transform-origin: top;
            animation: slideDown 0.3s ease-out;
        }

        #mobile-menu.hidden {
            display: none !important;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        #mobile-menu-btn {
            transition: all 0.3s ease;
        }

        #mobile-menu-btn:hover {
            transform: scale(1.05);
        }

        #mobile-menu nav a {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        #mobile-menu nav a::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s ease;
        }

        #mobile-menu nav a:hover::before {
            left: 100%;
        }

        #mobile-menu nav a:hover {
            transform: translateX(5px);
        }



        /* Action Buttons Styles */
        .action-buttons-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
            margin-top: 2rem;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            min-width: 140px;
            justify-content: center;
        }

        .action-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.5s ease;
        }

        .action-btn:hover::before {
            left: 100%;
        }

        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
            border-color: rgba(255, 255, 255, 0.4);
        }

        .action-btn:active {
            transform: translateY(-1px);
        }

        /* Phone Button */
        .phone-btn {
            color: #ec4899;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.9));
        }

        .phone-btn:hover {
            background: linear-gradient(135deg, rgba(255, 255, 255, 1), rgba(255, 255, 255, 0.95));
            color: #be185d;
        }

        /* WhatsApp Button */
        .whatsapp-btn {
            color: #25d366;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.9));
        }

        .whatsapp-btn:hover {
            background: linear-gradient(135deg, rgba(255, 255, 255, 1), rgba(255, 255, 255, 0.95));
            color: #128c7e;
        }

        /* Store Button */
        .store-btn {
            color: #a855f7;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.9));
        }

        .store-btn:hover {
            background: linear-gradient(135deg, rgba(255, 255, 255, 1), rgba(255, 255, 255, 0.95));
            color: #7c3aed;
        }

        .action-btn i {
            font-size: 1.1rem;
            transition: transform 0.3s ease;
        }

        .action-btn:hover i {
            transform: scale(1.1);
        }

        .btn-text {
            font-weight: 600;
            white-space: nowrap;
        }

        /* Responsive Action Buttons */
        @media (max-width: 768px) {
            .action-buttons-container {
                flex-direction: row;
                gap: 0.5rem;
                flex-wrap: wrap;
                justify-content: center;
                width: 100%;
                padding: 0 1rem;
            }
            
            .action-btn {
                flex: 1;
                min-width: 100px;
                padding: 0.75rem 0.75rem;
                font-size: 0.8rem;
            }
            
            .btn-text {
                font-size: 0.75rem;
            }
        }

        @media (max-width: 480px) {
            .action-buttons-container {
                gap: 0.4rem;
                padding: 0 0.5rem;
            }
            
            .action-btn {
                padding: 0.6rem 0.5rem;
                font-size: 0.7rem;
                min-width: 80px;
            }
            
            .btn-text {
                font-size: 0.65rem;
            }
            
            .action-btn i {
                font-size: 0.9rem;
            }
        }

        /* Services Title Styling - Same as "Why Choose {{ config('brand.name_en') }}" */
        .services-title {
            background: linear-gradient(45deg, #ec4899, #a855f7, #ec4899);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: servicesTitleGradient 3s ease-in-out infinite;
            text-shadow: 0 0 30px rgba(236, 72, 153, 0.3);
            position: relative;
            display: inline-block;
            text-align: center;
            width: 100%;
        }

        .services-title::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(236, 72, 153, 0.2), transparent);
            animation: servicesTitleShine 4s ease-in-out infinite;
        }

        @keyframes servicesTitleGradient {
            0%, 100% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
        }

        @keyframes servicesTitleShine {
            0% {
                opacity: 0;
            }
            50% {
                opacity: 1;
            }
            100% {
                opacity: 0;
            }
        }

        /* Card Hover Effects */
        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        /* Swiper Custom Styles */
        .swiper {
            width: 100%;
            height: 100%;
            padding: 20px 0;
        }

        .swiper-slide {
            text-align: center;
            font-size: 18px;
            background: transparent;
            display: flex;
            justify-content: center;
            align-items: stretch;
        }

        .swiper-slide .bg-white {
            width: 100%;
            height: 100%;
        }

        /* Swiper Navigation */
        .swiper-button-next,
        .swiper-button-prev {
            color: #ec4899;
            background: rgba(255, 255, 255, 0.9);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .swiper-button-next:after,
        .swiper-button-prev:after {
            font-size: 18px;
            font-weight: bold;
        }

        /* Swiper Pagination */
        .swiper-pagination-bullet {
            background: #ec4899;
            opacity: 0.5;
        }

        .swiper-pagination-bullet-active {
            opacity: 1;
            background: #ec4899;
        }

        /* Responsive Swiper */
        @media (max-width: 768px) {
            .swiper {
                padding: 10px 0;
            }
            
            .swiper-button-next,
            .swiper-button-prev {
                width: 35px;
                height: 35px;
            }
            
            .swiper-button-next:after,
            .swiper-button-prev:after {
                font-size: 16px;
            }
        }

        @media (max-width: 480px) {
            .swiper-button-next,
            .swiper-button-prev {
                width: 30px;
                height: 30px;
            }
            
            .swiper-button-next:after,
            .swiper-button-prev:after {
                font-size: 14px;
            }
        }

        /* تحسين مظهر الصور */
        .relative img {
            object-fit: cover !important;
            object-position: center !important;
            background: linear-gradient(135deg, #fdf2f8 0%, #f3e8ff 100%) !important;
            transition: transform 0.3s ease !important;
        }

        .relative img:hover {
            transform: scale(1.05) !important;
        }

        /* ضمان ظهور الصور كاملة في الجوال */
        @media (max-width: 768px) {
            .relative img {
                object-fit: cover !important;
                object-position: center !important;
                width: 100% !important;
                height: 100% !important;
            }
        }

        /* تكبير الصور في وضعية اللابتوب فقط - تطبيق نفس تنسيق products.blade.php */
        @media (min-width: 769px) {
            /* تكبير الصور في قسم "خدماتنا المميزة" */
            .swiper-slide .h-48.md\:h-80 {
                height: 320px !important; /* نفس h-80 من products.blade.php */
            }
            
            .swiper-slide .h-48.md\:h-80 img {
                object-fit: cover !important;
                object-position: center !important;
                width: 100% !important;
                height: 100% !important;
                background: linear-gradient(135deg, #fdf2f8 0%, #f3e8ff 100%) !important;
            }
            
            /* تحسين مظهر البطاقات في اللابتوب */
            .swiper-slide .bg-white {
                border-radius: 16px !important;
                box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
                border: 1px solid rgba(236, 72, 153, 0.1) !important;
            }
            
            /* تحسين مظهر النصوص في اللابتوب */
            .swiper-slide .text-xs.md\:text-xl {
                font-size: 1.25rem !important;
                line-height: 1.4 !important;
            }
            
            .swiper-slide .text-xs.md\:text-sm {
                font-size: 0.875rem !important;
                line-height: 1.5 !important;
            }
            
            /* تحسين مظهر الأزرار في اللابتوب */
            .swiper-slide button[type="submit"],
            .swiper-slide button[onclick*="openAddToCartModal"] {
                padding: 0.75rem 1rem !important;
                font-size: 0.875rem !important;
                min-height: 44px !important;
                border-radius: 8px !important;
            }
        }

        /* تخطيط الخدمات الست */
        @media (min-width: 1024px) {
            .grid-cols-1.md\:grid-cols-3 {
                grid-template-columns: repeat(3, 1fr) !important;
            }
        }

        @media (max-width: 1023px) and (min-width: 768px) {
            .grid-cols-1.md\:grid-cols-3 {
                grid-template-columns: repeat(2, 1fr) !important;
            }
        }

        /* تصميم احترافي للبطاقات */
        .service-card {
            background: linear-gradient(145deg, #ffffff, #f8fafc);
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: 
                0 15px 30px rgba(236, 72, 153, 0.08),
                0 5px 15px rgba(0, 0, 0, 0.05),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(236, 72, 153, 0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
            transform-style: preserve-3d;
            perspective: 1000px;
            height: 200px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
        }

        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, 
                #ec4899 0%, 
                #a855f7 33%, 
                #f59e0b 66%, 
                #ec4899 100%
            );
            background-size: 300% 100%;
            animation: gradientMove 4s ease-in-out infinite;
            border-radius: 20px 20px 0 0;
            transform: scaleX(1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 
                0 0 10px rgba(236, 72, 153, 0.3),
                0 0 20px rgba(168, 85, 247, 0.2),
                0 0 30px rgba(245, 158, 11, 0.1);
        }

        @keyframes gradientMove {
            0% {
                background-position: 0% 50%;
                box-shadow: 
                    0 0 10px rgba(236, 72, 153, 0.3),
                    0 0 20px rgba(168, 85, 247, 0.2),
                    0 0 30px rgba(245, 158, 11, 0.1);
            }
            25% {
                background-position: 50% 50%;
                box-shadow: 
                    0 0 15px rgba(168, 85, 247, 0.4),
                    0 0 25px rgba(245, 158, 11, 0.3),
                    0 0 35px rgba(236, 72, 153, 0.2);
            }
            50% {
                background-position: 100% 50%;
                box-shadow: 
                    0 0 20px rgba(245, 158, 11, 0.5),
                    0 0 30px rgba(236, 72, 153, 0.4),
                    0 0 40px rgba(168, 85, 247, 0.3);
            }
            75% {
                background-position: 50% 50%;
                box-shadow: 
                    0 0 15px rgba(236, 72, 153, 0.4),
                    0 0 25px rgba(168, 85, 247, 0.3),
                    0 0 35px rgba(245, 158, 11, 0.2);
            }
            100% {
                background-position: 0% 50%;
                box-shadow: 
                    0 0 10px rgba(236, 72, 153, 0.3),
                    0 0 20px rgba(168, 85, 247, 0.2),
                    0 0 30px rgba(245, 158, 11, 0.1);
            }
        }

        .service-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                linear-gradient(135deg, rgba(236, 72, 153, 0.02), rgba(168, 85, 247, 0.02)),
                radial-gradient(circle at 20% 80%, rgba(236, 72, 153, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(168, 85, 247, 0.03) 0%, transparent 50%);
            opacity: 0;
            transition: opacity 0.4s ease;
            pointer-events: none;
            animation: backgroundPulse 6s ease-in-out infinite;
        }

        /* تأثير زجاجي متقدم */
        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, 
                #ec4899 0%, 
                #a855f7 33%, 
                #f59e0b 66%, 
                #ec4899 100%
            );
            background-size: 300% 100%;
            animation: gradientMove 4s ease-in-out infinite;
            border-radius: 20px 20px 0 0;
            transform: scaleX(1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 
                0 0 10px rgba(236, 72, 153, 0.3),
                0 0 20px rgba(168, 85, 247, 0.2),
                0 0 30px rgba(245, 158, 11, 0.1);
        }

        /* إضافة تأثير زجاجي للبطاقة */
        .service-card {
            background: 
                linear-gradient(145deg, rgba(255, 255, 255, 0.9), rgba(248, 250, 252, 0.8)),
                linear-gradient(135deg, rgba(236, 72, 153, 0.05), rgba(168, 85, 247, 0.05));
            backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 
                0 15px 30px rgba(236, 72, 153, 0.08),
                0 5px 15px rgba(0, 0, 0, 0.05),
                inset 0 1px 0 rgba(255, 255, 255, 0.8),
                0 0 0 1px rgba(255, 255, 255, 0.1);
        }

        @keyframes backgroundPulse {
            0%, 100% {
                opacity: 0;
            }
            50% {
                opacity: 1;
            }
        }

        .service-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 
                0 20px 40px rgba(236, 72, 153, 0.12),
                0 10px 20px rgba(0, 0, 0, 0.08),
                0 0 30px rgba(168, 85, 247, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.9);
            border-color: rgba(236, 72, 153, 0.2);
            backdrop-filter: blur(15px);
        }

        .service-card:hover::before {
            animation-duration: 1.2s;
            height: 6px;
            box-shadow: 
                0 0 20px rgba(236, 72, 153, 0.6),
                0 0 30px rgba(168, 85, 247, 0.4),
                0 0 40px rgba(245, 158, 11, 0.3);
            filter: brightness(1.2) contrast(1.1);
        }

        .service-card:hover::after {
            opacity: 1;
        }

        .service-card:hover .icon-container {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 
                0 10px 25px rgba(236, 72, 153, 0.2),
                0 3px 10px rgba(0, 0, 0, 0.1);
        }

        .service-card:hover .service-title {
            background: linear-gradient(135deg, #ec4899, #a855f7, #f59e0b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            transform: translateY(-1px);
            text-shadow: 0 2px 4px rgba(236, 72, 153, 0.2);
        }

        .service-card:hover .service-description {
            color: #4b5563;
            transform: translateY(-2px);
        }

        .service-card:hover .service-description::before {
            width: 60%;
        }

        .icon-container {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #fdf2f8, #fce7f3, #fdf2f8);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            transition: all 0.4s ease;
            border: 3px solid #fce7f3;
            position: relative;
            box-shadow: 
                0 8px 20px rgba(236, 72, 153, 0.12),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            transform-style: preserve-3d;
        }

        .icon-container::before {
            content: '';
            position: absolute;
            inset: -2px;
            border-radius: 50%;
            background: linear-gradient(45deg, #ec4899, #a855f7, #f59e0b, #ec4899);
            background-size: 400% 400%;
            animation: iconGlow 3s ease-in-out infinite;
            opacity: 0;
            transition: opacity 0.4s ease;
            z-index: -1;
            filter: blur(3px);
        }

        @keyframes iconGlow {
            0%, 100% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
        }

        .icon-container::after {
            content: '';
            position: absolute;
            inset: -3px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ec4899, #a855f7, #f59e0b);
            opacity: 0;
            transition: opacity 0.4s ease;
            z-index: -1;
            filter: blur(1px);
        }

        .service-card:hover .icon-container::before {
            opacity: 1;
            animation-duration: 1.5s;
        }

        .service-card:hover .icon-container::after {
            opacity: 1;
        }

        .service-icon {
            font-size: 2.2rem;
            color: #ec4899;
            transition: all 0.4s ease;
            filter: drop-shadow(0 1px 3px rgba(0, 0, 0, 0.1));
        }

        .service-card:hover .service-icon {
            color: white;
            transform: scale(1.1);
            filter: drop-shadow(0 2px 6px rgba(0, 0, 0, 0.15));
        }

        .service-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            position: relative;
            background: linear-gradient(135deg, #1f2937, #374151);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-align: center;
        }

        .service-description {
            color: #6b7280;
            line-height: 1.4;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            font-weight: 400;
            position: relative;
            padding: 0.3rem 0;
            text-align: center;
            margin-top: auto;
        }
        }

        .service-description::before {
            content: '';
            position: absolute;
            left: 50%;
            bottom: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #ec4899, #a855f7);
            transition: all 0.3s ease;
            transform: translateX(-50%);
            border-radius: 1px;
        }

        /* تحسين التخطيط */
        .services-grid {
            padding: 2.5rem 0;
            position: relative;
        }

        /* عنصر احترافي تحت البطاقات */
        .services-section {
            position: relative;
            background: linear-gradient(135deg, #fdf2f8 0%, #f3e8ff 50%, #fef3c7 100%);
            padding: 3rem 0;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .services-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 80%, rgba(236, 72, 153, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(168, 85, 247, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(245, 158, 11, 0.04) 0%, transparent 50%);
            pointer-events: none;
            animation: sectionGlow 8s ease-in-out infinite;
        }

        @keyframes sectionGlow {
            0%, 100% {
                opacity: 0.5;
            }
            50% {
                opacity: 1;
            }
        }

        .services-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 80%, rgba(236, 72, 153, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(168, 85, 247, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(245, 158, 11, 0.04) 0%, transparent 50%);
            pointer-events: none;
        }

        .services-section::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 80px;
            background: linear-gradient(to top, rgba(255, 255, 255, 0.8), transparent);
            pointer-events: none;
        }

        .services-container {
            position: relative;
            z-index: 1;
        }

        .services-title {
            font-size: 2.5rem;
            font-weight: 800;
            text-align: center;
            margin-bottom: 0.8rem;
            background: linear-gradient(135deg, #ec4899, #a855f7, #f59e0b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
            position: relative;
            animation: titleGlow 4s ease-in-out infinite;
        }

        .services-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: linear-gradient(90deg, #ec4899, #a855f7, #f59e0b);
            border-radius: 2px;
            animation: titleUnderline 3s ease-in-out infinite;
        }

        @keyframes titleGlow {
            0%, 100% {
                filter: drop-shadow(0 0 5px rgba(236, 72, 153, 0.3));
            }
            50% {
                filter: drop-shadow(0 0 15px rgba(236, 72, 153, 0.6));
            }
        }

        @keyframes titleUnderline {
            0%, 100% {
                width: 100px;
                opacity: 0.7;
            }
            50% {
                width: 150px;
                opacity: 1;
            }
        }

        .services-subtitle {
            text-align: center;
            color: #6b7280;
            font-size: 1rem;
            margin-bottom: 2.5rem;
            font-weight: 500;
        }

        /* SEO Content Section Styling */
        .seo-content-section {
            position: relative;
            background: linear-gradient(135deg, #fdf2f8 0%, #f3e8ff 50%, #fef3c7 100%);
            padding: 4rem 0;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .seo-content-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 80%, rgba(236, 72, 153, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(168, 85, 247, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(245, 158, 11, 0.04) 0%, transparent 50%);
            animation: seoContentBackground 8s ease-in-out infinite;
        }

        @keyframes seoContentBackground {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }

        .seo-content-section::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 80px;
            background: linear-gradient(to top, rgba(255, 255, 255, 0.8), transparent);
            pointer-events: none;
        }

        .seo-main-title {
            font-size: 2.5rem;
            font-weight: 800;
            text-align: center;
            margin-bottom: 2rem;
            background: linear-gradient(135deg, #ec4899, #a855f7, #f59e0b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
            position: relative;
            display: inline-block;
        }

        .seo-main-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: linear-gradient(90deg, #ec4899, #a855f7, #f59e0b);
            border-radius: 2px;
            animation: titleUnderline 3s ease-in-out infinite;
        }

        .seo-service-card {
            background: linear-gradient(145deg, #ffffff, #f8fafc);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 
                0 15px 30px rgba(236, 72, 153, 0.08),
                0 5px 15px rgba(0, 0, 0, 0.05),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(236, 72, 153, 0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            height: 100%;
        }

        .seo-service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, 
                #ec4899 0%, 
                #a855f7 33%, 
                #f59e0b 66%, 
                #ec4899 100%);
            border-radius: 20px 20px 0 0;
            animation: seoCardGradient 3s ease-in-out infinite;
        }

        @keyframes seoCardGradient {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .seo-service-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 
                0 20px 40px rgba(236, 72, 153, 0.12),
                0 10px 20px rgba(0, 0, 0, 0.08),
                0 0 30px rgba(168, 85, 247, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.9);
            border-color: rgba(236, 72, 153, 0.2);
        }

        .seo-service-card:hover::before {
            animation-duration: 1.2s;
            height: 6px;
            box-shadow: 
                0 0 20px rgba(236, 72, 153, 0.6),
                0 0 30px rgba(168, 85, 247, 0.4),
                0 0 40px rgba(245, 158, 11, 0.3);
        }

        .seo-service-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #ec4899;
            margin-bottom: 1rem;
            text-align: center;
            position: relative;
            transition: all 0.3s ease;
        }

        .seo-service-card:hover .seo-service-title {
            background: linear-gradient(135deg, #ec4899, #a855f7, #f59e0b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            transform: translateY(-2px);
        }

        .seo-service-description {
            color: #6b7280;
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
        }

        .seo-service-card:hover .seo-service-description {
            color: #4b5563;
            transform: translateY(-2px);
        }

        .seo-service-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .seo-service-list li {
            color: #6b7280;
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
            padding: 0.5rem 0;
            position: relative;
            padding-right: 1.5rem;
            transition: all 0.3s ease;
        }

        .seo-service-list li::before {
            content: '🐾';
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1rem;
            opacity: 0.7;
            transition: all 0.3s ease;
        }

        .seo-service-card:hover .seo-service-list li {
            color: #4b5563;
            transform: translateX(-5px);
        }

        .seo-service-card:hover .seo-service-list li::before {
            opacity: 1;
            transform: translateY(-50%) scale(1.2);
        }

        .seo-why-choose {
            background: linear-gradient(145deg, #fdf2f8, #fce7f3);
            border-radius: 20px;
            padding: 2.5rem;
            margin-top: 3rem;
            border: 1px solid rgba(236, 72, 153, 0.1);
            box-shadow: 
                0 10px 25px rgba(236, 72, 153, 0.08),
                0 3px 10px rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
        }

        .seo-why-choose::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #ec4899, #a855f7, #f59e0b);
            border-radius: 20px 20px 0 0;
        }

        .seo-why-choose-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1rem;
            text-align: center;
            background: linear-gradient(135deg, #ec4899, #a855f7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .seo-why-choose-description {
            color: #6b7280;
            font-size: 1.1rem;
            line-height: 1.7;
            text-align: center;
            margin: 0;
        }

        /* تأثيرات إضافية للجوال */
        @media (max-width: 768px) {
            .service-card {
                padding: 1.2rem;
                margin: 0 0.3rem;
                border-radius: 18px;
                height: 180px;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                align-items: center;
            }
            
            .seo-content-section {
                padding: 2rem 0;
            }
            
            .seo-main-title {
                font-size: 1.8rem;
                margin-bottom: 1.5rem;
            }
            
            .seo-service-card {
                padding: 1.5rem;
                margin-bottom: 1rem;
            }
            
            .seo-service-title {
                font-size: 1.3rem;
                margin-bottom: 0.8rem;
            }
            
            .seo-service-description {
                font-size: 0.9rem;
                margin-bottom: 1rem;
            }
            
            .seo-service-list li {
                font-size: 0.85rem;
                margin-bottom: 0.3rem;
            }
            
            .seo-why-choose {
                padding: 1.5rem;
                margin-top: 2rem;
            }
            
            .seo-why-choose-title {
                font-size: 1.4rem;
                margin-bottom: 0.8rem;
            }
            
            .seo-why-choose-description {
                font-size: 1rem;
            }
        }
            
            .icon-container {
                width: 50px;
                height: 50px;
                margin-bottom: 0.8rem;
            }
            
            .service-icon {
                font-size: 1.5rem;
            }
            
            .service-title {
                font-size: 1rem;
                margin-bottom: 0.4rem;
            }
            
            .service-description {
                font-size: 0.85rem;
                line-height: 1.3;
            }
            
            .services-grid {
                gap: 1.5rem;
                padding: 2rem 0;
            }
            
            .services-title {
                font-size: 2rem;
            }
            
            .services-section {
                padding: 2.5rem 0;
            }
        }

        /* ===== CUSTOMER REVIEWS SECTION ===== */
        .reviews-section {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            position: relative;
            overflow: hidden;
        }

        .reviews-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 30% 70%, rgba(236, 72, 153, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 70% 30%, rgba(168, 85, 247, 0.05) 0%, transparent 50%);
            pointer-events: none;
        }

        .reviews-title {
            background: linear-gradient(135deg, #ec4899, #a855f7, #f59e0b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            position: relative;
            animation: titleGlow 4s ease-in-out infinite;
        }

        .reviews-subtitle {
            color: #64748b;
            font-weight: 500;
        }

        /* Review Card Styles */
        .review-card {
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.9), rgba(248, 250, 252, 0.8));
            backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 
                0 15px 30px rgba(236, 72, 153, 0.08),
                0 5px 15px rgba(0, 0, 0, 0.05),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            height: 280px;
            display: flex;
            flex-direction: column;
        }

        .review-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #ec4899, #a855f7, #f59e0b);
            background-size: 200% 100%;
            animation: gradientMove 3s ease-in-out infinite;
            border-radius: 20px 20px 0 0;
        }

        .review-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 
                0 20px 40px rgba(236, 72, 153, 0.12),
                0 10px 20px rgba(0, 0, 0, 0.08),
                0 0 30px rgba(168, 85, 247, 0.1);
        }

        /* Review Header */
        .review-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .reviewer-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ec4899, #a855f7);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            box-shadow: 0 8px 20px rgba(236, 72, 153, 0.2);
            transition: all 0.3s ease;
        }

        .review-card:hover .reviewer-avatar {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 12px 25px rgba(236, 72, 153, 0.3);
        }

        .reviewer-info {
            flex: 1;
        }

        .reviewer-name {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
        }

        .review-card:hover .reviewer-name {
            background: linear-gradient(135deg, #ec4899, #a855f7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .review-stars {
            display: flex;
            gap: 0.25rem;
        }

        .review-stars i {
            color: #fbbf24;
            font-size: 0.9rem;
            animation: starTwinkle 2s ease-in-out infinite;
        }

        .review-stars i:nth-child(1) { animation-delay: 0s; }
        .review-stars i:nth-child(2) { animation-delay: 0.2s; }
        .review-stars i:nth-child(3) { animation-delay: 0.4s; }
        .review-stars i:nth-child(4) { animation-delay: 0.6s; }
        .review-stars i:nth-child(5) { animation-delay: 0.8s; }

        @keyframes starTwinkle {
            0%, 100% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.2);
                opacity: 0.8;
            }
        }

        /* Review Text */
        .review-text {
            color: #4b5563;
            line-height: 1.6;
            font-size: 0.95rem;
            font-weight: 400;
            flex: 1;
            position: relative;
            padding: 1rem 0;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 6;
            -webkit-box-orient: vertical;
        }

        .review-text::before {
            content: '"';
            position: absolute;
            top: 0;
            right: -0.5rem;
            font-size: 3rem;
            color: rgba(236, 72, 153, 0.2);
            font-family: serif;
            line-height: 1;
        }

        /* Reviews Swiper Customization */
        .reviews-swiper {
            padding: 2rem 0;
        }

        .reviews-nav-next,
        .reviews-nav-prev {
            color: #ec4899;
            background: rgba(255, 255, 255, 0.9);
            width: 45px;
            height: 45px;
            border-radius: 50%;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .reviews-nav-next:hover,
        .reviews-nav-prev:hover {
            background: #ec4899;
            color: white;
            transform: scale(1.1);
        }

        .reviews-nav-next:after,
        .reviews-nav-prev:after {
            font-size: 16px;
            font-weight: bold;
        }

        .reviews-pagination .swiper-pagination-bullet {
            background: #ec4899;
            opacity: 0.5;
            transition: all 0.3s ease;
        }

        .reviews-pagination .swiper-pagination-bullet-active {
            opacity: 1;
            background: #ec4899;
            transform: scale(1.2);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .review-card {
                padding: 1.2rem;
                margin: 0 0.3rem;
                height: 250px;
            }
            
            .reviewer-avatar {
                width: 45px;
                height: 45px;
                font-size: 1.1rem;
            }
            
            .reviewer-name {
                font-size: 0.9rem;
            }
            
            .review-text {
                font-size: 0.85rem;
                -webkit-line-clamp: 5;
            }
            
            .reviews-title {
                font-size: 2rem;
            }
            
            .reviews-subtitle {
                font-size: 1rem;
            }
            
            .review-header {
                margin-bottom: 1rem;
            }
            
            .review-stars i {
                font-size: 0.8rem;
            }
        }

        /* Mobile Specific Styles */
        @media (max-width: 768px) {
            .card-hover:hover {
                transform: none;
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            }
            
            .line-clamp-1 {
                display: -webkit-box;
                -webkit-line-clamp: 1;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            .line-clamp-2 {
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
            
            /* تحسين حجم البطاقات في الجوال - بطاقتين */
            .grid-cols-2 > div {
                min-width: 0;
                max-width: none;
            }
            
            /* تكبير الصور في الجوال */
            .grid-cols-2 .h-24 {
                height: 160px !important;
            }
            
            /* تكبير النصوص في الجوال */
            .grid-cols-2 .text-xs {
                font-size: 12px !important;
            }
            
            /* تكبير الأزرار في الجوال */
            .grid-cols-2 button[type="submit"] {
                padding: 8px 10px !important;
                font-size: 12px !important;
                min-height: 36px !important;
            }
            
            /* تكبير المسافات في الجوال */
            .grid-cols-2 .p-2 {
                padding: 8px !important;
            }
            
            .grid-cols-2 .mb-1 {
                margin-bottom: 3px !important;
            }
            
            .grid-cols-2 .mb-2 {
                margin-bottom: 4px !important;
            }
            
            .grid-cols-2 .mb-4 {
                margin-bottom: 6px !important;
            }
            
            /* إظهار الوصف في الجوال */
            .grid-cols-2 .hidden {
                display: block !important;
            }
            
            /* تكبير الأيقونات في الجوال */
            .grid-cols-2 .text-sm {
                font-size: 12px !important;
            }
            
            .grid-cols-2 .text-base {
                font-size: 14px !important;
            }
            
            /* ضمان ظهور الزر في الجوال */
            .grid-cols-2 button[type="submit"] {
                display: flex !important;
                visibility: visible !important;
                opacity: 1 !important;
                width: 100% !important;
                justify-content: center !important;
                align-items: center !important;
                white-space: nowrap !important;
                font-weight: bold !important;
            }
            
            /* تحسين تخطيط البطاقة في الجوال */
            .grid-cols-2 .flex.flex-col {
                height: auto !important;
                min-height: 200px !important;
            }
            
            /* إظهار النصوص الطويلة في الجوال */
            .grid-cols-2 .line-clamp-2 {
                -webkit-line-clamp: 2 !important;
            }
            
            /* تحسين مظهر البطاقات في الجوال */
            .grid-cols-2 .bg-white {
                border-radius: 12px !important;
                box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
                border: 1px solid rgba(236, 72, 153, 0.1) !important;
            }
            
            /* تحسين مظهر الأزرار في الجوال */
            .grid-cols-2 button[type="submit"] {
                border-radius: 8px !important;
                box-shadow: 0 2px 8px rgba(236, 72, 153, 0.3) !important;
                transition: all 0.3s ease !important;
            }
            
            .grid-cols-2 button[type="submit"]:hover {
                transform: translateY(-2px) !important;
                box-shadow: 0 4px 16px rgba(236, 72, 153, 0.4) !important;
            }
            
            /* تحسين مظهر الصور في الجوال */
            .grid-cols-2 .relative {
                border-radius: 8px 8px 0 0 !important;
                overflow: hidden !important;
            }
            
            /* ضمان ظهور الصور كاملة بدون قص */
            .grid-cols-2 .relative img {
                object-fit: contain !important;
                object-position: center !important;
                width: 100% !important;
                height: 100% !important;
                background: linear-gradient(135deg, #fdf2f8 0%, #f3e8ff 100%) !important;
            }
            
            /* تحسين مظهر الصور في جميع الأجهزة */
            .relative img {
                object-fit: contain !important;
                object-position: center !important;
                background: linear-gradient(135deg, #fdf2f8 0%, #f3e8ff 100%) !important;
            }
            
            /* تحسين مظهر شارة السعر في الجوال */
            .grid-cols-2 .absolute {
                border-radius: 12px !important;
                font-weight: bold !important;
            }
        }

        /* Cart Styles */
        #cart-modal {
            backdrop-filter: blur(5px);
        }

        #cart-modal .bg-white {
            animation: cartSlideIn 0.3s ease-out;
        }

        @keyframes cartSlideIn {
            from {
                opacity: 0;
                transform: translateY(-20px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        #cart-btn, #mobile-cart-btn {
            transition: all 0.3s ease;
        }

        #cart-btn:hover, #mobile-cart-btn:hover {
            transform: scale(1.1);
        }

        #close-cart {
            transition: all 0.3s ease;
        }

        #close-cart:hover {
            transform: rotate(90deg);
        }

        #checkout-btn:disabled {
            background: linear-gradient(to right, #9ca3af, #6b7280);
        }

        /* Responsive Cart */
        @media (max-width: 768px) {
            #cart-modal .bg-white {
                margin: 1rem;
                max-height: calc(100vh - 2rem);
            }
            
            #cart-modal .p-4 {
                padding: 1rem;
            }
            
            #cart-modal h3 {
                font-size: 1.1rem;
            }
        }

        @media (max-width: 480px) {
            #cart-modal .bg-white {
                margin: 0.5rem;
                max-height: calc(100vh - 1rem);
            }
            
            #cart-modal .p-4 {
                padding: 0.75rem;
            }
        }

        /* ===== ENHANCED HEADER NAVIGATION STYLES ===== */

        /* Header Container Positioning */
        header .container {
            position: relative;
        }

        /* Enhanced Header Background */
        header {
            background: linear-gradient(135deg, #ec4899 0%, #8b5cf6 50%, #f97316 100%);
            box-shadow: 0 8px 32px rgba(236, 72, 153, 0.3), 0 4px 16px rgba(249, 115, 22, 0.2);
            backdrop-filter: blur(20px);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Enhanced Header Scroll Effect */
        header.scrolled {
            background: linear-gradient(135deg, rgba(236, 72, 153, 0.95) 0%, rgba(139, 92, 246, 0.95) 50%, rgba(249, 115, 22, 0.95) 100%);
            box-shadow: 0 12px 40px rgba(236, 72, 153, 0.4), 0 8px 24px rgba(249, 115, 22, 0.3);
            backdrop-filter: blur(30px);
            border-bottom: 3px solid rgba(249, 115, 22, 0.8);
        }

        /* Center Navigation for Desktop and Tablet */
        @media (min-width: 768px) {
            /* Center the navigation */
            header nav {
                position: absolute;
                left: 50%;
                transform: translateX(-50%);
                z-index: 10;
                background: rgba(255, 255, 255, 0.15);
                backdrop-filter: blur(20px);
                border-radius: 30px;
                padding: 0.75rem 2rem;
                border: 2px solid rgba(249, 115, 22, 0.4);
                box-shadow: 0 8px 32px rgba(249, 115, 22, 0.2), inset 0 1px 0 rgba(255, 255, 255, 0.2);
            }
            
            /* Ensure logo and right section don't overlap */
            header .flex.items-center.justify-between > div:first-child {
                z-index: 20;
                position: relative;
            }
            
            header .flex.items-center.justify-between > div:last-child {
                z-index: 20;
                position: relative;
            }
            
            /* Enhanced Navigation links styling */
            header nav .nav-link {
                position: relative;
                padding: 0.75rem 1.5rem;
                border-radius: 20px;
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                font-weight: 600;
                font-size: 1rem;
                text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
                border: 1px solid transparent;
            }
            
            header nav .nav-link:hover {
                background: rgba(249, 115, 22, 0.2);
                transform: translateY(-3px) scale(1.05);
                box-shadow: 0 8px 25px rgba(249, 115, 22, 0.3), 0 4px 10px rgba(236, 72, 153, 0.2);
                border-color: rgba(249, 115, 22, 0.6);
            }
            
            header nav .nav-link::before {
                content: '';
                position: absolute;
                bottom: -2px;
                left: 50%;
                width: 0;
                height: 3px;
                background: linear-gradient(90deg, #f97316, #ec4899, #f97316);
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                transform: translateX(-50%);
                border-radius: 2px;
                box-shadow: 0 2px 8px rgba(249, 115, 22, 0.4);
            }
            
            header nav .nav-link:hover::before {
                width: 90%;
            }
        }

        /* Responsive adjustments for different screen sizes */
        @media (min-width: 768px) and (max-width: 1023px) {
            /* Enhanced Tablet adjustments */
            header nav {
                gap: 1.5rem;
                padding: 0.6rem 1.8rem;
                border-radius: 25px;
                border: 2px solid rgba(249, 115, 22, 0.5);
                box-shadow: 0 6px 24px rgba(249, 115, 22, 0.25), inset 0 1px 0 rgba(255, 255, 255, 0.2);
            }
            
            header nav .nav-link {
                font-size: 0.95rem;
                padding: 0.6rem 1.2rem;
                font-weight: 600;
            }
            
            header nav .nav-link:hover {
                transform: translateY(-2px) scale(1.03);
                box-shadow: 0 6px 20px rgba(249, 115, 22, 0.25), 0 3px 8px rgba(236, 72, 153, 0.15);
            }
        }

        @media (min-width: 1024px) {
            /* Enhanced Desktop adjustments */
            header nav {
                gap: 2rem;
                padding: 0.75rem 2.5rem;
                border-radius: 35px;
                border: 2px solid rgba(249, 115, 22, 0.6);
                box-shadow: 0 8px 32px rgba(249, 115, 22, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.3);
            }
            
            header nav .nav-link {
                font-size: 1.1rem;
                padding: 0.75rem 1.8rem;
                font-weight: 600;
            }
            
            header nav .nav-link:hover {
                transform: translateY(-3px) scale(1.05);
                box-shadow: 0 8px 25px rgba(249, 115, 22, 0.3), 0 4px 10px rgba(236, 72, 153, 0.2);
            }
            
            /* Enhanced logo hover effect for desktop */
            header .bg-white\/20:hover {
                transform: scale(1.1) rotate(5deg);
                box-shadow: 0 8px 25px rgba(249, 115, 22, 0.4);
            }
        }

        /* Enhanced Mobile navigation */
        @media (max-width: 767px) {
            header nav {
                position: static;
                transform: none;
                background: none;
                backdrop-filter: none;
                border: none;
                padding: 0;
            }
            
            header nav .nav-link {
                background: none;
                padding: 0.75rem 1rem;
                border-radius: 12px;
                font-weight: 500;
                position: relative;
                overflow: hidden;
            }
            
            header nav .nav-link::after {
                content: '';
                position: absolute;
                right: 0;
                top: 50%;
                width: 0;
                height: 2px;
                background: linear-gradient(90deg, #f97316, #ec4899);
                transition: width 0.3s ease;
                transform: translateY(-50%);
            }
            
            header nav .nav-link:hover {
                background: rgba(249, 115, 22, 0.15);
                transform: translateX(5px);
                box-shadow: 0 4px 12px rgba(249, 115, 22, 0.2);
            }
            
            header nav .nav-link:hover::after {
                width: 20px;
            }
            
            header nav .nav-link::before {
                display: none;
            }
            
            /* Enhanced mobile menu styling */
            #mobile-menu {
                border: 2px solid rgba(249, 115, 22, 0.3);
                box-shadow: 0 8px 32px rgba(249, 115, 22, 0.2);
                position: relative;
                overflow: hidden;
            }

            #mobile-menu::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: linear-gradient(135deg, rgba(249, 115, 22, 0.05) 0%, rgba(236, 72, 153, 0.05) 50%, rgba(139, 92, 246, 0.05) 100%);
                pointer-events: none;
            }
            
            #mobile-menu nav a,
            #mobile-menu button {
                position: relative;
                overflow: hidden;
            }
            
            #mobile-menu nav a::before,
            #mobile-menu button::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(249, 115, 22, 0.1), transparent);
                transition: left 0.5s ease;
            }
            
            #mobile-menu nav a:hover::before,
            #mobile-menu button:hover::before {
                left: 100%;
            }
        }

        /* Enhanced Logo and Brand Styling */
        header .bg-white\/20 {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 16px rgba(249, 115, 22, 0.2);
            position: relative;
            overflow: hidden;
        }

        /* Enhanced Logo Container */
        header .flex.items-center.gap-3 {
            position: relative;
        }

        header .flex.items-center.gap-3::before {
            content: '';
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            background: linear-gradient(45deg, rgba(249, 115, 22, 0.1), rgba(236, 72, 153, 0.1), rgba(139, 92, 246, 0.1));
            border-radius: 20px;
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: -1;
        }

        header .flex.items-center.gap-3:hover::before {
            opacity: 1;
        }

        /* Enhanced Button Styling */
        header button, header a[href*="catshoop1.com"] {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        header button:hover, header a[href*="catshoop1.com"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(249, 115, 22, 0.3);
        }

        /* Mobile Menu Enhancements */
        #mobile-menu {
            animation: slideDown 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            transform-origin: top;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Enhanced Header Glow Effect */
        header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(249, 115, 22, 0.1) 0%, rgba(236, 72, 153, 0.1) 50%, rgba(139, 92, 246, 0.1) 100%);
            pointer-events: none;
            z-index: -1;
        }

        /* Enhanced Logo Hover Effects */
        header .bg-white\/20 {
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        header .bg-white\/20::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: conic-gradient(from 0deg, transparent, rgba(249, 115, 22, 0.3), transparent);
            animation: rotate 3s linear infinite;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        header .bg-white\/20:hover::before {
            opacity: 1;
        }

        header .bg-white\/20:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(249, 115, 22, 0.3);
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Enhanced Navigation Link Glow Effect */
        header nav .nav-link {
            position: relative;
            z-index: 1;
        }

        header nav .nav-link::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at center, rgba(249, 115, 22, 0.1) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: -1;
            border-radius: inherit;
        }

        header nav .nav-link:hover::after {
            opacity: 1;
        }

        /* Enhanced Navigation Link Active State */
        header nav .nav-link.active {
            background: rgba(249, 115, 22, 0.3);
            border-color: rgba(249, 115, 22, 0.8);
            box-shadow: 0 4px 15px rgba(249, 115, 22, 0.4);
        }

        /* Enhanced Cart Badge Animation */
        header #cart-btn .bg-orange-400 {
            animation: pulse 2s infinite;
            box-shadow: 0 2px 8px rgba(249, 115, 22, 0.4);
        }

        @keyframes pulse {
            0%, 100% { 
                transform: scale(1);
                box-shadow: 0 2px 8px rgba(249, 115, 22, 0.4);
            }
            50% { 
                transform: scale(1.1);
                box-shadow: 0 4px 12px rgba(249, 115, 22, 0.6);
            }
        }

        /* Enhanced Button Hover Effects */
        header button:hover,
        header a[href*="catshoop1.com"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(249, 115, 22, 0.3);
            filter: brightness(1.1);
        }

        /* Enhanced Mobile Menu Button Shine Effect */
        #mobile-menu-btn {
            position: relative;
            overflow: hidden;
        }

        #mobile-menu-btn::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transform: rotate(45deg);
            transition: all 0.6s ease;
            opacity: 0;
        }

        #mobile-menu-btn:hover::after {
            opacity: 1;
            transform: rotate(45deg) translate(50%, 50%);
        }

        /* Enhanced Mobile Menu Button */
        #mobile-menu-btn {
            position: relative;
            overflow: hidden;
        }

        #mobile-menu-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        #mobile-menu-btn:hover::before {
            left: 100%;
        }

        /* Enhanced Header Border Animation */
        header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, #f97316, #ec4899, #8b5cf6, #f97316);
            background-size: 200% 100%;
            animation: borderFlow 3s linear infinite;
        }

        @keyframes borderFlow {
            0% { background-position: 0% 0%; }
            100% { background-position: 200% 0%; }
        }

        /* Enhanced Navigation Container */
        header nav {
            position: relative;
        }

        header nav::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, rgba(249, 115, 22, 0.3), rgba(236, 72, 153, 0.3), rgba(139, 92, 246, 0.3));
            border-radius: inherit;
            z-index: -1;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        header nav:hover::before {
            opacity: 1;
        }

        /* ===== STATISTICS SECTION WITH VIDEO BACKGROUND ===== */
        .statistics-section {
            position: relative;
            overflow: hidden;
            background: #000;
        }

        /* Video Background */
        .statistics-video-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .statistics-video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            filter: brightness(0.4) contrast(1.2) saturate(1.1);
            transition: filter 0.3s ease;
        }

        .statistics-video:hover {
            filter: brightness(0.5) contrast(1.3) saturate(1.2);
        }

        .statistics-video-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                135deg,
                rgba(0, 0, 0, 0.6) 0%,
                rgba(236, 72, 153, 0.3) 25%,
                rgba(168, 85, 247, 0.3) 50%,
                rgba(245, 158, 11, 0.3) 75%,
                rgba(0, 0, 0, 0.6) 100%
            );
            z-index: 2;
        }

        .statistics-title {
            background: linear-gradient(135deg, #ffffff, #fbbf24, #ffffff, #fbbf24);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            position: relative;
            animation: titleGlow 4s ease-in-out infinite;
            text-shadow: 0 0 30px rgba(255, 255, 255, 0.5);
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
        }

        .statistics-subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        }

        /* Statistics Grid */
        .statistics-grid {
            position: relative;
            z-index: 1;
        }

        /* Statistic Icon Item (Simple Design) */
        .statistic-icon-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            z-index: 10;
            padding: 0.5rem;
        }

        .statistic-icon-item:hover {
            transform: translateY(-5px) scale(1.05);
        }

        .statistic-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #ec4899, #a855f7, #f59e0b);
            background-size: 200% 100%;
            animation: gradientMove 3s ease-in-out infinite;
            border-radius: 20px 20px 0 0;
        }

        .statistic-icon-item:hover .statistic-icon-container {
            transform: scale(1.1);
            box-shadow: 
                0 4px 12px rgba(0, 0, 0, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.4);
        }

        .statistic-icon-item:hover .statistic-icon {
            transform: scale(1.1);
        }

        .statistic-icon-item:hover .statistic-number-container {
            transform: translateY(-2px);
        }

        .statistic-icon-item:hover .statistic-number {
            text-shadow: 0 3px 6px rgba(0, 0, 0, 0.4);
        }

        .statistic-icon-item:hover .statistic-symbol {
            transform: scale(1.1);
        }

        .statistic-icon-item:hover .statistic-title {
            transform: translateY(-1px);
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        }

        /* Statistic Icon Container */
        .statistic-icon-container {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ec4899, #a855f7);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            box-shadow: 
                0 2px 8px rgba(0, 0, 0, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.3);
        }

        .statistic-icon-container::before {
            content: '';
            position: absolute;
            inset: -2px;
            border-radius: 50%;
            background: linear-gradient(45deg, #ec4899, #a855f7, #f59e0b, #ec4899);
            background-size: 400% 400%;
            animation: iconGlow 3s ease-in-out infinite;
            opacity: 0;
            transition: opacity 0.4s ease;
            z-index: -1;
            filter: blur(3px);
        }

        /* Statistic Icon */
        .statistic-icon {
            font-size: 1rem;
            color: white;
            transition: all 0.3s ease;
            filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.2));
        }

        /* Statistic Number Container */
        .statistic-number-container {
            display: flex;
            align-items: baseline;
            justify-content: center;
            gap: 0.2rem;
            margin-bottom: 0.3rem;
        }

        /* Statistic Number */
        .statistic-number {
            font-size: 2.2rem;
            font-weight: 800;
            color: white;
            transition: all 0.3s ease;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            position: relative;
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s, transform 0.6s;
        }

        .statistic-number.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Statistic Symbol */
        .statistic-symbol {
            font-size: 1.2rem;
            font-weight: 700;
            color: white;
            transition: all 0.3s ease;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }

        /* Statistic Title */
        .statistic-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: white;
            margin-bottom: 0.2rem;
            transition: all 0.3s ease;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.4);
            position: relative;
        }

        /* Responsive Design for Statistics */
        @media (max-width: 768px) {
            .statistic-icon-item {
                padding: 0.3rem;
            }
            
            .statistic-icon-container {
                width: 30px;
                height: 30px;
                margin-bottom: 0.4rem;
            }
            
            .statistic-icon {
                font-size: 0.9rem;
            }
            
            .statistic-number {
                font-size: 1.8rem;
            }
            
            .statistic-symbol {
                font-size: 1rem;
            }
            
            .statistic-title {
                font-size: 0.75rem;
            }
            
            .statistics-title {
                font-size: 1.4rem;
            }
            
            .statistics-subtitle {
                font-size: 0.75rem;
            }
            
            /* Mobile video optimization */
            .statistics-video {
                filter: brightness(0.3) contrast(1.1) saturate(1.0);
            }
            
            .statistics-video-overlay {
                background: linear-gradient(
                    135deg,
                    rgba(0, 0, 0, 0.7) 0%,
                    rgba(236, 72, 153, 0.4) 25%,
                    rgba(168, 85, 247, 0.4) 50%,
                    rgba(245, 158, 11, 0.4) 75%,
                    rgba(0, 0, 0, 0.7) 100%
                );
            }
        }

        @media (max-width: 480px) {
            .statistics-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.3rem;
            }
            
            .statistic-icon-item {
                padding: 0.2rem;
            }
            
            .statistic-icon-container {
                width: 28px;
                height: 28px;
                margin-bottom: 0.3rem;
            }
            
            .statistic-icon {
                font-size: 0.8rem;
            }
            
            .statistic-number {
                font-size: 1.5rem;
            }
            
            .statistic-symbol {
                font-size: 0.9rem;
            }
            
            .statistic-title {
                font-size: 0.65rem;
                margin-bottom: 0.15rem;
            }
            
            /* Small screen video optimization */
            .statistics-video {
                filter: brightness(0.25) contrast(1.0) saturate(0.9);
            }
            
            .statistics-video-overlay {
                background: linear-gradient(
                    135deg,
                    rgba(0, 0, 0, 0.8) 0%,
                    rgba(236, 72, 153, 0.5) 25%,
                    rgba(168, 85, 247, 0.5) 50%,
                    rgba(245, 158, 11, 0.5) 75%,
                    rgba(0, 0, 0, 0.8) 100%
                );
            }
            
            .statistics-title {
                font-size: 1.3rem;
            }
            
            .statistics-subtitle {
                font-size: 0.7rem;
            }
        }

        /* Tablet specific styles */
        @media (min-width: 768px) and (max-width: 1024px) {
            .statistic-icon-item {
                padding: 0.4rem;
            }
            
            .statistic-icon-container {
                width: 38px;
                height: 38px;
                margin-bottom: 0.5rem;
            }
            
            .statistic-icon {
                font-size: 1.1rem;
            }
            
            .statistic-number {
                font-size: 2rem;
            }
            
            .statistic-symbol {
                font-size: 1.1rem;
            }
            
            .statistic-title {
                font-size: 0.85rem;
            }
            
            .statistics-title {
                font-size: 1.8rem;
            }
            
            .statistics-subtitle {
                font-size: 0.9rem;
            }
        }

        @media (min-width: 1025px) {
            .statistics-section {
                padding: 4rem 0;
            }
            
            .statistic-icon-item {
                padding: 0.6rem;
            }
            
            .statistic-icon-container {
                width: 45px;
                height: 45px;
                margin-bottom: 0.6rem;
            }
            
            .statistic-icon {
                font-size: 1.3rem;
            }
            
            .statistic-number {
                font-size: 2.5rem;
            }
            
            .statistic-symbol {
                font-size: 1.4rem;
            }
            
            .statistic-title {
                font-size: 1rem;
            }
            
            .statistics-title {
                font-size: 2.2rem;
            }
            
            .statistics-subtitle {
                font-size: 1.1rem;
            }
        }

        .stat-number {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.6s ease;
        }

        .stat-number.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Enhanced Responsive Header Adjustments */
        @media (max-width: 480px) {
            /* Extra Small Mobile */
            header .flex.items-center.gap-3 .flex.flex-col h1 {
                font-size: 1rem !important;
                line-height: 1.2;
            }
            
            header .flex.items-center.gap-3 .flex.flex-col span {
                font-size: 0.7rem !important;
                line-height: 1.1;
            }
            
            header .flex.items-center.gap-3 {
                gap: 0.5rem !important;
            }
            
            header .bg-white\/20.p-2.rounded-full img {
                width: 35px !important;
                height: 35px !important;
            }
            
            header .flex.items-center.justify-between {
                padding: 0.5rem 0.75rem !important;
                height: 4rem !important;
            }
        }

        @media (min-width: 481px) and (max-width: 767px) {
            /* Small Mobile */
            header .flex.items-center.gap-3 .flex.flex-col h1 {
                font-size: 1.2rem !important;
                line-height: 1.3;
            }
            
            header .flex.items-center.gap-3 .flex.flex-col span {
                font-size: 0.8rem !important;
                line-height: 1.2;
            }
            
            header .flex.items-center.gap-3 {
                gap: 0.75rem !important;
            }
            
            header .bg-white\/20.p-2.rounded-full img {
                width: 40px !important;
                height: 40px !important;
            }
            
            header .flex.items-center.justify-between {
                padding: 0.75rem 1rem !important;
                height: 4.5rem !important;
            }
        }

        @media (min-width: 768px) and (max-width: 1023px) {
            /* Tablet */
            header .flex.items-center.gap-3 .flex.flex-col h1 {
                font-size: 1.5rem !important;
                line-height: 1.4;
            }
            
            header .flex.items-center.gap-3 .flex.flex-col span {
                font-size: 0.9rem !important;
                line-height: 1.3;
            }
            
            header .flex.items-center.gap-3 {
                gap: 1rem !important;
            }
            
            header .bg-white\/20.p-2.rounded-full img {
                width: 45px !important;
                height: 45px !important;
            }
            
            header .flex.items-center.justify-between {
                padding: 1rem 1.5rem !important;
                height: 5rem !important;
            }
        }

        @media (min-width: 1024px) {
            /* Desktop */
            header .flex.items-center.gap-3 .flex.flex-col h1 {
                font-size: 1.75rem !important;
                line-height: 1.4;
            }
            
            header .flex.items-center.gap-3 .flex.flex-col span {
                font-size: 1rem !important;
                line-height: 1.3;
            }
            
            header .flex.items-center.gap-3 {
                gap: 1.25rem !important;
            }
            
            header .bg-white\/20.p-2.rounded-full img {
                width: 50px !important;
                height: 50px !important;
            }
            
            header .flex.items-center.justify-between {
                padding: 1.25rem 2rem !important;
                height: 5.5rem !important;
            }
        }

        /* ===== Booking DateTime Selection Styles ===== */
        .time-slot-btn {
            transition: all 0.3s ease;
        }
        .time-slot-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .time-slot-btn.selected {
            background: linear-gradient(135deg, #ec4899, #a855f7) !important;
            color: white !important;
            border-color: #ec4899 !important;
        }

        /* Custom Calendar Styles */
        .custom-calendar-container {
            max-width: 100%;
            margin: 0 auto;
        }

        .calendar-day {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            border-radius: 8px;
            font-weight: 500;
            min-height: 44px;
            font-size: 0.875rem;
        }

        @media (max-width: 768px) {
            .calendar-day {
                aspect-ratio: 1;
                min-height: 28px;
                max-height: 32px;
            }
        }

        @media (max-width: 640px) {
            .calendar-day {
                aspect-ratio: 1;
                min-height: 24px;
                max-height: 28px;
            }
        }

        @media (max-width: 480px) {
            .calendar-day {
                aspect-ratio: 1;
                min-height: 20px;
                max-height: 24px;
            }
        }

        .calendar-day:hover {
            background-color: #fce7f3;
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .calendar-day:hover {
                transform: scale(1.02);
            }
            
            .calendar-day.selected {
                transform: scale(1.05);
            }
        }

        .calendar-day.available {
            background-color: #f0f9ff;
            border: 2px solid #0ea5e9;
            color: #0c4a6e;
        }

        @media (max-width: 768px) {
            .calendar-day.available {
                border-width: 1px;
            }
            
            .calendar-day.selected {
                border-width: 1px;
            }
            
            .calendar-day.today {
                border-width: 1px;
            }
        }

        .calendar-day.available:hover {
            background-color: #0ea5e9;
            color: white;
        }

        .calendar-day.selected {
            background: linear-gradient(135deg, #ec4899, #a855f7);
            color: white;
            border: 2px solid #ec4899;
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(236, 72, 153, 0.3);
        }

        .calendar-day.past {
            color: #9ca3af;
            cursor: not-allowed;
            background-color: #f3f4f6;
            opacity: 0.5;
            text-decoration: line-through;
            position: relative;
        }

        .calendar-day.past::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: repeating-linear-gradient(
                45deg,
                transparent,
                transparent 2px,
                rgba(156, 163, 175, 0.1) 2px,
                rgba(156, 163, 175, 0.1) 4px
            );
            pointer-events: none;
        }

        .calendar-day.friday {
            color: #ef4444;
            background-color: #fef2f2;
            cursor: not-allowed;
            position: relative;
        }

        .calendar-day.friday::before {
            content: "إجازة";
            position: absolute;
            bottom: 2px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 8px;
            font-weight: bold;
        }

        .calendar-day.other-month {
            color: #d1d5db;
            cursor: not-allowed;
        }

        .calendar-day.today {
            border: 2px solid #f59e0b;
            background-color: #fffbeb;
            color: #92400e;
            font-weight: bold;
        }

        .calendar-day.today.available {
            border-color: #0ea5e9;
            background-color: #f0f9ff;
        }

        .calendar-header button:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(255,255,255,0.5);
        }

        /* ===== Responsive Improvements for Booking DateTime ===== */
        @media (max-width: 768px) {
            .custom-calendar-container {
                margin: 0 -0.5rem;
            }
            
            .calendar-day {
                min-height: 24px;
                max-height: 28px;
                font-size: 0.7rem;
                font-weight: 400;
            }
            
            .calendar-header {
                padding: 0.375rem;
            }
            
            .calendar-header h3 {
                font-size: 0.8rem;
            }
            
            .calendar-days-header div {
                padding: 0.125rem 0.0625rem;
                font-size: 0.65rem;
            }
            
            .calendar-days {
                padding: 0.5rem;
                gap: 0.125rem;
            }
            
            .calendar-footer {
                padding: 0.375rem;
            }
            
            .time-slot-btn {
                padding: 0.375rem 0.5rem;
                font-size: 0.8rem;
            }
        }

        @media (max-width: 640px) {
            .calendar-day {
                min-height: 28px;
                font-size: 0.65rem;
                font-weight: 400;
            }
            
            .calendar-days-header div {
                padding: 0.375rem 0.125rem;
                font-size: 0.65rem;
            }
            
            .calendar-days {
                padding: 0.375rem;
                gap: 0.125rem;
            }
            
            .time-slot-btn {
                padding: 0.375rem 0.5rem;
                font-size: 0.8rem;
            }
            
            .calendar-day.friday::before {
                font-size: 5px;
                bottom: 1px;
            }
        }

        /* تحسين عرض الأزرار على الشاشات الصغيرة */
        @media (max-width: 480px) {
            .action-buttons-container {
                flex-direction: row;
                gap: 0.4rem;
                flex-wrap: wrap;
                justify-content: center;
                width: 100%;
                padding: 0 0.5rem;
            }
            
            .action-btn {
                flex: 1;
                min-width: 80px;
                padding: 0.6rem 0.5rem;
                font-size: 0.7rem;
            }
            
            .btn-text {
                font-size: 0.65rem;
            }
            
            .action-btn i {
                font-size: 0.9rem;
            }
        }

        /* تحسين عرض التقويم على الشاشات المتوسطة */
        @media (min-width: 641px) and (max-width: 1024px) {
            .custom-calendar-container {
                max-width: 100%;
            }
            
            .calendar-day {
                min-height: 40px;
            }
            
            .time-slot-btn {
                padding: 0.625rem 1rem;
            }
        }

        /* تحسينات إضافية للشاشات الصغيرة جداً */
        @media (max-width: 480px) {
            .calendar-day {
                min-height: 18px;
                max-height: 22px;
                font-size: 0.55rem;
                font-weight: 400;
                border-radius: 3px;
            }
            
            .calendar-days-header div {
                padding: 0.125rem 0.0625rem;
                font-size: 0.55rem;
            }
            
            .calendar-days {
                padding: 0.125rem;
                gap: 0.0625rem;
            }
            
            .calendar-header {
                padding: 0.25rem;
            }
            
            .calendar-header h3 {
                font-size: 0.75rem;
            }
            
            .calendar-footer {
                padding: 0.25rem;
            }
            
            .calendar-day.friday::before {
                font-size: 3px;
                bottom: 0.25px;
            }
            
            .calendar-day.selected {
                transform: scale(1.03);
            }
            
            .calendar-day:hover {
                transform: scale(1.01);
            }
        }

        /* تحسينات للشاشات الصغيرة جداً جداً */
        @media (max-width: 360px) {
            .calendar-day {
                min-height: 16px;
                max-height: 20px;
                font-size: 0.5rem;
            }
            
            .calendar-days-header div {
                padding: 0.0625rem 0.03125rem;
                font-size: 0.5rem;
            }
            
            .calendar-days {
                padding: 0.0625rem;
                gap: 0.03125rem;
            }
            
            .calendar-header {
                padding: 0.125rem;
            }
            
            .calendar-header h3 {
                font-size: 0.7rem;
            }
            
            .calendar-footer {
                padding: 0.125rem;
            }
            
            .calendar-day.friday::before {
                font-size: 2px;
                bottom: 0.125px;
            }
            
            /* تحسين الأزرار للشاشات الصغيرة جداً */
            .action-buttons-container {
                flex-direction: row;
                gap: 0.3rem;
                flex-wrap: wrap;
                justify-content: center;
                width: 100%;
                padding: 0 0.3rem;
            }
            
            .action-btn {
                flex: 1;
                min-width: 70px;
                padding: 0.5rem 0.4rem;
                font-size: 0.65rem;
            }
            
            .btn-text {
                font-size: 0.6rem;
            }
            
            .action-btn i {
                font-size: 0.8rem;
            }
        }

        /* ===== Mobile Side-by-Side Layout ===== */
        @media (max-width: 767px) {
            /* جعل التاريخ والوقت جنباً إلى جنب على الجوال */
            .grid.grid-cols-1.md\:grid-cols-2 {
                grid-template-columns: 1fr 1fr !important;
                gap: 0.5rem !important;
            }
            
            /* تصغير التقويم أكثر على الجوال */
            .custom-calendar-container {
                margin: 0;
                max-width: 100%;
            }
            
            .calendar-day {
                min-height: 20px !important;
                max-height: 24px !important;
                font-size: 0.6rem !important;
                font-weight: 400;
            }
            
            .calendar-header {
                padding: 0.25rem !important;
            }
            
            .calendar-header h3 {
                font-size: 0.7rem !important;
            }
            
            .calendar-days-header div {
                padding: 0.125rem 0.0625rem !important;
                font-size: 0.6rem !important;
            }
            
            .calendar-days {
                padding: 0.25rem !important;
                gap: 0.125rem !important;
            }
            
            .calendar-footer {
                padding: 0.25rem !important;
            }
            
            .calendar-footer span {
                font-size: 0.7rem !important;
            }
            
            .mt-4.bg-blue-50 {
                padding: 0.5rem !important;
            }
            
            .mt-4.bg-blue-50 h4 {
                font-size: 0.8rem !important;
            }
            
            .mt-4.bg-blue-50 .grid {
                gap: 0.25rem !important;
            }
            
            .mt-4.bg-blue-50 .grid div {
                padding: 0.125rem !important;
            }
            
            .time-slot-btn {
                padding: 0.25rem 0.375rem !important;
                font-size: 0.7rem !important;
                min-height: 28px !important;
            }
            
            .text-base.sm\:text-lg {
                font-size: 0.8rem !important;
                line-height: 1.3 !important;
            }
            
            .mb-3.sm\:mb-4 {
                margin-bottom: 0.5rem !important;
            }
            
            .mb-6.sm\:mb-8 {
                margin-bottom: 1rem !important;
            }
        }

        @media (max-width: 480px) {
            .grid.grid-cols-1.md\:grid-cols-2 {
                grid-template-columns: 1fr !important;
                gap: 0.25rem !important;
            }
            
            .calendar-day {
                min-height: 18px !important;
                max-height: 22px !important;
                font-size: 0.55rem !important;
            }
            
            .calendar-header h3 {
                font-size: 0.65rem !important;
            }
            
            .calendar-days-header div {
                font-size: 0.55rem !important;
            }
            
            .time-slot-btn {
                padding: 0.2rem 0.3rem !important;
                font-size: 0.65rem !important;
            }
            
            .text-base.sm\:text-lg {
                font-size: 0.75rem !important;
            }
        }

        @media (max-width: 360px) {
            .grid.grid-cols-1.md\:grid-cols-2 {
                grid-template-columns: 1fr !important;
                gap: 0.125rem !important;
            }
            
            .calendar-day {
                min-height: 16px !important;
                max-height: 20px !important;
                font-size: 0.5rem !important;
            }
            
            .calendar-header h3 {
                font-size: 0.6rem !important;
            }
            
            .calendar-days-header div {
                font-size: 0.5rem !important;
            }
            
            .time-slot-btn {
                padding: 0.15rem 0.25rem !important;
                font-size: 0.6rem !important;
            }
        }

        /* ===== Additional Mobile Calendar Improvements ===== */
        @media (max-width: 767px) {
            .calendar-footer span {
                font-size: 0.6rem !important;
            }
            
            /* تصغير معلومات أوقات العمل */
            .mt-4.bg-blue-50 {
                margin-top: 0.5rem !important;
                padding: 0.5rem !important;
            }
            
            .mt-4.bg-blue-50 h4 {
                font-size: 0.7rem !important;
                margin-bottom: 0.5rem !important;
            }
            
            .mt-4.bg-blue-50 .grid {
                gap: 0.25rem !important;
            }
            
            .mt-4.bg-blue-50 .grid div {
                font-size: 0.6rem !important;
            }
            
            /* تصغير أزرار الوقت */
            .time-slot-btn {
                padding: 0.25rem 0.5rem !important;
                font-size: 0.7rem !important;
                margin: 0.125rem !important;
            }
            
            /* تصغير العناوين */
            .text-base.sm\:text-lg {
                font-size: 0.8rem !important;
                margin-bottom: 0.5rem !important;
            }
            
            /* تصغير المسافات بين العناصر */
            .mb-3.sm\:mb-4 {
                margin-bottom: 0.5rem !important;
            }
            
            .mb-6.sm\:mb-8 {
                margin-bottom: 0.75rem !important;
            }
        }

        /* تحسينات إضافية للشاشات الصغيرة جداً */
        @media (max-width: 480px) {
            .grid.grid-cols-1.md\:grid-cols-2 {
                gap: 0.25rem !important;
            }
            
            .calendar-day {
                min-height: 18px !important;
                max-height: 22px !important;
                font-size: 0.55rem !important;
            }
            
            .calendar-header h3 {
                font-size: 0.65rem !important;
            }
            
            .calendar-days-header div {
                font-size: 0.55rem !important;
            }
            
            .time-slot-btn {
                padding: 0.2rem 0.4rem !important;
                font-size: 0.65rem !important;
            }
            
            .text-base.sm\:text-lg {
                font-size: 0.75rem !important;
            }
        }

        /* تحسينات للشاشات الصغيرة جداً جداً */
        @media (max-width: 360px) {
            .grid.grid-cols-1.md\:grid-cols-2 {
                gap: 0.125rem !important;
            }
            
            .calendar-day {
                min-height: 16px !important;
                max-height: 20px !important;
                font-size: 0.5rem !important;
            }
            
            .calendar-header h3 {
                font-size: 0.6rem !important;
            }
            
            .calendar-days-header div {
                font-size: 0.5rem !important;
            }
            
            .time-slot-btn {
                padding: 0.15rem 0.3rem !important;
                font-size: 0.6rem !important;
            }
        }
    </style>
    
    <!-- Flash Messages for JavaScript -->
    <script>
        var flashMessages = {
            @if(session('success'))
                success: '{{ session('success') }}'
            @endif
            @if(session('error'))
                error: '{{ session('error') }}'
            @endif
        };
        
        // Performance optimization: Preload critical resources
        if ('connection' in navigator) {
            if (navigator.connection.effectiveType === '4g') {
                // Preload images for fast connections
                const preloadImages = [
                    '/images/logo.png',
                    '/images/default-product.svg'
                ];
                preloadImages.forEach(src => {
                    const link = document.createElement('link');
                    link.rel = 'preload';
                    link.as = 'image';
                    link.href = src;
                    document.head.appendChild(link);
                });
            }
        }
    </script>
    
    <!-- Schema.org Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "LocalBusiness",
        "name": "{{ config('brand.name') }}",
        "alternateName": "{{ config('brand.name_en') }}",
        "description": "أفضل خدمات العناية بالحيوانات الأليفة في الرياض. غسيل، تجميل، قص أظافر، تنظيف أذنين للقطط والكلاب. خدمة منزلية مضمونة بأسعار مناسبة.",
        "url": "{{ url()->current() }}",
        "logo": "/images/logo.png",
        "image": "/images/logo.png",
        "telephone": "{{ config('brand.whatsapp') }}",
        "priceRange": "$$",
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
        "openingHours": "Mo-Su 08:00-22:00",
        "serviceArea": {
            "@type": "City",
            "name": "الرياض"
        },
        "hasOfferCatalog": {
            "@type": "OfferCatalog",
            "name": "خدمات العناية بالحيوانات الأليفة",
            "itemListElement": [
                {
                    "@type": "Offer",
                    "itemOffered": {
                        "@type": "Service",
                        "name": "غسيل وتجميل القطط",
                        "description": "خدمة غسيل وتجميل شاملة للقطط"
                    }
                },
                {
                    "@type": "Offer",
                    "itemOffered": {
                        "@type": "Service",
                        "name": "غسيل وتجميل الكلاب",
                        "description": "خدمة غسيل وتجميل شاملة للكلاب"
                    }
                },
                {
                    "@type": "Offer",
                    "itemOffered": {
                        "@type": "Service",
                        "name": "قص أظافر الحيوانات",
                        "description": "خدمة قص أظافر آمنة للحيوانات الأليفة"
                    }
                },
                {
                    "@type": "Offer",
                    "itemOffered": {
                        "@type": "Service",
                        "name": "تنظيف الأذنين",
                        "description": "خدمة تنظيف أذنين احترافية"
                    }
                }
            ]
        },
        "aggregateRating": {
            "@type": "AggregateRating",
            "ratingValue": "4.9",
            "reviewCount": "150",
            "bestRating": "5",
            "worstRating": "1"
        },
        "review": [
            {
                "@type": "Review",
                "reviewRating": {
                    "@type": "Rating",
                    "ratingValue": "5",
                    "bestRating": "5"
                },
                "author": {
                    "@type": "Person",
                    "name": "نورة العتيبي"
                },
                "reviewBody": "الخدمة ممتازة جدًا! وصلوا في الوقت المحدد وتعاملهم راقٍ مع أليفي، أنصح أي أحد يجربهم بدون تردد."
            },
            {
                "@type": "Review",
                "reviewRating": {
                    "@type": "Rating",
                    "ratingValue": "5",
                    "bestRating": "5"
                },
                "author": {
                    "@type": "Person",
                    "name": "سارة الشهراني"
                },
                "reviewBody": "طلبت خدمة غسيل للقطتي، كانوا حريصين واحترافيين جدًا. حبيت التنظيم والدقة."
            }
        ],
        "sameAs": [
            "https://wa.me/{{ config('brand.whatsapp') }}"
        ]
    }
    </script>
    
    <!-- Organization Schema -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "{{ config('brand.name') }}",
        "url": "{{ url()->current() }}",
        "logo": "/images/logo.png",
        "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "{{ config('brand.whatsapp') }}",
            "contactType": "customer service",
            "availableLanguage": "Arabic"
        }
    }
    </script>
    
    <!-- Breadcrumb Schema -->
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
                "name": "خدمات العناية بالحيوانات الأليفة",
                "item": "{{ url()->current() }}"
            }
        ]
    }
    </script>
    
    <!-- Frontend JavaScript - Part 1 -->
    <script>
        // Frontend JavaScript for {{ config('brand.name_en') }} - محسن للسرعة القصوى
        // This file contains all frontend functionality for better performance and maintainability

        // تحسينات الأداء - تحميل سريع
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Typewriter Effect
            initializeTypewriter();
            
                    // Enhanced Navigation Bar Functionality
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const header = document.querySelector('header');
        const navLinks = document.querySelectorAll('header nav .nav-link');
        
        // Add active state to current page navigation link
        const currentPath = window.location.pathname;
        navLinks.forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                link.classList.add('active');
            }
        });
        
        // Enhanced header scroll effect
        let lastScrollTop = 0;
        window.addEventListener('scroll', function() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            if (scrollTop > 100) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
            
            lastScrollTop = scrollTop;
        });
            
            if (mobileMenuBtn && mobileMenu) {
                mobileMenuBtn.addEventListener('click', function() {
                    const isHidden = mobileMenu.classList.contains('hidden');
                    
                    if (isHidden) {
                        // Show menu with enhanced animation
                        mobileMenu.classList.remove('hidden');
                        mobileMenu.style.opacity = '0';
                        mobileMenu.style.transform = 'translateY(-20px) scale(0.95)';
                        
                        setTimeout(() => {
                            mobileMenu.style.transition = 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
                            mobileMenu.style.opacity = '1';
                            mobileMenu.style.transform = 'translateY(0) scale(1)';
                        }, 10);
                        
                        // Add button animation
                        mobileMenuBtn.style.transform = 'rotate(90deg)';
                        mobileMenuBtn.querySelector('i').classList.remove('fa-bars');
                        mobileMenuBtn.querySelector('i').classList.add('fa-times');
                    } else {
                        // Hide menu with enhanced animation
                        mobileMenu.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
                        mobileMenu.style.opacity = '0';
                        mobileMenu.style.transform = 'translateY(-20px) scale(0.95)';
                        
                        setTimeout(() => {
                            mobileMenu.classList.add('hidden');
                            mobileMenu.style.transition = '';
                            mobileMenu.style.opacity = '';
                            mobileMenu.style.transform = '';
                        }, 300);
                        
                        // Reset button animation
                        mobileMenuBtn.style.transform = 'rotate(0deg)';
                        mobileMenuBtn.querySelector('i').classList.remove('fa-times');
                        mobileMenuBtn.querySelector('i').classList.add('fa-bars');
                    }
                });
                
                // Close mobile menu when clicking outside
                document.addEventListener('click', function(event) {
                    if (!mobileMenuBtn.contains(event.target) && !mobileMenu.contains(event.target)) {
                        if (!mobileMenu.classList.contains('hidden')) {
                            mobileMenu.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
                            mobileMenu.style.opacity = '0';
                            mobileMenu.style.transform = 'translateY(-20px) scale(0.95)';
                            
                            setTimeout(() => {
                                mobileMenu.classList.add('hidden');
                                mobileMenu.style.transition = '';
                                mobileMenu.style.opacity = '';
                                mobileMenu.style.transform = '';
                                
                                // Reset button
                                mobileMenuBtn.style.transform = 'rotate(0deg)';
                                mobileMenuBtn.querySelector('i').classList.remove('fa-times');
                                mobileMenuBtn.querySelector('i').classList.add('fa-bars');
                            }, 300);
                        }
                    }
                });
                
                // Close mobile menu when window is resized to desktop
                window.addEventListener('resize', function() {
                    if (window.innerWidth >= 768) {
                        mobileMenu.classList.add('hidden');
                        mobileMenu.style.transition = '';
                        mobileMenu.style.opacity = '';
                        mobileMenu.style.transform = '';
                        
                        // Reset button
                        mobileMenuBtn.style.transform = 'rotate(0deg)';
                        mobileMenuBtn.querySelector('i').classList.remove('fa-times');
                        mobileMenuBtn.querySelector('i').classList.add('fa-bars');
                    }
                });
            }
            
            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Flash messages
            if (typeof flashMessages !== 'undefined') {
                if (flashMessages.success) {
                    alert(flashMessages.success);
                }
                if (flashMessages.error) {
                    alert(flashMessages.error);
                }
            }
            
            // تحسينات الأداء - تحميل سريع للصور
            const images = document.querySelectorAll('img[data-src]');
            if (images.length > 0) {
                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            img.src = img.dataset.src;
                            img.classList.remove('lazy');
                            imageObserver.unobserve(img);
                        }
                    });
                }, {
                    rootMargin: '50px 0px',
                    threshold: 0.1
                });
                
                images.forEach(img => imageObserver.observe(img));
            }
            
            // تحسينات الأداء - تحميل سريع للفيديو
            const videos = document.querySelectorAll('video[data-src]');
            if (videos.length > 0) {
                const videoObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const video = entry.target;
                            video.src = video.dataset.src;
                            videoObserver.unobserve(video);
                        }
                    });
                }, {
                    rootMargin: '100px 0px',
                    threshold: 0.1
                });
                
                videos.forEach(video => videoObserver.observe(video));
            }
            
            // SEO Enhancement: Track user engagement
            let startTime = Date.now();
            let hasScrolled = false;
            let hasClicked = false;
            
            window.addEventListener('scroll', function() {
                if (!hasScrolled) {
                    hasScrolled = true;
                    // Track scroll event for analytics
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'scroll', {
                            'event_category': 'engagement',
                            'event_label': 'page_scroll'
                        });
                    }
                }
            });
            
            document.addEventListener('click', function() {
                if (!hasClicked) {
                    hasClicked = true;
                    // Track click event for analytics
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'click', {
                            'event_category': 'engagement',
                            'event_label': 'page_interaction'
                        });
                    }
                }
            });
            
            // Track time on page
            window.addEventListener('beforeunload', function() {
                const timeOnPage = Date.now() - startTime;
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'timing_complete', {
                        'name': 'page_view_time',
                        'value': Math.round(timeOnPage / 1000)
                    });
                }
            });
            
            // Cart Functionality
            initializeCart();
        });

        // Cart System
        let cart = [];
        
        // دالة لتحميل السلة من localStorage
        function loadCartFromStorage() {
            try {
                const storedCart = localStorage.getItem('catShopCart');
                cart = storedCart ? JSON.parse(storedCart) : [];
                console.log('Cart loaded from storage:', cart);
            } catch (error) {
                console.error('Error loading cart from storage:', error);
                cart = [];
            }
        }
        
        // تحميل السلة عند بدء التطبيق
        loadCartFromStorage();
        
        function initializeCart() {
            const cartBtn = document.getElementById('cart-btn');
            const mobileCartBtn = document.getElementById('mobile-cart-btn');
            const cartModal = document.getElementById('cart-modal');
            const closeCart = document.getElementById('close-cart');
            const cartItems = document.getElementById('cart-items');
            const emptyCart = document.getElementById('empty-cart');
            const cartTotal = document.getElementById('cart-total');
            
            // Update cart display
            function updateCartDisplay() {
                console.log('Updating cart display, cart:', cart);
                const count = cart.length;
                
                // التأكد من وجود العناصر المطلوبة
                if (!cartItems || !emptyCart || !cartTotal) {
                    console.error('Cart elements not found:', { cartItems, emptyCart, cartTotal });
                    return;
                }
                
                // تحديث العداد في النافيجيشن بار
                const cartBadges = document.querySelectorAll('#cart-btn .bg-orange-400, #mobile-cart-btn .bg-orange-400');
                cartBadges.forEach(badge => {
                    badge.textContent = count;
                    if (count > 0) {
                        badge.style.opacity = '1';
                    } else {
                        badge.style.opacity = '0';
                    }
                });
                
                if (count === 0) {
                    if (emptyCart) emptyCart.style.display = 'block';
                    if (cartItems) cartItems.innerHTML = '';
                    if (cartTotal) cartTotal.textContent = '0.00';
                } else {
                    if (emptyCart) emptyCart.style.display = 'none';
                    if (cartItems) {
                        // مسح المحتوى الحالي
                        cartItems.innerHTML = '';
                        
                        // إضافة المنتجات
                        let itemsHtml = '';
                        cart.forEach(item => {
                            itemsHtml += `
                                <div class="bg-gray-50 rounded-lg p-4 mb-3">
                                    <div class="flex items-center gap-3 mb-3">
                                        <img src="${item.image}" alt="${item.name}" class="w-16 h-16 object-cover rounded-md" onerror="this.src='{{ asset('images/default-product.svg') }}'; this.onerror=null;">
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900">${item.name}</h4>
                                            <p class="text-sm text-gray-500">${item.price.toFixed(2)} ر.س</p>
                                        </div>
                                        <button type="button" onclick="removeFromCart('${item.id}')" class="text-red-500 hover:text-red-700 p-2 rounded-full hover:bg-red-50 transition-colors" title="حذف من السلة">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    <div class="flex gap-2">
                                        <button type="button" onclick="completeOrderForItem('${item.id}')" class="flex-1 bg-gradient-to-r from-pink-600 to-purple-600 text-white py-2 px-3 rounded-md text-sm font-medium hover:from-pink-700 hover:to-purple-700 transition-all duration-300">
                                            <i class="fas fa-shopping-cart ml-1"></i>
                                            إتمام الطلب
                                        </button>
                                    </div>
                                </div>
                            `;
                        });
                        cartItems.innerHTML = itemsHtml;
                    }
                    if (cartTotal) {
                        const total = cart.reduce((sum, item) => sum + parseFloat(item.price), 0);
                        cartTotal.textContent = total.toFixed(2);
                    }
                }
            }
            
            // دالة إزالة منتج من السلة
            window.removeFromCart = function(productId) {
                console.log('Removing product:', productId, 'Type:', typeof productId);
                console.log('Current cart before removal:', cart);
                
                if (confirm('هل تريد حذف هذا المنتج من السلة؟')) {
                    // تحويل productId إلى رقم للمقارنة الصحيحة
                    const numericProductId = parseInt(productId);
                    cart = cart.filter(item => parseInt(item.id) !== numericProductId);
                    localStorage.setItem('catShopCart', JSON.stringify(cart));
                    console.log('Cart after removal:', cart);
                    
                    // تحديث العرض فوراً
                    updateCartDisplay();
                    
                    // إظهار رسالة نجاح
                    setTimeout(() => {
                        alert('تم حذف المنتج من السلة بنجاح');
                    }, 100);
                }
            }

            // دالة تحديث عرض السلة (متاحة عالمياً)
            window.updateCartDisplay = updateCartDisplay;

            // دالة إتمام الطلب لمنتج واحد
            window.completeOrderForItem = function(productId) {
                console.log('Completing order for product:', productId);
                
                // إرسال المنتج المحدد إلى صفحة الحجز
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("frontend.booking.start") }}';
                
                // إضافة CSRF token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                form.appendChild(csrfToken);
                
                // إضافة معرف المنتج
                const productInput = document.createElement('input');
                productInput.type = 'hidden';
                productInput.name = 'product_id';
                productInput.value = productId;
                form.appendChild(productInput);
                
                // إضافة البيانات إلى session storage للوصول إليها في صفحة الحجز
                const numericProductId = parseInt(productId);
                const selectedItem = cart.find(item => parseInt(item.id) === numericProductId);
                if (selectedItem) {
                    sessionStorage.setItem('cartItems', JSON.stringify([selectedItem]));
                }
                
                document.body.appendChild(form);
                form.submit();
            }
            

            
            // Show cart modal
            function showCart() {
                cartModal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
            
            // Hide cart modal
            function hideCart() {
                cartModal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
            
            // Event listeners
            if (cartBtn) {
                cartBtn.addEventListener('click', showCart);
            }
            
            if (mobileCartBtn) {
                mobileCartBtn.addEventListener('click', showCart);
            }
            
            if (closeCart) {
                closeCart.addEventListener('click', hideCart);
            }
            
            // Close cart when clicking outside
            if (cartModal) {
                cartModal.addEventListener('click', function(e) {
                    if (e.target === cartModal) {
                        hideCart();
                    }
                });
            }
            
            // Close cart with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !cartModal.classList.contains('hidden')) {
                    hideCart();
                }
            });
            
            // Initialize cart display
            updateCartDisplay();
            
            // دالة عامة لتحديث السلة من الصفحات الأخرى
            window.updateCartFromPage = function() {
                console.log('Updating cart from page');
                loadCartFromStorage();
                updateCartDisplay();
            }
        }

        // Initialize Swiper for Products
        function initializeProductsSwiper() {
            if (typeof Swiper !== 'undefined') {
                const productsSwiper = new Swiper('.swiper', {
                    // Optional parameters
                    direction: 'horizontal',
                    loop: true,
                    
                    // Responsive breakpoints
                    breakpoints: {
                        // when window width is >= 320px
                        320: {
                            slidesPerView: 2,
                            spaceBetween: 10
                        },
                        // when window width is >= 480px
                        480: {
                            slidesPerView: 2,
                            spaceBetween: 15
                        },
                        // when window width is >= 768px
                        768: {
                            slidesPerView: 2,
                            spaceBetween: 20
                        },
                        // when window width is >= 1024px
                        1024: {
                            slidesPerView: 3,
                            spaceBetween: 25
                        },
                        // when window width is >= 1200px
                        1200: {
                            slidesPerView: 4,
                            spaceBetween: 30
                        }
                    },
                    
                    // Navigation arrows
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    
                    // Pagination
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                    },
                    
                    // Auto play
                    autoplay: {
                        delay: 3000,
                        disableOnInteraction: false,
                    },
                });
            }
        }

        // Initialize Swiper for Reviews
        function initializeReviewsSwiper() {
            if (typeof Swiper !== 'undefined') {
                const reviewsSwiper = new Swiper('.reviews-swiper', {
                    // Optional parameters
                    direction: 'horizontal',
                    loop: true,
                    
                    // Responsive breakpoints
                    breakpoints: {
                        // when window width is >= 320px
                        320: {
                            slidesPerView: 2,
                            spaceBetween: 10
                        },
                        // when window width is >= 480px
                        480: {
                            slidesPerView: 2,
                            spaceBetween: 15
                        },
                        // when window width is >= 768px
                        768: {
                            slidesPerView: 2,
                            spaceBetween: 20
                        },
                        // when window width is >= 1024px
                        1024: {
                            slidesPerView: 3,
                            spaceBetween: 25
                        },
                        // when window width is >= 1200px
                        1200: {
                            slidesPerView: 3,
                            spaceBetween: 30
                        }
                    },
                    
                    // Navigation arrows
                    navigation: {
                        nextEl: '.reviews-nav-next',
                        prevEl: '.reviews-nav-prev',
                    },
                    
                    // Pagination
                    pagination: {
                        el: '.reviews-pagination',
                        clickable: true,
                    },
                    
                    // Auto play
                    autoplay: {
                        delay: 4000,
                        disableOnInteraction: false,
                    },
                });
            }
        }

        // Initialize all Swiper instances when Swiper is loaded
        window.addEventListener('load', function() {
            // Wait a bit for Swiper to be fully loaded
            setTimeout(function() {
                initializeProductsSwiper();
                initializeReviewsSwiper();
            }, 100);
        });

        // Statistics Animation
        function initializeStatisticsAnimation() {
            // Intersection Observer for statistics animation
            const observerOptions = {
                threshold: 0.5,
                rootMargin: '0px 0px -100px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const statNumbers = entry.target.querySelectorAll('.stat-number');
                        statNumbers.forEach((statNumber, index) => {
                            setTimeout(() => {
                                animateCounter(statNumber);
                            }, index * 200);
                        });
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            // Observe the stats section
            const statsSection = document.querySelector('.stats-section');
            if (statsSection) {
                observer.observe(statsSection);
            }
        }

        // Counter animation function
        function animateCounter(element) {
            const target = parseInt(element.getAttribute('data-target'));
            const duration = 2000;
            const step = target / (duration / 16);
            let current = 0;

            element.classList.add('visible');

            const timer = setInterval(() => {
                current += step;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                
                // Add + symbol for numbers except the percentage
                const isPercentage = element.textContent.includes('%') || element.getAttribute('data-target') === '99';
                const displayValue = isPercentage ? 
                    Math.floor(current) + '%' : 
                    '+' + Math.floor(current).toLocaleString();
                
                element.textContent = displayValue;
            }, 16);
        }

        // Initialize statistics animation when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            initializeStatisticsAnimation();
            initializeNewStatisticsAnimation();
            initializeStatisticsCounter();
        });

        // New Statistics Animation for the new statistics section
        function initializeNewStatisticsAnimation() {
            // Intersection Observer for new statistics animation
            const observerOptions = {
                threshold: 0.3,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const statNumbers = entry.target.querySelectorAll('.stat-number');
                        statNumbers.forEach((statNumber, index) => {
                            setTimeout(() => {
                                animateNewCounter(statNumber);
                            }, index * 300);
                        });
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            // Observe the new statistics section
            const statisticsSection = document.querySelector('.statistics-section');
            if (statisticsSection) {
                observer.observe(statisticsSection);
            }
        }

        // New Counter animation function
        function animateNewCounter(element) {
            const target = parseInt(element.getAttribute('data-target'));
            const duration = 2500;
            const step = target / (duration / 16);
            let current = 0;
            const counterElement = element.querySelector('.counter');

            // Add visible class for CSS animation
            element.classList.add('visible');

            const timer = setInterval(() => {
                current += step;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                    
                    // Add final animation
                    counterElement.classList.add('animate');
                    setTimeout(() => {
                        counterElement.classList.remove('animate');
                    }, 500);
                }
                
                // Format the number with commas
                const formattedNumber = Math.floor(current).toLocaleString();
                counterElement.textContent = formattedNumber;
            }, 16);
        }

        // Video functionality for statistics section
        function initializeVideoFunctionality() {
            const videoContainer = document.querySelector('.video-container');
            if (!videoContainer) return;

            const video = videoContainer.querySelector('video');
            const videoOverlay = videoContainer.querySelector('.video-overlay');
            const playButton = videoContainer.querySelector('.play-button');

            if (!video || !videoOverlay || !playButton) return;

            // Function to play video
            function playVideo() {
                video.play().then(() => {
                    videoOverlay.style.display = 'none';
                    video.muted = false; // Unmute when user clicks play
                }).catch(error => {
                    console.log('Video play failed:', error);
                    // Fallback: show overlay with error message
                    videoOverlay.style.display = 'flex';
                    playButton.innerHTML = '<i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>';
                });
            }

            // Function to pause video
            function pauseVideo() {
                video.pause();
                videoOverlay.style.display = 'flex';
            }

            // Click event for play button
            playButton.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                playVideo();
            });

            // Click event for video overlay
            videoOverlay.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                playVideo();
            });

            // Video event listeners
            video.addEventListener('play', function() {
                videoOverlay.style.display = 'none';
            });

            video.addEventListener('pause', function() {
                videoOverlay.style.display = 'flex';
            });

            video.addEventListener('ended', function() {
                videoOverlay.style.display = 'flex';
                playButton.innerHTML = '<i class="fas fa-redo text-pink-600 text-2xl"></i>';
            });

            // Keyboard accessibility
            video.addEventListener('keydown', function(e) {
                if (e.code === 'Space' || e.code === 'Enter') {
                    e.preventDefault();
                    if (video.paused) {
                        playVideo();
                    } else {
                        pauseVideo();
                    }
                }
            });

            // Video loading optimization
            video.addEventListener('loadstart', function() {
                videoContainer.classList.add('loading');
            });

            video.addEventListener('canplay', function() {
                videoContainer.classList.remove('loading');
            });

            // Intersection Observer for lazy loading video
            const videoObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        // Preload video when it comes into view
                        video.preload = 'metadata';
                        videoObserver.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '50px'
            });

            videoObserver.observe(videoContainer);

            // Performance optimization: pause video when not visible
            const visibilityObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (!entry.isIntersecting && !video.paused) {
                        video.pause();
                    }
                });
            }, {
                threshold: 0.1
            });

            visibilityObserver.observe(video);
        }

        // Statistics Counter Animation
        function initializeStatisticsCounter() {
            const observerOptions = {
                threshold: 0.3,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const statNumbers = entry.target.querySelectorAll('.statistic-number');
                        statNumbers.forEach((statNumber, index) => {
                            setTimeout(() => {
                                animateStatisticsCounter(statNumber);
                            }, index * 200);
                        });
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            // Observe the statistics section
            const statisticsSection = document.querySelector('.statistics-section');
            if (statisticsSection) {
                observer.observe(statisticsSection);
            }
        }

        // Statistics Counter animation function
        function animateStatisticsCounter(element) {
            const target = parseInt(element.getAttribute('data-target'));
            const duration = 2000;
            const step = target / (duration / 16);
            let current = 0;

            // Add visible class for CSS animation
            element.classList.add('visible');

            const timer = setInterval(() => {
                current += step;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                
                // Format the number with commas
                const formattedNumber = Math.floor(current).toLocaleString();
                element.textContent = formattedNumber;
            }, 16);
        }

        // Statistics Video Optimization
        function initializeStatisticsVideo() {
            const statisticsVideo = document.querySelector('.statistics-video');
            if (!statisticsVideo) return;

            // Optimize video loading
            statisticsVideo.addEventListener('loadstart', function() {
                console.log('Statistics video loading started');
            });

            statisticsVideo.addEventListener('canplay', function() {
                console.log('Statistics video can play');
            });

            statisticsVideo.addEventListener('error', function(e) {
                console.error('Statistics video error:', e);
                // Fallback: hide video and show static background
                const videoBackground = document.querySelector('.statistics-video-background');
                if (videoBackground) {
                    videoBackground.style.display = 'none';
                }
            });

            // Pause video when not visible for performance
            const videoObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (!entry.isIntersecting) {
                        statisticsVideo.pause();
                    } else {
                        statisticsVideo.play().catch(e => {
                            console.log('Video autoplay failed:', e);
                        });
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '50px'
            });

            videoObserver.observe(statisticsVideo);

            // Mobile optimization
            if (window.innerWidth <= 768) {
                statisticsVideo.setAttribute('playsinline', '');
                statisticsVideo.setAttribute('muted', '');
            }
        }

        // Typewriter Effect Function
        function initializeTypewriter() {
            const typewriterText = document.querySelector('.typewriter-text');
            if (!typewriterText) return;

            // Array of messages to cycle through
            const messages = [
                "دلّل أليفك نظافة. راحة. حبّ... بخدمة توصلك لباب البيت",
                "في {{ config('brand.name') }}... نحول الحيوان من عادي إلى مَلَكي 👑 وانت مرتاح في بيتك",
                "احجز الآن... وخلي أليفك يطلع من الغسيل كأنه نجم مشهور",
                "أول ما يشم أليفك صابون {{ config('brand.name') }}... يعرف إن الدلع بدأ"
            ];
            
            let currentMessageIndex = 0;
            let index = 0;
            let isDeleting = false;
            let currentText = '';
            const typeSpeed = 70; // Speed for typing
            const deleteSpeed = 40; // Speed for deleting
            const pauseTime = 2500; // Pause time at the end
            
            // Clear initial content
            typewriterText.innerHTML = '';
            
            // Add cursor
            const cursorElement = document.createElement('span');
            cursorElement.className = 'typewriter-cursor';
            cursorElement.textContent = '|';
            typewriterText.appendChild(cursorElement);
            
            // Add typing sound effect (visual feedback)
            function addTypingEffect() {
                typewriterText.style.transform = 'scale(1.02)';
                setTimeout(() => {
                    typewriterText.style.transform = 'scale(1)';
                }, 100);
            }
            
            function typeWriter() {
                const currentMessage = messages[currentMessageIndex];
                const currentSpeed = isDeleting ? deleteSpeed : typeSpeed;
                
                if (!isDeleting && index < currentMessage.length) {
                    // Typing mode
                    currentText = currentMessage.substring(0, index + 1);
                    typewriterText.textContent = currentText;
                    typewriterText.appendChild(cursorElement);
                    addTypingEffect();
                    index++;
                    
                    // Random speed variation for more natural typing
                    const randomDelay = currentSpeed + Math.random() * 30 - 15;
                    setTimeout(typeWriter, randomDelay);
                } else if (isDeleting && index > 0) {
                    // Deleting mode
                    currentText = currentMessage.substring(0, index - 1);
                    typewriterText.textContent = currentText;
                    typewriterText.appendChild(cursorElement);
                    index--;
                    
                    // Random speed variation for deleting
                    const randomDelay = currentSpeed + Math.random() * 20 - 10;
                    setTimeout(typeWriter, randomDelay);
                } else {
                    // Switch modes
                    isDeleting = !isDeleting;
                    
                    if (!isDeleting) {
                        // Move to next message
                        currentMessageIndex = (currentMessageIndex + 1) % messages.length;
                        index = 0;
                        // Starting to type again - add a small pause
                        setTimeout(typeWriter, 800);
                    } else {
                        // Starting to delete, pause first
                        setTimeout(typeWriter, pauseTime);
                    }
                }
            }
            
            // Start typing after a short delay
            setTimeout(typeWriter, 1200);
        }

        // Initialize video functionality when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            initializeVideoFunctionality();
            initializeStatisticsVideo();
        });
    </script>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-gray-800 text-white fixed top-0 left-0 right-0 z-50 shadow-2xl border-b-4 border-orange-300/60">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-18 lg:h-20">
                <!-- Logo Section -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 hover:opacity-80 transition-all duration-300 cursor-pointer group" title="{{ config('brand.name') }} - الصفحة الرئيسية">
                        <div class="bg-white/20 p-2 rounded-full border-2 border-orange-300/40 group-hover:border-orange-300/80 transition-all duration-300 shadow-lg">
                            <img src="{{ url('/logo.php') }}" alt="{{ config('brand.name') }} - خدمات العناية بالحيوانات الأليفة في الرياض" title="{{ config('brand.name') }} - أفضل خدمات العناية بالحيوانات الأليفة" class="w-8 h-8 lg:w-10 lg:h-10 object-contain" loading="eager" fetchpriority="high">
                            <i class="fas fa-paw text-white text-xl" style="display: none;"></i>
                        </div>
                        <div class="flex flex-col">
                            <h1 class="text-lg lg:text-2xl font-bold">{{ config('brand.name') }} <span class="inline-flex items-center bg-gradient-to-r from-orange-400/40 to-pink-400/40 backdrop-blur-sm px-3 py-1 rounded-full text-sm font-medium text-white border border-orange-300/50 shadow-lg">(Grooming)</span></h1>
                            <span class="text-xs lg:text-sm text-pink-200 opacity-90">خدمات العناية بالحيوانات الأليفة</span>
                        </div>
                    </a>
                </div>
                
                <!-- Center Navigation -->
                <nav class="hidden md:flex items-center gap-4 lg:gap-6 absolute left-1/2 transform -translate-x-1/2">
                    <a href="{{ route('home') }}" class="nav-link hover:text-orange-200 transition-all duration-300 relative group" title="الصفحة الرئيسية - {{ config('brand.name') }}">
                        <span class="relative z-10">الرئيسية</span>
                        <div class="absolute inset-0 bg-gradient-to-r from-orange-400/20 to-pink-400/20 rounded-xl opacity-0 group-hover:opacity-100 transition-all duration-300"></div>
                    </a>
                    <a href="{{ route('products') }}" class="nav-link hover:text-orange-200 transition-all duration-300 relative group" title="خدمات العناية بالحيوانات الأليفة - {{ config('brand.name') }}">
                        <span class="relative z-10">المنتجات</span>
                        <div class="absolute inset-0 bg-gradient-to-r from-orange-400/20 to-pink-400/20 rounded-xl opacity-0 group-hover:opacity-100 transition-all duration-300"></div>
                    </a>
                </nav>
                
                <!-- Right Section -->
                <div class="hidden md:flex items-center gap-3 lg:gap-4">
                                <!-- Customer Profile Link -->
            @if(session('customer_id'))
                <a href="{{ route('frontend.customer.profile') }}" class="hover:text-orange-200 transition-all duration-300 p-2 rounded-lg hover:bg-white/10 group" title="ملفي الشخصي">
                    <i class="fas fa-user text-lg lg:text-xl"></i>
                </a>
            @else
                <a href="{{ route('frontend.customer.login') }}" class="hover:text-orange-200 transition-all duration-300 p-2 rounded-lg hover:bg-white/10 group" title="تسجيل الدخول">
                    <i class="fas fa-sign-in-alt text-lg lg:text-xl"></i>
                </a>
            @endif
            
            <!-- Cart Icon -->
            <button id="cart-btn" class="relative hover:text-orange-200 transition-all duration-300 p-2 rounded-lg hover:bg-white/10 group" title="سلة المشتريات">
                <i class="fas fa-shopping-cart text-lg lg:text-xl"></i>
                <div class="absolute -top-1 -right-1 w-5 h-5 bg-orange-400 rounded-full flex items-center justify-center text-xs font-bold text-white opacity-0 group-hover:opacity-100 transition-all duration-300">0</div>
            </button>
                    
                    <a href="https://catshoop1.com/" target="_blank" rel="noopener noreferrer" class="bg-gradient-to-r from-orange-400/30 to-pink-400/30 backdrop-blur-sm px-4 py-2 rounded-xl hover:from-orange-400/50 hover:to-pink-400/50 transition-all duration-300 border border-orange-300/40 hover:border-orange-300/80 shadow-lg" title="متجرنا الإلكتروني - {{ config('brand.name') }}">
                        <i class="fas fa-shopping-bag ml-1"></i>
                        متجرنا الإلكتروني
                    </a>
                </div>
                
                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="md:hidden bg-gradient-to-r from-orange-400/30 to-pink-400/30 p-2 rounded-lg hover:from-orange-400/50 hover:to-pink-400/50 transition-all duration-300 border border-orange-300/40 hover:border-orange-300/80 shadow-lg">
                    <i class="fas fa-bars text-lg"></i>
                </button>
            </div>
            
            <!-- Mobile Navigation -->
            <div id="mobile-menu" class="md:hidden hidden bg-gradient-to-b from-white/15 to-white/5 backdrop-blur-xl rounded-xl mt-3 p-4 border border-orange-300/30 shadow-2xl">
                <nav class="flex flex-col gap-3">
                    <a href="{{ route('home') }}" class="text-white hover:text-orange-200 transition-all duration-300 py-3 px-4 rounded-xl hover:bg-gradient-to-r hover:from-orange-400/20 hover:to-pink-400/20 border border-transparent hover:border-orange-300/40 flex items-center" title="الصفحة الرئيسية - {{ config('brand.name') }}">
                        <i class="fas fa-home ml-3 text-orange-300"></i>
                        الرئيسية
                    </a>
                    <a href="{{ route('products') }}" class="text-white hover:text-orange-200 transition-all duration-300 py-3 px-4 rounded-xl hover:bg-gradient-to-r hover:from-orange-400/20 hover:to-pink-400/20 border border-transparent hover:border-orange-300/40 flex items-center" title="خدمات العناية بالحيوانات الأليفة - {{ config('brand.name') }}">
                        <i class="fas fa-box ml-3 text-orange-300"></i>
                        المنتجات
                    </a>
                    
                    <!-- Mobile Customer Profile Link -->
                    @if(session('customer_id'))
                        <a href="{{ route('frontend.customer.profile') }}" class="text-white hover:text-orange-200 transition-all duration-300 py-3 px-4 rounded-xl hover:bg-gradient-to-r hover:from-orange-400/20 hover:to-pink-400/20 border border-transparent hover:border-orange-300/40 flex items-center" title="ملفي الشخصي">
                            <i class="fas fa-user ml-3 text-orange-300"></i>
                            ملفي الشخصي
                        </a>
                    @else
                        <a href="{{ route('frontend.customer.login') }}" class="text-white hover:text-orange-200 transition-all duration-300 py-3 px-4 rounded-xl hover:bg-gradient-to-r hover:from-orange-400/20 hover:to-pink-400/20 border border-transparent hover:border-orange-300/40 flex items-center" title="تسجيل الدخول">
                            <i class="fas fa-sign-in-alt ml-3 text-orange-300"></i>
                            تسجيل الدخول
                        </a>
                    @endif
                    
                    <!-- Mobile Cart Button -->
                    <button id="mobile-cart-btn" class="text-white hover:text-orange-200 transition-all duration-300 py-3 px-4 rounded-xl hover:bg-gradient-to-r hover:from-orange-400/20 hover:to-pink-400/20 border border-transparent hover:border-orange-300/40 flex items-center justify-between" title="سلة المشتريات">
                        <span class="flex items-center">
                            <i class="fas fa-shopping-cart ml-3 text-orange-300"></i>
                            سلة المشتريات
                        </span>
                        <span class="bg-orange-400 text-white text-xs font-bold px-2 py-1 rounded-full">0</span>
                    </button>
                    
                    <a href="https://catshoop1.com/" target="_blank" rel="noopener noreferrer" class="bg-gradient-to-r from-orange-400/40 to-pink-400/40 text-white hover:from-orange-400/60 hover:to-pink-400/60 transition-all duration-300 py-3 px-4 rounded-xl border border-orange-300/50 hover:border-orange-300/80 shadow-lg flex items-center" title="متجرنا الإلكتروني - {{ config('brand.name') }}">
                        <i class="fas fa-shopping-bag ml-3"></i>
                        متجرنا الإلكتروني
                    </a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="pt-16">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="container mx-auto px-4">
            <!-- Footer Content Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
                
                <!-- Company Info Section -->
                <div class="lg:col-span-2">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="bg-white/20 p-2 rounded-full">
                            <img src="{{ url('/logo.php') }}" alt="{{ config('brand.name') }}" class="w-8 h-8 object-contain">
                            <i class="fas fa-paw text-white text-xl" style="display: none;"></i>
                        </div>
                        <h3 class="text-xl font-bold">{{ config('brand.name') }} (Grooming)</h3>
                    </div>
                    <p class="text-gray-300 text-sm leading-relaxed mb-4">
                        خدمات العناية المتنقلة بالحيوانات الأليفة! نحن نقدم تجربة فريدة ومريحة لرعاية حيواناتكم الأليفة مباشرة عند باب منزلكم. هدفنا هو توفير أعلى مستوى من الرعاية والراحة لحيواناتكم الأليفة، مع ضمان راحتكم ورضاكم التام.
                    </p>
                    

                </div>
                
                <!-- Quick Links Section -->
                <div>
                    <h4 class="text-lg font-semibold mb-4 text-pink-400">روابط سريعة</h4>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('home') }}" class="text-gray-300 hover:text-pink-400 transition-colors text-sm flex items-center gap-2">
                                <i class="fas fa-home text-xs"></i>
                                الرئيسية
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('products') }}" class="text-gray-300 hover:text-pink-400 transition-colors text-sm flex items-center gap-2">
                                <i class="fas fa-box text-xs"></i>
                                خدماتنا
                            </a>
                        </li>
                        <li>
                            <a href="https://catshoop1.com/" target="_blank" rel="noopener noreferrer" class="text-gray-300 hover:text-pink-400 transition-colors text-sm flex items-center gap-2">
                                <i class="fas fa-shopping-bag text-xs"></i>
                                متجرنا الإلكتروني
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- Social Media Section -->
                <div>
                    <h4 class="text-lg font-semibold mb-4 text-pink-400">تابعنا</h4>
                    <div class="flex flex-wrap gap-3">
                        <a href="https://www.instagram.com/cat__shoop?igsh=MWZxOWE4bTVwZG51Zg==" target="_blank" rel="noopener noreferrer" class="bg-gradient-to-r from-pink-600 to-purple-600 p-3 rounded-full hover:scale-110 transition-transform duration-300" title="تابعنا على انستجرام">
                            <i class="fab fa-instagram text-white text-lg"></i>
                        </a>
                        <a href="https://www.snapchat.com/add/cat.shoop" target="_blank" rel="noopener noreferrer" class="bg-gradient-to-r from-pink-600 to-purple-600 p-3 rounded-full hover:scale-110 transition-transform duration-300" title="تابعنا على سناب شات">
                            <i class="fab fa-snapchat-ghost text-white text-lg"></i>
                        </a>
                        <a href="https://www.tiktok.com/@cat__shoop?_t=ZS-8yAQLyhQPvs&_r=1" target="_blank" rel="noopener noreferrer" class="bg-gradient-to-r from-pink-600 to-purple-600 p-3 rounded-full hover:scale-110 transition-transform duration-300" title="تابعنا على تيك توك">
                            <i class="fab fa-tiktok text-white text-lg"></i>
                        </a>
                        <a href="https://x.com/Cat__shoop?t=68RfPCLDhe95Nhlv8O6Pkw&s=09" target="_blank" rel="noopener noreferrer" class="bg-gradient-to-r from-pink-600 to-purple-600 p-3 rounded-full hover:scale-110 transition-transform duration-300" title="تابعنا على X">
                            <i class="fab fa-x-twitter text-white text-lg"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Apps Section -->
            <div class="border-t border-gray-700 pt-8">
                <div class="text-center mb-6">
                    <h4 class="text-lg font-semibold text-pink-400 mb-2">حمل تطبيقنا</h4>
                    <p class="text-gray-300 text-sm">احصل على تجربة أفضل مع تطبيقنا المخصص</p>
                </div>
                
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-8">
                    <!-- Apple App Store -->
                    <a href="https://apps.apple.com/us/app/%D9%83%D8%A7%D8%AA-%D8%B4%D9%88%D8%A8/id1629676474" target="_blank" rel="noopener noreferrer" class="bg-black text-white px-6 py-3 rounded-lg hover:bg-gray-900 transition-colors flex items-center gap-3 group">
                        <div class="text-2xl">
                            <i class="fab fa-apple"></i>
                        </div>
                        <div class="text-right">
                            <div class="text-xs text-gray-400">حمل من</div>
                            <div class="text-sm font-semibold">App Store</div>
                        </div>
                    </a>
                    
                    <!-- Google Play Store -->
                    <a href="https://play.google.com/store/apps/details?id=com.appsbunches.catshoop" target="_blank" rel="noopener noreferrer" class="bg-black text-white px-6 py-3 rounded-lg hover:bg-gray-900 transition-colors flex items-center gap-3 group">
                        <div class="text-2xl">
                            <i class="fab fa-google-play"></i>
                        </div>
                        <div class="text-right">
                            <div class="text-xs text-gray-400">حمل من</div>
                            <div class="text-sm font-semibold">Google Play</div>
                        </div>
                    </a>
                </div>
            </div>
            
            <!-- Copyright Section -->
            <div class="border-t border-gray-700 pt-6">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="text-gray-400 text-sm text-center md:text-right">
                        <p>جميع الحقوق محفوظة مؤسسة {{ config('brand.name') }} (Grooming) © 2025</p>
                    </div>
                    <div class="flex items-center gap-1 text-sm">
                        <span class="text-gray-400">صنع بكل</span>
                        <svg class="w-6 h-6 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        <span class="text-gray-400">في مؤسسة {{ config('brand.name') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Cart Modal -->
    <div id="cart-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 max-h-[80vh] overflow-hidden">
                <!-- Cart Header -->
                <div class="bg-gradient-to-r from-pink-600 to-purple-600 text-white p-4 flex items-center justify-between">
                    <h3 class="text-lg font-bold">سلة المشتريات</h3>
                    <button id="close-cart" class="text-white hover:text-pink-200 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <!-- Cart Content -->
                <div class="p-4">
                    <div id="cart-items" class="space-y-4">
                        <!-- Empty Cart Message -->
                        <div id="empty-cart" class="text-center py-8">
                            <i class="fas fa-shopping-cart text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">السلة فارغة</p>
                            <p class="text-gray-400 text-sm">اختر منتجات من قائمة الخدمات</p>
                        </div>
                    </div>
                </div>
                
                                        <!-- Cart Footer -->
                        <div class="border-t border-gray-200 p-4">
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-gray-800">المجموع:</span>
                                <span id="cart-total" class="font-bold text-pink-600">0.00 <span class="icon-saudi_riyal"></span></span>
                            </div>
                        </div>
            </div>
        </div>
    </div>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    
    <!-- تحسينات الأداء -->
    <script>
        // تحسين تحميل الصور
        document.addEventListener('DOMContentLoaded', function() {
            // تحميل الصور مسبقاً
            const bannerImg = new Image();
            bannerImg.src = '/banar.php';
            
            // تحسين الأداء للجوال
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register('/sw.js').catch(function(err) {
                    console.log('ServiceWorker registration failed: ', err);
                });
            }
        });
        
        // تحسين الأداء عند التمرير
        let ticking = false;
        function updatePerformance() {
            ticking = false;
        }
        
        function requestTick() {
            if (!ticking) {
                requestAnimationFrame(updatePerformance);
                ticking = true;
            }
        }
        
        window.addEventListener('scroll', requestTick);
    </script>

    @include('layouts.chatbot-widget')

</body>
</html> 