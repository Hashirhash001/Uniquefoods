/**
 * ================================================
 * AUTO-HIDE HEADER (PROFESSIONAL UX)
 * Hides on scroll down, shows on scroll up
 * ================================================
 */

(function() {
    'use strict';

    const AutoHideHeader = {
        lastScrollTop: 0,
        scrollThreshold: 50, // Minimum scroll before hiding
        delta: 5, // Sensitivity
        header: null,
        isScrolling: false,

        init() {
            this.header = document.querySelector('.unique-modern-header') ||
                         document.querySelector('.unique-mobile-header');

            if (!this.header) return;

            this.bindEvents();
        },

        bindEvents() {
            let scrollTimeout;

            window.addEventListener('scroll', () => {
                if (this.isScrolling) return;

                clearTimeout(scrollTimeout);

                // Debounce scroll for performance
                scrollTimeout = setTimeout(() => {
                    this.handleScroll();
                }, 10);
            }, { passive: true });
        },

        handleScroll() {
            const currentScrollTop = window.pageYOffset || document.documentElement.scrollTop;

            // Make sure they scroll more than delta
            if (Math.abs(this.lastScrollTop - currentScrollTop) <= this.delta) {
                return;
            }

            // Scrolling down
            if (currentScrollTop > this.lastScrollTop && currentScrollTop > this.scrollThreshold) {
                this.hideHeader();
            }
            // Scrolling up
            else if (currentScrollTop < this.lastScrollTop) {
                this.showHeader();
            }

            // Add compact mode when scrolled past threshold
            if (currentScrollTop > this.scrollThreshold) {
                this.header.classList.add('header-compact');
            } else {
                this.header.classList.remove('header-compact');
            }

            this.lastScrollTop = currentScrollTop;
        },

        hideHeader() {
            this.header.classList.add('header-hidden');
            this.header.classList.remove('header-visible');
        },

        showHeader() {
            this.header.classList.remove('header-hidden');
            this.header.classList.add('header-visible');
        }
    };

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => AutoHideHeader.init());
    } else {
        AutoHideHeader.init();
    }

    // Make available globally
    window.AutoHideHeader = AutoHideHeader;

})();
