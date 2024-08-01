<template>
    <Head title="Item Category"></Head>
    <BackendLayout>
        <Link :href="route('categories')" class="font-bold">Back</Link>
        <Centralize snippet-title="Item Category">
            <h3>{{ category.name }}</h3>
            <hr />
            <div class="mt-3 text-right">
                <Link :href="route('category.edit', category.id)" class="btn btn-dark">Modify</Link>
            </div>
        </Centralize>
    </BackendLayout>
</template>

<script setup>
import { usePage, Head, Link } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Centralize from "@/Layouts/Shared/Centralize.vue";

const category = usePage().props.category;
</script>
