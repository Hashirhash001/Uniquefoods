{{-- Footer Area Two --}}
<div class="rts-footer-area-two">
    <div class="container-2">
        <div class="row">
            <div class="col-lg-12">
                <div class="footer-two-main-wrapper">

                    {{-- Logo + Social --}}
                    <div class="footer-single-wixed-two start">
                        <a href="{{ route('home') }}" class="logo-area d-flex justify-content-center">
                            <img src="{{ asset('admin/assets/images/logo/logo-white.png') }}"
                                 alt="Unique Foods" class="logo" style="max-width: 150px;">
                        </a>
                        <p class="disc">
                            Fresh groceries delivered to your doorstep.
                        </p>
                        <div class="social-style-dash">
                            <ul>
                                <li><a href="https://wa.me/+447939699530?text=Hello" target="_blank" aria-label="YouTube"><i class="fa-brands fa-whatsapp"></i></a></li>
                                <li><a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a></li>
                                <li><a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a></li>
                            </ul>
                        </div>
                    </div>

                    {{-- Quick Links — accordion on mobile --}}
                    <div class="single-footer-wized mid footer-accordion-section">
                        <h3 class="footer-title footer-accordion-toggle">
                            Quick Links
                            <i class="fa-solid fa-chevron-down footer-accordion-icon ms-3"></i>
                        </h3>
                        <div class="footer-nav footer-accordion-body">
                            <ul>
                                <li><a href="{{ route('home') }}">Home</a></li>
                                <li><a href="{{ route('shop') }}">Shop</a></li>
                                <li><a href="{{ route('cart.index') }}">Cart</a></li>
                                <li><a href="{{ route('wishlist.index') }}">Wishlist</a></li>
                                @auth
                                    <li><a href="#">My Account</a></li>
                                @else
                                    <li><a href="{{ route('login') }}">Login</a></li>
                                @endauth
                            </ul>
                        </div>
                    </div>

                    {{-- Information — accordion on mobile --}}
                    <div class="single-footer-wized mid footer-accordion-section">
                        <h3 class="footer-title footer-accordion-toggle">
                            Information
                            <i class="fa-solid fa-chevron-down footer-accordion-icon"></i>
                        </h3>
                        <div class="footer-nav footer-accordion-body">
                            <ul>
                                <li><a href="#">About Us</a></li>
                                <li><a href="#">Delivery Information</a></li>
                                <li><a href="#">Privacy Policy</a></li>
                                <li><a href="#">Terms &amp; Conditions</a></li>
                                <li><a href="#">Contact Us</a></li>
                            </ul>
                        </div>
                    </div>

                    {{-- Contact — accordion on mobile --}}
                    <div class="single-footer-wized mid footer-accordion-section">
                        <h3 class="footer-title footer-accordion-toggle">
                            Get In Touch
                            <i class="fa-solid fa-chevron-down footer-accordion-icon"></i>
                        </h3>
                        <div class="contact-information footer-accordion-body">

                            <div class="single-contact-information-area">
                                <div class="icon-area">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                         viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"
                                         stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2A19.79 19.79 0 0 1 3.21 5.18 2 2 0 0 1 5.23 3h3a2 2 0 0 1 2 1.72c.13 1.21.37 2.39.72 3.53a2 2 0 0 1-.45 1.95l-2.27 2.27a16 16 0 0 0 6.59 6.59l2.27-2.27a2 2 0 0 1 1.95-.45c1.14.35 2.32.59 3.53.72A2 2 0 0 1 22 16.92z"></path>
                                    </svg>
                                </div>
                                <div class="information-area">
                                    <p class="disc">
                                        Available 24/7<br>
                                        <a href="tel:+447425837716">+44 7425 837716</a>
                                    </p>
                                </div>
                            </div>

                            <div class="single-contact-information-area">
                                <div class="icon-area">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                         viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"
                                         stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                        <polyline points="22,6 12,13 2,6"></polyline>
                                    </svg>
                                </div>
                                <div class="information-area">
                                    <p class="disc">
                                        Email Support<br>
                                        <a href="mailto:info@unique-food.co.uk">info@unique-food.co.uk</a>
                                    </p>
                                </div>
                            </div>

                            {{-- Mobile quick-contact buttons --}}
                            <div class="footer-mobile-contact-btns">
                                <a href="tel:+447425837716" class="footer-contact-btn">
                                    <i class="fa-solid fa-phone"></i> Call Us
                                </a>
                                <a href="mailto:info@unique-food.co.uk" class="footer-contact-btn">
                                    <i class="fa-solid fa-envelope"></i> Email Us
                                </a>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- Copyright --}}
{{-- <div class="rts-copyright-area-two">
    <div class="container-2">
        <div class="row">
            <div class="col-lg-12">
                <div class="copyright-arae-two-wrapper">
                    <p class="disc">
                        Copyright &copy; {{ date('Y') }}
                        <a href="{{ route('home') }}">Unique Foods</a>. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div> --}}

