<template>
    <VSnackbar
        v-model="_snackbar.show"
        location="top right"
        multi-line
        max-width="300"
        :timeout="_snackbar.timeout"
    >
      {{ _snackbar.text }}
      <template v-slot:actions>
        <v-btn
          color="blue"
          variant="text"
          @click="_hideSnackbar"
        >
          Close
        </v-btn>
      </template>
    </VSnackbar>
</template>

<script setup lang="ts">
import { reactive, onMounted, watch } from  'vue';
import { usePage } from '@inertiajs/vue3';

interface SnackbarData {
    text: String,
    show: Boolean,
    id: Number
}

const props = defineProps<{
    data: SnackbarData
}>()

const _snackbar = reactive({
    show: props.data.show ?? false,
    text: props.data.text ?? "",
    timeout: 7000,
})

const _showSnackbar = (text) => {
    _snackbar.text = text;
    _snackbar.show = true
}

const _hideSnackbar = () => {
    _snackbar.show = false;
    _snackbar.text = "";
}

watch(()=>props.data.id, () => {
    if(props.data.show) _showSnackbar(props.data.text);
})

onMounted(()=>{
    const status = usePage().props.status;
    if(status)
    {
        _showSnackbar(status)
    }
})
</script>
