<template>
    <div>
        <form @submit.prevent="submit">
            <VRow>
                <VCol cols="12" sm="6">
                    <VTextField
                        id="name"
                        v-model="form.name"
                        label="Name"
                        variant="outlined"
                        :hide-details="form.errors.name == undefined"
                        :error-messages="form.errors.name"
                    ></VTextField>
                </VCol>
                <VCol cols="12" sm="6">
                    <VAutocomplete
                        id="category"
                        v-model="form.category"
                        label="Name"
                        :items="categories"
                        variant="outlined"
                        :hide-details="form.errors.category == undefined"
                        :error-messages="form.errors.category"
                        density="compact"
                    ></VAutocomplete>
                </VCol>
            </VRow>
            <VRow>
                <VCol>
                    <VTextarea
                        id="description"
                        v-model="form.description"
                        label="Item Description"
                        :items="categories"
                        variant="outlined"
                        :hide-details="form.errors.description == undefined"
                        :error-messages="form.errors.description"
                        density="compact"
                    ></VTextarea>
                </VCol>
            </VRow>
            <h4 class="mt-3">Size and Weight</h4>
            <VRow>
                <VCol cols="12" sm="4">
                    <VTextField
                        id="height"
                        v-model="form.height"
                        label="Height"
                        variant="outlined"
                        :hide-details="form.errors.height == undefined"
                        :error-messages="form.errors.height"
                    ></VTextField>
                </VCol>
                <VCol cols="12" sm="4">
                    <VTextField
                        id="width"
                        v-model="form.width"
                        label="Width"
                        variant="outlined"
                        :hide-details="form.errors.width == undefined"
                        :error-messages="form.errors.width"
                    ></VTextField>
                </VCol>
                <VCol cols="12" sm="4">
                    <VTextField
                        id="weight"
                        v-model="form.weight"
                        label="Weight"
                        variant="outlined"
                        :hide-details="form.errors.weight == undefined"
                        :error-messages="form.errors.weight"
                    ></VTextField>
                </VCol>
            </VRow>
            <h4 class="mt-3">Pricing</h4>
            <VRow>
                <VCol cols="12" sm="4">
                    <VTextField
                        id="print-price"
                        v-model="form.print_price"
                        label="Print Price"
                        variant="outlined"
                        :hide-details="form.errors.print_price == undefined"
                        :error-messages="form.errors.print_price"
                    ></VTextField>
                </VCol>
                <VCol cols="12" sm="4">
                    <VTextField
                        id="sheet-price"
                        v-model="form.sheet_price"
                        label="Sheet Price"
                        variant="outlined"
                        :hide-details="form.errors.sheet_price == undefined"
                        :error-messages="form.errors.sheet_price"
                    ></VTextField>
                </VCol>
                <VCol cols="12" sm="4">
                    <VTextField
                        id="cover-print-price"
                        v-model="form.cover_print_price"
                        label="Cover Print Price"
                        variant="outlined"
                        :hide-details="form.errors.cover_print_price == undefined"
                        :error-messages="form.errors.cover_print_price"
                    ></VTextField>
                </VCol>
            </VRow>

            <div class="flex flex-row-reverse mt-3">
                <VBtn
                    color="blue-darken-1"
                    type="submit"
                    :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                >Save Item</VBtn>
            </div>
        </form>
    </div>
</template>

<script setup lang="ts">
import { useForm, usePage } from '@inertiajs/vue3';

interface ItemEditor {
    id: Number,
    name: String,
    category: String,
    description?: String,
    height: String,
    width: String,
    weight: String,
    print_price: Number,
    sheet_price: Number,
    cover_print_price: Number
}

const props = defineProps<{
    item?: ItemEditor
}>();

const categories = usePage().props.categories;

const form = useForm({
    id: props.item ? props.item.id : "",
    name: props.item ? props.item.name : "",
    category: props.item ? props.item.category.name : "",
    description: props.item ? props.item.description : "",
    height: props.item ? props.item.height : "",
    width: props.item ? props.item.width : "",
    weight: props.item ? props.item.weight : "",
    print_price: props.item ? props.item.print_price : "",
    sheet_price: props.item ? props.item.sheet_price : "",
    cover_print_price: props.item ? props.item.cover_print_price : "",
});

const submit = () => {
    form.post(route('item.store'), {
        onFinish: () => {},
    });
};
</script>

<style lang="scss" scoped>

</style>
