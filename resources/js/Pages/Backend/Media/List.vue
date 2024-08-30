<template>
    <Head title="Media Library"></Head>
    <BackendLayout>
        <VRow>
            <VCol cols="8">
                
                <DropzoneUploader usage="Order" @file-uploaded="showUploadedFile" v-if="showMediaUploader" />
                <Panel snippet-title="Media Library">
                    <div>
                        <form @submit.prevent="loadRecords">
                            <VTextField
                            label="Filter Media"
                            variant="outlined"
                            append-inner-icon="mdi-magnify"
                            v-model="search"
                            @click:append-inner="loadRecords"
                            :loading="loading"
                        >
                        </VTextField>
                        </form>
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
                                    color="red"
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
                        <VCol cols="12" v-if="cannotDeleteMessage.length" class="text-red">
                            <v-alert
                                type="error"
                                :text="cannotDeleteMessage"
                                closable
                                v-if="cannotDeleteMessage.length"
                            ></v-alert>
                        </VCol>
                        <VCol cols="12" class="text-right" v-if="mediaRecords.total">Showing {{ mediaRecords.from }} to {{ mediaRecords.to }} of {{ mediaRecords.total }} files.</VCol>
                    </VRow>
                    <VRow class="shadow p-2">
                        <VCol cols="12" sm="6" md="4" lg="3" xl="2" v-for="image in loadedRecords" :key="image.id" :class="image.isInUse ? 'bg-red-100 border-1 border-red-200' : ''">
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
                    <VRow>
                        <VCol cols="12" class="text-center mt-2">
                            <Link v-for="(pageLink, index) in paginationLinks" :key="index" :href="pageLink.url" :class="`btn rounded ${pageLink.active ? 'bg-black' : ''}`">
                                <span v-html="pageLink.label"></span>
                            </Link>
                        </VCol>
                    </VRow>
                </Panel>
            </VCol>
            <VCol cols="4">
                <VRow>
                    <VCol cols="12" class="text-right mt-3">
                        <VBtn
                            prepend-icon="mdi-multimedia"
                            color="grey-darken-3"
                            @click="showMediaUploader = true"
                        >Upload Media</VBtn>
                    </VCol>
                </VRow>
                <Panel snippet-title="Media Details" class="sticky top-0">
                    <form @submit.prevent="updateMediaDetails">
                        <VRow v-if="selectedMedia.thumbnail">
                            <VCol cols="12">
                                <VImg
                                    :src="selectedMedia.thumbnail"
                                    class="shadow"
                                >
                                </VImg>
                            </VCol>
                            <VCol cols="12" v-if="mediaOrders.length || mediaProducts.length">
                                
                                <div v-if="mediaOrders.length">
                                    <b>Linked Orders</b>
                                    <ul>
                                        <li v-for="order in mediaOrders" :key="order.id" class="list-disc pt-1"><Link class="underline underline-offset-2" :href="route('order.view', [order.id])">{{ order.name }}</Link></li>
                                    </ul>
                                </div>
                                <div v-if="mediaProducts.length">
                                    <b>Linked Products</b>
                                    <ul>
                                        <li v-for="product in mediaProducts" :key="product.id" class="list-disc pt-1"><Link class="underline underline-offset-2" :href="route('item.view', [product.id])">{{ product.name }}</Link></li>
                                    </ul>
                                </div>
                            </VCol>
                            <VCol cols="12">
                                <VTextField
                                    v-model="selectedMedia.name"
                                    label="Name"
                                    variant="outlined"
                                    hide-details
                                    density="compact"
                                ></VTextField>
                            </VCol>
                            <VCol cols="12">
                                <VTextarea
                                    v-model="selectedMedia.description"
                                    label="Description"
                                    variant="outlined"
                                    hide-details
                                    density="compact"
                                ></VTextarea>
                            </VCol>
                            <VCol cols="12">
                                <VSelect
                                    v-model="selectedMedia.usage"
                                    label="Usage"
                                    variant="outlined"
                                    :items="mediaUsages"
                                    hide-details
                                    density="compact"
                                ></VSelect>
                            </VCol>
                            <VCol cols="12" class="text-right">
                                <p v-if="selectedMedia.error.length" class="text-red">{{ selectedMedia.error }}</p>
                                <p v-if="selectedMedia.response.length">{{ selectedMedia.response }}</p>
                                <VBtn
                                    color="blue-darken-3"
                                    prepend-icon="mdi-content-save"
                                    :disabled="updatingMediaSettings"
                                    type="submit"
                                >Save</VBtn>
                            </VCol>
                            <VCol cols="12">
                                <div class="grid grid-cols-2 font-sm mt-3">
                                    <div>Uploaded By</div>
                                    <div>{{ selectedMedia.uploadedBy }}</div>
                                    <div>Date Uploaded</div>
                                    <div>{{ selectedMedia.created }}</div>
                                    <div>Last Modified</div>
                                    <div>{{ selectedMedia.lastUpdated }}</div>
                                </div>
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
import { usePage, Link, router } from '@inertiajs/vue3';
import axios from 'axios';
import moment from 'moment';
import DropzoneUploader from '@/Components/DropzoneUploader.vue';

