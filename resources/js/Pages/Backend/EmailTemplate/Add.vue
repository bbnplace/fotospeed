<template>
    <Head title="Create Email Template"></Head>
    <BackendLayout>
        <Centralize snippetTitle="Create Email Template">
            <EmailTemplateEditor></EmailTemplateEditor>
        </Centralize>
    </BackendLayout>
</template>

<script setup>
import { usePage, Head } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Centralize from "@/Layouts/Shared/Centralize.vue";
import EmailTemplateEditor from "@/Components/Editors/EmailTemplateEditor.vue"
</script>

<style lang="scss" scoped>

</style>
