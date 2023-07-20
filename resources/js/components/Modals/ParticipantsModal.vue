<template>
	<Modal
		:model-value="showModal"
		@update:modelValue="showModal = false"
		:title="$t('course.course_participants')"
		wrapper-class="modal-wrapper"
		modal-class="scrollable-modal"
	>
		<div class="scrollable-content">
			<div
				v-for="user in participants"
				:key="user.id"
				class="cursor-pointer py-0.5 font-bold"
				@click="$router.push({ name: 'UsersView', params: { id: user.id } })"
			>
				{{ user.surname }} {{ user.name }}
				<span
					v-if="user.account_role === 'teacher'"
					class="mr-2 rounded bg-orange-300 px-2.5 py-0.5 text-sm font-medium text-orange-950 dark:bg-red-200"
				>
					{{ $t('user.teacher') }}
				</span>

				<span
					v-if="user.account_role === 'admin'"
					class="mr-2 rounded bg-red-100 px-2.5 py-0.5 text-sm font-medium text-red-900 dark:bg-red-200"
				>
					{{ $t('user.admin') }}
				</span>
			</div>
		</div>

		<div class="row scrollable-modal-footer">
			<div class="flex flex-row-reverse">
				<button
					class="normal_btn mr-[15px]"
					type="button"
					@click.prevent="showModal = false"
				>
					{{ $t('general.close') }}
				</button>
			</div>
		</div>
	</Modal>
</template>

<script>
import VueModal from '@kouts/vue-modal';
import '@kouts/vue-modal/dist/vue-modal.css';

export default {
	name: 'ParticipantsModal',

	components: {
		Modal: VueModal
	},

	props: {
		showModal: {
			type: Boolean,
			default: false
		},

		participants: {
			type: Array,
			required: true
		}
	}
};
</script>

<style scoped>
.scrollable-modal {
	display: flex;
	flex-direction: column;
	max-height: calc(100% - 50px);
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
	padding: 10px 15px 10px 15px;
	flex-grow: 1;
}
.scrollable-modal .scrollable-modal-footer {
	padding: 15px 0 0 0;
	border-top: 1px solid #e5e5e5;
	margin-top: 15px;
	margin-left: -15px;
	margin-right: -15px;
}
</style>
