<template>
    <Panel snippet-title="Product Photos">
        <v-radio-group v-model="coverPhotoId" hide-details>
        <VRow class="mb-3">
            <VCol cols="4" class="p-2" v-for="image in selectedMedia" :key="image.id">
                <v-img :src="image.thumbnail" class="shadow" aspect-ratio="1"></v-img>
                <v-radio
                    color="blue"
                    label="Make Primary"
                    :value="image.id"
                ></v-radio>
            </VCol>
        </VRow>
        </v-radio-group>
        <div>
            <v-combobox
                v-model="selectedMedia"
                :items="productImages"
                label="Select from Media Library"
                multiple
                item-title="name"
                item-value="thumbnail_100"
                return-object
                variant="outlined"
                hide-details
            >
            <template v-slot:item="{ props, item }">
                <v-list-item v-bind="props">
                    <v-list-item-avatar>
                        <v-img :src="item.value" width="100" ></v-img>
                    </v-list-item-avatar>
                </v-list-item>
            </template>
        </v-combobox>
        </div>
        <div class="mt-2">
            <VBtn
                prepend-icon="mdi-upload"
                color="grey-darken-3"
                @click="()=>{showImageUpload = !showImageUpload}"
            >Upload Product Images</VBtn>
        </div>
        <v-row>
            <v-col>
                <DropzoneUploader v-if="showImageUpload" usage="product" @file-uploaded="selectUploadedFile" />
            </v-col>
        </v-row>
        <hr />
        <div class="text-right">
            <v-progress-circular v-if="savingPhotos" indeterminate color="blue-darken-3" class="mx-2"></v-progress-circular>
            <p v-if="photosSaveSuccess.length">{{ photosSaveSuccess }}</p>
            <p v-if="photosSaveError.length">{{ photosSaveError }}</p>
            <v-btn
                color="blue-darken-3"
                @click="saveProductImages"
            >Save</v-btn>
        </div>
    </Panel>
</template>

<script setup>
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import DropzoneUploader from './DropzoneUploader.vue';
import Panel from '@/Layouts/Shared/Panel.vue';
import axios from 'axios';

const product = usePage().props.item;
const productPhotoData = JSON.parse(product.product_photos);
const selectedMedia = ref(productPhotoData ? productPhotoData.images : []);
const productImages = ref(usePage().props.productMedia ?? [])
const coverPhotoId = ref(productPhotoData ? productPhotoData.primaryPhotoId : null);
const showImageUpload = ref(false);

const selectUploadedFile = (data) => {
    const uploadedData = {
        id: data.mediaId,
        name: "",
        thumbnail: data.imageThumbnail,
        thumbnail_100: data.thumbnail100,
    };
    selectedMedia.value.push(uploadedData);
    productImages.value.push(uploadedData);
}


const savingPhotos = ref(false);
const photosSaveSuccess = ref('');
const photosSaveError = ref('');

const saveProductImages = async () => {
    savingPhotos.value = true;
    photosSaveError.value = "";
    photosSaveSuccess.value = "";

    const payload = {
        productPhotos: {
            images: selectedMedia.value,
            primaryPhotoId: coverPhotoId.value
        }
    }

    try {
        const response = await axios.put(route('item.save-photos', [product.id]), payload, {
            headers: {
                "Content-Type": "application/json"
            }
        });

        if (response.data && response.data.status == "success") {
            photosSaveSuccess.value = response.data.message;
        } else {
            photosSaveError.value = response.data.message;
        }
    } catch (error) {
        if (error.response && error.response.status === 422) {
            photosSaveError.value = error.response.data.message;
        } else {
            photosSaveError.value = "Something went wrong! Pls try again later.";
        }
    }
    savingPhotos.value = false;

    setTimeout(()=>{
        photosSaveError.value = "";
        photosSaveSuccess.value = "";
    }, 5000);
}
</script>

