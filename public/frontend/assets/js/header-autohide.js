(function () {
    'use strict';

    const AutoHideHeader = {
        lastScrollTop: 0,
        scrollThreshold: 80,
        delta: 8,
        desktopHeader: null,
        mobileHeader: null,
        bottomNav: null,
        scrollTimeout: null,
        ticking: false,

        init() {
            this.desktopHeader = document.querySelector('.unique-modern-header');
            this.mobileHeader  = document.querySelector('.unique-mobile-header');
            this.bottomNav     = document.querySelector('.unique-mobile-bottom-nav');

            if (!this.desktopHeader && !this.mobileHeader) return;

            // Start everything visible
            this._show(this.desktopHeader);
            this._show(this.mobileHeader);
            this._showNav();

            this.lastScrollTop = Math.max(0, window.pageYOffset || 0);

            window.addEventListener('scroll', () => {
                if (this.ticking) return;
                this.ticking = true;

                clearTimeout(this.scrollTimeout);
                this.scrollTimeout = setTimeout(() => {
                    this._handleScroll();
                    this.ticking = false;
                }, 16);
            }, { passive: true });
        },

        _handleScroll() {
            const st = Math.max(0, window.pageYOffset || document.documentElement.scrollTop);

            // ── Close sort sheet on any scroll so it never gets caught mid-transition ──
            const sortSheet   = document.querySelector('.shop-mobile-sort-sheet');
            const sortOverlay = document.querySelector('.shop-sort-overlay');
            if (sortSheet?.classList.contains('open')) {
                sortSheet.classList.remove('open');
                sortOverlay?.classList.remove('show');
                document.querySelector('.shop-mobile-sort-trigger')?.classList.remove('active');
            }

            const maxScroll = document.documentElement.scrollHeight - window.innerHeight;

            // Near page bottom — show everything
            if (st >= maxScroll - 60) {
                this._showAll();
                this.lastScrollTop = st;
                return;
            }

            // At very top — show everything, remove compact
            if (st <= 10) {
                this._showAll();
                this._removeCompact();
                document.body.classList.remove('header-is-hidden', 'bottom-nav-hidden');
                this.lastScrollTop = st;
                return;
            }

            const diff = st - this.lastScrollTop;

            // Ignore tiny jitter
            if (Math.abs(diff) <= this.delta) return;

            const scrollingDown = diff > 0;

            if (scrollingDown && st > this.scrollThreshold) {
                // ── Scrolling DOWN: hide header & bottom nav ──
                this._hide(this.desktopHeader);
                this._hide(this.mobileHeader);
                this._hideNav();
                document.body.classList.add('header-is-hidden', 'bottom-nav-hidden');
            } else if (!scrollingDown) {
                // ── Scrolling UP: show header & bottom nav ──
                this._showAll();
                document.body.classList.remove('header-is-hidden', 'bottom-nav-hidden');
            }

            // Compact mode (desktop header only)
            if (st > this.scrollThreshold) {
                this.desktopHeader?.classList.add('header-compact');
            } else {
                this._removeCompact();
            }

            this.lastScrollTop = st;
        },

        _show(el) {
            if (!el) return;
            el.classList.remove('header-hidden');
            el.classList.add('header-visible');
        },

        _hide(el) {
            if (!el) return;
            el.classList.add('header-hidden');
            el.classList.remove('header-visible');
        },

        _showNav() {
            if (!this.bottomNav) return;
            this.bottomNav.classList.remove('nav-hidden');
            this.bottomNav.classList.add('nav-visible');
        },

        _hideNav() {
            if (!this.bottomNav) return;
            this.bottomNav.classList.add('nav-hidden');
            this.bottomNav.classList.remove('nav-visible');
        },

        _showAll() {
            this._show(this.desktopHeader);
            this._show(this.mobileHeader);
            this._showNav();
        },

        _removeCompact() {
            this.desktopHeader?.classList.remove('header-compact');
        }
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => AutoHideHeader.init());
    } else {
        AutoHideHeader.init();
    }

    window.AutoHideHeader = AutoHideHeader;

})();
