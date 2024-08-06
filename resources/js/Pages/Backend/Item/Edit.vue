<template>
    <Head title="Edit Product"></Head>
    <BackendLayout>
        <Panel snippetTitle="Edit Product">
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
