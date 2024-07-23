<template>
  <v-card
    
    flat
  >
    <template v-slot:text>
      <v-text-field
        v-model="form.search"
        label="Filter"
        prepend-inner-icon="mdi-magnify"
        variant="outlined"
        hide-details
        single-line
      ></v-text-field>
    </template>

    <v-data-table
      :headers="headers"
      :items="records"
      :search="form.search"
    ></v-data-table>
  </v-card>
</template>

<script setup>
import { reactive } from 'vue'
const props = defineProps({
    data: Object
})

const data = props.data;

const records = data.records;
const title = data.title;
const header = data.header;

const form = reactive({
    search: ""
})
</script>