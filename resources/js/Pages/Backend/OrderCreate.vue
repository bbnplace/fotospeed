<template>
    <Head title="Create Order"></Head>
    <BackendLayout>
        Create Order

        <div class="flex flex-row-reverse">
            <VBtn
                color="blue-darken-1"
                @click="submitOrder"
            >Save</VBtn>
        </div>
        <h4 class="mt-3">Select Item</h4>
        <VRow>
            <VCol>
                <VAutocomplete
                    v-model="orderForm.item"
                    label="Item"
                    :items="items"
                    variant="outlined"
                    density="compact"
                    hide-details
                ></VAutocomplete>
            </VCol>
        </VRow>
        <h4 class="mt-3">Upload Album Pages</h4>
        <template v-if="orderForm.orderFiles">
            <VRow>
                <VCol cols="12" lg="6" v-for="orderFile, index in orderForm.orderFiles" :key="index">
                    <OrderForm
                        :orderImage="orderFile.file"
                        @pageDataUpdated="(data) => {
                            updatePageData(data, orderFile)
                        }"
                    ></OrderForm>
                </VCol>
            </VRow>

        </template>
        <DropzoneUploader @fileUploaded="handleData"></DropzoneUploader>
        <div class="flex flex-row-reverse">
            <VBtn
                color="blue-darken-1"
                @click="submitOrder"
            >Save</VBtn>
        </div>
        <!-- {{ orderForm }} -->
    </BackendLayout>
</template>

<script setup>
import { reactive } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import BackendLayout from '@/Layouts/BackendLayout.vue';
import DropzoneUploader from '@/Components/DropzoneUploader.vue';
import OrderForm from '@/Components/OrderForm.vue';

const orderForm = reactive({
    orderFiles: [],
    item: ""
})

const handleData = data => {
    const copyOfUploadedData = { ...data }
    const newFile = {
        file: copyOfUploadedData,
        pageNo: "",
        copies: "",
        note: "",
    }
    orderForm.orderFiles.push(newFile);
}


const updatePageData = (pageObject, object) => {
    object.note = pageObject.note;
    object.copies = pageObject.copies;
    object.pageNo = pageObject.pageNumber;
}

const items = usePage().props.items;

const submitOrder = () => {

}

</script>

