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
            <static-records :data="recordsData"></static-records>
        </Panel>
    </BackendLayout>
</template>

<script setup>
import { computed, reactive, ref } from 'vue';
import BackendLayout from '@/Layouts/BackendLayout.vue';
import Panel from '@/Layouts/Shared/Panel.vue';
import { Head, Link, usePage, useForm } from '@inertiajs/vue3';
import LineChart from '@/Components/LineChart.vue';
import StaticRecords from '@/Components/StaticRecords.vue';
import VueDatePicker from '@vuepic/vue-datepicker';
// import Records from  '@/Components/Records.vue';
import '@vuepic/vue-datepicker/dist/main.css';
import axios from 'axios';
import moment from 'moment';

const props = computed(() => usePage().props[0] ?? usePage().props);
const key = computed(() => props.value.key);
const chartData = computed(() => props.value.reports[key.value]);
const periods = computed(() => props.value.periods);
const minDate = computed(() => props.value.startDate);
const reportRequester = computed(() => props.value.clientRoute);
const reportables = computed(() => props.value.reportables);

const user = computed(() => usePage().props.auth.user);
const userEmail = computed(() => user.value?.email);
const isEndUser = computed(() => user.value?.role?.name === 'Customer');

const headers = computed(() => {
    const header = [];
    
    // Determine time column from first record
    let timeKey = 'date';
    let timeTitle = 'Date';

    if (props.value.records && props.value.records.length > 0) {
        const firstRecord = props.value.records[0];
        if ('hour' in firstRecord) {
            timeKey = 'hour';
            timeTitle = 'Time';
        } else if ('month' in firstRecord) {
            timeKey = 'month';
            timeTitle = 'Month';
        } else if ('year' in firstRecord) {
            timeKey = 'year';
            timeTitle = 'Year';
        }
    } else {
        // Fallback based on key prop if no records
        if (key.value === '24hrs') {
            timeKey = 'hour';
            timeTitle = 'Time';
        }
    }

    // Add Time/Date column first
    header.push({
        title: timeTitle,
        key: timeKey,
        sortable: true
    });

    if (reportables.value) {
        for (let index = 0; index < reportables.value.length; index++) {
            const element = reportables.value[index];
            const title = element.replace('_', ' ').replace(/\b\w/g, function (char) {
                return char.toUpperCase();
            }); 
            header.push({
                title: title,
                key: element,
                sortable: true
            })
        }
    }
    
    return header;
});

const recordsData = computed(() => ({
    records: props.value.records,
    search: "",
    title: `${key.value} Report`,
    header: headers.value
}))

// console.log(records)



const items = computed(() => {
    const list = [];
    if (periods.value) {
        for (const k in periods.value) {
            list.push({
                title: periods.value[k],
                slug: k
            });
        }
    }
    return list;
});

const url = new URL(window.location.href);
const form = useForm({
    start: key.value == 'custom' ? url.searchParams.get('start') : "",
    stop: url.searchParams.get('stop') ?? new Date()
})

// Submiting Search Field
const submitForm = () => {
    form.get(isEndUser.value ? route(reportRequester.value, key.value) : route(reportRequester.value, {
        email: userEmail.value,
        ref: key.value
    }));
}

// Exporting Reports to File
const exportReport = async () => {
    const payload = {
        period: key.value,
        start: form.start,
        stop: form.stop,
    }

    // Assuming reportProps or similar is defined elsewhere or should be from props
    const exportEndpoint = props.value.exportEndpoint;

    const response = await axios.post(exportEndpoint, payload, {
        headers: {
            "Content-Type": "application/json"
        }
    });

    showSnackbar(response.data.message);
}

const snackbarText = ref('');
const snackbar = ref(false);

const showSnackbar = text => {
    snackbarText.value = text;
    snackbar.value = true;
}

</script>

<style scoped>
.chart-container {
  width: 100%;
  height: 400px;
}
</style>