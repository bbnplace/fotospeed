<template>
    <Head title="My Invoice"></Head>
    <BackendLayout>
        <VRow>
            <VCol>
                <Link href="#" class="font-bold" @click="goBack">Back</Link>
            </VCol>
        </VRow>
        <Panel snippet-title="Invoice Successfully Generated">
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
            <VRow v-if="invoice.invoice_status.name == 'Unpaid'">
                <VCol class="text-right">
                    <Paystack :data="paystackData" @paymentCompleted="handlePaymentCompletion" />
                </VCol>
            </VRow>
        </Panel>
    </BackendLayout>
</template>

<script setup>
    import { usePage, Head, router, Link } from "@inertiajs/vue3";
    import BackendLayout from "@/Layouts/BackendLayout.vue";
    import Panel from '@/Layouts/Shared/Panel.vue'
    import Paystack from '@/Components/Paystack.vue';
    import moment from 'moment';

    const user = usePage().props.auth.user;
    const invoice = usePage().props.invoice;
    const paystackData = usePage().props.paystack;

    const handlePaymentCompletion = data => {
        // console.log(data)
        // alert(`${data.reference}, ${data.status}`)
        setTimeout(()=>{
            router.visit(route('customer.receipt', [invoice.id]))
        }, 1000);
    }

// Other data
    const company = usePage().props.company;

    const client = {
        name: invoice.user.name,
        address: invoice.order.delivery_address,
        email: invoice.user.email
    };

    const invoiceNumber = invoice.id;
    const invoiceDate = moment(invoice.created_at).format('LL');

    const formatter = new Intl.NumberFormat('en-US', {
        style: 'decimal',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    });

    const goBack = () => {
        window.history.back()
    }
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
