<template>
    <Head title="Edit Item"></Head>
    <BackendLayout>
        <Panel snippetTitle="Edit Item">
            <ItemEditor :item="item"></ItemEditor>
        </Panel>
    </BackendLayout>
</template>

<script setup>
import { usePage, Head } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Panel from "@/Layouts/Shared/Panel.vue";
import ItemEditor from "@/Components/Editors/ItemEditor.vue";

const item = usePage().props.item;

</script>

<style lang="scss" scoped>

</style>
