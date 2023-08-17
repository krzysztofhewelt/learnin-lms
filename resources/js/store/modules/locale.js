const locale = {
	namespaced: true,

	state: {
		locale: localStorage.getItem('locale') || import.meta.env.LOCALE_DEFAULT,
		loading: false
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
				})
				.finally(() => {
					commit('setLoading', false);
				});
		}
	},

	mutations: {
        setLoading(state, newLoadingStatus) {
            state.loading = newLoadingStatus;
        },

		setUserLocale(state, locale) {
			state.locale = locale;
			localStorage.setItem('locale', locale);
		}
	}
};

export default locale;
