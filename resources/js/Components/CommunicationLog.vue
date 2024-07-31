<template>
    <Panel snippet-title="Communication Log">
        <div v-if="orderLog.length">
            <div v-for="(note, index) in orderLog" :key="index" class="task-card px-2">
                <p class="font-bold ">{{ moment(note.created_at).calendar() }}, {{ note.user.mobile == user.mobile ? "I" :  note.user.name }} wrote:</p>
                <p>{{ note.message }}</p>
            </div>
        </div>
        <VCard v-else class="p-3 text-center" color="grey-lighten-3">
            Leave a note for other team members working on this Order.
        </VCard>
        <hr />
        <div>
            <form @submit.prevent="sendMessage">
                <VTextarea
                    v-model="logMessage.newMessage"
                    label="Type Comment" 
                    variant="outlined"
                ></VTextarea>
                <div class="text-right">
                    <VBtn
                        color="black"
                        type="submit"
                    >Add Note</VBtn>
                </div>
            </form>
        </div>
    </Panel>
</template>

<script setup>
import { reactive, ref, onMounted } from 'vue';
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
            // Add the message to the thread and refresh.
            logMessage.newMessage = "";
            loadOrderLog();
        }
    }
}

const loadOrderLog = async () => {
    const response = await axios.get(route("order.log", [order.id]));
    // console.log(response.data.data);
    orderLog.value = response.data;
}

onMounted(() => {
    loadOrderLog();
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
