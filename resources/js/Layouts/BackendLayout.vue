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
        adminBranchOnly: false,
        links: [
            {
                icon: "mdi-account-group",
                name: "Customers",
                route: "customers",
                adminOnly: false,
                adminBranchOnly: false,
            },
            {
                icon: "mdi-cart-variant",
                name: "Orders",
                route: "orders",
                adminOnly: false,
                adminBranchOnly: false,
            },
            {
                icon: "mdi-image-multiple",
                name: "Media Library",
                route: "media",
                adminOnly: false,
                adminBranchOnly: true,
            },
            {
                icon: "mdi-invoice",
                name: "Invoices",
                route: "invoices",
                adminOnly: false,
                adminBranchOnly: true,
            },
        ]
    },
    {
        heading: "Messaging",
        adminBranchOnly: false,
        links: [
            {
                icon: "mdi-inbox",
                name: "Inbox",
                route: "messages",
                adminOnly: false,
                adminBranchOnly: false,
            },
            {
                icon: "mdi-group",
                name: "Groups",
                route: "groups",
                adminOnly: true,
                adminBranchOnly: false,
            },
            {
                icon: "mdi-message-processing",
                name: "SMS Templates",
                route: "sms-templates",
                adminOnly: true,
                adminBranchOnly: true,
            },
            {
                icon: "mdi-whatsapp",
                name: "WhatsApp Templates",
                route: "whatsapp-templates",
                adminOnly: true,
                adminBranchOnly: true,
            },
            {
                icon: "mdi-email-edit",
                name: "Email Templates",
                route: "email-templates",
                adminOnly: true,
                adminBranchOnly: true,
            },
            {
                icon: "mdi-square-rounded-badge-outline",
                name: "Notification Templates",
                route: "notification-templates",
                adminOnly: true,
                adminBranchOnly: true,
            }
        ]
    },
    {
        heading: "My Account",
        adminBranchOnly: false,
        links: [
            {
                icon: "mdi-account",
                name: "Profile",
                route: "profile.edit",
                adminOnly: false,
                adminBranchOnly: false,
            }
        ]
    },
    {
        heading: "ADMINISTRATIVE",
        adminBranchOnly: false,
        adminOnly: true,
        links: [
            {
                icon: "mdi-account-tie-woman",
                name: "Staff",
                route: "staff",
                adminOnly: true,
                adminBranchOnly: false,
            },
            {
                icon: "mdi-briefcase-account",
                name: "Roles",
                route: "roles",
                adminOnly: true,
                adminBranchOnly: true,
            },
            {
                icon: "mdi-shape",
                name: "Product Categories",
                route: "categories",
                adminOnly: true,
                adminBranchOnly: true,
            },
            {
                icon: "mdi-list-box-outline",
                name: "Products",
                route: "items",
                adminOnly: true,
                adminBranchOnly: true,
            },
            {
                icon: "mdi-cog-transfer",
                name: "Processes",
                route: "processes",
                adminOnly: true,
                adminBranchOnly: true,
            },
            {
                icon: "mdi-sitemap",
                name: "Branches",
                route: "branches",
                adminOnly: true,
                adminBranchOnly: true,
            },
            {
                icon: "mdi-map-marker",
                name: "States",
                route: "states",
                adminOnly: true,
                adminBranchOnly: true,
            },
            {
                icon: "mdi-cog",
                name: "Settings",
                route: "settings",
                adminOnly: true,
                adminBranchOnly: false,
            }
        ]
    }

];
</script>

