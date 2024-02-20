<template>
    <Head title="Create Order"></Head>
    <BackendLayout>
        Create Order
        <DropzoneUploader @fileUploaded="handleData"></DropzoneUploader>
        {{ orderForm }}
    </BackendLayout>
</template>

<script setup>
import { reactive } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import BackendLayout from '@/Layouts/BackendLayout.vue';
import DropzoneUploader from '@/Components/DropzoneUploader.vue';

const orderForm = reactive({
    orderFiles: []
})

const handleData = data => {
    orderForm.orderFiles.push(data);
}
</script>

