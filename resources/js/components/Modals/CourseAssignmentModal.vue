<template>
	<Modal
		:model-value="showModal"
		@update:modelValue="showModal = false"
		:title="$t('course.assign_to_course')"
		wrapper-class="modal-wrapper"
	>
		<h4 class="font-bold">{{ $t('select.assignment_instruction_title') }}:</h4>
		<p class="mb-4">
			{{ $t('select.assignment_instruction_explain') }}
			<span class="bg-orange-300">{{ $t('select.assignment_instruction_teacher') }}</span
			>, <span class="bg-green-300">{{ $t('select.assignment_instruction_student') }}</span
			>,
			<span class="bg-red-300">{{ $t('select.assignment_instruction_admin') }}</span>
		</p>

		<label class="font-bold" for="participants"
			>{{ $t('select.assignment_select_label') }}:</label
		>
		<Multiselect
			:model-value="participants"
			@update:modelValue="participants = $event"
			id="participants"
			label="name"
			track-by="id"
			:placeholder="$t('user.type_to_search')"
			open-direction="bottom"
			:options="users"
			:multiple="true"
			:searchable="true"
			:loading="loading"
			:internal-search="false"
			:clear-on-select="false"
			:show-labels="false"
			:close-on-select="false"
			:show-no-results="true"
			:options-limit="200"
			:max-height="600"
			:hide-selected="true"
			@search-change="debounceSearch"
		>
			<template #tag="{ option, remove }">
				<span
					class="mx-1 my-1 inline-block rounded-lg px-2 py-0.5"
					:class="getTagColorForUser(option.account_role)"
				>
					<span>{{ option.surname }} {{ option.name }}</span>
					<span class="ml-1 font-bold" @click="remove(option)">
						<Close class="inline h-4 w-4 cursor-pointer text-red-800" />
					</span>
				</span>
			</template>

			<template #option="{ option }">
				<span>{{ option.surname }} {{ option.name }} - {{ option.account_role }}</span>
			</template>

			<template #noResult>
				{{ $t('user.no_users_found') }}
			</template>

			<template #noOptions>
				{{ $t('select.no_options') }}
			</template>
		</Multiselect>

		<span class="mt-1 block font-bold text-red-400" v-if="validationErrors.assignedUsers">
			{{ $t('validation.validation_empty_assignedUsers') }}
		</span>

		<div class="modal-footer mt-4">
			<div class="flex flex-row-reverse">
				<button
					class="submit_btn ml-3 mr-[15px]"
					type="button"
					@click="handleSubmit"
					:disabled="loading"
				>
					{{ $t('general.save') }}
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
import Multiselect from 'vue-multiselect';
import { mapActions, mapState } from 'vuex';
import { useToast } from 'vue-toastification';
import Close from '@/components/Icons/Close.vue';
import { debounce } from 'lodash';

export default {
	name: 'CourseAssignmentModal',

	components: {
		Close,
		Modal: VueModal,
		Multiselect
	},

	data() {
		return {
			loading: false,
			users: []
		};
	},

	props: {
		showModal: {
			type: Boolean,
			default: false
		},

		courseId: {
			type: Number,
			required: true
		},

		participants: {
			type: Array,
			required: true
		}
	},

	computed: {
		...mapState('course', ['validationErrors'])
	},

	created() {
		this.debounceSearch = debounce((query) => this.searchUsersFromSelect(query), 500);
	},

	methods: {
		...mapActions('course', ['assignToCourse']),

		getTagColorForUser(accountRole) {
			switch (accountRole) {
				case 'student':
					return 'bg-green-300 hover:bg-green-400';
				case 'teacher':
					return 'bg-orange-300 hover:bg-orange-400';
				case 'admin':
					return 'bg-red-300 hover:bg-red-400';
			}
		},

		searchUsersFromSelect(option) {
			if (option !== '') {
				this.loading = true;
				axios
					.post('/users/search', {
						searchString: option
					})
					.then((response) => {
						this.users = response.data.data;
						this.loading = false;
					});
			}
		},

		handleSubmit() {
			const toast = useToast();

			this.assignToCourse({
				courseId: this.courseId,
				participants: this.participants
			})
				.then(() => {
					toast.success(this.$t('general.saved_successfully'));
					this.$emit('closing');
				})
				.catch(() => {
					toast.error(this.$t('general.validation_errors'));
				});
		}
	}
};
</script>

<style scoped>
.modal-footer {
	padding: 15px 0 0 0;
	border-top: 1px solid #e5e5e5;
	margin-left: -14px;
	margin-right: -14px;
}
</style>
