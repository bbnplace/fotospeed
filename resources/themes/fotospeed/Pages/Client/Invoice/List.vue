<template>
    <DefaultLayout>
        <div class="container my-5">
            <Records :data="dataResources">
                <template #item.order.total_cost="{ item }">
                    {{ formatter.format(item.order.total_cost) }}
                </template>
                <template #item.invoice_status.name="{ item }">
                    <v-chip
                        :color="getStatusColor(item.invoice_status?.name)"
                        variant="outlined"
                        size="small"
                        class="text-uppercase font-weight-bold"
                    >
                        {{ item.invoice_status?.name || 'Unknown' }}
                    </v-chip>
                </template>

                <template #mobile-item="{ item }">
                    <v-card 
                        class="mb-3 rounded-lg mx-1" 
                        elevation="3"
                        @click="router.get(route(dataResources.endpoint.detail, item.id))"
                    >
                        <div class="d-flex flex-row fill-height">
                            <!-- Status Color Stripe -->
                            <v-sheet
                                width="6"
                                class="fill-height rounded-s-lg"
                                :color="getStatusColor(item.invoice_status?.name)"
                            ></v-sheet>
                            
                            <!-- Card Content -->
                            <div class="flex-grow-1 py-3 pl-3 pr-2">
                                <div class="d-flex justify-space-between align-start mb-1">
                                    <div class="d-flex flex-column" style="max-width: 70%;">
                                        <div class="text-caption text-medium-emphasis text-uppercase font-weight-bold letter-spacing-1">
                                            Invoice #{{ item.invoice_number || item.id }}
                                        </div>
                                        <div class="text-subtitle-1 font-weight-bold text-truncate mt-n1">
                                            {{ item.order?.name || 'N/A' }}
                                        </div>
                                    </div>
                                    <v-chip
                                        :color="getStatusColor(item.invoice_status?.name)"
                                        size="x-small"
                                        variant="tonal"
                                        class="font-weight-bold text-uppercase mt-1"
                                    >
                                        {{ item.invoice_status?.name }}
                                    </v-chip>
                                </div>

                                <v-divider class="my-2 border-opacity-15"></v-divider>

                                <div class="d-flex justify-space-between align-end mt-2">
                                    <div>
                                        <div class="text-caption text-medium-emphasis mb-1 d-flex align-center">
                                            <v-icon icon="mdi-calendar-blank" size="x-small" class="mr-1"></v-icon>
                                            Issued
                                        </div>
                                        <div class="text-body-2 font-weight-medium">
                                            {{ moment(item.created_at).format('MMM D, YYYY') }}
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-caption text-medium-emphasis mb-1">Total</div>
                                        <div class="text-h6 font-weight-black text-primary lh-1">
                                            {{ new Intl.NumberFormat('en-NG', { style: 'currency', currency: 'NGN' }).format(item.order?.total_cost || 0) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </v-card>
                </template>
            </Records>
        </div>
    </DefaultLayout>
</template>

<script setup>
import DefaultLayout from '../../../Layouts/DefaultLayout.vue';
import { usePage, Head, router } from "@inertiajs/vue3";
import Records from  '@/Components/Records.vue';
import moment from 'moment';

const invoiceRefSrc = usePage().props.invoice_no_src;
const formatter = new Intl.NumberFormat('en-NG', {
    style: 'currency',
    currency: 'NGN',
    minimumFractionDigits: 2
});

const getStatusColor = (status) => {
    if (!status) return 'grey';
    switch (status.toLowerCase()) {
        case 'paid': return 'success';
        case 'unpaid': return 'error';
        case 'partially paid': return 'warning';
        case 'pending verification': return 'info';
        case 'pending': return 'warning';
        case 'cancelled': return 'grey';
        default: return 'primary';
    }
};

const dataResources = {
    endpoint: {
        records: usePage().props.endpoint,
        detail: "customer.invoice",
        // delete: "client.orders.delete"
    },
    filters: [
        {
            key: "invoice_number",
            type: "text",
            label: "Invoice #"
        },
        {
            key: "order_name",
            type: "text",
            label: "Order Name"
        },
        {
            key: "min_amount",
            type: "text",
            label: "Min Amount"
        },
        {
            key: "max_amount",
            type: "text",
            label: "Max Amount"
        },
        {
            key: "start_date",
            type: "date",
            label: "Start Date"
        },
        {
            key: "end_date",
            type: "date",
            label: "End Date"
        },
        {
            key: "status",
            type: "select",
            label: "Status",
            options: usePage().props.invoice_statuses
        }
    ],
    sortBy: [{ key: 'created_at', order: 'desc' }],
    headers: [
        {
            title: "Invoice #",
            key: invoiceRefSrc == "System Generated" ? "id" : "order.order_number",
            sortable: true
        },
        {
            title: "Order Name",
            key: "order.name",
            sortable: true
        },
        {
            title: "Amount",
            key: "order.total_cost",
            sortable: false,
            align: 'end'
        },
        {
            title: "Status",
            key: "invoice_status.name",
            sortable: true,
            align: 'center'
        },
        {
            title: "Issued",
            key: "created_at",
            sortable: true,
            align: 'end'
        },
    ],
    name: {
        singular: "Invoice",
        plural: "Invoices"
    }
}
</script>

