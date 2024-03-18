<template>
    <form @submit.prevent="submit">
        <div>
            <VTextField
                id="name"
                v-model="form.name"
                label="Branch Name"
                variant="outlined"
                autocomplete="name"
                :hide-details="form.errors.name == undefined"
                :error-messages="form.errors.name"
            ></VTextField>
        </div>
        <div class="mt-4">
            <VAutocomplete
                id="state"
                label="State"
                v-model="form.state"
                :items="states"
                variant="outlined"
                :hide-details="form.errors.state == undefined"
                :error-messages="form.errors.state"
                density="compact"
            >
            </VAutocomplete>
        </div>

        <div class="flex items-center justify-end mt-4">

            <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Save
            </PrimaryButton>
        </div>
    </form>
</template>

<script setup lang="ts">
import { useForm, usePage, Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

interface Branch {
    id: Number,
    name: String,
    state: String
}

const props = defineProps<{
    branch?: Branch
}>()

const states = usePage().props.states;

const form = useForm({
    id: props.branch ? props.branch.id : "",
    name: props.branch ? props.branch.name : "",
    state: props.branch ? props.branch.state.name : "",
});

const submit = () => {
    form.post(route('branch.add'), {
        onFinish: () => {},
    });
};
</script>

<style lang="scss" scoped>

</style>
