<template>
    <Head title="Staff"></Head>
    <BackendLayout>
        <Centralize :snippet-title="staff.name">
            <VRow>
                <VCol cols="6"><b>Staff Name</b><br />{{ staff.name }}</VCol>
                <VCol cols="6"><b>Branch</b><br />{{ staff.branch.name }}, {{ staff.state.name }}</VCol>
            </VRow>
            <VRow>
                <VCol cols="6"><b>Email</b><br />{{ staff.email }}</VCol>
                <VCol cols="6"><b>Mobile</b><br />{{ staff.mobile }}</VCol>
            </VRow>
            <VRow>
                <VCol cols="6"><b>Department</b><br />{{ staff.role.name }}</VCol>
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
