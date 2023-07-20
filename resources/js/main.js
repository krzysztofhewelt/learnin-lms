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
import '@/assets/vue-multiselect-tailwind.css';
import VueObserveVisibility from 'vue3-observe-visibility';

const app = createApp(App);

// axios settings
axiosSetup();

// libraries
app.use(store)
	.use(Toast, {
		newestOnTop: true,
		maxToasts: 20,
		showCloseButtonOnHover: true,
		timeout: 2500
	})
	.use(router)
	.use(i18nVue, {
		lang: import.meta.env.LOCALE_DEFAULT,
		resolve: (lang) => {
			const langs = import.meta.globEager('../../lang/php_*.json');
			return langs[`../../lang/php_${lang}.json`].default;
		}
	})
	.use(VueObserveVisibility);

app.mount('#app');
