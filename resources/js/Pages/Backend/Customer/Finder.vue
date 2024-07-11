<template>
    <Head title="Customer"></Head>
    <BackendLayout>
        <Panel snippetTitle="Find Customer">
            <form @submit.prevent="findCustomer">
                <div class="d-flex mb-6t">
                    <v-text-field
                        v-model="data.keyphrase"
                        prepend-inner-icon="mdi-magnify"
                        hide-details
                        type="text"
                        class="ma-2"
                        density="compact"
                        variant="outlined"
                        :loading="loading"
                    ></v-text-field>
                    <v-sheet class="ma-2 d-none d-sm-flex">
                        <VBtn text="Search" @click="findCustomer"></VBtn>
                    </v-sheet>
                </div>
            </form>
        </Panel>

        <Panel v-if="data.customer.name">
            <VRow>
                <VCol cols="6"><b>Customer Name</b><br />{{ data.customer.name }}</VCol>
                <VCol cols="6"><b>State</b><br />{{ data.customer.state.name }}</VCol>
            </VRow>
            <VRow>
                <VCol cols="6"><b>Email</b><br />{{ data.customer.email }}</VCol>
                <VCol cols="6"><b>Mobile</b><br />{{ data.customer.mobile }}</VCol>
            </VRow>
            <div class="mt-3">
                <Link :href="route('customer.edit', data.customer.id)" class="btn btn-secondary">Modify</Link>
            </div>
        </Panel>

    </BackendLayout>
</template>

<script setup>
import { reactive } from "vue";
import { usePage, Head, Link } from "@inertiajs/vue3";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import Panel from "@/Layouts/Shared/Panel.vue";
import axios from "axios";

let loading = false;
const data = reactive({
    keyphrase: "",
    customer: {}
})

let source = null;
const findCustomer = async () => {
    const payload = {
        keyphrase: data.keyphrase
    }

    loading = true;

    if(source) source.cancel('Request cancelled by user');
    source = axios.CancelToken.source();
    const response = await axios.post(usePage().props.endpoint, payload, {
        headers: {
            "Content-Type": "application/json"
        },
        cancelToken: source.token
    });

    data.customer = response.data.customer
    loading = false;
}

const submitForm = () => {

}
</script>

<style lang="scss" scoped>

</style>
