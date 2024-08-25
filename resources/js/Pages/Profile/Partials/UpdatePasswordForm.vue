<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Update Password</h2>

            <p class="mt-1 text-sm text-gray-600">
                Ensure your account is using a long, random password to stay secure.
            </p>
        </header>

        <form @submit.prevent="updatePassword" class="mt-6 space-y-6">
            <div>
                <VTextField
                    v-model="form.current_password"
                    id="current_password"
                    label="Current Password"
                    type="password"
                    variant="outlined"
                    :hide-details="form.errors.current_password == undefined"
                    :error-messages="form.errors.current_password"
                    autocomplete="current-password"
                ></VTextField>
            </div>

            <div>
                <VTextField
                    v-model="form.password"
                    id="password"
                    label="New Password"
                    type="password"
                    variant="outlined"
                    :hide-details="form.errors.password == undefined"
                    :error-messages="form.errors.password"
                    autocomplete="new-password"
                ></VTextField>
            </div>

            <div>
                <VTextField
                    v-model="form.password_confirmation"
                    id="password"
                    label="Confirm Password"
                    type="password"
                    variant="outlined"
                    :hide-details="form.errors.password_confirmation == undefined"
                    :error-messages="form.errors.password_confirmation"
                    autocomplete="new-password"
                ></VTextField>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
