<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <GuestLayout>
        <Head title="Password Changed" />

        <div class="mb-4 font-medium text-sm text-green-600">
            Your password has been changed successfully.
        </div>

        <div class="flex items-center justify-end mt-4">
            <Link :href="route('dashboard')">
                <PrimaryButton>
                    Continue to Dashboard
                </PrimaryButton>
            </Link>
        </div>
    </GuestLayout>
</template>
