<template>
   <div class="w-full flex justify-center">
            <div
                class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white overflow-hidden sm:rounded-lg"
            >
            <h3 class="mb-4">{{ prop.snippetTitle }}</h3>
                <slot/>
            </div>
        </div>
</template>

<script setup lang="ts">

const prop = defineProps<{
    snippetTitle?: String
}>();
</script>

