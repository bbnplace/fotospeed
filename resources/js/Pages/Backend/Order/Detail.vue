<template>
    <Head title="Order"></Head>
    <BackendLayout>
            <template v-if="orderForm.orderFiles">
            <VRow>
                <VCol cols="12" lg="6" v-for="orderFile, index in orderForm.orderFiles" :key="index">
                    <OrderForm
                        :orderImage="orderFile.file"
                        view="Detail"
                        @pageRemoved="removeImage"
                        @pageDataUpdated="(data) => {
                            updatePageData(data, orderFile)
                        }"
                    ></OrderForm>
                </VCol>
            </VRow>

        </template>
    </BackendLayout>
</template>

<script setup>
import { reactive } from 'vue';
import { usePage, Head } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import OrderForm from '@/Components/OrderForm.vue';

const orderDetail = usePage().props.orderDetail;
const orderForm = reactive({
    orderFiles: orderDetail.files,
});

const removeImage = data => {
    for (let index = 0; index < orderForm.orderFiles.length; index++) {
        const element = orderForm.orderFiles[index];

        if (element.file.id == data.id) {
            orderForm.orderFiles.splice(index, 1); // Main Line
        }
    }
}
</script>

