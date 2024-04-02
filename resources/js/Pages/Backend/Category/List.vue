<template>
    <Head title="Item Categories"></Head>
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
        title: "Category",
        key: "name",
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
        add: "category.add",
        edit: "category.edit",
        delete: "categories.delete",
        detail: "category.view"
    },
    headers,
    name: {
        singular: "category",
        plural: "categories"
    }
}

</script>

