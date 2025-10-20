<script setup>
import { usePage } from '@inertiajs/vue3';
const site = usePage().props.site;
</script>
<template>
    <!-- Product Section Start -->
    <section class="product-section fix section-padding pt-0">
        <div class="container">
            <div class="section-title text-center">
                <div class="sub-title wow fadeInUp">
                    <span>our Product</span>
                </div>
                <h2 class="wow fadeInUp" data-wow-delay=".3s">
                    Related Products
                </h2>
            </div>
            <div class="swiper product-slider">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="products-box-items">
                            <ul class="product-top">
                                <li>Mug</li>
                                <li>$59.00</li>
                            </ul>
                            <div class="product-thumb">
                                <img :src="`${site.url}/assets/img/product/p-1.jpg`" alt="img">
                                <ul class="product-icon d-grid justify-content-center align-items-center">
                                    <li>
                                        <a href="shop-cart.html"><i class="far fa-heart"></i></a>
                                    </li>
                                    <li>
                                        <a href="shop-details.html">
                                            <i class="fal fa-expand-arrows"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="shop-details.html"><i class="far fa-eye"></i></a>
                                    </li>
                                </ul>
                                <a href="shop-cart.html" class="theme-btn">Add To cart</a>
                            </div>
                            <div class="product-content">
                                <h4><a href="shop-details.html">Coffee Mug</a></h4>
                                <div class="star">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="products-box-items">
                            <ul class="product-top">
                                <li>Hoodie</li>
                                <li>$70.00</li>
                            </ul>
                            <div class="product-thumb">
                                <img :src="`${site.url}/assets/img/product/p-4.jpg`" alt="img">
                                <ul class="product-icon d-grid justify-content-center align-items-center">
                                    <li>
                                        <a href="shop-cart.html"><i class="far fa-heart"></i></a>
                                    </li>
                                    <li>
                                        <a href="shop-details.html">
                                            <i class="fal fa-expand-arrows"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="shop-details.html"><i class="far fa-eye"></i></a>
                                    </li>
                                </ul>
                                <a href="shop-cart.html" class="theme-btn">Add To cart</a>
                            </div>
                            <div class="product-content">
                                <h4><a href="shop-details.html">Stylish Hoodie</a></h4>
                                <div class="star">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="products-box-items">
                            <ul class="product-top">
                                <li>Packet</li>
                                <li>$30.00</li>
                            </ul>
                            <div class="product-thumb">
                                <img :src="`${site.url}/assets/img/product/p-3.jpg`" alt="img">
                                <ul class="product-icon d-grid justify-content-center align-items-center">
                                    <li>
                                        <a href="shop-cart.html"><i class="far fa-heart"></i></a>
                                    </li>
                                    <li>
                                        <a href="shop-details.html">
                                            <i class="fal fa-expand-arrows"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="shop-details.html"><i class="far fa-eye"></i></a>
                                    </li>
                                </ul>
                                <a href="shop-cart.html" class="theme-btn">Add To cart</a>
                            </div>
                            <div class="product-content">
                                <h4><a href="shop-details.html">Packaging</a></h4>
                                <div class="star">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="products-box-items">
                            <ul class="product-top">
                                <li>Bag</li>
                                <li>$49.00</li>
                            </ul>
                            <div class="product-thumb">
                                <img :src="`${site.url}/assets/img/product/p-2.jpg`" alt="img">
                                <ul class="product-icon d-grid justify-content-center align-items-center">
                                    <li>
                                        <a href="shop-cart.html"><i class="far fa-heart"></i></a>
                                    </li>
                                    <li>
                                        <a href="shop-details.html">
                                            <i class="fal fa-expand-arrows"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="shop-details.html"><i class="far fa-eye"></i></a>
                                    </li>
                                </ul>
                                <a href="shop-cart.html" class="theme-btn">Add To cart</a>
                            </div>
                            <div class="product-content">
                                <h4><a href="shop-details.html">Shopping Bag</a></h4>
                                <div class="star">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-dot mt-2">
                    <div class="dot"></div>
                </div>
            </div>
        </div>
    </section>
</template>