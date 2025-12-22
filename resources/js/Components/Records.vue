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
    <v-card class="mb-4" variant="flat" border>
        <v-card-text class="pa-2">
            <v-row dense align="center">
                <v-col 
                    cols="12" 
                    md="auto" 
                    class="d-flex align-center py-2 px-4 bg-light rounded cursor-pointer"
                    @click="showFilters = !showFilters"
                >
                    <v-icon start icon="mdi-filter-variant" size="small"></v-icon>
                    <span class="text-subtitle-2 font-weight-bold mr-2">Filter {{ props.name.plural }}</span>
                    <v-icon :icon="showFilters ? 'mdi-chevron-up' : 'mdi-chevron-down'" size="small"></v-icon>
                </v-col>
                
                <template v-if="showFilters">
                    <template v-if="props.filters">
                        <v-col v-for="(filter, index) in props.filters" :key="index" cols="12" sm="6" md="4" lg="3">
                            <v-text-field
                                v-if="filter.type === 'text'"
                                v-model="search[filter.key]"
                                :label="filter.label"
                                hide-details
                                density="compact"
                                variant="outlined"
                                clearable
                                bg-color="white"
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
                                bg-color="white"
                            ></v-select>
                            <v-text-field
                                v-if="filter.type === 'date'"
                                v-model="search[filter.key]"
                                :label="filter.label"
                                hide-details
                                density="compact"
                                variant="outlined"
                                type="date"
                                clearable
                                bg-color="white"
                            ></v-text-field>
                        </v-col>
                    </template>
                    <template v-else>
                        <v-col cols="12" sm>
                            <v-text-field
                                v-model="search"
                                append-inner-icon="mdi-magnify"
                                hide-details
                                :placeholder="`Filter ${props.name.singular}`"
                                type="text"
                                density="compact"
                                variant="outlined"
                                bg-color="white"
                            ></v-text-field>
                        </v-col>
                    </template>
                </template>
            </v-row>
        </v-card-text>
    </v-card>
    <div class="flex flex-row ml-5" v-if="selected && selected.length > 0">
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
            <!-- Desktop Table View -->
            <VDataTableServer
                v-if="!mobile"
                v-model="selected"
                :items="loadedRecords"
                :loading="loading"
                :items-length="totalRecords"
                v-model:items-per-page="itemsPerPage"
                :search="typeof search === 'string' ? search : undefined"
                :headers="props.headers"
                :sort-by="props.sortBy"
                item-value="id"
                @click:row="rowClicked"
                @update:options="loadRecords"
                :show-select="!!props.endpoint.delete"
                :item-selectable="(item) => !item.protected">
                
                <template v-for="(_, name) in $slots" v-slot:[name]="slotData">
                    <slot :name="name" v-bind="slotData" />
                </template>

                <template v-slot:item.actions="{ item }">
                        <!-- Actions slot content -->
                </template>
                <template v-slot:item.created_at="{ item }">
                    {{ moment(item.created_at).calendar() }}
                </template>
                <template v-slot:item.updated_at="{ item }">
                    {{ moment(item.updated_at).calendar() }}
                </template>
            </VDataTableServer>

            <!-- Mobile Card View -->
            <v-data-iterator
                v-else
                :items="loadedRecords"
                :items-per-page="itemsPerPage"
                :page="pageNo"
            >
                <template v-slot:default="{ items }">
                    <v-row>
                        <v-col v-for="item in items" :key="item.raw.id" cols="12">
                            <slot name="mobile-item" :item="item.raw">
                                <v-card class="mb-3 rounded-lg" @click="viewDetail(item.raw)" variant="elevated" elevation="1">
                                    <v-card-text class="pa-0">
                                        <div 
                                            v-for="(header, index) in props.headers" 
                                            :key="index" 
                                            class="d-flex justify-space-between align-center py-3 px-4"
                                            :class="{ 'border-b': index !== props.headers.length - 1 }"
                                        >
                                            <div class="text-caption text-medium-emphasis font-weight-bold text-uppercase">{{ header.title }}</div>
                                            <div class="text-body-2 font-weight-medium text-right ml-4">
                                                <!-- Check if specialized slot exists for this header key -->
                                                <slot 
                                                    v-if="$slots[`item.${header.key}`]" 
                                                    :name="`item.${header.key}`" 
                                                    :item="item.raw" 
                                                />
                                                <!-- Fallback formatting for dates -->
                                                <span v-else-if="header.key === 'created_at' || header.key === 'updated_at'">
                                                    {{ moment(item.raw[header.key]).calendar() }}
                                                </span>
                                                <!-- Default text display (handling nested keys) -->
                                                <span v-else>
                                                    {{ header.key.split('.').reduce((o, i) => o?.[i], item.raw) }}
                                                </span>
                                            </div>
                                        </div>
                                    </v-card-text>
                                </v-card>
                            </slot>
                        </v-col>
                    </v-row>
                </template>
                
                <!-- Use same pagination as table -->
                <template v-slot:footer>
                     <v-pagination
                        v-model="pageNo"
                        :length="Math.ceil(totalRecords / itemsPerPage)"
                        rounded="circle"
                        class="mt-4"
                        @update:model-value="(val) => loadRecords({ page: val, itemsPerPage, sortBy: currentOptions.sortBy })"
                    ></v-pagination>
                </template>
            </v-data-iterator>
        </VCol>
    </VRow>
    <Dialog
        :dialogData="deleteDialog"
        :show="dialog"
        @deleteConfirmed="deleteRecords(selected)"
        @deleteCancelled="closeDialog"
    ></Dialog>
    <Snackbar :data="snackbarOption"></Snackbar>
