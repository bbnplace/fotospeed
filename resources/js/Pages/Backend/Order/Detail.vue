<template>
    <Head title="Order"></Head>
    <BackendLayout>
        <Link :href="route('orders')" class="font-bold">Back</Link>
        <Panel :snippet-title="`Order Files`">
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
                    <p class="mt-4 font-bold">If you have the files and would like to upload them, use the button below.</p>
                    <p>
                        <Link 
                            :href="route('order.edit', [order.id])"
                            class="btn bg-blue font-bold"
                        >Upload Images</Link>
                    </p>
                </VCol>
            </VRow>
        </Panel>

        <VRow>
            <VCol cols="12" sm="6">
                <CommunicationLog />
            </VCol>
            <VCol cols="12" sm="6">
                <Panel snippetTitle="Order Details">
                    <VRow>
                        <VCol cols="12" sm="6">
                            <VRow >
                                <VCol>
                                    <b>Client</b><br />
                                    {{ order.user.name }}<br />
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
                                    <b>Reference Number</b><br />
                                    {{ order.order_number }}
                                    <Link v-if="!order.order_number && canEditOrder" class="font-bold underline" :href="`${route('order.edit', order.id)}`">[ Add ]</Link>
                                    <Link v-if="order.order_number && canEditOrder" class="ml-3 font-bold underline" :href="`${route('order.edit', order.id)}`">[ Edit ]</Link>
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
                                <VCol v-if="$page.props.auth.user.role != 'Customer'">
                                    <b>Price</b><br />
                                    ₦{{ orderDetail.price ?? " --:--" }} 
                                    <Link v-if="!orderDetail.price && canEditOrder" class="ml-3 font-bold underline" :href="`${route('order.edit', order.id)}`">Add</Link>
                                    <Link v-if="orderDetail.price && canEditOrder" class="ml-3 font-bold underline" :href="`${route('order.edit', order.id)}`">Edit</Link>
                                </VCol>
                            </VRow>
                        </VCol>

                        <VCol cols="12" sm="6">
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
                            <VRow v-if="user.isAdmin">
                                <VCol>
                                    <Link class="font-bold underline" :href="route('tasks.order.dashboard', [order.id])">View Tasks</Link>
                                </VCol>
                            </VRow>
                            <VRow>
                                <VCol>
                                    <b>Order Created</b><br />
                                    {{ moment(order.created_at).calendar() }}
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
                    <VRow>
                        <VCol>
                            <VBtn v-if="canGenerateInvoice && !invoicePaid"
                                color="blue-darken-1 m-1"
                                @click="generateInvoice"
                            >Generate Invoice</VBtn>
                            <VBtn
                                color="blue-darken-1 m-1"
                                @click="editOrder"
                                 v-if="canEditOrder"
                            >
                                Edit Order
                            </VBtn>
                            <VBtn
                                color="red-darken-1 m-1"
                                @click="cancelOrder"
                                 v-if="!invoicePaid && user.isAdmin"
                            >
                                Cancel Order
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
            </VCol>
        </VRow>
        
    </BackendLayout>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { usePage, useForm, Head, Link, router } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import OrderForm from '@/Components/OrderForm.vue';
import Panel from '@/Layouts/Shared/Panel.vue';
import moment from 'moment';
import CommunicationLog from '@/Components/CommunicationLog.vue';
import DownloadAllMediaBtn from '@/Components/DownloadAllMediaBtn.vue';

const user = usePage().props.auth.user;
const order = usePage().props.order;
const orderDetail = usePage().props.orderDetail;
const activities = usePage().props.activities;
const hasInvoice = usePage().props.hasInvoice;
const canGenerateInvoice = usePage().props.canGenerateInvoice;
const invoicePaid = usePage().props.invoicePaid;
const canEditOrder = usePage().props.canEditOrder;
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


const editOrder = () => {
    router.visit(route('order.edit', order.id));
}

const cancelOrder = () => {
    alert("Cancelling Order");
}

const generateInvoice = () => {
    form.post(route('invoice.create'));
}
</script>

