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
                        <v-icon icon="mdi-information-outline" color="white" class="me-3"></v-icon>
                        <span class="text-h6 font-weight-bold text-white">About Flow Builder</span>
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
                <div class="text-center mb-8">
                    <div class="brand-logo mb-2">
                        <span class="text-h3 font-weight-black tracking-tighter">
                            <span class="color-primary">FLOW</span>
                            <span class="text-grey-darken-3">BUILDER</span>
                        </span>
                    </div>
                    <v-chip
                        color="secondary"
                        variant="flat"
                        size="small"
                        class="px-4 font-weight-bold"
                    >
                        VERSION 1.2.0
                    </v-chip>
                </div>

                <v-divider class="mb-8"></v-divider>

                <div class="content-sections">
                    <section class="mb-6">
                        <h4 class="text-subtitle-1 font-weight-bold mb-3 d-flex align-center color-primary">
                            <v-icon icon="mdi-rocket-launch" size="small" class="me-2"></v-icon>
                            Overview
                        </h4>
                        <p class="text-body-2 text-grey-darken-1 leading-relaxed">
                            Cecula Flow Builder is a powerful orchestration engine designed to streamline complex business workflows. 
                            It enables users to design, deploy, and manage automated processes with ease and precision.
                        </p>
                    </section>

                    <section>
                        <h4 class="text-subtitle-1 font-weight-bold mb-3 d-flex align-center color-primary">
                            <v-icon icon="mdi-shield-check" size="small" class="me-2"></v-icon>
                            Core Features
                        </h4>
                        <div class="features-grid">
                            <div v-for="(feature, i) in features" :key="i" class="feature-item d-flex align-start mb-2">
                                <v-icon icon="mdi-check-circle" color="success" size="18" class="me-2 mt-1"></v-icon>
                                <span class="text-body-2 text-grey-darken-1">{{ feature }}</span>
                            </div>
                        </div>
                    </section>
                </div>
            </v-card-text>

            <v-divider></v-divider>

            <v-card-actions class="pa-6 bg-grey-lighten-5">
                <div class="text-caption text-grey-darken-1 font-weight-medium">
                    &copy; {{ new Date().getFullYear() }} Cecula Limited.
                </div>
                <v-spacer></v-spacer>
                <v-btn
                    color="primary"
                    variant="flat"
                    @click="close"
                    class="px-8 rounded-pill font-weight-bold"
                    elevation="2"
                >
                    Dismiss
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script setup>
import { computed } from 'vue';

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

const features = [
    'Real-time visual workflow designer',
    'Advanced task orchestration & state management',
    'Deep integration with Cecula messaging services',
    'Robust audit logging and performance monitoring'
];

const close = () => {
    isActive.value = false;
};
</script>

<style scoped>
.branded-card {
    font-family: 'Figtree', sans-serif !important;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2) !important;
}

.branded-header {
    background: #212529;
}

.color-primary {
    color: #ff2500;
}

.tracking-tighter {
    letter-spacing: -0.05em;
}

.leading-relaxed {
    line-height: 1.7;
}

.feature-item {
    transition: transform 0.2s ease;
}

.feature-item:hover {
    transform: translateX(5px);
}

.bg-grey-lighten-5 {
    background-color: #f9f9fb !important;
}
</style>
