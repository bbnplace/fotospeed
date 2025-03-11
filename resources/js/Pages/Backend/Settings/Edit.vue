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
                    <VTab value="messaging">Messaging</VTab>
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
                            <h4 class="my-3">Order Files / Space Management</h4>

                            <VRow>
                                <VCol cols="12">
                                    <p>To manage storage space, consider setting up automatic deletion of files attached to orders after a specified period. Define the duration for which these files should be retained before they are automatically deleted.</p>
                                    <v-select
                                        v-model="form.auto_delete_order_files_after"
                                        label="Select Duration"
                                        variant="outlined"
                                        :items="['Two Weeks', 'One Month', 'Three Months', 'Six Months', 'One Year', 'Two Years', 'Forever']"
                                        :hide-details="form.errors.auto_delete_order_files_after == undefined"
                                        :error-messages="form.errors.auto_delete_order_files_after"
                                    ></v-select>
                                </VCol>
                            </VRow>
                            <VRow>
                                <VCol cols="12">
                                    <p>At what order status should the file be eligible for deletion? Choose the Order Statuses when the files will no longer be needed.</p>
                                    <v-combobox
                                        v-model="form.order_file_delible_states"
                                        label="Select Statuses"
                                        variant="outlined"
                                        :items="reportStates"
                                        :hide-details="form.errors.order_file_delible_states == undefined"
                                        :error-messages="form.errors.order_file_delible_states"
                                        multiple
                                        chips
                                        small-chips
                                    ></v-combobox>
                                </VCol>
                            </VRow>
                        </VCard>
                    </v-window-item>
                    <v-window-item value="messaging">
                        <VCard>
                            <h4 class="my-3">SMS</h4>
                            <VRow>

                            </VRow>
                            <VRow>
                                <VCol>
                                    <VSelect
                                        id="sms_type"
                                        v-model="form.sms_type"
                                        label="Select SMS Origin"
                                        variant="outlined"
                                        :items="['SIM', 'A2P']"
                                        :hide-details="form.errors.sms_type == undefined"
                                        :error-messages="form.errors.sms_type"
                                    ></VSelect>
                                </VCol>
                                <VCol v-if="form.sms_type == 'SIM'">
                                    <VTextField
                                        id="cecula_sync_api_key"
                                        v-model="form.cecula_sync_api_key"
                                        label="Hosted SIM API Key"
                                        variant="outlined"
                                        :hide-details="form.errors.cecula_sync_api_key == undefined"
                                        :error-messages="form.errors.cecula_sync_api_key"
                                    ></VTextField>
                                </VCol>
                                <VCol v-if="form.sms_type == 'A2P'">
                                    <VTextField
                                        id="cecula_a2p_api_key"
                                        v-model="form.cecula_a2p_api_key"
                                        label="A2P API Key"
                                        variant="outlined"
                                        :hide-details="form.errors.cecula_a2p_api_key == undefined"
                                        :error-messages="form.errors.cecula_a2p_api_key"
                                    ></VTextField>
                                </VCol>
                            </VRow>
                            <h4 class="my-3">WhatsApp</h4>
                            <VRow>
                                <VCol>
                                    <VTextField
                                    id="wa_phone_id"
                                    v-model="form.wa_phone_id"
                                    label="Phone Number ID"
                                    variant="outlined"
                                    :hide-details="form.errors.wa_phone_id == undefined"
                                    :error-messages="form.errors.wa_phone_id"
                                ></VTextField>
                                </VCol>
                            </VRow>
                            <VRow>
                                <VCol>
                                    <VTextField
                                    id="wa_access_token"
                                    v-model="form.wa_access_token"
                                    label="Access Token"
                                    variant="outlined"
                                    :hide-details="form.errors.wa_access_token == undefined"
                                    :error-messages="form.errors.wa_access_token"
                                ></VTextField>
                                </VCol>
                            </VRow>
                            <h4 class="my-3">Email</h4>
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
                            <VRow>
                                <VCol>
                                    <VSelect
                                        id="invoice_number_src"
                                        v-model="form.invoice_no_src"
                                        label="Invoice Number Source"
                                        variant="outlined"
                                        :items="['Order Reference Number', 'System Generated']"
                                        :hide-details="form.errors.invoice_no_src == undefined"
                                        :error-messages="form.errors.invoice_no_src"
                                    ></VSelect>
                                </VCol>
                            </VRow>
                        </VCard>
                    </v-window-item>
                    <v-window-item value="payment">
                        <VCard>
                            <h4 class="my-3">Paystack</h4>
                            <VRow>
                                <VCol>
                                    <VTextField
                                    id="paystack_public_key"
                                    v-model="form.paystack_public_key"
                                    label="Public Key"
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
                                    label="Secret Key"
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
                            <h4 class="my-3">Offline Payment</h4>
                            <VRow>
                                <VCol>
                                    <VCheckbox
                                        id="support_offline_payment"
                                        v-model="form.support_offline_payment"
                                        label="Enable Support"
                                        :hide-details="form.errors.support_offline_payment == undefined"
                                        :error-messages="form.errors.support_offline_payment"
                                    ></VCheckbox>
                                </VCol>
                            </VRow>
                            <VRow>
                                <VCol>
                                    <VAutocomplete
                                        id="who_approves_offline_payment"
                                        v-model="form.who_approves_offline_payment"
                                        label="Who Approves?"
                                        :items="roles"
                                        variant="outlined"
                                        :hide-details="form.errors.who_approves_offline_payment == undefined"
                                        :error-messages="form.errors.who_approves_offline_payment"
                                        density="compact"
                                    ></VAutocomplete>
                                </VCol>
                            </VRow>
                            <h4 class="my-3">Loyalty Reward</h4>
                            <VRow>
                                <VCol>
                                    <VTextField
                                    id="loyalty_reward_formula"
                                    v-model="form.loyalty_reward_formula"
                                    label="Price Multiplier"
                                    variant="outlined"
                                    :hide-details="form.errors.loyalty_reward_formula == undefined"
                                    :error-messages="form.errors.loyalty_reward_formula"
                                    hint="Do something"
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
import { Head, usePage, useForm } from '@inertiajs/vue3';
import BackendLayout from '@/Layouts/BackendLayout.vue';
import Panel from '@/Layouts/Shared/Panel.vue';
import { onMounted, onBeforeUnmount, ref, reactive, computed } from 'vue';

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

