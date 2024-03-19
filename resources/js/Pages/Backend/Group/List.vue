<template>
    <Head title="Groups"></Head>
    <BackendLayout>
        <div class="d-flex flex-row-reverse my-3">
            <Link :href="route('group.add')" class="btn btn-primary">Create Group</Link>
        </div>
        <div class="d-flex mb-6t">
            <v-sheet class="ma-2 pa-2 d-none d-sm-flex">Filter Group </v-sheet>
                <v-text-field
                    v-model="search"
                    append-inner-icon="mdi-magnify"
                    hide-details
                    placeholder="Filter Group"
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
                                @click="editItem(item)"
                            >
                                mdi-pencil
                            </v-icon>
                        </template>
                </VDataTableServer>
            </VCol>
        </VRow>
    </BackendLayout>
</template>

<script setup>
import { ref } from 'vue';
import { usePage, Head, Link, router } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";

const selected = ref([]);
const itemsPerPage = ref(25);
const totalRecords = ref(0);
const loadedRecords = ref([]);
let loading = ref(false);
const search = ref("");

const headers = [
    {
        title: "Group",
        key: "name",
        sortable: true
    },
    {
        title: "Description",
        key: "description",
        sortable: false
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
    router.get(route('group.edit', item.id))
}

const viewDetail = item => {
    router.get(route('group.detail', item.id));
}

// Deleting selected contacts
const deleteRecords = item => {
    const form = reactive({
        contacts: item
    });

    dialog.value = false; confirmDelete.value = false;
    router.post(route('group.delete'), form);
}

</script>

<style lang="scss" scoped>

</style>
