<template>
    <Panel snippet-title="Communication Log">
        <div v-if="orderLog.length">
            <div v-for="(note, index) in orderLog" :key="index" class="task-card px-2">
                <p class="font-bold ">{{ moment(note.created_at).calendar() }}, {{ note.user.mobile == user.mobile ? "I" :  note.user.name }} wrote:</p>
                <p>{{ note.message }}</p>
            </div>
        </div>
        <div v-else>
            <VCard class="mb-2 p-2" color="grey-lighten-3">
                Leave a note for other team members working on this Order.
            </VCard>
        </div>
        <div>
            <form @submit.prevent="sendMessage">
                <VTextarea
                    v-model="logMessage.newMessage"
                    label="Type Comment" 
                    variant="outlined"
                    density="compact"
                    rows="2"
                    max-rows="4"
                    auto-grow
                    clearable
                ></VTextarea>
                <div class="text-right">
                    <VBtn
                        prepend-icon="mdi-note-plus"
                        color="black"
                        type="submit"
                    >Add Note</VBtn>
                </div>
            </form>
        </div>
    </Panel>
</template>

<script setup>
import { reactive, ref, onMounted, onBeforeUnmount } from 'vue';
import { usePage } from "@inertiajs/vue3";
import Panel from '@/Layouts/Shared/Panel.vue';
import moment from 'moment';
import axios from 'axios';

const order = usePage().props.order;
const user = usePage().props.auth.user;

const logMessage = reactive({
    newMessage: ""
});

const orderLog = ref([]);
let echoChannel = null;

const sendMessage = async () => {
    const payload = {
        message: logMessage.newMessage,
        orderId: order.id
    }

    const response = await axios.post(route("order.log.write"), payload, {
        headers: {
            "Content-Type": "application/json"
        }
    })

    const data = response.data;
    if (data.status !== undefined) {
        if (data.status == 'success') {
            // Add the message to the thread immediately for the sender
            logMessage.newMessage = "";
            if (data.data) {
                orderLog.value.push(data.data);
            }
        }
    }
}

const loadOrderLog = async () => {
    const response = await axios.get(route("order.log", [order.id]));
    // console.log(response.data.data);
    orderLog.value = response.data;
}

const showNotification = (message, userName) => {
    // Simple browser notification using alert-style notification
    // You can replace this with a toast library if preferred
    if (Notification.permission === "granted") {
        new Notification(`New message from ${userName}`, {
            body: message,
            icon: '/favicon.ico'
        });
    } else {
        // Fallback to console log
        console.log(`New message from ${userName}: ${message}`);
    }
}

const subscribeToChannel = () => {
    // Subscribe to the private channel for this specific order
    echoChannel = window.Echo.private(`order-chat.${order.id}`);
    
    // Listen for new messages
    echoChannel.listen('.new-message', (event) => {
        console.log('New message received:', event);
        
        // Add the new message to the orderLog
        orderLog.value.push({
            id: event.id,
            message: event.message,
            created_at: event.created_at,
            user: event.user
        });
        
        // Show notification
        showNotification(event.message, event.user.name);
    });
}

const unsubscribeFromChannel = () => {
    if (echoChannel) {
        window.Echo.leave(`order-chat.${order.id}`);
        echoChannel = null;
    }
}

onMounted(() => {
    loadOrderLog();
    subscribeToChannel();
    
    // Request notification permission
    if (Notification.permission === "default") {
        Notification.requestPermission();
    }
})

onBeforeUnmount(() => {
    unsubscribeFromChannel();
})
</script>

<style lang="scss" scoped>
.task-card {
  background-color: #fff;
  padding: 15px;
  margin: 10px 0;
  border-radius: 4px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}
</style>
