<template>
    
    <v-btn
        prepend-icon="mdi-account-credit-card"
    >Update Payment
    <v-overlay
        v-model="showPaymentConfirmationOverlay"
        activator="parent"
        scroll-strategy="static"
        location-strategy="connected"
    >
        <v-card min-width="250" max-width="500" class="p-2" >
            <v-card-title>Update Payment Status</v-card-title>
            <v-card-text :style="{'overflow-y': 'auto', 'max-height': '320px'}">
                <v-alert
                    type="error"
                    :text="paymentUpdateError"
                    closable
                    class="mb-2"
                    v-if="paymentUpdateError.length"
                ></v-alert>
                <v-row>
                    <v-col cols="12" class="mt-2">
                        <v-select
                            v-model="selectedPaymentStatus"
                            label="Select Status"
                            :items="paymentStatuses"
                            variant="outlined"
                            :hide-details="paymentErrors.errors.status == undefined"
                            :error-messages="paymentErrors.errors.status"
                            density="compact"
                        ></v-select>
                    </v-col>
                    <v-col cols="12" sm="6" v-if="selectedPaymentStatus == 'Paid'">
                        <v-text-field
                            v-model="amountPaid"
                            label="Amount Paid"
                            prefix="₦"
                            type="text"
                            variant="outlined"
                            density="compact"
                            :hide-details="paymentErrors.errors.amountPaid == undefined"
                            :error-messages="paymentErrors.errors.amountPaid"
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" v-if="selectedPaymentStatus == 'Paid'">
                        <v-select
                            v-model="selectedPaymentMethod"
                            label="Payment Method"
                            :items="paymentMethods"
                            variant="outlined"
                            :hide-details="paymentErrors.errors.method == undefined"
                            :error-messages="paymentErrors.errors.method"
                            density="compact"
                        ></v-select>
                    </v-col>
                    <v-col cols="12" v-if="selectedPaymentStatus == 'Paid' && selectedPaymentMethod == 'Cash'">
                        <v-textarea
                            v-model="whoReceivedCash"
                            label="Who Received the Cash?"
                            variant="outlined"
                            :hide-details="paymentErrors.errors.whoReceivedCash == undefined"
                            :error-messages="paymentErrors.errors.whoReceivedCash"
                            clearable
                        ></v-textarea>
                    </v-col>
                    <v-col cols="12" sm="6" v-if="selectedPaymentMethod == 'Bank Transfer' && selectedPaymentStatus == 'Paid'">
                        <h4>From</h4>
                        <v-row>
                            <v-col cols="12">
                                <v-text-field
                                    v-model="customerBank"
                                    label="Customer's Bank"
                                    type="text"
                                    variant="outlined"
                                    density="compact"
                                    :hide-details="paymentErrors.errors.customerBank == undefined"
                                    :error-messages="paymentErrors.errors.customerBank"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-text-field
                                    v-model="customerAccountNumber"
                                    label="Customer's Account Number"
                                    type="tel"
                                    variant="outlined"
                                    density="compact"
                                    :hide-details="paymentErrors.errors.customerAccountNumber == undefined"
                                    :error-messages="paymentErrors.errors.customerAccountNumber"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-text-field
                                    v-model="customerAccountName"
                                    label="Customer's Account Name"
                                    type="text"
                                    variant="outlined"
                                    density="compact"
                                    :hide-details="paymentErrors.errors.customerAccountName == undefined"
                                    :error-messages="paymentErrors.errors.customerAccountName"
                                ></v-text-field>
                            </v-col>
                        </v-row>
                    </v-col>
                    <v-col cols="12" sm="6" v-if="selectedPaymentMethod == 'Bank Transfer' && selectedPaymentStatus == 'Paid'">
                        <h4>To</h4>
                        <v-row>
                            <v-col cols="12">
                                <v-text-field
                                    v-model="organizationBank"
                                    label="Organization's Bank"
                                    type="text"
                                    variant="outlined"
                                    density="compact"
                                    :hide-details="paymentErrors.errors.organizationBank == undefined"
                                    :error-messages="paymentErrors.errors.organizationBank"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-text-field
                                    v-model="organizationAccountNumber"
                                    label="Organization's Account Number"
                                    type="tel"
                                    variant="outlined"
                                    density="compact"
                                    :hide-details="paymentErrors.errors.organizationAccountNumber == undefined"
                                    :error-messages="paymentErrors.errors.organizationAccountNumber"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-text-field
                                    v-model="organizationAccountName"
                                    label="Organization's Account Name"
                                    type="text"
                                    variant="outlined"
                                    density="compact"
                                    :hide-details="paymentErrors.errors.organizationAccountName == undefined"
                                    :error-messages="paymentErrors.errors.organizationAccountName"
                                ></v-text-field>
                            </v-col>
                        </v-row>
                    </v-col>
                    <v-col cols="12" v-if="selectedPaymentMethod == 'Bank Transfer' && selectedPaymentStatus == 'Paid'">
                        <v-text-field
                            v-model="transactionReference"
                            label="Transaction Reference"
                            type="text"
                            variant="outlined"
                            density="compact"
                            :hide-details="paymentErrors.errors.transactionReference == undefined"
                            :error-messages="paymentErrors.errors.transactionReference"
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12" v-if="selectedPaymentStatus == 'Paid'">
                        <v-date-picker
                            v-model="paymentDate"
                            :max="todayDate"
                            show-adjacent-months
                            title="Transaction Date"
                            :hide-details="paymentErrors.errors.paymentDate == undefined"
                            :error-messages="paymentErrors.errors.paymentDate"
                        ></v-date-picker>
                    </v-col>
                    <v-col cols="12">
                        <v-progress-linear indeterminate color="red" v-if="updatingPaymentStatus"></v-progress-linear>
                        <p v-if="paymentUpdateSuccess.length">{{ paymentUpdateSuccess }}</p>
                    </v-col>
                </v-row>
            </v-card-text>
            <v-card-actions>
                <v-btn
                    @click="updatePaymentStatus"
                >Update</v-btn>
                <v-btn
                    color="red"
                    @click="showPaymentConfirmationOverlay = !showPaymentConfirmationOverlay"
                >Close</v-btn>
            </v-card-actions>
        </v-card>
    </v-overlay>
    </v-btn>
