<template>
    <Head title="SMS Template"></Head>
    <BackendLayout>
        <Link :href="route('sms-templates')" class="font-bold">Back</Link>
        <Centralize snippetTitle="SMS Template">
            <h4>{{ smsTemplate.name }}</h4>
            <div class="mb-4">
                {{ smsTemplate.template }}
            </div>
            <v-row>
                <v-col cols="4">
                    Usage<br /><b>{{ smsTemplate.usage }}</b>
                </v-col>
                <v-col cols="4">
                    Target User<br /> <b>{{ smsTemplate.target }}</b>
                </v-col>
                <v-col cols="4">
                    Broadcast Time<br /> <b>{{ smsTemplate.timing }}</b>
                </v-col>
            </v-row>
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
