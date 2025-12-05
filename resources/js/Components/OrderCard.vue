<template>
    <Panel snippetTitle="Order Card" :cardColor="orderOnHold ? 'bg-gray-700 text-white' : 'bg-white'">
                    <VRow v-if="orderHoldReason && orderHoldReason.length && orderOnHold">
                        <VCol cols="12">
                            <VCard color="red">
                                <VCardText>{{ orderHoldReason }}</VCardText>
                            </VCard>
                        </VCol>
                    </VRow>
                    <VRow>
                        <VCol class="text-right">
                            <VBtn
                                color="blue-darken-3"
                                prepend-icon="mdi-printer"
                                @click="printOrderCard"
                                 v-if="canEditOrder && reference && reference.length && price > 0"
                            >
                                Print Card
                            </VBtn>
                        </VCol>
                    </VRow>
                    <hr/>
                    <VRow id="order-card">
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
                                    <b>Delivery Address</b><br />
                                    {{ order.delivery_address }}
                                </VCol>
                            </VRow>
                            <VRow>
                                <VCol>
                                    <b>Processing Branch</b><br />
                                    {{ order.source_branch ? order.source_branch.name : "-" }}
                                </VCol>
                            </VRow>
                            <VRow>
                                <VCol>
                                    <b>Origin Branch</b><br />
                                    {{ order.processing_branch.name }}
                                </VCol>
                            </VRow>
                            <VRow>
                                <VCol>
                                    <b>Order Number</b><br />
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
                                            scroll-strategy="static"
                                        >
                                            <VCard max-width="400" class="p-1">
                                                <VCardText class="pb-0">
                                                    <VTextField
                                                        v-model="reference"
                                                        hide-details
                                                        id="order-number"
                                                        variant="outlined"
                                                        label="Order Number"
                                                        style="min-width: 200px"
                                                        :loading="orderReferenceSaving"
                                                    ></VTextField>
                                                    <p v-if="orderReferenceResponse.length" class="text-center font-bold mt-1 mb-0">{{ orderReferenceResponse }}</p>
                                                    <p v-if="orderReferenceError.length" class="text-center text-red mt-1 mb-0">{{ orderReferenceError }}</p>
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
                            <VRow v-if="shouldShowPrice">
                                <VCol v-if="$page.props.auth.user.role != 'Customer'">
                                    <b>Price</b><br />
                                    ₦{{ price ? formatter.format(price) : " --:--" }} <VBtn
                                        elevation="0"
                                        prepend-icon="mdi-pencil"
                                        class="mr-2 no-padding no-border"
                                        v-if="canEditPrice && !orderOnHold"
                                    >
                                        <VOverlay
                                            v-model="showPriceOverlay"
                                            activator="parent"
                                            location-strategy="connected"
                                            scroll-strategy="static"
                                        >
                                            <VCard max-width="400" class="p-1">
                                                <VCardText class="pb-0">
                                                    <VTextField
                                                        v-model="price"
                                                        hide-details
                                                        id="price"
                                                        type="number"
                                                        variant="outlined"
                                                        label="Price"
                                                        prefix="₦"
                                                        style="width: 200px"
                                                        :loading="priceSaving"
                                                    ></VTextField>
                                                    <div v-if="priceResponse.length" class="text-center font-bold">{{ priceResponse }}</div>
                                                    <p v-if="priceError.length" class="text-center text-red mt-1 mb-0">{{ priceError }}</p>
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
                            <VRow v-if="price && reference && !orderOnHold && !hasInvoice && canGenerateInvoice && orderStatus != 'Cancelled'">
                                <VCol cols="12">
                                    <VBtn
                                        color="blue-darken-1"
                                        @click="generateInvoice"
                                    >Issue Invoice</VBtn>
                                </VCol>
                            </VRow>
                            <VRow v-if="shouldShowInvoice && hasInvoice">
                                <VCol cols="12">
                                    <b>Invoice Status</b><br />
                                    {{ invoicePaid ? "Paid" : "Unpaid" }}
                                </VCol>
                                <VCol cols="12">
                                    <Link class="font-bold underline" :href="route('invoice', [invoice.id])">Open Invoice</Link>
                                </VCol>
                            </VRow>
                            <VRow v-if="hasInvoice && invoicePaid">
                                <VCol cols="12">
                                    <b>Payment Method</b><br />
                                    {{ invoicePaymentMethod }}
                                </VCol>
                            </VRow>

                            
                            <!-- <VRow v-if="canApproveOfflinePayment">
                                <VCol cols="12">
                                    <OfflinePayment @statusUpdated="handleOfflinePaymentConfirmation"/>
                                </VCol>
                            </VRow> -->
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
                            
                            <VRow v-if="canForwardToNextProcess && enableHumanForwarding">
                                <VCol>
                                    <VBtn
                                        prepend-icon="mdi-play"
                                        color="blue-darken-3"
                                        @click="startNextProcess"
                                        :disabled="nextProcessStartSending"
                                    >Start Next Process</VBtn>
                                    <p class="my-2" v-if="nextProcessStartSending">
                                        <v-progress-linear color="red" indeterminate></v-progress-linear>
                                    </p>
                                    <p v-if="nextProcessStartError.length" class="text-red">
                                        {{ nextProcessStartError }}
                                    </p>
                                </VCol>
                            </VRow>
                            <VRow v-if="nextProcessStartResponse.length">
                                <VCol>
                                    {{ nextProcessStartResponse }}
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
                                                        style="min-width: 200px"
                                                        :loading="waybillSaving"
                                                    ></VTextField>
                                                    <div v-show="waybillResponse.length" class="text-center font-bold">{{ waybillResponse }}</div>
                                                    <p v-if="waybillError.length" class="text-center text-red mt-1 mb-0">{{ waybillError }}</p>
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
                                                label="Leave a note for team members"
                                                :loading="holdingOrderProgress"
                                            ></VTextarea>
                                            <p v-if="orderHoldResponse.length" class="text-center font-bold pt-2">{{ orderHoldResponse }}</p>
                                            <p v-if="orderHoldError && orderHoldError.length"  class="text-center text-red mt-1 mb-0">{{ orderHoldError }}</p>
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
                                            <p v-if="orderReactivationError && orderReactivationError.length"  class="text-center text-red mt-1 mb-0">{{ orderReactivationError }}</p>
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
                                            <p v-if="orderCancelError && orderCancelError.length"  class="text-center text-red mt-1 mb-0">{{ orderCancelError }}</p>
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
</template>

