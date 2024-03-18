<template>
    <div id="myDropzone" class="my-3 p-5 gap-3">
        <div>
            <v-icon
                size="x-large"
            >
                mdi-file
            </v-icon>
        </div>
        <p>Drop Media File here to upload.</p>
        <div v-if="readingFile || uploadingFile">
            <v-progress-circular v-if="readingFile" indeterminate color="red"></v-progress-circular>
            <v-progress-circular v-if="uploadingFile" :model-value="fileUploadProgress" color="red"></v-progress-circular>
            <span class="ml-3">{{ fileAction }}</span>
        </div>
    </div>
</template>

<script setup>
// This component uploads file and emits the file name, path and other meta data through a file-analyzed event.
import { ref, onMounted, reactive } from 'vue';
import { Dropzone } from 'dropzone';

const emit = defineEmits(['fileUploaded']);
const props = defineProps({

});

let fileUploadProgress = 0;
let uploadingFile = false;
let readingFile = ref(false);
let fileAction = ref("");

onMounted(()=>{
    initDropzone()
});

const inputData = reactive({
    fileReadCompleted: false,
    uploadedFile: "",
    codes: {},
    fileInfo: {},
    dataURL: ''
});

const initDropzone = () => {
    const dropzoneUpload = new Dropzone("#myDropzone", {
        url: route("file.upload"),
        paramName: "files", // Name that will be used to transfer the file
        // Additional Dropzone options...
        headers: {
            'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
        },
        success: handleSuccess,
        error: handleError
    });

    dropzoneUpload.on("sending", handleSendingStarted);
    dropzoneUpload.on("uploadprogress", handleUploadProgress);
}

const handleSendingStarted = (file, xhr, formData) => {
    uploadingFile = true;
    fileAction.value = "Uploading File ...";
}

const handleUploadProgress = (file, progress, bytesSent) => {
    fileUploadProgress = progress;
    fileAction.value = `${progress}% Uploaded`;
}

const handleSuccess =  async (file, response) => {
    // console.log(file)
    uploadingFile = false;
    fileAction.value = "";

    inputData.fileInfo = {
        name: file.name,
        size: formatBytes(file.size),
        type: file.type
    }
    inputData.uploadedFile = response.path;
    inputData.dataURL = file.dataURL;

    // Run a method to goto system and read first line of the file to extract heading.
    const payload = {
        filepath: response.path
    }

    // This will be used to assign names, description and instructions to each file
    emit('fileUploaded', inputData);
}

const handleError = (file, error) => {
    // Handle upload errors
    uploadingFile = false;
    console.error('File upload error:', error);
}

const formatBytes = bytes => {
    if (bytes === 0) return '0 Bytes';

    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));

    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}


</script>

<style scoped>
#myDropzone {
  border: 2px solid #0594be;
  padding: 20px;
  background-color: #ffffff;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  border-radius: 8px;
  font-size: 16px;
}




</style>
