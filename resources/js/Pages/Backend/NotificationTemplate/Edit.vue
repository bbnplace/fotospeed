<template>
    <Head title="Notifications Template Editor"></Head>
    <BackendLayout>

            <VRow>
                <VCol cols="12" md="6">
                    <Panel snippetTitle="Edit Notifications Template">
                        <NotificationTemplateEditor :notificationTemplate="notificationTemplate"></NotificationTemplateEditor>
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
import NotificationTemplateEditor from "@/Components/Editors/NotificationTemplateEditor.vue"
import TemplateCodes from '@/Components/TemplateCodes.vue'

const notificationTemplate = usePage().props.notificationTemplate
</script>

<style lang="scss" scoped>

</style>
