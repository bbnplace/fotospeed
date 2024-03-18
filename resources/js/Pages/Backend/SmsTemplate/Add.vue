<template>
    <Head title="SMS Template Editor"></Head>
    <BackendLayout>
        <Centralize snippetTitle="Create SMS Template">
            <SMSTemplateEditor></SMSTemplateEditor>
        </Centralize>
    </BackendLayout>
</template>

<script setup>
import { usePage, Head } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Centralize from "@/Layouts/Shared/Centralize.vue";
import SMSTemplateEditor from "@/Components/Editors/SMSTemplateEditor.vue"

</script>

<style lang="scss" scoped>

</style>
