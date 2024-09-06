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
            <h5 class="m-2">Optional Information</h5>
            <v-col cols="12">
                <v-select
                    v-model="form.usage"
                    label="Usage"
                    :items="usages"
                    variant="outlined"
                    :hide-details="form.errors.usage == undefined"
                    :error-messages="form.errors.usage"
                ></v-select>
            </v-col>
            <v-col cols="6">
                <v-select
                    v-model="form.timing"
                    label="Timing"
                    :items="timings"
                    variant="outlined"
                    :hide-details="form.errors.usage == undefined"
                    :error-messages="form.errors.usage"
                ></v-select>
            </v-col>
            <v-col cols="6">
                <v-select
                    v-model="form.target"
                    label="Target"
                    :items="targets"
                    variant="outlined"
                    :hide-details="form.errors.usage == undefined"
                    :error-messages="form.errors.usage"
                ></v-select>
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
import { useForm, usePage } from '@inertiajs/vue3';

interface EmailTemplateEditor {
    id?: Number,
    name: String,
    template: String,
    usage?: String,
    target?: String,
    timing?: String,
}

const props = defineProps<{
    emailTemplate: EmailTemplateEditor
}>()

const usages = usePage().props.usage;
 const targets = usePage().props.targets;
 const timings = usePage().props.timings;

const form = useForm({
    id: props.emailTemplate ? props.emailTemplate.id: "",
    name: props.emailTemplate ? props.emailTemplate.name: "",
    template: props.emailTemplate ? props.emailTemplate.template: "",
    usage: props.emailTemplate ? props.emailTemplate.usage : "",
    target: props.emailTemplate ? props.emailTemplate.target : "",
    timing: props.emailTemplate ? props.emailTemplate.timing : "",
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
