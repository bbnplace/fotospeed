<template>
    <Head title="Create Order"></Head>
    
    <div class="order-editor-wrapper">
        <!-- Hero Section -->
        <div class="hero-section">
            <div class="hero-content">
                <h1 class="hero-title">Create Your Order</h1>
                <p class="hero-subtitle">Professional printing services tailored to your needs</p>
            </div>
        </div>

        <!-- Main Form Container -->
        <div class="form-container">
            <!-- Product Selection Card -->
            <div class="section-card product-selection">
                <div class="section-header">
                    <v-icon class="section-icon" color="primary">mdi-package-variant</v-icon>
                    <h2 class="section-title">Product Selection</h2>
                </div>
                
                <VRow>
                    <VCol cols="12">
                        <VAutocomplete
                            v-model="masterForm.item"
                            label="Select Product"
                            :items="items"
                            variant="outlined"
                            density="comfortable"
                            :hide-details="masterForm.errors.item == undefined"
                            :error-messages="masterForm.errors.item"
                            @blur="getProductDetails"
                            prepend-inner-icon="mdi-newspaper-variant"
                            class="modern-input"
                        ></VAutocomplete>
                    </VCol>
                </VRow>

                <VRow class="mt-2">
                    <VCol cols="12" md="5">
                        <VTextField
                            v-model="masterForm.quantity"
                            label="Quantity"
                            variant="outlined"
                            density="comfortable"
                            type="number"
                            :hide-details="masterForm.errors.quantity == undefined"
                            :error-messages="masterForm.errors.quantity"
                            prepend-inner-icon="mdi-counter"
                            class="modern-input"
                        ></VTextField>
                    </VCol>
                    <VCol cols="12" md="7" v-if="$page.props.auth.user.role != 'Customer'">
                        <VTextField
                            v-model="masterForm.price"
                            label="Unit Price"
                            variant="outlined"
                            density="comfortable"
                            type="number"
                            prefix="₦ "
                            :hide-details="masterForm.errors.price == undefined"
                            :error-messages="masterForm.errors.price"
                            prepend-inner-icon="mdi-currency-ngn"
                            disabled
                            class="modern-input"
                        ></VTextField>
                    </VCol>
                </VRow>

                <VRow class="mt-2">
                    <VCol cols="12">
                        <VAutocomplete
                            v-model="masterForm.branch"
                            label="Processing Branch"
                            :items="branches"
                            variant="outlined"
                            density="comfortable"
                            :hide-details="masterForm.errors.branch == undefined"
                            :error-messages="masterForm.errors.branch"
                            prepend-inner-icon="mdi-office-building"
                            class="modern-input"
                        ></VAutocomplete>
                    </VCol>
                </VRow>

                <VRow class="mt-2">
                    <VCol cols="12">
                        <VTextarea
                            v-model="masterForm.note"
                            label="Special Instructions (Optional)"
                            variant="outlined"
                            rows="3"
                            :hide-details="masterForm.errors.note == undefined"
                            :error-messages="masterForm.errors.note"
                            prepend-inner-icon="mdi-text"
                            class="modern-input"
                        ></VTextarea>
                    </VCol>
                </VRow>
            </div>

            <!-- Order & Delivery Info - Two Columns -->
            <VRow class="mt-4">
                <!-- Tracking Information Card -->
                <VCol cols="12" lg="6">
                    <div class="section-card">
                        <div class="section-header">
                            <v-icon class="section-icon" color="success">mdi-tag-text</v-icon>
                            <h2 class="section-title">Tracking Information</h2>
                        </div>
                        
                        <VRow>
                            <VCol cols="12">
                                <VTextField
                                    v-model="masterForm.name"
                                    label="Order Name"
                                    variant="outlined"
                                    density="comfortable"
                                    :hide-details="masterForm.errors.name == undefined"
                                    :error-messages="masterForm.errors.name"
                                    prepend-inner-icon="mdi-tag"
                                    class="modern-input"
                                ></VTextField>
                            </VCol>
                        </VRow>

                        <VRow v-if="$page.props.auth.user.role != 'Customer'" class="mt-2">
                            <VCol cols="12">
                                <VTextField
                                    v-model="masterForm.orderNumber"
                                    label="Reference Number (Optional)"
                                    variant="outlined"
                                    density="comfortable"
                                    :hide-details="masterForm.errors.orderNumber == undefined"
                                    :error-messages="masterForm.errors.orderNumber"
                                    prepend-inner-icon="mdi-barcode"
                                    class="modern-input"
                                ></VTextField>
                            </VCol>
                        </VRow>
                    </div>
                </VCol>

                <!-- Delivery Information Card -->
                <VCol cols="12" lg="6">
                    <div class="section-card">
                        <div class="section-header">
                            <v-icon class="section-icon" color="warning">mdi-truck-fast</v-icon>
                            <h2 class="section-title">Delivery Information</h2>
                        </div>

                        <VRow v-if="$page.props.auth.user.role != 'Customer'">
                            <VCol cols="12">
                                <VTextField
                                    v-model="masterForm.customerMobile"
                                    label="Customer Mobile"
                                    variant="outlined"
                                    density="comfortable"
                                    type="tel"
                                    @blur="getCustomerInfo"
                                    :hide-details="masterForm.errors.customerMobile == undefined"
                                    :error-messages="masterForm.errors.customerMobile"
                                    prepend-inner-icon="mdi-cellphone"
                                    class="modern-input"
                                ></VTextField>
                                <div v-if="masterForm.customerData.name" class="customer-info">
                                    <v-icon small>mdi-account-check</v-icon>
                                    {{ masterForm.customerData.name }}
                                </div>
                            </VCol>
                            <VCol cols="12" v-if="masterForm.newCustomer" class="mt-2">
                                <VTextField
                                    v-model="masterForm.customerName"
                                    label="Full Name"
                                    variant="outlined"
                                    density="comfortable"
                                    :hide-details="masterForm.errors.customerName == undefined"
                                    :error-messages="masterForm.errors.customerName"
                                    prepend-inner-icon="mdi-account"
                                    class="modern-input"
                                ></VTextField>
                            </VCol>
                            <VCol cols="12" v-if="masterForm.newCustomer" class="mt-2">
                                <VTextField
                                    v-model="masterForm.customerEmail"
                                    label="Email (Optional)"
                                    variant="outlined"
                                    density="comfortable"
                                    :hide-details="masterForm.errors.customerEmail == undefined"
                                    :error-messages="masterForm.errors.customerEmail"
                                    prepend-inner-icon="mdi-email"
                                    class="modern-input"
                                ></VTextField>
                            </VCol>
                            <VCol cols="12" v-if="masterForm.newCustomer" class="mt-2">
                                <VTextField
                                    v-model="masterForm.password"
                                    label="Password"
                                    variant="outlined"
                                    density="comfortable"
                                    :hide-details="masterForm.errors.password == undefined"
                                    :error-messages="masterForm.errors.password"
                                    prepend-inner-icon="mdi-lock"
                                    class="modern-input"
                                ></VTextField>
                            </VCol>
                        </VRow>

                        <VRow :class="$page.props.auth.user.role != 'Customer' ? 'mt-2' : ''">
                            <VCol cols="12">
                                <VTextarea
                                    v-model="masterForm.deliveryAddress"
                                    label="Delivery Address"
                                    variant="outlined"
                                    rows="3"
                                    :hide-details="masterForm.errors.deliveryAddress == undefined"
                                    :error-messages="masterForm.errors.deliveryAddress"
                                    prepend-inner-icon="mdi-map-marker"
                                    class="modern-input"
                                ></VTextarea>
                            </VCol>
                        </VRow>

                        <VRow class="mt-3">
                            <VCol cols="12">
                                <div class="date-picker-wrapper">
                                    <label class="date-label">
                                        <v-icon small>mdi-calendar</v-icon>
                                        Select Delivery Date
                                    </label>
                                    <v-date-picker
                                        v-model="masterForm.date"
                                        :min="minDeliveryDate"
                                        :max="maxDeliveryDate"
                                        show-adjacent-months
                                        elevation="2"
                                        width="100%"
                                    ></v-date-picker>
                                </div>
                            </VCol>
                        </VRow>
                    </div>
                </VCol>
            </VRow>

            <!-- File Upload Section -->
            <div class="section-card upload-section mt-4">
                <div class="section-header">
                    <v-icon class="section-icon" color="info">mdi-cloud-upload</v-icon>
                    <h2 class="section-title">Upload Your Files</h2>
                </div>

                <template v-if="orderForm.orderFiles && orderForm.orderFiles.length">
                    <VRow>
                        <VCol cols="12" md="6" lg="4" v-for="(orderFile, index) in orderForm.orderFiles" :key="index">
                            <div class="file-card">
                                <OrderForm
                                    :orderImage="orderFile.file"
                                    :view="props.order ? 'Edit' : 'New'"
                                    @pageRemoved="removeImage"
                                    @pageDataUpdated="(data) => updatePageData(data, orderFile)"
                                ></OrderForm>
                            </div>
                        </VCol>
                    </VRow>
                </template>
                
                <DropzoneUploader 
                    v-if="!props.order" 
                    usage="Order" 
                    @fileUploaded="handleData"
                    class="mt-3"
                ></DropzoneUploader>
                <div class="error-message" v-if="masterForm.errors.files">{{ masterForm.errors.files }}</div>
            </div>

            <!-- Submit Button -->
            <div class="submit-section">
                <VBtn
                    color="primary"
                    size="large"
                    @click="submitOrder"
                    :loading="masterForm.processing"
                    :disabled="masterForm.processing"
                    class="submit-btn"
                    elevation="3"
                    prepend-icon="mdi-check-circle"
                >
                    {{ masterForm.btnTag }}
                </VBtn>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive } from 'vue';
