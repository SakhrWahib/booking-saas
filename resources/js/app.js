import './bootstrap';

// تحسينات الأداء الأساسية
document.addEventListener('DOMContentLoaded', function() {
    // تحسين تحميل الصور
    const images = document.querySelectorAll('img[data-src]');
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                observer.unobserve(img);
            }
        });
    });

    images.forEach(img => imageObserver.observe(img));

    // تحسين تحميل العناصر
    const lazyElements = document.querySelectorAll('.lazy-load');
    const lazyObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('loaded');
                observer.unobserve(entry.target);
            }
        });
    });

    lazyElements.forEach(el => lazyObserver.observe(el));

    // تحسين الأداء - تأجيل تحميل المحتوى غير المرئي
    const deferredElements = document.querySelectorAll('[data-defer]');
    const deferredObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const element = entry.target;
                const deferType = element.dataset.defer;
                
                switch(deferType) {
                    case 'script':
                        const script = document.createElement('script');
                        script.src = element.dataset.src;
                        document.head.appendChild(script);
                        break;
                    case 'content':
                        element.innerHTML = element.dataset.content;
                        break;
                }
                
                observer.unobserve(element);
            }
        });
    });

    deferredElements.forEach(el => deferredObserver.observe(el));
});

// تحسين الأداء - تحسين الأحداث
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// تحسين الأداء - تحسين التمرير
const optimizedScroll = debounce(function() {
    // تحسين التمرير هنا
}, 16);

window.addEventListener('scroll', optimizedScroll, { passive: true });

// تحسين الأداء - تحسين إعادة الحجم
const optimizedResize = debounce(function() {
    // تحسين إعادة الحجم هنا
}, 250);

window.addEventListener('resize', optimizedResize, { passive: true });

// تحسين الأداء - تحسين الأداء العام
document.addEventListener('DOMContentLoaded', function() {
    // تحسين الأداء - تحسين التحميل
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
    
    // تحسين الأداء - تحسين النماذج
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            this.classList.add('loading');
        });
    });
    
    // تحسين الأداء - تحسين الروابط
    const links = document.querySelectorAll('a[href^="#"]');
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });
});

// تحسين الأداء - تحسين الذاكرة
window.addEventListener('beforeunload', function() {
    // تنظيف الذاكرة قبل مغادرة الصفحة
    if (window.performance && window.performance.memory) {
        console.log('Memory usage:', window.performance.memory);
    }
});