<script setup>
import { ref } from 'vue';
import { usePage, useForm, Link } from "@inertiajs/vue3";
import axios from 'axios';
import Panel from '@/Layouts/Shared/Panel.vue';
import moment from 'moment';
import OfflinePayment from './OfflinePayment.vue';

const user = usePage().props.auth.user;
const order = usePage().props.order;
const orderDetail = usePage().props.orderDetail;
const hasInvoice = usePage().props.hasInvoice;
const canGenerateInvoice = usePage().props.canGenerateInvoice;
const invoicePaid = ref(usePage().props.invoicePaid);
const invoice = ref(usePage().props.invoice);
const canEditOrder = usePage().props.canEditOrder;
const canHoldOrder = usePage().props.canHoldOrder;
const canCancelOrder = usePage().props.canCancelOrder;
const canEditReferenceNumber = usePage().props.canEditReferenceNumber;
const canEditPrice = usePage().props.canEditPrice;
const canEditWaybill = usePage().props.canEditWaybill;
const canForwardToNextProcess = usePage().props.canForwardToNextProcess;
const showOverlay = ref(false);

// Processing branch viewing context and privacy settings
const isViewingFromProcessingBranch = usePage().props.isViewingFromProcessingBranch;
const showPriceToProcessingBranch = usePage().props.showPriceToProcessingBranch;
const showInvoiceToProcessingBranch = usePage().props.showInvoiceToProcessingBranch;

