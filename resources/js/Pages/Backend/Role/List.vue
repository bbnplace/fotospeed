<template>
    <Head title="Roles"></Head>
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
        add: "role.add",
        edit: "role.edit",
        delete: "roles.delete",
        detail: "role.view"
    },
    headers: [
        {
            title: "Role",
            key: "name",
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
        singular: "role",
        plural: "roles"
    }
}

</script>

