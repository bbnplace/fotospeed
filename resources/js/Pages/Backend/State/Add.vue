<template>
    <Head title="Register State"></Head>
    <BackendLayout>
        <Centralize snippetTitle="Register State">
            <StateEditor></StateEditor>
        </Centralize>
    </BackendLayout>
</template>

<script setup>
import { usePage, Head } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Centralize from "@/Layouts/Shared/Centralize.vue";
import StateEditor from "@/Components/Editors/StateEditor.vue";
</script>

<style lang="scss" scoped>

</style>
