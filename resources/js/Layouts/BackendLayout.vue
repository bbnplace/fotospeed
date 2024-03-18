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
                route: "customers"
            },
            {
                icon: "mdi-cart-variant",
                name: "Orders",
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
        heading: "Messaging",
        links: [
            {
                icon: "mdi-group",
                name: "Groups",
                route: "groups"
            },
            {
                icon: "mdi-message-processing",
                name: "SMS Templates",
                route: "sms-templates"
            },
            {
                icon: "mdi-email-edit",
                name: "Email Templates",
                route: "email-templates"
            }
        ]
    },
    {
        heading: "Settings",
        links: [
            {
                icon: "mdi-account-tie-woman",
                name: "Staff",
                route: "staff"
            },
            {
                icon: "mdi-shape",
                name: "Item Categories",
                route: "categories"
            },
            {
                icon: "mdi-list-box-outline",
                name: "Items",
                route: "items"
            },
            {
                icon: "mdi-sitemap",
                name: "Branches",
                route: "branches"
            },
            {
                icon: "mdi-map-marker",
                name: "States",
                route: "states"
            }
        ]
    }

];
</script>

