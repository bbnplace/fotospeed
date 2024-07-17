<template>
    <Head title="Create Email Template"></Head>
    <BackendLayout>
        <VRow>
            <VCol cols="12" md="6">
                <Panel snippetTitle="Create Email Template">
                    <EmailTemplateEditor></EmailTemplateEditor>
                </Panel>
            </VCol>
            <VCol cols="12" md="6">
                <TemplateCodes />
            </VCol>
        </VRow>
    </BackendLayout>
</template>

<script setup>
import { usePage, Head } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Panel from "@/Layouts/Shared/Panel.vue";
import EmailTemplateEditor from "@/Components/Editors/EmailTemplateEditor.vue"
import TemplateCodes from '@/Components/TemplateCodes.vue'
</script>

<style lang="scss" scoped>

</style>
