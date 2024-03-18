<template>
    <Head title="Add Item Category"></Head>
    <BackendLayout>
        <Centralize snippetTitle="Add Category">
            <CategoryEditor></CategoryEditor>
        </Centralize>
    </BackendLayout>
</template>

<script setup>
import { usePage, Head } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Centralize from "@/Layouts/Shared/Centralize.vue";
import CategoryEditor from "@/Components/Editors/CategoryEditor.vue";
</script>

<style lang="scss" scoped>

</style>