import { Head, usePage, useForm } from '@inertiajs/vue3';
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
const selectedProduct = usePage().props.selectedProduct;

const masterForm = useForm({
    item: selectedProduct ?? "Select",
    branch: "Select",
    files: [],
    customerMobile: "",
    customerData: {},
    unitPrice: 0,
    quantity: 1,
    price: 0,
    name: "",
    note: "",
    btnTag: "Submit Order",
    date: new Date(minDeliveryDate),
    deliveryAddress: "",
    orderNumber: "",
    print_price: 0,
    sheet_price: 0,
    cover_print_price: 0,
    newCustomer: false,
    customerName: null,
    customerEmail: null,
    password: null,
});

const handleData = data => {
    const copyOfUploadedData = { ...data, pageNumber: "", note: "" }
    const newFile = { file: copyOfUploadedData }
    orderForm.orderFiles.push(newFile);
}

const updatePageData = (pageObject, object) => {
    object.note = pageObject.note;
    object.pageNo = pageObject.pageNumber;
    object['freshEmission'] = true;
}

const submitOrder = () => {
    const fileData = [];
    orderForm.orderFiles.forEach(element => {
        let file = element.file;
        if (element.freshEmission) {
            file.pageNumber = element.pageNo;
            file.note = element.note;
        }
        fileData.push({ file })
    });

    masterForm.files = fileData;

    if (props.order) {
        masterForm.put(route('order.edit', [usePage().props.order.id]), {
            onFinish: () => {},
        });
    } else {
        masterForm.post(route("order.add"));
    }
}

