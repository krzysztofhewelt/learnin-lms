<template>
	<div>
		<router-link
			:to="{ name: 'TasksView', params: { id: $route.params.id } }"
			class="mb-3 block font-bold text-blue-500 no-underline hover:text-blue-700"
		>
			&lt; &lt; {{ $t('mark.back_to_task') }}
		</router-link>

		<h1 class="font-bold">{{ $t('mark.issue_grades_title') }}</h1>
		<div class="font-bold">
			{{ $t('task.task_name') }}: {{ task.name }}<br />
			<template v-if="task.available_to">
				{{ $t('task.available_to_label') }}: {{ task.available_to }}<br />
			</template>
			<template v-else>
				{{ $t('task.no_available_to') }}
			</template>
			{{ $t('course.course') }}: {{ task.course && task.course.name }}<br />
			<u>{{ $t('task.max_points_to_earn') }}: {{ task.max_points }}</u>
		</div>
		<router-link
			class="normal_btn mt-2"
			:to="{
				name: 'TaskStudentUploads',
				params: { id: $route.params.id }
			}"
		>
			{{ $t('mark.check_student_uploads') }}
		</router-link>
	</div>

	<div class="py-3 text-xl font-bold text-red-600" v-show="validationErrors.length > 0">
		{{ validationErrors }}
	</div>

	<form class="mt-6" @submit.prevent="handleSubmit">
		<BaseTable :title="$t('course.students_assigned')" :loading="loading" :columns="columns">
			<tr v-if="marksForTask.length === 0" class="text-center text-lg font-bold">
				<BaseRow :colspan="columns.length">{{ $t('course.no_students_assigned') }}</BaseRow>
			</tr>
			<tr
				v-for="studentMark in marksForTask"
				class="flex-no wrap mb-5 flex flex-col rounded-lg lg:table-row lg:rounded-none"
			>
				<BaseRow>
					<div class="mr-3 inline-block w-2/6 break-all bg-gray-200 px-6 py-3 lg:hidden">
						{{ columns.find((name) => name.name === 'surname').label }}
					</div>

					{{ studentMark.surname }}
				</BaseRow>
				<BaseRow>
					<div class="mr-3 inline-block w-2/6 break-all bg-gray-200 px-6 py-3 lg:hidden">
						{{ columns.find((name) => name.name === 'name').label }}
					</div>

					{{ studentMark.name }}
				</BaseRow>
				<BaseRow>
					<div class="mr-3 inline-block w-2/6 break-all bg-gray-200 px-6 py-3 lg:hidden">
						{{ columns.find((name) => name.name === 'identificationNumber').label }}
					</div>

					{{ studentMark.identification_number }}
				</BaseRow>
				<BaseRow>
					<div class="mr-3 inline-block w-2/6 break-all bg-gray-200 px-6 py-3 lg:hidden">
						{{ columns.find((name) => name.name === 'points').label }}
					</div>

					<BaseInput
						type="text"
						class="inline-block w-3/5"
						v-model="studentMark.points"
					/>
				</BaseRow>
				<BaseRow>
					<div class="mr-3 inline-block w-2/6 break-all bg-gray-200 px-6 py-3 lg:hidden">
						{{ columns.find((name) => name.name === 'mark').label }}
					</div>

					<BaseInput type="text" class="inline-block w-3/5" v-model="studentMark.mark" />
				</BaseRow>
				<BaseRow>
					<div class="mr-3 inline-block w-2/6 break-all bg-gray-200 px-6 py-3 lg:hidden">
						{{ columns.find((name) => name.name === 'comments').label }}
					</div>

					<div class="m-3 lg:m-0">
						<BaseTextarea v-model="studentMark.description"></BaseTextarea>
					</div>
				</BaseRow>
			</tr>
		</BaseTable>
		<div class="mt-2 text-right" v-if="marksForTask.length > 0">
			<ButtonSubmit :loading="loading" @click="handleSubmit" />
		</div>
	</form>
</template>

<script>
import BaseTable from '@/components/Table/BaseTable.vue';
import { mapActions, mapState } from 'vuex';
import BaseRow from '@/components/Table/BaseRow.vue';
import BaseInput from '@/components/BaseInput.vue';
import BaseTextarea from '@/components/BaseTextarea.vue';
import ButtonSubmit from '@/components/ButtonSubmit.vue';
import LoadingScreen from '@/components/LoadingScreen.vue';
import { useToast } from 'vue-toastification';

export default {
	name: 'TaskMarks',
	components: {
		LoadingScreen,
		ButtonSubmit,
		BaseTextarea,
		BaseInput,
		BaseRow,
		BaseTable
	},

	data() {
		return {
			columns: [
				{
					name: 'surname',
					label: this.$t('user.surname')
				},
				{
					name: 'name',
					label: this.$t('user.name')
				},
				{
					name: 'identificationNumber',
					label: this.$t('user.identification_number')
				},
				{
					name: 'points',
					label: this.$t('mark.points')
				},
				{
					name: 'mark',
					label: this.$t('mark.mark')
				},
				{
					name: 'comments',
					label: this.$t('mark.comment')
				}
			]
		};
	},

	computed: {
		...mapState('mark', ['loading', 'task', 'marksForTask', 'validationErrors'])
	},

	methods: {
		...mapActions('mark', ['getStudentsMarksForTask', 'editStudentsMarksForTask']),

		handleSubmit() {
			const toast = useToast();

			this.editStudentsMarksForTask({
				taskId: this.$route.params.id,
				studentsMarks: this.marksForTask
			})
				.then(() => {
					toast.success(this.$t('general.saved_successfully'));
				})
				.catch(() => {
					toast.error(this.$t('general.validation_errors'));
				});
		}
	},

	created() {
		this.getStudentsMarksForTask(this.$route.params.id);
	}
};
</script>
