<template>
    <VDialog
        v-model="props.show"
        width="auto"
    >
        <VCard
            max-width="400"
            prepend-icon="mdi-delete"
            :text="props.dialogData.body"
            :title="props.dialogData.title"
        >
            <VCardActions>
                <VSpacer></VSpacer>
                <VBtn
                    color="primary"
                    text="Cancel"
                    @click="cancelDelete"
                ></VBtn>
                <VBtn
                    class="ms-auto"
                    text="Yes"
                    @click="confirmDelete"
                ></VBtn>
            </VCardActions>
        </VCard>
    </VDialog>
</template>

<script setup lang="ts">
import { ref } from 'vue';

interface DialogData {
    title: String,
    body: String
}

const props = defineProps<{
    dialogData: DialogData,
    show: Boolean
}>();

const emit = defineEmits(['deleteConfirmed', 'deleteCancelled']);

const confirmDelete = () => {
    emit('deleteConfirmed');
}

const cancelDelete = () => {
    emit('deleteCancelled');
}


</script>
