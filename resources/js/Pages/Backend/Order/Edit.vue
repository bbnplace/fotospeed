<template>
    <Head title="Edit Order"></Head>
    <BackendLayout>
        <Link class="font-bold" :href="route('order.view', order.id)">Back to Order</Link>
        <Panel snippetTitle="Edit Order">
            <OrderEditor :order="details"></OrderEditor>
        </Panel>
    </BackendLayout>
</template>

<script setup>
import { usePage, Head, Link } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Panel from "@/Layouts/Shared/Panel.vue";
import OrderEditor from "@/Components/Editors/OrderEditor.vue";

const order = usePage().props.order;
const details = usePage().props.orderDetail;
</script>
