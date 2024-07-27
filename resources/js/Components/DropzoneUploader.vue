<template>
    <div id="myDropzone" class="my-3 p-5 gap-3">
        <div>
            <v-icon
                size="x-large"
            >
                mdi-file
            </v-icon>
        </div>
        <p>Click here or Drop Media File here to upload.</p>
        <div v-if="readingFile || uploadingFile" class="w-50">
            <!-- <v-progress-circular v-if="readingFile" indeterminate color="red"></v-progress-circular> -->
            <v-progress-linear v-if="uploadingFile" :model-value="fileUploadProgress" color="#0594be"></v-progress-linear>
            <span class="ml-3 w-50">{{ fileAction }}</span>
        </div>
        <div v-if="uploadErrors" class="text-red">
            <p>The following errors occurred during file upload:</p>
            <ul>
                <li class="list-disc" v-for="(error, index) in uploadErrors" :key="index">
                    {{ error }}
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup>
// This component uploads file and emits the file name, path and other meta data through a file-analyzed event.
import { ref, onMounted, reactive } from 'vue';
import { usePage } from  '@inertiajs/vue3';
import { Dropzone } from 'dropzone';
import { v4 as uuidv4 } from 'uuid';

const emit = defineEmits(['fileUploaded']);
const props = defineProps({
});


let fileUploadProgress = ref(0);
let uploadingFile = ref(false);
let readingFile = ref(false);
let fileAction = ref("");
let uploadErrors = ref("");
const csrfToken = usePage().props.stkn

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

const uploadParamName = 'files';
const initDropzone = () => {
    const dropzoneUpload = new Dropzone("#myDropzone", {
        url: route("file.upload"),
        paramName: uploadParamName, // Name that will be used to transfer the file
        // Additional Dropzone options...
        headers: {
            'X-CSRF-TOKEN': csrfToken,
        },
        success: handleSuccess,
        error: handleError
    });

    dropzoneUpload.on("sending", handleSendingStarted);
    dropzoneUpload.on("uploadprogress", handleUploadProgress);
}

const handleSendingStarted = (file, xhr, formData) => {
    uploadingFile.value = true;
    fileAction.value = "Uploading File ...";
    uploadErrors.value = "";
}

const handleUploadProgress = (file, progress, bytesSent) => {
    fileUploadProgress.value = progress;
    fileAction.value = `${Math.round(progress)}% Uploaded`;
}

const handleSuccess =  async (file, response) => {
    uploadingFile.value = false;
    fileAction.value = "";

    inputData.fileInfo = {
        name: file.name,
        size: formatBytes(file.size),
        type: file.type
    }
    inputData.uploadedFile = response.path;
    inputData.dataURL = file.dataURL;
    inputData.id = uuidv4();

    // Run a method to goto system and read first line of the file to extract heading.
    const payload = {
        filepath: response.path
    }

    // This will be used to assign names, description and instructions to each file
    emit('fileUploaded', inputData);
}

const handleError = (file, response) => {
    // Handle upload errors
    uploadingFile.value = false;
    uploadErrors.value = response.errors[uploadParamName];
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
