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
                <Panel snippetTitle="Order Card" :cardColor="orderOnHold ? 'bg-gray-700 text-white' : 'bg-white'">
                    <VRow>
                        <VCol class="text-right">
                            <VBtn
                                color="blue-darken-3"
                                prepend-icon="mdi-printer"
                                @click="editOrder"
                                 v-if="canEditOrder && reference && reference.length && price > 0"
                            >
                                Print Card
                            </VBtn>
                        </VCol>
                    </VRow>
                    <hr/>
                    <VRow>
                        <VCol cols="12" md="6">
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
                                    {{ reference }}
                                    <VBtn
                                        prepend-icon="mdi-pencil"
                                        class="mr-2 no-padding no-border"
                                        elevation="0"
                                        v-if="canEditReferenceNumber && !orderOnHold"
                                    >
                                        <VOverlay
                                            v-model="showOrderRefOverlay"
                                            activator="parent"
                                            location-strategy="connected"
                                            scroll-strategy="close"
                                        >
                                            <VCard max-width="400" class="p-1">
                                                <VCardText class="pb-0">
                                                    <VTextField
                                                        v-model="reference"
                                                        hide-details
                                                        id="order-number"
                                                        variant="outlined"
                                                        label="Reference Number"
                                                        style="width: 200px"
                                                        :loading="orderReferenceSaving"
                                                    ></VTextField>
                                                    <p v-if="orderReferenceResponse.length" class="text-center font-bold">{{ orderReferenceResponse }}</p>
                                                </VCardText>
                                                <VCardActions>
                                                    <VBtn
                                                        color="blue-darken-1 m-1"
                                                        @click="setReferenceNo"
                                                        :disabled="orderReferenceSaving"
                                                    >Save</VBtn>
                                                    <VBtn
                                                        color="grey-darken-1 m-1"
                                                        @click="showOrderRefOverlay = false"
                                                    >Close</VBtn>
                                                </VCardActions>
                                            </VCard>
                                        </VOverlay>
                                    </VBtn>
                                </VCol>
                            </VRow>
                            <VRow>
                                <VCol>
                                    <b>Order Name</b><br />
                                    {{ order.name }}
                                </VCol>
                            </VRow>
                            <VRow>
                                <VCol v-if="$page.props.auth.user.role != 'Customer'">
                                    <b>Price</b><br />
                                    ₦{{ price ?? " --:--" }} <VBtn
                                        elevation="0"
                                        prepend-icon="mdi-pencil"
                                        class="mr-2 no-padding no-border"
                                        v-if="canEditPrice && !orderOnHold"
                                    >
                                        <VOverlay
                                            v-model="showPriceOverlay"
                                            activator="parent"
                                            location-strategy="connected"
                                            scroll-strategy="close"
                                        >
                                            <VCard max-width="400" class="p-1">
                                                <VCardText class="pb-0">
                                                    <VTextField
                                                        v-model="price"
                                                        hide-details
                                                        id="price"
                                                        variant="outlined"
                                                        label="Price"
                                                        prefix="₦"
                                                        style="width: 200px"
                                                        :loading="priceSaving"
                                                    ></VTextField>
                                                    <div v-if="priceResponse.length" class="text-center font-bold">{{ priceResponse }}</div>
                                                </VCardText>
                                                <VCardActions>
                                                    <VBtn
                                                        color="blue-darken-1 m-1"
                                                        @click="setPrice"
                                                        :disabled="priceSaving"
                                                    >Save</VBtn>
                                                    <VBtn
                                                        color="grey-darken-1 m-1"
                                                        @click="showPriceOverlay = false"
                                                    >Close</VBtn>
                                                </VCardActions>
                                            </VCard>
                                        </VOverlay>
                                    </VBtn>
                                </VCol>
                            </VRow>
                            <VRow v-if="orderDetail.price && order.order_number && !hasInvoice && canGenerateInvoice && orderStatus != 'Cancelled'">
                                <VCol cols="12">
                                    <VBtn
                                        color="blue-darken-1"
                                        @click="generateInvoice"
                                    >Issue Invoice</VBtn>
                                </VCol>
                            </VRow>
                            <VRow v-if="hasInvoice">
                                <VCol cols="12" sm="6">
                                    <b>Invoice Status</b><br />
                                    {{ invoicePaid ? "Paid" : "Unpaid" }}
                                </VCol>
                            </VRow>
                            <VRow>
                                <VCol>
                                    <b>WayBill Number</b><br />
                                    {{ waybillNumber ?? 'NOT SET' }}  <VBtn
                                        elevation="0"
                                        prepend-icon="mdi-pencil"
                                        class="mr-2 no-padding no-border"
                                        v-if="canEditWaybill && !orderOnHold"
                                    >
                                        <VOverlay
                                            v-model="showWaybillOverlay"
                                            activator="parent"
                                            location-strategy="connected"
                                            scroll-strategy="close"
                                        >
                                            <VCard max-width="400" class="p-1">
                                                <VCardText class="pb-0">
                                                    <VTextField
                                                        v-model="waybillNumber"
                                                        hide-details
                                                        id="order-number"
                                                        variant="outlined"
                                                        label="Waybill Number"
                                                        style="width: 200px"
                                                        :loading="waybillSaving"
                                                    ></VTextField>
                                                    <div v-show="waybillResponse.length" class="text-center font-bold">{{ waybillResponse }}</div>
                                                </VCardText>
                                                <VCardActions>
                                                    <VBtn
                                                        color="blue-darken-1 m-1"
                                                        @click="saveWaybill"
                                                        :disabled="waybillSaving"
                                                    >Save</VBtn>
                                                    <VBtn
                                                        color="grey-darken-1 m-1"
                                                        @click="showWaybillOverlay = false"
                                                    >Close</VBtn>
                                                </VCardActions>
                                            </VCard>
                                        </VOverlay>
                                    </VBtn>
                                </VCol>
                            </VRow>
                        </VCol>

                        <VCol cols="12" md="6">
                            <VRow>
                                <VCol>
                                    <b>Product</b><br />
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
                                    {{ orderOnHold ? "On Hold" : orderStatus }}
                                </VCol>
                            </VRow>
                            <VRow>
                                <VCol>
                                    <b>Current Process</b><br />
                                    {{ currentProcess }}
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
                                    <b>Last Updated</b><br />
                                    {{ moment(order.updated_at).fromNow() }}
                                </VCol>
                            </VRow>
                            <VRow>
                                <VCol>
                                    <b>Target Delivery Date</b><br />
                                    {{ moment(orderDetail.date).format('LL') }}
                                </VCol>
                            </VRow>
                        </VCol>
                    </VRow>
                    
                    <VRow>
                        <VCol>
                            <b>Note</b><br />
                            {{ order.note }}
                        </VCol>
                    </VRow>
                    <hr/>
                    <VRow>
                        <VCol>
                            <VBtn
                                color="grey-darken-3"
                                prepend-icon="mdi-pause"
                                class="mr-2 mb-2"
                                 v-if="!orderOnHold && canHoldOrder"
                            >
                                Hold Order
                                <VOverlay
                                    v-model="showHoldOrderOverlay"
                                    activator="parent"
                                    location-strategy="connected"
                                    scroll-strategy="close"
                                >
                                    <VCard max-width="400" class="p-3">
                                        <VCardTitle>What's Wrong?</VCardTitle>
                                        <VCardText>
                                            <p>
                                                Why do you want to place this order on hold?<br />
                                            </p>
                                            <VTextarea
                                                v-model="orderHoldReason"
                                                hide-details
                                                id="hold-order"
                                                variant="outlined"
                                                label="Write note for team members here"
                                                :loading="holdingOrderProgress"
                                            ></VTextarea>
                                            <p v-if="orderHoldResponse.length" class="text-center font-bold pt-2">{{ orderHoldResponse }}</p>
                                        </VCardText>
                                        <VCardActions>
                                            <VBtn
                                                color="red-darken-1"
                                                @click="holdOrder"
                                                :disabled="holdingOrderProgress"
                                            >Continue</VBtn>
                                            <VBtn
                                                color="blue-darken-1"
                                                @click="showHoldOrderOverlay = false"
                                            >Close</VBtn>
                                        </VCardActions>
                                    </VCard>
                                </VOverlay>
                            </VBtn>
                            <VBtn
                                color="white"
                                class="mr-2 mb-2"
                                prepend-icon="mdi-play"
                                v-if="orderOnHold && user.isAdmin && orderStatus != 'Cancelled'"
                            >
                                Reactivate
                                <VOverlay
                                    v-model="showReactivateOrderOverlay"
                                    activator="parent"
                                    location-strategy="connected"
                                    scroll-strategy="close"
                                >
                                    <VCard max-width="400" class="p-3">
                                        <VCardTitle>Confirm Action!</VCardTitle>
                                        <VCardText>
                                            <p>Are you sure you want to reactivate this order?</p>
                                            <p class="text-center" v-if="reactivateOrderProgress">
                                                <v-progress-circular
                                                    color="red"
                                                    indeterminate
                                                ></v-progress-circular>
                                            </p>
                                            <p v-if="reactiveOrderResponse.length" class="text-center font-bold">{{ reactiveOrderResponse }}</p>
                                        </VCardText>
                                        <VCardActions>
                                            <VBtn
                                                color="red-darken-1 m-1"
                                                @click="reactivateOrder"
                                                :disabled="reactivateOrderProgress"
                                            >Yes Proceed</VBtn>
                                            <VBtn
                                                color="blue-darken-1 m-1"
                                                @click="showReactivateOrderOverlay = false"
                                            >Don't</VBtn>
                                        </VCardActions>
                                    </VCard>
                                </VOverlay>
                            </VBtn>
                            <VBtn
                                color="red-darken-1"
                                class="mr-2 mb-2"
                                prepend-icon="mdi-cancel"
                                v-if="canCancelOrder"
                            >
                                Cancel Order
                                <VOverlay
                                    v-model="showOverlay"
                                    activator="parent"
                                    location-strategy="connected"
                                    scroll-strategy="close"
                                >
                                    <VCard max-width="400" class="p-3">
                                        <VCardTitle>Heads Up!</VCardTitle>
                                        <VCardText>
                                            <p>Are you sure you want to cancel this order?</p>
                                            <p class="text-center" v-if="cancellingOrderProgress">
                                                <v-progress-circular
                                                    color="red"
                                                    indeterminate
                                                ></v-progress-circular>
                                            </p>
                                            <p v-if="orderCancelResponse.length" class="text-center font-bold">{{ orderCancelResponse }}</p>
                                        </VCardText>
                                        <VCardActions>
                                            <VBtn
                                                color="red-darken-1 m-1"
                                                @click="cancelOrder"
                                                :disabled="cancellingOrderProgress"
                                            >Yes Proceed</VBtn>
                                            <VBtn
                                                color="blue-darken-1 m-1"
                                                @click="showOverlay = false"
                                            >Don't</VBtn>
                                        </VCardActions>
                                    </VCard>
                                </VOverlay>
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
import { reactive, ref, computed } from 'vue';
import { usePage, useForm, Head, Link, router } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import OrderForm from '@/Components/OrderForm.vue';
import Panel from '@/Layouts/Shared/Panel.vue';
import moment from 'moment';
import CommunicationLog from '@/Components/CommunicationLog.vue';
import DownloadAllMediaBtn from '@/Components/DownloadAllMediaBtn.vue';
import axios from 'axios';
import { VTextField } from 'vuetify/lib/components/index.mjs';

