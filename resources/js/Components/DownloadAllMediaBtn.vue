<template>
    <VBtn
        color="blue"
        prepend-icon="mdi-download"
        :disabled="downloading"
    >
        Download All
        <VOverlay
            v-model="showOverlay"
            activator="parent"
            location-strategy="connected"
            scroll-strategy="close">
            <VCard class="p-3" max-width="400">
                <VCardTitle>One Moment Please!</VCardTitle>
                <VCardText>
                    <p>You're about to start downloading {{ recordProps.files.length }} files.</p>
                <div>To keep things organized, we recommend creating a dedicated folder to store all assets for this order.</div>
                </VCardText>
                <VCardActions>
                    <VBtn
                        @click="downloadAllAssets"
                        color="blue"
                        prepend-icon="mdi-download"
                        :disabled="downloading"
                    >
                        Start Download
                    </VBtn>
                </VCardActions>
            </VCard>
        </VOverlay>
    </VBtn>
</template>

<script setup>
import { ref } from "vue";
import axios from 'axios';
import { saveAs } from 'file-saver';
const downloadURL = route('file.fetch');
const showOverlay = ref(false);

const recordProps = defineProps({
    files: Array
});

const downloading = ref(false);


const downloadAsset = (imageUrl, mimeType, imageName) => {
    downloading.value = true;
    const downloadLink = `${downloadURL}?filepath=${encodeURIComponent(imageUrl)}&type=${mimeType}`;
    axios.get(downloadLink, { responseType: 'blob' })
    .then((response) => {
        const blob = new Blob([response.data], { type: response.headers['content-type'] });
        saveAs(blob, imageName);
        downloading.value = false;
    });
}

const downloadAllAssets = async () => {
    showOverlay.value = false;
    recordProps.files.forEach(image => {
        const ext = getFileExtension(image.file.uploadedFile);
        // If the file has a page number, file name should be page#.ext otherwise use original file name
        const downloadFileName = image.file.pageNumber ? `page-${image.file.pageNumber}.${ext}` : image.file.fileInfo.name;
        downloadAsset(image.file.uploadedFile, image.file.fileInfo.type, downloadFileName);
    })
}

const getFileExtension = (url) => {
    // Use a regular expression to capture the extension
    const extension = url.split('.').pop().split(/\#|\?/)[0];
    return extension;
}
</script>
