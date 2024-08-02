<template>
   <div class="w-full flex justify-center">
            <div
                class="w-full mt-6 px-6 py-3 bg-white overflow-hidden sm:rounded-lg"
            >
            <h3 class="mb-3 text-grey-darken-3">{{ prop.snippetTitle }}</h3>
                <slot/>
            </div>
        </div>
</template>

<script setup lang="ts">

const prop = defineProps<{
    snippetTitle?: String
}>();
</script>