// Determine if we should show price and invoice
const shouldShowPrice = !isViewingFromProcessingBranch || showPriceToProcessingBranch;
const shouldShowInvoice = !isViewingFromProcessingBranch || showInvoiceToProcessingBranch;

const orderCancelResponse = ref("");
const cancellingOrderProgress = ref(false);
const orderStatus = ref(order.order_status.name)
const currentProcess = ref(order.process ? order.process.name : "-");
const waybillNo = order.waybill_number;
const enableHumanForwarding = ref(order.human_forwarding);
const orderInvoice = usePage().props.invoice;
const invoicePaymentMethod = ref(orderInvoice == null ? "" : orderInvoice.payment_method);
const canApproveOfflinePayment = ref(usePage().props.canApproveOfflinePayment);

const handleOfflinePaymentConfirmation = (response) => {
    canApproveOfflinePayment.value = false;
    invoicePaid.value = response.invoicePaid;
    invoicePaymentMethod.value = response.paymentMethod;
}

const formatter = new Intl.NumberFormat('en-US', {
        style: 'decimal',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    });

const orderCancelError = ref("");
let source = null;
const cancelOrder = async () => {
    orderCancelResponse.value = "";
    orderCancelError.value = "";
    cancellingOrderProgress.value = true;
    const payload = {
        orderId: order.id
    };
    if(source) source.cancel('Request cancelled by user');
    source = axios.CancelToken.source();
    
    try {
        const response = await axios.put(route('order.cancel', [order.id]), payload, {
            headers: {
                "Content-Type": "application/json"
            },
            cancelToken: source.token
        });

        cancellingOrderProgress.value = false;
        orderCancelResponse.value = response.data.response;
        orderStatus.value = response.data.orderStatus;
    } catch (error) {
        cancellingOrderProgress.value = false;
        if (error.response && error.response.status === 422) {
            orderCancelError.value = error.response.data.message;
        } else {
            orderCancelError.value = "Something went wrong! Pls try again later.";
        }
    }

    setTimeout(()=>{
        orderCancelResponse.value = "";
        orderCancelError.value = "";
        showOverlay.value = false;
    }, 5000);
}

const form = useForm({
    orderId: order.id
});

const generateInvoice = () => {
    form.post(route('invoice.create'));
}


// Place Order on Hold
const orderHoldReason = ref(order.hold_reason);
const orderHoldResponse = ref("");
const holdingOrderProgress = ref(false);
const showHoldOrderOverlay = ref(false);
const orderOnHold = ref(order.paused);
const orderHoldError = ref("");
const holdOrder = async () => {
    orderHoldError.value = "";
    holdingOrderProgress.value = true;
    const payload = {
        reason: orderHoldReason.value
    }

    try {
        const response = await axios.put(route('order.hold', [order.id]), payload, {
            headers: {
                "Content-Type": "application/json"
            }
        });

        holdingOrderProgress.value = false;
        if (response.data && response.data.status == "success") {
            orderOnHold.value = true;
            showHoldOrderOverlay.value = false;
        }
    } catch (error) {
        holdingOrderProgress.value = false;
        if (error.response && error.response.status === 422) {
            orderHoldError.value = error.response.data.message;
        } else {
            orderHoldError.value = "Something went wrong! Pls try again later.";
        }
    }

    setTimeout(()=>{
        orderHoldError.value = "";
        showHoldOrderOverlay.value = false;
    }, 5000);
}


// Reactivate Held Order
const reactiveOrderResponse = ref("");
const reactivateOrderProgress = ref(false);
const showReactivateOrderOverlay = ref(false);
const orderReactivationError = ref("");
const reactivateOrder = async () => {
    reactivateOrderProgress.value = true;
    const payload = {
        
    }

    try {
        const response = await axios.put(route('order.reactivate', [order.id]), payload, {
            headers: {
                "Content-Type": "application/json"
            }
        });

        reactivateOrderProgress.value = false;
        if (response.data && response.data.status == "success") {
            // reactiveOrderResponse.value = response.data.response;
            orderOnHold.value = false;
            orderHoldReason.value = "";
            showReactivateOrderOverlay.value = false;
        }
    } catch (error) {
        reactivateOrderProgress.value = false;
        if (error.response && error.response.status === 422) {
            orderReactivationError.value = error.response.data.message;
        } else {
            orderReactivationError.value = "Something went wrong! Pls try again later.";
        }
    }

    setTimeout(()=>{
        orderReactivationError.value = "";
        showReactivateOrderOverlay.value = false;
    }, 5000);
}


