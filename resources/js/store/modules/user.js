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
		marks: []
	},

	actions: {
		async getUserDetails({ commit }, userId = '') {
			commit('loading', true);
			commit('clearValidationErrors');

			const userUrl = userId === '' ? '/profile' : '/users/show/' + userId;

			await axios.get(userUrl).then((response) => {
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
					active: response.data.active,
					locale: {
						name: response.data.locale,
						label: response.data.locale
					}
				});

				if (response.data.teacher !== null) commit('setTeacher', response.data.teacher);

				commit('setStudent', response.data.student);
			});

			commit('loading', false);
		},

		async createNewUserOrEdit({ commit }, { user, student, teacher }) {
			commit('loading', true);

			if (!user.id && user.password === '') delete user.password;

			let dataUserObject = {
				name: user.name,
				surname: user.surname,
				account_role: user.account_role && user.account_role.name,
				identification_number: user.identification_number,
				email: user.email,
				password: user.password,
				active: user.active,
				userLocale: user.locale && user.locale.name
			};

			if (user.account_role && user.account_role.name === 'student')
				dataUserObject['student'] = student;

			if (user.account_role && user.account_role.name === 'teacher')
				dataUserObject['teacher'] = teacher;

			const postUrl = !user.id
				? '/users/create-or-edit/0'
				: '/users/create-or-edit/' + user.id;

			return await axios
				.post(postUrl, dataUserObject)
				.then((response) => {
					commit('clearValidationErrors');
					commit('loading', false);

					return response;
				})
				.catch((error) => {
					commit('setValidationErrors', error.response.data.errors);
					commit('loading', false);

					throw error;
				});
		},

		async deleteUser({ commit }, userId) {
			commit('loading', true);

			await axios.delete('/users/delete/' + userId);

			commit('loading', false);
		},

		async getUserCourses({ dispatch, commit }, userId = '') {
			commit('loading', true);

			const coursesUrl = userId === '' ? '/courses' : '/users/courses/' + userId;

			await axios.get(coursesUrl).then((response) => {
				commit('setCourses', response.data);
			});

			commit('loading', false);
		},

		async getUserTasks({ dispatch, commit }, userId = '') {
			commit('loading', true);

			const tasksUrl = userId === '' ? '/tasks' : '/user/' + userId + '/tasks';

			await axios.get(tasksUrl).then((response) => {
				// paginate tasks or not
				commit('setTasks', response.data);
			});

			commit('loading', false);
		},

		async getCategoryTasks({ commit }, courseId) {
			commit('loading', true);

			await axios.get('/tasks/show/category/' + courseId).then((response) => {
				commit('setTasks', response.data);
			});

			commit('loading', false);
		},

		async getUserMarks({ dispatch, commit }, { userId = '', page = 1 }) {
			commit('loading', true);

			const marksUrl = userId === '' ? '/marks?page=' + page : '/user/' + userId + '/marks';

			await axios.get(marksUrl).then((response) => {
				// paginate marks or not
				commit('setMarks', response.data);
			});

			commit('loading', false);
		},

		async updateTeacherInformations({ commit }, { userId, teacher }) {
			commit('loading', true);

			await axios
				.post('/users/update-teacher/' + userId, teacher)
				.then(() => {
					commit('clearValidationErrors');
					commit('setTeacher', teacher);
				})
				.catch((error) => {
					commit('setValidationErrors', error.response.data.errors);
					throw error;
				});

			commit('loading', false);
		}
	},

	mutations: {
		loading(state, newLoadingStatus) {
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
		}
	},

	getters: {
		getUserRole(state) {
			return state.user.account_role && state.user.account_role.name;
		},

		isUserAdmin(state) {
			return state.user.account_role && state.user.account_role.name === 'admin';
		},

		isUserStudent(state) {
			return state.user.account_role && state.user.account_role.name === 'student';
		},

		isUserTeacher(state) {
			return state.user.account_role && state.user.account_role.name === 'teacher';
		},

		getFormattedDate: (state) => (date) => {
			return date !== null ? dayjs(date).format('L LT') : '';
		},

		getFormattedMarkDate: (state) => (date) => {
			return date !== null ? dayjs(date).format('L') : '';
		},

		getActiveCourses(state) {
			return (
				state.courses &&
				state.courses.filter(
					(course) =>
						dayjs() >= dayjs(course.available_from) &&
						(dayjs() <= dayjs(course.available_to) || course.available_to === null)
				)
			);
		},

		getEndedCourses(state) {
			return (
				state.courses &&
				state.courses.filter(
					(course) => dayjs() > dayjs(course.available_to) && course.available_to !== null
				)
			);
		},

		getUpcomingCourses(state) {
			return (
				state.courses &&
				state.courses.filter((course) => dayjs() < dayjs(course.available_from))
			);
		},

		getActiveTasks(state) {
			return (
				state.tasks &&
				state.tasks.filter(
					(task) =>
						dayjs() >= dayjs(task.available_from) &&
						(dayjs() <= dayjs(task.available_to) || task.available_to === null)
				)
			);
		},

		getExpiredTasks(state) {
			return (
				state.tasks &&
				state.tasks.filter(
					(task) => dayjs() > dayjs(task.available_to) && task.available_to !== null
				)
			);
		},

		getMarkedTasks(state) {
			return state.tasks && state.tasks.filter((task) => task.user_marks.length > 0);
		},

		getUpcomingTasks(state) {
			return (
				state.tasks && state.tasks.filter((task) => dayjs() < dayjs(task.available_from))
			);
		},

		getTeachersInCourse: (state) => (course) => {
			return course.users && course.users.filter((user) => user.account_role === 'teacher');
		}
	}
};

export default user;
