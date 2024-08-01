<template>
    <Head title="SMS Template"></Head>
    <BackendLayout>
        <Link :href="route('sms-templates')" class="font-bold">Back</Link>
        <Centralize snippetTitle="SMS Template">
            <h3>{{ smsTemplate.name }}</h3>
            <div>
                {{ smsTemplate.template }}
            </div>
            <hr />
            <div class="mt-3 text-right">
                <Link :href="route('sms-template.edit', smsTemplate.id)" class="btn btn-dark">Modify</Link>
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
