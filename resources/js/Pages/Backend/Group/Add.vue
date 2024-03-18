<template>
    <Head title="Create Group"></Head>
    <BackendLayout>
        <Centralize snippetTitle="Create Group">
            <GroupEditor></GroupEditor>
        </Centralize>
    </BackendLayout>
</template>

<script setup>
import { usePage, Head } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Centralize from "@/Layouts/Shared/Centralize.vue";
import GroupEditor from "@/Components/Editors/GroupEditor.vue";
</script>

<style lang="scss" scoped>

</style>
