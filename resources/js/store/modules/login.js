import axios from 'axios';

const login = {
	namespaced: true,

	state: {
		loading: false,
		token: localStorage.getItem('token') || '',
		user: JSON.parse(localStorage.getItem('user')) || {},
		validationErrors: []
	},

	actions: {
		async login({ commit }, { email, password }) {
			commit('setLoading', true);

			await axios
				.post('/login', {
					email: email,
					password: password,
					locale: this.state.locale.locale
				})
				.then((response) => {
					const token = response.headers.authorization;
					const user = response.data.user;

					commit('authSuccess', { token, user });
					commit('locale/setUserLocale', response.data.user.locale, { root: true });
				})
				.catch((error) => {
					if (
						error.response.status === 401 ||
						error.response.status === 422 ||
						error.response.status === 429
					)
						commit('loginErrors', error.response.data.errors);

					throw error;
				})
				.finally(() => {
					commit('setLoading', false);
				});
		},

		async refreshToken({ commit }) {
			return await axios
				.get('/refresh')
				.then((response) => {
					const token = response.headers.authorization;

					commit('refreshToken', token);
				})
				.catch((error) => {
					throw error;
				});
		},

		logout({ commit }) {
			commit('authReset');
		}
	},

	mutations: {
		setLoading(state, newLoadingStatus) {
			state.loading = newLoadingStatus;
		},

		refreshToken(state, token) {
			state.token = token;
			localStorage.setItem('token', token);
			axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
		},

		authSuccess(state, { token, user }) {
			state.token = token;
			state.user = user;
			localStorage.setItem('token', token);
			localStorage.setItem('user', JSON.stringify(user));
			axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
		},

		authReset(state) {
			state.token = '';
			state.user = {};
			state.validationErrors = '';
			localStorage.removeItem('token');
			localStorage.removeItem('user');
			delete axios.defaults.headers.common['Authorization'];
		},

		loginErrors(state, errors) {
			state.validationErrors = errors;
		}
	},

	getters: {
		isAdmin(state) {
			return state.user?.account_role === 'admin';
		},

		isTeacher(state) {
			return state.user?.account_role === 'teacher';
		},

		isStudent(state) {
			return state.user?.account_role === 'student';
		}
	}
};

export default login;
