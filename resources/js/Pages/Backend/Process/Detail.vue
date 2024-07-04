<template>
    <Head title="Process"></Head>
    <BackendLayout>
        <Panel :snippet-title="process.name">
            <VRow>
                <VCol cols="6"><b>Process Name</b><br />{{ process.name }}</VCol>
            </VRow>
            <VRow>
                <VCol><b>Description</b><br />{{ process.description ?? '-' }}</VCol>
            </VRow>
            <VRow>
                <VCol cols="4"><b>Team</b><br />{{ process.role ? process.role.name : "-" }}</VCol>
                <VCol cols="4"><b>SMS Template</b><br /><VIcon icon="mdi-circle" :color="process.sms_team == 1 ? 'green' : 'red'"></VIcon> {{ process.sms_template ? process.sms_template.name : "-" }}</VCol>
                <VCol cols="4"><b>Email Template</b><br /><VIcon icon="mdi-circle" :color="process.email_team == 1 ? 'green' : 'red'"></VIcon> {{ process.email_template ? process.email_template.name : "-" }}</VCol>
            </VRow>
            <VRow>
                <VCol cols="4"></VCol>
                <VCol cols="4"><b>Customer SMS Template</b><br /><VIcon icon="mdi-circle" :color="process.sms_customer == 1 ? 'green' : 'red'"></VIcon> {{ process.sms_template ? process.sms_template.name : "-" }}</VCol>
                <VCol cols="4"><b>Customer Email Template</b><br /><VIcon icon="mdi-circle" :color="process.email_customer == 1 ? 'green' : 'red'"></VIcon> {{ process.email_template ? process.email_template.name : "-" }}</VCol>
            </VRow>
            <VRow>
                <VCol cols="4"><b>Next Process</b><br />{{ process.next_process ? process.next_process.name : "-" }}</VCol>
            </VRow>
            <div class="mt-3">
                <Link :href="route('process.edit', process.id)" class="btn btn-secondary">Modify</Link>
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
