<template>
    <Head title="My Orders"></Head>
    <DefaultLayout>
        <section class="cart-section section-padding">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="section-title">My Orders</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="cart-wrapper">
                            <div class="cart-items-wrapper">
                                <Panel snippetTitle="New Order">
                                    <Records :data="dataResources"></Records>
                                </Panel>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </DefaultLayout>
</template>

<script setup>
import { usePage, Head } from "@inertiajs/vue3";
import DefaultLayout from "../../../Layouts/DefaultLayout.vue";
import Records from  '@/Components/Records.vue';

const dataResources = {
    endpoint: {
        records: usePage().props.endpoint,
        add: "customer.new-order",
        edit: "client.order.edit",
        // delete: "client.orders.delete",
        detail: "client.order.view"
    },
    addLabel: "New Order",
    headers: [
        {
            title: "Order#",
            key: "order_number",
            sortable: true
        },
        {
            title: "Items",
            key: "item.name",
            sortable: true
        },
        {
            title: "Status",
            key: "order_status.name",
            sortable: false
        },
        {
            title: "Date",
            key: "created_at",
            sortable: false,
            align: 'end'
        },
    ],
    name: {
        singular: "Order",
        plural: "Orders"
    }
}

</script>

