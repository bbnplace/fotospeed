<template>
    <Head title="SMS Template Editor"></Head>
    <BackendLayout>
        <Panel snippetTitle="Create SMS Template">
            <VRow>
                <VCol cols="12" md="6">
                    <SMSTemplateEditor></SMSTemplateEditor>
                </VCol>
                <VCol cols="12" md="6">
                    <h4>Template Codes</h4>
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
                    </dl>
                </VCol>
            </VRow>
        </Panel>
    </BackendLayout>
</template>

<script setup>
import { usePage, Head } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Panel from "@/Layouts/Shared/Panel.vue";
import SMSTemplateEditor from "@/Components/Editors/SMSTemplateEditor.vue"

</script>

<style lang="scss" scoped>

</style>
