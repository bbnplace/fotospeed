<template>
    <v-dialog
        v-model="isActive"
        persistent
        max-width="600"
        transition="dialog-bottom-transition"
    >
        <v-card class="rounded-xl overflow-hidden branded-card">
            <v-card-item class="pa-0">
                <div class="branded-header d-flex align-center justify-space-between px-6 py-4">
                    <div class="d-flex align-center">
                        <v-icon icon="mdi-alert-circle-outline" color="white" class="me-3"></v-icon>
                        <span class="text-h6 font-weight-bold text-white">Report an Issue</span>
                    </div>
                    <v-btn
                        icon="mdi-close"
                        variant="text"
                        color="white"
                        density="comfortable"
                        @click="close"
                    ></v-btn>
                </div>
            </v-card-item>

            <v-card-text class="pa-8">
                <div v-if="submitted" class="text-center py-10">
                    <div class="success-icon-wrapper mb-6">
                        <v-icon icon="mdi-check-circle" color="success" size="80"></v-icon>
                    </div>
                    <h3 class="text-h5 font-weight-bold mb-3 text-grey-darken-3">Issue Submitted!</h3>
                    <p class="text-body-1 text-grey-darken-1 leading-relaxed px-4">
                        {{ responseMessage }}
                    </p>
                    <v-btn
                        color="primary"
                        variant="flat"
                        class="mt-8 px-10 rounded-pill font-weight-bold"
                        elevation="2"
                        @click="close"
                    >
                        Close
                    </v-btn>
                </div>

                <v-form v-else @submit.prevent="submitForm">
                    <div class="mb-6">
                        <label class="d-block text-subtitle-2 font-weight-bold mb-2 text-grey-darken-2">Issue Title</label>
                        <v-text-field
                            v-model="form.title"
                            placeholder="A suitable title for the issue"
                            variant="outlined"
                            density="comfortable"
                            :error-messages="form.errors.title"
                            hide-details="auto"
                            required
                            class="custom-field"
                        ></v-text-field>
                    </div>

                    <div class="mb-6">
                        <label class="d-block text-subtitle-2 font-weight-bold mb-2 text-grey-darken-2">Complaint Details</label>
                        <v-textarea
                            v-model="form.complaint"
                            placeholder="Tell us more about what went wrong and how to replicate the error..."
                            variant="outlined"
                            density="comfortable"
                            rows="4"
                            hide-details="auto"
                            :error-messages="form.errors.complaint"
                            required
                            class="custom-field"
                        ></v-textarea>
                    </div>

                    <div class="mb-8">
                        <label class="d-block text-subtitle-2 font-weight-bold mb-2 text-grey-darken-2">Upload Screenshot (Optional)</label>
                        <v-file-input
                            v-model="form.screenshot"
                            placeholder="Select an image"
                            variant="outlined"
                            density="comfortable"
                            prepend-inner-icon="mdi-camera"
                            prepend-icon=""
                            accept="image/*"
                            :error-messages="form.errors.screenshot"
                            show-size
                            hide-details="auto"
                            class="custom-field"
                        ></v-file-input>
                    </div>

                    <div class="d-flex justify-end gap-3 mt-4">
                        <v-btn
                            variant="text"
                            @click="close"
                            class="rounded-pill px-6 font-weight-bold"
                            color="grey-darken-1"
                            :disabled="form.processing"
                        >
                            Cancel
                        </v-btn>
                        <v-btn
                            color="primary"
                            variant="flat"
                            type="submit"
                            class="px-10 rounded-pill font-weight-bold"
                            elevation="2"
                            :loading="form.processing"
                        >
                            Submit Report
                        </v-btn>
                    </div>
                </v-form>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script setup>
import { computed, ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps({
    modelValue: {
        type: Boolean,
        required: true
    }
});

const emit = defineEmits(['update:modelValue']);

const isActive = computed({
    get: () => props.modelValue,
    set: (value) => emit('update:modelValue', value)
});

const submitted = ref(false);
const responseMessage = ref('');

const form = useForm({
    title: '',
    complaint: '',
    screenshot: null,
});

const submitForm = async () => {
    form.clearErrors();
    form.processing = true;

    const formData = new FormData();
    formData.append('title', form.title);
    formData.append('complaint', form.complaint);
    if (form.screenshot) {
        // Vuetify v-file-input returns an array
        const file = Array.isArray(form.screenshot) ? form.screenshot[0] : form.screenshot;
        formData.append('screenshot', file);
    }

    try {
        const response = await axios.post(route('report-issue.store'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        if (response.data.status === 'success') {
            submitted.value = true;
            responseMessage.value = response.data.message;
            form.reset();
        }
    } catch (error) {
        if (error.response && error.response.data.errors) {
            form.setError(error.response.data.errors);
        } else {
            console.error('Submission error:', error);
        }
    } finally {
        form.processing = false;
    }
};

const close = () => {
    isActive.value = false;
    setTimeout(() => {
        if (submitted.value) {
            submitted.value = false;
        }
        form.reset();
        form.clearErrors();
    }, 300);
};

watch(isActive, (newVal) => {
    if (newVal) {
        submitted.value = false;
    }
});
</script>

<style scoped>
.branded-card {
    font-family: 'Figtree', sans-serif !important;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2) !important;
}

.branded-header {
    background: #212529;
}

.leading-relaxed {
    line-height: 1.7;
}

.gap-3 {
    gap: 0.75rem;
}

.custom-field :deep(.v-field__outline) {
    --v-field-border-opacity: 0.15;
}

.custom-field :deep(.v-field--focused .v-field__outline) {
    --v-field-border-opacity: 1;
    color: #ff2500;
}

.success-icon-wrapper {
    display: inline-flex;
    padding: 20px;
    background: #f0fdf4;
    border-radius: 50%;
}
</style>
