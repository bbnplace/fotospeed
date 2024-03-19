<template>
    <Head title="State"></Head>
    <BackendLayout>
        <Centralize snippet-title="State">
            <h3>{{ state.name }}</h3>
            <div class="mt-3">
                <Link :href="route('state.edit', state.id)" class="btn btn-secondary">Modify</Link>
            </div>
        </Centralize>
    </BackendLayout>
</template>

<script setup>
import { usePage, Head, Link } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Centralize from "@/Layouts/Shared/Centralize.vue";

const state = usePage().props.state;
</script>