</template>

<script setup>
import { ref, watch, onMounted, onUnmounted } from 'vue';
import { Link, router } from "@inertiajs/vue3";
import Dialog from '@/Components/Dialog.vue';
import Snackbar from '@/Components/Snackbar.vue';
import { snackbarOption, showSnackbar } from '@/Composables/snackbarOptions.js';
import moment from 'moment';

const mobile = ref(false);

let mql = null;

const updateMobile = (e) => {
    mobile.value = e.matches;
};

onMounted(() => {
    mql = window.matchMedia('(max-width: 512px)');
    mobile.value = mql.matches;
    mql.addEventListener('change', updateMobile);
    
    if (mobile.value) {
        loadRecords(currentOptions.value);
    }
});

onUnmounted(() => {
    if (mql) mql.removeEventListener('change', updateMobile);
});

// Load record data from props
const recordProps = defineProps({
    data: Object
});
const props = recordProps.data;

const showFilters = ref(!props.collapseFilters);



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
    sortBy: props.sortBy || []
});

// Function for loading and filtering records from datasource
let source = null;
const loadRecords = async ({page, itemsPerPage, sortBy}) => {
    // Update current options
    currentOptions.value = { page, itemsPerPage, sortBy };

    // Clean search object - remove empty, null, or undefined values
    let cleanedSearch = search.value;
    if (typeof search.value === 'object' && search.value !== null) {
        cleanedSearch = Object.fromEntries(
            Object.entries(search.value).filter(([_, v]) => v != null && v !== '')
        );
        // If no valid filters remain, set to empty object
        if (Object.keys(cleanedSearch).length === 0) {
            cleanedSearch = null;
        }
    }

    const payload = {page, itemsPerPage, sortBy, search: cleanedSearch}
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
const deleteDialog = ref({
    title: "Confirm Delete",
    body: `Are you sure you want to delete the selected ${props.name.plural.toLowerCase()}?`
})

const closeDialog = () => {
    dialog.value = false
}

const showDialog = () => {
    const selectedItemsWithProducts = loadedRecords.value.filter(record => 
        selected.value.includes(record.id) && (record.items_count > 0 || record.products_count > 0)
    );

    if (selectedItemsWithProducts.length > 0) {
        deleteDialog.value.body = `One or more selected ${props.name.plural.toLowerCase()} contain products. Proceeding with this action will result in the products getting deleted, along with their setup production workflows. Are you sure you want to proceed?`;
        deleteDialog.value.puzzle = 'DELETE';
        deleteDialog.value.confirmLabel = 'Proceed';
        deleteDialog.value.confirmColor = 'error';
    } else {
        deleteDialog.value.body = `Are you sure you want to delete the selected ${props.name.plural.toLowerCase()}?`;
        deleteDialog.value.puzzle = null;
        deleteDialog.value.confirmLabel = 'Yes';
        deleteDialog.value.confirmColor = 'primary';
    }
    dialog.value = true;
}

const rowClicked = (table, row) => {
    viewDetail(row.item);
};
</script>
