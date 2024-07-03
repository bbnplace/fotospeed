<template>
    <Head title="Edit Order"></Head>
    <BackendLayout>
        <Panel snippetTitle="Edit Order">
            <OrderEditor :order="details"></OrderEditor>
        </Panel>
    </BackendLayout>
</template>

<script setup>
import { usePage, Head } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Panel from "@/Layouts/Shared/Panel.vue";
import OrderEditor from "@/Components/Editors/OrderEditor.vue";

const details = usePage().props.orderDetail;
</script>
