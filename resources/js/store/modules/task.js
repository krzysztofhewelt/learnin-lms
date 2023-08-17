import dayjs from 'dayjs';
import axios from 'axios';

const task = {
	namespaced: true,

	state: {
		loading: false,
		validationErrors: [],
		task: {},
		taskRefFiles: [],
		studentFiles: [],
		userMark: {},
		allStudentFilesInTask: []
	},

	actions: {
		async showTask({ commit }, taskId) {
			commit('setLoading', true);

			await axios
				.get('/tasks/show/' + taskId)
				.then((response) => {
					commit('setTask', {
						id: response.data.task.id,
						name: response.data.task.name,
						description: response.data.task.description,
						available_from: response.data.task.available_from,
						available_to: response.data.task.available_to,
						max_points: response.data.task.max_points,
						course: response.data.task.course,
						category: response.data.task.category
					});
					commit('setTaskRefFiles', response.data.task.task_files);
					commit('setStudentFiles', response.data.studentFiles);
					commit('setUserMark', response.data.userMark);
				})
				.finally(() => {
					commit('setLoading', false);
				});
		},

		async createOrEditTask({ commit }, task) {
			commit('setLoading', true);
			commit('clearValidationErrors');

			task.course_ID = task.course?.id;
			task.course_category = task.category?.id;

			return await axios({
				method: !task.id ? 'post' : 'patch',
				url: !task.id ? '/tasks/create' : '/tasks/edit/' + task.id,
				data: task
			})
				.then((response) => {
					commit('setTask', task);
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

		// delete task
		async deleteTask({ commit }, taskId) {
			commit('setLoading', true);

			await axios
				.delete('/tasks/delete/' + taskId)
				.then(() => {
					commit('resetTask');
				})
				.finally(() => {
					commit('setLoading', false);
				});
		},

		async deleteStudentFile({ commit, state }, fileId) {
			commit('setLoading', true);

			await axios
				.delete('/delete-resource/' + fileId + '/student_upload')
				.then(() => {
					commit(
						'setStudentFiles',
						state.studentFiles.filter((file) => file.id !== fileId)
					);
				})
				.finally(() => {
					commit('setLoading', false);
				});
		},

		async deleteTaskRefFile({ commit, state }, fileId) {
			commit('setLoading', true);

			await axios
				.delete('/delete-resource/' + fileId + '/task_ref')
				.then(() => {
					commit(
						'setTaskRefFiles',
						state.taskRefFiles.filter((file) => file.id !== fileId)
					);
				})
				.finally(() => {
					commit('setLoading', false);
				});
		},

		async getStudentFilesInTask({ commit }, taskId) {
			commit('setLoading', true);

			await axios
				.get('/tasks/students_uploads/' + taskId)
				.then((response) => {
					commit('setTask', response.data.task);
					commit('setAllStudentFilesInTask', response.data.student_files);
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

		setTask(state, task) {
			state.task = task;
		},

		setTaskRefFiles(state, refFiles) {
			state.taskRefFiles = refFiles;
		},

		setStudentFiles(state, studentFiles) {
			state.studentFiles = studentFiles;
		},

		setAllStudentFilesInTask(state, allStudentFiles) {
			state.allStudentFilesInTask = allStudentFiles;
		},

		setUserMark(state, userMark) {
			state.userMark = userMark;
		},

		resetTask(state) {
			state.task = {};
			state.taskRefFiles = [];
			state.studentFiles = [];
			state.userMark = {};
			state.validationErrors = [];
		},

		setValidationErrors(state, errors) {
			state.validationErrors = errors;
		},

		clearValidationErrors(state) {
			state.validationErrors = [];
		}
	},

	getters: {
		checkStudentCanUploadFiles(state) {
			return (
				dayjs() >= dayjs(state.task.available_from) &&
				(!state.task.available_to || dayjs() <= dayjs(state.task.available_to))
			);
		},

		getDescriptionLength(state) {
			return state.task.description?.length;
		},

		getRefFilesCount(state) {
			return state.taskRefFiles?.length;
		},

		getStudentFilesCount(state) {
			return state.studentFiles?.length;
		},

		isEnded(state) {
			return state.task.available_to && dayjs() > dayjs(state.task.available_to);
		}
	}
};

export default task;
