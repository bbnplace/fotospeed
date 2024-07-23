<template>
    <Head title="Dashboard" />

    <BackendLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        
        <div class="d-flex">
            <v-menu
            transition="slide-y-transition"
            >
                <template v-slot:activator="{ props }">
                    <v-btn
                        color="light"
                        v-bind="props"
                    >
                    {{ periods[key] }}  <v-icon>mdi-chevron-down</v-icon>
                    </v-btn>
                </template>

                <v-list>
                    <v-list-item v-for="(item, i) in items" :key="i">
                        <v-list-item-title>
                            <Link :href="`${route(reportRequester, item.slug)}`">{{ item.title }}</Link>
                        </v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-menu>
            
            <div class="d-flex ml-5" v-if="key == 'custom'">
                <VueDatePicker
                    placeholder="Start Date"
                    v-model="form.start"
                    :min-date="minDate"
                    :is-24="false">
                </VueDatePicker>
                <VueDatePicker
                    placeholder="Stop Date"
                    v-model="form.stop"
                    :max-date="new Date()"
                    :is-24="false">
                </VueDatePicker>
                <VBtn
                    class="ml-1"
                    variant="flat"
                    color="grey-darken-3"
                    @click="submitForm"
                >
                    Fetch
                </VBtn>
            </div>
        </div>
        
        <Panel snippetTitle="Report Chart" class="chart-container">
            <LineChart :data="chartData" />
        </Panel>
        <Panel :snippetTitle="`${key} Records`">
            <static-records :data="records"></static-records>
        </Panel>
    </BackendLayout>
</template>

<script setup>
import BackendLayout from '@/Layouts/BackendLayout.vue';
import Panel from '@/Layouts/Shared/Panel.vue';
import { Head, Link, usePage, useForm } from '@inertiajs/vue3';
import LineChart from '@/Components/LineChart.vue';
import StaticRecords from '@/Components/StaticRecords.vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import Records from  '@/Components/Records.vue';
import '@vuepic/vue-datepicker/dist/main.css';
import axios from 'axios';
import moment from 'moment';

const props = usePage().props[0] ?? usePage().props;
const key = props.key;
const chartData = props.reports[key];
const periods = props.periods;
const minDate = props.startDate;
const reportRequester = props.clientRoute;
const reportables = props.reportables;

const header = [];
for (let index = 0; index < reportables.length; index++) {
    const element = reportables[index];
    header.push({
        title: element,
        key: element,
        sortable: true
    })
}

const records = {
    records: props.records,
    search: "",
    title: `${key} Report`,
    header: header
}



const items = [];
for (const key in periods) {
    items.push({
        title: periods[key],
        slug: key
    });
}

const url = new URL(window.location.href);
const form = useForm({
    start: key == 'custom' ? url.searchParams.get('start') : "",
    stop: url.searchParams.get('stop') ?? new Date()
})

const submitForm = () => {

}



</script>

<style scoped>
.chart-container {
  width: 100%;
  height: 400px;
}
</style>