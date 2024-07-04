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
                <VCol cols="12">
                    <VExpansionPanels
                        v-model="switches.panels"
                    >
                        <VExpansionPanel title="Team">
                            <VRow class="p-3 pt-0">
                                <VCol cols="12" sm="6">
                                    <v-switch
                                        v-model="form.smsTeam"
                                        label="Send SMS/WhatsApp"
                                        color="#0594be"
                                        hide-details
                                    ></v-switch>

                                    <VAutocomplete
                                        id="smsTemplate"
                                        v-model="form.smsTemplate"
                                        label="SMS/WhatsApp Template"
                                        :items="smsTemplates"
                                        variant="outlined"
                                        :hide-details="form.errors.smsTemplate == undefined"
                                        :error-messages="form.errors.smsTemplate"
                                        density="compact"
                                        v-if="form.smsTeam"
                                    ></VAutocomplete>
                                </VCol>
                                <VCol cols="12" sm="6">
                                    <v-switch
                                        v-model="form.emailTeam"
                                        label="Send Email"
                                        color="#0594be"
                                        hide-details
                                    ></v-switch>

                                    <VAutocomplete
                                        id="emailTemplate"
                                        v-model="form.emailTemplate"
                                        label="Email Template"
                                        :items="emailTemplates"
                                        variant="outlined"
                                        :hide-details="form.errors.emailTemplate == undefined"
                                        :error-messages="form.errors.emailTemplate"
                                        density="compact"
                                        v-if="form.emailTeam"
                                    ></VAutocomplete>
                                </VCol>
                            </VRow>
                        </VExpansionPanel>
                        <VExpansionPanel title="Customer">
                            <VRow class="p-3 pt-0">
                                <VCol cols="12" sm="6">
                                    <v-switch
                                        v-model="form.smsCustomer"
                                        label="Send SMS/WhatsApp"
                                        color="#0594be"
                                        hide-details
                                    ></v-switch>

                                    <VAutocomplete
                                        id="smsTemplate"
                                        v-model="form.customerSmsTemplate"
                                        label="SMS/WhatsApp Template"
                                        :items="smsTemplates"
                                        variant="outlined"
                                        :hide-details="form.errors.smsTemplate == undefined"
                                        :error-messages="form.errors.smsTemplate"
                                        density="compact"
                                        v-if="form.smsCustomer"
                                    ></VAutocomplete>
                                </VCol>
                                <VCol cols="12" sm="6">
                                    <v-switch
                                        v-model="form.emailCustomer"
                                        label="Send Email"
                                        color="#0594be"
                                        hide-details
                                    ></v-switch>

                                    <VAutocomplete
                                        id="emailTemplate"
                                        v-model="form.customerEmailTemplate"
                                        label="Email Template"
                                        :items="emailTemplates"
                                        variant="outlined"
                                        :hide-details="form.errors.emailTemplate == undefined"
                                        :error-messages="form.errors.emailTemplate"
                                        density="compact"
                                        v-if="form.emailCustomer"
                                    ></VAutocomplete>
                                </VCol>
                            </VRow>
                        </VExpansionPanel>
                    </VExpansionPanels>
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
    email_customer: Boolean
}

const props = defineProps<{
    process?: ProcessEditor
}>();

const roles = usePage().props.roles;
const processes = usePage().props.processes;
const smsTemplates = usePage().props.smsTemplates;
const emailTemplates = usePage().props.emailTemplates;

const form = useForm({
    id: props.process ? props.process.id : "",
    name: props.process ? props.process.name : "",
    role: props.process ? props.process.role.name : "",
    description: props.process ? props.process.description : "",
    nextProcess: props.process ? (props.process.next_process ? props.process.next_process.name : "") : "",
    smsTeam: props.process ? (props.process.sms_team ? !!props.process.sms_team : false) : false,
    smsTemplate: props.process ? (props.process.sms_template ? props.process.sms_template.name : "") : "",
    emailTeam:  props.process ? (props.process.email_team ? !!props.process.email_team : false) : false,
    emailTemplate: props.process ? (props.process.email_template ? props.process.email_template.name : "") : "",
    smsCustomer:  props.process ? (props.process.sms_customer ? !!props.process.sms_customer : false) : false,
    customerSmsTemplate: props.process ? (props.process.customer_sms_template ? props.process.customer_sms_template.name : "") : "",
    emailCustomer: props.process ? (props.process.email_customer ? !!props.process.email_customer : false) : false,
    customerEmailTemplate: props.process ? (props.process.customer_email_template ? props.process.customer_email_template.name : "") : "",
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

