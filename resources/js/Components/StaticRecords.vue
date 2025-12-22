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
      v-if="headers.length"
      :headers="headers"
      :items="records"
      :search="form.search"
      :items-per-page="10"
      item-value="id"
    ></v-data-table>
  </v-card>
</template>

<script setup>
import { reactive, computed } from 'vue'
const props = defineProps({
    data: Object
})

const records = computed(() => props.data?.records || [])
const headers = computed(() => props.data?.header || [])

const form = reactive({
    search: ""
})
</script>