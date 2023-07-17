<template>
	<DeleteModal
		:show-modal="showDeleteModal"
		@closing="showDeleteModal = false"
		:deleted-resource="deletedResource"
	/>

	<UploadModal
		:show-modal="showUploadModal"
		@closing="showUploadModal = false"
		:resource="uploadResource"
	/>

	<div class="relative flex h-fit w-full flex-col break-words rounded-lg bg-white shadow-xl">
		<LoadingScreen v-if="loading" />
		<div class="grid grid-cols-3 grid-rows-1 divide-x">
			<div class="col-span-3 p-12 lg:col-span-2">
				<div class="mb-6 lg:flex lg:justify-between">
					<h1 class="my-auto text-xl font-bold">
						{{ $t('task.task_details') }}
					</h1>
					<div class="flex flex-col gap-2 md:flex-row" v-if="isTeacher">
						<router-link
							:to="{
								name: 'MarksTask',
								params: { id: $route.params.id }
							}"
							class="normal_btn justify-center"
						>
							<Edit class="mr-1 h-4 w-4" />
							{{ $t('task.issue_grades') }}
						</router-link>

						<router-link
							:to="{
								name: 'TaskStudentUploads',
								params: { id: $route.params.id }
							}"
							class="normal_btn justify-center"
						>
							<Edit class="mr-1 h-4 w-4" />
							{{ $t('task.student_files') }}
						</router-link>

						<div class="justify-center">
							<Dropdown>
								<button class="normal_btn w-full">
									<More class="mx-auto h-5 w-5" />
								</button>

								<template #content>
									<router-link
										:to="{
											name: 'TasksEdit',
											params: { id: $route.params.id }
										}"
										class="block w-full p-2 font-bold text-blue-600 no-underline"
									>
										{{ $t('general.edit') }}
									</router-link>
									<button
										class="w-full p-2 font-bold text-red-400"
										@click="showDeleteResourceModal('task', $route.params.id)"
									>
										{{ $t('general.delete') }}
									</button>
								</template>
							</Dropdown>
						</div>
					</div>
				</div>
				<div class="pb-6">
					<h1 class="my-5 inline text-3xl font-bold">
						{{ task.name }}
					</h1>
					<div class="inline" v-if="isStudent">
						<span
							v-if="userMark !== null"
							class="ml-3 text-lg font-semibold text-green-600"
						>
							{{ $t('task.marked') }}
						</span>
						<span v-else class="ml-3 text-lg font-semibold text-red-600">
							{{ $t('task.not_marked') }}
						</span>
					</div>
				</div>
				<div class="border-b-[1px] py-3" v-if="userMark !== null && isStudent">
					<h1 class="text-xl font-bold">
						{{ $t('mark.task_marked_label') }}
					</h1>
					<div
						class="mt-2 grid grid-cols-1 space-y-3 text-center lg:grid-cols-3 lg:space-y-0 lg:divide-x"
					>
						<div class="px-2">
							<div class="text-zinc-400">
								{{ $t('mark.points_earned_label') }}
							</div>
							{{ userMark.points }}
						</div>
						<div class="px-2">
							<div class="text-zinc-400">
								{{ $t('mark.mark_earned_label') }}
							</div>
							{{ userMark.mark }}
						</div>
						<div class="px-2">
							<div class="text-zinc-400">
								{{ $t('mark.date_of_mark_label') }}
							</div>
							{{ getFormattedMarkDate(userMark.updated_at) }}
						</div>
					</div>
					<div v-if="userMark.description && userMark.description.length > 0">
						<div class="mt-2 text-zinc-400">
							{{ $t('mark.comment') }}
						</div>
						<p class="mb-0 whitespace-pre-line">
							{{ userMark.description }}
						</p>
					</div>
				</div>
				<div class="border-b-[1px] py-3">
					<h1 class="mb-2 text-xl font-bold">
						{{ $t('task.task_description') }}
					</h1>
					<p class="whitespace-pre-line">
						{{ descriptionLimitedString }}
					</p>

					<button
						class="font-medium text-blue-600 no-underline"
						v-if="getDescriptionLength > 500"
						@click="descriptionExpanded = !descriptionExpanded"
					>
						{{
							descriptionExpanded ? $t('general.read_less') : $t('general.read_more')
						}}
					</button>
				</div>
				<div class="border-b-[1px] py-3">
					<div
						class="grid grid-cols-1 space-y-3 text-center lg:grid-cols-3 lg:space-y-0 lg:divide-x"
					>
						<div class="px-2">
							<div class="text-zinc-400">
								{{ $t('task.available_from_label') }}
							</div>
							{{ getFormattedDate(task.available_from) }}
						</div>
						<div class="px-2">
							<div class="text-zinc-400">
								{{ $t('task.available_to_label') }}
							</div>
							<Popper
								v-if="!isEnded"
								:content="
									getFormattedDate(task.available_to) ||
									$t('general.not_available')
								"
								hover
							>
								{{
									getRelativeTime(task.available_to) ||
									$t('general.not_available')
								}}
							</Popper>
							<Popper v-else :content="getFormattedDate(task.available_to)" hover>
								<div class="font-bold text-green-600">
									{{ $t('course.ended_course') }}
								</div>
							</Popper>
						</div>
						<div class="px-2">
							<div class="text-zinc-400">
								{{ $t('task.max_points_to_earn') }}
							</div>
							{{ task.max_points }}
						</div>
					</div>
				</div>
				<div class="border-b-[1px] py-3" v-if="isStudent">
					<h1 class="text-xl font-bold">
						{{ $t('task.your_files') }}
					</h1>
					<button
						class="normal_btn mb-2"
						v-if="studentCanUploadFiles"
						@click="showUploadsModal('student_upload')"
					>
						<Upload class="mr-1 h-4 w-4" />
						{{ $t('files.upload_files') }}
					</button>
					<div class="mb-2 text-lg font-bold text-red-600" v-else>
						{{ $t('task.cant_upload_file_after') }}
					</div>
					<div
						class="grid grid-cols-1 gap-3 lg:grid-cols-3"
						v-if="studentFiles.length > 0"
					>
						<div
							class="col-span-3 lg:col-span-1"
							v-for="studentFile in studentFiles"
							:key="studentFile.id"
						>
							<button
								class="block break-all text-left font-bold text-purple-800 no-underline hover:text-purple-500"
								@click="download(studentFile.id, 'student_upload')"
							>
								{{ studentFile.filename_original }} ({{ studentFile.file_size }}
								{{ studentFile.file_size_unit }})
							</button>
							<div class="font-sm block text-zinc-400">
								<span class="font-sm block text-zinc-400">
									{{ $t('general.updated') }}
									<Popper
										:content="getFormattedDate(studentFile.updated_at)"
										hover
									>
										<span class="underline decoration-dotted">{{
											getRelativeTime(studentFile.updated_at)
										}}</span>
									</Popper>
								</span>
							</div>
							<button
								v-if="studentCanUploadFiles"
								class="font-semibold text-red-400 hover:text-red-600"
								@click="showDeleteResourceModal('studentFile', studentFile.id)"
							>
								{{ $t('general.delete') }}
							</button>
						</div>
					</div>
					<div class="text-lg font-bold underline" v-if="studentFiles.length === 0">
						{{ $t('general.no_files') }}
					</div>
				</div>
			</div>
			<div class="col-span-4 row-span-5 p-12 lg:col-span-1">
				<div>
					<div class="border-b-[1px] pb-3">
						<h1 class="mb-2 text-xl font-bold">
							{{ $t('files.files_to_download') }}
						</h1>
						<button
							class="normal_btn"
							v-if="isTeacher"
							@click="showUploadsModal('task_ref')"
						>
							<Upload class="mr-1 h-4 w-4" />
							{{ $t('files.upload_files') }}
						</button>
					</div>
					<div class="py-3 text-center font-bold" v-if="getRefFilesCount === 0">
						{{ $t('general.no_files') }}
					</div>
					<div class="py-3" v-for="file in refFilesLimitedArray" :key="file.id">
						<button
							class="block break-all text-left font-bold text-purple-800 no-underline hover:text-purple-500"
							@click="download(file.id, 'task_ref')"
						>
							{{ file.filename_original }} ({{ file.file_size }}
							{{ file.file_size_unit }})
						</button>
						<span class="font-sm block text-zinc-400">
							{{ $t('general.updated') }}
							<Popper :content="getFormattedDate(file.updated_at)" hover>
								<span class="underline decoration-dotted">{{
									getRelativeTime(file.updated_at)
								}}</span>
							</Popper>
						</span>
						<button
							class="font-semibold text-red-400 hover:text-red-600"
							v-if="isTeacher"
							@click="showDeleteResourceModal('taskRef', file.id)"
						>
							{{ $t('general.delete') }}
						</button>
					</div>
					<button
						class="font-medium text-blue-600 no-underline"
						v-if="getRefFilesCount > 5"
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
import Dropdown from '@/components/Dropdown.vue';
import Upload from '@/components/Icons/Upload.vue';
import Popper from 'vue3-popper';
import { mapActions, mapGetters, mapState } from 'vuex';
import DeleteModal from '@/components/Modals/DeleteModal.vue';
import LoadingScreen from '@/components/LoadingScreen.vue';
import UploadModal from '@/components/Modals/UploadModal.vue';
import download from '@/utils/download';

