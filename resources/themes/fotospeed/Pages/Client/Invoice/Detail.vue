<template>
    <Head title="My Invoice"></Head>
    <DefaultLayout>

        <div class="invoice my-5">
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
                <VCol>
                    <div class="bg-light pa-4 rounded">
                        <h4 class="mb-2">Bank Account Details</h4>
                        <p><strong>Bank Name:</strong> {{ bank_account.bank_name }}</p>
                        <p><strong>Account Number:</strong> {{ bank_account.account_number }}</p>
                        <p><strong>Account Name:</strong> {{ bank_account.account_name || company.name }}</p>
                    </div>
                </VCol>
            </VRow>

             <!-- Loyalty Points & Totals -->
            <VRow>
                <VCol class="text-right">
                    <div v-if="invoice.points_redeemed > 0" class="mb-2 text-success">
                        <p class="mb-1"><small>Loyalty Points Redeemed:</small> <strong>{{ invoice.points_redeemed }}</strong></p>
                        <p class="mb-1"><small>Discount Applied:</small> <strong>-₦{{ formatter.format(invoice.points_discount_amount) }}</strong></p>
                        <VDivider class="my-2 ms-auto" style="width: 200px"></VDivider>
                    </div>
                    <h3>Total: ₦{{ formatter.format(invoice.order.total_cost) }} [{{ invoice.invoice_status.name.toUpperCase() }}]</h3>
                    <div v-if="invoice.points_redeemed > 0">
                        <small class="text-muted">(Net Paid: ₦{{ formatter.format(invoice.order.total_cost - invoice.points_discount_amount) }})</small>
                    </div>
                </VCol>
            </VRow>
        </div>

        <div class="invoice-related my-5">
            <!-- Paid Invoice Details -->
            <VRow v-if="invoice.invoice_status.name != 'Unpaid'" class="mt-4">
                <VCol>
                    <div class="px-4 pb-4">
                        <div class="d-flex justify-space-between align-center mb-2">
                             <h3 class="mb-0">Payment Information</h3>
                            <VChip :color="getStatusColor(invoice.invoice_status.name)" size="small" variant="flat">{{ invoice.invoice_status.name.toUpperCase() }}</VChip>
                        </div>
                        <VDivider class="mb-4"></VDivider>
                        
                        <!-- Paystack Payment -->
                        <div v-if="invoice.payment_method == 'Paystack' || invoice.paystack_response">
                            <VRow dense>
                                <VCol cols="4" class="text-medium-emphasis">Method</VCol>
                                <VCol cols="8" class="font-weight-bold">Paystack</VCol>
                                
                                <template v-if="parsedPaystackData">
                                    <VCol cols="4" class="text-medium-emphasis">Reference</VCol>
                                    <VCol cols="8" class="font-weight-bold tex-uppercase">{{ parsedPaystackData.reference }}</VCol>
                                    
                                    <VCol cols="4" class="text-medium-emphasis">Channel</VCol>
                                    <VCol cols="8" class="font-weight-bold text-capitalize">{{ parsedPaystackData.channel }}</VCol>
                                    
                                    <VCol cols="4" class="text-medium-emphasis">Paid At</VCol>
                                    <VCol cols="8" class="font-weight-bold">{{ formatDate(parsedPaystackData.paid_at) }}</VCol>
                                </template>
                            </VRow>
                        </div>
                        <!-- Bank Transfer Payment -->
                        <div v-else-if="invoice.customer_payment_proof">
                            <VRow dense>
                                <VCol cols="4" class="text-medium-emphasis">Method</VCol>
                                <VCol cols="8" class="font-weight-bold">Bank Transfer</VCol>
                                
                                <template v-if="parsedPaymentProof">
                                    <VCol cols="4" class="text-medium-emphasis">Bank</VCol>
                                    <VCol cols="8" class="font-weight-bold">{{ parsedPaymentProof.customerBank }}</VCol>
                                    
                                    <VCol cols="4" class="text-medium-emphasis">Account</VCol>
                                    <VCol cols="8" class="font-weight-bold">{{ parsedPaymentProof.customerAccountName }}</VCol>
                                    
                                    <VCol cols="4" class="text-medium-emphasis">Amount</VCol>
                                    <VCol cols="8" class="font-weight-bold">₦{{ formatter.format(parsedPaymentProof.amountPaid) }}</VCol>
                                    
                                    <VCol cols="4" class="text-medium-emphasis">Date</VCol>
                                    <VCol cols="8" class="font-weight-bold">{{ formatDate(parsedPaymentProof.paymentDate) }}</VCol>
                                    
                                    <VCol v-if="parsedPaymentProof.transactionReference" cols="4" class="text-medium-emphasis">Ref</VCol>
                                    <VCol v-if="parsedPaymentProof.transactionReference" cols="8" class="font-weight-bold">{{ parsedPaymentProof.transactionReference }}</VCol>
                                    
                                    <!-- Loyalty Points Used -->
                                    <template v-if="parsedPaymentProof.pointsRedeemed && parsedPaymentProof.pointsRedeemed > 0">
                                        <VCol cols="12" class="mt-2">
                                            <VDivider></VDivider>
                                        </VCol>
                                        <VCol cols="4" class="text-medium-emphasis">Points Used</VCol>
                                        <VCol cols="8" class="font-weight-bold text-purple">{{ formatter.format(parsedPaymentProof.pointsRedeemed) }} points</VCol>
                                        
                                        <VCol cols="4" class="text-medium-emphasis">Points Discount</VCol>
                                        <VCol cols="8" class="font-weight-bold text-success">-₦{{ formatter.format(parsedPaymentProof.pointsDiscount) }}</VCol>
                                        
                                        <VCol cols="4" class="text-medium-emphasis">Total Paid</VCol>
                                        <VCol cols="8" class="font-weight-bold">₦{{ formatter.format(parseFloat(parsedPaymentProof.amountPaid) + parseFloat(parsedPaymentProof.pointsDiscount)) }}</VCol>
                                    </template>
                                </template>
                            </VRow>
                        </div>
                            <div v-else>
                                <VRow dense>
                                <VCol cols="4" class="text-medium-emphasis">Method</VCol>
                                <VCol cols="8" class="font-weight-bold">{{ invoice.payment_method || 'N/A' }}</VCol>
                            </VRow>
                        </div>
                    </div>
                </VCol>
                
                <!-- Refund Information -->
                <VCol v-if="invoice.refunded">
                    <VAlert type="warning" border="start" variant="tonal">
                        <h4 class="alert-heading text-red-700">Refund Processed</h4>
                        <p class="text-sm">This invoice has been refunded.</p>
                        <hr class="my-2">
                        <p class="mb-0 text-sm">
                            <strong>Amount:</strong> ₦{{ formatter.format(invoice.refund_amount) }}<br>
                            <strong>Date:</strong> {{ formatDate(invoice.refunded_at) }}<br>
                            <strong>Account:</strong> {{ invoice.refund_account_name }} ({{ invoice.refund_bank_name }})
                        </p>
                    </VAlert>
                </VCol>
            </VRow>

             <!-- Unpaid Invoice Actions -->
            <VRow v-if="invoice.invoice_status.name == 'Unpaid'" class="mt-4">
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
                        
                        <!-- Loyalty Points Hint/Option -->
                        <VRow v-if="availablePoints >= minPointsRedeemable" class="mb-6">
                            <VCol>
                                <VCard class="mb-4" color="purple-darken-1" variant="elevated" elevation="3">
                                    <VCardText class="text-white">
                                        <div class="d-flex align-center justify-space-between mb-2">
                                            <div>
                                                <strong class="text-h6">🎁 Loyalty Points Available: {{ availablePoints }}</strong>
                                                <p class="text-caption mb-0" style="opacity: 0.9;">Redeem points to reduce your invoice amount</p>
                                            </div>
                                        </div>

                                        <VDivider class="my-3" style="border-color: rgba(255,255,255,0.3);"></VDivider>

                                        <div v-if="maxPointsUsable > 0">
                                            <VAlert color="white" class="mb-3" density="compact">
                                                <div class="text-purple-darken-2">
                                                    <p class="mb-1"><strong>Maximum Points You Can Use:</strong> {{ formatter.format(maxPointsUsable) }} points</p>
                                                    <p class="mb-1"><strong>Maximum Discount:</strong> ₦{{ formatter.format(maxDiscount) }}</p>
                                                    <p class="mb-0"><strong>Final Amount to Pay:</strong> ₦{{ formatter.format(finalAmount) }}</p>
                                                </div>
                                            </VAlert>

                                            <VCheckbox
                                                v-model="useLoyaltyPoints"
                                                label="I want to use my loyalty points to pay for this invoice"
                                                color="white"
                                                density="compact"
                                                hide-details
                                                class="text-white"
                                            ></VCheckbox>

                                            <VAlert v-if="useLoyaltyPoints" color="green-lighten-4" class="mt-3" density="compact">
                                                <strong class="text-green-darken-3">✓ Points will be redeemed automatically when you make payment</strong>
                                            </VAlert>
                                        </div>
                                        <VAlert v-else color="white" density="compact">
                                            <span class="text-purple-darken-2">You need at least {{ formatter.format(minPointsRedeemable) }} points to redeem rewards.</span>
                                        </VAlert>
                                    </VCardText>
                                </VCard>
                            </VCol>
                        </VRow>
                        
                        <Paystack :data="paystackData" @paymentCompleted="handlePaymentCompletion" @paymentError="handlePaymentError" />
                        
                        <!-- Paystack Error Message -->
                        <VAlert v-if="paystackError" type="error" density="compact" class="mt-3 mb-4" closable @click:close="paystackError = false">
                            <strong>Card Payment Unavailable</strong>
                            <p class="mb-0 mt-1">We're unable to process card payments at this time. Please use the bank transfer option below to complete your payment.</p>
                        </VAlert>
                        
                        <!-- Bank Transfer Payment Section -->
                        <div v-if="bank_account.bank_name && bank_account.account_number" class="mt-6">
                            <VDivider class="my-4"></VDivider>
                            <h3 class="mb-3">Or Pay via Bank Transfer</h3>
                            <VCard class="mb-4">
                                <VCardText>
                                    <h4 class="mb-2">Bank Account Details</h4>
                                    <p><strong>Bank Name:</strong> {{ bank_account.bank_name }}</p>
                                    <p><strong>Account Number:</strong> {{ bank_account.account_number }}</p>
                                    <p><strong>Account Name:</strong> {{ bank_account.account_name || company.name }}</p>
                                </VCardText>
                            </VCard>
                            
                            <VBtn
                                v-if="!showBankPaymentForm"
                                color="success"
                                @click="showBankPaymentForm = true"
                                class="mb-4"
                            >I have paid</VBtn>
                            
                            <VAlert v-if="bankPaymentError" type="error" class="mb-4" closable @click:close="bankPaymentError = ''">{{ bankPaymentError }}</VAlert>
                            <VAlert v-if="bankPaymentSuccess" type="success" class="mb-4">{{ bankPaymentSuccess }}</VAlert>

                            <VCard v-if="showBankPaymentForm">
                                <VCardTitle>Payment Confirmation</VCardTitle>
                                <VCardText>
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
                                                <VCombobox
                                                    v-model="bankPaymentForm.customer_bank"
                                                    label="Your Bank"
                                                    :items="banks"
                                                    variant="outlined"
                                                    density="compact"
                                                    :error-messages="bankPaymentFormErrors.customer_bank"
                                                    required
                                                ></VCombobox>
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
                                            <VCol cols="12">
                                                <VAlert v-if="!isPaymentAmountValid" type="warning" density="compact" class="mb-3">
                                                    <template v-if="useLoyaltyPoints && availablePoints >= minPointsRedeemable">
                                                        Minimum payment required: ₦{{ formatter.format(finalAmount) }} (after loyalty points discount)
                                                    </template>
                                                    <template v-else>
                                                        Full invoice amount required: ₦{{ formatter.format(invoiceTotal) }}
                                                    </template>
                                                </VAlert>
                                            </VCol>
                                            <VCol cols="12" class="text-right">
                                                <VBtn
                                                    type="submit"
                                                    color="primary"
                                                    :loading="submittingBankPayment"
                                                    :disabled="submittingBankPayment || !isPaymentAmountValid"
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
    </DefaultLayout>
