<template>
    <form @submit.prevent="search">
        <v-card
            class="mx-auto"
            color="surface-light"
        >
            <v-card-text>
                <v-text-field
                    :loading="loading"
                    append-inner-icon="mdi-magnify"
                    density="compact"
                    :label="props.label"
                    variant="solo"
                    hide-details
                    single-line
                    @click:append-inner="onClick"
                    v-model="form.search"
                ></v-text-field>
            </v-card-text>
        </v-card>
    </form>
</template>

<script setup>
import { ref } from  'vue';
import { useForm, usePage } from '@inertiajs/vue3';

const searchPhrase = usePage().props.keyword;

const props = defineProps({
    label: String,
    route: String
});

const loaded = ref(false);
const loading = ref(false);

const onClick = () => {
    loading.value = true;

    setTimeout(()=>{
        loading.value = false;
        loaded.value = true;
    }, 2000);
}

const form = useForm({
    search: searchPhrase ?? ""
})
const search = () => {
    form.get(props.route);
}

</script>

