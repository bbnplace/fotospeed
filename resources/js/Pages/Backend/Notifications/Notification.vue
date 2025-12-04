<template>
    <Head title="Notification"></Head>
    <BackendLayout>
        <VRow>
            <VCol></VCol>
            <VCol cols="12" md="10" lg="8">
                <Panel snippet-title="Notifications">
                    <v-card class="p-2">
                        <VCardTitle>
                            {{ notification.title }}
                        </VCardTitle>
                        <VCardText>
                            <p v-html="notification.message"></p>
                            <div class="text-grey">{{ moment(notification.created_at).calendar() }}</div>
                        </VCardText>
                    </v-card>
                    <div class="mt-4 text-center">
                        <Link :href="route('notifications')" class="btn bg-blue">View All</Link>
                    </div>
                </Panel>
            </VCol>
            <VCol></VCol>
        </VRow>
        
    </BackendLayout>
</template>

<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import BackendLayout from "@/Layouts/BackendLayout.vue"
import Panel from '@/Layouts/Shared/Panel.vue';
import moment from 'moment';

const notification = usePage().props.notification;

</script>

