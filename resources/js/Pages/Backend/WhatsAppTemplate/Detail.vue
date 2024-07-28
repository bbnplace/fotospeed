<template>
    <Head title="WhatsApp Template"></Head>
    <BackendLayout>
        <Centralize snippetTitle="WhatsApp Template">
            <h3>{{ whatsAppTemplate.name }}</h3>
            <div>
                {{ whatsAppTemplate.template }}
            </div>
            <div class="mt-3">
                <Link :href="route('whatsapp-template.edit', whatsAppTemplate.id)" class="btn btn-secondary">Modify</Link>
            </div>
        </Centralize>
    </BackendLayout>
</template>

<script setup>
import { usePage, Head, Link } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Centralize from "@/Layouts/Shared/Centralize.vue";

const whatsAppTemplate = usePage().props.whatsAppTemplate;
</script>
