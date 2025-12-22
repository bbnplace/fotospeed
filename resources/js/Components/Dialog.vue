<template>
    <VDialog
        v-model="props.show"
        width="auto"
        persistent
    >
        <VCard
            max-width="400"
            prepend-icon="mdi-delete"
            :title="props.dialogData.title"
        >
            <VCardText>
                <div class="mb-4">{{ props.dialogData.body }}</div>
                
                <div v-if="props.dialogData.puzzle" class="mt-4">
                    <div class="mb-2">Please type <strong>{{ props.dialogData.puzzle }}</strong> to proceed:</div>
                    <VTextField
                        v-model="puzzleInput"
                        density="compact"
                        variant="outlined"
                        placeholder="Type here..."
                        hide-details
                        class="mb-2"
                        @keyup.enter="isMatch && confirmDelete()"
                    ></VTextField>
                </div>
            </VCardText>
            <VCardActions>
                <VSpacer></VSpacer>
                <VBtn
                    color="grey-darken-1"
                    variant="text"
                    @click="cancelDelete"
                >Cancel</VBtn>
                <VBtn
                    :color="props.dialogData.confirmColor || 'primary'"
                    variant="flat"
                    :disabled="!isMatch"
                    @click="confirmDelete"
                >{{ props.dialogData.confirmLabel || 'Yes' }}</VBtn>
            </VCardActions>
        </VCard>
    </VDialog>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue';

interface DialogData {
    title: string;
    body: string;
    puzzle?: string;
    confirmLabel?: string;
    confirmColor?: string;
}

const props = defineProps<{
    dialogData: DialogData,
    show: boolean
}>();

const emit = defineEmits(['deleteConfirmed', 'deleteCancelled']);

const puzzleInput = ref('');

const isMatch = computed(() => {
    if (!props.dialogData.puzzle) return true;
    return puzzleInput.value === props.dialogData.puzzle;
});

// Reset input when dialog shows/hides
watch(() => props.show, (val) => {
    if (!val) {
        puzzleInput.value = '';
    }
});

const confirmDelete = () => {
    if (isMatch.value) {
        emit('deleteConfirmed');
    }
}

const cancelDelete = () => {
    emit('deleteCancelled');
}
</script>
