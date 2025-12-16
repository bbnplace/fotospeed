<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyMobile: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    submitRoute: {
        type: String,
        default: 'profile.update'
    }
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    mobile: user.mobile,
    email: user.email,
});
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Profile Information</h2>

            <p class="mt-1 text-sm text-gray-600">
                Update your account's profile information and mobile.
            </p>
        </header>

        <form @submit.prevent="form.patch(route(submitRoute))" class="mt-6 space-y-6">
            <div>
                <VTextField
                    v-model="form.name"
                    id="name"
                    label="Full Name"
                    type="text"
                    variant="outlined"
                    :hide-details="form.errors.name == undefined"
                    :error-messages="form.errors.name"
                ></VTextField>
            </div>

            <div>
                <VTextField
                    v-model="form.mobile"
                    id="mobile"
                    label="Mobile"
                    type="tel"
                    variant="outlined"
                    :hide-details="form.errors.mobile == undefined"
                    :error-messages="form.errors.mobile"
                ></VTextField>
            </div>

            <div>
                <VTextField
                    v-model="form.email"
                    id="email"
                    label="Email Address"
                    type="email"
                    variant="outlined"
                    :hide-details="form.errors.email == undefined"
                    :error-messages="form.errors.email"
                ></VTextField>
            </div>

            <div v-if="mustVerifyMobile && user.mobile_verified_at === null">
                <p class="text-sm mt-2 text-gray-800">
                    Your mobile number is unverified.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Click here to re-send the verification link.
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 font-medium text-sm text-green-600"
                >
                    A new verification link has been sent to your mobile.
                </div>
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
