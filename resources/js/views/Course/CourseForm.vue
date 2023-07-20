<template>
	<div class="relative flex h-fit w-full flex-col break-words rounded-lg bg-white shadow-xl">
		<LoadingScreen v-if="loading" />
		<form @submit.prevent="handleSubmit">
			<div class="p-6">
				<h1 class="mb-4 text-xl font-bold">
					{{
						this.$route.name === 'CoursesEdit'
							? $t('course.editing_course')
							: $t('course.creating_course')
					}}
				</h1>

				<div class="grid grid-cols-1 gap-4 lg:grid-cols-4">
					<div class="col-span-4">
						<BaseInputGroup
							id="name"
							:label="$t('course.course_name')"
							type="text"
							:required="true"
							v-model="course.name"
							:validation-errors="validationErrors.name"
						/>
					</div>
					<div class="col-span-4">
						<BaseTextarea
							id="description"
							:label="$t('course.course_description')"
							v-model="course.description"
							:validation-errors="validationErrors.description"
						/>
					</div>
					<div class="col-span-4 lg:col-span-2">
						<BaseInputGroup
							id="available_from"
							type="datetime-local"
							:label="$t('course.available_from')"
							:required="true"
							v-model="availableFrom"
							:validation-errors="validationErrors.available_from"
						/>
					</div>
					<div class="col-span-4 lg:col-span-2">
						<BaseInputGroup
							id="available_to"
							type="datetime-local"
							:label="$t('course.available_to')"
							v-model="availableTo"
							:validation-errors="validationErrors.available_to"
						/>
					</div>
				</div>
			</div>
			<div class="rounded-b-lg bg-gray-100 px-4 py-3 text-right">
				<ButtonSubmit :loading="loading" />
			</div>
		</form>
	</div>
</template>

<script>
import BaseInputGroup from '@/components/BaseInputGroup.vue';
import BaseTextarea from '@/components/BaseTextarea.vue';
import ButtonSubmit from '@/components/ButtonSubmit.vue';
import LoadingScreen from '@/components/LoadingScreen.vue';
import { mapActions, mapGetters, mapMutations, mapState } from 'vuex';
import Multiselect from 'vue-multiselect';
import { useToast } from 'vue-toastification';
import router from '@/router';

export default {
	name: 'CourseForm',
	components: {
		Multiselect,
		LoadingScreen,
		ButtonSubmit,
		BaseTextarea,
		BaseInputGroup
	},

	computed: {
		...mapState('course', ['course', 'loading', 'validationErrors']),
		...mapGetters('course', ['getISODate']),

		availableFrom: {
			set: function (val) {
				this.course.available_from = val;
			},

			get: function () {
				return this.course.available_from && this.getISODate(this.course.available_from);
			}
		},

		availableTo: {
			set: function (val) {
				this.course.available_to = val;
			},

			get: function () {
				return this.course.available_to && this.getISODate(this.course.available_to);
			}
		}
	},

	methods: {
		...mapActions('course', ['getCourseDetails', 'createOrEditCourse']),
		...mapMutations('course', ['resetCourse']),

		handleSubmit() {
			const toast = useToast();

			this.course.available_from = this.availableFrom;
			this.course.available_to = this.availableTo;

			this.createOrEditCourse(this.course)
				.then((response) => {
					toast.success(this.$t('general.saved_successfully'));
					router.push({
						name: 'CoursesView',
						params: { id: response.data.course_ID }
					});
				})
				.catch((error) => {
					toast.error(this.$t('general.validation_errors'));
					throw error;
				});
		}
	},

	created() {
		if (this.$route.params.id) this.getCourseDetails(this.$route.params.id);
		else this.resetCourse();
	}
};
</script>
