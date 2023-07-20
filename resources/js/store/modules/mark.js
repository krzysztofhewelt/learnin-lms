const mark = {
	namespaced: true,

	state: {
		loading: false,
		validationErrors: [],
		marksForTask: [],
		task: {}
	},

	actions: {
		async getStudentsMarksForTask({ commit }, taskId) {
			commit('loading', true);
			commit('clearValidationErrors');

			await axios
				.get('/marks/task/' + taskId)
				.then((response) => {
					commit('setTask', response.data.task);
					commit('setMarksForTask', response.data.student_marks);
				})
				.catch((error) => {
					if (error.response.status === 422)
						commit('setValidationErrors', error.response.data.errors);

					throw error;
				})
				.finally(() => {
					commit('loading', false);
				});
		},

		// edit user marks
		async editStudentsMarksForTask({ commit }, { taskId, studentsMarks }) {
			commit('loading', true);

			await axios
				.post('/marks/task/' + taskId, { marks: studentsMarks })
				.then(() => {
					commit('clearValidationErrors');
				})
				.catch((error) => {
					if (error.response.status === 422)
						commit('setValidationErrors', error.response.data.error);

					throw error;
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

		setValidationErrors(state, validationErrors) {
			state.validationErrors = validationErrors;
		},

		clearValidationErrors(state) {
			state.validationErrors = [];
		},

		setTask(state, task) {
			state.task = task;
		},

		setMarksForTask(state, marks) {
			state.marksForTask = marks;
		}
	}
};

export default mark;
