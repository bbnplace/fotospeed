<template>
    <Head title="My Orders"></Head>
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
        add: "customer.new-order",
        edit: "client.order.edit",
        // delete: "client.orders.delete",
        detail: "client.order.view"
    },
    headers: [
        {
            title: "Order#",
            key: "order_number",
            sortable: true
        },
        {
            title: "Items",
            key: "item.name",
            sortable: true
        },
        {
            title: "Status",
            key: "order_status.name",
            sortable: false
        },
        {
            title: "Date",
            key: "created_at",
            sortable: false,
            align: 'end'
        },
    ],
    name: {
        singular: "Order",
        plural: "Orders"
    }
}

</script>

