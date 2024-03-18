<template>
    <Head title="Edit Branch"></Head>
    <BackendLayout>
        <Centralize snippetTitle="Edit Branch">
            <BranchEditor :branch="branch"></BranchEditor>
        </Centralize>
    </BackendLayout>
</template>

<script setup>
import { usePage, Head } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Centralize from "@/Layouts/Shared/Centralize.vue";
import BranchEditor from "@/Components/Editors/BranchEditor.vue";

const branch = usePage().props.branch;

</script>

<style lang="scss" scoped>

</style>
