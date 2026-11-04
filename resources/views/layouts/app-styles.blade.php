@import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap');
body {
    font-family: 'Cairo', sans-serif;
}

/* Utility classes for RTL support */
.rtl\:text-right:where([dir="rtl"], [dir="rtl"] *) {
    text-align: right;
}
.rtl\:text-left:where([dir="rtl"], [dir="rtl"] *) {
    text-align: left;
}
.rtl\:mr-2:where([dir="rtl"], [dir="rtl"] *) {
    margin-right: 0.5rem;
}
.rtl\:ml-2:where([dir="rtl"], [dir="rtl"] *) {
    margin-left: 0.5rem;
}
.rtl\:pr-3:where([dir="rtl"], [dir="rtl"] *) {
    padding-right: 0.75rem;
}
.rtl\:pl-3:where([dir="rtl"], [dir="rtl"] *) {
    padding-left: 0.75rem;
}

/* Focus and hover states */
.focus\:ring-blue-500:focus {
    --tw-ring-color: rgb(59 130 246);
}
.focus\:border-blue-500:focus {
    --tw-border-opacity: 1;
    border-color: rgb(59 130 246);
}

/* Logo styles */
.nav-container img[alt="{{ config('brand.name_en') }} Logo"] {
    max-height: 32px;
    width: auto;
    object-fit: contain;
    filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.1)) brightness(0.9);
    transition: filter 0.3s ease;
    display: block !important;
}

.nav-container img[alt="{{ config('brand.name_en') }} Logo"]:hover {
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.15)) brightness(1);
}

/* Favicon styles */
link[rel="icon"],
link[rel="shortcut icon"],
link[rel="apple-touch-icon"] {
    display: block;
}

/* Mobile logo adjustments */
@media (max-width: 767px) {
    .nav-container img[alt="{{ config('brand.name_en') }} Logo"] {
        max-height: 28px;
    }
    
    .nav-container .text-lg {
        font-size: 0.875rem;
    }
}

/* Custom scrollbar */
.overflow-x-auto::-webkit-scrollbar {
    height: 8px;
}
.overflow-x-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
}
.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}
.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* RTL Specific Styles */
[dir="rtl"] .sidebar {
    right: 0;
    left: auto;
}

[dir="rtl"] .main-content {
    margin-right: 16rem; /* 64px */
    margin-left: 0;
}

/* LTR Specific Styles */
[dir="ltr"] .sidebar {
    left: 0;
    right: auto;
}

[dir="ltr"] .main-content {
    margin-left: 16rem; /* 64px */
    margin-right: 0;
}

[dir="rtl"] .space-x-reverse > :not([hidden]) ~ :not([hidden]) {
    --tw-space-x-reverse: 1;
}

[dir="ltr"] .space-x-reverse > :not([hidden]) ~ :not([hidden]) {
    --tw-space-x-reverse: 0;
}

/* Rest of your existing styles */
.modal {
    display: none;
}
.modal.show {
    display: block;
}

.language-btn {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    background: #EEF2FF;
    border-radius: 8px;
    color: #4F46E5;
    font-size: 0.875rem;
    transition: all 0.2s ease;
}

.language-btn:hover {
    background: #E0E7FF;
    transform: translateY(-1px);
}

.language-btn i {
    font-size: 14px;
}

.language-switcher {
    position: fixed;
    top: 20px;
    @if(App::getLocale() == 'ar')
        left: 20px;
    @else
        right: 20px;
    @endif
    z-index: 1000;
}

.lang-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 20px;
    color: #333;
    text-decoration: none;
    font-size: 14px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.lang-btn:hover {
    background: #f8f9fa;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    transform: translateY(-1px);
    text-decoration: none;
    color: #333;
}

.lang-btn i {
    font-size: 16px;
}

/* تعديل اتجاه الموقع حسب اللغة */
[dir="rtl"] .language-switcher {
    left: 20px;
    right: auto;
}

[dir="ltr"] .language-switcher {
    right: 20px;
    left: auto;
}

[dir="rtl"] .ml-2 {
    margin-left: 0.5rem;
}

[dir="ltr"] .ml-2 {
    margin-right: 0.5rem;
}

/* تمييز الإشعارات المقروءة */
.notification-item.read {
    opacity: 0.75;
    background-color: #f8f9fa;
}

.notification-item.read:hover {
    background-color: #e9ecef;
}

/* ===== RESPONSIVE DESIGN - PART 3: NOTIFICATIONS & DROPDOWNS ===== */

