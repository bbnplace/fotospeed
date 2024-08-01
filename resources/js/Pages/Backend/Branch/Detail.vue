<template>
    <Head title="Branch"></Head>
    <BackendLayout>
        <Link :href="route('branches')" class="font-bold">Back</Link>
        <Centralize snippetTitle="Branch">
            <h3>{{ branch.name }}</h3>
            <div>
                {{ branch.address }}
            </div>
            <div>
                {{ branch.state.name }}
            </div>
            <hr />
            <div class="mt-3 text-right">
                <Link :href="route('branch.edit', branch.id)" class="btn btn-dark">Rename</Link>
            </div>
        </Centralize>
    </BackendLayout>
</template>

<script setup>
import { usePage, Head, Link } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Centralize from "@/Layouts/Shared/Centralize.vue";

const branch = usePage().props.branch;

</script>

