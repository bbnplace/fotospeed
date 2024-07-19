<template>
    <Head title="Settings"></Head>
    <BackendLayout>
        <Panel snippetTitle="Settings">
            <div v-if="saveStatus" class="flex flex-row-reverse mt-3 font-bold">
                <span class="bg-lime-300 px-2 rounded">{{ saveStatus }}</span>
            </div>

            <form @submit.prevent="submit">
                <VTabs v-model="tab">
                    <VTab value="organization">Organization</VTab>
                    <VTab value="order">Order</VTab>
                    <VTab value="file">File Upload</VTab>
                    <VTab value="payment">Payment</VTab>
                    <VTab value="email">Email</VTab>
                    <VTab value="sms">SMS</VTab>
                    <VTab value="report">Report</VTab>
                </VTabs>
                <v-window v-model="tab" direction="vertical">
                    <v-window-item value="organization">
                        <VCard>
                            <h4 class="my-3">Organisation</h4>
                            <VRow>
                                <VCol>
                                    <VTextField
                                    id="org_name"
                                    v-model="form.org_name"
                                    label="Name"
                                    variant="outlined"
                                    :hide-details="form.errors.org_name == undefined"
                                    :error-messages="form.errors.org_name"
                                ></VTextField>
                                </VCol>
                            </VRow>
                            <VRow>
                                <VCol>
                                    <VTextField
                                    id="org_address"
                                    v-model="form.org_address"
                                    label="Address"
                                    variant="outlined"
                                    :hide-details="form.errors.org_address == undefined"
                                    :error-messages="form.errors.org_address"
                                ></VTextField>
                                </VCol>
                            </VRow>
                            <VRow>
                                <VCol>
                                    <VTextField
                                    id="org_phone"
                                    v-model="form.org_phone"
                                    label="Phone"
                                    variant="outlined"
                                    :hide-details="form.errors.org_phone == undefined"
                                    :error-messages="form.errors.org_phone"
                                ></VTextField>
                                </VCol>
                            </VRow>
                            <VRow>
                                <VCol>
                                    <VTextField
                                    id="org_email"
                                    v-model="form.org_email"
                                    label="Email"
                                    variant="outlined"
                                    :hide-details="form.errors.org_email == undefined"
                                    :error-messages="form.errors.org_email"
                                ></VTextField>
                                </VCol>
                            </VRow>
                            <VRow>
                                <VCol>
                                    <VTextField
                                    id="org_url"
                                    v-model="form.org_url"
                                    label="Url"
                                    variant="outlined"
                                    :hide-details="form.errors.org_url == undefined"
                                    :error-messages="form.errors.org_url"
                                ></VTextField>
                                </VCol>
                            </VRow>
                        </VCard>
                    </v-window-item>
                    <v-window-item value="file">
                        <VCard>
                            <h4 class="my-3">File Settings</h4>
                            <VRow>
                                <VCol>
                                    <VTextField
                                    id="max_file_size"
                                    v-model="form.max_file_size"
                                    label="Max. File Size (Bytes)"
                                    variant="outlined"
                                    :hide-details="form.errors.max_file_size == undefined"
                                    :error-messages="form.errors.max_file_size"
                                ></VTextField>
                                </VCol>
                            </VRow>
                            <VRow>
                                <VCol>
                                    <VTextField
                                    id="thumbnail_size"
                                    v-model="form.thumbnail_size"
                                    label="Thumbnail Size"
                                    variant="outlined"
                                    :hide-details="form.errors.thumbnail_size == undefined"
                                    :error-messages="form.errors.thumbnail_size"
                                ></VTextField>
                                </VCol>
                            </VRow>
                            <VRow>
                                <VCol>
                                    <VTextField
                                    id="file_mime_types"
                                    v-model="form.file_mime_types"
                                    label="File Mime Types"
                                    variant="outlined"
                                    :hide-details="form.errors.file_mime_types == undefined"
                                    :error-messages="form.errors.file_mime_types"
                                ></VTextField>
                                </VCol>
                            </VRow>
                        </VCard>
                    </v-window-item>
                    <v-window-item value="email">
                        <VCard>
                            <h4 class="my-3">Email Settings</h4>
                            <VRow>
                                <VCol>
                                    <VTextField
                                    id="email_host"
                                    v-model="form.email_host"
                                    label="Host"
                                    variant="outlined"
                                    :hide-details="form.errors.email_host == undefined"
                                    :error-messages="form.errors.email_host"
                                ></VTextField>
                                </VCol>
                            </VRow>
                            <VRow>
                                <VCol>
                                    <VTextField
                                    id="email_port"
                                    v-model="form.email_port"
                                    label="Port"
                                    variant="outlined"
                                    :hide-details="form.errors.email_port == undefined"
                                    :error-messages="form.errors.email_port"
                                ></VTextField>
                                </VCol>
                            </VRow>
                            <VRow>
                                <VCol>
                                    <VTextField
                                    id="email_sender_name"
                                    v-model="form.email_sender_name"
                                    label="Display Name"
                                    variant="outlined"
                                    :hide-details="form.errors.email_sender_name == undefined"
                                    :error-messages="form.errors.email_sender_name"
                                ></VTextField>
                                </VCol>
                            </VRow>
                            <VRow>
                                <VCol>
                                    <VTextField
                                    id="from_email"
                                    v-model="form.from_email"
                                    label="From Email Address"
                                    variant="outlined"
                                    :hide-details="form.errors.from_email == undefined"
                                    :error-messages="form.errors.from_email"
                                ></VTextField>
                                </VCol>
                            </VRow>
                            <VRow>
                                <VCol>
                                    <VTextField
                                    id="email_password"
                                    v-model="form.email_password"
                                    label="Password"
                                    variant="outlined"
                                    :hide-details="form.errors.email_password == undefined"
                                    :error-messages="form.errors.email_password"
                                ></VTextField>
                                </VCol>
                            </VRow>
                            <VRow>
                                <VCol>
                                    <VTextField
                                    id="replyto_email"
                                    v-model="form.replyto_email"
                                    label="Reply-To Email"
                                    variant="outlined"
                                    :hide-details="form.errors.replyto_email == undefined"
                                    :error-messages="form.errors.replyto_email"
                                ></VTextField>
                                </VCol>
                            </VRow>
                        </VCard>
                    </v-window-item>
                    <v-window-item value="order">
                        <VCard>
                            <h4 class="my-3">Order Settings</h4>
                            <VRow>
                                <VCol>
                                    <VTextField
                                    id="min_order_processing_days"
                                    v-model="form.min_order_processing_days"
                                    label="Min. Processing Days"
                                    variant="outlined"
                                    :hide-details="form.errors.min_order_processing_days == undefined"
                                    :error-messages="form.errors.min_order_processing_days"
                                ></VTextField>
                                </VCol>
                            </VRow>
                            <VRow>
                                <VCol>
                                    <VTextField
                                    id="max_order_processing_days"
                                    v-model="form.max_order_processing_days"
                                    label="Max. Processing Days"
                                    variant="outlined"
                                    :hide-details="form.errors.max_order_processing_days == undefined"
                                    :error-messages="form.errors.max_order_processing_days"
                                ></VTextField>
                                </VCol>
                            </VRow>
                        </VCard>
                    </v-window-item>
                    <v-window-item value="payment">
                        <VCard>
                            <h4 class="my-3">Payment Settings</h4>
                            <VRow>
                                <VCol>
                                    <VTextField
                                    id="paystack_public_key"
                                    v-model="form.paystack_public_key"
                                    label="Paystack Public Key"
                                    variant="outlined"
                                    :hide-details="form.errors.paystack_public_key == undefined"
                                    :error-messages="form.errors.paystack_public_key"
                                ></VTextField>
                                </VCol>
                            </VRow>
                            <VRow>
                                <VCol>
                                    <VTextField
                                    id="paystack_secret_key"
                                    v-model="form.paystack_secret_key"
                                    label="Paystack Secret Key"
                                    variant="outlined"
                                    :hide-details="form.errors.paystack_secret_key == undefined"
                                    :error-messages="form.errors.paystack_secret_key"
                                ></VTextField>
                                </VCol>
                            </VRow>
                            <VRow>
                                <VCol>
                                    <VAutocomplete
                                        id="payment_sms_temp"
                                        v-model="form.payment_sms_temp"
                                        label="Confirmation SMS Template"
                                        :items="smsTemplates"
                                        variant="outlined"
                                        :hide-details="form.errors.payment_sms_temp == undefined"
                                        :error-messages="form.errors.payment_sms_temp"
                                        density="compact"
                                    ></VAutocomplete>
                                </VCol>
                            </VRow>
                            <VRow>
                                <VCol>
                                    <VAutocomplete
                                        id="payment_email_temp"
                                        v-model="form.payment_email_temp"
                                        label="Confirmation Email Template"
                                        :items="emailTemplates"
                                        variant="outlined"
                                        :hide-details="form.errors.payment_email_temp == undefined"
                                        :error-messages="form.errors.payment_email_temp"
                                        density="compact"
                                    ></VAutocomplete>
                                </VCol>
                            </VRow>
                        </VCard>
                    </v-window-item>
                    <v-window-item value="sms">
                        <VCard>
                            <h4 class="my-3">SMS Settings</h4>
                            <VRow>
                                <VCol>
                                    <VTextField
                                    id="cecula_sync_api_key"
                                    v-model="form.cecula_sync_api_key"
                                    label="Cecula Sync API Key"
                                    variant="outlined"
                                    :hide-details="form.errors.cecula_sync_api_key == undefined"
                                    :error-messages="form.errors.cecula_sync_api_key"
                                ></VTextField>
                                </VCol>
                            </VRow>
                        </VCard>
                    </v-window-item>
                    <v-window-item value="report">
                        <VCard>
                            <h4 class="my-3">Report</h4>
                            <VRow>
                                <VCol>
                                    <VCombobox
                                        v-model="form.reportables"
                                        label="Select Report Fields to display"
                                        :items="reportStates"
                                        :search-input.sync="reportStatusSearch"
                                        multiple
                                        chips
                                        small-chips
                                        variant="outlined"
                                        :hide-details="form.errors.reportables == undefined"
                                        :error-messages="form.errors.reportables"
                                        max-errors="5"
                                    ></VCombobox>
                                </VCol>
                            </VRow>
                            <VRow>
                                <VCol>
                                    <VCombobox
                                        v-model="form.reportViewers"
                                        label="Who can view reports on dashboard?"
                                        :items="roles"
                                        :search-input.sync="reportViewersSearch"
                                        multiple
                                        chips
                                        small-chips
                                        variant="outlined"
                                        :hide-details="form.errors.reportViewers == undefined"
                                        :error-messages="form.errors.reportViewers"
                                        max-errors="5"
                                    ></VCombobox>
                                </VCol>
                            </VRow>
                        </VCard>
                    </v-window-item>
                </v-window>
                
                
                <div class="flex flex-row-reverse mt-3">
                    <VBtn
                        color="blue-darken-1"
                        type="submit"
                        :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                    >Save</VBtn>
                </div>
            </form>
        </Panel>
    </BackendLayout>
    <Snackbar :data="snackbarOption"></Snackbar>
