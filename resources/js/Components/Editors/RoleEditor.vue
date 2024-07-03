<template>
    <form @submit.prevent="submit">
        <div>
            <VTextField
                id="name"
                v-model="form.name"
                label="Role Name"
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

interface Role {
    id: Number,
    name: String
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
