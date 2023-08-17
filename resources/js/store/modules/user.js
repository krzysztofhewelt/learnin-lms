import dayjs from 'dayjs';
import axios from 'axios';

const user = {
	namespaced: true,

	state: {
		loading: false,

		validationErrors: [],
		user: {
			active: 1
		},
		// student fields of study
		student: [],
		// teacher information
		teacher: {},

		courses: [],
		tasks: [],
		marks: [],
		marksPaginated: []
	},

	actions: {
		async getDashboard({ commit }) {
			commit('setLoading', true);

			await axios
				.get('/dashboard')
				.then((response) => {
					commit('setUser', response.data.user);
					commit('setStudent', response.data.user.student);
					commit('setTeacher', response.data.user.teacher);
					commit('setCourses', response.data.courses);
					commit('setTasks', response.data.tasks);
					commit('setMarks', response.data.marks);
				})
				.finally(() => {
					commit('setLoading', false);
				});
		},

		async getUserDetails({ commit }, userId = '') {
			commit('setLoading', true);
			commit('clearValidationErrors');

			const userUrl = userId === '' ? '/profile' : '/users/show/' + userId;

			await axios
				.get(userUrl)
				.then((response) => {
					commit('setUser', {
						id: response.data.id,
						name: response.data.name,
						surname: response.data.surname,
						account_role: {
							name: response.data.account_role,
							label: response.data.account_role
						},
						identification_number: response.data.identification_number,
						email: response.data.email,
						active: response.data.active
					});

					if (response.data.teacher) commit('setTeacher', response.data.teacher);

					commit('setStudent', response.data.student);
				})
				.finally(() => {
					commit('setLoading', false);
				});
		},

		async createNewUserOrEdit({ commit }, { user, student, teacher }) {
			commit('setLoading', true);

			if (!user.id && !user.password) delete user.password;

			let dataUserObject = {
				name: user.name,
				surname: user.surname,
				account_role: user.account_role?.name,
				identification_number: user.identification_number,
				email: user.email,
				password: user.password,
				active: user.active
			};

			if (user.account_role?.name === 'student')
				dataUserObject['student'] = student;

			if (user.account_role?.name === 'teacher')
				dataUserObject['teacher'] = teacher;

			const postUrl = !user.id
				? '/users/create-or-edit/0'
				: '/users/create-or-edit/' + user.id;

			return await axios
				.put(postUrl, dataUserObject)
				.then((response) => {
					commit('clearValidationErrors');
					return response;
				})
				.catch((error) => {
					commit('setValidationErrors', error.response.data.errors);
					throw error;
				})
				.finally(() => {
					commit('setLoading', false);
				});
		},

		async deleteUser({ commit }, userId) {
			commit('setLoading', true);

			await axios.delete('/users/delete/' + userId).finally(() => {
				commit('setLoading', false);
			});
		},

		async getUserCourses({ dispatch, commit }, userId = '') {
			commit('setLoading', true);

			const coursesUrl = userId === '' ? '/courses' : '/users/courses/' + userId;

			await axios
				.get(coursesUrl)
				.then((response) => {
					commit('setCourses', response.data);
				})
				.finally(() => {
					commit('setLoading', false);
				});
		},

		async getUserTasks({ dispatch, commit }, userId = '') {
			commit('setLoading', true);

			const tasksUrl = userId === '' ? '/tasks' : '/user/' + userId + '/tasks';

			await axios
				.get(tasksUrl)
				.then((response) => {
					// paginate tasks or not
					commit('setTasks', response.data);
				})
				.finally(() => {
					commit('setLoading', false);
				});
		},

		async getCategoryTasks({ commit }, courseId) {
			commit('setLoading', true);

			await axios
				.get('/tasks/show/category/' + courseId)
				.then((response) => {
					commit('setTasks', response.data);
				})
				.finally(() => {
					commit('setLoading', false);
				});
		},

		async getUserMarks({ commit }, { page = 1 }) {
			commit('setLoading', true);

			await axios
				.get('/marks', {
					params: {
						page: page
					}
				})
				.then((response) => {
					commit('setMarksPaginated', response.data);
				})
				.finally(() => {
					commit('setLoading', false);
				});
		},

		async updateTeacherInformation({ commit }, { userId, teacher }) {
			commit('setLoading', true);

			await axios
				.patch('/users/update-teacher/' + userId, teacher)
				.then(() => {
					commit('clearValidationErrors');
					commit('setTeacher', teacher);
				})
				.catch((error) => {
					commit('setValidationErrors', error.response.data.errors);

					throw error;
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

		setValidationErrors(state, validationErrors) {
			state.validationErrors = validationErrors;
		},

		clearValidationErrors(state) {
			state.validationErrors = [];
		},

		resetUser(state) {
			state.user = {
				active: 1
			};

			state.student = [];
			state.teacher = {};

			state.courses = [];
			state.tasks = [];
			state.marks = [];
		},

		setUser(state, user) {
			state.user = user;
		},

		setStudent(state, student) {
			state.student = student;
		},

		setTeacher(state, teacher) {
			state.teacher = teacher;
		},

		setCourses(state, courses) {
			state.courses = courses;
		},

		setTasks(state, tasks) {
			state.tasks = tasks;
		},

		setMarks(state, marks) {
			state.marks = marks;
		},

		setMarksPaginated(state, marks) {
			state.marksPaginated = marks;
		}
	},

	getters: {
		isUserAdmin(state) {
			return state.user.account_role?.name === 'admin';
		},

		isUserStudent(state) {
			return state.user.account_role?.name === 'student';
		},

		isUserTeacher(state) {
			return state.user.account_role?.name === 'teacher';
		},

		isOwnAccount(state, _, rootState) {
			return state.user.id === rootState.login.user.id;
		},

		getActiveCourses(state) {
			return state.courses?.filter(
				(course) =>
					dayjs() >= dayjs(course.available_from) &&
					(dayjs() <= dayjs(course.available_to) || !course.available_to)
			);
		},

		getEndedCourses(state) {
			return state.courses?.filter(
				(course) => course.available_to && dayjs() > dayjs(course.available_to)
			);
		},

		getUpcomingCourses(state) {
			return state.courses?.filter((course) => dayjs() < dayjs(course.available_from));
		},

		getActiveTasks(state) {
			return state.tasks?.filter(
				(task) =>
					dayjs() >= dayjs(task.available_from) &&
					(dayjs() <= dayjs(task.available_to) || !task.available_to)
			);
		},

		getExpiredTasks(state) {
			return state.tasks?.filter(
				(task) => task.available_to && dayjs() > dayjs(task.available_to)
			);
		},

		getMarkedTasks(state) {
			return state.tasks?.filter((task) => task.user_marks?.length > 0);
		},

		getUpcomingTasks(state) {
			return state.tasks?.filter((task) => dayjs() < dayjs(task.available_from));
		},

		getTeachersInCourse: () => (course) => {
			return course.users?.filter((user) => user.account_role === 'teacher');
		}
	}
};

export default user;