const showMediaUploader = ref(false);

const enableSelection= ref(false)
const selected = ref([]);
const selectAll = ref(false);
const mediaRecords = usePage().props.records.records;
const loadedRecords = ref(mediaRecords.data);
const paginationLinks = mediaRecords.links;

let loading = ref(false);
const search = ref(usePage().props.records.searchPhrase);
const page = ref(1);
const mediaUsages = usePage().props.usage;
const mediaProducts = ref([]);
const mediaOrders = ref([]);

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
        search: search.value
    }
    
    router.visit(route('media', payload))
}

const showUploadedFile = () => {
    loadRecords();
}

// loadRecords();

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
    mediaOrders.value = [];
    mediaProducts.value = [];
    selectedMedia.value.id = image.id;
    selectedMedia.value.thumbnail = image.thumbnail;
    selectedMedia.value.name = image.name;
    selectedMedia.value.usageData = JSON.parse(image.data);
    selectedMedia.value.description = image.description ?? "";
    selectedMedia.value.uploadedBy = image.staff.name == undefined ? image.customer.name : image.staff.name;
    selectedMedia.value.created = moment(image.created_at).format('LL');
    selectedMedia.value.lastUpdated = moment(image.updated_at).format('LL');
    selectedMedia.value.error = "";
    selectedMedia.value.response = "";
    selectedMedia.value.usage = image.usage.charAt(0).toUpperCase() + image.usage.slice(1)

    if (selectedMedia.value.usageData.orders || selectedMedia.value.usageData.products) {
        getMediaUsageInfo(selectedMedia.value.usageData);
    }
}

const getMediaUsageInfo = async usageData => {
    const payload = {
        data: usageData
    }

    try {
        const response = await axios.post(route('media.usage'), payload, {
            headers: {
                "Content-Type": "application/json"
            }
        })

        if (response.data && response.data.status == 'success') {
            // Prepare Presentation with the response data
            mediaProducts.value = response.data.products;
            mediaOrders.value = response.data.orders;
        }
    } catch (error) {
        
    }
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

const clearMediaInUseHighlights = () => {
    for (let index = 0; index < loadedRecords.value.length; index++) {
        loadedRecords.value[index]['isInUse'] = false;
    }
}

const showDeleteOverlay = ref(false);
const deletingMedia = ref(false);
const deleteSuccessResponse = ref("");
const deleteFailureResponse = ref("");
const cannotDeleteMessage = ref("");
const deleteSelectedMedia = async () => {
    showDeleteOverlay.value = false;
    cannotDeleteMessage.value = "";

    clearMediaInUseHighlights();

    let imagesInUse = 0
    for (let ind = 0; ind < selected.value.length; ind++) {
        const selectedItem = selected.value[ind];
        for (let index = 0; index < loadedRecords.value.length; index++) {
            const element = loadedRecords.value[index];
            if (element.id == selectedItem) {
                const elementUsageData = JSON.parse(element.data);
                loadedRecords.value[index]['isInUse'] = elementUsageData != null && elementUsageData.products && elementUsageData.products.length > 0;
                if(loadedRecords.value[index]['isInUse']){
                    imagesInUse++;
                }
            }
        }
    }
    
    if(imagesInUse > 0) {
        cannotDeleteMessage.value = "The highlighted files cannot be deleted because they are currently in use. To see how these files are being used, click on each file and check the media details panel. To delete the file, you must first disconnect them from the utilities they are associated with."
        return false;
    }

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
            cannotDeleteMessage.value = error.response.data.message;
            const mediaData = error.response.data.mediaData ?? []
            if (mediaData.length) {
                for (let ind = 0; ind < mediaData.length; ind++) {
                const selectedItem = mediaData[ind];
                for (let index = 0; index < loadedRecords.value.length; index++) {
                    const element = loadedRecords.value[index];
                    if (element.id == selectedItem.mediaId) {
                        loadedRecords.value[index]['isInUse'] = element.id == selectedItem.mediaId;
                    }
                }
            }
            }
        } else {
            cannotDeleteMessage.value = "Something went wrong! Pls try again later.";
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