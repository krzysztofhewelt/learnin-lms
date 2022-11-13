import './bootstrap';

import { createApp } from 'vue';
import App from '@/App.vue';
import { i18nVue } from 'laravel-vue-i18n';
import Toast from 'vue-toastification';
import 'vue-toastification/dist/index.css';
import router from '@/router';
import store from '@/store/index';
import '../css/app.css';
import axiosSetup from '@/helpers/axiosSetup';
import '@/assets/popper-theme.css';
import '@/assets/vue-multiselect.min.css';
import '@/assets/vue-multiselect-tailwind-fix.css';

const app = createApp(App);

// axios settings
axiosSetup();

// libraries
app.use(store)
	.use(Toast, {
		timeout: 2500,
		maxToasts: 20,
		newestOnTop: true
	})
	.use(router)
	.use(i18nVue, {
		lang: 'en',
		resolve: (lang) => {
			const langs = import.meta.globEager('../../lang/*.json');
			return langs[`../../lang/${lang}.json`].default;
		}
	});

app.mount('#app');
