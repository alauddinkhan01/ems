import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp, Link} from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import Toaster from "@meforma/vue-toaster";
import axios from 'axios';
import VueAxios from 'vue-axios';
import { $vfm, VueFinalModal, ModalsContainer } from 'vue-final-modal';
import eventBus from 'vue3-eventbus'
import moment  from 'moment';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, app, props, plugin }) {
        const VueApp = createApp({ render: () => h(app, props) });

        VueApp.config.globalProperties.$moment = moment;

        VueApp.use(plugin)
            .mixin({ methods: { route } })
            .component("Link", Link)
            .component('font-awesome-icon', FontAwesomeIcon)
            .component('VueFinalModal',VueFinalModal)
            .component('ModalsContainer',ModalsContainer)
            .use(Toaster)
            .use(eventBus)
            .use(ZiggyVue)
            .use(VueAxios, axios)
            .mount(el);
    },
});

InertiaProgress.init({ color: '#4B5563' });
