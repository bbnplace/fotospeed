<template>
    <Head title="Notification"></Head>
    <BackendLayout>
        <VRow>
            <VCol></VCol>
            <VCol cols="12" md="10" lg="8">
                <Panel snippet-title="Notifications">
                    <Searchbar label="Search Notifications" :route="route('notifications')" class="mb-3" />
                    <VDataIterator :items="items">
                    <!-- <template v-slot:default="{ items }"> -->
                        <Link v-for="(notification, i) in items" :key="i" :href="route('notification.view', [notification.id])">
                        <v-card class="p-2 my-3">
                            <VCardTitle>
                                {{ notification.title }}
                            </VCardTitle>
                            <VCardText>
                                <p>{{ notification.message }}</p>
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
import { ref } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import BackendLayout from "@/Layouts/BackendLayout.vue"
import Panel from '@/Layouts/Shared/Panel.vue';
import moment from 'moment';
import Searchbar from '@/Components/Searchbar.vue';

const notifications = usePage().props.notifications;
const page = ref(notifications.current_page)
const items = notifications.data;
const links = notifications.links;
//   const notifications = Array.from({ length: 15 }, (k, v) => ({
//     title: 'Item ' + v + 1,
//     text: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Commodi, ratione debitis quis est labore voluptatibus! Eaque cupiditate minima, at placeat totam, magni doloremque veniam neque porro libero rerum unde voluptatem!',
//   }))

</script>

