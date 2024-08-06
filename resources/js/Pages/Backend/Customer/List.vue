<template>
    <Head title="Customers"></Head>
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
        add: "customer.add",
        edit: "customer.edit",
        delete: "customers.delete",
        detail: "customer.view"
    },
    headers: [
        {
            title: "Name",
            key: "name",
            sortable: true
        },
        // {
        //     title: "Role",
        //     key: "role.name",
        //     sortable: true
        // },
        {
            title: "Mobile",
            key: "mobile",
            sortable: true
        },
        // {
        //     title: "Email",
        //     key: "email",
        //     sortable: true
        // },
        {
            title: "State",
            key: "state.name",
            sortable: true
        },
        {
            title: "Registered",
            key: "created_at",
            sortable: false,
            align: "end"
        },
    ],
    name: {
        singular: "customer",
        plural: "customers"
    }
}

</script>

