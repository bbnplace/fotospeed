<template>
    <Head title="My Invoice"></Head>
    <BackendLayout>
        <VRow>
            <VCol>
                <Link href="#" class="font-bold" @click="goBack">Back</Link>
            </VCol>
        </VRow>
        <Panel snippet-title="Invoice Successfully Generated" v-if="isNewlyGeneratedInvoice">
            <p>
                Please Navigate to the task dashboard and move Invoice Generation Task to <b>Done</b>.<br />
                Once all tasks in this process are <b>done</b>, a link to login and make payment will be sent to the customer.
            </p>
            <p>
                <Link :href="user.isAdmin ? route('tasks.order.dashboard', [invoice.order_id]) : route('dashboard')" class="font-bold btn btn-info">Goto Task Dashboard</Link>
            </p>
        </Panel>
        <Panel snippetTitle="Invoice">
            <VRow>
                <VCol>
                    <h4>Invoice #</h4>
                    <p>{{ invoiceNumber }}</p>
                </VCol>
                <VCol>
                    <h4>Date</h4>
                    <p>{{ invoiceDate }}</p>
                </VCol>
            </VRow>
            <VRow>
                <VCol>
                    <h3>From:</h3>
                    <address>
                        <p class="font-bold">{{ company.name }}</p>
                        <p>{{ company.address }}</p>
                        <p>{{ company.email }}</p>
                        <p>{{ company.phone }}</p>
                    </address>
                </VCol>
                <VCol>
                    <h3>To:</h3>
                    <address>
                        <p class="font-bold">{{ client.name }}</p>
                        <p>{{ client.address }}</p>
                        <p>{{ client.email }}</p>
                    </address>
                </VCol>
            </VRow>
            <table class="invoice-table">
            <thead>
                <tr>
                <th>Order Name</th>
                <th>Quantity</th>
                <th>Price (₦)</th>
                <th>Total (₦)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                <td>{{ invoice.order.name }}</td>
                <td>1</td>
                <td>{{ formatter.format(invoice.order.total_cost) }}</td>
                <td>{{ formatter.format(invoice.order.total_cost) }}</td>
                </tr>
            </tbody>
            </table>
            <VRow>
                <VCol class="text-right">
                    <h3>Total: ₦{{ formatter.format(invoice.order.total_cost) }} [{{ invoice.invoice_status.name.toUpperCase() }}]</h3>
                </VCol>
            </VRow>
            <VRow v-if="invoice.invoice_status.name == 'Unpaid' && user.isCustomer">
                <VCol class="text-right">
                    <Paystack :data="paystackData" @paymentCompleted="handlePaymentCompletion" />
                </VCol>
            </VRow>

            <!-- Staff Offline Payment Panel -->
            <VRow v-if="invoice.invoice_status.name == 'Unpaid' && !user.isCustomer && !customerPaymentProof">
                <VCol>
                    <VCard class="mb-4">
                        <VCardTitle>Offline Payment Entry</VCardTitle>
                        <VCardText>
                            <div v-if="!bankPaymentSuccess">
                                <VAlert type="info" class="mb-4">
                                    Use this form to record a payment made by the customer via Bank Transfer, USSD, or Bank Deposit.
                                </VAlert>
                                
                                <VAlert v-if="bankPaymentError" type="error" class="mb-4" closable @click:close="bankPaymentError = ''">{{ bankPaymentError }}</VAlert>
                                
                                <VForm @submit.prevent="confirmSubmission">
                                    <VRow>
                                        <VCol cols="12" sm="6">
                                            <VTextField
                                                v-model="bankPaymentForm.amount"
                                                label="Amount Paid"
                                                prefix="₦"
                                                type="number"
                                                variant="outlined"
                                                density="compact"
                                                :error-messages="bankPaymentFormErrors.amount"
                                                required
                                            ></VTextField>
                                        </VCol>
                                        <VCol cols="12" sm="6">
                                            <VSelect
                                                v-model="bankPaymentForm.payment_method"
                                                label="Payment Method"
                                                :items="['Transfer', 'USSD', 'Bank Deposit']"
                                                variant="outlined"
                                                density="compact"
                                                :error-messages="bankPaymentFormErrors.payment_method"
                                                required
                                            ></VSelect>
                                        </VCol>
                                        <VCol cols="12" sm="6">
                                            <VAutocomplete
                                                v-model="bankPaymentForm.customer_bank"
                                                label="Customer Bank"
                                                :items="banks"
                                                variant="outlined"
                                                density="compact"
                                                :error-messages="bankPaymentFormErrors.customer_bank"
                                                required
                                            ></VAutocomplete>
                                        </VCol>
                                        <VCol cols="12" sm="6">
                                            <VTextField
                                                v-model="bankPaymentForm.depositor_name"
                                                label="Depositor/Account Name"
                                                variant="outlined"
                                                density="compact"
                                                :error-messages="bankPaymentFormErrors.depositor_name"
                                                required
                                            ></VTextField>
                                        </VCol>
                                        <VCol cols="12" sm="6">
                                            <VTextField
                                                v-model="bankPaymentForm.transaction_reference"
                                                label="Transaction Reference"
                                                variant="outlined"
                                                density="compact"
                                                :error-messages="bankPaymentFormErrors.transaction_reference"
                                                required
                                            ></VTextField>
                                        </VCol>
                                        <VCol cols="12" sm="6">
                                            <VTextField
                                                v-model="bankPaymentForm.payment_date"
                                                label="Payment Date"
                                                type="date"
                                                variant="outlined"
                                                density="compact"
                                                :error-messages="bankPaymentFormErrors.payment_date"
                                                :max="todayDate"
                                                required
                                            ></VTextField>
                                        </VCol>
                                        <VCol cols="12" class="text-right">
                                            <VBtn
                                                type="submit"
                                                color="primary"
                                                :loading="submittingBankPayment"
                                                :disabled="submittingBankPayment"
                                            >Submit Payment</VBtn>
                                        </VCol>
                                    </VRow>
                                </VForm>
                            </div>
                            <div v-else class="text-center py-5">
                                <VIcon size="64" color="success" class="mb-3">mdi-check-circle-outline</VIcon>
                                <h3 class="text-success mb-2">Payment Submitted Successfully!</h3>
                                <p class="mb-4">{{ bankPaymentSuccessMessage }}</p>
                                <div class="d-flex justify-center align-center flex-column">
                                    <VProgressCircular
                                        :model-value="(countdown / 7) * 100"
                                        color="primary"
                                        size="64"
                                        width="6"
                                        class="mb-2"
                                    >
                                        {{ countdown }}
                                    </VProgressCircular>
                                    <p class="text-caption text-grey">Reloading in {{ countdown }} seconds...</p>
                                </div>
                            </div>
                        </VCardText>
                    </VCard>
                </VCol>
            </VRow>

            <!-- Confirmation Dialog -->
            <VDialog v-model="showConfirmationDialog" max-width="500">
                <VCard>
                    <VCardTitle>Confirm Payment Details</VCardTitle>
                    <VCardText>
                        <p class="mb-3">Please crosscheck the data you are about to submit. <b>You will not be able to edit this data after submission.</b></p>
                        <VList density="compact">
                            <VListItem>
                                <template v-slot:prepend><b class="mr-2">Amount:</b></template>
                                ₦{{ formatter.format(bankPaymentForm.amount) }}
                            </VListItem>
                            <VListItem>
                                <template v-slot:prepend><b class="mr-2">Method:</b></template>
                                {{ bankPaymentForm.payment_method }}
                            </VListItem>
                            <VListItem>
                                <template v-slot:prepend><b class="mr-2">Bank:</b></template>
                                {{ bankPaymentForm.customer_bank }}
                            </VListItem>
                            <VListItem>
                                <template v-slot:prepend><b class="mr-2">Depositor:</b></template>
                                {{ bankPaymentForm.depositor_name }}
                            </VListItem>
                             <VListItem>
                                <template v-slot:prepend><b class="mr-2">Ref:</b></template>
                                {{ bankPaymentForm.transaction_reference }}
                            </VListItem>
                             <VListItem>
                                <template v-slot:prepend><b class="mr-2">Date:</b></template>
                                {{ bankPaymentForm.payment_date }}
                            </VListItem>
                        </VList>
                    </VCardText>
                    <VCardActions>
                        <VSpacer></VSpacer>
                        <VBtn color="error" text @click="showConfirmationDialog = false">Cancel</VBtn>
                        <VBtn color="success" text @click="submitBankPayment" :loading="submittingBankPayment">Confirm & Submit</VBtn>
                    </VCardActions>
                </VCard>
            </VDialog>
        </Panel>
        <Panel snippet-title="Paystack Transaction Details" v-if="paystackPaymentDetails != null">
            <VRow class="mb-2">
                <VCol cols="12" sm="6" md="4">
                    <h5 class="my-3 text-blue">Payment Channel</h5>
                    <VRow>
                        <VCol cols="12" class="pb-0"><b>Payment Method</b><br />{{ paymentAuthorization.channel }}</VCol>
                        <VCol cols="12" class="pb-0"><b>Card Type</b><br />{{ paymentAuthorization.card_type }}</VCol>
                        <VCol cols="12" class="pb-0"><b>Bank</b><br />{{ paymentAuthorization.bank }}</VCol>
                        <VCol cols="12" class="pb-0"><b>Card Brand</b><br />{{ paymentAuthorization.brand }}</VCol>
                        <VCol cols="12" class="pb-0"><b>Card Number</b><br />xxxx xxxx xxxx {{ paymentAuthorization.last4 }}</VCol>
                        <VCol cols="12" class="pb-0"><b>Country Code</b><br />{{ paymentAuthorization.country_code }}</VCol>
                    </VRow>
                </VCol>
                <VCol cols="12" sm="6" md="4">
                    <h5 class="my-3 text-blue">Transaction</h5>
                    <VRow>
                        <VCol cols="12" class="pb-0"><b>Transaction Status</b><br />{{ paystackPaymentDetails.status.toUpperCase() }}</VCol>
                        <VCol cols="12" class="pb-0"><b>Paystack Trnx ID</b><br />{{ paystackPaymentDetails.id }}</VCol>
                        <VCol cols="12" class="pb-0"><b>OMS Reference</b><br />{{ paystackPaymentDetails.reference }}</VCol>
                        <VCol cols="12" class="pb-0"><b>Mode / Domain</b><br />{{ paystackPaymentDetails.domain }}</VCol>
                        <VCol cols="12" class="pb-0"><b>Transaction Amount</b><br />{{ paystackPaymentDetails.currency }} {{ formatter.format(paystackPaymentDetails.amount / 100) }}</VCol>
                        <VCol cols="12" class="pb-0"><b>Transaction Fees</b><br />{{ paystackPaymentDetails.currency }} {{ formatter.format(paystackPaymentDetails.fees / 100) }}</VCol>
                        <VCol cols="12" class="pb-0"><b>Date Paid</b><br />{{ moment(paystackPaymentDetails.paidAt).calendar() }}</VCol>
                    </VRow>
                </VCol>
                <VCol cols="12" sm="6" md="4">
                    <h5 class="my-3 text-blue">Customer</h5>
                    <VRow>
                        <VCol cols="12" class="pb-0"><b>Customer Name</b><br />{{ paymentCustomerDetail.first_name ?? '-' }} {{ paymentCustomerDetail.last_name ?? '-' }}</VCol>
                        <VCol cols="12" class="pb-0"><b>Email</b><br />{{ paymentCustomerDetail.email }}</VCol>
                        <VCol cols="12" class="pb-0"><b>Mobile</b><br />{{ paymentCustomerDetail.phone }}</VCol>
                        <VCol cols="12" class="pb-0"><b>Customer ID</b><br />{{ paymentCustomerDetail.id }}</VCol>
                        <VCol cols="12" class="pb-0"><b>Customer Code</b><br />{{ paymentCustomerDetail.customer_code }}</VCol>
                        <VCol cols="12" class="pb-0"><b>Risk Action</b><br />{{ paymentCustomerDetail.risk_action }}</VCol>
                    </VRow>
                </VCol>
            </VRow>
        </Panel>
        <Panel snippet-title="Payment Details" v-if="offlinePaymentData">
                <template v-if="offlinePaymentData.paymentMethod == 'Bank Transfer'">
                    <VRow class="mb-3">
                        <VCol cols="12" sm="6" md="4">
                            <h5 class="my-3 text-blue">Customer</h5>
                            <VRow>
                                <VCol cols="12" class="pb-0"><b>Name</b><br />{{ offlinePaymentData.customerAccountName ?? '-' }}</VCol>
                                <VCol cols="12" class="pb-0"><b>Bank</b><br />{{ offlinePaymentData.customerBank }}</VCol>
                                <VCol cols="12" class="pb-0"><b>Account Number</b><br />{{ offlinePaymentData.customerAccountNumber }}</VCol>
                            </VRow>
                        </VCol>
                        <VCol cols="12" sm="6" md="4">
                            <h5 class="my-3 text-blue">Receiving Account</h5>
                            <VRow>
                                <VCol cols="12" class="pb-0"><b>Name</b><br />{{ offlinePaymentData.organizationAccountName ?? '-' }}</VCol>
                                <VCol cols="12" class="pb-0"><b>Bank</b><br />{{ offlinePaymentData.organizationBank }}</VCol>
                                <VCol cols="12" class="pb-0"><b>Account Number</b><br />{{ offlinePaymentData.organizationAccountNumber }}</VCol>
                            </VRow>
                        </VCol>
                        <VCol cols="12" sm="6" md="4">
                            <h5 class="my-3 text-blue">Transaction</h5>
                            <VRow>
                                <VCol cols="12" class="pb-0"><b>Transaction Reference</b><br />{{ offlinePaymentData.transactionReference ?? '-' }}</VCol>
                                <VCol cols="12" class="pb-0"><b>Amount</b><br />₦{{ offlinePaymentData.currency ?? '' }} {{ formatter.format(offlinePaymentData.amountPaid) }}</VCol>
                                <VCol cols="12" class="pb-0"><b>Date</b><br />{{ moment(offlinePaymentData.paymentDate).calendar() }}</VCol>
                                <VCol cols="12" class="pb-0"><b>Payment Method</b><br />{{ offlinePaymentData.paymentMethod }}</VCol>
                                <VCol cols="12" class="pb-0"><b>Status</b><br />{{ offlinePaymentData.status }}</VCol>
                            </VRow>
                        </VCol>
                    </VRow>
            </template>
            <template v-if="offlinePaymentData.paymentMethod == 'Cash'">
                <VRow class="mb-3">
                    <VCol cols="12" md="4" class="pb-0"><b>Payment Method</b><br />{{ offlinePaymentData.paymentMethod }}</VCol>
                    <VCol cols="12" md="4" class="pb-0"><b>Amount</b><br />₦{{ formatter.format(offlinePaymentData.amountPaid) }}</VCol>
                    <VCol cols="12" md="4" class="pb-0"><b>Cash Received By</b><br />{{ offlinePaymentData.whoReceivedCash }}</VCol>
                </VRow>
            </template>
        </Panel>

        <Panel snippet-title="Customer Payment Proof" v-if="customerPaymentProof">
            <VRow class="mb-3">
                <VCol cols="12" sm="6" md="4">
                    <h5 class="my-3 text-blue">Customer</h5>
                    <VRow>
                        <VCol cols="12" class="pb-0"><b>Name</b><br />{{ customerPaymentProof.customerAccountName ?? '-' }}</VCol>
                        <VCol cols="12" class="pb-0"><b>Bank</b><br />{{ customerPaymentProof.customerBank }}</VCol>
                        <VCol cols="12" class="pb-0"><b>Account Number</b><br />{{ customerPaymentProof.customerAccountNumber ?? '-' }}</VCol>
                    </VRow>
                </VCol>
                <VCol cols="12" sm="6" md="4">
                    <h5 class="my-3 text-blue">Receiving Account</h5>
                    <VRow>
                        <VCol cols="12" class="pb-0"><b>Name</b><br />{{ customerPaymentProof.organizationAccountName ?? '-' }}</VCol>
                        <VCol cols="12" class="pb-0"><b>Bank</b><br />{{ customerPaymentProof.organizationBank }}</VCol>
                        <VCol cols="12" class="pb-0"><b>Account Number</b><br />{{ customerPaymentProof.organizationAccountNumber }}</VCol>
                    </VRow>
                </VCol>
                <VCol cols="12" sm="6" md="4">
                    <h5 class="my-3 text-blue">Transaction</h5>
                    <VRow>
                        <VCol cols="12" class="pb-0"><b>Transaction Reference</b><br />{{ customerPaymentProof.transactionReference ?? '-' }}</VCol>
                        <VCol cols="12" class="pb-0"><b>Amount</b><br />₦{{ customerPaymentProof.currency ?? '' }} {{ formatter.format(customerPaymentProof.amountPaid) }}</VCol>
                        <VCol cols="12" class="pb-0"><b>Date</b><br />{{ moment(customerPaymentProof.paymentDate).calendar() }}</VCol>
                        <VCol cols="12" class="pb-0"><b>Payment Method</b><br />{{ customerPaymentProof.paymentMethod }}</VCol>
                        <VCol cols="12" class="pb-0"><b>Status</b><br />{{ invoice.invoice_status.name == 'Paid' ? 'Confirmed' : customerPaymentProof.status }}</VCol>
                    </VRow>
                </VCol>
            </VRow>
        </Panel>



        <div class="my-4 text-right" v-if="customerPaymentProof && invoice.invoice_status.name == 'Unpaid'">
             <VBtn color="success" @click="showAcknowledgeModal = true" v-if="canApprovePayment">Acknowledge Payment</VBtn>
        </div>

        <VDialog v-model="showAcknowledgeModal" max-width="500">
            <VCard>
                <VCardTitle>Confirm Payment Receipt</VCardTitle>
                <VCardText>
                    By clicking the 'Yes' button below you confirm that you have received payment of <b>₦{{ formatter.format(customerPaymentProof.amountPaid) }}</b> from the customer.
                </VCardText>
                <VCardActions>
                    <VSpacer></VSpacer>
                    <VBtn color="error" text @click="showAcknowledgeModal = false">No</VBtn>
                    <VBtn color="success" text @click="confirmPayment" :loading="processingPayment">Yes</VBtn>
                </VCardActions>
            </VCard>
        </VDialog>
    </BackendLayout>
