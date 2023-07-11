<template>
	<DeleteModal
		:show-modal="showDeleteModal"
		@closing="showDeleteModal = false"
		:deleted-resource="deletedResource"
	/>

	<CourseCategoryModal
		:show-modal="showCourseCategoryModal"
		@closing="showCourseCategoryModal = false"
		:action="courseCategoryModalAction"
		:category="courseCategoryModalCategory"
		:course-id="courseCategoryModalCourseId"
	/>

	<UploadModal
		:show-modal="showUploadModal"
		@closing="showUploadModal = false"
		:resource="uploadResource"
	/>

	<ParticipantsModal
		:show-modal="showParticipantsModal"
		@closing="showParticipantsModal = false"
		:participants="courseUsers"
	/>

	<CourseAssignmentModal
		:show-modal="showCourseAssignmentModal"
		@closing="showCourseAssignmentModal = false"
		:participants="courseUsers"
		:course-id="Number($route.params.id)"
	/>

	<div class="relative flex h-fit w-full flex-col break-words rounded-lg bg-white shadow-xl">
		<LoadingScreen v-if="loading" />
		<div class="grid grid-cols-3 grid-rows-1 lg:divide-x">
			<div class="col-span-3 p-12 lg:col-span-2">
				<div class="mb-6 lg:flex lg:justify-between">
					<h1 class="my-auto text-xl font-bold">
						{{ $t('course.course_details') }}
					</h1>
					<div class="flex flex-col gap-2 md:flex-row" v-if="isTeacher || isAdmin">
						<button class="normal_btn justify-center" @click="showAssignmentModal">
							<Edit class="mr-1 h-4 w-4" />
							{{ $t('course.assign_to_course') }}
						</button>

						<div class="justify-center">
							<button class="normal_btn w-full" @click.stop="moreDropdown = !moreDropdown">
								<More class="mx-auto h-5 w-5" />
							</button>

							<Dropdown v-if="moreDropdown" v-click-outside="closeDropdown">
								<router-link
									:to="{
										name: 'CoursesEdit',
										params: { id: $route.params.id }
									}"
									class="block w-full p-2 font-bold text-blue-600 no-underline"
								>
									{{ $t('general.edit') }}
								</router-link>

								<button
									class="w-full p-2 font-bold text-red-400"
									@click="showDeleteResourceModal('course', $route.params.id)"
								>
									{{ $t('general.delete') }}
								</button>
							</Dropdown>
						</div>
					</div>
				</div>
				<h1 class="my-5 text-3xl font-bold">{{ course.name }}</h1>
				<div class="border-b-[1px] py-4">
					<div
						class="grid grid-cols-1 space-y-3 text-center lg:grid-cols-4 lg:space-y-0 lg:divide-x"
					>
						<div class="px-2">
							<div class="text-zinc-400">
								{{ $t('course.categories_label') }}
							</div>
							{{ getCategoriesCount > 0 ? getCategoriesCount : 0 }}
						</div>
						<div class="px-2">
							<div class="text-zinc-400">
								{{ $t('course.available_from_label') }}
							</div>
							{{ getFormattedDate(course.available_from) }}
						</div>
						<div class="px-2">
							<div class="text-zinc-400">
								{{ $t('course.available_to_label') }}
							</div>
							<Popper
								v-if="!isEnded"
								:content="
									getFormattedDate(course.available_to) ||
									$t('general.not_available')
								"
								hover
							>
								{{
									getRelativeTime(course.available_to) ||
									$t('general.not_available')
								}}
							</Popper>
							<Popper v-else :content="getFormattedDate(course.available_to)" hover>
								<span class="font-bold text-green-600">{{
									$t('course.ended_course')
								}}</span>
							</Popper>
						</div>
						<div class="cursor-pointer px-2" @click="showParticipantsModal = true">
							<div class="text-zinc-400">
								{{ $t('course.participants_label') }}
							</div>
							<div class="font-bold text-purple-800">
								{{ getCourseUsersCount }}
							</div>
						</div>
					</div>
				</div>
				<div v-if="getDescriptionLength > 0" class="border-b-[1px] py-4">
					<h1 class="mb-2 text-xl font-bold">
						{{ $t('course.course_description') }}
					</h1>
					<p class="whitespace-pre-line">
						{{ descriptionLimitedString }}
					</p>

					<button
						class="font-medium text-blue-600"
						v-if="getDescriptionLength > 500"
						@click="descriptionExpanded = !descriptionExpanded"
					>
						{{
							descriptionExpanded ? $t('general.read_less') : $t('general.read_more')
						}}
					</button>
				</div>
				<div class="border-b-[1px] py-4">
					<div class="lg:flex lg:justify-between">
						<div>
							<h1 class="mb-0 text-xl font-bold">
								{{ $t('course.course_categories_title') }}
							</h1>
							<span class="text-slate-400 lg:text-sm" v-if="getCategoriesCount > 0">
								{{
									$tChoice('course.categories', getCategoriesCount, {
										value: getCategoriesCount
									})
								}}
							</span>
						</div>
						<div>
							<button
								class="normal_btn"
								v-if="isTeacher"
								@click="
									showCategoryModal(course.id, { id: -1, name: '' }, 'create')
								"
							>
								<Add class="mr-1 h-4 w-4" />
								{{ $t('general.add_new') }}
							</button>
						</div>
					</div>
					<div v-if="getCategoriesCount === 0" class="text-center font-bold">
						{{ $t('general.no_data') }}
					</div>
					<div
						v-for="category in courseCategories"
						:key="category.id"
						class="mt-3 flex flex-1 justify-between"
					>
						<div>
							<router-link
								:to="{
									name: 'TasksInCategory',
									params: { id: category.id }
								}"
								class="block font-bold text-purple-800 no-underline hover:text-purple-500"
								>{{ category.name }}</router-link
							>
						</div>
						<div v-if="isTeacher">
							<button
								class="px-2 font-semibold text-blue-600 hover:text-blue-900"
								@click="
									showCategoryModal(
										course.id,
										{
											id: category.id,
											name: category.name
										},
										'edit'
									)
								"
							>
								{{ $t('general.edit') }}
							</button>
							<button
								class="font-semibold text-red-400 hover:text-red-600"
								@click="showDeleteResourceModal('category', category.id, false)"
							>
								{{ $t('general.delete') }}
							</button>
						</div>
					</div>
				</div>
			</div>
			<div class="col-span-3 row-span-5 p-12 lg:col-span-1">
				<div>
					<div class="border-b-[1px] pb-3">
						<h1 class="mb-2 text-xl font-bold">
							{{ $t('files.files_to_download') }}
						</h1>
						<button
							class="normal_btn"
							v-if="isTeacher"
							@click="showUploadsModal('course_file')"
						>
							<Upload class="mr-1 h-4 w-4" />
							{{ $t('files.upload_files') }}
						</button>
					</div>
					<div class="py-3 text-center font-bold" v-if="getCourseFilesCount === 0">
						{{ $t('general.no_files') }}
					</div>
					<div class="py-3" v-for="file in filesLimitedArray" :key="file.id">
						<button
							class="break-all text-left font-bold text-purple-800 no-underline hover:text-purple-500"
							@click="download(file.id, 'course_file')"
						>
							{{ file.filename_original }} ({{ file.file_size }}
							{{ file.file_size_unit }})
						</button>
						<div class="font-sm text-zinc-400">
							{{ $t('general.updated') }}
							<Popper :content="getFormattedDate(file.updated_at)" hover>
								<span class="underline decoration-dotted">{{
									getRelativeTime(file.updated_at)
								}}</span>
							</Popper>
						</div>
						<button
							class="font-semibold text-red-400 hover:text-red-600"
							v-if="isTeacher"
							@click="showDeleteResourceModal('courseFile', file.id)"
						>
							{{ $t('general.delete') }}
						</button>
					</div>
					<button
						class="font-medium text-blue-600"
						v-if="getCourseFilesCount > 5"
						@click="filesExpanded = !filesExpanded"
					>
						{{ filesExpanded ? $t('general.show_less') : $t('general.show_more') }}
					</button>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import Edit from '@/components/Icons/Edit.vue';
