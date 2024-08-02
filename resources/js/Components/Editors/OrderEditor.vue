<template>
    <Head title="Create Order"></Head>
        <VRow>
            <VCol>
                <VAutocomplete
                    v-model="masterForm.item"
                    label="Select Item"
                    :items="items"
                    variant="outlined"
                    density="compact"
                    :hide-details="masterForm.errors.item == undefined"
                    :error-messages="masterForm.errors.item"
                ></VAutocomplete>
            </VCol>
        </VRow>
        <h4 class="mt-3">Upload Album Pages</h4>
        <template v-if="orderForm.orderFiles">
            <VRow>
                <VCol cols="12" lg="6" v-for="orderFile, index in orderForm.orderFiles" :key="index">
                    <OrderForm
                        :orderImage="orderFile.file"
                        view="New"
                        @pageRemoved="removeImage"
                        @pageDataUpdated="(data) => {
                            updatePageData(data, orderFile)
                        }"
                    ></OrderForm>
                </VCol>
            </VRow>
        </template>
        <!-- {{ orderForm.orderFiles}} -->
        <DropzoneUploader @fileUploaded="handleData"></DropzoneUploader>
        <div class="text-red" v-if="masterForm.errors.files">{{ masterForm.errors.files }}</div>
        <VRow class="mt-4">
            <VCol cols="12" md="6">
                <VRow v-if="$page.props.auth.user.role != 'Customer'">
                    <VCol>
                        <VTextField
                            id="orderNumber"
                            v-model="masterForm.orderNumber"
                            label="Reference Number (Optional)"
                            variant="outlined"
                            autocomplete="off"
                            :hide-details="masterForm.errors.orderNumber == undefined"
                            :error-messages="masterForm.errors.orderNumber"
                        ></VTextField>
                    </VCol>
                </VRow>
                <VRow>
                    <VCol>
                        <VTextField
                            id="name"
                            v-model="masterForm.name"
                            label="Order Name"
                            variant="outlined"
                            autocomplete="off"
                            :hide-details="masterForm.errors.name == undefined"
                            :error-messages="masterForm.errors.name"
                        ></VTextField>
                    </VCol>
                </VRow>
                <VRow>
                    <VCol>
                        <VTextarea
                            id="note"
                            v-model="masterForm.note"
                            label="Note (optional)"
                            variant="outlined"
                            autocomplete="off"
                            :hide-details="masterForm.errors.note == undefined"
                            :error-messages="masterForm.errors.note"
                        ></VTextarea>
                    </VCol>
                </VRow>
                <VRow>
                    <VCol cols="12" sm="6">
                        <VTextField
                            id="quantity"
                            v-model="masterForm.quantity"
                            label="Quantity"
                            variant="outlined"
                            autocomplete="off"
                            type="number"
                            :hide-details="masterForm.errors.quantity == undefined"
                            :error-messages="masterForm.errors.quantity"
                        ></VTextField>
                    </VCol>
                </VRow>
                <VRow>
                    <VCol cols="12" sm="6" v-if="$page.props.auth.user.role != 'Customer'">
                        <VTextField
                            id="price"
                            v-model="masterForm.price"
                            label="Price"
                            variant="outlined"
                            autocomplete="off"
                            type="number"
                            prefix="₦ "
                            :hide-details="masterForm.errors.price == undefined"
                            :error-messages="masterForm.errors.price"
                        ></VTextField>
                    </VCol>
                </VRow>
                <VRow>
                    <VCol>
                        <VAutocomplete
                            v-model="masterForm.branch"
                            label="Select Branch"
                            :items="branches"
                            variant="outlined"
                            density="compact"
                            :hide-details="masterForm.errors.branch == undefined"
                            :error-messages="masterForm.errors.branch"
                        ></VAutocomplete>
                    </VCol>
                </VRow>
                <VRow v-if="$page.props.auth.user.role != 'Customer'">
                    <VCol>
                        <VTextField
                            id="customerMobile"
                            v-model="masterForm.customerMobile"
                            label="Customer Mobile"
                            variant="outlined"
                            autocomplete="off"
                            type="tel"
                            @blur="getCustomerInfo"
                            :hide-details="masterForm.errors.customerMobile == undefined"
                            :error-messages="masterForm.errors.customerMobile"
                        ></VTextField>
                        {{ masterForm.customerData.name }}
                    </VCol>
                </VRow>
                <VRow>
                    <VCol>
                        <VTextarea
                            id="deliveryAddress"
                            v-model="masterForm.deliveryAddress"
                            label="Delivery Address"
                            variant="outlined"
                            autocomplete="off"
                            :hide-details="masterForm.errors.deliveryAddress == undefined"
                            :error-messages="masterForm.errors.deliveryAddress"
                        ></VTextarea>
                    </VCol>
                </VRow>
            </VCol>

            <VCol cols="12" md="6">
                <v-container>
                    <v-row justify="space-around">
                    <v-date-picker
                        v-model="masterForm.date"
                        :min="minDeliveryDate"
                        :max="maxDeliveryDate"
                        title="Select Delivery Date"
                        :hide-details="masterForm.errors.date == undefined"
                        :error-messages="masterForm.errors.date"
                    ></v-date-picker>
                    </v-row>
                </v-container>
                <!-- {{ masterForm.date }} -->
            </VCol>
        </VRow>

        <div class="flex flex-row-reverse">
            <VBtn
                color="blue-darken-1"
                @click="submitOrder"
            >{{ masterForm.btnTag }}</VBtn>
        </div>

          <!-- {{ masterForm }} -->
