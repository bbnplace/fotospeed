<template>
    <VRow class="img-upload m-1">
        <VCol cols="12" sm="4" md="5" class="flex flex-column items-center gap-2 justify-center">
            <div>
                <VOverlay
                    activator="parent"
                    location-strategy="connected"
                    scroll-strategy="close"
                >
                    <VCard class="p-4">
                        <p class="text-right">Page# {{ pageData.pageNumber }}</p>
                        <VImg
                            :src="props.orderImage.imageThumbnail"
                            cover
                            class="d-flex"
                            style="{borderRadius: 5}"
                        ></VImg>
                        <h4 class="my-2">{{ props.orderImage.fileInfo.name }}</h4>
                        <p><b>Note</b><br /> {{ pageData.note }}</p>
                        <VCol cols="12">
                            <VBtn
                                class="mx-1"
                                size="small"
                                prepend-icon="mdi-delete"
                                color="red"
                                @click="removeImage(props.orderImage.id)"
                                >
                            Remove Page</VBtn>
                            <VBtn
                                v-if="props.view != 'New'"
                                class="mx-1"
                                size="small"
                                prepend-icon="mdi-download"
                                color="blue-darken-1"
                                @click="downloadAsset"
                                :disabled="downloading"
                                >
                            Download</VBtn>
                        </VCol>
                    </VCard>
                </VOverlay>
                <VImg
                    :src="props.orderImage.imageThumbnail"
                    :width="175"
                    :height="175"
                    cover
                    class="d-flex cursor-pointer"
                    style="{borderRadius: 5}"
                ></VImg>
            </div>
            <div>
                <div class="text-caption">{{ props.orderImage.fileInfo.name }}</div>
                <div class="text-caption">Size: {{ props.orderImage.fileInfo.size }}</div>
            </div>
        </VCol>
        <VCol class="flex flex-column gap-2">
            <VTextField
                v-model="pageData.pageNumber"
                label="Page #"
                variant="outlined"
                density="compact"
                hide-details
                :disabled="props.view == 'Detail'"
            ></VTextField>
            <VTextarea
                v-model="pageData.note"
                label="Note (Optional)"
                variant="outlined"
                hide-details
                :disabled="props.view == 'Detail'"
            ></VTextarea>
        </VCol>
        
    </VRow>
    <VRow>

    </VRow>
</template>

<script setup lang="ts">
import { reactive, watch, ref } from 'vue';
import axios from 'axios';
import { saveAs } from 'file-saver';
const downloadURL = route('file.fetch');

const downloading = ref(false);

interface OrderImage {
    fileReadCompleted?: Boolean,
    uploadedFile: String,
    codes?: Object,
    dataURL: String,
    pageNumber: Number,
    note: String,
    id: String,
    fileInfo: {
        name: String,
        size: String,
        type: String
    }
}

const props = defineProps<{
    orderImage: OrderImage,
    view: String
}>()

const emit = defineEmits(['pageDataUpdated', 'pageRemoved']);

const pageData = reactive({
    pageNumber: props.orderImage.pageNumber,
    note: props.orderImage.note
});


const removeImage = id => {
    emit('pageRemoved', {
        id
    });
}

watch(pageData, (newData, oldData) => {
    emit('pageDataUpdated', newData);
})

const downloadAsset = () => {
    downloading.value = true;
    const ext = getFileExtension(props.orderImage.uploadedFile);
    const imageName = pageData.pageNumber ? `page-${pageData.pageNumber}.${ext}` : props.orderImage.fileInfo.name;
    const imageUrl = props.orderImage.uploadedFile;
    const mimeType = props.orderImage.fileInfo.type;
    const downloadLink = `${downloadURL}?filepath=${encodeURIComponent(imageUrl)}&type=${mimeType}`;
    axios.get(downloadLink, { responseType: 'blob' })
    .then((response) => {
        const blob = new Blob([response.data], { type: response.headers['content-type'] });
        saveAs(blob, imageName);
        downloading.value = false;
    });
}


const getFileExtension = (url) => {
    // Use a regular expression to capture the extension
    const extension = url.split('.').pop().split(/\#|\?/)[0];
    return extension;
}
</script>
