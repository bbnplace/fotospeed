<template>
    <Head title="Customer"></Head>
    <BackendLayout>
        <Centralize :snippet-title="customer.name">
            <VRow>
                <VCol cols="6">Customer Name<br />{{ customer.name }}</VCol>
                <VCol cols="6">State<br />{{ customer.state.name }}</VCol>
            </VRow>
            <VRow>
                <VCol cols="6">Email<br />{{ customer.email }}</VCol>
                <VCol cols="6">Mobile<br />{{ customer.mobile }}</VCol>
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
