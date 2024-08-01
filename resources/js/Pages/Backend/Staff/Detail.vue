<template>
    <Head title="Staff"></Head>
    <BackendLayout>
        <Link :href="route('staff')" class="font-bold">Back</Link>
        <VRow>
            <VCol cols="12" md="7">
                <Panel :snippet-title="staff.name">
                    <VRow>
                        <VCol cols="6"><b>Staff Name</b><br />{{ staff.name }}</VCol>
                        <VCol cols="6"><b>Department</b><br />{{ staff.role.name }}</VCol>
                        
                    </VRow>
                    <VRow>
                        <VCol cols="6"><b>Email</b><br />{{ staff.email }}</VCol>
                        <VCol cols="6"><b>Mobile</b><br />{{ staff.mobile }}</VCol>
                    </VRow>
                    <VRow>
                        <VCol cols="6"><b>Branch</b><br />{{ staff.branch.name }}</VCol>
                        <VCol cols="6"><b>State</b><br />{{ staff.state.name }}</VCol>
                    </VRow>
                    <hr />
                    <div class="mt-3 text-right">
                        <Link :href="route('staff.edit', staff.id)" class="btn btn-dark">Modify</Link>
                    </div>
                </Panel>
            </VCol>
            <VCol cols="12" md="5">
                <Panel snippet-title="Recent Logins">
                    <VTable  v-if="logins.length">
                        <THead>
                            <tr>
                                <td>Date</td>
                                <td>IP Address</td>
                            </tr>
                        </THead>
                        <TBody>
                            <tr v-for="login in logins" :key="login.id">
                                <td>{{ moment(login.created_at).format('MM/DD/YYYY, h:mm A') }}</td>
                                <td>{{ login.ip_address }}</td>
                            </tr>
                        </TBody>
                    </VTable>
                    <div v-else>No login record</div>
                </Panel>
            </VCol>
        </VRow>
    </BackendLayout>
</template>

<script setup>
import { usePage, Head, Link } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Panel from "@/Layouts/Shared/Panel.vue";
import moment from 'moment';

const staff = usePage().props.staff;
const logins = usePage().props.logins;
</script>
