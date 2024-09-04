<script setup>
import '@/../scss/app.scss';
import '@/../scss/icons.scss';

import { ref, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import Topbar from '@/Layouts/Shared/Topbar.vue';
import LeftSidebar from '@/Layouts/Shared/LeftSidebar.vue';
import RightSidebar from '@/Layouts/Shared/RightSidebar.vue';
import Footer from  '@/Layouts/Shared/Footer.vue';

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

onMounted(()=>{
    new ThemeCustomizer().init();
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