</template>

<script setup>
    import { usePage, Head, router, Link } from "@inertiajs/vue3";
    import BackendLayout from "@/Layouts/BackendLayout.vue";
    import Panel from '@/Layouts/Shared/Panel.vue'
    import Paystack from '@/Components/Paystack.vue';
    import moment from 'moment';
    import { ref, computed } from 'vue';
    import axios from 'axios';

    const user = usePage().props.auth.user;
    const invoice = usePage().props.invoice;
    const paystackData = usePage().props.paystack;
    const invoiceRefSrc = usePage().props.invoice_no_src;
    const paystackPaymentDetails = invoice.paystack_response != null ? JSON.parse(invoice.paystack_response) : null;
    const paymentAuthorization = invoice.paystack_response != null ? paystackPaymentDetails.authorization : null;
    const paymentCustomerDetail = invoice.paystack_response != null ? paystackPaymentDetails.customer : null;
    const isNewlyGeneratedInvoice = usePage().props.newlyGeneratedInvoice;
    const offlinePaymentData = invoice.offline_payment_data != null ? JSON.parse(invoice.offline_payment_data) : null;
    const customerPaymentProof = invoice.customer_payment_proof != null ? JSON.parse(invoice.customer_payment_proof) : null;
    const banks = usePage().props.banks;
    const approverRole = usePage().props.approverRole;
    const userRole = usePage().props.userRole;

    const canApprovePayment = computed(() => {
        return user.isAdmin || userRole === approverRole;
    });

    const handlePaymentCompletion = data => {
        setTimeout(()=>{
            router.visit(route('customer.receipt', [invoice.id]))
        }, 1000);
    }

    const showAcknowledgeModal = ref(false);
    const processingPayment = ref(false);

    const confirmPayment = async () => {
        processingPayment.value = true;
        
        const payload = {
            orderId: invoice.order_id,
            status: 'Paid',
            paymentMethod: customerPaymentProof.paymentMethod,
            amountPaid: customerPaymentProof.amountPaid,
            customerBank: customerPaymentProof.customerBank,
            customerAccountName: customerPaymentProof.customerAccountName,
            customerAccountNumber: customerPaymentProof.customerAccountNumber,
            organizationBank: customerPaymentProof.organizationBank,
            organizationAccountName: customerPaymentProof.organizationAccountName,
            organizationAccountNumber: customerPaymentProof.organizationAccountNumber,
            whoReceivedCash: customerPaymentProof.whoReceivedCash,
            paymentDate: customerPaymentProof.paymentDate,
            transactionReference: customerPaymentProof.transactionReference,
        };

        try {
            const response = await axios.post(route('order.update-payment'), payload);
            if (response.data && response.data.status == "success") {
                showAcknowledgeModal.value = false;
                router.reload();
            }
        } catch (error) {
            console.error(error);
            alert("Failed to update payment status. Please try again.");
        } finally {
            processingPayment.value = false;
        }
    }

