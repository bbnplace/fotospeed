<template>
    <Head title="Edit Email Template"></Head>
    <BackendLayout>
        <VRow>
            <VCol cols="12" md="6">
                <Panel snippetTitle="Edit Email Template">
                    <EmailTemplateEditor :emailTemplate="emailTemplate"></EmailTemplateEditor>
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
import EmailTemplateEditor from "@/Components/Editors/EmailTemplateEditor.vue";
import TemplateCodes from '@/Components/TemplateCodes.vue';

const emailTemplate = usePage().props.emailTemplate
</script>

<style lang="scss" scoped>

</style>
