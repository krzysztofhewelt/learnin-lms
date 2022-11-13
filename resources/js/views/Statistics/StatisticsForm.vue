<template>
	<div
		class="relative mb-6 mt-6 flex w-full min-w-0 flex-col break-words rounded-lg bg-white px-6 py-6 shadow-xl"
	>
		<div class="grid grid-cols-1 lg:grid-cols-3">
			<div class="mb-3 lg:col-start-1">
				<span class="text-2xl font-bold">{{ $t('statistics.select_type') }}:</span>
				<Multiselect
					v-model="statType"
					:options="availableStatTypes"
					:placeholder="$t('general.select_one')"
					:searchable="false"
					select-label=""
					:selected-label="$t('select.selected')"
					deselect-label=""
					label="name"
					track-by="id"
					:allow-empty="false"
					@select="resetChoices"
				/>
			</div>

			<div class="mb-3 lg:col-start-1">
				<span class="text-2xl font-bold">{{ $t('statistics.select_course') }}:</span>
				<Multiselect
					v-model="course"
					:options="courses || []"
					:loading="loading"
					:placeholder="$t('general.select_one')"
					select-label=""
					:selected-label="$t('select.selected')"
					deselect-label=""
					label="name"
					track-by="id"
					@select="setCourseCategories"
				>
					<template #noResult>
						{{ $t('select.no_results') }}
					</template>

					<template #noOptions>
						{{ $t('select.no_options') }}
					</template>
				</Multiselect>
			</div>

			<div class="mb-3 lg:col-start-1">
				<span class="text-2xl font-bold">{{ $t('statistics.select_category') }}:</span>
				<Multiselect
					v-model="category"
					:options="courseCategories"
					:loading="loading"
					:placeholder="$t('general.select_one')"
					select-label=""
					:selected-label="$t('select.selected')"
					deselect-label=""
					label="name"
					track-by="id"
					@select="getCourseCategoryTasks"
				>
					<template #noResult>
						{{ $t('select.no_results') }}
					</template>

					<template #noOptions>
						{{ $t('select.no_options') }}
					</template>
				</Multiselect>
			</div>

			<div class="mb-3 lg:col-start-1" v-if="statType.id === 'fromTask'">
				<span class="text-2xl font-bold">{{ $t('statistics.select_task') }}:</span>
				<Multiselect
					v-model="task"
					:options="tasks"
					:loading="loading"
					:placeholder="$t('general.select_one')"
					select-label=""
					:selected-label="$t('select.selected')"
					deselect-label=""
					label="name"
					track-by="id"
					@select="selectTask"
				>
					<template #noResult>
						{{ $t('select.no_results') }}
					</template>

					<template #noOptions>
						{{ $t('select.no_options') }}
					</template>
				</Multiselect>
			</div>

			<button-submit
				v-if="statTypeSelectedCorrectly"
				:value="$t('general.generate')"
				@click="generateReport"
				class="mb-4 w-fit lg:col-start-1"
			/>
		</div>

		<div v-if="reportGenerated" class="border-t-[1px]">
			<div class="py-4">
				<div>
					<b>{{ $t('statistics.generated_date') }}: </b>
					{{ generatedDate }}
				</div>
				<div>
					<b>{{ $t('course.course') }}: </b>
					{{ course.name }}
				</div>
				<div>
					<b>{{ $t('course.course_category') }}: </b>
					{{ category.name }}
				</div>
				<div v-if="task">
					<b>{{ $t('task.task') }}: </b>
					{{ task.name }}
					<br />
					<b>{{ $t('task.max_points_to_earn') }}: </b>
					{{ task.max_points }}
				</div>
			</div>

			<BaseTable :title="statType.name" :columns="resultColumns" :loading="loading">
				<tr v-if="resultData.length === 0">
					<BaseRow :colspan="resultColumns.length" class="text-center text-xl font-bold">
						{{ $t('task.no_tasks') }}
					</BaseRow>
				</tr>
				<tr
					v-for="student in resultData"
					class="flex-no wrap mb-5 flex flex-col rounded-lg lg:table-row lg:rounded-none"
				>
					<BaseRow>
						<div
							class="mr-3 inline-block w-2/6 break-all bg-gray-200 px-6 py-3 lg:hidden"
						>
							{{ resultColumns.find((name) => name.name === 'surname').label }}
						</div>

						{{ student.surname }}
					</BaseRow>
					<BaseRow>
						<div
							class="mr-3 inline-block w-2/6 break-all bg-gray-200 px-6 py-3 lg:hidden"
						>
							{{ resultColumns.find((name) => name.name === 'name').label }}
						</div>

						{{ student.name }}
					</BaseRow>
					<BaseRow>
						<div
							class="mr-3 inline-block w-2/6 break-all bg-gray-200 px-6 py-3 lg:hidden"
						>
							{{
								resultColumns.find((name) => name.name === 'identificationNumber')
									.label
							}}
						</div>

						{{ student.identification_number }}
					</BaseRow>
					<BaseRow>
						<div
							class="mr-3 inline-block w-2/6 break-all bg-gray-200 px-6 py-3 lg:hidden"
						>
							{{ resultColumns.find((name) => name.name === 'points').label }}
						</div>

						{{ student.points || $t('general.not_available') }}
					</BaseRow>
					<BaseRow>
						<div
							class="mr-3 inline-block w-2/6 break-all bg-gray-200 px-6 py-3 lg:hidden"
						>
							{{ resultColumns.find((name) => name.name === 'mark').label }}
						</div>

						<template v-if="student.mark && student.mark < 2"> 2 </template>
						<template v-else>
							{{ student.mark || $t('general.not_available') }}
						</template>
					</BaseRow>
				</tr>
			</BaseTable>
		</div>
	</div>
