<template>
    <Head title="Edit Group"></Head>
    <BackendLayout>
        <Centralize snippetTitle="Edit Group">
            <GroupEditor :group="group"></GroupEditor>
        </Centralize>
    </BackendLayout>
</template>

<script setup>
import { usePage, Head } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Centralize from "@/Layouts/Shared/Centralize.vue";
import GroupEditor from "@/Components/Editors/GroupEditor.vue";

const group = usePage().props.group
</script>

<style lang="scss" scoped>

</style>
