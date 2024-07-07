<template>
    <Head title="Edit Order"></Head>
    <ClientLayout>
        <Panel snippetTitle="Edit Order">
            <OrderEditor :order="details"></OrderEditor>
        </Panel>
    </ClientLayout>
</template>

<script setup>
import { usePage, Head } from "@inertiajs/vue3";
import ClientLayout from "@/Layouts/ClientLayout.vue";
import Panel from "@/Layouts/Shared/Panel.vue";
import OrderEditor from "@/Components/Editors/OrderEditor.vue";

const details = usePage().props.orderDetail;
</script>
