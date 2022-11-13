<template>
	<Modal
		:model-value="showModal"
		@update:modelValue="showModal = false"
		:title="$t('files.upload_files')"
		modal-class="scrollable-modal"
	>
		<form @submit.prevent="uploadFiles" enctype="multipart/form-data">
			<div class="scrollable-content">
				<div
					class="mt-1 flex justify-center rounded-md border-2 border-dashed border-gray-300 p-8 text-lg font-bold"
					v-if="uploading"
				>
					{{ $t('files.uploading_msg') }}
				</div>
				<div
					class="mt-1 flex justify-center rounded-md border-2 border-dashed border-gray-300 p-8"
					:class="{ 'bg-zinc-100': uploadDragoverEvent }"
					@dragover.prevent="onUploadDragoverEvent($event)"
					@drop.prevent="onUploadDropEvent($event)"
					@dragenter="onUploadDragEnter"
					@dragleave="onUploadDragLeave"
					v-if="!uploading"
				>
					<div class="text-center">
						<NewFile class="mx-auto h-12 w-12 text-gray-400" />
						<div class="mb-4 flex text-sm text-gray-600">
							<label
								for="file-upload"
								class="relative cursor-pointer rounded-md font-medium text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 hover:text-indigo-500"
							>
								<span class="font-bold">{{ $t('files.upload_files') }}</span>
								<input
									id="file-upload"
									name="file-upload"
									@change="onAddInputFiles"
									type="file"
									class="sr-only"
									multiple
								/>
							</label>
							<p class="pl-1">{{ $t('files.drag_and_drop') }}</p>
						</div>
						<p class="text-xs text-gray-500">
							{{
								$t('files.max_file_size', {
									size: 256,
									unit: 'MB'
								})
							}}
						</p>
						<p class="text-xs text-gray-500">
							{{ $t('files.max_files', { size: 10 }) }}
						</p>
					</div>
				</div>

				<div
					v-if="files.length > 0"
					class="mt-2 overflow-hidden bg-white shadow-md sm:rounded-md"
				>
					<div v-for="(file, index) in files" :key="index">
						<div
							class="block transition duration-150 ease-in-out hover:bg-gray-50 focus:bg-gray-50 focus:outline-none"
						>
							<div class="px-4 py-3">
								<div class="flex items-center justify-between">
									<div
										class="truncate text-sm font-medium leading-5 text-indigo-600 hover:text-indigo-500"
									>
										<Close
											class="inline h-5 w-5 cursor-pointer text-red-600"
											@click="removeFile(file)"
											v-show="!uploading"
										/>
										{{ file.name }} ({{ getPrettyUnitSize(file.size) }})
									</div>
									<div class="ml-2 flex flex-shrink-0">
										<span
											class="inline-flex rounded-full px-2 text-xs font-semibold leading-5"
											:class="[
												uploading
													? 'bg-orange-100 text-orange-400'
													: 'bg-green-100 text-green-400'
											]"
										>
											{{
												uploading
													? $t('files.uploading')
													: $t('files.ready_to_upload')
											}}
										</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="scrollable-modal-footer">
				<div class="flex flex-row-reverse">
					<ButtonSubmit
						class="ml-3"
						:loading="this.files.length === 0 || uploading"
						:value="$t('files.upload_files')"
					/>
					<button class="manage_btn" type="button" @click.prevent="showModal = false">
						{{ $t('general.cancel') }}
					</button>
				</div>
			</div>
		</form>
	</Modal>
</template>

<script>
import VueModal from '@kouts/vue-modal';
import '@kouts/vue-modal/dist/vue-modal.css';
import NewFile from '@/components/Icons/NewFile.vue';
import ButtonSubmit from '@/components/ButtonSubmit.vue';
import Close from '@/components/Icons/Close.vue';
import { useToast } from 'vue-toastification';
import { mapActions } from 'vuex';

