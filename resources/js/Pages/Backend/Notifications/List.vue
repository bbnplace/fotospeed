<template>
    <Head title="Notification"></Head>
    <BackendLayout>
        <VRow>
            <VCol></VCol>
            <VCol cols="12" md="10" lg="8">
                <Panel snippet-title="Notifications">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <Searchbar label="Search Notifications" :route="route('notifications')" class="w-50" />
                        <div>
                            <button @click="markAllRead" class="btn btn-sm btn-outline-primary me-2">Mark All Read</button>
                            <button @click="deleteAll" class="btn btn-sm btn-outline-danger">Delete All</button>
                        </div>
                    </div>
                    <VDataIterator :items="items">
                    <!-- <template v-slot:default="{ items }"> -->
                        <Link v-for="(notification, i) in items" :key="i" :href="route('notification.view', [notification.id])">
                        <v-card class="p-2 my-3">
                            <VCardTitle>
                                {{ notification.title }}
                            </VCardTitle>
                            <VCardText>
                                <p v-html="notification.message"></p>
                                <div class="text-grey">{{ moment(notification.created_at).calendar() }}</div>
                            </VCardText>
                        </v-card>
                        </Link>
                    <!-- </template> -->
                    </VDataIterator>
                    <div class="text-center">
                        <Link v-for="(link, i) in links" :key="i" :href="link.url" v-html="link.label" :class="`btn${link.active ? ' btn-dark' : ''}`"></Link> 
                    </div>
                </Panel>
            </VCol>
            <VCol></VCol>
        </VRow>
        
    </BackendLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import BackendLayout from "@/Layouts/BackendLayout.vue"
import Panel from '@/Layouts/Shared/Panel.vue';
import moment from 'moment';
import Searchbar from '@/Components/Searchbar.vue';

const page = computed(() => usePage().props.notifications.current_page);
const items = computed(() => usePage().props.notifications.data);
const links = computed(() => usePage().props.notifications.links);

const markAllRead = () => {
    if (confirm('Are you sure you want to mark all notifications as read?')) {
        router.post(route('notifications.mark-all-read'));
    }
}

const deleteAll = () => {
    if (confirm('Are you sure you want to delete all notifications? This cannot be undone.')) {
        router.delete(route('notifications.delete-all'), {
            onSuccess: () => window.location.reload()
        });
    }
}

</script>

