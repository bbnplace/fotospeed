<template>
    <Head title="Branches"></Head>
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
        add: "branch.add",
        edit: "branch.edit",
        delete: "branches.delete",
        detail: "branch.view"
    },
    headers,
    name: {
        singular: "branch",
        plural: "branches"
    }
}
</script>
