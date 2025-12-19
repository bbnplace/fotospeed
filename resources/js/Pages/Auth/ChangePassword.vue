<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const showPassword = ref(false);

const form = useForm({
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.put(route('password.update'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Change Password" />

        <div class="mb-4 text-sm text-gray-600">
            This is a temporary password. Please choose a new password to continue.
        </div>

        <form @submit.prevent="submit">
            <div>
                <VTextField
                    id="password"
                    :type="showPassword ? 'text' : 'password'"
                    v-model="form.password"
                    label="New Password"
                    variant="outlined"
                    autocomplete="new-password"
                    :hide-details="form.errors.password == undefined"
                    :error-messages="form.errors.password"
                    prepend-inner-icon="mdi-lock"
                    :append-inner-icon="showPassword ? 'mdi-eye-off' : 'mdi-eye'"
                    @click:append-inner="showPassword = !showPassword"
                ></VTextField>
            </div>

            <div class="mt-4">
                <VTextField
                    id="password_confirmation"
                    :type="showPassword ? 'text' : 'password'"
                    v-model="form.password_confirmation"
                    label="Confirm Password"
                    variant="outlined"
                    autocomplete="new-password"
                    :hide-details="form.errors.password_confirmation == undefined"
                    :error-messages="form.errors.password_confirmation"
                    prepend-inner-icon="mdi-lock"
                ></VTextField>
            </div>

            <div class="flex items-center justify-end mt-4">
                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Change Password
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
