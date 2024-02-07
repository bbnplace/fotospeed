<script setup>
import '@/../scss/app.scss';
import '@/../scss/icons.scss';

import { ref, onMounted } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import Topbar from '@/Layouts/Shared/Topbar.vue';
import LeftSidebar from '@/Layouts/Shared/LeftSidebar.vue';
import RightSidebar from '@/Layouts/Shared/RightSidebar.vue';
import Footer from  '@/Layouts/Shared/Footer.vue';

import '@/head';
import '@/indigo';
import ThemeCustomizer from '@/layout';
onMounted(()=>{
    new ThemeCustomizer().init();
})
</script>

<template>
    <div class="wrapper">
        <Topbar></Topbar>
        <LeftSidebar></LeftSidebar>
        <div class="content-page">
            <div class="content">
                <slot />
            </div>
            <Footer></Footer>
        </div>
    </div>
</template>
