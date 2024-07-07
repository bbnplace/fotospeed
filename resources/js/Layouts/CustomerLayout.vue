<template>
    <AuthenticatedLayout :menus="menus">
        <slot />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from './AuthenticatedLayout.vue';

const menus = [
    {
        heading: "Manage",
        links: [
            {
                icon: "mdi-cart-variant",
                name: "My Orders",
                route: "orders"
            },
            {
                icon: "mdi-inbox",
                name: "Inbox",
                route: "messages"
            }
        ]
    },
    {
        heading: "Settings",
        links: [
            {
                icon: "mdi-cog-outline",
                name: "Profile",
                route: "profile"
            }
        ]
    }

];
</script>

