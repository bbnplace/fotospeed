<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Head, usePage, useForm, Link } from '@inertiajs/vue3';
const site = usePage().props.site;
</script>
<template>
    <!-- Header Area Start -->
    <header class="header-section">
        <div id="header-sticky" class="header-1">
            <div class="container-fluid">
                <div class="mega-menu-wrapper">
                    <div class="header-main">
                        <div class="header-left">
                            <div class="logo">
                                <a :href="site.url" class="header-logo">
                                    <ApplicationLogo />
                                </a>
                            </div>
                        </div>
                        <div class="mean__menu-wrapper">
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
                        <div class="header-right d-flex justify-content-end align-items-center">
                            <a href="#0" class="search-trigger search-icon"><i class="fal fa-search"></i></a>
                            <a :href="route('login')" class="user-icon"><i class="far fa-user"></i></a>
                            <Link :href="route('logout')" method="post" class="logout-icon" as="button" title="Signout"><i class="far fa-sign-out"></i></Link>
                            <!-- <div class="menu-cart">
                                <button id="openButton" class="cart-icon">
                                    <i class="far fa-shopping-cart"></i>
                                </button>
                            </div> -->
                            <div class="header__hamburger d-xl-none my-auto">
                                <div class="sidebar__toggle">
                                    <img src="assets/img/toggle.svg" alt="img">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
</template>