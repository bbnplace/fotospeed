<template>
    <DefaultLayout>
        <div class="container my-5">
            <Records :data="dataResources"></Records>
        </div>
    </DefaultLayout>
</template>

<script setup>
import DefaultLayout from '../../../Layouts/DefaultLayout.vue';
import { usePage, Head } from "@inertiajs/vue3";
import Records from  '@/Components/Records.vue';

const invoiceRefSrc = usePage().props.invoice_no_src;

const dataResources = {
    endpoint: {
        records: usePage().props.endpoint,
        detail: "customer.invoice",
        // delete: "client.orders.delete"
    },
    headers: [
        {
            title: "Invoice #",
            key: invoiceRefSrc == "System Generated" ? "id" : "order.order_number",
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
        singular: "Invoice",
        plural: "Invoices"
    }
}
</script>

