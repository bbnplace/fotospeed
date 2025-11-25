<template>
    <Head title="Email Templates"></Head>
    <BackendLayout>
        <Panel snippetTitle="Email Templates">
            <div v-if="emailMethod === 'API' && emailProvider" class="mb-4">
                <v-btn
                    color="blue-darken-1"
                    prepend-icon="mdi-sync"
                    @click="syncProviderTemplates"
                    :loading="syncing"
                    :disabled="syncing"
                >
                    Sync {{ emailProvider }} Templates
                </v-btn>
                <v-alert v-if="syncMessage" :type="syncMessageType" class="mt-2">
                    {{ syncMessage }}
                </v-alert>
            </div>
            <Records :data="dataResources"></Records>
        </Panel>
    </BackendLayout>
    <Snackbar :data="snackbarOption"></Snackbar>
</template>

<script setup>
import { usePage, Head } from "@inertiajs/vue3";
import { ref } from 'vue';
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Panel from "@/Layouts/Shared/Panel.vue";
import Records from  '@/Components/Records.vue';
import Snackbar from '@/Components/Snackbar.vue';
import axios from 'axios';

const props = usePage().props;
const emailProvider = props.emailProvider;
const emailMethod = props.emailMethod;
const syncEndpoint = props.syncEndpoint;

const syncing = ref(false);
const syncMessage = ref('');
const syncMessageType = ref('success');
const snackbarOption = ref({});

const syncProviderTemplates = async () => {
    syncing.value = true;
    syncMessage.value = '';

    try {
        const response = await axios.post(syncEndpoint);
        
        if (response.data.status === 'success') {
            syncMessage.value = response.data.message;
            syncMessageType.value = 'success';
            
            // Reload the page to show the synced templates
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        }
    } catch (error) {
        syncMessage.value = error.response?.data?.message || 'Failed to sync templates';
        syncMessageType.value = 'error';
    } finally {
        syncing.value = false;
    }
};

const dataResources = {
    endpoint: {
        records: props.endpoint,
        // add: "email-template.add", // Disabled - templates are synced from providers
        edit: "email-template.edit",
        delete: "email-templates.delete",
        detail: "email-template.view"
    },
    headers: [
        {
            title: "Name",
            key: "name",
            sortable: true
        },
        {
            title: "Template",
            key: "template",
            sortable: false
        },
        {
            title: "Provider",
            key: "provider",
            sortable: true
        },
    ],
    name: {
        singular: "Email Template",
        plural: "Email Templates"
    }
}

</script>
