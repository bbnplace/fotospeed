<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const showPassword = ref(false);

const form = useForm({
    mobile: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <div>
                <VTextField
                    id="mobile"
                    v-model="form.mobile"
                    label="Mobile Number"
                    variant="outlined"
                    autocomplete="phone"
                    :hide-details="form.errors.mobile == undefined"
                    :error-messages="form.errors.mobile"
                    prepend-inner-icon="mdi-cellphone"
                ></VTextField>
            </div>
            </div>

            <div class="mt-3">
                <VTextField
                    id="password"
                    :type="showPassword ? 'text' : 'password'"
                    v-model="form.password"
                    label="Password"
                    variant="outlined"
                    autocomplete="current-password"
                    :hide-details="form.errors.password == undefined"
                    :error-messages="form.errors.password"
                    prepend-inner-icon="mdi-lock"
                    :append-inner-icon="showPassword ? 'mdi-eye-off' : 'mdi-eye'"
                    @click:append-inner="showPassword = !showPassword"
                ></VTextField>
            </div>

            <div class="block">
                <VCheckbox
                    v-model:checked="form.remember"
                    label="Remember Me"
                    id="remember"
                    name="remember"
                ></VCheckbox>
            </div>

            <div class="flex items-center justify-end">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Forgot your password?
                </Link>

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Log in
                </PrimaryButton>
            </div>
            <div class="mt-4">
                Don't have an account?
                <a
                    :href="route('signup')"
                    class="ms-4 underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Submit Request
                </a>
            </div>
        </form>
    </GuestLayout>
</template>
