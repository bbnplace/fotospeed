<template>
    <Head title="Process"></Head>
    <BackendLayout>
        <Link :href="route('processes')" class="font-bold">Back</Link>
        <Panel :snippet-title="`${process.name} Process`">
            <div class="grid grid-cols-4 gap-4 mb-4">
                <div class="text-center shadow p-3">
                    <div class="text-6xl">
                        {{ process.unclaimed_count }}
                    </div>
                    <div>Unclaimed</div>
                </div>
                <div class="text-center shadow p-3">
                    <div class="text-6xl">
                        {{ process.todo_count }}
                    </div>
                    <div>Todo</div>
                </div>
                <div class="text-center shadow p-3">
                    <div class="text-6xl">
                        {{ process.doing_count }}
                    </div>
                    <div>Doing</div>
                </div>
                <div class="text-center shadow p-3">
                    <div class="text-6xl">
                        {{ process.done_count }}
                    </div>
                    <div>Done</div>
                </div>
            </div>
            <VRow>
                <VCol><b>Description</b><br />{{ process.description ?? '-' }}</VCol>
            </VRow>
            <div class="mt-3">
                <Link :href="route('process.edit', process.id)" class="btn btn-dark">Modify</Link>
            </div>
        </Panel>

        <div class="mt-3">
            <Records :data="dataResources"></Records>
        </div>
    </BackendLayout>
</template>

<script setup>
import { usePage, Head, Link } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Panel from "@/Layouts/Shared/Panel.vue";
import Records from  '@/Components/Records.vue';

const process = usePage().props.process;

const dataResources = {
    endpoint: {
        records: usePage().props.ordersEndpoint,
        detail: "order.view",
        add: "process.add",
        edit: "process.edit",
        // delete: "processes.delete",
    },
    addLabel: "Add Process",
    headers: [
        {
            title: "Order #",
            key: "order_number",
            sortable: true
        },
        {
            title: "Order Name",
            key: "name",
            sortable: true
        },
        {
            title: "Product",
            key: "item.name",
            sortable: true
        },
        {
            title: "Tasks Count",
            key: "task_count",
            sortable: true
        },
        {
            title: "Status",
            key: "task_statuses",
            sortable: false
        },
        {
            title: "Last Updated",
            key: "updated_at",
            sortable: false,
            align: "end"
        }
    ],
    name: {
        singular: "Order",
        plural: "Orders"
    }
}
</script>
