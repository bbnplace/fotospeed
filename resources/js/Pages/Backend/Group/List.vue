<template>
    <Head title="Groups"></Head>
    <BackendLayout>
        <Link :href="route('group.add')">Create Group</Link>
    </BackendLayout>
</template>

<script setup>
import { usePage, Head, Link } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";

const header = [];

</script>

<style lang="scss" scoped>

</style>
