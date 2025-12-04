<template>
    <Head title="Inbox"></Head>
    <BackendLayout>
        <Records :data="dataResources"></Records>
    </BackendLayout>
</template>

<script setup>
import { usePage, Head } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Records from  '@/Components/Records.vue';

const dataResources = {
    endpoint: {
        records: usePage().props.endpoint,
        // add: "order.add",
        edit: "order.edit",
        // delete: "orders.delete",
        detail: "order.view"
    },
    headers: [
        {
            title: "Order #",
            key: "order_number",
            sortable: true
        },
        {
            title: "Customer",
            key: "user.name",
            sortable: true
        },
        {
            title: "Product",
            key: "item.name",
            sortable: true
        },
        {
            title: "Origin Branch",
            key: "source_branch.name",
            sortable: true
        },
        {
            title: "Status",
            key: "order_status.name",
            sortable: false
        },
        {
            title: "Last Updated",
            key: "updated_at",
            sortable: false,
            align: "end"
        }
    ],
    name: {
        singular: "Order",
        plural: "Orders"
    },
    name: {
        singular: "Order",
        plural: "Orders"
    },
    filters: [
        { key: 'order_number', label: 'Order #', type: 'text' },
        { key: 'customer_name', label: 'Customer Name', type: 'text' },
        { key: 'product', label: 'Product', type: 'select', options: usePage().props.products },
        { key: 'status', label: 'Status', type: 'select', options: usePage().props.statuses }
    ]
}

</script>

