<template></template>
<script setup>
import { usePage } from '@inertiajs/vue3';
const props = usePage().props;

window.location.href = route('customer.new-order', {
    product: props.product
});
</script>