{{-- Search popup --}}
<div class="search-input-area">
    <div class="container">
        <div class="search-input-inner">
            <div class="input-div">
                <input id="searchInput1" class="search-input" type="text" placeholder="Search products...">
                <button><i class="far fa-search"></i></button>
            </div>
        </div>
        <div id="close" class="search-close-icon"><i class="far fa-times"></i></div>
    </div>
</div>

<div id="anywhere-home" class="anywere"></div>

<div class="progress-wrap">
    <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
        <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
    </svg>
</div>

{{-- Footer Mobile CSS + Accordion JS --}}
<style>
/* ── Mobile quick-contact buttons ── */
.footer-mobile-contact-btns {
    display: none;
}

/* ── Accordion icon default ── */
.footer-accordion-icon {
    display: none;
    font-size: 13px;
    transition: transform 0.3s ease;
    margin-left: auto;
}

@media (max-width: 991px) {

    /* Stack columns vertically */
    .footer-two-main-wrapper {
        flex-direction: column !important;
        gap: 0 !important;
    }

    /* Logo section — centered, reduced padding */
    .footer-single-wixed-two.start {
        padding: 32px 20px 24px !important;
        text-align: center !important;
    }

    .footer-single-wixed-two.start .disc {
        max-width: 340px;
        margin: 12px auto !important;
    }

    .footer-single-wixed-two.start .social-style-dash {
        justify-content: center !important;
    }

    /* Accordion sections */
    .footer-accordion-section {
        border-top: 1px solid rgba(255,255,255,0.1) !important;
        padding: 0 !important;
    }

    /* Toggle header — full tap target */
    .footer-accordion-toggle {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        padding: 16px 20px !important;
        margin: 0 !important;
        cursor: pointer !important;
        user-select: none !important;
        min-height: 52px !important; /* 44px+ touch target */
    }

    .footer-accordion-icon {
        display: inline-block !important;
        flex-shrink: 0 !important;
    }

    .footer-accordion-toggle.open .footer-accordion-icon {
        transform: rotate(180deg) !important;
    }

    /* Body — collapsed by default */
    .footer-accordion-body {
        max-height: 0 !important;
        overflow: hidden !important;
        transition: max-height 0.35s ease, padding 0.35s ease !important;
        padding: 0 20px !important;
    }

    .footer-accordion-body.open {
        max-height: 400px !important;
        padding: 4px 20px 20px !important;
    }

    /* Nav links — larger tap targets */
    .footer-nav ul li {
        margin-bottom: 0 !important;
    }

    .footer-nav ul li a {
        display: block !important;
        padding: 10px 0 !important;
        font-size: 15px !important;
        border-bottom: 1px solid rgba(255,255,255,0.06) !important;
    }

    .footer-nav ul li:last-child a {
        border-bottom: none !important;
    }

    /* Contact info spacing */
    .single-contact-information-area {
        padding: 10px 0 !important;
        border-bottom: 1px solid rgba(255,255,255,0.06) !important;
    }

    .single-contact-information-area:last-of-type {
        border-bottom: none !important;
    }

    /* Quick contact buttons — mobile only */
    .footer-mobile-contact-btns {
        display: flex !important;
        gap: 10px !important;
        margin-top: 16px !important;
        padding-bottom: 4px !important;
    }

    .footer-contact-btn {
        flex: 1 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 8px !important;
        padding: 12px 8px !important;
        border: 1.5px solid rgba(255,255,255,0.3) !important;
        border-radius: 8px !important;
        color: #fff !important;
        font-size: 14px !important;
        font-weight: 600 !important;
        text-decoration: none !important;
        transition: background 0.2s !important;
        min-height: 44px !important;
    }

    .footer-contact-btn:hover {
        background: rgba(255,255,255,0.12) !important;
        color: #fff !important;
    }

    /* Copyright — centered on mobile */
    .copyright-arae-two-wrapper {
        flex-direction: column !important;
        text-align: center !important;
        gap: 8px !important;
        padding: 16px 20px !important;
    }

    /* Add bottom padding so content clears the mobile bottom nav */
    .rts-footer-area-two {
        padding: 0;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Only activate accordions on mobile
    if (window.innerWidth >= 992) return;

    document.querySelectorAll('.footer-accordion-toggle').forEach(function(toggle) {
        toggle.addEventListener('click', function() {
            var body    = this.nextElementSibling;
            var isOpen  = body.classList.contains('open');

            // Close all open sections first
            document.querySelectorAll('.footer-accordion-body.open').forEach(function(b) {
                b.classList.remove('open');
            });
            document.querySelectorAll('.footer-accordion-toggle.open').forEach(function(t) {
                t.classList.remove('open');
            });

            // Toggle clicked section
            if (!isOpen) {
                body.classList.add('open');
                this.classList.add('open');
            }
        });
    });
});
</script>
