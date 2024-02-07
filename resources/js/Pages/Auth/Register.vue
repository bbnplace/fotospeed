<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

const states = usePage().props.states;
console.log(usePage().props)

const form = useForm({
    name: '',
    mobile: '',
    email: '',
    password: '',
    password_confirmation: '',
    state: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <form @submit.prevent="submit">
            <div>
                <VTextField
                    id="name"
                    v-model="form.name"
                    label="Full Name"
                    variant="outlined"
                    autocomplete="name"
                    :hide-details="form.errors.name == undefined"
                    :error-messages="form.errors.name"
                    append-inner-icon="mdi-account"
                ></VTextField>
            </div>

            <div class="mt-4">
                <VTextField
                    id="mobile"
                    v-model="form.mobile"
                    label="Mobile Number"
                    variant="outlined"
                    autocomplete="tel"
                    :hide-details="form.errors.mobile == undefined"
                    :error-messages="form.errors.mobile"
                    append-inner-icon="mdi-cellphone"
                ></VTextField>
            </div>

            <div class="mt-4">
                <VTextField
                    id="email"
                    v-model="form.email"
                    label="Email"
                    variant="outlined"
                    autocomplete="off"
                    :hide-details="form.errors.email == undefined"
                    :error-messages="form.errors.email"
                    append-inner-icon="mdi-email"
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
            <VDivider class="border-opacity-75 my-8"></VDivider>
            <div class="mt-4">
                <VTextField
                    id="password"
                    type="password"
                    v-model="form.password"
                    label="Password"
                    variant="outlined"
                    autocomplete="new-password"
                    :hide-details="form.errors.password == undefined"
                    :error-messages="form.errors.password"
                    append-inner-icon="mdi-lock"
                ></VTextField>
            </div>

            <div class="mt-4">
                <VTextField
                    id="password_confirmation"
                    type="password"
                    v-model="form.password_confirmation"
                    label="Confirm Password"
                    variant="outlined"
                    autocomplete="new-password"
                    :hide-details="form.errors.password_confirmation == undefined"
                    :error-messages="form.errors.password_confirmation"
                    append-inner-icon="mdi-lock"
                ></VTextField>
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link
                    :href="route('login')"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Already registered?
                </Link>

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Register
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
