<template>
    <v-autocomplete
        v-model="selection"
        :label="props.label"
        :items="suggestions"
        :variant="variant"
        :no-data-text="noDataText"
        item-value="id"
        item-title="name"
        return-object
        @input="fetchList"
        @update:modelValue="emitChoice"
        hide-details
        density="compact"
        class="autocomplete"
    ></v-autocomplete>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const props = defineProps({
    endpoint: String,
    label: String,
    variant: String,
    noDataText: String,
    params: {
        type: Object,
        default: () => ({})
    }
})

const emit = defineEmits(['selected']);

const selection = ref('');
const suggestions = ref([]);

onMounted(() => {
    fetchList({ target: { value: '' } });
});

const fetchList = async (event) => {

    // if (event.target.value.length < 3) {
    //     suggestions.value = [];
    //     return false;
    // }

    const payload = {
        keyword: event.target.value,
        ...props.params
    }

    try {
        const response = await axios.post(props.endpoint, payload, {
            headers: {
                "Content-Type": "application/json"
            }
        });

        if (response.data) {
            suggestions.value = response.data.map(item => ({
                id: item.id,
                name: item.name + (item.branch ? ' - ' + item.branch.name : '')
            }))
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