const user = usePage().props.auth.user;
const order = usePage().props.order;
const orderDetail = usePage().props.orderDetail;
const activities = usePage().props.activities;
const hasInvoice = usePage().props.hasInvoice;
const canGenerateInvoice = usePage().props.canGenerateInvoice;
const invoicePaid = usePage().props.invoicePaid;
const canEditOrder = usePage().props.canEditOrder;
const canHoldOrder = usePage().props.canHoldOrder;
const canCancelOrder = usePage().props.canCancelOrder;
const canEditReferenceNumber = usePage().props.canEditReferenceNumber;
const canEditPrice = usePage().props.canEditPrice;
const canEditWaybill = usePage().props.canEditWaybill;
const orderForm = reactive({
    orderFiles: orderDetail.files,
});
const orderCancelResponse = ref("");
const cancellingOrderProgress = ref(false);
const orderStatus = ref(order.order_status.name)
const currentProcess = order.process ? order.process.name : "-";
const waybillNo = order.waybill_number;
const showOverlay = ref(false);

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

let source = null;
const cancelOrder = async () => {
    cancellingOrderProgress.value = true;
    const payload = {
        orderId: order.id
    };
    if(source) source.cancel('Request cancelled by user');
    source = axios.CancelToken.source();
    const response = await axios.put(route('order.cancel', [order.id]), payload, {
        headers: {
            "Content-Type": "application/json"
        },
        cancelToken: source.token
    });

    cancellingOrderProgress.value = false;
    orderCancelResponse.value = response.data.response;
    orderStatus.value = response.data.orderStatus;

    setTimeout(()=>{
        orderCancelResponse.value = "";
        showOverlay.value = false;
    }, 10000);

}