</template>

<script setup>
import { Head, usePage, useForm } from '@inertiajs/vue3'
import BackendLayout from '@/Layouts/BackendLayout.vue';
import Panel from '@/Layouts/Shared/Panel.vue'
import { snackbarOption, showSnackbar } from '@/Composables/snackbarOptions.js';
import { onMounted, onBeforeUnmount, ref, reactive, computed } from 'vue'

const tab = ref(null);

const props = usePage().props;
const settings = props.settings;
const smsTemplates = props.smsTemplates;
const emailTemplates = props.emailTemplates;
const reportStates = props.reportStates;
const roles = props.roles;
let saveStatus = ref(null);
const reportStatusSearch = ref(null);
const reportViewersSearch = ref(null);

const form = useForm({
    max_file_size: settings.max_file_size,
    thumbnail_size: settings.thumbnail_size,
    file_mime_types: settings.file_mime_types,
    email_sender_name: settings.email_sender_name,
    from_email: settings.from_email,
    replyto_email: settings.replyto_email,
    email_host: settings.email_host,
    email_port: settings.email_port,
    email_password: settings.email_password,
    min_order_processing_days: settings.min_order_processing_days,
    max_order_processing_days: settings.max_order_processing_days,
    cecula_sync_api_key: settings.cecula_sync_api_key,
    paystack_secret_key: settings.paystack_secret_key,
    paystack_public_key: settings.paystack_public_key,
    org_name: settings.org_name,
    org_address: settings.org_address,
    org_email: settings.org_email,
    org_phone: settings.org_phone,
    org_url: settings.org_url,
    payment_sms_temp: settings.payment_sms_temp ?? 'None',
    payment_email_temp: settings.payment_email_temp ?? 'None',
    reportables: JSON.parse(settings.reportables),
    reportViewers: JSON.parse(settings.reports_permission),
    processing: false,
});

