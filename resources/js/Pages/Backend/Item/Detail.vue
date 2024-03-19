<template>
    <Head title="Item"></Head>
    <BackendLayout>
        <Centralize :snippet-title="item.name">
            <VRow>
                <VCol cols="6">Item Name<br />{{ item.name }}</VCol>
                <VCol cols="6">Category<br />{{ item.category.name }}</VCol>
            </VRow>
            <VRow>
                <VCol>Description<br />{{ item.description }}</VCol>
            </VRow>
            <VRow>
                <VCol cols="4">Height<br />{{ item.height }}</VCol>
                <VCol cols="4">Width<br />{{ item.width }}</VCol>
                <VCol cols="4">Weight<br />{{ item.weight }}</VCol>
            </VRow>
            <VRow>
                <VCol cols="4">Print Price<br />{{ item.print_price }}</VCol>
                <VCol cols="4">Sheet Price<br />{{ item.sheet_price }}</VCol>
                <VCol cols="4">Cover Print<br />{{ item.cover_print_price }}</VCol>
            </VRow>
            <div class="mt-3">
                <Link :href="route('item.edit', item.id)" class="btn btn-secondary">Modify</Link>
            </div>
        </Centralize>
    </BackendLayout>
</template>

<script setup>
import { usePage, Head, Link } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Centralize from "@/Layouts/Shared/Centralize.vue";

const item = usePage().props.item;
</script>
