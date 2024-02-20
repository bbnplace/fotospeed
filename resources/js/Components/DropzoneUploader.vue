<template>
    <div id="myDropzone" class="my-5 p-5 gap-3">
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

const emit = defineEmits(['fileAnalyzed']);
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
    uploadingFile = false;
    fileAction.value = "";

    inputData.fileInfo = {
        name: file.name,
        size: file.size,
        type: file.type
    }
    inputData.uploadedFile = response.path;

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


</script>

<style scoped>
#myDropzone {
  border: 2px solid #3d3d3d;
  padding: 20px;
  background-color: #d4d4d4;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  border-radius: 15px;
  font-size: 16px;
}

.v-progress-circular {
  margin: 1rem auto;
}
</style>
