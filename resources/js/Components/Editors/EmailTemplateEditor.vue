<template>
    <form @submit.prevent="submit">
        <v-row>
            <v-col cols="12">
                <VTextField
                    id="name"
                    v-model="form.name"
                    label="Template Name"
                    variant="outlined"
                    autocomplete="name"
                    :hide-details="form.errors.name == undefined"
                    :error-messages="form.errors.name"
                ></VTextField>
            </v-col>
            <v-col cols="12">
                <VTextarea
                    id="template"
                    v-model="form.template"
                    label="Email Template"
                    variant="outlined"
                    :hide-details="form.errors.template == undefined"
                    :error-messages="form.errors.template"
                    density="compact"
                ></VTextarea>
            </v-col>

            <v-col class="flex items-center justify-end">
                <v-btn
                    prepend-icon="mdi-content-save"
                    :disabled="form.processing"
                    color="grey-darken-3"
                    type="submit"
                >Save</v-btn>
            </v-col>
        </v-row>
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
    if(props.emailTemplate) {
        form.put(route('email-template.edit', [props.emailTemplate.id]), {
            onFinish: () => {},
        });
    } else {
        form.post(route('email-template.add'), {
            onFinish: () => {},
        });
    }
};
</script>

<style lang="scss" scoped>

</style>
