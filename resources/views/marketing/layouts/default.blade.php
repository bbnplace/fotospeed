<!DOCTYPE html>
<html lang="en">
    <!--<< Header Area >>-->
    <head>
        <!-- ========== Meta Tags ========== -->
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="modinatheme">
        <meta name="description" content="FotoSpeed - Printing Company, Photobooks, Synthetic Albums">
        <!-- ======== Page title ============ -->
        <title>FotoSpeed - Printing Company, Photobooks, Synthetic Albums</title>
        <!--<< Favcion >>-->
        <link rel="shortcut icon" href="{{ config('app.url') }}/assets/img/favicon.svg">
        <!--<< Bootstrap min.css >>-->
        <link rel="stylesheet" href="{{ config('app.url') }}/assets/css/bootstrap.min.css">
        <!--<< Font Awesome.css >>-->
        <link rel="stylesheet" href="{{ config('app.url') }}/assets/css/font-awesome.css">
        <!--<< Animate.css >>-->
        <link rel="stylesheet" href="{{ config('app.url') }}/assets/css/animate.css">
        <!--<< Magnific Popup.css >>-->
        <link rel="stylesheet" href="{{ config('app.url') }}/assets/css/magnific-popup.css">
        <!--<< MeanMenu.css >>-->
        <link rel="stylesheet" href="{{ config('app.url') }}/assets/css/meanmenu.css">
        <!--<< Swiper Slider.css >>-->
        <link rel="stylesheet" href="{{ config('app.url') }}/assets/css/swiper-bundle.min.css">
        <!--<< Nice Select.css >>-->
        <link rel="stylesheet" href="{{ config('app.url') }}/assets/css/nice-select.css">
        <!--<< Main.css >>-->
        <link rel="stylesheet" href="{{ config('app.url') }}/assets/css/main.css">
        <!--<< Style.css >>-->
        <link rel="stylesheet" href="{{ config('app.url') }}/assets/css/style.css">
    </head>

    <body>
