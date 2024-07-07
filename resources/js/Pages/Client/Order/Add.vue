<template>
    <Head title="Create Order"></Head>
    <ClientLayout>
        <Panel snippetTitle="New Order">
            <OrderEditor></OrderEditor>
        </Panel>
    </ClientLayout>
</template>

<script setup>
import { usePage, Head } from "@inertiajs/vue3";
import ClientLayout from "@/Layouts/ClientLayout.vue";
import Panel from "@/Layouts/Shared/Panel.vue";
import OrderEditor from "@/Components/Editors/OrderEditor.vue";
</script>

