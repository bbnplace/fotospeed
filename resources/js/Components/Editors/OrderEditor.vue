<template>
    <Head title="Create Order"></Head>
        <div class="flex flex-row-reverse">
            <VBtn
                color="blue-darken-1"
                @click="submitOrder"
            >Submit</VBtn>
        </div>
        <h4 class="mt-3">Select Item</h4>
        <VRow>
            <VCol>
                <VAutocomplete
                    v-model="masterForm.item"
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
                        view="New"
                        @pageRemoved="removeImage"
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
            >Submit</VBtn>
        </div>
        <!-- {{ orderForm.orderFiles }} -->
</template>

<script setup>
import { reactive } from 'vue';
import { Head, usePage, router, useForm } from '@inertiajs/vue3';
import DropzoneUploader from '@/Components/DropzoneUploader.vue';
import OrderForm from '@/Components/OrderForm.vue';

const props = defineProps({
    order: Object
});

const orderForm = reactive({
    orderFiles: props.order ? props.order.files : [],
});

const masterForm = useForm({
    item: props.order ? props.order.item : "",
    files: []
})

const handleData = data => {
    const copyOfUploadedData = { ...data, pageNumber: "", note: "" }

    const newFile = {
        file: copyOfUploadedData,

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
    // To reduce size of submitted data, cut out the dataURL from each file before submitting
    const fileData = [];

    orderForm.orderFiles.forEach(element => {
        const file = { ...element.file, note: element.note, pageNumber: element.pageNo }
        file.dataURL = undefined;

        fileData.push({
            file
        })
    });
    masterForm.files = fileData;

    if (props.order) {

    } else {
        // Submit the order data to endpoint
        masterForm.post(route("order.add"));
    }
}

const removeImage = data => {
    for (let index = 0; index < orderForm.orderFiles.length; index++) {
        const element = orderForm.orderFiles[index];

        if (element.file.id == data.id) {
            orderForm.orderFiles.splice(index, 1); // Main Line
        }
    }
}

</script>

