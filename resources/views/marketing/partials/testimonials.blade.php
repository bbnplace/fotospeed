<!-- Testimonial Section Start -->
    <section id="testimonials" class="testimonial-section fix bg-cover section-padding" style="background-image: url('assets/img/testimonial-bg.jpg');">
        <div class="container">
            <div class="section-title text-center">
                {{-- <div class="sub-title wow fadeInUp">
                    <span>Testimonials</span>
                </div> --}}
                <h2 class="split-text right">
                    Here's What Our Customers <br> Have to Say
                </h2>
            </div>
            <div class="testimonial-wrapper">
                <div class="swiper testimonial-slider">
                    <div class="swiper-wrapper">
                        @foreach ($testimonials as $testimonial)
                            <div class="swiper-slide">
                            <div class="testimonial-box-items">
                                <div class="star">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <p>
                                    {{ $testimonial['feedback'] }}
                                </p>
                                <div class="client-info">
                                    <div class="client-img bg-cover" style="background-image: url('assets/img/testimonial/{{ $testimonial['image'] }}');"></div>
                                    <div class="content">
                                        <h4>{{ $testimonial['name'] }}</h4>
                                        <span>{{ $testimonial['role'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        
                    </div>
                </div>
            </div>
        </div>
    </section>