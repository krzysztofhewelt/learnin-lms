import { useToast } from 'vue-toastification';
import router from '@/router';
import dayjs from 'dayjs';
import axios from 'axios';

const toast = useToast();

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
		// show task
		async showTask({ commit }, taskId) {
			commit('loading', true);

			await axios.get('/tasks/show/' + taskId).then((response) => {
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
			});

			commit('loading', false);
		},

		async createOrEditTask({ commit }, task) {
			commit('loading', true);
			commit('clearValidationErrors');

			task.course_ID = task.course && task.course.id;
			task.course_category = task.category && task.category.id;

			if (task.available_to === '') delete task.available_to;

			return await axios({
				method: !task.id ? 'post' : 'patch',
				url: !task.id ? '/tasks/create' : '/tasks/edit/' + task.id,
				data: task
			})
				.then((response) => {
					commit('setTask', task);
					commit('clearValidationErrors');
					commit('loading', false);

					return response;
				})
				.catch((error) => {
					if (error.response.status === 422)
						commit('setValidationErrors', error.response.data.errors);

					commit('loading', false);
					throw error;
				});
		},

		// delete task
		async deleteTask({ dispatch, commit }, taskId) {
			commit('loading', true);

			await axios.delete('/tasks/delete/' + taskId).then(() => {
				commit('loading', false);
				toast.success('Task deleted successfully');
				router.push({ name: 'TasksUser' });
			});

			commit('loading', false);
		},

		async deleteStudentFile({ commit }, fileId) {
			commit('loading', true);

			await axios
				.post('/delete-resource/' + fileId, {
					file_type: 'student_upload'
				})
				.then(() => {
					commit(
						'setStudentFiles',
						state.studentFiles.filter((file) => file.id !== fileId)
					);
				});

			commit('loading', false);
		},

		async deleteTaskRefFile({ commit }, fileId) {
			commit('loading', true);

			await axios
				.post('/delete-resource/' + fileId, {
					file_type: 'task_ref'
				})
				.then(() => {
					commit(
						'setTaskRefFiles',
						state.taskRefFiles.filter((file) => file.id !== fileId)
					);
				});

			commit('loading', false);
		},

		async getStudentFilesInTask({ dispatch, commit }, taskId) {
			commit('loading', true);

			await axios.get('/tasks/students_uploads/' + taskId).then((response) => {
				commit('setTask', response.data.task);
				commit('setAllStudentFilesInTask', response.data.student_files);
			});

			commit('loading', false);
		}
	},

	mutations: {
		loading(state, newLoadingStatus) {
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
		},

		setUserTasks(state, tasks) {
			state.userTasks = tasks;
		},

		setValidationErrors(state, errors) {
			state.validationErrors = errors;
		},

		clearValidationErrors(state) {
			state.validationErrors = [];
		}
	},

	getters: {
		getFormattedDate: (state) => (date) => {
			return date !== null ? dayjs(date).format('L LT') : '';
		},

		getFormattedMarkDate: (state) => (date) => {
			return date !== null ? dayjs(date).format('L') : '';
		},

		getRelativeTime: (state) => (date) => {
			return date !== null ? dayjs().to(dayjs(date)) : '';
		},

		studentCanUploadFiles(state) {
			return (
				dayjs() >= dayjs(state.task.available_from) &&
				(state.task.available_to === null || dayjs() <= dayjs(state.task.available_to))
			);
		},

		getISODate: (state) => (date) => {
			return date !== null ? dayjs(date).format('YYYY-MM-DDTHH:mm') : '';
		},

		getDescriptionLength(state) {
			return state.task.description && state.task.description.length;
		},

		getRefFilesCount(state) {
			return state.taskRefFiles && state.taskRefFiles.length;
		},

		getStudentFilesCount(state) {
			return state.studentFiles && state.studentFiles.length;
		},

		isEnded(state) {
			return state.task.available_to !== null && dayjs() > dayjs(state.task.available_to);
		}
	}
};

export default task;
