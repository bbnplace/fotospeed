<template>
    <Head title="WhatsApp Template"></Head>
    <BackendLayout>
        <Centralize snippetTitle="WhatsApp Template">
            <h4>{{ whatsAppTemplate.name }}</h4>
            <div class="mb-4">
                {{ whatsAppTemplate.template }}
            </div>
            <v-row>
                <v-col cols="4">
                    Usage<br /><b>{{ whatsAppTemplate.usage }}</b>
                </v-col>
                <v-col cols="4">
                    Target User<br /> <b>{{ whatsAppTemplate.target }}</b>
                </v-col>
                <v-col cols="4">
                    Broadcast Time<br /> <b>{{ whatsAppTemplate.timing }}</b>
                </v-col>
            </v-row>
            <hr />
            <div class="mt-3 text-right">
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
