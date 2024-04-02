<template>
    <Head title="Groups"></Head>
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
        add: "group.add",
        edit: "group.edit",
        delete: "groups.delete",
        detail: "group.view"
    },
    headers: [
        {
            title: "Group",
            key: "name",
            sortable: true
        },
        {
            title: "Description",
            key: "description",
            sortable: false
        },
        {
            title: "Actions",
            key: "actions",
            sortable: false,
            width: '100px'
        },
    ],
    name: {
        singular: "Group",
        plural: "Groups"
    }
}

</script>
