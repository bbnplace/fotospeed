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
                                    <b>Branch</b><br />
                                    {{ order.branch.name }}
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
                                            scroll-strategy="close"
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
import { usePage, Link } from "@inertiajs/vue3";
import axios from 'axios';
import Panel from '@/Layouts/Shared/Panel.vue';
import moment from 'moment';
// import { VTextField } from 'vuetify/lib/components/index.mjs';

const user = usePage().props.auth.user;
const order = usePage().props.order;
const orderDetail = usePage().props.orderDetail;
const hasInvoice = usePage().props.hasInvoice;
const canGenerateInvoice = usePage().props.canGenerateInvoice;
const invoicePaid = usePage().props.invoicePaid;
const canEditOrder = usePage().props.canEditOrder;
const canHoldOrder = usePage().props.canHoldOrder;
const canCancelOrder = usePage().props.canCancelOrder;
const canEditReferenceNumber = usePage().props.canEditReferenceNumber;
const canEditPrice = usePage().props.canEditPrice;
const canEditWaybill = usePage().props.canEditWaybill;
const showOverlay = ref(false);

const orderCancelResponse = ref("");
const cancellingOrderProgress = ref(false);
const orderStatus = ref(order.order_status.name)
const currentProcess = order.process ? order.process.name : "-";
const waybillNo = order.waybill_number;

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
    }, 10000);

}

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
        // reactiveOrderResponse.value = "";
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
}


const printOrderCard = () => {
    const printableElementId = 'order-card';
    const printContent = document.getElementById(printableElementId).innerHTML;

    const printWindow = window.open('','', 'height=600,width=800');
    // Add the HTML content to the new window
    // printWindow.document.open();
    printWindow.document.write(`
        <html>
        <head>
            <title>Print Job Card</title>
            <link href="https://cdn.jsdelivr.net/npm/vuetify@2.6.0/dist/vuetify.min.css" rel="stylesheet">
            <style>
            /* Add any specific styles for printing here */
            @media print {
                /* Print styles */
                body {
                    font-family: Arial, sans-serif;
                }
                .v-row {
                    display: flex !important;
                    flex-wrap: wrap !important;
                    margin-right: 0 !important;
                    margin-left: 0 !important;
                }

                /* Ensure columns behave correctly in print */
                .v-col {
                    flex-grow: 1 !important;
                    padding-right: 0 !important;
                    padding-left: 0 !important;
                    max-width: 100% !important;
                    box-sizing: border-box !important;
                }

                /* Full-width column for print */
                .v-col-12 {
                    flex-basis: 100% !important;
                    max-width: 100% !important;
                }

                /* 50% width column for print */
                .v-col-md-6 {
                    flex-basis: 50% !important;
                    max-width: 50% !important;
                }
            }
            </style>
        </head>
        <body>
            ${printContent}
        </body>
        </html>
    `);
    printWindow.document.close();

    // Trigger the print dialog
    printWindow.focus();
    printWindow.print();
}
</script>

<style lang="scss" scoped>

</style>
