<script setup>
import BackendLayout from '@/Layouts/BackendLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <BackendLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Restricted</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3>
                            <VIcon siz="30" icon="mdi-lock"></VIcon>
                            Access Restricted</h3>
                        <p class="mt-4 ml-3">
                            Only Administrators can manage:
                        </p>
                        <ul class="ml-3">
                            <li >SMS Templates</li>
                            <li>Email Templates</li>
                            <li>Staff</li>
                            <li>Item Categories</li>
                            <li>Roles</li>
                            <li>Items</li>
                            <li>Processes</li>
                            <li>Branches</li>
                            <li>States</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </BackendLayout>
</template>

<style scoped>
ul {
    list-style: disc !important;
}

ul li {
    line-height: 1.8rem;;
}
</style>
