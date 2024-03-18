<template>
    <form @submit.prevent="submit">
        <div>
            <VTextField
                id="name"
                v-model="form.name"
                label="Group Name"
                variant="outlined"
                autocomplete="name"
                :hide-details="form.errors.name == undefined"
                :error-messages="form.errors.name"
            ></VTextField>
        </div>
        <div class="mt-4">
            <VTextarea
                id="description"
                v-model="form.description"
                label="Description (Optional)"
                variant="outlined"
                :hide-details="form.errors.description == undefined"
                :error-messages="form.errors.description"
                density="compact"
            ></VTextarea>
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

interface Group {
    id: Number,
    name: String,
    description: String
}

const props = defineProps<{
    group?: Group
}>()


const form = useForm({
    id: props.group ? props.group.id : "",
    name: props.group ? props.group.name : "",
    state: props.group ? props.group.description : "",
});

const submit = () => {
    form.post(route('group.add'), {
        onFinish: () => {},
    });
};
</script>

<style lang="scss" scoped>

</style>
