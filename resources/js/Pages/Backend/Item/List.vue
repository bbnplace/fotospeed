<template>
    <Head title=""></Head>
    <BackendLayout>
        <Link :href="route('item.add')">Add Item</Link>
    </BackendLayout>
</template>

<script setup>
import { usePage, Head, Link } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";

const header = [];

</script>

<style lang="scss" scoped>

</style>