</template>

<script setup>
import { ref } from 'vue';
import { usePage } from "@inertiajs/vue3";
import axios from 'axios';

const emit = defineEmits(['statusUpdated']);

const order = usePage().props.order;
const selectedPaymentMethod = ref("");
const selectedPaymentStatus = ref("");
const paymentMethods = usePage().props.paymentMethods;
const paymentStatuses = usePage().props.paymentStatuses;
const paymentDate = ref(new Date());
const todayDate = ref(new Date());
const showPaymentConfirmationOverlay = ref(false);
const paymentUpdateError = ref("");
const paymentUpdateSuccess = ref("");
const updatingPaymentStatus = ref(false);

const customerBank = ref("");
const customerAccountNumber = ref("");
const customerAccountName = ref("");
const organizationBank = ref("");
const organizationAccountNumber = ref("");
const organizationAccountName = ref("");
const whoReceivedCash = ref("");
const amountPaid = ref(order.total_cost);
const transactionReference = ref("");
const paymentErrors = ref({
    errors: {}
});

const updatePaymentStatus = async () => {
    const payload = {
        orderId: order.id,
        status: selectedPaymentStatus.value,
        paymentMethod: selectedPaymentMethod.value,
        amountPaid: amountPaid.value,
        customerBank: customerBank.value,
        customerAccountName: customerAccountName.value,
        customerAccountNumber: customerAccountNumber.value,
        organizationBank: organizationBank.value,
        organizationAccountName: organizationAccountName.value,
        organizationAccountNumber: organizationAccountNumber.value,
        whoReceivedCash: whoReceivedCash.value,
        paymentDate: paymentDate.value,
        transactionReference: transactionReference.value,
    }
    updatingPaymentStatus.value = true;
    paymentUpdateError.value = "";
    paymentUpdateSuccess.value = "";
    paymentErrors.value.errors = {};

    try {
        const response = await axios.post(route('order.update-payment'), payload, {
            headers: {
                "Content-Type": "application/json"
            }
        });

        if (response.data && response.data.status == "success") {
            paymentUpdateSuccess.value = response.data.message;

            emit('statusUpdated', {
                paymentMethod: selectedPaymentMethod.value,
                invoicePaid: selectedPaymentStatus.value == 'Paid'
            });
        } else {
            
        }
    } catch (error) {
        if (error.response && error.response.status === 422) {
            paymentUpdateError.value = error.response.data.message;
            paymentErrors.value.errors = error.response.data.errors;
        } else {
            paymentUpdateError.value = "Something went wrong! Pls try again later.";
        }
    }

    updatingPaymentStatus.value = false;
}
</script>

