<script setup>
import '@/../scss/app.scss';
import '@/../scss/icons.scss';

import { ref, onMounted, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import Topbar from '@/Layouts/Shared/Topbar.vue';
import LeftSidebar from '@/Layouts/Shared/LeftSidebar.vue';
import RightSidebar from '@/Layouts/Shared/RightSidebar.vue';
import Footer from  '@/Layouts/Shared/Footer.vue';
import axios from 'axios';

import '@/head';
import '@/indigo';
import ThemeCustomizer from '@/layout';

const notificationsPermitted = ref(Notification.permission === 'granted')
const requestNotificationPermission = () => {
    if (Notification.permission !== 'granted') {
        Notification.requestPermission().then(permission => {
            notificationsPermitted.value = permission === 'granted';
        });
    }
}

const props = defineProps({
    menus: Object
});

let heartbeatInterval = null;

// Function to ping server and keep session alive
const pingHeartbeat = async () => {
    try {
        const response = await axios.get('/heartbeat');
        // Update CSRF token if provided
        if (response.data.csrf_token) {
            const metaTag = document.querySelector('meta[name="csrf-token"]');
            if (metaTag) {
                metaTag.setAttribute('content', response.data.csrf_token);
            }
            // Update axios default header
            axios.defaults.headers.common['X-CSRF-TOKEN'] = response.data.csrf_token;
        }
    } catch (error) {
        console.error('Heartbeat failed:', error);
    }
};

onMounted(()=>{
    new ThemeCustomizer().init();
    
    // Start heartbeat - ping every 10 minutes (600000ms)
    // Session lifetime is typically 120 minutes, so 10 min interval keeps it fresh
    heartbeatInterval = setInterval(pingHeartbeat, 10 * 60 * 1000);
    
    // Initial ping after 1 minute
    setTimeout(pingHeartbeat, 60 * 1000);
})

onUnmounted(() => {
    // Clear interval when component is destroyed
    if (heartbeatInterval) {
        clearInterval(heartbeatInterval);
    }
})
</script>

<template>
    <div class="wrapper">
        <Topbar></Topbar>
        <LeftSidebar :menus="props.menus"></LeftSidebar>
        <div class="content-page">
            <div class="content">
                <div class="container-fluid p-2">
                    <v-alert
                        type="info"
                        closable
                        v-if="!notificationsPermitted"
                    >
                        This platform needs to send you notifications when there are activities that require your attention. Use the button below to grant us permission.
                        <v-btn
                            class="mt-2"
                            @click="requestNotificationPermission"
                        >
                            Grant Permission
                        </v-btn>
                    </v-alert>
                    <slot />
                </div>
            </div>
            <Footer></Footer>
        </div>
    </div>
</template>
