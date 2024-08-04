<template>
    <Head title="Notification Templates"></Head>
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
        add: "notification-template.add",
        edit: "notification-template.edit",
        delete: "notification-templates.delete",
        detail: "notification-template.view"
    },
    headers: [
        {
            title: "Name",
            key: "name",
            sortable: true
        },
        {
            title: "Title",
            key: "title",
            sortable: true
        },
        {
            title: "Template",
            key: "template",
            sortable: false
        },
    ],
    name: {
        singular: "Notification Template",
        plural: "Notification Templates"
    }
}
</script>
