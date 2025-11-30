<template>
    <VRow class="img-upload m-1">
        <VCol cols="12" sm="4" md="5" class="flex flex-column items-center gap-2 justify-center">
            <div>
                <VOverlay
                    activator="parent"
                    location-strategy="connected"
                    scroll-strategy="close"
                >
                    <VCard class="p-4" width="350">
                        <p class="text-right">Page# {{ pageData.pageNumber }}</p>
                        <template v-if="!isPdf">
                            <VImg
                                :src="props.orderImage.imageThumbnail"
                                cover
                                class="d-flex"
                                :style="{borderRadius: '5px'}"
                            ></VImg>
                        </template>
                        <h4 class="my-2">{{ props.orderImage.fileInfo.name }}</h4>
                        <p><b>Note</b><br /> {{ pageData.note }}</p>
                        <VCol cols="12">
                            <VBtn
                                class="mx-1"
                                size="small"
                                prepend-icon="mdi-delete"
                                color="red"
                                @click="removeImage(props.orderImage.id)"
                                v-if="props.view == 'New'"
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
                    :style="{borderRadius: '5px'}"
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
import { reactive, watch, ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { saveAs } from 'file-saver';
const user = usePage().props.auth.user;

const downloadURL = route('file.fetch');

const downloading = ref(false);

interface OrderImage {
    fileReadCompleted?: boolean,
    uploadedFile: string,
    codes?: Object,
    dataURL: string,
    pageNumber: number,
    note: string,
    id: string,
    fileInfo: {
        name: string,
        size: string,
        type: string
    },
    imageThumbnail?: string
}

const props = defineProps<{
    orderImage: OrderImage,
    view: string
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

const isPdf = computed(() => {
    const ext = getFileExtension(props.orderImage.uploadedFile);
    return ext.toLowerCase() === 'pdf' || props.orderImage.fileInfo.type === 'application/pdf';
});

</script>
