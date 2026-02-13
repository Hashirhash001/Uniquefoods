/**
 * ================================================
 * AUTO-HIDE HEADER - FINAL FIX (No Bounce Issues)
 * ================================================
 */

(function() {
    'use strict';

    const AutoHideHeader = {
        lastScrollTop: 0,
        scrollThreshold: 100,
        delta: 10, // ✅ Increased from 5 to reduce sensitivity
        header: null,
        headerHeight: 0,
        isScrolling: false,
        scrollTimeout: null,
        lastDirection: null, // ✅ Track last direction to prevent flickering

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
            window.addEventListener('scroll', () => {
                if (this.isScrolling) return;

                clearTimeout(this.scrollTimeout);

                this.scrollTimeout = setTimeout(() => {
                    this.handleScroll();
                }, 50); // ✅ Increased debounce from 10ms to 50ms

            }, { passive: true });
        },

        handleScroll() {
            const currentScrollTop = window.pageYOffset || document.documentElement.scrollTop;

            // ✅ Prevent negative scroll (mobile bounce)
            if (currentScrollTop < 0) {
                return;
            }

            // ✅ Calculate max scroll to prevent bottom bounce issues
            const maxScroll = Math.max(
                document.body.scrollHeight,
                document.body.offsetHeight,
                document.documentElement.clientHeight,
                document.documentElement.scrollHeight,
                document.documentElement.offsetHeight
            ) - window.innerHeight;

            // ✅ Near bottom of page - show header (prevents flickering)
            if (currentScrollTop >= maxScroll - 50) {
                this.showHeader();
                document.body.classList.remove('header-is-hidden');
                this.lastScrollTop = currentScrollTop;
                return;
            }

            // Check if scroll is significant enough
            if (Math.abs(this.lastScrollTop - currentScrollTop) <= this.delta) {
                return;
            }

            // At top - show header
            if (currentScrollTop <= 0) {
                this.showHeader();
                this.header.classList.remove('header-compact');
                document.body.classList.remove('header-is-hidden');
                this.lastScrollTop = currentScrollTop;
                this.lastDirection = null;
                return;
            }

            // ✅ Determine scroll direction
            const isScrollingDown = currentScrollTop > this.lastScrollTop;

            // ✅ Only change header state if direction changed (prevents flickering)
            if (this.lastDirection !== null && this.lastDirection === isScrollingDown) {
                this.lastScrollTop = currentScrollTop;
                return;
            }

            // Scrolling down - hide header
            if (isScrollingDown && currentScrollTop > this.scrollThreshold) {
                this.hideHeader();
                document.body.classList.add('header-is-hidden');
                this.lastDirection = true;
            }
            // Scrolling up - show header
            else if (!isScrollingDown) {
                this.showHeader();
                document.body.classList.remove('header-is-hidden');
                this.lastDirection = false;
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
