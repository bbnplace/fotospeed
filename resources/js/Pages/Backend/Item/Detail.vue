<template>
    <Head title="Product"></Head>
    <BackendLayout>
        <Link :href="route('items')" class="font-bold">Back</Link>
        <VRow>
            <VCol cols="12" md="6">
                <Panel snippet-title="Product">
                    <VRow>
                        <VCol cols="6"><b>Product Name</b><br />{{ item.name }}</VCol>
                        <VCol cols="6"><b>Category</b><br />{{ item.category.name }}</VCol>
                    </VRow>
                    <VRow>
                        <VCol><b>Description</b><br />{{ item.description }}</VCol>
                    </VRow>
                    <VRow>
                        <VCol cols="4"><b>Height</b><br />{{ item.height }}</VCol>
                        <VCol cols="4"><b>Width</b><br />{{ item.width }}</VCol>
                        <VCol cols="4"><b>Weight</b><br />{{ item.weight }}</VCol>
                    </VRow>
                    <VRow>
                        <VCol cols="4"><b>Print Price</b><br />₦{{ item.print_price }}</VCol>
                        <VCol cols="4"><b>Sheet Price</b><br />₦{{ item.sheet_price }}</VCol>
                        <VCol cols="4"><b>Cover Print</b><br />₦{{ item.cover_print_price }}</VCol>
                    </VRow>
                    <VRow>
                        <VCol ><b>Processing Centers</b><br />
                            <v-chip-group
                                selected-class="text-primary"
                                column
                            >
                                <v-chip
                                v-for="(processingCenter, index) in processingCenters"
                                :key="index"
                                >
                                {{ processingCenter }}
                                </v-chip>
                            </v-chip-group>
                        </VCol>
                    </VRow>
                    <VRow>
                        <VCol>
                            <b>Primary Processing Center</b><br />
                            <VChip class="mt-1">{{ item.primary_order_processing_branch }}</VChip>
                        </VCol>
                    </VRow>
                    <hr  class="mt-4" />
                    <div class="text-right">
                        <VBtn
                            color="blue-darken-3"
                            class="mr-2"
                        >
                            Duplicate
                            <VOverlay
                                v-model="showDuplicateOverlay"
                                activator="parent"
                                location-strategy="connected"
                                scroll-strategy="close"
                            >
                                <VCard min-width="300">
                                    <VCardTitle>Create Similar Product</VCardTitle>
                                    <VCardText>
                                        <div>
                                            <VTextField
                                                v-model="cloneData.productName"
                                                variant="outlined"
                                                label="Product Name"
                                                hide-details
                                            ></VTextField>
                                        </div>
                                        <div>
                                            <VCheckbox
                                                v-model="cloneData.includeProcess"
                                                label="Include Production Processes"
                                                hide-details
                                            ></VCheckbox>
                                        </div>
                                        <div v-if="processingClone" class="text-center my-3">
                                            <v-progress-circular
                                                color="red"
                                                indeterminate
                                            ></v-progress-circular>
                                        </div>
                                        <p v-if="cloneResponse.length">
                                            {{ cloneResponse }}<br />
                                            <Link :href="clonePageLink">View Product</Link>
                                        </p>
                                        <p v-if="cloneError.length" class="text-red">{{ cloneError }}</p>
                                    </VCardText>
                                    <VCardActions>
                                        <VBtn
                                        @click="duplicateProduct"
                                        >Proceed</VBtn>
                                        <VBtn 
                                            color="red"
                                            @click="!showDuplicateOverlay"
                                        >Close</VBtn>
                                    </VCardActions>
                                </VCard>
                            </VOverlay>
                        </VBtn>
                        <VBtn
                            color="grey-darken-3"
                            @click="modifyProduct"
                        >Edit</VBtn>
                    </div>
                </Panel>
                <ProductImagesSelector />
            </VCol>
            <VCol cols="12" md="6">
                <ProcessActivitiesEditor />
                <TemplateCodes />
            </VCol>
        </VRow>
        
    </BackendLayout>
</template>

<script setup>
import { ref } from 'vue';
import { usePage, Head, Link, router } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Panel from "@/Layouts/Shared/Panel.vue";
import ProcessActivitiesEditor from '@/Components/Editors/ProcessActivitiesEditor.vue';
import ProductImagesSelector from '@/Components/ProductImagesSelector.vue';
import TemplateCodes from '@/Components/TemplateCodes.vue'
import axios from 'axios';

const item = usePage().props.item;
const processingCenters = item.order_processing_branches ? JSON.parse(item.order_processing_branches) : []

const modifyProduct = () => {
    router.visit(route('item.edit', item.id));
}

const cloneData = ref({
    productName: `${item.name} Copy`,
    includeProcess: false
});

const showDuplicateOverlay = ref(false);
const processingClone = ref(false);
const cloneResponse = ref("");
const cloneError = ref("");
const clonePageLink = ref("")

const duplicateProduct = async () => {
    const payload = cloneData.value;
    processingClone.value = true;
    cloneResponse.value = "";
    cloneError.value = "";

    try {
        const response = await axios.post(route('item.duplicate', item.id), payload, {
            headers: {
                "Content-Type": "application/json"
            }
        });

        if (response.data && response.data.status == "success") {
            cloneResponse.value = response.data.response;
            clonePageLink.value = response.data.link;
        }
    } catch (error) {
        if (error.response && error.response.status === 422) {
            cloneError.value = error.response.data.message;
        } else {
            cloneError.value = "Something went wrong! Pls try again later.";
        }
    }

    processingClone.value = false;
}
</script>
