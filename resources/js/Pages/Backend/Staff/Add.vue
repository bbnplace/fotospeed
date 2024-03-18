<template>
    <Head title="Register Staff"></Head>
    <BackendLayout>
        <Centralize snippetTitle="Register Staff">
            <UserEditor></UserEditor>
        </Centralize>
    </BackendLayout>
</template>

<script setup>
import { usePage, Head } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Centralize from "@/Layouts/Shared/Centralize.vue";
import UserEditor from "@/Components/Editors/UserEditor.vue";
</script>

<style lang="scss" scoped>

</style>
