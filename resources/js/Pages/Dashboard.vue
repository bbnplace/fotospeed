<template>
    <Head title="Dashboard" />

    <BackendLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3>Reports</h3>
                        <p>Not enough data to generate report.</p>
                    </div>
                </div>
            </div>
        </div>
        <Panel snippetTitle="Report Chart" class="chart-container">
            <LineChart />
        </Panel>
    </BackendLayout>
</template>

<script setup>
import BackendLayout from '@/Layouts/BackendLayout.vue';
import Panel from '@/Layouts/Shared/Panel.vue'
import { Head } from '@inertiajs/vue3';
import LineChart from '@/Components/LineChart.vue'

</script>

<style scoped>
.chart-container {
  width: 100%;
  height: 400px;
}
</style>