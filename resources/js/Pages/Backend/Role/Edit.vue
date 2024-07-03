<template>
    <Head title="Rename Role"></Head>
    <BackendLayout>
        <Centralize snippetTitle="Rename Role">
            <RoleEditor :role="role"></RoleEditor>
        </Centralize>
    </BackendLayout>
</template>

<script setup>
import { usePage, Head } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Centralize from "@/Layouts/Shared/Centralize.vue";
import RoleEditor from "@/Components/Editors/RoleEditor.vue";

const role = usePage().props.role;
</script>

<style lang="scss" scoped>

</style>
