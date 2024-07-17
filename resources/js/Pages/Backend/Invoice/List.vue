<template>
    <Head title="Invoices"></Head>
    <BackendLayout>
        <Panel snippetTitle="Invoices">
            <Records :data="dataResources"></Records>
        </Panel>
    </BackendLayout>
</template>

<script setup>
import { Head, usePage, useForm } from '@inertiajs/vue3'
import BackendLayout from '@/Layouts/BackendLayout.vue';
import Panel from '@/Layouts/Shared/Panel.vue'
import Records from  '@/Components/Records.vue';

const dataResources = {
    endpoint: {
        records: usePage().props.endpoint,
        detail: "invoice",
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
            sortable: false
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

<style lang="scss" scoped>

</style>
