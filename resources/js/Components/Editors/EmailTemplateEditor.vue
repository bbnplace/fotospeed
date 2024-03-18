<template>
    <VRow>

    </VRow>
</template>

<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';

interface EmailTemplateEditor {
    id?: Number,
    name: String,
    template: String
}

const props = defineProps<{
    emailTemplate: EmailTemplateEditor
}>()

const form = useForm({
    id: props.emailTemplate.id,
    name: props.emailTemplate.name,
    template: props.emailTemplate.template
});

</script>

<style lang="scss" scoped>

</style>
