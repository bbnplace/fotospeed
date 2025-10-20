<template>
    <DefaultLayout>
        <ShopLeftSidebar 
            :categories="categories"
            :products="products"
            :category="category"
            :search="search"
        />
    </DefaultLayout>
</template>

<script setup>
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import DefaultLayout from '../Layouts/DefaultLayout.vue';
import ShopLeftSidebar from '../Partials/ShopLeftSidebar.vue';
const page = usePage();
const categories = ref(page.props.categories || []);
const products = ref(page.props.products || []);
const category = ref(page.props.category || null);
const search = ref(page.props.search || null);

</script>