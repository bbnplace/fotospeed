<template>
    <div v-if="feedbackLog.length">
        <div v-for="(feedback, index) in feedbackLog" :key="index" class="task-card px-2">
            <p class="font-bold ">{{ moment(feedback.created_at).calendar() }}, {{ feedback.staff.mobile == user.mobile ? "I" :  feedback.staff.name }} wrote:</p>
            <p>{{ feedback.note }}</p>
        </div>
    </div>
    <VCard v-else class="p-3 mt-2 text-center" color="grey-lighten-3">
        Customer Feedback help your organisation deliver better customer experiences.
    </VCard>
    <hr />
    <div>
        <form @submit.prevent="sendMessage">
            <VTextarea
                v-model="logMessage.newMessage"
                label="Type Feedback" 
                variant="outlined"
                density="compact"
                rows="2"
                max-rows="4"
                auto-grow
                clearable
            ></VTextarea>
            <div class="text-right">
                <VBtn
                    prepend-icon="mdi-content-save"
                    color="black"
                    type="submit"
                >Save</VBtn>
            </div>
        </form>
    </div>
</template>

<script setup>
import { reactive, ref, onMounted } from 'vue';
import { usePage } from "@inertiajs/vue3";
import moment from 'moment';
import axios from 'axios';

const customer = usePage().props.customer;
const order = usePage().props.order ?? {};
const user = usePage().props.auth.user;

const logMessage = reactive({
    newMessage: ""
});

const feedbackLog = ref([]);

const sendMessage = async () => {
    const payload = {
        message: logMessage.newMessage,
        customerId: customer.id,
        orderId: order.id ?? null,
    }

    const response = await axios.post(route("customer.feedback.write"), payload, {
        headers: {
            "Content-Type": "application/json"
        }
    })

    const data = response.data;
    if (data.status !== undefined) {
        if (data.status == 'success') {
            // Add the message to the thread and refresh.
            logMessage.newMessage = "";
            loadCustomerFeedback();
        }
    }
}

const loadCustomerFeedback = async () => {
    const url = order.id == undefined ? route("customer.feedback", [customer.id]) : route("customer.feedback.order", [customer.id, order.id])
    const response = await axios.get(url);
    feedbackLog.value = response.data;
}

onMounted(() => {
    loadCustomerFeedback();
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
