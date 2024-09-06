<template>
    <Head title="Email Template"></Head>
    <BackendLayout>
        <Centralize snippetTitle="Email Template">
            <h4>{{ emailTemplate.name }}</h4>
            <div class="mb-4">
                {{ emailTemplate.template }}
            </div>
            <v-row>
                <v-col cols="4">
                    Usage<br /><b>{{ emailTemplate.usage }}</b>
                </v-col>
                <v-col cols="4">
                    Target User<br /> <b>{{ emailTemplate.target }}</b>
                </v-col>
                <v-col cols="4">
                    Broadcast Time<br /> <b>{{ emailTemplate.timing }}</b>
                </v-col>
            </v-row>
            <hr />
            <div class="mt-3 text-right">
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
