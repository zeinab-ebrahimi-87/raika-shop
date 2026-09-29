document.addEventListener('DOMContentLoaded', function() {
    var hamburger = document.getElementById('hamburger');
    var navMenu = document.getElementById('navMenu');
    var overlay = document.getElementById('mobileOverlay');
    
    if (hamburger && navMenu && overlay) {
        hamburger.addEventListener('click', function() {
            navMenu.classList.toggle('active');
            overlay.classList.toggle('active');
        });
        
        overlay.addEventListener('click', function() {
            navMenu.classList.remove('active');
            overlay.classList.remove('active');
        });
        
        navMenu.querySelectorAll('a').forEach(function(link) {
            link.addEventListener('click', function() {
                navMenu.classList.remove('active');
                overlay.classList.remove('active');
            });
        });
    }
});



// ============ Back to Top ============
(function() {
    var backToTop = document.getElementById('backToTop');
    
    if (!backToTop) return;
    
    // کلیک → برو بالا
    backToTop.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
        return false;
    });
    
    // نمایش/مخفی کردن
    function toggleButton() {
        var scrollTop = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;
        
        if (scrollTop > 200) {
            backToTop.classList.add('show');
        } else {
            backToTop.classList.remove('show');
        }
    }
    
    window.addEventListener('scroll', toggleButton, { passive: true });
    window.addEventListener('load', toggleButton);
    toggleButton();
})();


// Loading روی دکمه‌های submit
document.querySelectorAll('form').forEach(function(form) {
    form.addEventListener('submit', function() {
        var submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn && !submitBtn.disabled) {
            submitBtn.classList.add('loading');
            submitBtn.disabled = true;
        }
    });
});

// ============================================
// جستجوی زنده
// ============================================

(function() {
    var searchToggle = document.getElementById('searchToggle');
    var searchPanel = document.getElementById('searchPanel');
    var searchOverlay = document.getElementById('searchOverlay');
    var searchClose = document.getElementById('searchClose');
    var searchInput = document.getElementById('searchInput');
    var searchResults = document.getElementById('searchResults');
    
    if (!searchToggle || !searchPanel) return;
    
    var searchTimeout;
    
    // باز کردن
    searchToggle.addEventListener('click', function() {
        searchPanel.classList.add('open');
        searchOverlay.classList.add('open');
        setTimeout(function() {
            searchInput.focus();
        }, 400);
    });
    
    // بستن
    function closeSearch() {
        searchPanel.classList.remove('open');
        searchOverlay.classList.remove('open');
        searchInput.value = '';
        searchResults.innerHTML = '';
    }
    
    searchClose.addEventListener('click', closeSearch);
    searchOverlay.addEventListener('click', closeSearch);
    
    // ESC بستن
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeSearch();
        }
    });
    
    // تایپ → جستجو
    searchInput.addEventListener('input', function() {
        var q = this.value.trim();
        
        clearTimeout(searchTimeout);
        
        if (q.length < 2) {
            searchResults.innerHTML = '';
            return;
        }
        
        searchResults.innerHTML = '<div class="search-loading">در حال جستجو</div>';
        
        searchTimeout = setTimeout(function() {
            fetch('/search?q=' + encodeURIComponent(q))
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    if (data.length === 0) {
                        searchResults.innerHTML = 
                            '<div class="search-empty">' +
                            '<div class="search-empty-icon">🔍</div>' +
                            '<p>محصولی پیدا نشد</p>' +
                            '</div>';
                        return;
                    }
                    
                    var html = '';
                    data.forEach(function(item) {
                        html += '<a href="' + item.url + '" class="search-result-item">' +
                            '<div class="search-result-image">' +
                            '<img src="' + item.image + '" alt="' + item.name + '">' +
                            '</div>' +
                            '<div class="search-result-info">' +
                            '<div class="search-result-name">' + item.name + '</div>' +
                            '<div class="search-result-price">' + item.price + '</div>' +
                            '</div>' +
                            '</a>';
                    });
                    
                    searchResults.innerHTML = html;
                })
                .catch(function() {
                    searchResults.innerHTML = '<div class="search-empty">خطا در جستجو</div>';
                });
        }, 300);
    });
})();

// ============================================
// انیمیشن کاکائو روی دسته‌بندی محصولات
// ============================================

(function() {
    var categories = document.querySelectorAll('.category-item');
    
    categories.forEach(function(category) {
        category.addEventListener('mouseenter', function() {
            createCocoaParticles(this);
        });
    });
    
    function createCocoaParticles(element) {
        var container = element.querySelector('.cocoa-particles');
        if (!container) return;
        
        // پاک کردن ذرات قبلی
        container.innerHTML = '';
        
        // تعداد ذرات
        var particleCount = 12;
        
        for (var i = 0; i < particleCount; i++) {
            var particle = document.createElement('span');
            particle.className = 'cocoa-particle';
            
            // موقعیت شروع (مرکز یا لبه‌ها)
            var startX = Math.random() * 100;
            var startY = Math.random() * 100;
            
            particle.style.left = startX + '%';
            particle.style.top = startY + '%';
            
            // موقعیت نهایی (به سمت بیرون یا بالا)
            var endX = (Math.random() - 0.5) * 100;  // -50 تا 50
            var endY = -(Math.random() * 80 + 20);   // به سمت بالا
            particle.style.setProperty('--x', endX + 'px');
            particle.style.setProperty('--y', endY + 'px');
            
            // تأخیر انیمیشن
            particle.style.animationDelay = (Math.random() * 0.2) + 's';
            
            // اندازه
            var size = 4 + Math.random() * 6;
            particle.style.width = size + 'px';
            particle.style.height = size + 'px';
            
            container.appendChild(particle);
            
            // پاک کردن بعد از انیمیشن
            (function(p) {
                setTimeout(function() {
                    if (p.parentNode) {
                        p.parentNode.removeChild(p);
                    }
                }, 2500);
            })(particle);
        }
    }
})();

// ============================================
// Toast Notifications
// ============================================

(function() {
    // ساخت کانتینر
    var container = document.createElement('div');
    container.className = 'toast-container';
    container.id = 'toastContainer';
    document.body.appendChild(container);

    // تابع اصلی
    window.showToast = function(type, title, message, duration) {
        duration = duration || 5000;
        
        var icons = {
            'success': '✅',
            'error': '❌',
            'warning': '⚠️',
            'info': 'ℹ️'
        };
        
        var toast = document.createElement('div');
        toast.className = 'toast toast-' + type;
        
        toast.innerHTML = 
            '<div class="toast-icon">' + (icons[type] || 'ℹ️') + '</div>' +
            '<div class="toast-content">' +
                (title ? '<div class="toast-title">' + title + '</div>' : '') +
                '<div class="toast-message">' + message + '</div>' +
            '</div>' +
            '<button class="toast-close" type="button">✕</button>';
        
        container.appendChild(toast);
        
        // بستن
        var timeout = setTimeout(function() {
            removeToast(toast);
        }, duration);
        
        // دکمه بستن
        toast.querySelector('.toast-close').addEventListener('click', function() {
            clearTimeout(timeout);
            removeToast(toast);
        });
        
        return toast;
    };

    function removeToast(toast) {
        toast.classList.add('removing');
        setTimeout(function() {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 400);
    }
})();