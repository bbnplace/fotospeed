<template>
    <div class="single-sidebar-widget">
        <div class="wid-title">
            <h4>search here</h4>
        </div>
        <div class="search_widget">
            <form @submit.prevent="submitForm">
                <input type="text" placeholder="search here" v-model="form.search">
                <button type="submit"><i class="fal fa-search"></i></button>
            </form>
        </div>
    </div>
</template>
<script setup>
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    search: {
        type: String,
        default: null
    },
    category: {
        type: Object,
        default: null
    }
});

const form = useForm({
    search: props.search,
    category: props.category ? props.category.slug : null
});

const submitForm = () => {
    window.location.href = route('marketing.products', {
        search: form.search,
        category: form.category
    });
};
</script>