// Other data
    const company = usePage().props.company;

    const client = {
        name: invoice.user.name,
        address: invoice.order.delivery_address,
        email: invoice.user.email
    };

    const invoiceNumber = invoiceRefSrc == "System Generated" ? invoice.id : invoice.order.order_number;
    const invoiceDate = moment(invoice.created_at).format('LL');

    const formatter = new Intl.NumberFormat('en-US', {
        style: 'decimal',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    });

    const goBack = () => {
        window.history.back()
    }

    // Bank Payment Form Logic
    const submittingBankPayment = ref(false);
    const bankPaymentSuccess = ref(false);
    const bankPaymentSuccessMessage = ref('');
    const bankPaymentError = ref('');
    const todayDate = new Date().toISOString().split('T')[0];
    const showConfirmationDialog = ref(false);
    const countdown = ref(7);
    
    const bankPaymentForm = ref({
        amount: invoice.order.total_cost,
        payment_method: '',
        customer_bank: '',
        depositor_name: '',
        transaction_reference: '',
        payment_date: todayDate,
    });

    const bankPaymentFormErrors = ref({
        amount: '',
        payment_method: '',
        customer_bank: '',
        depositor_name: '',
        transaction_reference: '',
        payment_date: '',
    });

    const confirmSubmission = () => {
        showConfirmationDialog.value = true;
    }

    const submitBankPayment = async () => {
        submittingBankPayment.value = true;
        bankPaymentError.value = '';
        // bankPaymentSuccess.value = false; // Don't reset this here as it controls the view
        
        // Reset errors
        Object.keys(bankPaymentFormErrors.value).forEach(key => {
            bankPaymentFormErrors.value[key] = '';
        });

        try {
            const response = await axios.post(route('invoice.submit-payment', invoice.id), bankPaymentForm.value);
            
            if (response.data.status === 'success') {
                bankPaymentSuccessMessage.value = response.data.message;
                bankPaymentSuccess.value = true;
                showConfirmationDialog.value = false;
                
                // Start countdown
                const timer = setInterval(() => {
                    countdown.value--;
                    if (countdown.value <= 0) {
                        clearInterval(timer);
                        router.reload();
                    }
                }, 1000);
            }
        } catch (error) {
            showConfirmationDialog.value = false; // Close dialog on error
            if (error.response && error.response.status === 422) {
                const errors = error.response.data.errors;
                Object.keys(errors).forEach(key => {
                    if (bankPaymentFormErrors.value.hasOwnProperty(key)) {
                        bankPaymentFormErrors.value[key] = errors[key][0];
                    }
                });
                bankPaymentError.value = error.response.data.message || 'Please correct the errors in the form.';
            } else {
                bankPaymentError.value = error.response?.data?.message || 'Something went wrong. Please try again later.';
            }
        }

        submittingBankPayment.value = false;
    };
</script>

<style scoped>
    .invoice {
        font-family: Arial, sans-serif;
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #ccc;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        background: white;
    }
    .invoice-header {
        /* display: flex; */
        /* justify-content: space-between; */
        margin-bottom: 20px;
    }
    .invoice-details {
        display: flex;
        justify-content: space-between;
        margin-right: 20px;
    }
    .invoice-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    .invoice-table th,
    .invoice-table td {
        border: 1px solid #ddd;
        padding: 8px;
    }
    .invoice-footer {
        text-align: right;
        font-size: 1.2em;
        font-weight: bold;
    }

    .address-block {
        flex: 1;
        margin-right: 20px;
    }
    address{
        margin: 0 0 2rem;
        line-height: 1.5;
    }
    address p{
        margin-bottom: 5px;
    }
</style>
