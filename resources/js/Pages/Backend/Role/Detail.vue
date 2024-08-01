<template>
    <Head title="Item Role"></Head>
    <BackendLayout>
        <Link :href="route('roles')" class="font-bold">Back</Link>
        <Centralize snippet-title="Item Role">
            <h3>{{ role.name }}</h3>
            <hr />
            <div class="mt-3 text-right">
                <Link :href="route('role.edit', role.id)" class="btn btn-dark">Rename</Link>
            </div>
        </Centralize>
    </BackendLayout>
</template>

<script setup>
import { usePage, Head, Link } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Centralize from "@/Layouts/Shared/Centralize.vue";

const role = usePage().props.role;
</script>
