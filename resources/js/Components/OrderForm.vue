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
                <div>{{ props.orderImage.fileInfo.name }}</div>
                <div>Size: {{ props.orderImage.fileInfo.size }}</div>
            </div>
        </VCol>
        <VCol class="flex flex-column gap-2">
            <VTextField
                v-model="pageData.copies"
                label="Copies"
                variant="outlined"
                density="compact"
                hide-details
                type="number"
            ></VTextField>
            <VTextField
                v-model="pageData.pageNumber"
                label="Page #"
                variant="outlined"
                density="compact"
                hide-details
                bg-color="rgba(10,10,10, .5)"
            ></VTextField>
            <VTextarea
                v-model="pageData.note"
                label="Note (Optional)"
                variant="outlined"
                density="compact"
                hide-details
            ></VTextarea>
        </VCol>
    </VRow>
</template>

<script setup lang="ts">
import { reactive, watch } from 'vue';

interface OrderImage {
    fileReadCompleted?: Boolean,
    uploadedFile: String,
    codes?: Object,
    dataURL: String,
    fileInfo: {
        name: String,
        size: String,
        type: String
    }
}

const props = defineProps<{
    orderImage: OrderImage
}>()

const emit = defineEmits(['pageDataUpdated']);

const pageData = reactive({
    copies: '',
    pageNumber: '',
    note: ''
});

watch(pageData, (newData, oldData) => {
    emit('pageDataUpdated', newData);
})


</script>
