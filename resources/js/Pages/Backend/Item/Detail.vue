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
                        <Link :href="route('item.edit', item.id)" class="btn btn-dark">Modify</Link>
                    </div>
                </Panel>
            </VCol>
            <VCol cols="12" md="6">
                <ProcessActivitiesEditor />
                <TemplateCodes />
            </VCol>
        </VRow>
        
    </BackendLayout>
</template>

<script setup>

import { usePage, Head, Link } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Panel from "@/Layouts/Shared/Panel.vue";
import ProcessActivitiesEditor from '@/Components/Editors/ProcessActivitiesEditor.vue';
import TemplateCodes from '@/Components/TemplateCodes.vue'

const item = usePage().props.item;
const processingCenters = item.order_processing_branches ? JSON.parse(item.order_processing_branches) : []

</script>