/* Mobile notifications dropdown */
@media (max-width: 767px) {
    /* Notifications dropdown mobile adjustments */
    .relative[x-data*="notifications"] .absolute {
        position: fixed !important;
        top: 4rem !important;
        left: 0.5rem !important;
        right: 0.5rem !important;
        width: auto !important;
        max-width: none !important;
        margin-top: 0 !important;
        z-index: 1001;
    }
    
    /* User menu dropdown mobile adjustments */
    .relative[x-data*="open: false"] .absolute {
        position: fixed !important;
        top: 4rem !important;
        left: 0.5rem !important;
        right: 0.5rem !important;
        width: auto !important;
        max-width: none !important;
        margin-top: 0 !important;
        z-index: 1001;
    }
    
    /* Language switcher mobile adjustments */
    .language-btn {
        padding: 0.5rem 0.75rem;
        font-size: 0.75rem;
    }
    
    .language-btn i {
        font-size: 12px;
    }
    
    /* Notification item mobile enhancements */
    .notification-item {
        padding: 0.75rem !important;
    }
    
    .notification-item .flex {
        gap: 0.5rem;
    }
    
    .notification-item p {
        font-size: 0.875rem;
        line-height: 1.4;
    }
    
    .notification-item .text-xs {
        font-size: 0.75rem;
    }
}

/* Tablet notifications adjustments */
@media (min-width: 768px) and (max-width: 1023px) {
    .relative[x-data*="notifications"] .absolute {
        width: 320px !important;
    }
    
    .relative[x-data*="open: false"] .absolute {
        width: 200px !important;
    }
    
    .notification-item {
        padding: 0.875rem !important;
    }
}

/* ===== RESPONSIVE DESIGN - PART 1: BASIC MEDIA QUERIES ===== */

/* Mobile First Approach - Base styles for mobile */
@media (max-width: 767px) {
    /* Sidebar adjustments for mobile */
    .sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s ease-in-out;
        z-index: 1000;
    }
    
    [dir="rtl"] .sidebar {
        transform: translateX(100%);
    }
    
    .sidebar.mobile-open {
        transform: translateX(0);
    }
    
    [dir="rtl"] .sidebar.mobile-open {
        transform: translateX(0);
    }
    
    /* Main content adjustments for mobile */
    .main-content {
        margin-left: 0 !important;
        margin-right: 0 !important;
        padding: 0.5rem;
    }
    
    /* Navigation adjustments for mobile */
    .nav-container {
        padding: 0 0.5rem;
    }
    
    .nav-container .flex {
        gap: 0.5rem;
    }
    
    /* Hide some elements on mobile */
    .nav-container .text-lg {
        font-size: 0.875rem;
    }
    
    .nav-container .gap-6 {
        gap: 0.5rem;
    }
    
    /* Mobile menu button */
    .mobile-menu-btn {
        display: block !important;
        background: none;
        border: none;
        color: #4F46E5;
        font-size: 1.25rem;
        padding: 0.5rem;
        cursor: pointer;
    }
    
    /* Overlay for mobile menu */
    .mobile-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 999;
    }
    
    .mobile-overlay.show {
        display: block;
    }
}

/* Tablet styles */
@media (min-width: 768px) and (max-width: 1023px) {
    .sidebar {
        width: 200px;
    }
    
    [dir="rtl"] .main-content {
        margin-right: 12.5rem;
    }
    
    [dir="ltr"] .main-content {
        margin-left: 12.5rem;
    }
    
    .mobile-menu-btn {
        display: none !important;
    }
}

/* Desktop styles */
@media (min-width: 1024px) {
    .mobile-menu-btn {
        display: none !important;
    }
    
    .mobile-overlay {
        display: none !important;
    }
}

/* ===== RESPONSIVE DESIGN - PART 3: SIDEBAR & NAVIGATION ENHANCEMENTS ===== */

/* Enhanced Sidebar styles for all devices */
.sidebar {
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: rgba(255, 255, 255, 0.3) transparent;
}

.sidebar::-webkit-scrollbar {
    width: 4px;
}

.sidebar::-webkit-scrollbar-track {
    background: transparent;
}

.sidebar::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.3);
    border-radius: 2px;
}

.sidebar::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.5);
}

/* Enhanced navigation items */
.sidebar nav ul li a {
    position: relative;
    overflow: hidden;
}

.sidebar nav ul li a::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
    transition: left 0.5s;
}

.sidebar nav ul li a:hover::before {
    left: 100%;
}

