{{-- Footer Area Two --}}
<div class="rts-footer-area-two">
    <div class="container-2">
        <div class="row">
            <div class="coll-lg-12">
                <div class="footer-two-main-wrapper">

                    {{-- Logo + newsletter --}}
                    <div class="footer-single-wixed-two start">
                        <a href="{{ route('home') }}" class="logo-area">
                            <img src="{{ asset('frontend/assets/images/logo/logo-02.svg') }}" alt="logo-area" class="logo">
                        </a>

                        <p class="disc">
                            Whats inside: New Arrivals, Exclusive Sales, News & More.
                        </p>

                        <form action="#" method="POST">
                            <input type="email" placeholder="Email Address" required>
                            <button class="rts-btn btn-primary" type="submit">
                                <i class="fa-light fa-arrow-right"></i>
                            </button>
                        </form>

                        <div class="social-style-dash">
                            <ul>
                                <li><a href="#"><i class="fa-brands fa-facebook-f"></i></a></li>
                                <li><a href="#"><i class="fa-brands fa-twitter"></i></a></li>
                                <li><a href="#"><i class="fa-brands fa-linkedin-in"></i></a></li>
                                <li><a href="#"><i class="fa-brands fa-youtube"></i></a></li>
                                <li><a href="#"><i class="fa-brands fa-instagram"></i></a></li>
                            </ul>
                        </div>
                    </div>

                    {{-- Quick links (use your template’s columns if you want) --}}
                    <div class="single-footer-wized mid">
                        <h3 class="footer-title">Our Store</h3>
                        <div class="footer-nav">
                            <ul>
                                <li><a href="#">Delivery Information</a></li>
                                <li><a href="#">Privacy Policy</a></li>
                                <li><a href="#">Terms & Conditions</a></li>
                                <li><a href="#">Support Center</a></li>
                                <li><a href="#">Careers</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="single-footer-wized mid">
                        <h3 class="footer-title">Shop Categories</h3>
                        <div class="footer-nav">
                            <ul>
                                <li><a href="#">Grocery</a></li>
                                <li><a href="#">Vegetables</a></li>
                                <li><a href="#">Meat & Fish</a></li>
                                <li><a href="#">Snacks</a></li>
                                <li><a href="#">Beverages</a></li>
                            </ul>
                        </div>
                    </div>

                    {{-- Contact --}}
                    <div class="single-footer-wized">
                        <h3 class="footer-title">Need Help? Contact Us</h3>
                        <div class="contact-information">

                            <div class="single-contact-information-area">
                                <div class="icon-area">
                                    <img src="{{ asset('frontend/assets/images/icons/11.svg') }}" alt="icons">
                                </div>
                                <div class="information-area">
                                    <p class="disc">
                                        258 Daniel Street, 2589<br>
                                        Kanayannur, Kerala
                                    </p>
                                </div>
                            </div>

                            <div class="single-contact-information-area">
                                <div class="icon-area">
                                    <img src="{{ asset('frontend/assets/images/icons/12.svg') }}" alt="icons">
                                </div>
                                <div class="information-area">
                                    <p class="disc">
                                        Call us between 8:00 AM - 12PM<br>
                                        <a href="tel:+919999999999">+91 99999 99999</a>
                                    </p>
                                </div>
                            </div>

                            <div class="single-contact-information-area">
                                <div class="icon-area">
                                    <img src="{{ asset('frontend/assets/images/icons/13.svg') }}" alt="icons">
                                </div>
                                <div class="information-area">
                                    <p class="disc">
                                        Live Chat<br>
                                        <span>Chat With an Expert</span>
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
                        Copyright {{ date('Y') }} <a href="{{ route('home') }}">Unique Foods</a>. All rights reserved.
                    </p>

                    <div class="payment-processw-area">
                        <span>Payment Accepts</span>
                        <img src="{{ asset('cassets/images/payment/04.png') }}" alt="payment">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Search popup + progress (optional, template uses these) --}}
<div class="search-input-area">
    <div class="container">
        <div class="search-input-inner">
            <div class="input-div">
                <input id="searchInput1" class="search-input" type="text" placeholder="Search by keyword or...">
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
