<template>
    <VSnackbar
        v-model="_snackbar.show"
        location="top right"
        multi-line
        max-width="300"
        :timeout="_snackbar.timeout"
        :color="_snackbar.color"
    >
      {{ _snackbar.text }}
      <template v-slot:actions>
        <v-btn
          color="white"
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
import { hideSnackbar } from '@/Composables/snackbarOptions.js';

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
    color: 'info',
})

const _showSnackbar = (text, color = 'info') => {
    _snackbar.text = text;
    _snackbar.color = color;
    _snackbar.show = true
}

const _hideSnackbar = () => {
    _snackbar.show = false;
    _snackbar.text = "";
}

watch(()=>props.data.id, () => {
    if(props.data.show) _showSnackbar(props.data.text);
});

watch(()=>_snackbar.show, (newVal, oldVal) => {
    if(!newVal) hideSnackbar();
})

onMounted(()=>{
    const note = usePage().props.note;
    const error = usePage().props.error;
    
    if(error !== null && error !== undefined)
    {
        _showSnackbar(error, 'error')
    }
    else if(note !== null && note !== undefined)
    {
        _showSnackbar(note, 'success')
    }
})
</script>
