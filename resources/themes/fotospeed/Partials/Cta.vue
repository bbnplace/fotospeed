<template>
    <!-- Cta Section Start -->
    <section class="cta-section-3">
        <div class="container">
            <div class="cta-wrapper-3 bg-cover" :style="{backgroundImage: `url('${site.url}/assets/img/cta-bg-3.jpg')`}">
                <div class="cta-content">
                    <h2 class="split-text right">
                        Ready to Create Some <br>
                        Custome Products?
                    </h2>
                    <a :href="`${site.url}/contact.html`" class="theme-btn wow fadeInUp" data-wow-delay=".5s">Contact Us</a>
                </div>
                <div class="cta-shape wow fadeInUp" data-wow-delay=".3s"> 
                    <img :src="`${site.url}/assets/img/cta-2-shape2.png`" alt="img">
                </div>
            </div>
        </div>
    </section>
</template>
<script setup>
import { usePage } from '@inertiajs/vue3';
const site = usePage().props.site;

</script>