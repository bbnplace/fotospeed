<template>
    <form @submit.prevent="submit">
        <div>
            <VTextField
                id="name"
                v-model="form.name"
                label="Branch Name"
                variant="outlined"
                autocomplete="name"
                :hide-details="form.errors.name == undefined"
                :error-messages="form.errors.name"
            ></VTextField>
        </div>
        <div class="mt-3">
            <VTextarea
                id="address"
                v-model="form.address"
                label="Office Address"
                variant="outlined"
                :hide-details="form.errors.address == undefined"
                :error-messages="form.errors.address"
                density="compact"
            ></VTextarea>
        </div>
        <div class="mt-3">
            <VAutocomplete
                id="state"
                label="State"
                v-model="form.state"
                :items="states"
                variant="outlined"
                :hide-details="form.errors.state == undefined"
                :error-messages="form.errors.state"
                density="compact"
            >
            </VAutocomplete>
        </div>
        <div class="mt-3">
            
            <VCombobox
                id="contacts"
                chips
                multiple
                v-model="form.contacts"
                label="Phone Numbers"
                variant="outlined"
                :items="[]"
                :hide-details="form.errors.contacts == undefined"
                :error-messages="form.errors.contacts"
            ></VCombobox>
        </div>
        <div class="mt-3">
            <v-checkbox
                v-model="form.isAdministrative"
                label="Is Administrative Branch"
                :hide-details="form.errors.isAdministrative == undefined"
                :error-messages="form.errors.isAdministrative"
            ></v-checkbox>
        </div>
        <hr />
        <div class="flex items-center justify-end mt-2">
            <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Save
            </PrimaryButton>
        </div>
    </form>
</template>

<script setup lang="ts">
import { useForm, usePage, Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { VCombobox } from 'vuetify/lib/components/index.mjs';

interface Branch {
    id: Number,
    name: String,
    address: String,
    is_administrative: Boolean,
    state: {
        id: Number,
        name: String
    },
    contacts: String[]
}

const props = defineProps<{
    branch?: Branch
}>()

const states = usePage().props.states;

const form = useForm({
    id: props.branch ? props.branch.id : "",
    name: props.branch ? props.branch.name : "",
    address: props.branch ? props.branch.address : "",
    state: props.branch ? props.branch.state.name : "",
    isAdministrative: props.branch ? props.branch.is_administrative == 1 : false,
    contacts: props.branch && props.branch.contacts ? props.branch.contacts : [],
});

const submit = () => {
    if(props.branch) {
        form.put(route('branch.edit', [props.branch.id]), {
            onFinish: () => {},
        });
    } else {
        form.post(route('branch.add'), {
            onFinish: () => {},
        });
    }
};
</script>

<style lang="scss" scoped>

</style>