const normalizeReportables = (reportables) => {
    const normalizedReportables = [];
    const initialReportables = JSON.parse(reportables);
    initialReportables.forEach(element => {
        normalizedReportables.push(element.replace('_', ' ').replace(/\b\w/g, function (char) {
            return char.toUpperCase();
        }));
    });
    return normalizedReportables;
}

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
    cecula_a2p_api_key: settings.cecula_a2p_api_key,
    sms_type: settings.sms_type ?? 'A2P',
    paystack_secret_key: settings.paystack_secret_key,
    paystack_public_key: settings.paystack_public_key,
    loyalty_reward_formula: settings.loyalty_reward_formula,
    wa_phone_id: settings.wa_phone_id,
    wa_access_token: settings.wa_access_token,
    org_name: settings.org_name,
    org_address: settings.org_address,
    org_email: settings.org_email,
    org_phone: settings.org_phone,
    org_url: settings.org_url,
    invoice_no_src: settings.invoice_no_src,
    payment_sms_temp: settings.payment_sms_temp ?? 'None',
    payment_email_temp: settings.payment_email_temp ?? 'None',
    reportables: normalizeReportables(settings.reportables),
    reportViewers: JSON.parse(settings.reports_permission),
    processing: false,
    support_offline_payment: settings.support_offline_payment == 1,
    who_approves_offline_payment: settings.who_approves_offline_payment,
    order_file_delible_states: settings.order_file_delible_states ? JSON.parse(settings.order_file_delible_states) : ['Delivered'],
    auto_delete_order_files_after: settings.auto_delete_order_files_after ?? 'Two Weeks',
});



const submit = () =>{
    form.post(route('settings'), {
        onFinish: () => {
            saveStatus.value = "Saved Changes";
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
            invalidReportViewers.push(form.reportViewers[parts[1]]);
        }
    })
    form.errors.reportViewers = invalidReportViewers.length > 0 ? [`The following values are not supported: ${invalidReportViewers.join(', ')}`] : []
}

</script>
