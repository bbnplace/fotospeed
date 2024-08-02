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
                                @click="downloadImage(props.orderImage.uploadedFile)"
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
            ></VTextField>
            <VTextarea
                v-model="pageData.note"
                label="Note (Optional)"
                variant="outlined"
                hide-details
            ></VTextarea>
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
