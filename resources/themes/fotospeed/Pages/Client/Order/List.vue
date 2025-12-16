<template>
    <Head title="My Orders"></Head>
    <DefaultLayout>
        <div class="container my-5">
            <Records :data="dataResources">
                <template #item.total_cost="{ item }">
                    {{ formatter.format(item.total_cost) }}
                </template>
                <template #item.order_status.name="{ item }">
                    <v-chip
                        :color="getStatusColor(item.order_status?.name)"
                        variant="outlined"
                        size="small"
                        class="text-uppercase font-weight-bold"
                    >
                        {{ item.order_status?.name || 'Unknown' }}
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
                                :color="getStatusColor(item.order_status?.name)"
                            ></v-sheet>
                            
                            <!-- Card Content -->
                            <div class="flex-grow-1 py-3 pl-3 pr-2">
                                <div class="d-flex justify-space-between align-start mb-1">
                                    <div class="d-flex flex-column" style="max-width: 70%;">
                                        <div class="text-caption text-medium-emphasis text-uppercase font-weight-bold letter-spacing-1">
                                            Order #{{ item.order_number || item.id }}
                                        </div>
                                        <div class="text-subtitle-1 font-weight-bold text-truncate mt-n1">
                                            {{ item.item?.name || 'N/A' }}
                                        </div>
                                    </div>
                                    <v-chip
                                        :color="getStatusColor(item.order_status?.name)"
                                        size="x-small"
                                        variant="tonal"
                                        class="font-weight-bold text-uppercase mt-1"
                                    >
                                        {{ item.order_status?.name }}
                                    </v-chip>
                                </div>

                                <v-divider class="my-2 border-opacity-15"></v-divider>

                                <div class="d-flex justify-space-between align-end mt-2">
                                    <div>
                                        <div class="text-caption text-medium-emphasis mb-1 d-flex align-center">
                                            <v-icon icon="mdi-calendar-blank" size="x-small" class="mr-1"></v-icon>
                                            Ordered
                                        </div>
                                        <div class="text-body-2 font-weight-medium">
                                            {{ moment(item.created_at).format('MMM D, YYYY') }}
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-caption text-medium-emphasis mb-1">Total</div>
                                        <div class="text-h6 font-weight-black text-primary lh-1">
                                            {{ new Intl.NumberFormat('en-NG', { style: 'currency', currency: 'NGN' }).format(item.total_cost || 0) }}
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
import { usePage, Head, router } from "@inertiajs/vue3";
import DefaultLayout from "../../../Layouts/DefaultLayout.vue";
import Records from  '@/Components/Records.vue';
import moment from 'moment';

const formatter = new Intl.NumberFormat('en-NG', {
    style: 'currency',
    currency: 'NGN',
    minimumFractionDigits: 2
});

const getStatusColor = (status) => {
    if (!status) return 'grey';
    switch (status.toLowerCase()) {
        case 'completed': return 'success';
        case 'pending': return 'warning';
        case 'in progress': return 'info';
        case 'cancelled': return 'error';
        case 'on hold': return 'grey';
        default: return 'primary';
    }
};

const dataResources = {
    endpoint: {
        records: usePage().props.endpoint,
        add: "customer.new-order",
        edit: "client.order.edit",
        // delete: "client.orders.delete",
        detail: "client.order.view"
    },
    addLabel: "New Order",
    filters: [
        {
            key: "order_number",
            type: "text",
            label: "Order #"
        },
        {
            key: "item_name",
            type: "text",
            label: "Item Name"
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
            options: usePage().props.order_statuses
        }
    ],
    sortBy: [{ key: 'created_at', order: 'desc' }],
    collapseFilters: true,
    headers: [
        {
            title: "Order#",
            key: "order_number",
            sortable: true
        },
        {
            title: "Item",
            key: "item.name",
            sortable: true
        },
        {
            title: "Amount",
            key: "total_cost",
            sortable: false,
            align: 'end'
        },
        {
            title: "Status",
            key: "order_status.name",
            sortable: true,
            align: 'center'
        },
        {
            title: "Ordered",
            key: "created_at",
            sortable: true,
            align: 'end'
        },
    ],
    name: {
        singular: "Order",
        plural: "Orders"
    }
}

</script>

