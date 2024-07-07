<template>
    <Head title="Indigo Africa Online"></Head>
    <!-- <CustomerLayout> -->
        <Panel class="my-0">
            <div class="text-center border-bottom pb-3 container">
                <div :style="{ marginBottom: '60px'}">
                    <h1>Welcome, {{ $page.props.auth.user.name }}</h1>
                    <h4><Link href="#" @click.prevent="submitForm">Sign Out</Link></h4>
                </div>
                <VRow>
                    <VCol cols="12" sm="4">
                        <Link :href="route('customer.new-order')">
                            <VIcon size="50" icon="mdi-upload"></VIcon>
                            <p>Upload Order</p>
                        </Link>
                    </VCol>
                    <VCol cols="12" sm="4">
                        <Link :href="route('customer.my-orders')">
                            <VIcon size="50" icon="mdi-cart"></VIcon>
                            <p>My Orders</p>
                        </Link>
                    </VCol>
                    <VCol cols="12" sm="4">
                        <VIcon size="50" icon="mdi-invoice"></VIcon>
                        <p>Invoice</p>
                    </VCol>
                </VRow>
            </div>
        </Panel>
        <div class="pb-3 container">
            <slot />
        </div>
        <div class="pb-3 container">
            Copyright 2024. Indigo Africa.
        </div>
</template>

<script setup>
import { Head, usePage, useForm, Link } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import Panel from "@/Layouts/Shared/Panel.vue";

const form = useForm({});
const submitForm = () => {
    form.post(route('logout'));
}

</script>

<style lang="scss" scoped>

</style>
