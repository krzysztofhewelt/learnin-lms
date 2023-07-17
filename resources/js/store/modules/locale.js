const locale = {
	namespaced: true,

	state: {
		locale: localStorage.getItem('locale') || import.meta.env.LOCALE_DEFAULT,
		loading: false
	},

	mutations: {
		setUserLocale(state, locale) {
			state.locale = locale;
			localStorage.setItem('locale', locale);
		},

		setLoading(state, newLoadingStatus) {
			state.loading = newLoadingStatus;
		}
	},

	actions: {
		setLocale({ commit }, locale) {
			commit('setLoading', true);

			axios
				.post('/locale', {
					userLocale: locale
				})
				.then(() => {
					commit('setUserLocale', locale);
					commit('setLoading', false);
				});
		}
	}
};

export default locale;
