const locale = {
	namespaced: true,

	state: {
		locale: localStorage.getItem('locale') || process.env.DEFAULT_LOCALE
	},

	mutations: {
		setUserLocale(state, locale) {
			state.locale = locale;
			localStorage.setItem('locale', locale);
		}
	},

	actions: {
		setLocale({ commit }, locale) {
			axios
				.post('/locale', {
					userLocale: locale
				})
				.then(() => {
					commit('setUserLocale', locale);
				});
		}
	}
};

export default locale;
