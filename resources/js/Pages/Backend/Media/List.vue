<template>
    <Head title="Media Library"></Head>
    <BackendLayout>
        <VRow>
            <VCol cols="8">
                
                <DropzoneUploader @file-uploaded="showUploadedFile" v-if="showMediaUploader" />
                <Panel snippet-title="Media Library">
                    <div>
                        <VTextField
                            label="Filter Media"
                            variant="outlined"
                            append-inner-icon="mdi-magnify"
                            v-model="search"
                            @keyup="loadRecords"
                            :loading="loading"
                        >
                        </VTextField>
                    </div>
                    <VRow>
                        <VCol>
                            <VIcon
                                :icon="enableSelection ? 'mdi-checkbox-blank-off' : 'mdi-checkbox-blank-outline'"
                                color="blue"
                                @click="toggleSelection"
                        ></VIcon> {{enableSelection ? 'Disable' : 'Enable'}} Selection
                        </VCol>
                    </VRow>
                    <VRow>
                        <VCol cols="12" sm="6" md="4" lg="3" xl="2" v-for="image in loadedRecords" :key="image.id">
                            <VCheckbox
                                class="m-0 p-0"
                                hide-details
                                v-if="enableSelection"
                            >
                            </VCheckbox>
                            <VImg
                                :src="image.thumbnail"
                                class="shadow hover-effect"
                                @click="selectImage(image)"
                                cover
                                width="100%"
                                :height="enableSelection ? '60%' : '80%'"
                            >
                            </VImg>
                            <div class="text-center p-2">
                                {{ image.name ?? 'Not Named' }}
                            </div>
                        </VCol>
                    </VRow>
                </Panel>
            </VCol>
            <VCol cols="4">
                <VRow>
                    <VCol cols="12" class="text-right mt-3">
                        <VBtn
                            prepend-icon="mdi-multimedia"
                            color="red"
                            @click="showMediaUploader = true"
                        >Upload Media</VBtn>
                    </VCol>
                </VRow>
                <Panel snippet-title="Media Details">
                    <form @submit.prevent="updateMediaDetails">
                        <VRow v-if="selectedMedia.thumbnail">
                            <VCol cols="12">
                                <VImg
                                    :src="selectedMedia.thumbnail"
                                    class="shadow"
                                >
                                </VImg>
                            </VCol>
                            <VCol cols="12">
                                <VTextField
                                    v-model="selectedMedia.name"
                                    label="Name"
                                    variant="outlined"
                                    hide-details
                                ></VTextField>
                            </VCol>
                            <VCol cols="12">
                                <VTextarea
                                    v-model="selectedMedia.description"
                                    label="Description"
                                    variant="outlined"
                                    hide-details
                                ></VTextarea>
                            </VCol>
                            <VCol cols="12">
                                <div class="grid grid-cols-2">
                                    <div>Uploaded By</div>
                                    <div class="font-bold">{{ selectedMedia.uploadedBy }}</div>
                                    <div>Date Uploaded</div>
                                    <div class="font-bold">{{ selectedMedia.created }}</div>
                                    <div>Last Modified</div>
                                    <div class="font-bold">{{ selectedMedia.lastUpdated }}</div>
                                </div>
                            </VCol>
                            <VCol cols="12">
                                <p v-if="selectedMedia.error.length" class="text-red">{{ selectedMedia.error }}</p>
                                <p v-if="selectedMedia.response.length">{{ selectedMedia.response }}</p>
                                <VBtn
                                    color="blue-darken-3"
                                    :disabled="updatingMediaSettings"
                                    type="submit"
                                >Update</VBtn>
                            </VCol>
                        </VRow>
                        <VRow v-else>
                            <VCol cols="12">
                                <div class="text-center p-4 shadow">
                                    Select a Media File.
                                </div>
                            </VCol>
                        </VRow>
                    </form>
                </Panel>
            </VCol>
        </VRow>
    </BackendLayout>
</template>

<script setup>
import { ref } from 'vue';
import BackendLayout from '@/Layouts/BackendLayout.vue';
import Panel from '@/Layouts/Shared/Panel.vue'
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';
import moment from 'moment';
import DropzoneUploader from '@/Components/DropzoneUploader.vue';

const showMediaUploader = ref(false);

const enableSelection= ref(false)
const selected = ref([]);
const itemsPerPage = ref(25);
const totalRecords = ref(0);
const loadedRecords = ref([]);
let loading = ref(false);
const search = ref("");
const page = ref(1);
const sortBy = null;
const endpoint = usePage().props.endpoint;

const toggleSelection = () => {
    enableSelection.value = !enableSelection.value
}

let source = null;

const loadRecords = async () => {
    const payload = {
        page: page.value,
        itemsPerPage: itemsPerPage.value,
        sortBy,
        search: search.value
    }
    
    loading.value = true;
    if(source) source.cancel('Request cancelled by user');
    source = axios.CancelToken.source();
    const response = await axios.post(endpoint, payload, {
        headers: {
            "Content-Type": "application/json"
        },
        cancelToken: source.token
    });
    loadedRecords.value = response.data.records;
    totalRecords.value = response.data.totalRecords
    loading.value = false;
}

const showUploadedFile = () => {
    loadRecords();
}

loadRecords();

const selectedMedia = ref({
    thumbnail: "",
    name: "",
    description: "",
    uploadedBy: "",
    created: "",
    lastUpdated: "",
    id: "",
    error: "",
    response: ""
});

const selectImage = image => {
    selectedMedia.value.id = image.id;
    selectedMedia.value.thumbnail = image.thumbnail;
    selectedMedia.value.name = image.name;
    selectedMedia.value.description = image.description ?? "";
    selectedMedia.value.uploadedBy = image.staff.name == undefined ? image.customer.name : image.staff.name;
    selectedMedia.value.created = moment(image.created_at).format('LL');
    selectedMedia.value.lastUpdated = moment(image.updated_at).format('LL');
    selectedMedia.value.error = "";
    selectedMedia.value.response = "";
}

const mediaUploadResponse = ref("");
const mediaUploadError = ref("");
const updatingMediaSettings = ref(false);

const updateMediaDetails = async () => {
    const payload = selectedMedia.value
    updatingMediaSettings.value = true;
    selectedMedia.value.error = ""
    selectedMedia.value.response = ""

    try {
        const response = await axios.put(route('media.edit', [payload.id]), payload, {
            headers: {
                "Content-Type": "application/json"
            }
        })

        if (response.data && response.data.status == "success") {
            selectedMedia.value.response = response.data.response;
            loadRecords();
        }
    } catch (error) {
        if (error.response && error.response.status === 422) {
            selectedMedia.value.error = error.response.data.message;
        } else {
            selectedMedia.value.error = "Something went wrong! Pls try again later.";
        }
    }
    updatingMediaSettings.value = false;
}

</script>

<style lang="scss" scoped>
    .hover-effect {
    transition: transform 0.3s ease;
    }

    .hover-effect:hover {
    transform: scale(1.03); /* Slight zoom effect */
    }

    .v-label, label{
        font-size: 10px !important;
    }
</style>