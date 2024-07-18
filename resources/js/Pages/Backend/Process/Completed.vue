<template>
    <Head title="Process Completed"></Head>
    <BackendLayout>
        <Panel snippetTitle="Completed">
            <div>
                Order #{{ props.orderNumber }} has now been moved to {{ props.newProcess }}.
            </div>
            <div class="mt-4">
                <Link
                    :href="route('order.view', [order.id])"
                    class="btn bg-blue font-bold"
                >Continue</Link>
            </div>
        </Panel>
    </BackendLayout>
</template>

<script setup>
import Panel from '@/Layouts/Shared/Panel.vue';
import BackendLayout from '@/Layouts/BackendLayout.vue';
import { usePage, Head, Link } from '@inertiajs/vue3';

const props = usePage().props;
const order = props.order;

</script>

