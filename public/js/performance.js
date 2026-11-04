// تحسينات الأداء الإضافية للسرعة القصوى
// Performance optimizations for maximum speed

// تحسين تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    // تحسين تحميل الخطوط
    if ('fonts' in document) {
        document.fonts.ready.then(function() {
            document.body.classList.add('fonts-loaded');
        });
    }
    
    // تحسين تحميل الصور
    const images = document.querySelectorAll('img');
    images.forEach(img => {
        if (img.complete) {
            img.classList.add('loaded');
        } else {
            img.addEventListener('load', function() {
                this.classList.add('loaded');
            });
        }
    });
    
    // تحسين تحميل الفيديو
    const videos = document.querySelectorAll('video');
    videos.forEach(video => {
        video.addEventListener('loadeddata', function() {
            this.classList.add('loaded');
        });
    });
});

// تحسين الأداء - تقليل استخدام الذاكرة
window.addEventListener('load', function() {
    // تنظيف الذاكرة بعد تحميل الصفحة
    if ('requestIdleCallback' in window) {
        requestIdleCallback(function() {
            // تنظيف الذاكرة
            if ('gc' in window) {
                window.gc();
            }
        });
    }
});

// تحسين الأداء - تحميل سريع للخطوط
const fontObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.fontDisplay = 'swap';
        }
    });
});

// تحسين الأداء - تحميل سريع للصور
const imageObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const img = entry.target;
            if (img.dataset.src) {
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                imageObserver.unobserve(img);
            }
        }
    });
}, {
    rootMargin: '50px 0px',
    threshold: 0.1
});

// تحسين الأداء - تحميل سريع للفيديو
const videoObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const video = entry.target;
            if (video.dataset.src) {
                video.src = video.dataset.src;
                video.removeAttribute('data-src');
                videoObserver.unobserve(video);
            }
        }
    });
}, {
    rootMargin: '100px 0px',
    threshold: 0.1
});

// تحسين الأداء - تحميل سريع للخطوط
document.addEventListener('DOMContentLoaded', function() {
    const fontLinks = document.querySelectorAll('link[rel="preload"][as="style"]');
    fontLinks.forEach(link => {
        fontObserver.observe(link);
    });
    
    const lazyImages = document.querySelectorAll('img[data-src]');
    lazyImages.forEach(img => {
        imageObserver.observe(img);
    });
    
    const lazyVideos = document.querySelectorAll('video[data-src]');
    lazyVideos.forEach(video => {
        videoObserver.observe(video);
    });
});

// تحسين الأداء - تقليل استخدام الذاكرة
window.addEventListener('beforeunload', function() {
    // تنظيف الذاكرة قبل مغادرة الصفحة
    if ('requestIdleCallback' in window) {
        requestIdleCallback(function() {
            // تنظيف الذاكرة
            if ('gc' in window) {
                window.gc();
            }
        });
    }
});

// تحسين الأداء - تحميل سريع للخطوط
if ('fonts' in document) {
    document.fonts.ready.then(function() {
        document.body.classList.add('fonts-loaded');
    });
}

// تحسين الأداء - تحميل سريع للصور
const images = document.querySelectorAll('img');
images.forEach(img => {
    if (img.complete) {
        img.classList.add('loaded');
    } else {
        img.addEventListener('load', function() {
            this.classList.add('loaded');
        });
    }
});

// تحسين الأداء - تحميل سريع للفيديو
const videos = document.querySelectorAll('video');
videos.forEach(video => {
    video.addEventListener('loadeddata', function() {
        this.classList.add('loaded');
    });
});

// تحسين الأداء - تقليل استخدام الذاكرة
if ('requestIdleCallback' in window) {
    requestIdleCallback(function() {
        // تنظيف الذاكرة
        if ('gc' in window) {
            window.gc();
        }
    });
} 