</template>

<script setup>
import { reactive } from 'vue';
import { Head, usePage, router, useForm } from '@inertiajs/vue3';
import DropzoneUploader from '@/Components/DropzoneUploader.vue';
import OrderForm from '@/Components/OrderForm.vue';

const props = defineProps({
    order: Object
});

const orderForm = reactive({
    orderFiles: props.order ? props.order.files : [],
});

const items = usePage().props.items;
const branches = usePage().props.branches;

const minDeliveryDate = usePage().props.deliveryDate.min;
const maxDeliveryDate = usePage().props.deliveryDate.max;

const masterForm = useForm({
    item: props.order ? props.order.item : "Select",
    branch: props.order ? props.order.branch : "Select",
    files: [],
    customerMobile: props.order ? usePage().props.order.user.mobile : "",
    customerData: props.order ? usePage().props.order.user : {},
    price: props.order ? props.order.price : "",
    name: props.order ? props.order.name : "",
    note: props.order ? props.order.note : "",
    btnTag: props.order ? "Save" : "Submit",
    date: new Date((props.order ? props.order.date : "")),
    deliveryAddress: props.order ? props.order.deliveryAddress : "",
    orderNumber: props.order ? props.order.orderNumber : "",
    quantity: props.order ? props.order.quantity : 1,
})

const handleData = data => {
    const copyOfUploadedData = { ...data, pageNumber: "", note: "" }

    const newFile = {
        file: copyOfUploadedData,

    }
    orderForm.orderFiles.push(newFile);
}


const updatePageData = (pageObject, object) => {
    object.note = pageObject.note;
    object.copies = pageObject.copies;
    object.pageNo = pageObject.pageNumber;
}

const submitOrder = () => {
    // To reduce size of submitted data, cut out the dataURL from each file before submitting
    const fileData = [];

    orderForm.orderFiles.forEach(element => {
        let file = element.file;
        if(element.pageNo && element.note){
            file = { ...element.file, note: element.note, pageNumber: element.pageNo }
            file.dataURL = undefined;
        }

        fileData.push({
            file
        })
    });

    masterForm.files = fileData;

    if (props.order) {
        masterForm.put(route('order.edit', [usePage().props.order.id]), {
            onFinish: () => {},
        });
    } else {
        // Submit the order data to endpoint
        masterForm.post(route("order.add"));
    }
}


const removeImage = data => {
    for (let index = 0; index < orderForm.orderFiles.length; index++) {
        const element = orderForm.orderFiles[index];

        if (element.file.id == data.id) {
            orderForm.orderFiles.splice(index, 1); // Main Line
        }
    }
}

const customerDataEndpoint = usePage().props.endpoint;
let source = null;
const getCustomerInfo = async () => {
    const MIN_MOBILE_LENGTH = 9;
    if(masterForm.customerMobile.length > MIN_MOBILE_LENGTH){

        source = axios.CancelToken.source();
        const payload = {
            mobile: masterForm.customerMobile
        }
        const response = await axios.post(customerDataEndpoint, payload, {
            headers: {
                "Content-Type": "application/json"
            },
            cancelToken: source.token
        });
        masterForm.customerData = response.data;
    }
}

</script>

