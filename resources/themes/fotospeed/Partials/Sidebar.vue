<script setup>
import { usePage } from '@inertiajs/vue3';
const site = usePage().props.site;
</script>

<template>
    <!-- Sidebar Area Here -->
     
    <div id="targetElement" class="side_bar slideInRight side_bar_hidden">
        <div class="side_bar_overlay"></div>
        <div class="cart-title mb-50">
            <h4>Shopping cart</h4>
        </div>
        <div class="cartmini__widget">
            <div class="cartmini__widget-item">
                <div class="cartmini__thumb">
                    <a :href="`${site.url}/product-details.html`">
                   <img :src="`${site.url}/assets/img/header/product-1.jpg`" alt="img">
                </a>
                </div>
                <div class="cartmini__content">
                    <h5><a :href="`${site.url}/product-details.html`">Level Bolt Smart Lock</a></h5>
                    <div class="cartmini__price-wrapper">
                        <span class="cartmini__price">$46.00</span>
                        <span class="cartmini__quantity">x2</span>
                    </div>
                </div>
                <button class="cartmini__del"><i class="fal fa-times"></i></button>
            </div>
            <div class="cartmini__widget-item">
                <div class="cartmini__thumb">
                    <a :href="`${site.url}/product-details.html`">
                        <img :src="`${site.url}/assets/img/header/product-2.jpg`" alt="img">
                    </a>
                </div>
                <div class="cartmini__content">
                    <h5><a :href="`${site.url}/product-details.html`">Trademil for younger</a></h5>
                    <div class="cartmini__price-wrapper">
                        <span class="cartmini__price">$78.00</span>
                        <span class="cartmini__quantity">x1</span>
                    </div>
                </div>
                <button class="cartmini__del"><i class="fal fa-times"></i></button>
            </div>
            <div class="cartmini__widget-item">
                <div class="cartmini__thumb">
                    <a :href="`${site.url}/product-details.html`">
                        <img :src="`${site.url}/assets/img/header/product-3.jpg`" alt="img">
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
</template>