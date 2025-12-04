<template>
    <Head title="Staff"></Head>
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
        add: "staff.add",
        edit: "staff.edit",
        delete: "staff.delete",
        detail: "staff.view"
    },
    headers: [
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
        },
        {
            title: "Branch",
            key: "branch.name",
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
        singular: "Staff",
        plural: "Staff"
    },
    filters: [
        { key: 'name', label: 'Name', type: 'text' },
        { key: 'mobile', label: 'Mobile', type: 'text' },
        { key: 'email', label: 'Email', type: 'text' },
        { key: 'role', label: 'Role', type: 'select', options: usePage().props.roles },
        { key: 'branch', label: 'Branch', type: 'select', options: usePage().props.branches }
    ]
}
</script>
