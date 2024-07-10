<template>
    <Head title="SMS Template Editor"></Head>
    <BackendLayout>

            <VRow>
                <VCol cols="12" md="6">
                    <Panel snippetTitle="Edit SMS Template">
                        <SMSTemplateEditor :smsTemplate="smsTemplate"></SMSTemplateEditor>
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
import SMSTemplateEditor from "@/Components/Editors/SMSTemplateEditor.vue"
import TemplateCodes from '@/Components/TemplateCodes.vue'

const smsTemplate = usePage().props.smsTemplate
</script>

<style lang="scss" scoped>

</style>
