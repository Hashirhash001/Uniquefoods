/**
 * ================================================
 * AUTO-HIDE HEADER - FINAL FIX
 * NO WHITESPACE when header is hidden
 * ================================================
 */

(function() {
    'use strict';

    const AutoHideHeader = {
        lastScrollTop: 0,
        scrollThreshold: 100,
        delta: 5,
        header: null,
        headerHeight: 0,
        isScrolling: false,

        init() {
            this.header = document.querySelector('.unique-modern-header') ||
                         document.querySelector('.unique-mobile-header');

            if (!this.header) return;

            this.calculateHeaderHeight();
            this.bindEvents();

            window.addEventListener('resize', () => {
                this.calculateHeaderHeight();
            });
        },

        calculateHeaderHeight() {
            this.headerHeight = this.header.offsetHeight;
        },

        bindEvents() {
            let scrollTimeout;

            window.addEventListener('scroll', () => {
                if (this.isScrolling) return;

                clearTimeout(scrollTimeout);

                scrollTimeout = setTimeout(() => {
                    this.handleScroll();
                }, 10);
            }, { passive: true });
        },

        handleScroll() {
            const currentScrollTop = window.pageYOffset || document.documentElement.scrollTop;

            if (Math.abs(this.lastScrollTop - currentScrollTop) <= this.delta) {
                return;
            }

            // At top - show header and add padding
            if (currentScrollTop <= 0) {
                this.showHeader();
                this.header.classList.remove('header-compact');
                document.body.classList.remove('header-is-hidden');
                this.lastScrollTop = currentScrollTop;
                return;
            }

            // Scrolling down - hide header and remove padding
            if (currentScrollTop > this.lastScrollTop && currentScrollTop > this.scrollThreshold) {
                this.hideHeader();
                document.body.classList.add('header-is-hidden');
            }
            // Scrolling up - show header and add padding
            else if (currentScrollTop < this.lastScrollTop) {
                this.showHeader();
                document.body.classList.remove('header-is-hidden');
            }

            // Compact mode
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

    // Initialize
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => AutoHideHeader.init());
    } else {
        AutoHideHeader.init();
    }

    window.AutoHideHeader = AutoHideHeader;

})();
