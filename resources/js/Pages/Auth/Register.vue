<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link} from '@inertiajs/vue3';
import UserEditor from '@/Components/Editors/UserEditor.vue';

</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <UserEditor></UserEditor>

        <Link
            :href="route('login')"
            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
            Already registered?
        </Link>
    </GuestLayout>
</template>
