<template>
    <VBtn
        @click="downloadAllAssets"
        color="blue"
        prepend-icon="mdi-download"
    >
        Download All
    </VBtn>
</template>

<script setup>
import axios from 'axios';
import { saveAs } from 'file-saver';
const downloadURL = route('file.fetch');

const recordProps = defineProps({
    files: Array
});


const downloadAsset = (imageUrl, mimeType, imageName) => {
    const downloadLink = `${downloadURL}?filepath=${encodeURIComponent(imageUrl)}&type=${mimeType}`;
    axios.get(downloadLink, { responseType: 'blob' })
    .then((response) => {
        const blob = new Blob([response.data], { type: response.headers['content-type'] });
        saveAs(blob, imageName);
    });
}

const downloadAllAssets = async () => {
    recordProps.files.forEach(image => {
        const ext = getFileExtension(image.file.uploadedFile);
        downloadAsset(image.file.uploadedFile, image.file.fileInfo.type, `page-${image.file.pageNumber}.${ext}`)
    })
}

const getFileExtension = (url) => {
    // Use a regular expression to capture the extension
    const extension = url.split('.').pop().split(/\#|\?/)[0];
    return extension;
}
</script>
