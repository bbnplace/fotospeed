<!-- Faq Section Start -->
                <section id="faqs" class="faq-section fix section-padding">
                    <div class="container">
                        <div class="faq-wrapper">
                            <div class="row g-4">
                                <div class="col-lg-6">
                                   <div class="faq-image-2">
                                        <div class="faq-img reveal fix">
                                            <img src="{{ config('app.url') }}/assets/img/faq/03.jpg" alt="img">
                                            <a href="https://www.youtube.com/watch?v=CJQUV8gHQqM" class="video-btn ripple video-popup">
                                                <i class="fas fa-play"></i>
                                            </a>
                                        </div>
                                        <div class="faq-img-2 wow img-custom-anim-top" data-wow-duration="1.5s" data-wow-delay="0.1s">
                                            <img src="{{ config('app.url') }}/assets/img/faq/04.jpg" alt="img">
                                        </div>
                                   </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="faq-content">
                                        <div class="section-title">
                                            <div class="sub-title wow fadeInUp">
                                                <span>FREQUENTLY ASKED QUESTION</span>
                                            </div>
                                            <h2 class="split-text right">
                                                Questions First-Timers Ask
                                                About Fotospeed
                                            </h2>
                                        </div>
                                        <div class="faq-accordion mt-3 mt-md-0">
                                            <div class="accordion" id="accordion">
                                                <?php $index = 0 ?>
                                                @foreach ($faqs as $faq)
                                                    <div class="accordion-item mb-4 wow fadeInUp" data-wow-delay="{{ $index * 0.2 }}s">
                                                        <h5 class="accordion-header">
                                                            <button class="accordion-button {{ $index == 0 ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#faq{{ $index + 1 }}" aria-expanded="{{ $index == 0 ? 'true' : 'false' }}" aria-controls="faq{{ $index + 1 }}">
                                                                <span>{{ $index + 1 }}.</span> {{ $faq['question'] }}
                                                            </button>
                                                        </h5>
                                                        <div id="faq{{ $index + 1 }}" class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}" data-bs-parent="#accordion">
                                                            <div class="accordion-body">
                                                                {{ $faq['answer'] }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                <?php $index++ ?>
                                                @endforeach

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>