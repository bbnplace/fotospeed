<template>
    <div class="d-flex flex-row-reverse my-3" v-if="props.endpoint.add">
        <Link :href="route(props.endpoint.add)" class="btn btn-primary">Add {{ props.name.singular }}</Link>
    </div>
    <div class="d-flex mb-6t">
        <v-sheet class="ma-2 pa-2 d-none d-sm-flex">Filter {{ props.name.singular }}</v-sheet>
            <v-text-field
                v-model="search"
                append-inner-icon="mdi-magnify"
                hide-details
                :placeholder="`Filter ${props.name.singular}`"
                type="text"
                class="ma-2"
                density="compact"
                variant="outlined"
            ></v-text-field>
    </div>
    <div class="flex gap-5 ml-5" v-if="selected.value && selected.value.length > 0">
        <v-icon  v-if="props.endpoint.delete"
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
                :headers="props.headers"
                item-value="id"
                @update:options="loadRecords"
                :show-select="props.endpoint.delete">
                <template v-slot:item.actions="{ item }">
                        <v-icon v-if="props.endpoint.detail"
                            size="small"
                            class="me-2"
                            @click="viewDetail(item)"
                        >
                            mdi-eye
                        </v-icon>
                        <v-icon v-if="props.endpoint.edit"
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
import { Link, router } from "@inertiajs/vue3";
import Dialog from '@/Components/Dialog.vue';
import Snackbar from '@/Components/Snackbar.vue';
import { snackbarOption, showSnackbar } from '@/Composables/snackbarOptions.js';

// Load record data from props
const recordProps = defineProps({
    data: Object
});
const props = recordProps.data;



// Records List
const selected = ref([]);
const itemsPerPage = ref(25);
const totalRecords = ref(0);
const loadedRecords = ref([]);
let loading = ref(false);
const search = ref("");
const pageNo = ref(1);

// const actions = {
//     edit: true,
//     delete: true,
//     view: true
// }

// if(props.actions != undefined) {
//     if (props.actions.edit != undefined) {
//         actions.edit = props.actions.edit
//     }

//     if (props.actions.view != undefined) {
//         actions.view = props.actions.view
//     }

//     if (props.actions.delete != undefined) {
//         actions.delete = props.actions.delete
//     }
// }

// Function for loading and filtering records from datasource
let source = null;
const loadRecords = async ({page, itemsPerPage, sortBy}) => {
    const payload = {page, itemsPerPage, sortBy, search}
    loading = true;
    pageNo.value = page;
    if(source) source.cancel('Request cancelled by user');
    source = axios.CancelToken.source();
    const response = await axios.post(props.endpoint.records, payload, {
        headers: {
            "Content-Type": "application/json"
        },
        cancelToken: source.token
    });
    loadedRecords.value = response.data.records;
    totalRecords.value = response.data.totalRecords
    loading = false;
}

// Link to the Edit Item View
const editItem = item => {
    router.get(route(props.endpoint.edit, item.id))
}

// Linking to the details view
const viewDetail = item => {
    router.get(route(props.endpoint.detail, item.id));
}

// Deleting selected Items
const deleteRecords = (items) => {
    router.delete(route(props.endpoint.delete), {
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
            showSnackbar(`Selected ${props.name.plural} have been deleted`);

            selected.value = [];
        }
    })
}



// Dialog
const dialog = ref(false);
const deleteDialog = {
    title: "Confirm Delete",
    body: `Are you sure you want to delete the selected ${props.name.plural.toLowerCase()}?`
}

const closeDialog = () => {
    dialog.value = false
}

const showDialog = () => {
    dialog.value = true;
}
</script>
