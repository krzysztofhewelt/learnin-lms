<template>
	<Modal
		:model-value="showModal"
		@update:modelValue="showModal = false"
		:title="$t('general.confirm_delete')"
		wrapper-class="modal-wrapper"
	>
		<div class="m-2 flex">
			<div
				class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100"
			>
				<Warning class="h-8 w-8 text-red-600" />
			</div>
			<p class="ml-4">
				{{ $t('general.are_you_sure_delete') }}
			</p>
		</div>
		<div class="modal-footer">
			<div class="flex flex-row-reverse">
				<button
					class="delete_btn ml-3 mr-[15px]"
					type="button"
					@click.prevent="deleteResource"
				>
					{{ $t('general.delete') }}
				</button>
				<button class="manage_btn" type="button" @click.prevent="showModal = false">
					{{ $t('general.cancel') }}
				</button>
			</div>
		</div>
	</Modal>
</template>

<script>
import VueModal from '@kouts/vue-modal';
import '@kouts/vue-modal/dist/vue-modal.css';
import Warning from '@/components/Icons/Warning.vue';
import { mapActions } from 'vuex';
import { useToast } from 'vue-toastification';

export default {
	name: 'DeleteModal',

	components: {
		Warning,
		Modal: VueModal
	},

	props: {
		showModal: {
			type: Boolean,
			default: false
		},

		deletedResource: {
			required: true,
			validator: function (obj) {
				return (
					obj.id &&
					(Number(obj.id) > 0 || obj.id === -1) &&
					obj.type &&
					[
						'none',
						'course',
						'courseFile',
						'category',
						'task',
						'taskRef',
						'studentFile',
						'user'
					].includes(obj.type)
				);
			}
		}
	},

	methods: {
		...mapActions('user', ['deleteUser']),
		...mapActions('course', ['deleteCourse', 'deleteCourseFile', 'deleteCourseCategory']),
		...mapActions('task', ['deleteTask', 'deleteTaskRefFile', 'deleteStudentFile']),

		deleteResource() {
			switch (this.deletedResource.type) {
				case 'course':
					this.deleteCourse(this.deletedResource.id);
					break;
				case 'courseFile':
					this.deleteCourseFile(this.deletedResource.id);
					break;
				case 'category':
					this.deleteCourseCategory(this.deletedResource.id);
					break;
				case 'task':
					this.deleteTask(this.deletedResource.id);
					break;
				case 'taskRef':
					this.deleteTaskRefFile(this.deletedResource.id);
					break;
				case 'studentFile':
					this.deleteStudentFile(this.deletedResource.id);
					break;
				case 'user':
					this.deleteUser(this.deletedResource.id);
					break;
			}

			const toast = useToast();
			toast.success(this.$t('general.removed_successfully'));

			this.$emit('closing');
		}
	}
};
</script>

<style scoped>
.modal-footer {
	padding: 15px 0 0 0;
	border-top: 1px solid #e5e5e5;
	margin-top: 15px;
	margin-left: -15px;
	margin-right: -15px;
}
</style>