export default {
	name: 'UploadModal',

	components: {
		Close,
		ButtonSubmit,
		NewFile,
		Modal: VueModal
	},

	props: {
		showModal: {
			type: Boolean,
			default: false
		},

		resource: {
			required: true,
			validator: function (obj) {
				return (
					obj.type &&
					['none', 'student_upload', 'task_ref', 'course_file'].includes(obj.type) &&
					obj.id &&
					obj.id &&
					Number.isInteger(obj.id)
				);
			}
		}
	},

	data() {
		return {
			uploading: false,
			files: [],
			pageX: 0,
			pageY: 0,
			uploadDragoverTracking: false,
			uploadDragoverEvent: false
		};
	},

	methods: {
		...mapActions('course', ['getCourseDetails']),
		...mapActions('task', ['showTask']),

		onAddInputFiles(e) {
			let inputFiles = e.target.files;

			if (!inputFiles) return;

			[...inputFiles].forEach((f) => {
				if (this.fileValidator(f)) this.files.push(f);
			});
		},

		getPrettyUnitSize(filesize) {
			const units = ['B', 'KB', 'MB', 'GB'];
			let i = 0;
			while (filesize > 900) {
				filesize /= 1024;
				i++;
			}

			return Math.round(filesize * 100) / 100 + ' ' + units[i];
		},

		onUploadDragEnter() {
			this.uploadDragoverEvent = true;
		},

		onUploadDragLeave() {
			this.uploadDragoverEvent = false;
		},

		onUploadDragoverEvent(e) {
			this.uploadDragoverEvent = true;
			this.uploadDragoverTracking = true;
			this.pageX = e.pageX;
			this.pageY = e.pageY;
		},

		onUploadDropEvent(e) {
			this.uploadDragoverEvent = false;
			this.uploadDragoverTracking = false;
			this.pageX = 0;
			this.pageY = 0;
			this.droppedFiles(e);
		},

		droppedFiles(e) {
			let droppedFiles = e.dataTransfer.files;

			if (!droppedFiles) return;

			[...droppedFiles].forEach((f) => {
				if (this.fileValidator(f)) this.files.push(f);
			});
		},

		fileValidator(file) {
			return (
				file.size <= 256000000 &&
				this.files.length < 10 &&
				this.files.filter((f) => f.name === file.name).length === 0
			);
		},

		removeFile(file) {
			this.files = this.files.filter((f) => {
				return f !== file;
			});
		},

		async uploadFiles() {
			let formData = new FormData();
			const toast = useToast();

			formData.append('file_type', this.resource.type);
			this.files.forEach((file, index) => {
				formData.append('files[' + index + ']', file);
			});

			const config = {
				headers: {
					'Content-Type': 'multipart/form-data'
				}
			};

			this.uploading = true;
			await axios
				.post('/upload/' + this.resource.id, formData, config)
				.then(() => {
					this.uploading = false;
					this.updateFiles(this.resource.type);

					toast.success(this.$t('files.uploaded_successfully'));
					this.files = [];
					this.$emit('closing');
				})
				.catch(() => {
					this.uploading = false;
					toast.error(this.$t('files.uploaded_failure'));
				});
		},

		updateFiles(fileType) {
			switch (fileType) {
				case 'course_file':
					this.getCourseDetails(this.resource.id);
					break;
				case 'task_ref':
				case 'student_upload':
					this.showTask(this.resource.id);
					break;
			}
		}
	}
};
</script>

<style scoped>
.scrollable-modal {
	display: flex;
	align-items: center;
	flex-direction: column;
	max-height: calc(100% - 50px);
}

.scrollable-modal .vm {
	top: auto;
}

.scrollable-modal .vm-titlebar {
	flex-shrink: 0;
}

.scrollable-modal .vm-content {
	padding: 0;
	flex-grow: 1;
	display: flex;
	flex-direction: column;
	min-height: 0;
}

.scrollable-modal .vm-content .scrollable-content {
	position: relative;
	overflow-y: auto;
	overflow-x: hidden;
	flex-grow: 1;
}

.scrollable-modal .scrollable-modal-footer {
	padding: 15px 15px 0 0;
	border-top: 1px solid #e5e5e5;
	margin-top: 15px;
	margin-left: -15px;
	margin-right: -15px;
}
</style>
