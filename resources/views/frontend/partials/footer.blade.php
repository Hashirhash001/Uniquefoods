{{-- Footer Area Two --}}
<div class="rts-footer-area-two">
    <div class="container-2">
        <div class="row">
            <div class="coll-lg-12">
                <div class="footer-two-main-wrapper">

                    {{-- Logo + newsletter --}}
                    <div class="footer-single-wixed-two start">
                        <a href="{{ route('home') }}" class="logo-area d-flex justify-content-center">
                            <img src="{{ asset('admin/assets/images/logo/logo-white.png') }}" alt="Unique Foods" class="logo" style="max-width: 150px;">
                        </a>

                        <p class="disc">
                            Fresh groceries delivered to your doorstep. Subscribe for exclusive deals and offers.
                        </p>

                        <div class="social-style-dash">
                            <ul>
                                <li><a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a></li>
                                <li><a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a></li>
                                <li><a href="#" aria-label="Twitter"><i class="fa-brands fa-twitter"></i></a></li>
                                <li><a href="#" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a></li>
                            </ul>
                        </div>
                    </div>

                    {{-- Quick links --}}
                    <div class="single-footer-wized mid">
                        <h3 class="footer-title">Quick Links</h3>
                        <div class="footer-nav">
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

                    <div class="single-footer-wized mid">
                        <h3 class="footer-title">Information</h3>
                        <div class="footer-nav">
                            <ul>
                                <li><a href="#">About Us</a></li>
                                <li><a href="#">Delivery Information</a></li>
                                <li><a href="#">Privacy Policy</a></li>
                                <li><a href="#">Terms & Conditions</a></li>
                                <li><a href="#">Contact Us</a></li>
                            </ul>
                        </div>
                    </div>

                    {{-- Contact --}}
                    <div class="single-footer-wized">
                        <h3 class="footer-title">Get In Touch</h3>
                        <div class="contact-information">

                            <div class="single-contact-information-area">
                                <div class="icon-area">
                                    <img src="{{ asset('frontend/assets/images/icons/11.svg') }}" alt="Address">
                                </div>
                                <div class="information-area">
                                    <p class="disc">
                                        Kanayannur, Kerala, India
                                    </p>
                                </div>
                            </div>

                            <div class="single-contact-information-area">
                                <div class="icon-area">
                                    <img src="{{ asset('frontend/assets/images/icons/12.svg') }}" alt="Phone">
                                </div>
                                <div class="information-area">
                                    <p class="disc">
                                        Available 24/7<br>
                                        <a href="tel:+919999999999">+44 7425 837716</a>
                                    </p>
                                </div>
                            </div>

                            <div class="single-contact-information-area">
                                <div class="icon-area">
                                    <img src="{{ asset('frontend/assets/images/icons/13.svg') }}" alt="Email">
                                </div>
                                <div class="information-area">
                                    <p class="disc">
                                        Email Support<br>
                                        <a href="mailto:info@unique-food.co.uk">info@unique-food.co.uk</a>
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- Copyright --}}
<div class="rts-copyright-area-two">
    <div class="container-2">
        <div class="row">
            <div class="col-lg-12">
                <div class="copyright-arae-two-wrapper">
                    <p class="disc">
                        Copyright © {{ date('Y') }} <a href="{{ route('home') }}">Unique Foods</a>. All rights reserved.
                    </p>

                    <div class="payment-processw-area">
                        <span>Secure Payment</span>
                        <div style="display: inline-flex; align-items: center; gap: 8px; margin-left: 8px;">
                            <i class="fa-brands fa-cc-visa" style="font-size: 28px; color: #1A1F71;"></i>
                            <i class="fa-brands fa-cc-mastercard" style="font-size: 28px; color: #EB001B;"></i>
                            <i class="fa-brands fa-cc-amex" style="font-size: 28px; color: #006FCF;"></i>
                            <span style="margin: 0 4px; color: #9CA3AF;">via</span>
                            <i class="fa-brands fa-stripe" style="font-size: 32px; color: #635BFF;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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

{{-- Scroll to top --}}
<div class="progress-wrap">
    <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
        <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
    </svg>
</div>
