<template>
    <Head title="My Invoice"></Head>
    <ClientLayout>

        <div class="invoice mt-4">
            <h1>Invoice</h1>
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
                        <p>Indigo Africa</p>
                        <p>Ikeja, Lagos</p>
                        <p>support@indigoafrica.net</p>
                    </address>
                </VCol>
                <VCol>
                    <h3>To:</h3>
                    <div class="address">
                        <p>{{ invoice.user.name }}</p>
                        <p>{{ invoice.user.address }}</p>
                        <p>{{ invoice.user.email }}</p>
                    </div>
                </VCol>
            </VRow>
            <table class="invoice-table">
            <thead>
                <tr>
                <th>Order Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                <td>{{ invoice.order.name }}</td>
                <td>1</td>
                <td>{{ invoice.order.total_cost }}</td>
                <td>{{ invoice.order.total_cost }}</td>
                </tr>
            </tbody>
            </table>
            <VRow>
                <VCol class="text-right">
                    <h3>Total: {{ invoice.order.total_cost }} [{{ invoice.invoice_status.name }}]</h3>
                </VCol>
            </VRow>
            <VRow>
                <VCol>
                    <h3>Payment Method</h3>
                    <p>Bank Transfer</p>
                    <p>
                        Bank Name<br />
                        0000000000<br />
                        Indigo Africa<br />
                    </p>
                </VCol>
            </VRow>
        </div>
    </ClientLayout>
</template>

<script setup>
    import { usePage, Head } from "@inertiajs/vue3";
    import ClientLayout from "@/Layouts/ClientLayout.vue";
    import { ref, computed } from 'vue';

    const invoice = usePage().props.invoice;

    const company = ref({
    name: 'Your Company',
    address: '123 Main St, City, Country',
    email: 'company@example.com'
    });

    const client = ref({
    name: 'Client Name',
    address: '456 Elm St, City, Country',
    email: 'client@example.com'
    });

    const invoiceNumber = ref('0001');
    const invoiceDate = ref(new Date().toLocaleDateString());
    const items = ref([
    { description: 'Service 1', quantity: 1, price: 100 },
    { description: 'Service 2', quantity: 2, price: 150 },
    // Add more items as needed
    ]);

    const invoiceTotal = computed(() =>
    items.value.reduce((total, item) => total + item.quantity * item.price, 0)
    );
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
  margin: 0;
  line-height: 1.5;
}
</style>
