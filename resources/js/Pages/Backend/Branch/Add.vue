<template>
    <Head title="Register Branch"></Head>
    <BackendLayout>
        <Centralize snippetTitle="Register Branch">
            <BranchEditor></BranchEditor>
        </Centralize>
    </BackendLayout>
</template>

<script setup>
import { usePage, Head } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Centralize from "@/Layouts/Shared/Centralize.vue";
import BranchEditor from "@/Components/Editors/BranchEditor.vue";
</script>

<style lang="scss" scoped>

</style>
