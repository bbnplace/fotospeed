<template>
    <Head title="Processes"></Head>
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
        add: "process.add",
        // edit: "process.edit",
        delete: "processes.delete",
        detail: "process.view"
    },
    headers: [
        {
            title: "Process",
            key: "name",
            sortable: true
        },
        {
            title: "Unclaimed [Orders]",
            key: "unclaimed_count",
            sortable: false,
            sortable: false,
            align: "end"
        },
        {
            title: "ToDo [Orders]",
            key: "todo_count",
            sortable: false,
            align: "end"
        },
        {
            title: "Doing [Orders]",
            key: "doing_count",
            sortable: false,
            align: "end"
        },
        {
            title: "Done [Orders]",
            key: "done_count",
            sortable: false,
            align: "end"
        },
        
    ],
    name: {
        singular: "Process",
        plural: "Processes"
    }
}

</script>
