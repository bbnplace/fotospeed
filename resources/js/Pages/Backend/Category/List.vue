<template>
    <Head title="Item Categories"></Head>
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
        add: "category.add",
        edit: "category.edit",
        delete: "categories.delete",
        detail: "category.view"
    },
    headers: [
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
    ],
    name: {
        singular: "category",
        plural: "categories"
    }
}

</script>

