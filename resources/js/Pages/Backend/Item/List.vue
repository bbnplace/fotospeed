<template>
    <Head title="Items"></Head>
    <BackendLayout>
        <Records :data="listResource"></Records>
    </BackendLayout>
</template>

<script setup>
import { usePage, Head, Link, router } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Records from  '@/Components/Records.vue';

const headers = [
    {
        title: "Item",
        key: "name",
        sortable: true
    },
    {
        title: "Category",
        key: "category.name",
        sortable: true
    },
    {
        title: "Description",
        key: "description",
        sortable: true
    },
    {
        title: "Actions",
        key: "actions",
        sortable: false,
        width: '100px'
    },
];

const listResource = {
    endpoint: {
        records: usePage().props.endpoint,
        add: "item.add",
        edit: "item.edit",
        delete: "items.delete",
        detail: "item.view"
    },
    headers,
    name: {
        singular: "Item",
        plural: "Items"
    }
}

</script>
