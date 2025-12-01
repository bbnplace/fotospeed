import './bootstrap';
import '@/../css/app.css';
import 'vuetify/styles';
import '@mdi/font/css/materialdesignicons.css';

// import '@/../../public/assets/themes/fotospeed.css';

import "@/../../public/assets/css/bootstrap.min.css"
import "@/../../public/assets/css/font-awesome.css"
import "@/../../public/assets/css/animate.css"
import "@/../../public/assets/css/magnific-popup.css"
import "@/../../public/assets/css/meanmenu.css"
import "@/../../public/assets/css/swiper-bundle.min.css"
import "@/../../public/assets/css/nice-select.css"
import "@/../../public/assets/css/main.css"
import "@/../../public/assets/css/style.css"



import "@/../../public/assets/js/jquery-3.7.1.min.js"
import "@/../../public/assets/js/viewport.jquery.js"
import "@/../../public/assets/js/bootstrap.bundle.min.js"

import "@/../../public/assets/js/jquery.nice-select.min.js"
import "@/../../public/assets/js/jquery.waypoints.js"
import "@/../../public/assets/js/jquery.counterup.min.js"
import "@/../../public/assets/js/swiper-bundle.min.js"
import "@/../../public/assets/js/jquery.meanmenu.min.js"
import "@/../../public/assets/js/jquery.magnific-popup.min.js"
// WOW.js is loaded via script tag in app.blade.php to ensure proper global initialization
import "@/../../public/assets/js/main.js"



import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '@/../../vendor/tightenco/ziggy/dist/vue.m';

import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

const vuetify = createVuetify({
    components,
    directives,
})

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(vuetify)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#0594be',
        showSpinner: false
    },
    finish: () => {
        // Hide preloader after Inertia finishes initial page load
        if (typeof $ !== 'undefined') {
            $(".preloader").addClass('loaded');
            $(".preloader").delay(600).fadeOut();
        }
    },
});
