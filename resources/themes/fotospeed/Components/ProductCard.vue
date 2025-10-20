<template>
    <div class="col-xl-4 col-lg-4 col-md-6 wow fadeInUp" data-wow-delay=".2s">
        <div class="shop-items style-2">
            <div class="shop-image">
                <a :href="route('marketing.product.show', product.slug)">
                    <img :src="route('media.view', primaryPhoto.id)" alt="img">
                </a>
                <ul class="product-icon d-grid justify-content-center align-items-center">
                    <!-- <li>
                        <a href="shop-cart.html"><i class="far fa-heart"></i></a>
                    </li> -->
                    <!-- <li>
                        <a href="shop-details.html">
                            <i class="far fa-exchange"></i>
                        </a>
                    </li> -->
                    <li>
                        <a :href="route('marketing.product.show', product.slug)"><i class="far fa-eye"></i></a>
                    </li>
                    <li>
                        <a :href="route('customer.new-order')"><i class="fal fa-cart-plus"></i></a>
                    </li>
                </ul>
                <div class="offer-text" v-if="product.on_promo">-20%</div>
            </div>
            <div class="shop-content">
                <h5><a :href="route('marketing.product.show', product.slug)">{{ product.name }}</a></h5>
                <!-- <h5><a :href="route('customer.new-order')">{{ product.name }}</a></h5> -->
                <ul class="price-list">
                    <li>From {{ formatMoney(product.starting_price) }}</li>
                </ul>
                <div class="shop-btn">
                    <!-- <a href="shop-details.html" class="theme-btn">
                        <span> Add to cart</span>
                    </a> -->
                    <a :href="route('customer.logged-in', {product: product.slug})" class="mt-3 theme-btn" style="padding: 10px 15px !important; border-radius: 5px;">
                        <span> Request Similar Job</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3';
import { usePrimaryPhoto, useFormatMoney } from '../Composables/usePrimaryPhoto';

const site = usePage().props.site;

const props = defineProps({
    product: {
        type: Object,
        required: true
    }
});

const product = props.product;

const { getPrimaryPhotoData } = usePrimaryPhoto();
const primaryPhoto = getPrimaryPhotoData(product);

const { formatMoney } = useFormatMoney();

</script>