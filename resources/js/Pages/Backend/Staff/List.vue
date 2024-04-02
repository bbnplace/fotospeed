<template>
    <Head title="Staff"></Head>
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
        title: "Name",
        key: "name",
        sortable: true
    },
    {
        title: "Role",
        key: "role.name",
        sortable: true
    },
    {
        title: "Mobile",
        key: "mobile",
        sortable: true
    },
    {
        title: "Email",
        key: "email",
        sortable: true
    }, {
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
        add: "staff.add",
        edit: "staff.edit",
        delete: "staff.delete",
        detail: "staff.view"
    },
    headers,
    name: {
        singular: "Staff",
        plural: "Staff"
    }
}

</script>
