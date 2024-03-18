<template>
    <Head title="Rename State"></Head>
    <BackendLayout>
        <Centralize snippetTitle="Rename State">
            <StateEditor :state="state"></StateEditor>
        </Centralize>
    </BackendLayout>
</template>

<script setup>
import { usePage, Head } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Centralize from "@/Layouts/Shared/Centralize.vue";
import StateEditor from "@/Components/Editors/StateEditor.vue";

const state = usePage().props.state;
</script>

<style lang="scss" scoped>

</style>