const removeImage = data => {
    for (let index = 0; index < orderForm.orderFiles.length; index++) {
        const element = orderForm.orderFiles[index];
        if (element.file.id == data.id) {
            orderForm.orderFiles.splice(index, 1);
        }
    }
}

const customerDataEndpoint = usePage().props.endpoint;
let source = null;

const getCustomerInfo = async () => {
    const MIN_MOBILE_LENGTH = 9;
    if (masterForm.customerMobile.length > MIN_MOBILE_LENGTH) {
        source = axios.CancelToken.source();
        const payload = { mobile: masterForm.customerMobile }
        const response = await axios.post(customerDataEndpoint, payload, {
            headers: { "Content-Type": "application/json" },
            cancelToken: source.token
        });
        
        if (response.data && response.data.status == 'success') {
            if (response.data.customer == null) {
                masterForm.newCustomer = true;
            } else {
                masterForm.customerData = response.data.customer;
            }
        }
    }
}

const getProductDetails = async () => {
    const payload = { name: masterForm.item };
    try {
        const response = await axios.post(route('item.get-by-name'), payload, {
            headers: { "Content-Type": "application/json" }
        });
        
        if (response.data && response.data.status === 'success') {
            const product = response.data.product;
            masterForm.price = (product.print_price ?? 0) + (product.sheet_price ?? 0) + (product.cover_print_price ?? 0);
        }
    } catch (error) {
        // Handle error
    }
}
</script>

