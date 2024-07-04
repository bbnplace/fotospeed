<template>
    <Head title="Process Editor"></Head>
    <BackendLayout>
        <Panel snippetTitle="Create Process">
            <ProcessEditor></ProcessEditor>
        </Panel>
    </BackendLayout>
</template>

<script setup>
import { usePage, Head } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Panel from "@/Layouts/Shared/Panel.vue";
import ProcessEditor from "@/Components/Editors/ProcessEditor.vue"

</script>

<style lang="scss" scoped>

</style>
