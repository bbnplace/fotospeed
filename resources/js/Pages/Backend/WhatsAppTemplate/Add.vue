<template>
    <Head title="WhatsApp Template Editor"></Head>
    <BackendLayout>

            <VRow>
                <VCol cols="12" md="6">
                    <Panel snippetTitle="Create WhatsApp Template">
                        <WhatsappTemplateEditor></WhatsappTemplateEditor>
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
import WhatsappTemplateEditor from "@/Components/Editors/WhatsappTemplateEditor.vue"
import TemplateCodes from '@/Components/TemplateCodes.vue'

</script>

<style lang="scss" scoped>

</style>
