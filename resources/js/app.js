import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import PrimeVue from 'primevue/config';
import Avatar from 'primevue/avatar';
import Button from 'primevue/button';
import Card from 'primevue/card';
import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import Dropdown from 'primevue/dropdown';
import MultiSelect from 'primevue/multiselect';
import InputText from 'primevue/inputtext';
import DatePicker from 'primevue/datepicker';
import Dialog from 'primevue/dialog';
import Tag from 'primevue/tag';
import Toast from 'primevue/toast';
import ToastService from 'primevue/toastservice';
import InputNumber from 'primevue/inputnumber';
import Aura from '@primevue/themes/aura';
import 'primeicons/primeicons.css';
import '../css/app.css';
const appName = import.meta.env.VITE_APP_NAME || 'SchoolApp';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) => resolvePageComponent(`./pages/${name}.vue`, import.meta.glob('./pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const vueApp = createApp({ render: () => h(App, props) });
        vueApp.use(plugin);
        vueApp.use(PrimeVue, {
            ripple: true,
            theme: {
                preset: Aura,
            },
        });
        vueApp.use(ToastService);
        vueApp.component('PAvatar', Avatar);
        vueApp.component('PButton', Button);
        vueApp.component('PCard', Card);
        vueApp.component('PColumn', Column);
        vueApp.component('PDataTable', DataTable);
        vueApp.component('PDropdown', Dropdown);
        vueApp.component('PMultiSelect', MultiSelect);
        vueApp.component('PInputText', InputText);
        vueApp.component('PDatePicker', DatePicker);
        vueApp.component('PInputNumber', InputNumber);
        vueApp.component('PTag', Tag);
        vueApp.component('PDialog', Dialog);
        vueApp.component('PToast', Toast);
        vueApp.mount(el);
    },
    progress: {
        color: '#0f766e',
    },
});
