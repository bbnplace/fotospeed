@extends('marketing.layouts.default')

@section('content')

    @include('marketing.partials.breadcrumb-banner-mini')
            
                <!-- Contact Info Section Start -->
                <section class="contact-page-wrap section-padding">
                <div class="container">
                    <div class="row g-4">
                        @foreach ($offices as $office)
                            <div class="col-lg-4 col-md-6 col-12">
                                <div class="single-contact-card card1">
                                    <div class="top-part">
                                        <div class="icon">
                                            <i class="fal fa-map-marker-alt"></i>
                                        </div>
                                        <div class="title">
                                            <h4>{{ $office['name'] }}</h4>
                                            <span>{{ $office['address'] }}</span>
                                        </div>
                                    </div>
                                    <div class="bottom-part">                            
                                        <div class="info">
                                            @php
                                                $office['contacts'] = $office['contacts'] ? json_decode($office['contacts'], true) : [];
                                            @endphp
                                            @foreach ($office['contacts'] as $phone)
                                                <p>{{ $phone }}</p>
                                            @endforeach
                                        </div>
                                        <div class="icon">
                                            <i class="fal fa-phone"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        {{-- <div class="col-lg-4 col-md-6 col-12">
                            <div class="single-contact-card card1">
                                <div class="top-part">
                                    <div class="icon">
                                        <i class="fal fa-envelope"></i>
                                    </div>
                                    <div class="title">
                                        <h4>Email Address</h4>
                                        <span>Sent mail asap anytime</span>
                                    </div>
                                </div>
                                <div class="bottom-part">                            
                                    <div class="info">
                                        <p>info@example.com</p>
                                        <p>jobs@example.com</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fal fa-arrow-right"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="single-contact-card card2">
                                <div class="top-part">
                                    <div class="icon">
                                        <i class="fal fa-phone"></i>
                                    </div>
                                    <div class="title">
                                        <h4>Phone Number</h4>
                                        <span>call us asap anytime</span>
                                    </div>
                                </div>
                                <div class="bottom-part">                            
                                    <div class="info">
                                        <p>098-098-098-09</p>
                                        <p>+(098) 098-098-765</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fal fa-arrow-right"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="single-contact-card card3">
                                <div class="top-part">
                                    <div class="icon">
                                        <i class="fal fa-map-marker-alt"></i>
                                    </div>
                                    <div class="title">
                                        <h4>Office Address</h4>
                                        <span>Sent mail asap anytime</span>
                                    </div>
                                </div>
                                <div class="bottom-part">                            
                                    <div class="info">
                                        <p>B2, Miranda City Tower</p>
                                        <p>New York, US</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fal fa-arrow-right"></i>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
                </section>
            
                <!-- Contact Section Start -->
                <section class="contact-section-2 fix section-padding pt-0">
                    <div class="container">
                        <div class="contact-form-items">
                            <div class="title text-center">
                                <h2 class="split-text right">Let’s Get in Touch</h2>
                                <p>Your email address will not be published. Required fields are marked *</p>
                            </div>
                            <form action="contact.php" id="contact-form" method="POST">
                                <div class="row g-4">
                                    <div class="col-lg-6">
                                        <div class="form-clt">
                                            <input type="text" name="name" id="name" placeholder="Your Name*">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-clt">
                                            <input type="tel" name="mobile" id="mobile" placeholder="Your Whatsapp Number*">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-clt">
                                            <textarea name="message" id="message" placeholder="Write Message*"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <button type="submit" class="theme-btn">
                                            SEND YOUR MEASSAGE
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
            
                <!-- Map Section Start -->
                <div class="office-google-map-wrapper wow fadeInUp">
                    <iframe src="https://www.google.com/maps?q=83%2C%20Opebi%20Road%2C%20Ikeja%2C%20Lagos%2C%20Nigeria&output=embed" style="border:0; width: 100%;"
                    allowfullscreen="" loading="lazy"></iframe>
                </div>
@endsection