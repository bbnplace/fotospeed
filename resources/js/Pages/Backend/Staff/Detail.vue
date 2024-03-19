<template>
    <Head title="Staff"></Head>
    <BackendLayout>
        <Centralize :snippet-title="staff.name">
            <VRow>
                <VCol cols="6">Staff Name<br />{{ staff.name }}</VCol>
                <VCol cols="6">State<br />{{ staff.state.name }}</VCol>
            </VRow>
            <VRow>
                <VCol cols="6">Email<br />{{ staff.email }}</VCol>
                <VCol cols="6">Mobile<br />{{ staff.mobile }}</VCol>
            </VRow>
            <div class="mt-3">
                <Link :href="route('staff.edit', staff.id)" class="btn btn-secondary">Modify</Link>
            </div>
        </Centralize>
    </BackendLayout>
</template>

<script setup>
import { usePage, Head, Link } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Centralize from "@/Layouts/Shared/Centralize.vue";

const staff = usePage().props.staff;
</script>
