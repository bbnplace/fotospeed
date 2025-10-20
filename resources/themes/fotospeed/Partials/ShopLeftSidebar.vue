<template>
    <!-- Shop Section Start -->
    <section class="shop-section fix section-padding">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-4 order-2 order-md-1">
                    <div class="shop-main-sidebar">
                        <product-search 
                            :search="props.search"
                            :category="props.category"
                        />
                        <product-categories 
                            :categories="props.categories"
                            :category="props.category"
                         />
                    </div>
                </div>
                <div class="col-lg-8 order-1 order-md-2">
                    <div class="woocommerce-notices-wrapper wow fadeInUp" data-wow-delay=".3s">
                        <p v-if="props.category || props.search">Showing <span>{{ products.from }}</span> to <span>{{ products.to }}</span> of {{ products.total }} matching records</p>
                        <p v-else>Featured Products</p>
                        <div class="form-clt">
                            <!-- <div class="nice-select" tabindex="0">
                                <span class="current">
                                    Default Sorting
                                </span>
                                <ul class="list">
                                    <li data-value="1" class="option selected focus">
                                       Default sorting
                                 </li>
                                 <li data-value="1" class="option">
                                    Sort by popularity
                                 </li>
                                 <li data-value="1" class="option">
                                    Sort by average rating
                                 </li>
                                 <li data-value="1" class="option">
                                    Sort by latest
                                 </li>
                              </ul>
                            </div>
                            <div class="icon">
                                <a href="shop.html"><i class="fas fa-list"></i></a>
                            </div>
                            <div class="icon-2">
                                <a href="shop.html"><i class="fas fa-th-large"></i></a>
                            </div> -->
                        </div>
                    </div>
                    <div class="row">
                        <product-card v-for="product in props.products.data" :key="product.id" :product="product" />
                        
                    </div>
                    <div class="page-nav-wrap mt-5 text-center wow fadeInUp" data-wow-delay=".3s">
                        <ul>
                            <li v-for="link in props.products.links" :key="link.id">
                                <a 
                                    v-if="link.url" 
                                    :class="`page-numbers${link.active ? ' current' : ''}`" 
                                    :href="link.url"
                                >
                                    <span :class="`page-numbers${link.active ? ' current' : ''}`" v-html="mutateLabel(link.label)"></span>
                                </a>
                                <span v-else :class="`page-numbers${link.active ? ' current' : ''}`" v-html="mutateLabel(link.label)"></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>

import ProductCategories from '../Components/ProductCategories.vue';
import ProductSearch from '../Components/ProductSearch.vue';
import ProductCard from '../Components/ProductCard.vue';



const props = defineProps({
    title: {
        type: String,
        default: 'Shop'
    },
    description: {
        type: String,
        default: 'Explore our products and make your purchase online.'
    },
    page: {
        type: String,
        default: 'shop'
    },
    categories: {
        type: Array,
        default: () => []
    },
    products: {
        type: Array,
        default: () => []
    },
    category: {
        type: Object,
        default: null
    },
    search: {
        type: String,
        default: null
    }
});

console.log(props.products);

const mutateLabel = (label) =>  {
    if (label === '&laquo; Previous') return '&laquo;';
    if (label === 'Next &raquo;') return '&raquo;';
    return label;
}
</script>