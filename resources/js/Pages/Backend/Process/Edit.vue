<template>
    <Head title="Edit Process"></Head>
    <BackendLayout>
        <Panel snippetTitle="Edit Process">
            <ProcessEditor :process="process"></ProcessEditor>
        </Panel>
    </BackendLayout>
</template>

<script setup>
import { usePage, Head } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Panel from "@/Layouts/Shared/Panel.vue";
import ProcessEditor from "@/Components/Editors/ProcessEditor.vue";

const process = usePage().props.process;

</script>
