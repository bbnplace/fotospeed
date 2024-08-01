<template>
    <Head title="States"></Head>
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
        add: "state.add",
        edit: "state.edit",
        delete: "states.delete",
        detail: "state.view"
    },
    headers: [
        {
            title: "State",
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
        singular: "State",
        plural: "States"
    }
}

</script>
