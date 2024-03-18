<template>
    <Head title="Add Item Category"></Head>
    <BackendLayout>
        <Panel snippetTitle="Add Item">
            <ItemEditor></ItemEditor>
        </Panel>
    </BackendLayout>
</template>

<script setup>
import { usePage, Head } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Panel from "@/Layouts/Shared/Panel.vue";
import ItemEditor from "@/Components/Editors/ItemEditor.vue";
</script>

<style lang="scss" scoped>

</style>
