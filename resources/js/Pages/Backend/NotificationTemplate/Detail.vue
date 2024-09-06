<template>
    <Head title="Notification Template"></Head>
    <BackendLayout>
        <Link :href="route('notification-templates')" class="font-bold">Back</Link>
        <Centralize snippetTitle="Notification Template">
            <h4 class="mb-2">{{ notificationTemplate.name }}</h4>
            <div class="mb-4">
                <b>title</b>
                <p class="mb-1">{{ notificationTemplate.title }}</p>
                <b>Body</b>
                <p>
                    {{ notificationTemplate.template }}
                </p>
            </div>
            <v-row>
                <v-col cols="4">
                    Usage<br /><b>{{ notificationTemplate.usage }}</b>
                </v-col>
                <v-col cols="4">
                    Target User<br /> <b>{{ notificationTemplate.target }}</b>
                </v-col>
                <v-col cols="4">
                    Broadcast Time<br /> <b>{{ notificationTemplate.timing }}</b>
                </v-col>
            </v-row>
            <hr />
            <div class="mt-3 text-right">
                <Link :href="route('notification-template.edit', notificationTemplate.id)" class="btn btn-dark">Modify</Link>
            </div>
        </Centralize>
    </BackendLayout>
</template>

<script setup>
import { usePage, Head, Link } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Centralize from "@/Layouts/Shared/Centralize.vue";

const notificationTemplate = usePage().props.notificationTemplate;
</script>
