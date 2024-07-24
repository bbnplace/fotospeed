<template>
    <Head title="Product"></Head>
    <BackendLayout>
        <VRow>
            <VCol cols="12" md="6">
                <Panel :snippet-title="item.name">
                    <VRow>
                        <VCol cols="6"><b>Item Name</b><br />{{ item.name }}</VCol>
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
                    <div class="mt-3">
                        <Link :href="route('item.edit', item.id)" class="btn btn-secondary">Modify</Link>
                    </div>
                </Panel>
            </VCol>
            <VCol cols="12" md="6">
                <ProcessActivitiesEditor />
            </VCol>
        </VRow>
        
    </BackendLayout>
</template>

<script setup>

import { usePage, Head, Link } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Panel from "@/Layouts/Shared/Panel.vue";
import ProcessActivitiesEditor from '@/Components/Editors/ProcessActivitiesEditor.vue';

const item = usePage().props.item;

</script>
