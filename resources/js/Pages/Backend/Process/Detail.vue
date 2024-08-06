<template>
    <Head title="Process"></Head>
    <BackendLayout>
        <Link :href="route('processes')" class="font-bold">Back</Link>
        <Panel :snippet-title="process.name">
            <VRow>
                <VCol cols="6"><b>Process Name</b><br />{{ process.name }}</VCol>
            </VRow>
            <VRow>
                <VCol><b>Description</b><br />{{ process.description ?? '-' }}</VCol>
            </VRow>
            <hr />
            <div class="mt-3 text-right">
                <Link :href="route('process.edit', process.id)" class="btn btn-dark">Modify</Link>
            </div>
        </Panel>
    </BackendLayout>
</template>

<script setup>
import { usePage, Head, Link } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Panel from "@/Layouts/Shared/Panel.vue";

const process = usePage().props.process;
</script>
