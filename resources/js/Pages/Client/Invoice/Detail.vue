<template>
    <Head title="My Invoice"></Head>
    <ClientLayout>

        <div class="invoice mt-4">
            <h1 class="mb-4">Invoice</h1>
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
                        <!-- Debug: Email is '{{ client.email }}' -->
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
            <VRow v-if="invoice.invoice_status.name == 'Unpaid'">
                <VCol class="text-right">
                    <div v-if="emailMissing">
                        <VAlert type="warning" class="mb-4 text-left">
                            Please update your email address to proceed with payment.
                        </VAlert>
                        <VForm @submit.prevent="updateEmail">
                            <VRow>
                                <VCol cols="12" sm="8" offset-sm="4">
                                    <VTextField
                                        v-model="emailUpdateForm.email"
                                        label="Email Address"
                                        type="email"
                                        :error-messages="emailUpdateForm.errors.email"
                                        required
                                    ></VTextField>
                                </VCol>
                                <VCol cols="12" sm="8" offset-sm="4" class="text-right">
                                    <VBtn
                                        color="primary"
                                        type="submit"
                                        :loading="emailUpdateForm.processing"
                                    >Save Email & Continue</VBtn>
                                </VCol>
                            </VRow>
                        </VForm>
                    </div>
                    <div v-else>
                        <Paystack :data="paystackData" @paymentCompleted="handlePaymentCompletion" />
                        
                        <!-- Bank Transfer Payment Section -->
                        <div v-if="bank_account.bank_name && bank_account.account_number" class="mt-6">
                            <VDivider class="my-4"></VDivider>
                            <h3 class="mb-3">Or Pay via Bank Transfer</h3>
                            <VCard class="mb-4">
                                <VCardText>
                                    <h4 class="mb-2">Bank Account Details</h4>
                                    <p><strong>Bank Name:</strong> {{ bank_account.bank_name }}</p>
                                    <p><strong>Account Number:</strong> {{ bank_account.account_number }}</p>
                                    <p><strong>Account Name:</strong> {{ company.name }}</p>
                                </VCardText>
                            </VCard>
                            
                            <VBtn
                                v-if="!showBankPaymentForm"
                                color="success"
                                @click="showBankPaymentForm = true"
                                class="mb-4"
                            >I have paid</VBtn>
                            
                            <VCard v-if="showBankPaymentForm">
                                <VCardTitle>Payment Confirmation</VCardTitle>
                                <VCardText>
                                    <VAlert v-if="bankPaymentError" type="error" class="mb-4" closable @click:close="bankPaymentError = ''">{{ bankPaymentError }}</VAlert>
                                    <VAlert v-if="bankPaymentSuccess" type="success" class="mb-4">{{ bankPaymentSuccess }}</VAlert>
                                    
                                    <VForm @submit.prevent="submitBankPayment">
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
                                                <VTextField
                                                    v-model="bankPaymentForm.customer_bank"
                                                    label="Your Bank"
                                                    variant="outlined"
                                                    density="compact"
                                                    :error-messages="bankPaymentFormErrors.customer_bank"
                                                    required
                                                ></VTextField>
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
                                                <VBtn
                                                    @click="showBankPaymentForm = false"
                                                    class="ml-2"
                                                >Cancel</VBtn>
                                            </VCol>
                                        </VRow>
                                    </VForm>
                                </VCardText>
                            </VCard>
                        </div>
                    </div>
                </VCol>
            </VRow>
        </div>
    </ClientLayout>
</template>

<script setup>
    import { usePage, Head, router, useForm } from "@inertiajs/vue3";
    import ClientLayout from "@/Layouts/ClientLayout.vue";
    import { ref, computed } from 'vue';
    import Paystack from '@/Components/Paystack.vue';
    import moment from 'moment';
    import axios from 'axios';

    const invoice = usePage().props.invoice;
    const paystackData = usePage().props.paystack;
    const invoiceRefSrc = usePage().props.invoice_no_src;
    const bank_account = usePage().props.bank_account;

    const handlePaymentCompletion = data => {
        setTimeout(()=>{
            router.visit(route('customer.receipt', [invoice.id]))
        }, 1000);
    }

    // Other data
    const company = usePage().props.company;

    const client = computed(() => {
        const inv = usePage().props.invoice;
        return {
            name: inv.user.name,
            address: inv.order.delivery_address,
            email: inv.user.email
        }
    });

    const emailMissing = computed(() => !client.value.email || client.value.email == null || client.value.email == '');

    const emailUpdateForm = useForm({
        email: ''
    });

    const updateEmail = async () => {
        emailUpdateForm.clearErrors();
        try {
            const response = await axios.put(route('customer.update-email'), {
                email: emailUpdateForm.email
            });
            
            if (response.data.status === 'success') {
                // Reload the page to refresh data and show payment button
                router.reload({ only: ['invoice', 'paystack'] });
                // Also update paystack data email
                paystackData.email = response.data.user.email;
            }
        } catch (error) {
             if (error.response && error.response.status === 422) {
                emailUpdateForm.setError(error.response.data.errors);
            } else {
                // Handle generic error
                console.error(error);
            }
        }
    };

    const invoiceNumber = invoiceRefSrc == "System Generated" ? invoice.id : invoice.order.order_number;
    const invoiceDate = moment(invoice.created_at).format('LL');

    const formatter = new Intl.NumberFormat('en-US', {
        style: 'decimal',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    });

    // Bank Payment Form
    const showBankPaymentForm = ref(false);
    const submittingBankPayment = ref(false);
    const bankPaymentSuccess = ref('');
    const bankPaymentError = ref('');
    const todayDate = new Date().toISOString().split('T')[0];
    
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

    const submitBankPayment = async () => {
        submittingBankPayment.value = true;
        bankPaymentError.value = '';
        bankPaymentSuccess.value = '';
        
        // Reset errors
        Object.keys(bankPaymentFormErrors.value).forEach(key => {
            bankPaymentFormErrors.value[key] = '';
        });

        try {
            const response = await axios.post(route('customer.invoice.submit-bank-payment', invoice.id), bankPaymentForm.value);
            
            if (response.data.status === 'success') {
                bankPaymentSuccess.value = response.data.message;
                showBankPaymentForm.value = false;
                // Reset form
                bankPaymentForm.value = {
                    amount: invoice.order.total_cost,
                    payment_method: '',
                    customer_bank: '',
                    depositor_name: '',
                    transaction_reference: '',
                    payment_date: todayDate,
                };
            }
        } catch (error) {
            if (error.response && error.response.status === 422) {
                const errors = error.response.data.errors;
                Object.keys(errors).forEach(key => {
                    if (bankPaymentFormErrors.value.hasOwnProperty(key)) {
                        bankPaymentFormErrors.value[key] = errors[key][0];
                    }
                });
                bankPaymentError.value = error.response.data.message || 'Please correct the errors in the form.';
            } else {
                bankPaymentError.value = 'Something went wrong. Please try again later.';
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
