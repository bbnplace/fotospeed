<template>
    <form @submit.prevent="submit">
        <div>
            <VTextField
                id="name"
                v-model="form.name"
                label="Category Name"
                variant="outlined"
                autocomplete="name"
                :hide-details="form.errors.name == undefined"
                :error-messages="form.errors.name"
            ></VTextField>
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

interface Category {
    id: Number,
    name: String
}

const props = defineProps<{
    category?: Category
}>()

const form = useForm({
    id: props.category ? props.category.id : "",
    name: props.category ? props.category.name : "",
});

const submit = () => {
    form.post(route('category.add'), {
        onFinish: () => {},
    });
};
</script>

<style lang="scss" scoped>

</style>
