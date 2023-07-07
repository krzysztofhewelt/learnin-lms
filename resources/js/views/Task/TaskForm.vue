<template>
	<div class="relative flex h-fit w-full flex-col break-words rounded-lg bg-white shadow-xl">
		<LoadingScreen v-if="loading" />
		<form @submit.prevent="handleSubmit">
			<div class="p-6">
				<h1 class="my-auto mb-4 block text-xl font-bold">
					{{
						this.$route.name === 'TasksEdit'
							? $t('task.editing_task')
							: $t('task.creating_task')
					}}
				</h1>

				<div class="grid grid-cols-4 gap-4">
					<div class="col-span-4">
						<BaseInputGroup
							id="name"
							:label="$t('task.task_name')"
							type="text"
							:required="true"
							v-model="task.name"
							:validation-errors="validationErrors.name"
						/>
					</div>
					<div class="col-span-4">
						<BaseTextarea
							id="description"
							:label="$t('task.task_description')"
							v-model="task.description"
							:required="true"
							:validation-errors="validationErrors.description"
						/>
					</div>
					<div class="col-span-4 lg:col-span-2">
						<BaseInputGroup
							id="available_from"
							type="datetime-local"
							:label="$t('task.available_from_label')"
							:required="true"
							v-model="availableFrom"
							:validation-errors="validationErrors.available_from"
						/>
					</div>
					<div class="col-span-4 lg:col-span-2">
						<BaseInputGroup
							id="available_to"
							type="datetime-local"
							:label="$t('task.available_from_label')"
							v-model="availableTo"
							:validation-errors="validationErrors.available_to"
						/>
					</div>
					<div class="col-span-4 lg:col-span-1">
						<BaseInputGroup
							id="max_points"
							type="number"
							:label="$t('task.max_points_to_earn')"
							v-model="task.max_points"
							:required="true"
							:validation-errors="validationErrors.max_points"
						/>
					</div>

					<div class="col-span-4 lg:col-span-1 lg:col-start-1">
						<label for="course" class="text-sm font-medium text-gray-700"
							>{{ $t('course.course') }}:</label
						>
						<span class="ml-px font-bold text-red-600">*</span>
						<div class="shadow-sm">
							<Multiselect
								v-model="task.course"
								:options="courses"
								id="course"
								:loading="loading"
								:placeholder="$t('general.select_one')"
								select-label=""
								:selected-label="$t('select.selected')"
								deselect-label=""
								label="name"
								track-by="id"
								@select="getCategories"
								@remove="resetCategory"
							>
								<template #noResult>
									{{ $t('select.no_results') }}
								</template>

								<template #noOptions>
									{{ $t('select.no_options') }}
								</template>
							</Multiselect>
						</div>
						<span
							class="mt-1 block font-bold text-red-400"
							v-for="error in validationErrors.course_ID"
						>
							{{ error }}
						</span>
					</div>
					<div class="col-span-4 lg:col-span-1">
						<label for="category" class="text-sm font-medium text-gray-700"
							>{{ $t('course.course_category') }}:</label
						>
						<span class="ml-px font-bold text-red-600">*</span>
						<div class="shadow-sm">
							<Multiselect
								v-model="task.category"
								:options="courseCategories"
								id="category"
								:loading="loading"
								:placeholder="$t('general.select_one')"
								select-label=""
								:selected-label="$t('select.selected')"
								deselect-label=""
								label="name"
								track-by="id"
							>
								<template #noResult>
									{{ $t('select.no_results') }}
								</template>

								<template #noOptions>
									{{ $t('select.no_options') }}
								</template>
							</Multiselect>
						</div>
						<span
							class="mt-1 block font-bold text-red-400"
							v-for="error in validationErrors.course_category"
						>
							{{ error }}
						</span>
					</div>
				</div>
			</div>
			<div class="rounded-b-lg bg-gray-100 px-4 py-3 text-right sm:px-6">
				<ButtonSubmit :loading="loading" />
			</div>
		</form>
	</div>
</template>

<script>
import LoadingScreen from '@/components/LoadingScreen.vue';
import BaseInputGroup from '@/components/BaseInputGroup.vue';
import BaseTextarea from '@/components/BaseTextarea.vue';
import ButtonSubmit from '@/components/ButtonSubmit.vue';
import Multiselect from 'vue-multiselect';
import { mapActions, mapGetters, mapMutations, mapState } from 'vuex';
import router from '@/router';
import { useToast } from 'vue-toastification';

export default {
	name: 'TaskForm',
	components: {
		ButtonSubmit,
		BaseTextarea,
		BaseInputGroup,
		LoadingScreen,
		Multiselect
	},

	computed: {
		...mapState('task', ['loading', 'task', 'validationErrors']),
		...mapState('course', ['courseCategories']),
		...mapState('user', ['courses']),
		...mapGetters('task', ['getISODate']),

		availableFrom: {
			set: function (val) {
				this.task.available_from = val;
			},

			get: function () {
				return this.task.available_from && this.getISODate(this.task.available_from);
			}
		},

		availableTo: {
			set: function (val) {
				this.task.available_to = val;
			},

			get: function () {
				return this.task.available_to && this.getISODate(this.task.available_to);
			}
		}
	},

	methods: {
		...mapActions('task', ['showTask', 'createOrEditTask']),
		...mapActions('user', ['getUserCourses']),
		...mapActions('course', ['getCourseCategories']),
		...mapMutations('task', ['resetTask']),

		handleSubmit() {
			const toast = useToast();

			this.task.available_from = this.availableFrom;
			this.task.available_to = this.availableTo;

			this.createOrEditTask(this.task)
				.then((response) => {
					toast.success(this.$t('general.saved_successfully'));
					router.push({
						name: 'TasksView',
						params: { id: response.data.task_ID }
					});
				})
				.catch((error) => {
					toast.error(this.$t('general.validation_errors'));
					throw error;
				});
		},

		getCategories(option) {
			this.$store.commit('task/loading', true);
			this.task.category = '';

			this.getCourseCategories(option.id).then(() => {
				this.$store.commit('task/loading', false);
			});
		},

		resetCategory() {
			this.task.category = '';
			this.$store.commit('course/setCourseCategories', []);
		}
	},

	created() {
		if (this.$route.name === 'TasksEdit') {
			this.showTask(this.$route.params.id).then(() => {
				this.$store.commit('task/loading', true);
				this.getUserCourses();
				this.getCourseCategories(this.task.course.id);
				this.$store.commit('task/loading', false);
			});
		} else {
			this.$store.commit('task/loading', true);
			this.resetTask();

			this.getUserCourses().then(() => {
				this.$store.commit('task/loading', false);
			});
		}
	}
};
</script>
