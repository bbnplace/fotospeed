<template>
    <Head title="Order"></Head>
    <BackendLayout>
        <Panel :snippet-title="`${order.user.name}'s Order`">
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
        </Panel>

        <Panel snippetTitle="Details">
            <VRow>
                <VCol cols="12" md="6">
                    <VRow >
                        <VCol>
                            <b>Client</b><br />
                            {{ order.user.name }}
                            {{ order.user.mobile}}
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
                            {{ order.order_number }}
                        </VCol>
                    </VRow>
                    <VRow>
                        <VCol>
                            <b>Order Name</b><br />
                            {{ order.name }}
                        </VCol>
                    </VRow>
                    <VRow>
                        <VCol>
                            <b>Note</b><br />
                            {{ order.note }}
                        </VCol>
                    </VRow>
                    <VRow>
                        <VCol cols="12" sm="6" v-if="$page.props.auth.user.role != 'Customer'">
                            <b>Price</b><br />
                            ₦{{ orderDetail.price }}
                        </VCol>
                    </VRow>
                </VCol>

                <VCol cols="12" md="6">
                    <VRow>
                        <VCol>
                            <b>Order Quantity</b><br />
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
            <VRow v-if="$page.props.order.order_status.name != 'Completed' && $page.props.nextProcess != 'Cancelled'">
                <VCol>
                    <VBtn v-if="$page.props.nextProcess == 'Billing'"
                        color="blue-darken-1"
                        @click="submitOrder"
                    >Issue Invoice</VBtn>
                    <VBtn v-else
                        color="blue-darken-1"
                        @click="submitOrder"
                    >
                        <template v-if="$page.props.auth.user.isAdmin">Forward To {{ $page.props.nextProcess }}</template>
                        <template v-else>Mark Completed</template>

                    </VBtn>
                </VCol>
            </VRow>
        </Panel>
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
    </BackendLayout>
</template>

<script setup>
import { reactive } from 'vue';
import { usePage, useForm, Head } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import OrderForm from '@/Components/OrderForm.vue';
import Panel from '@/Layouts/Shared/Panel.vue';
import moment from 'moment';

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

const form = useForm({
    orderId: order.id
});

const submitOrder = () => {
    form.post(route('process.forward'));
}
</script>

