<template>
    <Head title="Process Completed"></Head>
    <BackendLayout>
        <Panel snippetTitle="Completed">
            <div>
            Order #{{ props.orderNumber }} has now been moved to {{ props.newProcess }}.
            </div>
        </Panel>
    </BackendLayout>
</template>

<script setup>
import Panel from '@/Layouts/Shared/Panel.vue';
import BackendLayout from '@/Layouts/BackendLayout.vue';
import { usePage, Head } from '@inertiajs/vue3';

const props = usePage().props;

</script>

