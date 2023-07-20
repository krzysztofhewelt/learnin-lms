<template>
	<div class="relative flex h-fit w-full flex-col break-words rounded-lg bg-white shadow-xl">
		<LoadingScreen v-if="loading" />
		<div class="p-12">
			<router-link
				:to="{ name: 'TasksView', params: { id: $route.params.id } }"
				class="mb-3 block font-bold text-blue-500 no-underline hover:text-blue-700"
			>
				&lt; &lt; {{ $t('mark.back_to_task') }}
			</router-link>

			<h1 class="block text-xl font-bold">
				{{ $t('task.student_upload_title') }}
			</h1>
			<p class="mb-4">
				<b>{{ $t('task.task_name') }}:</b> {{ task.name }}<br />
				<b>{{ $t('task.available_from') }}:</b>
				{{ getFormattedDate(task.available_from) }}<br />
				<b>{{ $t('task.available_to') }}:</b>
				{{ getFormattedDate(task.available_to) || $t('general.not_available') }}<br />
			</p>

			<button class="normal_btn mb-3 mr-3 w-fit" @click="downloadAll(task.id)">
				{{ $t('task.download_zip') }}
			</button>

			<router-link
				:to="{ name: 'MarksTask', params: { id: $route.params.id } }"
				class="normal_btn"
			>
				{{ $t('task.issue_grades') }}
			</router-link>

			<div class="mt-10 divide-y divide-gray-200">
				<div v-for="student in allStudentFilesInTask" :key="student.id">
					<h1 class="mt-4 text-xl font-bold">{{ student.surname }} {{ student.name }}</h1>
					<div class="mb-4 grid grid-cols-1 gap-3 md:grid-cols-6">
						<div
							v-if="student.files.length === 0"
							class="text-lg font-bold text-red-500 underline"
						>
							{{ $t('general.no_files') }}
						</div>
						<div v-for="file in student.files" :key="file.file_ID">
							<button
								class="block break-all text-left font-bold text-purple-800 no-underline hover:text-purple-500"
								@click="download(file.file_ID)"
							>
								{{ file.filename_original }} ({{ file.file_size }}
								{{ file.file_size_unit }})
							</button>
							<div class="text-gray-400">
								<Popper :content="getFormattedDate(file.updated_at)" hover>
									<span class="underline decoration-dotted">{{
										getRelativeTime(file.updated_at)
									}}</span>
								</Popper>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import { mapActions, mapGetters, mapState } from 'vuex';
import LoadingScreen from '@/components/LoadingScreen.vue';
import Popper from 'vue3-popper';

export default {
	name: 'TaskStudentUploads',
	components: { LoadingScreen, Popper },

	computed: {
		...mapState('task', ['loading', 'task', 'allStudentFilesInTask']),
		...mapGetters('task', ['getFormattedDate', 'getRelativeTime'])
	},

	methods: {
		...mapActions('task', ['getStudentFilesInTask']),

		download(fileId) {
			axios
				.post(
					'/download/' + fileId,
					{
						file_type: 'student_upload'
					},
					{
						responseType: 'blob'
					}
				)
				.then((res) => {
					let filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
					let matches = filenameRegex.exec(res.headers['content-disposition']);
					let filename;

					if (matches != null && matches[1]) filename = matches[1].replace(/['"]/g, '');

					let fileURL = window.URL.createObjectURL(new Blob([res.data]));
					let fileLink = document.createElement('a');

					fileLink.href = fileURL;
					fileLink.setAttribute('download', filename);
					document.body.appendChild(fileLink);

					fileLink.click();
				});
		},

		async downloadAll(taskId) {
			this.$store.commit('task/loading', true);

			await axios
				.get('/tasks/students_uploads/' + taskId + '/zip', {
					responseType: 'blob'
				})
				.then((res) => {
					let filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
					let matches = filenameRegex.exec(res.headers['content-disposition']);
					let filename;

					if (matches != null && matches[1]) filename = matches[1].replace(/['"]/g, '');

					let fileURL = window.URL.createObjectURL(new Blob([res.data]));
					let fileLink = document.createElement('a');

					fileLink.href = fileURL;
					fileLink.setAttribute('download', filename);
					document.body.appendChild(fileLink);

					fileLink.click();
				});

			this.$store.commit('task/loading', false);
		}
	},

	created() {
		this.getStudentFilesInTask(this.$route.params.id);
	}
};
</script>
