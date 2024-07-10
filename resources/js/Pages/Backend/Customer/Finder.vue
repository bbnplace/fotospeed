<template>
    <Head title="Customer"></Head>
    <BackendLayout>
        <Panel snippetTitle="Find Customer">
            <div class="d-flex mb-6t">
            <v-text-field
                v-model="data.keyphrase"
                prepend-inner-icon="mdi-magnify"
                hide-details
                type="text"
                class="ma-2"
                density="compact"
                variant="outlined"
            ></v-text-field>
            <v-sheet class="ma-2 d-none d-sm-flex">
                <VBtn text="Search"></VBtn>
            </v-sheet>
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

const data = reactive({
    keyphrase: ""
})

const submitForm = () => {

}
</script>

<style lang="scss" scoped>

</style>