</template>

<script setup>
    import { usePage, Head, router, useForm } from "@inertiajs/vue3";
    import DefaultLayout from '../../../Layouts/DefaultLayout.vue';
    import { ref, computed } from 'vue';
    import Paystack from '@/Components/Paystack.vue';
    import moment from 'moment';
    import axios from 'axios';

    const invoice = usePage().props.invoice;
    const paystackData = usePage().props.paystack;
    const invoiceRefSrc = usePage().props.invoice_no_src;
    const bank_account = usePage().props.bank_account;
    const banks = usePage().props.banks;

    // Loyalty Points Logic
    const availablePoints = ref(Math.round(usePage().props.availablePoints || 0));
    const settings = usePage().props.settings || {};
    const minPointsRedeemable = settings.min_points_redeemable || 100;
    const pointsToCurrencyRatio = parseFloat(settings.points_to_currency_ratio) || 1;
    const maxPercentage = parseFloat(settings.max_invoice_percentage_payable_by_points) || 100;
    const invoiceTotal = parseFloat(invoice.order.total_cost);
    const useLoyaltyPoints = ref(false);

    // Calculate maximum points that can be used
    const maxPointsUsable = computed(() => {
        const maxByPercentage = Math.floor((invoiceTotal * maxPercentage / 100) / pointsToCurrencyRatio);
        const maxByInvoiceTotal = Math.floor(invoiceTotal / pointsToCurrencyRatio);
        const maxByAvailable = availablePoints.value;
        return Math.min(maxByPercentage, maxByInvoiceTotal, maxByAvailable);
    });

    // Calculate maximum discount amount
    const maxDiscount = computed(() => {
        return maxPointsUsable.value * pointsToCurrencyRatio;
    });

    // Calculate final amount after discount
    const finalAmount = computed(() => {
        return Math.max(0, invoiceTotal - maxDiscount.value);
    });

    // Validate bank payment amount
    const isPaymentAmountValid = computed(() => {
        const amountPaid = parseFloat(bankPaymentForm.value.amount) || 0;
        
        // If customer is using loyalty points
        if (useLoyaltyPoints.value && availablePoints.value >= minPointsRedeemable) {
            // Amount paid must be >= final amount after discount
            return amountPaid >= finalAmount.value;
        }
        
        // If NOT using loyalty points, amount must equal full invoice total
        return amountPaid >= invoiceTotal;
    });

    // Paystack error handling
    const paystackError = ref(false);

    const handlePaymentCompletion = data => {
        setTimeout(()=>{
            router.visit(route('customer.receipt', [invoice.id]))
        }, 1000);
    }

    const handlePaymentError = (error) => {
        paystackError.value = true;
    }

    const parsedPaystackData = computed(() => {
        if (!invoice.paystack_response) return null;
        try {
            const data = typeof invoice.paystack_response === 'string' 
                ? JSON.parse(invoice.paystack_response) 
                : invoice.paystack_response;
            return {
                reference: data.reference,
                channel: data.channel,
                paid_at: data.paid_at || data.transaction_date,
            };
        } catch (e) {
            return null;
        }
    });

    const parsedPaymentProof = computed(() => {
        if (!invoice.customer_payment_proof) return null;
        try {
             return typeof invoice.customer_payment_proof === 'string' 
                ? JSON.parse(invoice.customer_payment_proof) 
                : invoice.customer_payment_proof;
        } catch (e) {
            return null;
        }
    });

    const formatDate = (dateString) => {
        if (!dateString) return 'N/A';
        return moment(dateString).format('LLL');
    };

    const getStatusColor = (statusName) => {
        const statusColors = {
            'Paid': 'success',
            'Unpaid': 'error',
            'Awaiting Verification': 'warning',
            'Failed': 'error',
            'Cancelled': 'grey'
        };
        return statusColors[statusName] || 'info';
    };

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
            // Include loyalty points flag in submission
            const paymentData = {
                ...bankPaymentForm.value,
                use_loyalty_points: useLoyaltyPoints.value
            };
            
            const response = await axios.post(route('customer.invoice.submit-bank-payment', invoice.id), paymentData);
            
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
    .invoice, .invoice-related {
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
