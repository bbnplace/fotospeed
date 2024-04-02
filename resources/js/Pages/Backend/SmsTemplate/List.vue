<template>
    <Head title="SMS Templates"></Head>
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
];

const listResource = {
    endpoint: {
        records: usePage().props.endpoint,
        add: "sms-template.add",
        edit: "sms-template.edit",
        delete: "sms-templates.delete",
        detail: "sms-template.view"
    },
    headers,
    name: {
        singular: "SMS Template",
        plural: "SMS Templates"
    }
}
</script>
