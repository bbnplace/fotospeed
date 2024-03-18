<template>
    <Head title="Rename Category"></Head>
    <BackendLayout>
        <Centralize snippetTitle="Rename Category">
            <CategoryEditor :category="category"></CategoryEditor>
        </Centralize>
    </BackendLayout>
</template>

<script setup>
import { usePage, Head } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Centralize from "@/Layouts/Shared/Centralize.vue";
import CategoryEditor from "@/Components/Editors/CategoryEditor.vue";

const category = usePage().props.category;
</script>

<style lang="scss" scoped>

</style>
