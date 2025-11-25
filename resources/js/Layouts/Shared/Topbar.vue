<template>
    <div class="navbar-custom">
    <div class="topbar container-fluid">
        <div class="d-flex align-items-center gap-lg-2 gap-1">

            <!-- Topbar Brand Logo -->
            <div class="logo-topbar">
                <!-- Logo light -->
                <a href="/" class="logo-light">
                    <span class="logo-lg">
                        <ApplicationLogo  class="w-9 h-9 fill-current text-gray-500"></ApplicationLogo>
                    </span>
                    <span class="logo-sm">
                        <ApplicationLogo  class="w-9 h-9 fill-current text-gray-500"></ApplicationLogo>
                    </span>
                </a>

                <!-- Logo Dark -->
                <a href="/" class="logo-dark">
                    <span class="logo-lg">
                        <ApplicationLogo  class="w-9 h-9 fill-current text-gray-500"></ApplicationLogo>
                    </span>
                    <span class="logo-sm">
                        <ApplicationLogo  class="w-9 h-9 fill-current text-gray-500"></ApplicationLogo>
                    </span>
                </a>
            </div>

            <!-- Sidebar Menu Toggle Button -->
            <button class="button-toggle-menu">
                <i class="ri-menu-2-fill"></i>
            </button>

            <!-- Horizontal Menu Toggle Button -->
            <button class="navbar-toggle" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                <div class="lines">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </button>

            <!-- Topbar Search Form -->
            <!-- <div class="app-search dropdown d-none d-lg-block">
                <form>
                    <div class="input-group">
                        <input type="search" class="form-control dropdown-toggle" placeholder="Search..." id="top-search">
                        <span class="ri-search-line search-icon"></span>
                    </div>
                </form>
            </div> -->
        </div>

        <ul class="topbar-menu d-flex align-items-center gap-3">
            <li class="dropdown d-lg-none">
                <!-- <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <i class="ri-search-line fs-22"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-animated dropdown-lg p-0">
                    <form class="p-3">
                        <input type="search" class="form-control" placeholder="Search ..." aria-label="Recipient's username">
                    </form>
                </div> -->
            </li>

            <NotificationsDropdown />

            <li class="d-none d-md-inline-block">
                <a class="nav-link" href="#" data-toggle="fullscreen">
                    <i class="ri-fullscreen-line fs-22"></i>
                </a>
            </li>

            <li class="dropdown">
                <a class="nav-link dropdown-toggle arrow-none nav-user px-2" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <span class="account-user-avatar">
                         <VIcon icon="mdi-account-circle" size="30"></VIcon>
                    </span>
                    <span class="d-lg-flex flex-column gap-1 d-none">
                        <h5 class="my-0">
                            {{ name }}
                        </h5>
                        <h6 class="my-0 fw-normal">{{ role }}</h6>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated profile-dropdown">
                    <!-- item-->
                    <!-- <div class=" dropdown-header noti-title">
                        <h6 class="text-overflow m-0">Welcome !</h6>
                    </div> -->

                    <!-- item-->
                    <a :href="route('profile.edit')" class="dropdown-item">
                        <i class="ri-account-circle-line fs-18 align-middle me-1"></i>
                        <span>My Account</span>
                    </a>

                    <!-- item-->
                    <a :href="route('profile.edit')" class="dropdown-item">
                        <i class="ri-settings-4-line fs-18 align-middle me-1"></i>
                        <span>Settings</span>
                    </a>

                    <form>
                        <Link href="#" @click.prevent="submitForm" class="dropdown-item">
                            <i class="ri-logout-box-line fs-18 align-middle me-1"></i>
                            <span>Logout</span>
                        </Link>
                    </form>
                </div>
            </li>
        </ul>
    </div>
</div>

<Snackbar :data="snackbarOption"></Snackbar>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { usePage, useForm, Link, router } from  '@inertiajs/vue3';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import { snackbarOption, showSnackbar } from '@/Composables/snackbarOptions.js';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import NotificationsDropdown from '@/Components/NotificationsDropdown.vue';

const userProps = usePage().props.auth.user;
const name = userProps.name;
const role = userProps.role;
const branchId = usePage().props.auth.user.branch_id;
const userId = usePage().props.auth.user.id;

const form = useForm({})
const submitForm = () => {
    form.post(route('logout'));
}

window.Pusher = Pusher;
// window.Pusher.logToConsole = true;

const echo = new Echo({
  broadcaster: 'pusher',
  key: import.meta.env.VITE_PUSHER_APP_KEY,
  cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
  encrypted: true,
});

const showBrowserNotification = notification => {
    // Check if notifications are supported
    if (!("Notification" in window)) {
        console.error("This browser does not support notifications");
        return;
    }

    if (Notification.permission === 'granted') {
        try {
            const uniqueTag = `notification-${Date.now()}`;
            
            const notif = new Notification('OMS Notification', {
                body: notification.message,
                icon: window.location.origin + '/images/logo.png',
                data: { url: notification.url },
                tag: uniqueTag,
                // Remove requireInteraction for better Chrome compatibility
                // requireInteraction: true
            });
            
            notif.onclick = function(event) {
                event.preventDefault();
                try {
                    window.focus();
                    router.visit(event.target.data.url);
                } catch (error) {
                    console.error("Navigation error:", error);
                    window.open(event.target.data.url, '_blank');
                }
                notif.close();
            };
        } catch (error) {
            console.error("Notification error:", error);
        }
    } else if (Notification.permission !== 'denied') {
        Notification.requestPermission().then((permission) => {
            console.log("Notification permission:", permission);
            if (permission === 'granted') {
                showBrowserNotification(notification);
            }
        });
    } else {
        console.warn("Notifications are denied. Please enable them in browser settings.");
    }
}

onMounted(() => {
    console.log('Topbar mounted - Echo listening for user:', userId);
    
    // Listening for branch event as message is passed through different processing stages.
    echo.private(`notify.${branchId}`)
        .listen('JobReceived', (e) => {
            console.log('JobReceived event:', e);
            showSnackbar(e.message);
        });

    // Notifying user when there is a new order
    echo.private(`new-order.${branchId}`)
        .listen('AnnounceNewOrder', (e) => {
            console.log('AnnounceNewOrder event:', e);
            showSnackbar(e.message);
        });

    echo.private(`App.Models.User.${userId}`)
        .notification((notification) => {
            console.log('Notification received:', notification);
            showBrowserNotification(notification);
        });

});

onBeforeUnmount(() => {
    echo.private(`notify.${branchId}`).stopListening('JobReceived');
    echo.private(`new-order.${branchId}`).stopListening('AnnounceNewOrder');
    echo.leave(`App.Models.User.${userId}`);
});
</script>

<style lang="scss" scoped>

</style>
