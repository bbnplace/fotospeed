<template>
    <Head title="Notification Template"></Head>
    <BackendLayout>
        <Link :href="route('notification-templates')" class="font-bold">Back</Link>
        <Centralize snippetTitle="Notification Template">
            <h4 class="mb-3">{{ notificationTemplate.name }}</h4>
            <div>
                <b>title</b>
                <h4 class="mb-3">{{ notificationTemplate.title }}</h4>
                <b>Body</b>
                <p>
                    {{ notificationTemplate.template }}
                </p>
            </div>
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
