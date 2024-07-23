import './bootstrap';
import '../css/app.css';
import 'primevue/resources/themes/saga-blue/theme.css';      // thème de PrimeVue
import 'primevue/resources/primevue.min.css';                // CSS principal de PrimeVue
import 'primeicons/primeicons.css';  
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import PrimeVue from 'primevue/config';
import ToastService from 'primevue/toastservice';
import MultiSelect from 'primevue/multiselect';
import InputText from 'primevue/inputtext';
import Aura from '@primevue/themes/aura';
import Card from 'primevue/card';
import Paginator from 'primevue/paginator';

/* import font awesome icon component */
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
/* import the fontawesome core */
import { library } from '@fortawesome/fontawesome-svg-core'


/* import specific icons */
import { fas } from '@fortawesome/free-solid-svg-icons'

/* add icons to the library */
library.add(fas)


const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(PrimeVue, {
                theme: {
                    preset: Aura,
                    options: {
                        prefix: 'p',
                        darkModeSelector: 'system',
                        cssLayer: false
                    }
                }
             })
            .use(ToastService)
            .component('font-awesome-icon', FontAwesomeIcon)
            .component('MultiSelect', MultiSelect)
            .component('InputText', InputText)
            .component('Card', Card)
            .component('Paginator', Paginator)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
