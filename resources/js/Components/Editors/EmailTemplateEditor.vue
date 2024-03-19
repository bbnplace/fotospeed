<template>
    <form @submit.prevent="submit">
        <div>
            <VTextField
                id="name"
                v-model="form.name"
                label="Template Name"
                variant="outlined"
                autocomplete="name"
                :hide-details="form.errors.name == undefined"
                :error-messages="form.errors.name"
            ></VTextField>
        </div>
        <div class="mt-4">
            <VTextarea
                id="template"
                v-model="form.template"
                label="Email Template"
                variant="outlined"
                :hide-details="form.errors.template == undefined"
                :error-messages="form.errors.template"
                density="compact"
            ></VTextarea>
        </div>

        <div class="flex items-center justify-end mt-4">

            <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Save
            </PrimaryButton>
        </div>
    </form>
</template>

<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

interface EmailTemplateEditor {
    id?: Number,
    name: String,
    template: String
}

const props = defineProps<{
    emailTemplate: EmailTemplateEditor
}>()

const form = useForm({
    id: props.emailTemplate ? props.emailTemplate.id: "",
    name: props.emailTemplate ? props.emailTemplate.name: "",
    template: props.emailTemplate ? props.emailTemplate.template: "",
});

const submit = () => {
    form.post(route('email-template.add'), {
        onFinish: () => {},
    });
};
</script>

<style lang="scss" scoped>

</style>
