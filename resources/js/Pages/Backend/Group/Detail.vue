<template>
    <Head title="Group"></Head>
    <BackendLayout>
        <Centralize snippetTitle="Group">
            <h3>{{ group.name }}</h3>
            <div>
                {{ group.description }}
            </div>
            <div class="mt-3">
                <Link :href="route('group.edit', group.id)" class="btn btn-secondary">Modify</Link>
            </div>
        </Centralize>
    </BackendLayout>
</template>

<script setup>
import { usePage, Head, Link } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Centralize from "@/Layouts/Shared/Centralize.vue";

const group = usePage().props.group;
</script>
