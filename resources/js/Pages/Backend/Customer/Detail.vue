<template>
    <Head title="Customer"></Head>
    <BackendLayout>
        <VRow>
            <VCol cols="12" md="6">
                <Panel :snippet-title="customer.name">
                    <VRow>
                        <VCol cols="6"><b>Customer Name</b><br />{{ customer.name }}</VCol>
                        <VCol cols="6"><b>State</b><br />{{ customer.state.name }}</VCol>
                    </VRow>
                    <VRow>
                        <VCol cols="6"><b>Email</b><br />{{ customer.email }}</VCol>
                        <VCol cols="6"><b>Mobile</b><br />{{ customer.mobile }}</VCol>
                    </VRow>
                    <VRow>
                        <VCol ><b>Groups</b><br />
                            <v-chip-group
                                selected-class="text-primary"
                                column
                            >
                                <v-chip
                                v-for="(group, index) in groups"
                                :key="index"
                                >
                                {{ group }}
                                </v-chip>
                            </v-chip-group>
                        </VCol>
                    </VRow>
                    <div class="mt-3">
                        <Link :href="route('customer.edit', customer.id)" class="btn btn-secondary">Modify</Link>
                    </div>
                </Panel>
            </VCol>
            <VCol cols="12" md="6">
                <CustomerFeedback />
            </VCol>
        </VRow>
        
    </BackendLayout>
</template>

<script setup>
import { usePage, Head, Link } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Panel from "@/Layouts/Shared/Panel.vue";
import CustomerFeedback from '@/Components/CustomerFeedback.vue';

const customer = usePage().props.customer;
const groups = usePage().props.customerGroups;
</script>
