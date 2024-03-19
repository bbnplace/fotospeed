<template>
    <Head title="Update Customer Info"></Head>
    <BackendLayout>
        <Centralize snippetTitle="Update Customer Info">
            <UserEditor :userType="customer.role.name" :user="customer"></UserEditor>
        </Centralize>
    </BackendLayout>
</template>

<script setup>
import { usePage, Head } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Centralize from "@/Layouts/Shared/Centralize.vue";
import UserEditor from "@/Components/Editors/UserEditor.vue";

const customer = usePage().props.customer

</script>

<style lang="scss" scoped>

</style>
