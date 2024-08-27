<template>
    <Head title="Media Library"></Head>
    <BackendLayout>
        <VRow>
            <VCol cols="8">
                
                <DropzoneUploader usage="Order" @file-uploaded="showUploadedFile" v-if="showMediaUploader" />
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
                        <VCol class="flex flex-row">
                            <div class="pt-1 pl-2">
                                <VIcon
                                    :icon="enableSelection ? 'mdi-checkbox-blank-off' : 'mdi-checkbox-blank-outline'"
                                    color="gray-darken-3"
                                    @click="toggleSelection"
                                ></VIcon> {{enableSelection ? 'Disable' : 'Enable'}} Selection
                            </div>
                            <v-checkbox
                                v-model="selectAll"
                                label="Select All"
                                hide-details
                                @change="toggleSelectAllImages"
                                v-if="enableSelection"
                            ></v-checkbox>
                            <div class="pt-1 pl-2" v-if="enableSelection && selected.length">
                                <span v-if="deletingMedia">
                                    Deleting Media <v-progress-circular indeterminate size="25" class="mr-3 ml-2"></v-progress-circular>
                                </span>
                                <span v-if="deleteSuccessResponse.length" class="mr-3">
                                    {{ deleteSuccessResponse }}
                                </span>
                                <span v-if="deleteFailureResponse.length" class="text-red mr-3">
                                    {{ deleteFailureResponse }}
                                </span>
                                <v-btn
                                    color="gray-darken-3"
                                    prepend-icon="mdi-delete"
                                >
                                    Delete
                                    <v-overlay
                                        v-model="showDeleteOverlay"
                                        activator="parent"
                                        scroll-strategy="block"
                                        location-strategy="connected"
                                    >
                                        <v-card width="350px" class="p-2">
                                            <v-card-title>Confirm Action</v-card-title>
                                            <v-card-text>
                                                <p>Are you sure you want to delete the selected media files?</p>
                                                <v-progress-circular indeterminate v-if="deletingMedia"></v-progress-circular>
                                            </v-card-text>
                                            <v-card-actions v-if="!deletingMedia">
                                                <v-btn
                                                    color="red"
                                                    @click="deleteSelectedMedia"
                                                >Continue</v-btn>
                                                <v-btn
                                                    @click="showDeleteOverlay = false"
                                                >Close</v-btn>
                                            </v-card-actions>
                                        </v-card>
                                    </v-overlay>
                                </v-btn>
                            </div>
                        </VCol>
                    </VRow>
                    <VRow>
                        <VCol cols="12" sm="6" md="4" lg="3" xl="2" v-for="image in loadedRecords" :key="image.id">
                            <div class="m-0 p-0 relative bottom-0 left-0">
                                <VCheckbox
                                    v-model="selected"
                                    :value="image.id"
                                    hide-details
                                    v-if="enableSelection"
                                    @change="toggleControlSelector"
                                >
                                </VCheckbox>
                            </div>
                            <VImg
                                :src="image.thumbnail"
                                class="shadow hover-effect"
                                @click="selectImage(image)"
                                aspect-ratio="1"
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
                                <VSelect
                                    v-model="selectedMedia.usage"
                                    label="Usage"
                                    variant="outlined"
                                    :items="mediaUsages"
                                    hide-details
                                ></VSelect>
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
                            <VCol cols="12" class="text-right">
                                <hr/>
                                <p v-if="selectedMedia.error.length" class="text-red">{{ selectedMedia.error }}</p>
                                <p v-if="selectedMedia.response.length">{{ selectedMedia.response }}</p>
                                <VBtn
                                    color="blue-darken-3"
                                    prepend-icon="mdi-content-save"
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
const selectAll = ref(false);
const itemsPerPage = ref(25);
const totalRecords = ref(0);
const loadedRecords = ref([]);
let loading = ref(false);
const search = ref("");
const page = ref(1);
const sortBy = null;
const endpoint = usePage().props.endpoint;
const mediaUsages = usePage().props.usage;

const toggleSelection = () => {
    enableSelection.value = !enableSelection.value
}

const toggleSelectAllImages = () => {
    selectAll.value ? selectAllImages() : unselectAllImages();
}

const toggleControlSelector = () => {
    selectAll.value = selected.value.length === loadedRecords.value.length;
}

const selectAllImages = () => {
    unselectAllImages();
    loadedRecords.value.forEach(element => {
        selected.value.push(element.id);
    })
}

const unselectAllImages = () => {
    selected.value = [];
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
    selectedMedia.value.usage = image.usage.charAt(0).toUpperCase() + image.usage.slice(1)
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


const showDeleteOverlay = ref(false);
const deletingMedia = ref(false);
const deleteSuccessResponse = ref("");
const deleteFailureResponse = ref("");
const deleteSelectedMedia = async () => {
    if (selected.value.length === 0) {
        // Notify user that they have not selected any contact
        return false;
    }
    showDeleteOverlay.value = false;

    const payload = {
        selections: selected.value
    }

    deletingMedia.value = true;
    try {
        const response = await axios.post(route('media.delete'), payload, {
            headers: {
                "Content-Type": "application/json"
            }
        });

        if (response.data && response.data.status == "success") {
            deleteSuccessResponse.value = response.data.message;
            selected.value = [];
            selectAll.value = false;
            loadRecords();

        }
    } catch (error) {
        if (error.response && error.response.status === 422) {
            deleteFailureResponse.value = error.response.data.message;
        } else {
            deleteFailureResponse.value = "Something went wrong! Pls try again later.";
        }
    }
    deletingMedia.value = false;

    setTimeout(()=>{
        deleteFailureResponse.value = "";
        deleteSuccessResponse.value = "";
    }, 7000);
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