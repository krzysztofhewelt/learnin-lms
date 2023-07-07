const admin = {
	namespaced: true,

	// all arrays have pagination
	state: {
		loading: false,
		users: [],
		courses: [],
		tasks: []
	},

	actions: {
		async getAllUsers({ commit }, page = 1) {
			commit('loading', true);

			await axios.get('/admin/users?page=' + page).then((response) => {
				commit('setUsers', response.data);
			});

			commit('loading', false);
		},

		async getUser({ commit }, { search: searchString, page = 1 }) {
			commit('loading', true);

			await axios
				.post('/users/search?page=' + page, {
					searchString: searchString
				})
				.then((response) => {
					commit('setUsers', response.data);
				});

			commit('loading', false);
		},

		async getAllCourses({ commit }, page = 1) {
			commit('loading', true);

			await axios.get('/admin/courses?page=' + page).then((response) => {
				commit('setCourses', response.data);
			});

			commit('loading', false);
		},

		async getCourse({ commit }, { search: searchString, page = 1 }) {
			commit('loading', true);

			await axios
				.post('/courses/search?page=' + page, {
					searchString: searchString
				})
				.then((response) => {
					commit('setCourses', response.data);
				});

			commit('loading', false);
		},

		async getAllTasks({ commit }, page = 1) {
			commit('loading', true);

			await axios.get('/admin/tasks?page=' + page).then((response) => {
				commit('setTasks', response.data);
			});

			commit('loading', false);
		},

		async getTask({ commit }, { search: searchString, page = 1 }) {
			commit('loading', true);

			await axios
				.post('/tasks/search?page=' + page, {
					searchString: searchString
				})
				.then((response) => {
					commit('setTasks', response.data);
				});

			commit('loading', false);
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
