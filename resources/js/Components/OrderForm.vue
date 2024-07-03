<template>
    <VRow class="img-upload m-1">
        <VCol cols="12" sm="4" md="5" class="flex flex-column items-center gap-2 justify-center">
            <div>
                <VImg
                    :src="props.orderImage.dataURL"
                    :width="175"
                    :height="175"
                    cover
                    class="d-flex"
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
            ></VTextField>
            <VTextarea
                v-model="pageData.note"
                label="Customer's Note (Optional)"
                variant="outlined"
                hide-details
            ></VTextarea>
        </VCol>
        <VCol cols="12">
            <VBtn
                class="mx-1"
                size="small"
                prepend-icon="mdi-delete"
                color="white"
                @click="removeImage(props.orderImage.id)"
                >
            Remove</VBtn>
            <VBtn
                v-if="props.view != 'New'"
                class="mx-1"
                size="small"
                prepend-icon="mdi-download"
                color="blue-darken-1"
                @click="downloadImage(props.orderImage.uploadedFile)"
                >
            Download</VBtn>
        </VCol>
    </VRow>
    <VRow>

    </VRow>
</template>

<script setup lang="ts">
import { reactive, watch } from 'vue';
import { router } from  '@inertiajs/vue3';

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

const downloadImage = file => {
    const data = {
        filepath: file
    }
    window.open(route('file.download', data), '_blank');
}

const removeImage = id => {
    emit('pageRemoved', {
        id
    });
}

watch(pageData, (newData, oldData) => {
    emit('pageDataUpdated', newData);
})


</script>
