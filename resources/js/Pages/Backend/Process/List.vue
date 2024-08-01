<template>
    <Head title="Processes"></Head>
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
        add: "process.add",
        edit: "process.edit",
        delete: "processes.delete",
        detail: "process.view"
    },
    headers: [
        {
            title: "Process",
            key: "name",
            sortable: true
        },
        // {
        //     title: "Category",
        //     key: "category.name",
        //     sortable: true
        // },
        // {
        //     title: "Description",
        //     key: "description",
        //     sortable: true
        // },
        // {
        //     title: "Actions",
        //     key: "actions",
        //     sortable: false,
        //     width: '100px'
        // },
    ],
    name: {
        singular: "Process",
        plural: "Processes"
    }
}

</script>
