import axios from 'axios';

const login = {
	namespaced: true,

	state: {
		loading: false,
		token: localStorage.getItem('token') || '',
		user: JSON.parse(localStorage.getItem('user')) || {},
		validationErrors: [],
		accountErrors: ''
	},

	actions: {
		async login({ commit }, { email, password }) {
			commit('loading', true);

			await axios
				.post('/login', {
					email: email,
					password: password
				})
				.then((response) => {
					const token = response.headers.authorization;

					commit('authSuccess', token);
					commit('setUser', response.data.user);
					localStorage.setItem('token', token);
					localStorage.setItem('user', JSON.stringify(response.data.user));
					commit('locale/setUserLocale', response.data.user.locale, {
						root: true
					});
				})
				.catch((error) => {
					if (error.response.status === 401 || error.response.status === 423)
						commit('accountErrors', error.response.data);

					if (error.response.status === 422)
						commit('authErrors', error.response.data.errors);

					throw error;
				})
				.finally(() => {
					commit('loading', false);
				});
		},

		async refresh({ commit }) {
			return await axios
				.get('/refresh')
				.then((response) => {
					const token = response.headers.authorization;
					localStorage.setItem('token', token);
					commit('refreshToken', token);
				})
				.catch((error) => {
					throw error;
				});
		},

		logout({ commit }) {
			localStorage.removeItem('token');
			localStorage.removeItem('user');
			commit('authReset');
		}
	},

	mutations: {
		loading(state, newLoadingStatus) {
			state.loading = newLoadingStatus;
		},

		setUser(state, user) {
			state.user = user;
		},

		refreshToken(state, token) {
			state.token = token;
			axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
		},

		authSuccess(state, token, user) {
			state.token = token;
			state.user = user;
			axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
		},

		authReset(state) {
			state.token = '';
			state.user = {};
			state.validationErrors = '';
			state.accountErrors = '';
			delete axios.defaults.headers.common['Authorization'];
		},

		authErrors(state, errors) {
			state.validationErrors = errors;
			state.accountErrors = '';
		},

		accountErrors(state, errors) {
			state.accountErrors = errors;
			state.validationErrors = '';
		}
	},

	getters: {
		isAuthenticated(state) {
			return state.token !== '';
		},

		isAdmin(state) {
			return state.user && state.user.account_role === 'admin';
		},

		isTeacher(state) {
			return state.user && state.user.account_role === 'teacher';
		},

		isStudent(state) {
			return state.user && state.user.account_role === 'student';
		}
	}
};

export default login;
