<template>
    <Head title="Branches"></Head>
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
        add: "branch.add",
        edit: "branch.edit",
        delete: "branches.delete",
        detail: "branch.view"
    },
    headers: [
        {
            title: "Branch",
            key: "name",
            sortable: true
        },
        {
            title: "Address",
            key: "address",
            sortable: true
        },
        {
            title: "State",
            key: "state.name",
            sortable: true
        },
        // {
        //     title: "Actions",
        //     key: "actions",
        //     sortable: false,
        //     width: '100px'
        // },
    ],
    name: {
        singular: "branch",
        plural: "branches"
    }
}
</script>