<!-- Preloader Start -->
    <div id="preloader" class="preloader">
        <div class="animation-preloader">
            <div class="spinner">                
            </div>
            <div class="txt-loading">
                
            </div>
            <p class="text-center">Loading</p>
        </div>
        <div class="loader">
            <div class="row">
                <div class="col-3 loader-section section-left">
                    <div class="bg"></div>
                </div>
                <div class="col-3 loader-section section-left">
                    <div class="bg"></div>
                </div>
                <div class="col-3 loader-section section-right">
                    <div class="bg"></div>
                </div>
                <div class="col-3 loader-section section-right">
                    <div class="bg"></div>
                </div>
            </div>
        </div>
    </div>

    <!--<< Mouse Cursor Start >>-->  
    <div class="mouse-cursor cursor-outer"></div>
    <div class="mouse-cursor cursor-inner"></div>

    <!-- Back To Top Start -->
    <div class="scroll-up">
        <svg class="scroll-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
        </svg>
    </div>

    <!-- Offcanvas Area Start -->
    <div class="fix-area">
        <div class="offcanvas__info">
            <div class="offcanvas__wrapper">
                <div class="offcanvas__content">
                    <div class="offcanvas__top mb-5 d-flex justify-content-between align-items-center">
                        <div class="offcanvas__logo">
                            <a href="{{ route('marketing.home') }}">
                                <img src="{{ config('app.url') }}/assets/img/logo/logo.svg" alt="logo-img">
                            </a>
                        </div>
                        <div class="offcanvas__close">
                            <button>
                            <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <p class="text d-none d-xl-block">
                        This involves interactions between a business and its customers. It's about meeting customers' needs and resolving their problems. Effective customer service is crucial.
                    </p>
                    <div class="mobile-menu fix mb-3"></div>
                    <div class="offcanvas__contact">
                        <div class="header-button mt-4">
                            <a href="shop-details.html" class="theme-btn">Shop Now <i class="far fa-arrow-right"></i></a>
                        </div>
                        <div class="social-icon d-flex align-items-center">
                            <a href="https://www.facebook.com/Syntheticalbum" target="_blank"><i class="fab fa-facebook-f"></i></a>
                            <a href="https://www.instagram.com/photobooknigeria/" target="_blank"><i class="fab fa-instagram"></i></a>
                            <a href="https://go.wa.link/photobooknigeria" target="_blank"><i class="fab fa-whatsapp"></i></a>
                            <a href="https://www.youtube.com/@indigoafrica" target="_blank"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="offcanvas__overlay"></div>

    {{-- @include('partials.offcanvas') --}}

    <!-- Header Area Start -->
    <header class="header-section-1">
        <div id="header-sticky" class="header-1">
            <div class="container-fluid">
                <div class="mega-menu-wrapper">
                    <div class="header-main">
                        <div class="header-left">
                            <div class="logo">
                                <a href="{{ route('marketing.home') }}" class="header-logo">
                                    <img src="{{ config('app.url') }}/assets/img/logo/logo.png" alt="logo-img">
                                </a>
                            </div>
                        </div>
                        <div class="mean__menu-wrapper">
                            <div class="main-menu">
                                <nav id="mobile-menu">
                                    <ul>
                                        <li class="active">
                                            <a href="#home" class="border-top-none">
                                            Home 
                                            </a>
                                        </li>
                                        
                                        <li>
                                            <a href="#who-we-are">Who are we</a>
                                        </li>
                                        <li>
                                            <a href="#how-we-work">How We Work</a>
                                        </li>
                                        <li>
                                            <a href="#testimonials">Testimonials</a>
                                        </li>
                                        <li>
                                            <a href="#submit-design">Submit Design</a>
                                        </li>
                                        <li>
                                            <a href="#faqs">FAQs</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="header-right d-flex justify-content-end align-items-center">
                            <a href="#0" class="search-trigger search-icon"><i class="fal fa-search"></i></a>
                            <a href="{{ route('login') }}" class="user-icon"><i class="far fa-user"></i></a>
                            {{-- <div class="menu-cart">
                                <button id="openButton" class="cart-icon">
                                    <i class="far fa-shopping-cart"></i>
                                </button>
                            </div> --}}
                            <a href="{{ route('marketing.products') }}">
                                <i class="far fa-shopping-cart"></i>
                            </a>
                            <div class="header__hamburger d-xl-none my-auto">
                                <div class="sidebar__toggle">
                                    <img src="{{ config('app.url') }}/assets/img/toggle.svg" alt="img">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Sidebar Area Here -->
    <div id="targetElement" class="side_bar slideInRight side_bar_hidden">
        <div class="side_bar_overlay"></div>
        <div class="cart-title mb-50">
            <h4>Shopping cart</h4>
        </div>
        <div class="cartmini__widget">
            <div class="cartmini__widget-item">
                <div class="cartmini__thumb">
                    <a href="product-details.html">
                   <img src="{{ config('app.url') }}/assets/img/header/product-1.jpg" alt="img">
                </a>
                </div>
                <div class="cartmini__content">
                    <h5><a href="product-details.html">Level Bolt Smart Lock</a></h5>
                    <div class="cartmini__price-wrapper">
                        <span class="cartmini__price">$46.00</span>
                        <span class="cartmini__quantity">x2</span>
                    </div>
                </div>
                <button class="cartmini__del"><i class="fal fa-times"></i></button>
            </div>
            <div class="cartmini__widget-item">
                <div class="cartmini__thumb">
                    <a href="product-details.html">
                        <img src="{{ config('app.url') }}/assets/img/header/product-2.jpg" alt="img">
                    </a>
                </div>
                <div class="cartmini__content">
                    <h5><a href="product-details.html">Trademil for younger</a></h5>
                    <div class="cartmini__price-wrapper">
                        <span class="cartmini__price">$78.00</span>
                        <span class="cartmini__quantity">x1</span>
                    </div>
                </div>
                <button class="cartmini__del"><i class="fal fa-times"></i></button>
            </div>
            <div class="cartmini__widget-item">
                <div class="cartmini__thumb">
                    <a href="product-details.html">
                        <img src="{{ config('app.url') }}/assets/img/header/product-3.jpg" alt="img">
                    </a>
                </div>
                <div class="cartmini__content">
                    <h5><a href="product-details.html">ViewSonic VP2756-2K</a></h5>
                    <div class="cartmini__price-wrapper">
                        <span class="cartmini__price">$98.00</span>
                        <span class="cartmini__quantity">x3</span>
                    </div>
                </div>
                <button class="cartmini__del"><i class="fal fa-times"></i></button>
            </div>
            <div class="cartmini__checkout">
                <div class="cartmini__checkout-title mb-4">
                    <h4>Subtotal:</h4>
                    <span>$113.00</span>
                </div>
                <div class="cartmini__checkout-btn">
                    <a href="shop-cart.html" class="theme-btn mb-2 w-100"> view cart</a>
                    <a href="checkout.html" class="theme-btn w-100 style-2"> checkout</a>
                </div>
            </div>
        </div>
        <button id="closeButton" class="x-mark-icon"><i class="fas fa-times"></i></button>
    </div>

    <!-- Search Area Start -->
    <div class="search-wrap">
        <div class="search-inner">
            <i class="fas fa-times search-close" id="search-close"></i>
            <div class="search-cell">
                <form method="get">
                    <div class="search-field-holder">
                        <input type="search" class="main-search-input" placeholder="Search...">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div data-bs-spy="scroll" data-bs-target="#mobile-menu" data-bs-offset="100" class="body__wrapper">
        @yield('content')
    </div>

    <!-- Footer Section Start -->
    <footer class="footer-section bg-cover" style="background-image: url('assets/img/footer-bg.png');">
        <div class="container">
            <div class="footer-widgets-wrapper">
                <div class="row">
                    <div class="col-xl-3 col-sm-6 col-md-6 col-lg-4 wow fadeInUp" data-wow-delay=".2s">
                        <div class="single-footer-widget">
                            <div class="widget-head">
                                <a href="{{ route('marketing.home') }}">
                                    <img src="{{ config('app.url') }}/assets/img/logo/logo.svg" alt="logo-img">
                                </a>
                            </div>
                            <div class="footer-content">
                                <div class="contact-info-area">
                                    <div class="contact-items">
                                        <div class="icon">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                        <div class="content">
                                            <p>Address</p>
                                            <h4>83, Opebi Road, Ikeja, Lagos, Nigeria</h4>
                                        </div>
                                    </div>
                                    <div class="contact-items">
                                        <div class="icon">
                                            <i class="fas fa-phone-alt"></i>
                                        </div>
                                        <div class="content">
                                            <p>
                                                Phone Number</p>
                                            <h4><a href="tel:+2349030002505">+2349030002505</a></h4>
                                        </div>
                                    </div>
                                    <div class="contact-items-2">
                                        <h4>Open Hours:</h4>
                                        <p>
                                            Mon - Sat: 9 am - 5 pm <br>
                                            Sunday: CLOSED
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 col-md-6 col-lg-4 ps-lg-5 wow fadeInUp" data-wow-delay=".4s">
                        <div class="single-footer-widget">
                            <div class="widget-head">
                                <h3>Useful Links</h3>
                            </div>
                            <ul class="list-items">
                                <li>
                                    <a href="{{ route('marketing.products') }}">
                                        Shop
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('customer.new-order') }}">
                                        Submit Design
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 col-md-6 col-lg-4 ps-lg-2 wow fadeInUp" data-wow-delay=".6s">
                        <div class="single-footer-widget">
                            <div class="widget-head">
                                <h3>Company</h3>
                            </div>
                            <ul class="list-items">
                                <li>
                                    <a href="{{ route('about') }}">
                                        About Us
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('contact') }}">
                                        Contact Us
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('privacy') }}">
                                        Privacy Policy
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 col-md-6 col-lg-4 wow fadeInUp" data-wow-delay=".8s">
                        <div class="single-footer-widget">
                            <div class="widget-head">
                                <h3>Social</h3>
                            </div>
                            <div class="footer-content">
                                <p>Keep in touch via our social media channels</p>
                                <div class="social-icon d-flex align-items-center">
                                    <a href="https://www.facebook.com/Syntheticalbum" target="_blank"><i class="fab fa-facebook-f"></i></a>
                                    <a href="https://www.instagram.com/photobooknigeria/" target="_blank"><i class="fab fa-instagram"></i></a>
                                    <a href="https://go.wa.link/photobooknigeria" target="_blank"><i class="fab fa-whatsapp"></i></a>
                                    <a href="https://www.youtube.com/@indigoafrica" target="_blank"><i class="fab fa-youtube"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <div class="footer-bottom-wrapper">
                    <p class="wow fadeInUp" data-wow-delay="0.5s">Copyright &copy; {{ date('Y') }}. Millewwy International Enterprises Limited. All Right Reserved.</p>
                    
                </div>
            </div>
        </div>
    </footer>

    

    <!--<< All JS Plugins >>-->
    <script src="{{ config('app.url') }}/assets/js/jquery-3.7.1.min.js"></script>
    <!--<< Viewport Js >>-->
    <script src="{{ config('app.url') }}/assets/js/viewport.jquery.js"></script>
    <!--<< Bootstrap Js >>-->
    <script src="{{ config('app.url') }}/assets/js/bootstrap.bundle.min.js"></script>
    <!--<< Gsap Js >>-->
    <script src="{{ config('app.url') }}/assets/js/gsap/gsap.js"></script>
    <!--<< Gsap Scroll To Pluging Js >>-->
    <script src="{{ config('app.url') }}/assets/js/gsap/gsap-scroll-to-plugin.js"></script>
    <!--<< Gsap Scroll Smoother Js >>-->
    <script src="{{ config('app.url') }}/assets/js/gsap/gsap-scroll-smoother.js"></script>
    <!--<< Gsap Scroll Trigger Js >>-->
    <script src="{{ config('app.url') }}/assets/js/gsap/gsap-scroll-trigger.js"></script>
    <!--<< Gsap Split Text Js >>-->
    <script src="{{ config('app.url') }}/assets/js/gsap/gsap-split-text.js"></script>
    <!--<< Nice Select Js >>-->
    <script src="{{ config('app.url') }}/assets/js/jquery.nice-select.min.js"></script>
    <!--<< Waypoints Js >>-->
    <script src="{{ config('app.url') }}/assets/js/jquery.waypoints.js"></script>
    <!--<< Counterup Js >>-->
    <script src="{{ config('app.url') }}/assets/js/jquery.counterup.min.js"></script>
    <!--<< Swiper Slider Js >>-->
    <script src="{{ config('app.url') }}/assets/js/swiper-bundle.min.js"></script>
    <!--<< MeanMenu Js >>-->
    <script src="{{ config('app.url') }}/assets/js/jquery.meanmenu.min.js"></script>
    <!--<< Magnific Popup Js >>-->
    <script src="{{ config('app.url') }}/assets/js/jquery.magnific-popup.min.js"></script>
    <!--<< Wow Animation Js >>-->
    <script src="{{ config('app.url') }}/assets/js/wow.min.js"></script>
    <!--<< Main.js >>-->
    <script src="{{ config('app.url') }}/assets/js/main.js"></script>
    </body>
</html>