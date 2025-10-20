<template>
    <div class="single-sidebar-widget">
        <div class="wid-title">
            <h4>Catagories</h4>
        </div>
        <div class="shop-catagory-items">
            <ul>
                <li v-for="category in props.categories" :key="category.id">
                    <a :href="`${route('marketing.products')}?category=${category.slug}`" :class="`d-flex justify-content-between align-items-center${props.category?.slug === category.slug ? ' active' : ''}`">
                        {{ category.name }} ({{ category.items_count ?? 0 }})
                    </a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    categories: {
        type: Array,
        default: () => []
    },
    category: {
        type: Object,
        default: null
    }
});
</script>