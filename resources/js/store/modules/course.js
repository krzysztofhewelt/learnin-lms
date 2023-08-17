import axios from 'axios';
import dayjs from 'dayjs';

const course = {
	namespaced: true,

	state: {
		loading: false,
		validationErrors: [],
		course: {},
		courseCategories: [],
		courseUsers: [],
		courseFiles: [],
		courseCategoryTasks: []
	},

	actions: {
		async getCourseDetails({ commit }, courseId) {
			commit('setLoading', true);
			commit('resetCourse');

			await axios
				.get('/courses/details/' + courseId)
				.then((response) => {
					commit('setCourse', {
						id: response.data.id,
						name: response.data.name,
						description: response.data.description,
						available_from: response.data.available_from,
						available_to: response.data.available_to
					});
					commit('setCourseUsers', response.data.users);
					commit('setCourseCategories', response.data.categories);
					commit('setCourseFiles', response.data.course_files);
				})
				.finally(() => {
					commit('setLoading', false);
				});
		},

		async getCourseCategories({ commit }, courseId) {
			commit('setLoading', true);

			await axios
				.get('/categories/course/' + courseId)
				.then((response) => {
					commit('setCourseCategories', response.data.categories);
				})
				.finally(() => {
					commit('setLoading', false);
				});
		},

		async createOrEditCourse({ commit }, course) {
			commit('setLoading', true);
			commit('clearValidationErrors');

			return await axios({
				method: !course.id ? 'post' : 'patch',
				url: !course.id ? '/courses/create' : '/courses/edit/' + course.id,
				data: course
			})
				.then((response) => {
					commit('clearValidationErrors');
					return response;
				})
				.catch((error) => {
					if (error.response.status === 422)
						commit('setValidationErrors', error.response.data.errors);

					throw error;
				})
				.finally(() => {
					commit('setLoading', false);
				});
		},

		async deleteCourse({ commit }, courseId) {
			commit('setLoading', true);

			await axios
				.delete('/courses/delete/' + courseId)
				.then(() => {
					commit('resetCourse');
				})
				.finally(() => {
					commit('setLoading', false);
				});
		},

		async deleteCourseFile({ commit, state }, courseFileId) {
			commit('setLoading', true);

			await axios
				.delete('/delete-resource/' + courseFileId + '/course_file')
				.then(() => {
					commit(
						'setCourseFiles',
						state.courseFiles.filter((file) => file.id !== courseFileId)
					);
				})
				.finally(() => {
					commit('setLoading', false);
				});
		},

		async deleteCourseCategory({ commit, state }, categoryId) {
			commit('setLoading', true);

			await axios
				.delete('/categories/delete/' + categoryId)
				.then(() => {
					commit(
						'setCourseCategories',
						state.courseCategories.filter((category) => category.id !== categoryId)
					);
				})
				.finally(() => {
					commit('setLoading', false);
				});
		},

		async createOrEditCategory({ commit }, { courseId, category }) {
			commit('setLoading', true);

			await axios({
				method: category.id === -1 ? 'post' : 'patch',
				url:
					category.id === -1
						? '/categories/create/course/' + courseId
						: '/categories/edit/' + category.id,
				data: {
					name: category.name
				}
			})
				.then((response) => {
					if (response.config.method === 'post') commit('addCourseCategory', category);
					else commit('updateCourseCategory', category);

					category.id = response.data.id;
					commit('clearValidationErrors');
				})
				.catch((error) => {
					if (error.response.status === 422)
						commit('setValidationErrors', error.response.data.errors);

					throw error;
				})
				.finally(() => {
					commit('setLoading', false);
				});
		},

		async assignToCourse({ dispatch, commit }, { courseId, participants }) {
			commit('setLoading', true);

			await axios
				.post('/courses/assign/' + courseId, {
					assignedUsers: participants.map((user) => user.id)
				})
				.then(() => {
					commit('setCourseUsers', participants);
					commit('clearValidationErrors');
				})
				.catch((error) => {
					if (error.response.status === 422)
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

		clearValidationErrors(state) {
			state.validationErrors = [];
		},

		setValidationErrors(state, validationErrors) {
			state.validationErrors = validationErrors;
		},

		setCourse(state, course) {
			state.course = course;
		},

		setCourseUsers(state, users) {
			state.courseUsers = users;
		},

		setCourseCategories(state, courseCategories) {
			state.courseCategories = courseCategories;
		},

		setCourseFiles(state, courseFiles) {
			state.courseFiles = courseFiles;
		},

		resetCourse(state) {
			state.course = {};
			state.courseCategories = [];
			state.courseUsers = [];
			state.courseFiles = [];
			state.validationErrors = [];
		},

		addCourseCategory(state, category) {
			state.courseCategories.push(category);
		},

		updateCourseCategory(state, category) {
			state.courseCategories.find((key) => key['id'] === category.id).name = category.name;
		}
	},

	getters: {
		getCategoriesCount(state) {
			return state.courseCategories?.length;
		},

		getCourseUsersCount(state) {
			return state.courseUsers?.length;
		},

		getDescriptionLength(state) {
			return state.course.description?.length;
		},

		getCourseFilesCount(state) {
			return state.courseFiles?.length;
		},

		isEnded(state) {
			return state.course.available_to && dayjs() > dayjs(state.course.available_to);
		}
	}
};

export default course;