// Register the Order Reference Number
const reference = ref(order.order_number);
const orderReferenceSaving = ref(false);
const orderReferenceResponse = ref("");
const orderReferenceError = ref("");
const showOrderRefOverlay = ref(false)
const setReferenceNo = async () => {
    orderReferenceError.value = "";
    orderReferenceSaving.value = true;
    const payload = {
        orderNumber: reference.value
    }

    try {
        const response = await axios.put(route('order.set-reference', [order.id]), payload, {
            headers: {
                "Content-Type": "application/json"
            }
        });

        orderReferenceSaving.value = false;
        if (response.data && response.data.status == "success") {
            orderReferenceResponse.value = response.data.response;
        }
    } catch (error) {
        orderReferenceSaving.value = false;
        if (error.response && error.response.status === 422) {
            orderReferenceError.value = error.response.data.message;
        } else {
            orderReferenceError.value = "Something went wrong! Pls try again later.";
        }
    }

    setTimeout(()=>{
        orderReferenceResponse.value = "";
        orderReferenceError.value = "";
        showOrderRefOverlay.value = false;
    }, 5000);
}


// Register the price for the Order
const price = ref(order.total_cost);
const priceSaving = ref(false);
const priceResponse = ref("");
const priceError = ref("");
const showPriceOverlay = ref(false)
const setPrice = async () => {
    priceError.value = "";
    priceSaving.value = true;
    const payload = {
        price: price.value
    }

   try {
        const response = await axios.put(route('order.set-price', [order.id]), payload, {
            headers: {
                "Content-Type": "application/json"
            }
        });

        priceSaving.value = false;
        if (response.data && response.data.status == "success") {
            priceResponse.value = response.data.response;
        }
   } catch (error) {
        priceSaving.value = false;
        if (error.response && error.response.status === 422) {
            priceError.value = error.response.data.message;
        } else {
            priceError.value = "Something went wrong! Pls try again later.";
        }
   }

   setTimeout(()=>{
        priceResponse.value = "";
        priceError.value = "";
        showPriceOverlay.value = false;
    }, 5000);
}

// Register Waybill
const waybillNumber = ref(waybillNo);
const waybillSaving = ref(false);
const waybillResponse = ref("");
const waybillError = ref("");
const showWaybillOverlay = ref(false)
const saveWaybill = async () => {
    waybillSaving.value = true;
    waybillError.value = "";
    const payload = {
        waybillNumber: waybillNumber.value
    }

    try {
        const response = await axios.put(route('order.save-waybill', [order.id]), payload, {
            headers: {
                "Content-Type": "application/json"
            }
        });

        waybillSaving.value = false;
        if (response.data && response.data.status == "success") {
            waybillResponse.value = response.data.response;
        }
    } catch (error) {
        waybillSaving.value = false;
        if (error.response && error.response.status === 422) {
            waybillError.value = error.response.data.message;
        } else {
            waybillError.value = "Something went wrong! Pls try again later.";
        }
    }

    setTimeout(()=>{
        waybillResponse.value = "";
        waybillError.value = "";
        showWaybillOverlay.value = false;
    }, 5000);
}


