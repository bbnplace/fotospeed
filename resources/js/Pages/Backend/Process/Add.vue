<template>
    <Head title="Process Editor"></Head>
    <BackendLayout>
        <Centralize snippetTitle="Create Process">
            <ProcessEditor></ProcessEditor>
        </Centralize>
    </BackendLayout>
</template>

<script setup>
import { usePage, Head } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Centralize from "@/Layouts/Shared/Centralize.vue";
import ProcessEditor from "@/Components/Editors/ProcessEditor.vue"

</script>

<style lang="scss" scoped>

</style>