const generateInvoice = () => {
    form.post(route('invoice.create'));
}


// Place Order on Hold
const orderHoldReason = ref("");
const orderHoldResponse = ref("");
const holdingOrderProgress = ref(false);
const showHoldOrderOverlay = ref(false);
const orderOnHold = ref(order.paused);
const holdOrder = async () => {
    holdingOrderProgress.value = true;
    const payload = {
        reason: orderHoldReason.value
    }

    const response = await axios.put(route('order.hold', [order.id]), payload, {
        headers: {
            "Content-Type": "application/json"
        }
    });

    holdingOrderProgress.value = false;
    if (response.data && response.data.status == "success") {
        // orderHoldResponse.value = response.data.response;
        orderHoldReason.value = "";
        orderOnHold.value = true;
        showHoldOrderOverlay.value = false;
    }
}


// Reactive Held Order
const reactiveOrderResponse = ref("");
const reactivateOrderProgress = ref(false);
const showReactivateOrderOverlay = ref(false);
const reactivateOrder = async () => {
    reactivateOrderProgress.value = true;
    const payload = {
        
    }

    const response = await axios.put(route('order.reactivate', [order.id]), payload, {
        headers: {
            "Content-Type": "application/json"
        }
    });

    reactivateOrderProgress.value = false;
    if (response.data && response.data.status == "success") {
        // reactiveOrderResponse.value = response.data.response;
        orderOnHold.value = false;
        showReactivateOrderOverlay.value = false;
    }
}


