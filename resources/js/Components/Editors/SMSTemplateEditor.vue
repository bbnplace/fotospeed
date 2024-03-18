<template>
    <VRow>

    </VRow>
</template>

<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';

interface SMSTemplateEditor {
    id?: Number,
    name: String,
    template: String
}

const props = defineProps<{
    smsTemplate: SMSTemplateEditor
}>()

const form = useForm({
    id: props.smsTemplate.id,
    name: props.smsTemplate.name,
    template: props.smsTemplate.template
});

</script>

<style lang="scss" scoped>

</style>
