<template>
    <Head title="Create Order"></Head>
    <BackendLayout>
        <Panel snippetTitle="New Order">
            <OrderEditor></OrderEditor>
        </Panel>
    </BackendLayout>
</template>

<script setup>
import { usePage, Head } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Panel from "@/Layouts/Shared/Panel.vue";
import OrderEditor from "@/Components/Editors/OrderEditor.vue";
</script>

