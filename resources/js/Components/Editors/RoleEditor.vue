<template>
    <form @submit.prevent="submit">
        <div v-if="role?.protected" class="mb-4">
            <VAlert
                type="info"
                variant="tonal"
                density="compact"
            >
                This is a protected system role and cannot be renamed.
            </VAlert>
        </div>
        
        <div>
            <VTextField
                id="name"
                v-model="form.name"
                label="Role Name"
                variant="outlined"
                autocomplete="name"
                :disabled="role?.protected"
                :hide-details="form.errors.name == undefined"
                :error-messages="form.errors.name"
            ></VTextField>
        </div>

        <div class="flex items-center justify-end mt-4">

            <PrimaryButton 
                class="ms-4" 
                :class="{ 'opacity-25': form.processing || role?.protected }" 
                :disabled="form.processing || role?.protected"
            >
                Save
            </PrimaryButton>
        </div>
    </form>
</template>

<script setup lang="ts">
import { useForm, usePage, Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

interface Role {
    id: Number,
    name: String,
    protected?: boolean
}

const props = defineProps<{
    role?: Role
}>()

const form = useForm({
    id: props.role ? props.role.id : "",
    name: props.role ? props.role.name : "",
});

const submit = () => {
    if(props.role) {
        form.put(route('role.edit', [props.role.id]), {
            onFinish: () => {},
        });
    } else {
        form.post(route('role.add'), {
            onFinish: () => {},
        });
    }
};
</script>

<style lang="scss" scoped>

</style>
