<template>
    <Panel snippetTitle="Template Codes">
        <dl>
            <dt>customer_name</dt>
            <dd>The name of the customer as registered.</dd>
            <dt>customer_email</dt>
            <dd>This captures the email address of the customer.</dd>
            <dt>customer_mobile</dt>
            <dd>This captures the mobile number of the customer.</dd>
            <dt>order_name</dt>
            <dd>The name of the order as defined by customer or service personnel.</dd>
            <dt>order_number</dt>
            <dd>Represents the ID (or tracking number) for the order.</dd>
            <dt>delivery_address</dt>
            <dd>The Delivery Address provided by the customer.</dd>
            <dt>order_status</dt>
            <dd>This captures the current status of the order.</dd>
            <dt>next_process</dt>
            <dd>This captures the next process an order is to undergo.</dd>
            <dt>order_branch</dt>
            <dd>This captures the branch where processing the order.</dd>
            <dt>invoice_link</dt>
            <dd>The link that will be used by customer to view invoice and make payment.</dd>
            <dt>item_name</dt>
            <dd>The name of the item being ordered. Aliases: <b>product_name</b>, <b>product</b>, or <b>item</b>.</dd>
        </dl>
    </Panel>
</template>

<script setup>
import Panel from "@/Layouts/Shared/Panel.vue";
</script>

