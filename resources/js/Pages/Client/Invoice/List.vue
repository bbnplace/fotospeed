
<template>
    <Head title="My Invoices"></Head>
    <ClientLayout>
        <Records :data="dataResources"></Records>
    </ClientLayout>
</template>

<script setup>
import { usePage, Head } from "@inertiajs/vue3";
import ClientLayout from "@/Layouts/ClientLayout.vue";
import Records from  '@/Components/Records.vue';

const dataResources = {
    endpoint: {
        records: usePage().props.endpoint,
        detail: "customer.invoice",
        // delete: "client.orders.delete"
    },
    headers: [
        {
            title: "Invoice #",
            key: "id",
            sortable: true
        },
        {
            title: "Order Name",
            key: "order.name",
            sortable: true
        },
        {
            title: "Amount",
            key: "order.total_cost",
            sortable: false
        },
        {
            title: "Actions",
            key: "actions",
            sortable: false,
            width: '100px'
        },
    ],
    name: {
        singular: "Order",
        plural: "Orders"
    }
}
</script>

