<template>
    <li class="dropdown notification-list" v-if="totalNotification > 0">
        <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
            <i class="ri-notification-3-line fs-22"></i>
            <span v-if="unreadNotifications > 0" class="noti-icon-badge"></span>
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
        </div>
    </li>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import moment from 'moment';

const recentNotifications = ref({});
const totalNotification = ref(0);
const unreadNotifications = ref(0);
const loadRecentNotifications = async () => {
    const response = await axios.get(route('notifications.recent'));
    recentNotifications.value = response.data.notifications;
    totalNotification.value = response.data.count;
    unreadNotifications.value = response.data.unread;
}

onMounted(()=>{
    loadRecentNotifications();
})

</script>