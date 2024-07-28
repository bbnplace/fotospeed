<template>
    <Head title="WhatsApp Template Editor"></Head>
    <BackendLayout>

            <VRow>
                <VCol cols="12" md="6">
                    <Panel snippetTitle="Edit WhatsApp Template">
                        <WhatsAppTemplateEditor :whatsAppTemplate="whatsAppTemplate"></WhatsAppTemplateEditor>
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
import WhatsAppTemplateEditor from "@/Components/Editors/WhatsAppTemplateEditor.vue"
import TemplateCodes from '@/Components/TemplateCodes.vue'

const whatsAppTemplate = usePage().props.whatsAppTemplate
</script>

<style lang="scss" scoped>

</style>
