<template>
    <v-autocomplete
        v-model="selection"
        :label="props.label"
        :items="suggestions"
        :variant="variant"
        :no-data-text="noDataText"
        @input="fetchList"
        @change="emitChoice"
        hide-details
        density="compact"
    ></v-autocomplete>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const props = defineProps({
    endpoint: String,
    label: String,
    variant: String,
    noDataText: String
})

const emit = defineEmits(['selected']);

const selection = ref('');
const suggestions = ref([]);

const fetchList = async (event) => {

    if (event.target.value.length < 3) {
        suggestions.value = [];
        return false;
    }

    const payload = {
        keyword: event.target.value
    }

    try {
        const response = await axios.post(props.endpoint, payload, {
            headers: {
                "Content-Type": "application/json"
            }
        });

        if (response.data) {
            suggestions.value = response.data
        }
    } catch (error) {
        
    }
}


const emitChoice = () => {
    emit("selected", {
        selection: selection.value
    })
}

</script>
