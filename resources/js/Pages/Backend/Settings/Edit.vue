<template>
    <Head title="Settings"></Head>
    <BackendLayout>
        <Panel snippetTitle="Settings">
            <div v-if="saveStatus" class="flex flex-row-reverse mt-3 font-bold">
                <span class="bg-lime-300 px-2 rounded">{{ saveStatus }}</span>
            </div>

            <form @submit.prevent="submit">
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
import { onMounted, onBeforeUnmount, ref } from 'vue'

const props = usePage().props;
const settings = props.settings;
let saveStatus = ref("");

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
    processing: false
})

const submit = () =>{
    form.post(route('settings'), {
        onFinish: () => {
            saveStatus.value = 'Saved Changes';
            // showSnackbar('Saved Changes')
        }
    })
}

onMounted(()=>{
    console.log('Mounted')
})
</script>

<style lang="scss" scoped>

</style>
