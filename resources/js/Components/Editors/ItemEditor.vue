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
                        label="Category"
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
            <h4 class="mt-3">Size and Weight (include units)</h4>
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
                        label="Weight (Optional)"
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
                        label="Print Price (Optional)"
                        variant="outlined"
                        prefix="₦"
                        :hide-details="form.errors.print_price == undefined"
                        :error-messages="form.errors.print_price"
                    ></VTextField>
                </VCol>
                <VCol cols="12" sm="4">
                    <VTextField
                        id="sheet-price"
                        v-model="form.sheet_price"
                        label="Sheet Price (Optional)"
                        variant="outlined"
                        prefix="₦"
                        :hide-details="form.errors.sheet_price == undefined"
                        :error-messages="form.errors.sheet_price"
                    ></VTextField>
                </VCol>
                <VCol cols="12" sm="4">
                    <VTextField
                        id="cover-print-price"
                        v-model="form.cover_print_price"
                        label="Cover Print Price (Optional)"
                        variant="outlined"
                        prefix="₦"
                        :hide-details="form.errors.cover_print_price == undefined"
                        :error-messages="form.errors.cover_print_price"
                    ></VTextField>
                </VCol>
            </VRow>
            <h4 class="mt-3">Processing Units</h4>
            <VRow>
                <VCol cols="12" md="6">
                    <VCombobox
                        v-model="form.production_branches"
                        label="Select Branches that can process this item"
                        :items="branches"
                        multiple
                        chips
                        small-chips
                        variant="outlined"
                        :hide-details="form.errors.production_branches == undefined"
                        :error-messages="form.errors.production_branches"
                        max-errors="5"
                        density="compact"
                    ></VCombobox>
                </VCol>
                <VCol cols="12" md="6">
                    <VAutocomplete
                        id="branch"
                        v-model="form.primary_production_branch"
                        label="Primary Branch"
                        :items="branches"
                        variant="outlined"
                        :hide-details="form.errors.primary_production_branch == undefined"
                        :error-messages="form.errors.primary_production_branch"
                        density="compact"
                    ></VAutocomplete>
                    <b>Important:</b> In the event that customer selects a center that cannot process this item, this is the center that will receive the order.
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
    category: {
        id: Number,
        name: String
    },
    description?: String,
    height: String,
    width: String,
    weight: String,
    print_price: Number,
    sheet_price: Number,
    cover_print_price: Number,
    order_processing_branches: string,
    primary_order_processing_branch: String,
}

const props = defineProps<{
    item?: ItemEditor
}>();

const categories = usePage().props.categories;
const branches = usePage().props.branches;

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
    production_branches: props.item ? JSON.parse(props.item.order_processing_branches) : [],
    primary_production_branch: props.item ? props.item.primary_order_processing_branch : ""
});

const submit = () => {
    if(props.item) {
        form.put(route('item.edit', [props.item.id]), {
            onFinish: () => {},
        });
    } else {
        form.post(route('item.add'), {
            onFinish: () => {},
        });
    }
};
</script>

<style lang="scss" scoped>

</style>
