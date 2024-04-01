<template>
    <Head title="Customers"></Head>
    <BackendLayout>

        <div class="d-flex flex-row-reverse my-4">
            <Link :href="route('customer.add')" class="btn btn-primary">Register Customer</Link>
        </div>

        <div class="d-flex mb-6t">
            <v-sheet class="ma-2 pa-2 d-none d-sm-flex">Filter Customer</v-sheet>
                <v-text-field
                    v-model="search"
                    append-inner-icon="mdi-magnify"
                    hide-details
                    placeholder="Filter Customer"
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
                @click="showDialog"
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
                                @click="editItem(item)"
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
        @deleteConfirmed="deleteRecords(selected.value)"
        @deleteCancelled="closeDialog"
    ></Dialog>
    <Snackbar :data="snackbarOption"></Snackbar>
</template>

<script setup>
import { ref } from 'vue';
import { usePage, Head, Link, router } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Dialog from '@/Components/Dialog.vue';
import Snackbar from '@/Components/Snackbar.vue';
import { snackbarOption, showSnackbar } from '@/Composables/snackbarOptions.js';

const selected = ref([]);
const itemsPerPage = ref(25);
const totalRecords = ref(0);
const loadedRecords = ref([]);
let loading = ref(false);
const search = ref("");
const dialog = ref(false);

const headers = [
    {
        title: "Name",
        key: "name",
        sortable: true
    },
    {
        title: "Role",
        key: "role.name",
        sortable: true
    },
    {
        title: "Mobile",
        key: "mobile",
        sortable: true
    },
    {
        title: "Email",
        key: "email",
        sortable: true
    }, {
        title: "State",
        key: "state.name",
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

const editItem = item => {
    router.get(route('customer.edit', item.id))
}

const viewDetail = item => {
    router.get(route('customer.view', item.id));
}

// Deleting selected contacts
const deleteRecords = items => {
    router.delete(route('customers.delete'), {
        data: {
            ids: items
        },
        onFinish: (d)=>{
            closeDialog();
            const obj = {
                page: pageNo.value,
                itemsPerPage: itemsPerPage.value,
                sortBy: []
            }
            loadRecords(obj);
            showSnackbar("Selected customers have been deleted");
            selected.value = [];
        }
    })
}

const deleteDialog = {
    title: "Confirm Delete",
    body: "Are you sure you want to delete the selected customers?"
}

const closeDialog = () => {
    dialog.value = false
}

const showDialog = () => {
    dialog.value = true;
}

</script>

