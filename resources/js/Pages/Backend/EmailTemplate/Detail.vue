<template>
    <Head title="Email Template"></Head>
    <BackendLayout>
        <Centralize snippetTitle="Email Template">
            <h3>{{ emailTemplate.name }}</h3>
            <div>
                {{ emailTemplate.template }}
            </div>
            <div class="mt-3">
                <Link :href="route('email-template.edit', emailTemplate.id)" class="btn btn-secondary">Modify</Link>
            </div>
        </Centralize>
    </BackendLayout>
</template>

<script setup>
import { usePage, Head, Link } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Centralize from "@/Layouts/Shared/Centralize.vue";

const emailTemplate = usePage().props.emailTemplate;
</script>
