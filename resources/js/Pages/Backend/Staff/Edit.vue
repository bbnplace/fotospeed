<template>
    <Head title="Update Staff Info"></Head>
    <BackendLayout>
        <Centralize snippetTitle="Update Staff Info">
            <UserEditor :userType="staff.role.name" :user="staff"></UserEditor>
        </Centralize>
    </BackendLayout>
</template>

<script setup>
import { usePage, Head } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Centralize from "@/Layouts/Shared/Centralize.vue";
import UserEditor from "@/Components/Editors/UserEditor.vue";

const staff = usePage().props.staff

</script>

<style lang="scss" scoped>

</style>