const submit = () =>{
    form.post(route('settings'), {
        onFinish: () => {
            saveStatus.value = 'Saved Changes';
            setTimeout(()=>{
                saveStatus.value = null;
            }, 5000)
        },
        onError: (errors) => {
            handleReportablesError(errors);
            handleReportViewersError(errors);
        }
    })
}

const handleReportablesError = (errors) =>{
    const errorKeys = Object.keys(errors);
    const invalidReportables = [];
    errorKeys.forEach(element => {
        if (element.indexOf('reportables.') === 0) {
            const parts = element.split('.');
            invalidReportables.push(form.reportables[parts[1]])
        }
    })
    form.errors.reportables = invalidReportables.length > 0 ? [`The following values are not supported: ${invalidReportables.join(', ')}`] : []
}

const handleReportViewersError = (errors) =>{
    const errorKeys = Object.keys(errors);
    const invalidReportViewers = [];
    errorKeys.forEach(element => {
        if (element.indexOf('reportViewers.') === 0) {
            const parts = element.split('.');
            invalidReportViewers.push(form.reportViewers[parts[1]])
        }
    })
    form.errors.reportViewers = invalidReportViewers.length > 0 ? [`The following values are not supported: ${invalidReportViewers.join(', ')}`] : []
}

</script>

<style lang="scss" scoped>

</style>
