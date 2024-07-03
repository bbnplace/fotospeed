<template>
    <Head title="Add Item Role"></Head>
    <BackendLayout>
        <Centralize snippetTitle="Add Role">
            <RoleEditor></RoleEditor>
        </Centralize>
    </BackendLayout>
</template>

<script setup>
import { usePage, Head } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Centralize from "@/Layouts/Shared/Centralize.vue";
import RoleEditor from "@/Components/Editors/RoleEditor.vue";
</script>

<style lang="scss" scoped>

</style>