export default {
	name: 'TaskView',
	components: {
		UploadModal,
		LoadingScreen,
		DeleteModal,
		Upload,
		Dropdown,
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
			showUploadModal: false,

			deletedResource: {
				type: 'none',
				id: -1
			},

			uploadResource: {
				type: 'none',
				id: Number(this.$route.params.id)
			}
		};
	},

	computed: {
		...mapState('task', ['loading', 'task', 'taskRefFiles', 'studentFiles', 'userMark']),
		...mapGetters('task', [
			'getFormattedDate',
			'getFormattedMarkDate',
			'getRelativeTime',
			'getDescriptionLength',
			'getRefFilesCount',
			'getStudentFilesCount',
			'isEnded',
			'studentCanUploadFiles'
		]),
		...mapGetters('login', ['isTeacher', 'isStudent', 'isAdmin']),

		refFilesLimitedArray: function () {
			return this.filesExpanded
				? this.taskRefFiles
				: this.taskRefFiles && this.taskRefFiles.slice(0, 5);
		},

		descriptionLimitedString: function () {
			return this.descriptionExpanded
				? this.task.description
				: (this.task.description &&
						this.task.description.length <= 500 &&
						this.task.description.slice(0, 500)) ||
						(this.task.description &&
							this.task.description.length > 500 &&
							this.task.description.slice(0, 500).concat('...'));
		}
	},

	methods: {
		download,
		...mapActions('task', ['showTask']),

		showDeleteResourceModal(resourceType, resourceId) {
			this.showDeleteModal = true;
			this.deletedResource.type = resourceType;
			this.deletedResource.id = resourceId;
		},

		showUploadsModal(type) {
			this.showUploadModal = true;
			this.uploadResource.type = type;
		},

		closeDropdown() {
			this.moreDropdown = false;
		}
	},

	created() {
		this.showTask(this.$route.params.id);
	}
};
</script>
