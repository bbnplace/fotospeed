<template>
    <Head title="Edit Email Template"></Head>
    <BackendLayout>
        <Centralize snippetTitle="Edit Email Template">
            <EmailTemplateEditor :emailTemplate="emailTemplate"></EmailTemplateEditor>
        </Centralize>
    </BackendLayout>
</template>

<script setup>
import { usePage, Head } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Centralize from "@/Layouts/Shared/Centralize.vue";
import EmailTemplateEditor from "@/Components/Editors/EmailTemplateEditor.vue"

const emailTemplate = usePage().props.emailTemplate
</script>

<style lang="scss" scoped>

</style>
