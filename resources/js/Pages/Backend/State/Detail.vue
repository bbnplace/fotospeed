<template>
    <Head title="State"></Head>
    <BackendLayout>
        <Link :href="route('states')" class="font-bold">Back</Link>
        <Centralize snippet-title="State">
            <h3>{{ state.name }}</h3>
            <hr />
            <div class="mt-3 text-right">
                <Link :href="route('state.edit', state.id)" class="btn btn-dark">Rename</Link>
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
