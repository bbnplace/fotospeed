<template>
    <Head title="Staff"></Head>
    <BackendLayout>
        <Link :href="route('staff.add')">Register Staff</Link>
    </BackendLayout>
</template>

<script setup>
import { usePage, Head, Link } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";

const header = [];

</script>

<style lang="scss" scoped>

</style>
