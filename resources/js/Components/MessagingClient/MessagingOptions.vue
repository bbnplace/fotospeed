<template>
    <VTabs v-model="tab">
        <!-- <VTab prepend-icon="mdi-sim" value="hostedsim">Hosted SIM</VTab> -->
        <VTab prepend-icon="mdi-whatsapp" value="whatsapp">WhatsApp</VTab>
        <!-- <VTab prepend-icon="mdi-email" value="email">Email</VTab> -->
        <VTab prepend-icon="mdi-text" value="a2psms">A2P SMS</VTab>
    </VTabs>
    <VWindow v-model="tab" direction="vertical">
        <!-- <VWindowItem value="hostedsim">
            <HostedSim />
        </VWindowItem> -->
        <VWindowItem value="whatsapp">
            <Whatsapp />
        </VWindowItem>
        <!-- <VWindowItem value="email">
            <Email />
        </VWindowItem> -->
        <VWindowItem value="a2psms">
            <A2PSms />
        </VWindowItem>
    </VWindow>
</template>

<script setup>
import { ref } from "vue";
import Email from "@/Components/MessagingClient/Email.vue";
import HostedSim from "@/Components/MessagingClient/HostedSim.vue";
import Whatsapp from "@/Components/MessagingClient/Whatsapp.vue";
import A2PSms from "@/Components/MessagingClient/A2PSms.vue";

const tab = ref(null);

</script>

<style lang="scss" scoped>

</style>