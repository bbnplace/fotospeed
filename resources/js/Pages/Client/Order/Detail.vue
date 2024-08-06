<template>
    <Head title="Order"></Head>
    <ClientLayout>
        <Panel snippetTitle="Order Files">
            <VRow v-if="orderForm.orderFiles">
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
                    <h1 class="text-red">Image Files were not uploaded for this Order!</h1>
                    <p>Use the button below to upload files.</p>
                    <p>
                        <Link 
                        :href="route('client.order.edit', [order.id])"
                        class="btn bg-blue font-bold mt-3"
                        >Upload Files</Link>
                    </p>
                </VCol>
            </VRow>
        </Panel>

        <Panel snippetTitle="Details">
            <VRow>
                <VCol cols="12" md="6">
                    <VRow>
                        <VCol>
                            <b>Client</b><br />
                            {{ orderDetail.customerData.name }}
                            {{ orderDetail.customerMobile}}
                        </VCol>
                    </VRow>
                    <VRow>
                        <VCol>
                            <b>Branch</b><br />
                            {{ order.branch.name }}
                        </VCol>
                    </VRow>
                    <VRow>
                        <VCol>
                            <b>Order Number</b><br />
                            {{ orderDetail.orderNumber }}
                        </VCol>
                    </VRow>
                    <VRow>
                        <VCol>
                            <b>Order Name</b><br />
                            {{ orderDetail.name }}
                        </VCol>
                    </VRow>
                    <VRow>
                        <VCol>
                            <b>Note</b><br />
                            {{ orderDetail.note }}
                        </VCol>
                    </VRow>
                    <VRow>
                        <VCol cols="12" sm="6">
                            <b>Price</b><br />
                            ₦{{ orderDetail.price }}
                        </VCol>
                    </VRow>
                </VCol>

                <VCol cols="12" md="6">
                    <VRow>
                        <VCol>
                            <b>Item</b><br />
                            {{ $page.props.order.item.name }}
                        </VCol>
                    </VRow>
                    <VRow>
                        <VCol>
                            <b>Quantity</b><br />
                            {{ $page.props.order.quantity }}
                        </VCol>
                    </VRow>
                    <VRow>
                        <VCol>
                            <b>Order Status</b><br />
                            {{ $page.props.order.order_status.name }}
                        </VCol>
                    </VRow>
                    <VRow>
                        <VCol>
                            <b>Order Created On</b><br />
                            {{ moment(orderDetail.created_at).format('LL') }}
                        </VCol>
                    </VRow>
                    <VRow>
                        <VCol>
                            <b>Delivery Date</b><br />
                            {{ moment(orderDetail.date).format('LL') }}
                        </VCol>
                    </VRow>
                </VCol>
            </VRow>
        </Panel>
    </ClientLayout>
</template>

<script setup>
import { reactive } from 'vue';
import { usePage, Head, Link } from "@inertiajs/vue3";
import ClientLayout from "@/Layouts/ClientLayout.vue";
import Panel from "@/Layouts/Shared/Panel.vue";
import OrderForm from '@/Components/OrderForm.vue';
import moment from 'moment';

const order = usePage().props.order;
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