// Register the Order Reference Number
const reference = ref(order.order_number);
const orderReferenceSaving = ref(false);
const orderReferenceResponse = ref("");
const showOrderRefOverlay = ref(false)
const setReferenceNo = async () => {
    orderReferenceSaving.value = true;
    const payload = {
        orderNumber: reference.value
    }

    const response = await axios.put(route('order.set-reference', [order.id]), payload, {
        headers: {
            "Content-Type": "application/json"
        }
    });

    orderReferenceSaving.value = false;
    if (response.data && response.data.status == "success") {
        orderReferenceResponse.value = response.data.response;
    }
}


// Register the price for the Order
const price = ref(order.total_cost);
const priceSaving = ref(false);
const priceResponse = ref("");
const showPriceOverlay = ref(false)
const setPrice = async () => {
    priceSaving.value = true;
    const payload = {
        price: price.value
    }

    const response = await axios.put(route('order.set-price', [order.id]), payload, {
        headers: {
            "Content-Type": "application/json"
        }
    });

    priceSaving.value = false;
    if (response.data && response.data.status == "success") {
        priceResponse.value = response.data.response;
    }
}

// Register Waybill
const waybillNumber = ref(waybillNo);
const waybillSaving = ref(false);
const waybillResponse = ref("");
const showWaybillOverlay = ref(false)
const saveWaybill = async () => {
    waybillSaving.value = true;
    const payload = {
        waybillNo: waybillNumber.value
    }

    const response = await axios.put(route('order.save-waybill', [order.id]), payload, {
        headers: {
            "Content-Type": "application/json"
        }
    });

    waybillSaving.value = false;
    if (response.data && response.data.status == "success") {
        waybillResponse.value = response.data.response;
    }
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