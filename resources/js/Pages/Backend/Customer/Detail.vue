<template>
    <Head title="Customer"></Head>
    <BackendLayout>
        <Link :href="route('customers')" class="font-bold">Back </Link>
        <VRow>
            <VCol cols="12" md="5">
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
                    <hr />
                    <div class="mt-3 text-right">
                        <Link :href="route('customer.edit', customer.id)" class="btn btn-dark">Modify</Link>
                    </div>
                </Panel>
            </VCol>
            <VCol cols="12" md="7">
                <Panel snippet-title="Engagements">
                    <v-expansion-panels>
                        <v-expansion-panel>
                            <v-expansion-panel-title color="blue">Messages</v-expansion-panel-title>
                            <v-expansion-panel-text>
                                <MessagingOptions />
                            </v-expansion-panel-text>
                        </v-expansion-panel>
                        <v-expansion-panel>
                            <v-expansion-panel-title color="blue">Customer Feedback</v-expansion-panel-title>
                            <v-expansion-panel-text>
                                <CustomerFeedback />
                            </v-expansion-panel-text>
                        </v-expansion-panel>
                    </v-expansion-panels>
                </Panel>
            </VCol>
        </VRow>
        
    </BackendLayout>
</template>

<script setup>
import { usePage, Head, Link } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Panel from "@/Layouts/Shared/Panel.vue";
import CustomerFeedback from '@/Components/CustomerFeedback.vue';
import MessagingOptions from '@/Components/MessagingClient/MessagingOptions.vue';

const customer = usePage().props.customer;
const groups = usePage().props.customerGroups;
</script>
