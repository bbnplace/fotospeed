<template>
    <Head title="WhatsApp Templates"></Head>
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
        add: "whatsapp-template.add",
        edit: "whatsapp-template.edit",
        delete: "whatsapp-templates.delete",
        detail: "whatsapp-template.view"
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
            title: "Category",
            key: "category",
            sortable: false
        },
        {
            title: "Status",
            key: "status",
            sortable: false
        }
    ],
    name: {
        singular: "WhatsApp Template",
        plural: "WhatsApp Templates"
    }
}
</script>
