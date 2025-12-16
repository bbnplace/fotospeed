<script setup>
import DefaultLayout from '../../Layouts/DefaultLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue';
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue';

const props = defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    loyalty: {
        type: Object,
        default: () => ({})
    }
});

const formatter = new Intl.NumberFormat('en-NG', {
    style: 'currency',
    currency: 'NGN',
    minimumFractionDigits: 2
});

const numberFormatter = new Intl.NumberFormat('en-US', {
    maximumFractionDigits: 0
});

</script>

<template>
    <Head title="My Profile" />

    <DefaultLayout>
        <!-- Breadcrumb Area Start -->
        <section class="breadcrumb-area bg-image" style="background-image: url('assets/img/breadcrumb/01.jpg');">
            <div class="container">
                <div class="breadcrumb-content text-center">
                    <h2>My Profile</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a :href="route('marketing.home')">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Profile</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </section>

        <section class="product-cart-area pt-100 pb-100">
            <div class="container">
                
                <!-- Loyalty Rewards Panel -->
                <div class="row mb-5">
                    <div class="col-12">
                        <div class="cart-total-box p-4" style="background: #f9f9f9; border-radius: 8px;">
                            <h3 class="mb-4" style="color: #DAA520;"><i class="fas fa-crown me-2"></i> Loyalty Rewards</h3>
                            <div class="row text-center">
                                <div class="col-md-3 mb-3">
                                    <div class="p-3 bg-white rounded shadow-sm">
                                        <h5 class="text-muted mb-2">Available Points</h5>
                                        <h2 class="text-primary fw-bold">{{ numberFormatter.format(loyalty.available || 0) }}</h2>
                                        <small class="text-muted">Worth {{ formatter.format(loyalty.available_currency || 0) }}</small>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="p-3 bg-white rounded shadow-sm">
                                        <h5 class="text-muted mb-2">Total Earned</h5>
                                        <h3 class="text-success">{{ numberFormatter.format(loyalty.total_earned || 0) }}</h3>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="p-3 bg-white rounded shadow-sm">
                                        <h5 class="text-muted mb-2">Redeemed</h5>
                                        <h3 class="text-info">{{ numberFormatter.format(loyalty.total_redeemed || 0) }}</h3>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="p-3 bg-white rounded shadow-sm">
                                        <h5 class="text-muted mb-2">Expired</h5>
                                        <h3 class="text-danger">{{ numberFormatter.format(loyalty.total_expired || 0) }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6 mb-4">
                        <div class="cart-total-box p-4">
                            <h4 class="mb-4">Profile Information</h4>
                            <UpdateProfileInformationForm
                                :must-verify-email="mustVerifyEmail"
                                :status="status"
                                submit-route="customer.profile.update"
                            />
                        </div>
                    </div>

                    <div class="col-lg-6 mb-4">
                        <div class="cart-total-box p-4">
                            <h4 class="mb-4">Update Password</h4>
                            <UpdatePasswordForm />
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </DefaultLayout>
</template>
