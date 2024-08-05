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
            <!-- <VRow>
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
            </VRow> -->
            <hr />
            <div class="flex flex-row-reverse mt-3">
                <VBtn
                    color="blue-darken-1"
                    type="submit"
                    :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                >Save</VBtn>
            </div>
        </form>
    </div>
</template>

<script setup lang="ts">
import { useForm, usePage } from '@inertiajs/vue3';
import { reactive } from 'vue';

const switches = reactive({
    panels: []
})

interface ProcessEditor {
    id: Number,
    name: String,
    role: {
        id: Number,
        name: String
    },
    next_process: {
        id: Number,
        name: String
    },
    description?: String,
    sms_template: {
        id: Number,
        name: String
    },
    email_template: {
        id: Number,
        name: String
    },
    customer_sms_template: {
        id: Number,
        name: String
    },
    customer_email_template: {
        id: Number,
        name: String
    },
    sms_team: Boolean,
    email_team: Boolean,
    sms_customer: Boolean,
    email_customer: Boolean,
    report_process: Boolean,
    report_as: String,
}

const props = defineProps<{
    process?: ProcessEditor
}>();

// const roles = usePage().props.roles;
// const processes = usePage().props.processes;
// const smsTemplates = usePage().props.smsTemplates;
// const emailTemplates = usePage().props.emailTemplates;
// const reportStates = usePage().props.reportStates;

const form = useForm({
    id: props.process ? props.process.id : "",
    name: props.process ? props.process.name : "",
    // role: props.process ? (props.process.role ? props.process.role.name : "") : "",
    description: props.process ? props.process.description : "",
    smsTeam: props.process ? (props.process.sms_team ? !!props.process.sms_team : false) : false,
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

