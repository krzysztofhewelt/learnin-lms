const admin = {
	namespaced: true,

	// all arrays contains pagination links
	state: {
		loading: false,
		users: [],
		courses: [],
		tasks: []
	},

	actions: {
		async getUsers({ commit }, { search: searchString, page = 1 }) {
			commit('loading', true);

			await axios
				.get('/admin/users', {
					params: {
						page: page,
						search: searchString
					}
				})
				.then((response) => {
					commit('setUsers', response.data);
				})
				.finally(() => {
					commit('loading', false);
				});
		},

		async getCourses({ commit }, { search: searchString, page = 1 }) {
			commit('loading', true);

			await axios
				.get('/admin/courses', {
					params: {
						page: page,
						search: searchString
					}
				})
				.then((response) => {
					commit('setCourses', response.data);
				})
				.finally(() => {
					commit('loading', false);
				});
		},

		async getTasks({ commit }, { search: searchString, page = 1 }) {
			commit('loading', true);

			await axios
				.get('/admin/tasks', {
					params: {
						page: page,
						search: searchString
					}
				})
				.then((response) => {
					commit('setTasks', response.data);
				})
				.finally(() => {
					commit('loading', false);
				});
		}
	},

	mutations: {
		loading(state, newLoadingStatus) {
			state.loading = newLoadingStatus;
		},

		setUsers(state, users) {
			state.users = users;
		},

		setCourses(state, courses) {
			state.courses = courses;
		},

		setTasks(state, tasks) {
			state.tasks = tasks;
		}
	}
};

export default admin;
