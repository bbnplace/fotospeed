<template>
    <div class="d-flex flex-row-reverse mb-2" v-if="props.endpoint.add">
        <Link :href="route(props.endpoint.add)" class="btn btn-primary fw-bold">{{ props.addLabel || `Add ${props.name.singular}` }}</Link>
    </div>

    <div class="mb-2">
        <v-alert
            v-if="errorMessage"
            type="error"
            variant="tonal"
            :text="errorMessage"
            closable
        ></v-alert>
    </div>
    <div class="d-flex mb-2 bg-white rounded shadow flex-wrap">
        <v-sheet class="ma-2 pa-2 d-none d-sm-flex align-center">Filter {{ props.name.singular }}</v-sheet>
        
        <template v-if="props.filters">
            <div v-for="(filter, index) in props.filters" :key="index" class="ma-2" style="min-width: 150px;">
                <v-text-field
                    v-if="filter.type === 'text'"
                    v-model="search[filter.key]"
                    :label="filter.label"
                    hide-details
                    density="compact"
                    variant="outlined"
                    clearable
                ></v-text-field>
                <v-select
                    v-if="filter.type === 'select'"
                    v-model="search[filter.key]"
                    :label="filter.label"
                    :items="filter.options"
                    item-title="name"
                    item-value="name"
                    hide-details
                    density="compact"
                    variant="outlined"
                    clearable
                ></v-select>
            </div>
        </template>
        <template v-else>
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
        </template>
    </div>
    <div class="flex flex-row ml-5" v-if="selected.value && selected.value.length > 0">
        <v-icon  v-if="props.endpoint.delete"
            size="large"
            title="Delete"
            class="my-1 mx-0"
            @click="showDialog"
        >
        mdi-delete-circle
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
                :search="typeof search === 'string' ? search : undefined"
                :headers="props.headers"
                item-value="id"
                @click:row="rowClicked"
                @update:options="loadRecords"
                :show-select="!!props.endpoint.delete"
                :item-selectable="(item) => !item.protected">
                <template v-slot:item.actions="{ item }">
                        <!-- <v-icon v-if="props.endpoint.detail"
                            size="small"
                            class="me-2"
                            @click="viewDetail(item)"
                        >
                            mdi-eye
                        </v-icon> -->
                        <!-- <v-icon v-if="props.endpoint.edit"
                            size="small"
                            class="me-2"
                            @click="editItem(item)"
                        >
                            mdi-pencil
                        </v-icon> -->
                    </template>
                    <template v-slot:item.created_at="{ item }">
                        {{ moment(item.created_at).calendar() }}
                    </template>
                    <template v-slot:item.updated_at="{ item }">
                        {{ moment(item.updated_at).calendar() }}
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
import { ref, watch } from 'vue';
import { Link, router } from "@inertiajs/vue3";
import Dialog from '@/Components/Dialog.vue';
import Snackbar from '@/Components/Snackbar.vue';
import { snackbarOption, showSnackbar } from '@/Composables/snackbarOptions.js';
import moment from 'moment';

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

// Initialize search as object if filters exist, else string
const search = ref(props.filters ? {} : "");

const pageNo = ref(1);
const errorMessage = ref("");

// Store current options for reloading
const currentOptions = ref({
    page: 1,
    itemsPerPage: 25,
    sortBy: []
});

// Function for loading and filtering records from datasource
let source = null;
const loadRecords = async ({page, itemsPerPage, sortBy}) => {
    // Update current options
    currentOptions.value = { page, itemsPerPage, sortBy };

    const payload = {page, itemsPerPage, sortBy, search: search.value}
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
    errorMessage.value = response.data.error ? response.data.error.message : "";
    loading = false;
}

// Watch search for changes (deep watch for object)
watch(search, () => {
    loadRecords(currentOptions.value);
}, { deep: true });


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

const rowClicked = (table, row) => {
    viewDetail(row.item);
};
</script>
