<template>
    <div>
        <form @submit.prevent="submit">
            <VRow>
                <VCol cols="12" sm="6">
                    <VTextField
                        id="name"
                        v-model="form.name"
                        label="Name"
                        variant="outlined"
                        :hide-details="form.errors.name == undefined"
                        :error-messages="form.errors.name"
                    ></VTextField>
                </VCol>
            </VRow>
            <VRow>
                <VCol>
                    <VTextarea
                        id="description"
                        v-model="form.description"
                        label="Description"
                        variant="outlined"
                        :hide-details="form.errors.description == undefined"
                        :error-messages="form.errors.description"
                        density="compact"
                    ></VTextarea>
                </VCol>
            </VRow>
            <VRow>
                <VCol cols="12" sm="6">
                    <VAutocomplete
                        id="role"
                        v-model="form.role"
                        label="Team"
                        :items="roles"
                        variant="outlined"
                        :hide-details="form.errors.role == undefined"
                        :error-messages="form.errors.role"
                        density="compact"
                    ></VAutocomplete>
                </VCol>
            </VRow>
            <VRow>
                <VCol cols="12" sm="6">
                    <VAutocomplete
                        id="smsTemplate"
                        v-model="form.smsTemplate"
                        label="SMS/WhatsApp Template"
                        :items="smsTemplates"
                        variant="outlined"
                        :hide-details="form.errors.smsTemplate == undefined"
                        :error-messages="form.errors.smsTemplate"
                        density="compact"
                    ></VAutocomplete>
                </VCol>
                <VCol cols="12" sm="6">
                    <VAutocomplete
                        id="emailTemplate"
                        v-model="form.emailTemplate"
                        label="Email Template"
                        :items="emailTemplates"
                        variant="outlined"
                        :hide-details="form.errors.emailTemplate == undefined"
                        :error-messages="form.errors.emailTemplate"
                        density="compact"
                    ></VAutocomplete>
                </VCol>
            </VRow>
            <VRow>
                <VCol cols="12" sm="6">
                    <VAutocomplete
                        id="nextprocess"
                        v-model="form.nextProcess"
                        label="Next Process"
                        :items="processes"
                        variant="outlined"
                        :hide-details="form.errors.nextProcess == undefined"
                        :error-messages="form.errors.nextProcess"
                        density="compact"
                    ></VAutocomplete>
                </VCol>
            </VRow>
        </form>
    </div>
</template>

<script setup lang="ts">
import { useForm, usePage } from '@inertiajs/vue3';

interface ProcessEditor {
    id: Number,
    name: String,
    role: {
        id: Number,
        name: String
    },
    nextProcess: {
        id: Number,
        name: String
    },
    description?: String,
    smsTemplate: {
        id: Number,
        name: String
    },
    emailTemplate: {
        id: Number,
        name: String
    }
}

const props = defineProps<{
    process?: ProcessEditor
}>();

const roles = usePage().props.roles;
const processes = usePage().props.processes;
const smsTemplates = usePage().props.smstesTemplates;
const emailTemplates = usePage().props.emailTemplates;

const form = useForm({
    id: props.process ? props.process.id : "",
    name: props.process ? props.process.name : "",
    role: props.process ? props.process.role.name : "",
    description: props.process ? props.process.description : "",
    nextProcess: props.process ? props.process.nextProcess.name : "",
    smsTemplate: props.process ? props.process.smsTemplate.name : "",
    emailTemplate: props.process ? props.process.emailTemplate.name : "",
});

const submit = () => {
    if(props.process) {
        form.put(route('process.edit', [props.process.id]), {
            onFinish: () => {},
        });
    } else {
        form.post(route('process.add'), {
            onFinish: () => {},
        });
    }
};
</script>