</template>

<script>
import Loading from '@/components/LoadingScreen.vue';
import Multiselect from 'vue-multiselect';
import { mapActions, mapState } from 'vuex';
import BaseTable from '@/components/Table/BaseTable.vue';
import ButtonSubmit from '@/components/ButtonSubmit.vue';
import BaseRow from '@/components/Table/BaseRow.vue';
import dayjs from 'dayjs';

export default {
	name: 'StatisticsForm',
	components: { BaseRow, ButtonSubmit, BaseTable, Loading, Multiselect },

	data() {
		return {
			loading: true,
			statType: '',
			availableStatTypes: [
				{
					id: 'fromTask',
					name: this.$t('statistics.student_marks')
				},
				{
					id: 'fromCourseCategory',
					name: this.$t('statistics.average_marks_from_category')
				}
			],
			course: '',
			category: '',
			task: '',
			reportGenerated: false,
			generatedDate: '',

			resultColumns: [
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
				}
			],
			resultData: []
		};
	},

	computed: {
		...mapState('user', ['courses', 'tasks']),
		...mapState('course', ['courseCategories', 'courseCategoryTasks']),

		statTypeSelectedCorrectly() {
			return (
				(this.statType &&
					this.statType.id === 'fromCourseCategory' &&
					this.course &&
					this.category) ||
				(this.statType &&
					this.statType.id === 'fromTask' &&
					this.course &&
					this.category &&
					this.task)
			);
		}
	},

	methods: {
		...mapActions('user', ['getUserCourses', 'getCategoryTasks']),
		...mapActions('course', ['getCourseCategories']),

		setCourseCategories(option) {
			this.loading = true;
			this.task = '';
			this.category = '';
			this.reportGenerated = false;
			this.getCourseCategories(option.id);
			this.loading = false;
		},

		resetChoices() {
			this.reportGenerated = false;
			this.course = '';
			this.category = '';
			this.resultData = [];
		},

		getCourseCategoryTasks(option) {
			this.loading = true;
			this.task = '';
			this.reportGenerated = false;
			this.getCategoryTasks(option.id);
			this.loading = false;
		},

		selectTask() {
			this.reportGenerated = false;
		},

		async generateReport() {
			this.loading = true;

			if (this.statType.id === 'fromTask') {
				await axios.get('/statistics/task/' + this.task.id).then((response) => {
					this.reportGenerated = true;
					this.resultData = response.data[0].studentMarks;
					this.generatedDate = dayjs().format('L LT');
				});
			} else if (this.statType.id === 'fromCourseCategory') {
				this.task = '';

				await axios
					.get('/statistics/course/' + this.course.id + '/' + this.category.id)
					.then((response) => {
						this.resultColumns[3]['label'] = this.$t('mark.avg_points');
						this.resultColumns[4]['label'] = this.$t('mark.avg_mark');

						this.reportGenerated = true;
						this.resultData = response.data;
						this.generatedDate = dayjs().format('L LT');
					});
			}

			this.loading = false;
		}
	},

	created() {
		this.loading = true;
		this.getUserCourses().then(() => (this.loading = false));
	}
};
</script>
