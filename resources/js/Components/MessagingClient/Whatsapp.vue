<template>
    <div class="chat-window mb-4" v-if="messageThreads.length">
        <div v-for="(message, index) in messageThreads" :key="index" 
             :class="['d-flex', message.direction === 'out' ? 'justify-end' : 'justify-start']">
            <div :class="['message-bubble', message.direction === 'out' ? 'sent' : 'received']">
                <p class="mb-1">{{ message.body }}</p>
                <small class="text-caption" :class="message.direction === 'out' ? 'text-grey-lighten-3' : 'text-grey-darken-1'">
                    {{ moment(message.created_at).calendar() }}
                </small>
            </div>
        </div>
    </div>
    <VCard v-else class="p-3 mt-2 text-center" color="grey-lighten-3">
        You have not sent any Whatsapp Message to this customer.
    </VCard>
    <hr />
    <div>
        <form @submit.prevent="sendMessage">
            <VTextarea
                v-model="logMessage.newMessage"
                label="Type Text" 
                variant="outlined"
                rows="2"
                auto-grow
            ></VTextarea>
            <div class="text-right">
                <VBtn
                    color="success"
                    type="submit"
                    prepend-icon="mdi-send"
                    :loading="sending"
                >Send</VBtn>
            </div>
        </form>
    </div>
</template>

<script setup>
import { reactive, ref, onMounted, nextTick } from 'vue';
import { usePage } from "@inertiajs/vue3";
import Panel from '@/Layouts/Shared/Panel.vue';
import moment from 'moment';
import axios from 'axios';

const customer = usePage().props.customer;
const user = usePage().props.auth.user;

const logMessage = reactive({
    newMessage: ""
});

const messageThreads = ref([]);
const sending = ref(false);

const sendMessage = async () => {
    if (!logMessage.newMessage.trim()) return;
    
    sending.value = true;
    const payload = {
        message: logMessage.newMessage,
        mobile: customer.mobile // Changed from customerMobile to match controller validation
    }

    try {
        const response = await axios.post(route("customer.whatsapp.write"), payload, {
            headers: {
                "Content-Type": "application/json"
            }
        })

        const data = response.data;
        if (data.status !== undefined) {
            if (data.status == 'success') {
                // Add the message to the thread and refresh.
                logMessage.newMessage = "";
                loadMessages();
            }
        }
    } catch (error) {
        console.error("Failed to send message", error);
    } finally {
        sending.value = false;
    }
}

const loadMessages = async () => {
    const response = await axios.get(route("customer.whatsapp.log", [customer.mobile]));
    messageThreads.value = response.data;
    scrollToBottom();
}

const scrollToBottom = () => {
    nextTick(() => {
        const container = document.querySelector('.chat-window');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    });
}

onMounted(() => {
    loadMessages();
})
</script>

<style lang="scss" scoped>
.chat-window {
    max-height: 400px;
    overflow-y: auto;
    padding: 10px;
    background-color: #f5f5f5;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
}

.message-bubble {
    max-width: 80%;
    padding: 10px 15px;
    margin-bottom: 10px;
    border-radius: 15px;
    position: relative;
    word-wrap: break-word;
}

.sent {
    background-color: #128C7E; /* WhatsApp Green */
    color: white;
    border-bottom-right-radius: 2px;
}

.received {
    background-color: white;
    color: black;
    border-bottom-left-radius: 2px;
    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
}
</style>
