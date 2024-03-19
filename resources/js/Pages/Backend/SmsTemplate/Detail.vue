<template>
    <Head title="SMS Template"></Head>
    <BackendLayout>
        <Centralize snippetTitle="SMS Template">
            <h3>{{ smsTemplate.name }}</h3>
            <div>
                {{ smsTemplate.template }}
            </div>
            <div class="mt-3">
                <Link :href="route('sms-template.edit', smsTemplate.id)" class="btn btn-secondary">Modify</Link>
            </div>
        </Centralize>
    </BackendLayout>
</template>

<script setup>
import { usePage, Head, Link } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Centralize from "@/Layouts/Shared/Centralize.vue";

const smsTemplate = usePage().props.smsTemplate;
</script>
