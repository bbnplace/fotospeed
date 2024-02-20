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
                    <slot />
                </div>
            </div>
            <Footer></Footer>
        </div>
    </div>
</template>
