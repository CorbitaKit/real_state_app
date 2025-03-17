import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import PrimeVue from 'primevue/config';
import 'primevue/resources/themes/aura-light-green/theme.css'
import Message from 'primevue/message';
import Menu from 'primevue/menu';
import { OhVueIcon } from "oh-vue-icons";
import DataTable from 'primevue/datatable';
import ColumnGroup from 'primevue/columngroup';
import Row from 'primevue/row';
import Column from 'primevue/column';
import Card from 'primevue/card';
import './Pages/plugins/icon-plugin'
import 'primeicons/primeicons.css'
import Vue3FormWizard from 'vue3-form-wizard'
import 'vue3-form-wizard/dist/style.css'
import { createPinia } from 'pinia'
import Toast from 'primevue/toast';
import ToastService from 'primevue/toastservice';
import ColorPicker from 'primevue/colorpicker';
import Dropdown from 'primevue/dropdown';
import InputText from 'primevue/inputtext';
import InputNumber from 'primevue/inputnumber';
import ConfirmDialog from 'primevue/confirmdialog';
import ConfirmationService from 'primevue/confirmationservice';
import Button from 'primevue/button';
import InputMask from 'primevue/inputmask';
import Calendar from 'primevue/calendar';
import Image from 'primevue/image';
import Tag from 'primevue/tag';
import Dialog from 'primevue/dialog';
import Chart from 'primevue/chart';
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import '@mdi/font/css/materialdesignicons.css'
import Password from 'primevue/password';
import Tooltip from 'primevue/tooltip';
import ProgressBar from 'primevue/progressbar';
import Checkbox from 'primevue/checkbox';

import ToggleButton from 'primevue/togglebutton';

import { FilterMatchMode } from 'primevue/api';

// import 'vuetify/styles'

const vuetify = createVuetify({
  components,
  directives,
  ssr: true,
})

const pinia = createPinia()

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    return pages[`./Pages/${name}.vue`]

  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .directive('tooltip', Tooltip)
      .use(plugin)
      .use(PrimeVue)
      .use(vuetify)
      .use(Vue3FormWizard)
      .use(pinia)
      .use(ToastService)
      .use(ConfirmationService)
      .component('Message', Message)
      .component('Menu', Menu)
      .component('v-icon', OhVueIcon)
      .component('DataTable', DataTable)
      .component('Column', Column)
      .component('Card', Card)
      .component('Toast', Toast)
      .component('ColorPicker', ColorPicker)
      .component('Dropdown', Dropdown)
      .component('InputText', InputText)
      .component('InputNumber', InputNumber)
      .component('ConfirmDialog', ConfirmDialog)
      .component('Button', Button)
      .component('InputMask', InputMask)
      .component('Calendar', Calendar)
      .component('Image', Image)
      .component('Tag', Tag)
      .component('Dialog', Dialog)
      .component('Chart', Chart)
      .component('Password', Password)
      .component('ProgressBar', ProgressBar)
      .component('ColumnGroup', ColumnGroup)
      .component('Checkbox', Checkbox)
      .component('Row', Row)
      .component('ToggleButton', ToggleButton)
      .mount(el)
  },
})
