import axios from 'axios';
import router from '@/router';
import { useToast } from 'vue-toastification';
import store from '@/store';
import login from '@/store/modules/login';
import { trans } from 'laravel-vue-i18n';

const toast = useToast();

export default function setup() {
	axios.defaults.baseURL = import.meta.env.API_URL;
	axios.defaults.headers.common['Authorization'] = 'Bearer ' + login.state.token;
	axios.defaults.headers.post['Content-Type'] = 'application/json';

	axios.interceptors.response.use(
		(response) => {
			return response;
		},
		(error) => {
			if (!error.response) {
				return Promise.reject(error);
			}

			if (
				error.response.status === 401 &&
				error.response.config.url !== '/login' &&
				error.response.config.url !== '/refresh'
			) {
				return store
					.dispatch('login/refreshToken')
					.then(() => {
						error.config.headers = {
							Authorization: `Bearer ${login.state.token}`
						};

						error.config.data = error.config.data ? JSON.parse(error.config.data) : '';

						return axios(error.config);
					})
					.catch((error) => {
						if (error.response.status === 401) {
							toast.info(trans('general.token_expired'));
							return router.push('/login');
						}

						throw error;
					});
			}

			if (error.response.status === 403) {
				toast.error(trans('general.forbidden'));
				return router.push('/');
			}

			if (error.response.status === 404) {
				toast.error(trans('general.not_exists'));
				return router.push('/');
			}

			if (error.response.status === 500) {
				toast.error(trans('general.bad_request'));
				return router.push('/');
			}

			return Promise.reject(error);
		}
	);
}