const nextProcessStartSending = ref(false);
const nextProcessStartResponse = ref("");
const nextProcessStartError = ref("");
const startNextProcess = async () => {
    nextProcessStartSending.value = true;
    nextProcessStartResponse.value = "";
    nextProcessStartError.value = "";

    const payload = {
        orderId: order.id
    }

    try {
        const response = await axios.post(route('order.process.forward'), payload, {
            headers: {
                "Content-Type": "application/json"
            }
        });

        if (response.data && response.data.status == "success") {
            nextProcessStartResponse.value = response.data.message;
            currentProcess.value = response.data.currentProcess;
            enableHumanForwarding.value = false;
        }
    } catch (error) {
        if (error.response && error.response.status === 422) {
            nextProcessStartError.value = error.response.data.message;
        } else {
            nextProcessStartError.value = "Something went wrong! Pls try again later.";
        }
    }
    nextProcessStartSending.value = false;

    setTimeout(()=>{
        nextProcessStartResponse.value = "";
        nextProcessStartError.value = "";
    }, 7000)
}



const printOrderCard = () => {
    // Get order data
    const clientName = order.user.name;
    const clientPhone = order.user.mobile;
    const deliveryAddress = order.delivery_address;
    const originBranch = order.source_branch ? order.source_branch.name : "-";
    const processingBranch = order.processing_branch.name;
    const orderNumber = reference.value;
    const orderName = order.name;
    const productName = order.item.name;
    const quantity = order.quantity;
    const orderPrice = price.value ? `₦${formatter.format(price.value)}` : "--";
    const orderStatusText = orderOnHold.value ? "On Hold" : orderStatus.value;
    const currentProcessText = currentProcess.value;
    const orderDate = moment(order.created_at).format('MMMM DD, YYYY');
    const targetDeliveryDate = moment(orderDetail.date).format('MMMM DD, YYYY');
    const waybill = waybillNumber.value ?? 'NOT SET';
    const orderNotes = order.note;

    const printWindow = window.open('', '', 'height=800,width=900');
    const siteName = usePage().props.site.name;
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Order Card - ${orderNumber}</title>
            <style>
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }
                
                body {
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    color: #333;
                    background: #f5f5f5;
                    padding: 20px;
                }
                
                .print-container {
                    max-width: 800px;
                    margin: 0 auto;
                    background: white;
                    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                    border-radius: 8px;
                    overflow: hidden;
                }
                
                .header {
                    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
                    color: white;
                    padding: 30px;
                    text-align: center;
                }
                
                .header h1 {
                    font-size: 32px;
                    font-weight: 700;
                    margin-bottom: 5px;
                    letter-spacing: -0.5px;
                }
                
                .header .subtitle {
                    font-size: 14px;
                    opacity: 0.9;
                    text-transform: uppercase;
                    letter-spacing: 2px;
                }
                
                .order-number {
                    background: rgba(255,255,255,0.2);
                    padding: 15px 25px;
                    margin-top: 20px;
                    border-radius: 6px;
                    display: inline-block;
                }
                
                .order-number-label {
                    font-size: 12px;
                    opacity: 0.8;
                    margin-bottom: 5px;
                }
                
                .order-number-value {
                    font-size: 24px;
                    font-weight: 700;
                    letter-spacing: 1px;
                }
                
                .content {
                    padding: 0 30px;
                }
                
                .status-bar {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding: 15px 20px;
                    background: ${orderOnHold.value ? '#ef4444' : '#10b981'};
                    color: white;
                    border-radius: 6px;
                    margin-bottom: 30px;
                }
                
                .status-label {
                    font-size: 12px;
                    opacity: 0.9;
                }
                
                .status-value {
                    font-size: 18px;
                    font-weight: 700;
                }
                
                .section {
                    margin-bottom: 30px;
                }
                
                .section-title {
                    font-size: 16px;
                    font-weight: 700;
                    color: #1e3a8a;
                    margin-bottom: 15px;
                    padding-bottom: 8px;
                    border-bottom: 2px solid #e5e7eb;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                }
                
                .info-grid {
                    display: grid;
                    grid-template-columns: repeat(2, 1fr);
                    gap: 20px;
                }
                
                .info-item {
                    background: #f9fafb;
                    padding: 15px;
                    border-radius: 6px;
                    border-left: 3px solid #3b82f6;
                }
                
                .info-label {
                    font-size: 11px;
                    color: #6b7280;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    margin-bottom: 6px;
                    font-weight: 600;
                }
                
                .info-value {
                    font-size: 15px;
                    color: #111827;
                    font-weight: 500;
                }
                
                .info-value.large {
                    font-size: 18px;
                    font-weight: 700;
                    color: #1e3a8a;
                }
                
                .full-width {
                    grid-column: 1 / -1;
                }
                
                .notes-box {
                    background: #fef3c7;
                    padding: 15px;
                    border-radius: 6px;
                    border-left: 4px solid #f59e0b;
                    margin-top: 10px;
                }
                
                .notes-content {
                    color: #78350f;
                    font-size: 14px;
                    line-height: 1.6;
                }
                
                .footer {
                    background: #f9fafb;
                    padding: 20px 30px;
                    text-align: center;
                    border-top: 2px solid #e5e7eb;
                }
                
                .footer-text {
                    font-size: 12px;
                    color: #6b7280;
                }
                
                .print-date {
                    font-size: 11px;
                    color: #9ca3af;
                    margin-top: 5px;
                }
                
                @media print {
                    body {
                        background: white;
                        padding: 0;
                    }
                    
                    .print-container {
                        box-shadow: none;
                        border-radius: 0;
                    }
                    
                    @page {
                        margin: 15mm;
                    }
                }
            </style>
        </head>
        <body>
            <div class="print-container">
                <div class="header">
                    <h1>${siteName}</h1>
                    <div class="order-number">
                        <div class="order-number-label">Order Number</div>
                        <div class="order-number-value">${orderNumber}</div>
                    </div>
                </div>
                
                <div class="content">
                    <div class="status-bar">
                        <div>
                            <div class="status-label">Current Status</div>
                            <div class="status-value">${orderStatusText}</div>
                        </div>
                        <div>
                            <div class="status-label">Process</div>
                            <div class="status-value">${currentProcessText}</div>
                        </div>
                    </div>
                    
                    <div class="section">
                        <div class="section-title">Client Information</div>
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Client Name</div>
                                <div class="info-value">${clientName}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Phone Number</div>
                                <div class="info-value">${clientPhone}</div>
                            </div>
                            <div class="info-item full-width">
                                <div class="info-label">Delivery Address</div>
                                <div class="info-value">${deliveryAddress}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="section">
                        <div class="section-title">Order Details</div>
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Order Name</div>
                                <div class="info-value large">${orderName}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Product</div>
                                <div class="info-value">${productName}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Quantity</div>
                                <div class="info-value">${quantity}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Total Cost</div>
                                <div class="info-value large">${orderPrice}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="section">
                        <div class="section-title">Processing Information</div>
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Processing Branch</div>
                                <div class="info-value">${originBranch}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Origin Branch</div>
                                <div class="info-value">${processingBranch}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Order Date</div>
                                <div class="info-value">${orderDate}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">${orderStatus.value === 'Delivered' || orderStatus.value === 'Fulfilled' ? 'Delivery Date' : 'Estimated Delivery Date'}</div>
                                <div class="info-value">${targetDeliveryDate}</div>
                            </div>
                            <div class="info-item full-width">
                                <div class="info-label">Waybill Number</div>
                                <div class="info-value">${waybill}</div>
                            </div>
                        </div>
                    </div>
                    
                    ${orderNotes ? `
                        <div class="section">
                            <div class="section-title">Special Notes</div>
                            <div class="notes-box">
                                <div class="notes-content">${orderNotes}</div>
                            </div>
                        </div>
                    ` : ''}
                </div>
                
                <div class="footer">
                    <div class="footer-text">This is an official order card from ${siteName}</div>
                    <div class="print-date">Printed on ${moment().format('MMMM DD, YYYY [at] h:mm A')}</div>
                </div>
            </div>
            
            <script>
                window.onload = function() {
                    window.print();
                    window.onafterprint = function() {
                        window.close();
                    };
                };
            <` + `/script>
        <` + `/body>
        <` + `/html>
    `);
    printWindow.document.close();
}
</script>

<style lang="scss" scoped>

</style>
