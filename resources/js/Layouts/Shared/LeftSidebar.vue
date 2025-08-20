<template>
    <div class="leftside-menu">

        <Link href="#" class="logo logo-light">
            <span class="logo-lg">
                <ApplicationLogo></ApplicationLogo>
            </span>
            <span class="logo-sm">
                <ApplicationLogo></ApplicationLogo>
            </span>
        </Link>

        <Link href="#" class="logo logo-dark">
            <span class="logo-lg">
                <ApplicationLogo></ApplicationLogo>
            </span>
            <span class="logo-sm">
                <ApplicationLogo></ApplicationLogo>
            </span>
        </Link>

        <div class="button-sm-hover" data-bs-toggle="tooltip" data-bs-placement="right" title="Show Full Sidebar">
            <i class="ri-checkbox-blank-circle-line align-middle"></i>
        </div>

        <div class="button-close-fullsidebar">
            <i class="ri-close-fill align-middle"></i>
        </div>

        <div class="h-100" id="leftside-menu-container" data-simplebar>
            <div class="leftbar-user">
                <Link :href="route('order.add')" class="btn btn-primary">
                    Create Order
                </Link>
            </div>


            <ul class="side-nav">
                <li class="side-nav-item">
                    <Link :href="route('dashboard')" class="side-nav-link">
                        <VIcon icon="mdi-view-dashboard"></VIcon>
                        <span> Dashboard</span>
                    </Link>
                </li>
                <template v-for="(menu, index) in props.menus" :key="index">
                    <template v-if="(!menu.adminOnly || userProps.isAdmin) || (!menu.adminBranchOnly || userProps.isAdminBranch)">
                        <li class="side-nav-title mt-3">{{ menu.heading }}</li>

                        <li class="side-nav-item" v-for="(link, i) in menu.links" :key="i" :class="{'menuitem-active': $page.url.startsWith('/panel/' + link.route)}">
                            <Link :href="route(link.route)" class="side-nav-link" :class="{'active': $page.url.startsWith('/panel/' + link.route)}" v-if="(!link.adminOnly || userProps.isAdmin) && (!link.adminBranchOnly || userProps.isAdminBranch)">
                                <VIcon :icon="link.icon"></VIcon>
                                <span> {{ link.name }} </span>
                            </Link>
                        </li>
                    </template>
                </template>

            </ul>

            <div class="clearfix"></div>
        </div>
    </div>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
const props = defineProps({
    menus: Object
});

const userProps = usePage().props.auth.user

</script>

<style scoped>
.collapse {
    visibility: unset;
}

.logo{
    height: var(--ct-topbar-height);
}

.logo-light{
    display: flex !important;
    justify-content: center;
    align-items: center;
}

.green {
    background-color: green;
}
</style>
