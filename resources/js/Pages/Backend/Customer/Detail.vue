<template>
    <Head title="Customer"></Head>
    <BackendLayout>
        <Centralize :snippet-title="customer.name">
            <VRow>
                <VCol cols="6"><b>Customer Name</b><br />{{ customer.name }}</VCol>
                <VCol cols="6"><b>State</b><br />{{ customer.state.name }}</VCol>
            </VRow>
            <VRow>
                <VCol cols="6"><b>Email</b><br />{{ customer.email }}</VCol>
                <VCol cols="6"><b>Mobile</b><br />{{ customer.mobile }}</VCol>
            </VRow>
            <div class="mt-3">
                <Link :href="route('customer.edit', customer.id)" class="btn btn-secondary">Modify</Link>
            </div>
        </Centralize>
    </BackendLayout>
</template>

<script setup>
import { usePage, Head, Link } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Centralize from "@/Layouts/Shared/Centralize.vue";

const customer = usePage().props.customer;
</script>
