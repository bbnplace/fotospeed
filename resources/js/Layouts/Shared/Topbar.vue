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



            <li class="dropdown notification-list" v-if="totalNotification > 0">
                <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <i class="ri-notification-3-line fs-22"></i>
                    <span class="noti-icon-badge"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated dropdown-lg py-0">
                    <div class="p-2 border-top-0 border-start-0 border-end-0 border-dashed border">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-0 fs-16 fw-semibold"> Notification</h6>
                            </div>
                            <div class="col-auto">
                                
                            </div>
                        </div>
                    </div>

                    <template v-if="totalNotification > 0">
                        <div style="max-height: 300px;" data-simplebar>
                            <template v-for="(notifications, key) in recentNotifications" :key="key">
                                <h5 class="text-muted fs-12 fw-bold p-2 text-uppercase mb-0">{{ key }}</h5>

                                <a href="javascript:void(0);" class="dropdown-item p-0 notify-item unread-noti card m-0 shadow-none bg-white" v-for="(notification, i) in notifications" :key="i">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <div class="notify-icon bg-primary">
                                                    <i class="ri-message-3-line fs-18"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 text-truncate ms-2">
                                                <h5 class="noti-item-title fw-semibold fs-13">{{ notification.title.substring(0, 23) }} <small class="fw-normal text-muted float-end ms-1">{{ moment(notification.created_at).fromNow() }}</small></h5>
                                                <small class="noti-item-subtitle text-muted">{{ notification.message.substring(0, 50) }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </template>
                            
                        </div>

                        <!-- All-->
                        <a href="javascript:void(0);" class="dropdown-item text-center text-primary text-decoration-underline fw-bold notify-item border-top border-light py-2">
                            View All
                        </a>
                    </template>
                    <!-- <div v-else>
                        <p class="p-2 text-center">No Notification</p>
                    </div> -->

                </div>
            </li>


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
<br />

<Snackbar :data="snackbarOption"></Snackbar>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { usePage, useForm, Link } from  '@inertiajs/vue3';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import { snackbarOption, showSnackbar } from '@/Composables/snackbarOptions.js';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import axios from 'axios';
import moment from 'moment';

const userProps = usePage().props.auth.user;
const name = userProps.name;
const role = userProps.role;
const branchId = usePage().props.auth.user.branch_id;

const form = useForm({})
const submitForm = () => {
    form.post(route('logout'));
}

window.Pusher = Pusher;

const echo = new Echo({
  broadcaster: 'pusher',
  key: import.meta.env.VITE_PUSHER_APP_KEY,
  cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
  encrypted: true,
});

const recentNotifications = ref({});
const totalNotification = ref(0);
const loadRecentNotifications = async () => {
    const response = await axios.get(route('notifications.recent'));
    recentNotifications.value = response.data.notifications;
    totalNotification.value = response.data.count;
}

onMounted(() => {
    // Listening for branch event as message is passed through different processing stages.
    echo.private(`notify.${branchId}`)
        .listen('JobReceived', (e) => {
            showSnackbar(e.message);
        });

    // Notifying user when there is a new order
    echo.private(`new-order.${branchId}`)
    .listen('AnnounceNewOrder', (e) => {
        showSnackbar(e.message);
    });

    loadRecentNotifications();
});

onBeforeUnmount(() => {
    echo.private(`notify.${branchId}`).stopListening('JobReceived');
    echo.private(`new-order.${branchId}`).stopListening('AnnounceNewOrder');
});
</script>

<style lang="scss" scoped>

</style>
