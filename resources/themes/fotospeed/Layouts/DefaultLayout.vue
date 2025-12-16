<script setup>
import { Head, usePage, useForm, Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import AnnouncementBar from '../Partials/AnnouncementBar.vue';
import Header from '../Partials/Header.vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Sidebar from '../Partials/Sidebar.vue';
import Search from '../Partials/Search.vue';
import Breadcrumb from '../Partials/Breadcrumb.vue';
import Shop from '../Partials/Shop.vue';
import Footer from '../Partials/Footer.vue';
import Cta from '../Partials/Cta.vue';

onMounted(() => {
    const preloader = document.getElementById('preloader');
    if (preloader) {
        preloader.classList.add('loaded');
        setTimeout(() => {
            preloader.style.display = 'none';
        }, 600);
    }
});

const isMobileMenuOpen = ref(false);
const toggleMobileMenu = () => isMobileMenuOpen.value = !isMobileMenuOpen.value;
const closeMobileMenu = () => isMobileMenuOpen.value = false;
</script>

<template>
    <div id="preloader" class="preloader">
        <div class="animation-preloader">
            <div class="spinner">                
            </div>
            <div class="txt-loading">
                
            </div>
            <p class="text-center">Loading</p>
        </div>
        <div class="loader">
            <div class="row">
                <div class="col-3 loader-section section-left">
                    <div class="bg"></div>
                </div>
                <div class="col-3 loader-section section-left">
                    <div class="bg"></div>
                </div>
                <div class="col-3 loader-section section-right">
                    <div class="bg"></div>
                </div>
                <div class="col-3 loader-section section-right">
                    <div class="bg"></div>
                </div>
            </div>
        </div>
    </div>

    <!--<< Mouse Cursor Start >>-->  
    <div class="mouse-cursor cursor-outer"></div>
    <div class="mouse-cursor cursor-inner"></div>

    <!-- Back To Top Start -->
    <div class="scroll-up">
        <svg class="scroll-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
        </svg>
    </div>

    <!-- Offcanvas Area Start -->
    <div class="fix-area">
        <div class="offcanvas__info" :class="{ 'info-open': isMobileMenuOpen }">
            <div class="offcanvas__wrapper">
                <div class="offcanvas__content">
                    <div class="offcanvas__top mb-5 d-flex justify-content-between align-items-center">
                        
                    </div>
                    <p class="text d-none d-xl-block py-4">
                        
                    </p>
                    <div class="mobile-menu fix mb-3 mean-container">
                        <div class="mean-bar">
                            <nav class="mean-nav">
                                <ul>
                                    <li v-if="$page.props.auth.user" class="d-md-none pb-4">
                                        <a href="#">
                                            <i class="fas fa-gem me-1" style="color: #DAA520;"></i> 
                                            Loyalty Rewards: {{ Number($page.props.auth.user.points).toLocaleString('en-US', {maximumFractionDigits: 0}) }} Pts
                                        </a>
                                    </li>
                                    <li></li>
                                    <li><a :href="route('marketing.home')">Home</a></li>
                                    <li><a :href="route('marketing.products')">Showroom</a></li>
                                    <li><a :href="route('customer.my-orders')">Orders</a></li>
                                    <li><a :href="route('customer.invoices')">Invoices</a></li>
                                    <li><a :href="route('customer.profile.edit')">My Profile</a></li>
                                    <li class="mt-5"><Link :href="route('logout')" method="post" as="button">Logout</Link></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="offcanvas__contact">
                        <div class="social-icon d-flex align-items-center">
                            <a href="https://www.facebook.com/Syntheticalbum" target="_blank"><i class="fab fa-facebook-f"></i></a>
                            <a href="https://www.instagram.com/photobooknigeria/" target="_blank"><i class="fab fa-instagram"></i></a>
                            <a href="https://go.wa.link/photobooknigeria" target="_blank"><i class="fab fa-whatsapp"></i></a>
                            <a href="https://www.youtube.com/@indigoafrica" target="_blank"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="offcanvas__overlay" :class="{ 'overlay-open': isMobileMenuOpen }" @click="closeMobileMenu"></div>

    <!-- <AnnouncementBar></AnnouncementBar> -->
    <div style="display: flex; flex-direction: column; min-height: 100vh;">
        <Header @toggleMobileMenu="toggleMobileMenu"></Header>

        <Sidebar></Sidebar>
        <Search></Search>
        
        <div style="flex: 1;">
            <slot />
        </div>
        <Footer></Footer>
    </div>
  
</template>