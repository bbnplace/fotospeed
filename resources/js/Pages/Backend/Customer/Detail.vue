<template>
    <Head title="Customer"></Head>
    <BackendLayout>
        <Link :href="route('customers')" class="font-bold">Back </Link>
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
                        <VCol cols="6"><b>Reward Points</b><br />
                            <div class="d-flex flex-column text-caption mt-2">
                                <div class="d-flex align-center mb-1">
                                    <v-icon icon="mdi-arrow-up-circle" color="success" size="x-small" class="mr-2"></v-icon>
                                    <span class="text-medium-emphasis mr-2" style="min-width: 60px;">Earned:</span>
                                    <span class="font-weight-bold">{{ Number(loyaltyPoints.total_earned).toLocaleString() }}</span>
                                    <span class="text-grey ml-1">(₦{{ Number(loyaltyPoints.total_earned_currency).toLocaleString() }})</span>
                                </div>
                                <div class="d-flex align-center mb-1">
                                    <v-icon icon="mdi-arrow-down-circle" color="error" size="x-small" class="mr-2"></v-icon>
                                    <span class="text-medium-emphasis mr-2" style="min-width: 60px;">Used:</span>
                                    <span class="font-weight-bold">{{ Number(loyaltyPoints.total_redeemed).toLocaleString() }}</span>
                                    <span class="text-grey ml-1">(₦{{ Number(loyaltyPoints.total_redeemed_currency).toLocaleString() }})</span>
                                </div>
                                <v-divider class="my-1 border-dashed"></v-divider>
                                <div class="d-flex align-center">
                                    <v-icon icon="mdi-wallet" color="primary" size="x-small" class="mr-2"></v-icon>
                                    <span class="text-medium-emphasis mr-2" style="min-width: 60px;">Available:</span>
                                    <span class="font-weight-bold text-high-emphasis">{{ Number(loyaltyPoints.available).toLocaleString() }}</span>
                                    <span class="text-primary font-weight-bold ml-1">(₦{{ Number(loyaltyPoints.available_currency).toLocaleString() }})</span>
                                </div>
                            </div>
                        </VCol>
                        <VCol cols="6" ><b>Groups</b><br />
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
            <VCol cols="12" md="6">
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
const loyaltyPoints = usePage().props.loyaltyPoints;
</script>
