<template>
    <footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="footer-links d-none d-md-block">
                    <!-- <a href="javascript:void(0)" @click="showAbout = true">About</a> -->
                    <a href="javascript:void(0)" @click="showReportIssue = true">Report Issues</a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-md-end">
                    Powered by <a href="https://cecula.com" target="_blank" style="color: #ff2500;">Cecula</a>.
                </div>
            </div>
        </div>
    </div>
    <AboutModal v-model="showAbout" />
    <ReportIssueModal v-model="showReportIssue" />
</footer>
</template>

<script setup>
import { ref } from 'vue';
import AboutModal from '@/Components/AboutModal.vue';
import ReportIssueModal from '@/Components/ReportIssueModal.vue';

const showAbout = ref(false);
const showReportIssue = ref(false);
</script>

<style lang="scss" scoped>

</style>
