<template>
    <Head title="Email Templates"></Head>
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
        add: "email-template.add",
        edit: "email-template.edit",
        delete: "email-templates.delete",
        detail: "email-template.view"
    },
    headers,
    name: {
        singular: "Email Template",
        plural: "Email Templates"
    }
}

</script>
