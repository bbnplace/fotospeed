<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Head, usePage, useForm, Link } from '@inertiajs/vue3';
    defineEmits(['toggleMobileMenu']);
    const site = usePage().props.site;
</script>
<template>
    <!-- Header Area Start -->
    <header class="header-section">
        <div id="header-sticky" class="header-1">
            <div class="container-fluid">
                <div class="mega-menu-wrapper">
                    <div class="header-main d-flex align-items-center justify-content-between">
                        <div class="header-left">
                            <div class="logo">
                                <a :href="site.url" class="header-logo">
                                    <ApplicationLogo  style="height: 27px !important; width: auto !important;" />
                                </a>
                            </div>
                        </div>
                        <div class="mean__menu-wrapper d-none d-xl-block">
                            <div class="main-menu">
                                <nav id="mobile-menu">
                                    <ul>
                                        <li class="active menu-thumb">
                                            <a :href="`${site.homeRoute}`">
                                            Home 
                                            </a>
                                        </li>
                                        <li>
                                            <a :href="route('marketing.products')">Showroom</a>
                                        </li>
                                        <li>
                                            <a :href="route('customer.my-orders')">Orders</a>
                                        </li>
                                        <li>
                                            <a :href="route('customer.invoices')">
                                                Invoices
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="header-right d-flex justify-content-end align-items-center gap-3">
                            <a href="#0" class="search-trigger search-icon d-none d-md-inline-block"><i class="fal fa-search"></i></a>
                            
                            <div v-if="$page.props.auth.user" class="me-4 d-none d-md-flex align-items-center">
                                <span class="fw-bold" style="color: #DAA520;">
                                    <i class="fas fa-gem me-1"></i> {{ Number($page.props.auth.user.points).toLocaleString('en-US', {maximumFractionDigits: 0}) }} Pts
                                </span>
                            </div>

                            <a :href="route('customer.profile.edit')" v-if="$page.props.auth.user" class="user-icon d-none d-md-inline-block" title="My Profile"><i class="far fa-user"></i></a>
                            <a :href="route('login')" v-else class="user-icon d-none d-md-inline-block"><i class="far fa-user"></i></a>
                            
                            <Link v-if="$page.props.auth.user" :href="route('logout')" method="post" class="logout-icon d-none d-md-inline-block" as="button" title="Signout"><i class="far fa-sign-out"></i></Link>
                            <!-- <div class="menu-cart">
                                <button id="openButton" class="cart-icon">
                                    <i class="far fa-shopping-cart"></i>
                                </button>
                            </div> -->
                            <div class="header__hamburger d-xl-none my-auto" @click="$emit('toggleMobileMenu')">
                                <div class="sidebar__toggle">
                                    <i class="fal fa-bars font-size-24 text-dark"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
</template>