<template>
    <Head title="Inbox"></Head>
    <BackendLayout>

        <div class="d-flex mb-6t">
            <v-sheet class="ma-2 pa-2 d-none d-sm-flex">Filter Messages</v-sheet>
                <v-text-field
                    v-model="search"
                    append-inner-icon="mdi-magnify"
                    hide-details
                    placeholder="Filter Messages"
                    type="text"
                    class="ma-2"
                    density="compact"
                    variant="outlined"
                ></v-text-field>

        </div>
        <div class="flex gap-5 ml-5" v-if="selected.value && selected.value.length > 0">
            <v-icon
                size="small"
                title="Delete"
                @click="()=>{confirmDelete = true; dialog = true;}"
            >
                mdi-delete
            </v-icon>
        </div>
        <VRow>
            <VCol>
                <VDataTableServer
                    v-model="selected.value"
                    :items="loadedRecords"
                    :loading="loading"
                    :items-length="totalRecords"
                    v-model:items-per-page="itemsPerPage"
                    :search="search"
                    :headers="headers"
                    item-value="id"
                    @update:options="loadRecords"
                    show-select>
                    <template v-slot:item.actions="{ item }">
                            <v-icon
                                size="small"
                                class="me-2"
                                @click="viewDetail(item)"
                            >
                                mdi-eye
                            </v-icon>
                            <v-icon
                                size="small"
                                class="me-2"
                                @click="replyMessage(item)"
                            >
                                mdi-pencil
                            </v-icon>
                        </template>
                </VDataTableServer>
            </VCol>
        </VRow>
    </BackendLayout>
    <Dialog
        :dialogData="deleteDialog"
        :show="dialog"
        @deleteConfirmed="deleteRecords"
        @deleteCancelled="closeDialog"
    ></Dialog>
</template>

<script setup>
import { ref } from 'vue';
import { usePage, Head, Link, router } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Dialog from '@/Components/Dialog.vue';

const selected = ref([]);
const itemsPerPage = ref(25);
const totalRecords = ref(0);
const loadedRecords = ref([]);
let loading = ref(false);
const search = ref("");
const dialog = ref(false);

const headers = [
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
];

let source = null;
const loadRecords = async ({page, itemsPerPage, sortBy}) => {
    const payload = {page, itemsPerPage, sortBy, search}
    loading = true;
    if(source) source.cancel('Request cancelled by user');
    source = axios.CancelToken.source();
    const response = await axios.post(usePage().props.endpoint, payload, {
        headers: {
            "Content-Type": "application/json"
        },
        cancelToken: source.token
    });
    loadedRecords.value = response.data.records;
    totalRecords.value = response.data.totalRecords
    loading = false;
}

const replyMessage = item => {
    // router.get(route('customer.edit', item.id))
}

const viewDetail = item => {
    router.get(route('order.detail', item.id));
}

// Deleting selected contacts
const deleteRecords = item => {
    const form = reactive({
        contacts: item
    });

    dialog.value = false; confirmDelete.value = false;
    router.post(route('order.delete'), form);
}

const deleteDialog = {
    title: "Confirm Delete",
    body: "Are you sure you want to delete the selected orders?"
}

const closeDialog = () => {
    console.log("Closing dialog")
    dialog.value = false
}

const showDialog = () => {
    dialog.value = true;
}
</script>

