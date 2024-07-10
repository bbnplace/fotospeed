<template>
    <Head title="My Orders"></Head>
    <ClientLayout>
        <Records :data="dataResources"></Records>
    </ClientLayout>
</template>

<script setup>
import { usePage, Head } from "@inertiajs/vue3";
import ClientLayout from "@/Layouts/ClientLayout.vue";
import Records from  '@/Components/Records.vue';

const dataResources = {
    endpoint: {
        records: usePage().props.endpoint,
        add: "customer.new-order",
        edit: "client.order.edit",
        // delete: "client.orders.delete",
        detail: "client.order.view"
    },
    headers: [
        {
            title: "Order Number",
            key: "order_number",
            sortable: true
        },
        {
            title: "Items",
            key: "item.name",
            sortable: true
        },
        {
            title: "Total Cost",
            key: "total_cost",
            sortable: true
        },
        {
            title: "Status",
            key: "order_status.name",
            sortable: false
        },
        {
            title: "Actions",
            key: "actions",
            sortable: false,
            width: '100px'
        },
    ],
    name: {
        singular: "Order",
        plural: "Orders"
    }
}

// const replyMessage = item => {
//     // router.get(route('customer.edit', item.id))
// }

// const viewDetail = item => {
//     router.get(route('order.detail', item.id));
// }

// // Deleting selected contacts
// const deleteRecords = items => {
//     router.delete(route('orders.delete'), {
//         data: {
//             ids: items
//         },
//         onFinish: (d)=>{
//             closeDialog();
//             const obj = {
//                 page: pageNo.value,
//                 itemsPerPage: itemsPerPage.value,
//                 sortBy: []
//             }
//             loadRecords(obj);
//             showSnackbar("Selected orders have been deleted");
//             selected.value = [];
//         }
//     })
// }


</script>

