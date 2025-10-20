<script setup>
import { Head, usePage, useForm, Link } from '@inertiajs/vue3';
const site = usePage().props.site;
</script>
<template>
    <!-- Shop Section Start -->
    <section class="shop-section fix section-padding">
        <div class="container">
            <div class="row g-">
                <div class="col-xl-3 col-lg-4 col-md-6 wow fadeInUp" data-wow-delay=".2s">
                    <div class="shop-items style-2 mt-0">
                        <div class="shop-image">
                            <img :src="`${site.url}/assets/img/shop/01.jpg`" alt="img">
                            <ul class="product-icon d-grid justify-content-center align-items-center">
                                <li>
                                    <a href="shop-cart.html"><i class="far fa-heart"></i></a>
                                </li>
                                <li>
                                    <a href="shop-details.html">
                                        <i class="far fa-exchange"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="shop-details.html"><i class="far fa-eye"></i></a>
                                </li>
                                <li>
                                    <a href="shop-cart.html"><i class="fal fa-cart-plus"></i></a>
                                </li>
                            </ul>
                            <div class="offer-text">-20%</div>
                        </div>
                        <div class="shop-content">
                            <h5><a href="shop-details.html">Product Price by Toggle</a></h5>
                            <ul class="price-list">
                                <li>$16.00 – $26.00</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 wow fadeInUp" data-wow-delay=".4s">
                    <div class="shop-items style-2 mt-0">
                        <div class="shop-image">
                            <img :src="`${site.url}/assets/img/shop/02.jpg`" alt="img">
                            <ul class="product-icon d-grid justify-content-center align-items-center">
                                <li>
                                    <a href="shop-cart.html"><i class="far fa-heart"></i></a>
                                </li>
                                <li>
                                    <a href="shop-details.html">
                                        <i class="far fa-exchange"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="shop-details.html"><i class="far fa-eye"></i></a>
                                </li>
                                <li>
                                    <a href="shop-cart.html"><i class="fal fa-cart-plus"></i></a>
                                </li>
                            </ul>
                        </div>
                        <div class="shop-content">
                            <h5><a href="shop-details.html">Creative Life</a></h5>
                            <ul class="price-list">
                                <li>$16.00 – $26.00</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 wow fadeInUp" data-wow-delay=".6s">
                    <div class="shop-items style-2 mt-0">
                        <div class="shop-image">
                            <img :src="`${site.url}/assets/img/shop/03.jpg`" alt="img">
                            <ul class="product-icon d-grid justify-content-center align-items-center">
                                <li>
                                    <a href="shop-cart.html"><i class="far fa-heart"></i></a>
                                </li>
                                <li>
                                    <a href="shop-details.html">
                                        <i class="far fa-exchange"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="shop-details.html"><i class="far fa-eye"></i></a>
                                </li>
                                <li>
                                    <a href="shop-cart.html"><i class="fal fa-cart-plus"></i></a>
                                </li>
                            </ul>
                            <div class="offer-text">Sale!</div>
                        </div>
                        <div class="shop-content">
                            <h5><a href="shop-details.html">Brochure Blue</a></h5>
                            <ul class="price-list">
                                <li>$18.00 – <del>$27.00</del></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 wow fadeInUp" data-wow-delay=".8s">
                    <div class="shop-items style-2 mt-0">
                        <div class="shop-image">
                            <img :src="`${site.url}/assets/img/shop/04.jpg`" alt="img">
                            <ul class="product-icon d-grid justify-content-center align-items-center">
                                <li>
                                    <a href="shop-cart.html"><i class="far fa-heart"></i></a>
                                </li>
                                <li>
                                    <a href="shop-details.html">
                                        <i class="far fa-exchange"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="shop-details.html"><i class="far fa-eye"></i></a>
                                </li>
                                <li>
                                    <a href="shop-cart.html"><i class="fal fa-cart-plus"></i></a>
                                </li>
                            </ul>
                        </div>
                        <div class="shop-content">
                            <h5><a href="shop-details.html">Catalogue Green</a></h5>
                            <ul class="price-list">
                                <li>$15.00</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 wow fadeInUp" data-wow-delay=".2s">
                    <div class="shop-items style-2 mt-0">
                        <div class="shop-image">
                            <img :src="`${site.url}/assets/img/shop/05.jpg`" alt="img">
                            <ul class="product-icon d-grid justify-content-center align-items-center">
                                <li>
                                    <a href="shop-cart.html"><i class="far fa-heart"></i></a>
                                </li>
                                <li>
                                    <a href="shop-details.html">
                                        <i class="far fa-exchange"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="shop-details.html"><i class="far fa-eye"></i></a>
                                </li>
                                <li>
                                    <a href="shop-cart.html"><i class="fal fa-cart-plus"></i></a>
                                </li>
                            </ul>
                            <div class="offer-text">Sold</div>
                        </div>
                        <div class="shop-content">
                            <h5><a href="shop-details.html">Brochure Yellow</a></h5>
                            <ul class="price-list">
                                <li>$28.00</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 wow fadeInUp" data-wow-delay=".4s">
                    <div class="shop-items style-2 mt-0">
                        <div class="shop-image">
                            <img :src="`${site.url}/assets/img/shop/06.jpg`" alt="img">
                            <ul class="product-icon d-grid justify-content-center align-items-center">
                                <li>
                                    <a href="shop-cart.html"><i class="far fa-heart"></i></a>
                                </li>
                                <li>
                                    <a href="shop-details.html">
                                        <i class="far fa-exchange"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="shop-details.html"><i class="far fa-eye"></i></a>
                                </li>
                                <li>
                                    <a href="shop-cart.html"><i class="fal fa-cart-plus"></i></a>
                                </li>
                            </ul>
                        </div>
                        <div class="shop-content">
                            <h5><a href="shop-details.html">Creative Life</a></h5>
                            <ul class="price-list">
                                <li>$12.00</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 wow fadeInUp" data-wow-delay=".6s">
                    <div class="shop-items style-2 mt-0">
                        <div class="shop-image">
                            <img :src="`${site.url}/assets/img/shop/01.jpg`" alt="img">
                            <ul class="product-icon d-grid justify-content-center align-items-center">
                                <li>
                                    <a href="shop-cart.html"><i class="far fa-heart"></i></a>
                                </li>
                                <li>
                                    <a href="shop-details.html">
                                        <i class="far fa-exchange"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="shop-details.html"><i class="far fa-eye"></i></a>
                                </li>
                                <li>
                                    <a href="shop-cart.html"><i class="fal fa-cart-plus"></i></a>
                                </li>
                            </ul>
                            <div class="offer-text">-20%</div>
                        </div>
                        <div class="shop-content">
                            <h5><a href="shop-details.html">Product Price by Toggle</a></h5>
                            <ul class="price-list">
                                <li>$16.00 – $26.00</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 wow fadeInUp" data-wow-delay=".8s">
                    <div class="shop-items style-2 mt-0">
                        <div class="shop-image">
                            <img :src="`${site.url}/assets/img/shop/02.jpg`" alt="img">
                            <ul class="product-icon d-grid justify-content-center align-items-center">
                                <li>
                                    <a href="shop-cart.html"><i class="far fa-heart"></i></a>
                                </li>
                                <li>
                                    <a href="shop-details.html">
                                        <i class="far fa-exchange"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="shop-details.html"><i class="far fa-eye"></i></a>
                                </li>
                                <li>
                                    <a href="shop-cart.html"><i class="fal fa-cart-plus"></i></a>
                                </li>
                            </ul>
                        </div>
                        <div class="shop-content">
                            <h5><a href="shop-details.html">Creative Life</a></h5>
                            <ul class="price-list">
                                <li>$16.00 – $26.00</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="page-nav-wrap mt-5 text-center wow fadeInUp" data-wow-delay=".3s">
                <ul>
                    <li><a class="page-numbers" href="#"><i class="fal fa-long-arrow-left"></i></a></li>
                    <li><a class="page-numbers" href="#">01</a></li>
                    <li><a class="page-numbers" href="#">02</a></li>
                    <li><a class="page-numbers" href="#">..</a></li>
                    <li><a class="page-numbers" href="#">10</a></li>
                    <li><a class="page-numbers" href="#">11</a></li>
                    <li><a class="page-numbers" href="#"><i class="fal fa-long-arrow-right"></i></a></li>
                </ul>
            </div>
        </div>
    </section>
</template>