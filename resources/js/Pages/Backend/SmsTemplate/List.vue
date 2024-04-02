<template>
    <Head title="SMS Templates"></Head>
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
        add: "sms-template.add",
        edit: "sms-template.edit",
        delete: "sms-templates.delete",
        detail: "sms-template.view"
    },
    headers: [
        {
            title: "Name",
            key: "name",
            sortable: true
        },
        {
            title: "Template",
            key: "template",
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
        singular: "SMS Template",
        plural: "SMS Templates"
    }
}
</script>