<style scoped>
.order-editor-wrapper {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding-bottom: 60px;
}

.hero-section {
    padding: 60px 20px;
    text-align: center;
    color: white;
}

.hero-title {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 12px;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
}

.hero-subtitle {
    font-size: 1.3rem;
    opacity: 0.95;
    font-weight: 300;
}

.form-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.section-card {
    background: white;
    border-radius: 16px;
    padding: 32px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.section-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 15px 50px rgba(0,0,0,0.15);
}

.section-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 2px solid #f0f0f0;
}

.section-icon {
    font-size: 28px;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #333;
    margin: 0;
}

.modern-input :deep(.v-field) {
    border-radius: 12px;
    transition: all 0.3s ease;
}

.modern-input :deep(.v-field:hover) {
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.modern-input :deep(.v-field--focused) {
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.25);
}

.customer-info {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 8px;
    padding: 8px 12px;
    background: #e8f5e9;
    border-radius: 8px;
    color: #2e7d32;
    font-size: 0.9rem;
}

.date-picker-wrapper {
    background: #f8f9fa;
    padding: 16px;
    border-radius: 12px;
}

.date-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    color: #555;
    margin-bottom: 12px;
    font-size: 0.95rem;
}

.file-card {
    border-radius: 12px;
    overflow: hidden;
    transition: transform 0.3s ease;
}

.file-card:hover {
    transform: scale(1.02);
}

.upload-section {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
}

.error-message {
    color: #d32f2f;
    font-size: 0.9rem;
    margin-top: 12px;
    padding: 12px;
    background: #ffebee;
    border-radius: 8px;
}

.submit-section {
    margin-top: 32px;
    text-align: center;
}

.submit-btn {
    padding: 24px 48px !important;
    font-size: 1.1rem !important;
    font-weight: 600 !important;
    border-radius: 50px !important;
    text-transform: none !important;
    letter-spacing: 0.5px;
    transition: all 0.3s ease !important;
}

.submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(102, 126, 234, 0.4) !important;
}

/* Responsive Design */
@media (max-width: 960px) {
    .hero-title {
        font-size: 2.2rem;
    }
    
    .hero-subtitle {
        font-size: 1.1rem;
    }
    
    .section-card {
        padding: 24px;
    }
    
    .submit-btn {
        padding: 20px 40px !important;
        width: 100%;
    }
}

@media (max-width: 600px) {
    .hero-section {
        padding: 40px 20px;
    }
    
    .hero-title {
        font-size: 1.8rem;
    }
    
    .section-card {
        padding: 20px;
    }
}
</style>