import More from '@/components/Icons/More.vue';
import Add from '@/components/Icons/Add.vue';
import Upload from '@/components/Icons/Upload.vue';
import LoadingScreen from '@/components/LoadingScreen.vue';
import Dropdown from '@/components/Dropdown.vue';
import { mapActions, mapGetters, mapState } from 'vuex';
import Popper from 'vue3-popper';
import VueModal from '@kouts/vue-modal';
import '@kouts/vue-modal/dist/vue-modal.css';
import DeleteModal from '@/components/Modals/DeleteModal.vue';
import CourseCategoryModal from '@/components/Modals/CourseCategoryModal.vue';
import UploadModal from '@/components/Modals/UploadModal.vue';
import ParticipantsModal from '@/components/Modals/ParticipantsModal.vue';
import CourseAssignmentModal from '@/components/Modals/CourseAssignmentModal.vue';
import download from '@/utils/download';

export default {
	name: 'CourseView',
	components: {
		CourseAssignmentModal,
		ParticipantsModal,
		UploadModal,
		CourseCategoryModal,
		DeleteModal,
		Modal: VueModal,
		Dropdown,
		LoadingScreen,
		Upload,
		Add,
		More,
		Edit,
		Popper
	},

	data() {
		return {
			moreDropdown: false,
			descriptionExpanded: false,
			filesExpanded: false,

			showDeleteModal: false,
			showCourseCategoryModal: false,
			showUploadModal: false,
			showParticipantsModal: false,
			showCourseAssignmentModal: false,

			deletedResource: {
				type: 'none',
				id: -1
			},

			courseCategoryModalAction: 'create',
			courseCategoryModalCourseId: -1,
			courseCategoryModalCategory: {},

			uploadResource: {
				type: 'none',
				id: Number(this.$route.params.id)
			}
		};
	},

	computed: {
		...mapState('course', [
			'loading',
			'course',
			'courseUsers',
			'courseCategories',
			'courseFiles'
		]),
		...mapGetters('course', [
			'getFormattedDate',
			'getRelativeTime',
			'getCategoriesCount',
			'getCourseUsersCount',
			'getDescriptionLength',
			'getCourseFilesCount',
			'isEnded'
		]),
		...mapGetters('login', ['isTeacher', 'isAdmin']),

		filesLimitedArray: function () {
			return this.filesExpanded
				? this.courseFiles
				: this.courseFiles && this.courseFiles.slice(0, 5);
		},

		descriptionLimitedString: function () {
			return this.descriptionExpanded
				? this.course.description
				: (this.course.description &&
						this.course.description.length <= 500 &&
						this.course.description.slice(0, 500)) ||
						(this.course.description &&
							this.course.description.length > 500 &&
							this.course.description.slice(0, 500).concat('...'));
		}
	},

	methods: {
        download,
		...mapActions('course', ['getCourseDetails']),

		showDeleteResourceModal(resourceType, resourceId) {
			this.showDeleteModal = true;
			this.deletedResource.type = resourceType;
			this.deletedResource.id = resourceId;
		},

		showCategoryModal(courseId, category, action) {
			this.showCourseCategoryModal = true;
			this.courseCategoryModalAction = action;
			this.courseCategoryModalCourseId = courseId;
			this.courseCategoryModalCategory = category;
		},

		showUploadsModal(type) {
			this.showUploadModal = true;
			this.uploadResource.type = type;
		},

		showAssignmentModal() {
			this.showCourseAssignmentModal = true;
		},

        closeDropdown() {
            this.moreDropdown = false;
        }
	},

	created() {
		this.getCourseDetails(this.$route.params.id);
	}
};
</script>
