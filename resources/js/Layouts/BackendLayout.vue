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
                icon: "mdi-account-group",
                name: "Customers",
                route: "customers",
                adminOnly: false
            },
            {
                icon: "mdi-cart-variant",
                name: "Orders",
                route: "orders",
                adminOnly: false
            },
            {
                icon: "mdi-inbox",
                name: "Inbox",
                route: "messages",
                adminOnly: false
            }
        ]
    },
    {
        heading: "Messaging",
        links: [
            {
                icon: "mdi-group",
                name: "Groups",
                route: "groups",
                adminOnly: false
            },
            {
                icon: "mdi-message-processing",
                name: "SMS Templates",
                route: "sms-templates",
                adminOnly: true
            },
            {
                icon: "mdi-email-edit",
                name: "Email Templates",
                route: "email-templates",
                adminOnly: true
            }
        ]
    },
    {
        heading: "Settings",
        links: [
            {
                icon: "mdi-account-tie-woman",
                name: "Staff",
                route: "staff",
                adminOnly: true
            },
            {
                icon: "mdi-shape",
                name: "Item Categories",
                route: "categories",
                adminOnly: true
            },
            {
                icon: "mdi-briefcase-account",
                name: "Roles",
                route: "roles",
                adminOnly: true
            },
            {
                icon: "mdi-list-box-outline",
                name: "Items",
                route: "items",
                adminOnly: true
            },
            {
                icon: "mdi-cog-outline",
                name: "Processes",
                route: "processes",
                adminOnly: true
            },
            {
                icon: "mdi-sitemap",
                name: "Branches",
                route: "branches",
                adminOnly: true
            },
            {
                icon: "mdi-map-marker",
                name: "States",
                route: "states",
                adminOnly: true
            },
            {
                icon: "mdi-account",
                name: "Profile",
                route: "profile.edit",
                adminOnly: false
            },
            {
                icon: "mdi-cog",
                name: "Settings",
                route: "settings",
                adminOnly: true
            }
        ]
    }

];
</script>

