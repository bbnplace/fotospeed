<template>
    <div v-if="messageThreads.length">
        <div v-for="(message, index) in messageThreads" :key="index" class="task-card px-2">
            <p class="font-bold ">{{ moment(message.created_at).calendar() }}</p>
            <p>{{ message.subject }}</p>
        </div>
    </div>
    <VCard v-else class="p-3 mt-2 text-center" color="grey-lighten-3">
        You have not sent any email to this customer.
    </VCard>
    <hr />
    <div>
        <form @submit.prevent="sendMessage">
            <VTextField
                v-model="logMessage.subject"
                variant="outlined"
                label="Subject"
            ></VTextField>
            <VTextarea
                v-model="logMessage.newMessage"
                label="Type Email Body" 
                variant="outlined"
            ></VTextarea>
            <div class="text-right">
                <VBtn
                    color="black"
                    type="submit"
                    prepend-icon="mdi-send"
                >Send</VBtn>
            </div>
        </form>
    </div>
</template>

<script setup>
import { reactive, ref, onMounted } from 'vue';
import { usePage } from "@inertiajs/vue3";
import Panel from '@/Layouts/Shared/Panel.vue';
import moment from 'moment';
import axios from 'axios';

const customer = usePage().props.customer;
const user = usePage().props.auth.user;

const logMessage = reactive({
    subject: "",
    newMessage: ""
});

const messageThreads = ref([]);

const sendMessage = async () => {
    const payload = {
        subject: logMessage.subject,
        message: logMessage.newMessage,
        customerEmail: customer.email
    }

    const response = await axios.post(route("customer.email.write"), payload, {
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
}

const loadMessages = async () => {
    const response = await axios.get(route("customer.email.log", [customer.mobile]));
    messageThreads.value = response.data;
}

onMounted(() => {
    loadMessages();
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
