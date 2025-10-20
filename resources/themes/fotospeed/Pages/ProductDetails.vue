<template>
    <default-layout>
        <shop-details :product="product">
            <!-- <template #sidebar>
                <product-search />
                <product-categories :categories="categories" />
            </template> -->
        </shop-details>
    </default-layout>
</template>
<script setup>
import { usePage } from '@inertiajs/vue3';
import DefaultLayout from '../Layouts/DefaultLayout.vue';
import ShopDetails from '../Partials/ShopDetails.vue';

const site = usePage().props.site;
const product = usePage().props.product;
console.log('Product Details:', product);

</script>