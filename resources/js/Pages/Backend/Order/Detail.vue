<template>
    <Head title="Order"></Head>
    <BackendLayout>
        <Link :href="route('orders')" class="font-bold">Back</Link>
        <Panel :snippet-title="`Order Assets`">
            <div class="text-right mb-2" v-if="orderForm.orderFiles.length">
                <DownloadAllMediaBtn :files="orderForm.orderFiles" />
            </div>
            <VRow v-if="orderForm.orderFiles.length">
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
            <VRow v-else>
                <VCol class="text-center">
                    <h3 class="text-red">No asset was uploaded for this order!</h3>
                    <p>If you do not have the image files, kindly contact the client using the phone number below.</p>
                </VCol>
            </VRow>
        </Panel>

        <VRow>
            <VCol cols="12" sm="6">
                <CommunicationLog />
            </VCol>
            <VCol cols="12" sm="6">
                <OrderCard />
                <Panel snippet-title="Activities" v-if="activities.length">
                    <VTable>
                        <THead>
                            <tr>
                                <td>Process</td>
                                <td>Completed by</td>
                                <td>Date</td>
                            </tr>
                        </THead>
                        <TBody>
                            <tr v-for="activity in activities" :key="activity.id">
                                <td>{{ activity.process.name }}</td>
                                <td>{{ activity.staff.name }}</td>
                                <td>{{ moment(activity.created_at).format('MM/DD/YYYY, h:mm A') }}</td>
                            </tr>
                        </TBody>
                    </VTable>
                </Panel>
            </VCol>
        </VRow>
    </BackendLayout>
</template>

<script setup>
import { reactive, ref, computed } from 'vue';
import { usePage, Head, Link, router } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import OrderForm from '@/Components/OrderForm.vue';
import Panel from '@/Layouts/Shared/Panel.vue';
import moment from 'moment';
import CommunicationLog from '@/Components/CommunicationLog.vue';
import DownloadAllMediaBtn from '@/Components/DownloadAllMediaBtn.vue';
import OrderCard from '@/Components/OrderCard.vue';

const order = usePage().props.order;
const orderDetail = usePage().props.orderDetail;
const activities = usePage().props.activities;

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


const editOrder = () => {
    router.visit(route('order.edit', order.id));
}


</script>

<style scoped>
.no-border {
  border: none;
}

.no-padding {
  padding: 0;
}
</style>