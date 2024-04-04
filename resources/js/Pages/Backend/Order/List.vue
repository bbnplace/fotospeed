<template>
    <Head title="Inbox"></Head>
    <BackendLayout>
        <Records :data="dataResources"></Records>
    </BackendLayout>
</template>

<script setup>
import { usePage, Head } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Records from  '@/Components/Records.vue';

const dataResources = {
    endpoint: {
        records: usePage().props.endpoint,
        add: "order.add",
        edit: "order.edit",
        delete: "orders.delete",
        detail: "order.view"
    },
    headers: [
        {
            title: "Customer",
            key: "user.name",
            sortable: true
        },
        {
            title: "Items",
            key: "order.item.name",
            sortable: true
        },
        {
            title: "Total Cost",
            key: "total_cost",
            sortable: true
        },
        {
            title: "Status",
            key: "orderStatus.name",
            sortable: true
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

