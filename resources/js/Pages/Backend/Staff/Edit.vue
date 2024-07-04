<template>
    <Head title="Update Staff Info"></Head>
    <BackendLayout>
        <Centralize snippetTitle="Update Staff Info">
            <StaffEditor :user="staff"></StaffEditor>
        </Centralize>
    </BackendLayout>
</template>

<script setup>
import { usePage, Head } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Centralize from "@/Layouts/Shared/Centralize.vue";
import StaffEditor from "@/Components/Editors/StaffEditor.vue";

const staff = usePage().props.staff

</script>

<style lang="scss" scoped>

</style>
