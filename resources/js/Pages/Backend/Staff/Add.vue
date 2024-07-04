<template>
    <Head title="Register Staff"></Head>
    <BackendLayout>
        <Centralize snippetTitle="Register Staff">
            <StaffEditor></StaffEditor>
        </Centralize>
    </BackendLayout>
</template>

<script setup>
import { usePage, Head } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Centralize from "@/Layouts/Shared/Centralize.vue";
import StaffEditor from "@/Components/Editors/StaffEditor.vue";
</script>

<style lang="scss" scoped>

</style>