/* Mobile specific sidebar enhancements */
@media (max-width: 767px) {
    .sidebar {
        width: 280px;
        max-width: 85vw;
    }
    
    .sidebar nav {
        padding: 1rem 0.75rem;
    }
    
    .sidebar nav ul li a {
        padding: 0.75rem 1rem;
        margin-bottom: 0.25rem;
        border-radius: 0.5rem;
    }
    
    .sidebar nav ul li a span {
        font-size: 0.9rem;
    }
    
    /* Enhanced mobile menu button */
    .mobile-menu-btn {
        transition: all 0.3s ease;
        border-radius: 0.375rem;
    }
    
    .mobile-menu-btn:hover {
        background-color: rgba(79, 70, 229, 0.1);
        transform: scale(1.05);
    }
    
    .mobile-menu-btn:active {
        transform: scale(0.95);
    }
    
    /* Mobile overlay enhancements */
    .mobile-overlay {
        backdrop-filter: blur(2px);
        transition: opacity 0.3s ease;
    }
    
    /* Navigation bar mobile enhancements */
    .nav-container .flex-shrink-0 .text-lg {
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    /* Hide time/date on very small screens */
    @media (max-width: 480px) {
        .nav-container .text-sm {
            display: none;
        }
        
        .nav-container .gap-6 {
            gap: 0.25rem;
        }
    }
}

/* Tablet specific enhancements */
@media (min-width: 768px) and (max-width: 1023px) {
    .sidebar nav ul li a {
        padding: 0.625rem 0.75rem;
    }
    
    .sidebar nav ul li a span {
        font-size: 0.875rem;
    }
    
    .sidebar nav ul li a div {
        padding: 0.5rem;
    }
    
    /* Compact navigation for tablet */
    .nav-container .text-lg {
        font-size: 1rem;
    }
    
    .nav-container .gap-6 {
        gap: 1rem;
    }
}

/* Large screen enhancements */
@media (min-width: 1024px) {
    .sidebar nav ul li a:hover {
        transform: translateX(0.25rem);
    }
    
    [dir="rtl"] .sidebar nav ul li a:hover {
        transform: translateX(-0.25rem);
    }
    
    .sidebar nav ul li a {
        transition: all 0.3s ease;
    }
    
    .sidebar nav ul li a:hover div {
        transform: scale(1.1);
    }
}

/* ===== RESPONSIVE DESIGN - PART 4: FINAL ENHANCEMENTS & COMPATIBILITY ===== */

/* Touch device optimizations */
@media (hover: none) and (pointer: coarse) {
    .sidebar nav ul li a:hover {
        transform: none !important;
    }
    
    .sidebar nav ul li a:hover div {
        transform: none !important;
    }
    
    .mobile-menu-btn:hover {
        transform: none !important;
    }
    
    .language-btn:hover {
        transform: none !important;
    }
    
    /* Increase touch targets for mobile */
    .sidebar nav ul li a {
        min-height: 48px;
        display: flex;
        align-items: center;
    }
    
    .mobile-menu-btn {
        min-width: 44px;
        min-height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .language-btn {
        min-height: 44px;
        display: flex;
        align-items: center;
    }
}

/* Landscape mobile optimizations */
@media (max-width: 767px) and (orientation: landscape) {
    .nav-container {
        height: 3.5rem;
    }
    
    .sidebar {
        top: 3.5rem;
    }
    
    .main-content {
        padding-top: 3.5rem;
    }
    
    .nav-container .text-lg {
        font-size: 0.75rem;
    }
    
    .nav-container .gap-6 {
        gap: 0.25rem;
    }
}

/* High DPI display optimizations */
@media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
    .sidebar nav ul li a div {
        border-radius: 0.375rem;
    }
    
    .mobile-menu-btn {
        border-radius: 0.375rem;
    }
    
    .language-btn {
        border-radius: 0.5rem;
    }
}

/* Print styles */
@media print {
    .sidebar,
    .nav-container,
    .mobile-overlay,
    .mobile-menu-btn {
        display: none !important;
    }
    
    .main-content {
        margin: 0 !important;
        padding: 0 !important;
    }
}

/* Accessibility improvements */
@media (prefers-reduced-motion: reduce) {
    .sidebar,
    .sidebar nav ul li a,
    .mobile-menu-btn,
    .language-btn,
    .mobile-overlay {
        transition: none !important;
        animation: none !important;
    }
    
    .sidebar nav ul li a::before {
        display: none !important;
    }
}

/* Dark mode support (if needed in future) */
@media (prefers-color-scheme: dark) {
    /* Future dark mode styles can be added here */
}

/* Focus indicators for keyboard navigation */
.sidebar nav ul li a:focus,
.mobile-menu-btn:focus,
.language-btn:focus {
    outline: 2px solid #4F46E5;
    outline-offset: 2px;
}

/* Loading states */
.sidebar.loading {
    opacity: 0.7;
    pointer-events: none;
}

/* Error states */
.sidebar.error {
    border-left: 4px solid #ef4444;
}

/* Success states */
.sidebar.success {
    border-left: 4px solid #10b981;
}

/* Performance optimizations */
.sidebar {
    will-change: transform;
    backface-visibility: hidden;
}

.mobile-overlay {
    will-change: opacity;
}

/* Ensure proper stacking context */
.nav-container {
    z-index: 50;
}

.sidebar {
    z-index: 40;
}

.mobile-overlay {
    z-index: 30;
}

/* Final mobile adjustments for very small screens */
@media (max-width: 360px) {
    .nav-container .flex-shrink-0 .text-lg {
        max-width: 150px;
        font-size: 0.75rem;
    }
    
    .sidebar {
        width: 260px;
        max-width: 90vw;
    }
    
    .sidebar nav ul li a {
        padding: 0.5rem 0.75rem;
    }
    
    .sidebar nav ul li a span {
        font-size: 0.8rem;
    }
} 