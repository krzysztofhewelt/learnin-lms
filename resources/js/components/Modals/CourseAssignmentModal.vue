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
		<form @submit.prevent="handleSubmit">
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
				:loading="loadingUsers"
				:internal-search="false"
				:clear-on-select="false"
				:show-labels="false"
				:close-on-select="false"
				:show-no-results="true"
				:options-limit="200"
				:max-height="300"
				:hide-selected="true"
				@search-change="debounceSearch"
			>
				<template #tag="{ option, remove }">
					<span
						class="align-center mx-1 my-1 inline-flex justify-center rounded-lg px-2 py-0.5"
						:class="getTagColorForUser(option.account_role)"
					>
						<span>{{ option.surname }} {{ option.name }}</span>
						<Close
							class="my-auto ml-1 h-4 w-4 cursor-pointer text-red-800"
							@click="remove(option)"
						/>
					</span>
				</template>

				<template #option="{ option }">
					<span>{{ option.surname }} {{ option.name }}</span>
                    <span :class="getLabelColorForUser(option.account_role)">{{ option.account_role }}</span>
				</template>

				<template #noResult>
					{{ $t('user.no_users_found') }}
				</template>

				<template #noOptions>
					{{ $t('select.no_options') }}
				</template>

				<template #afterList>
					<div
						ref="load_more"
						v-observe-visibility="{
							callback: loadMoreUsers,
							throttle: 300
						}"
						v-if="page + 1 <= lastPage && !loadingUsers"
					></div>
				</template>
			</Multiselect>

			<span class="mt-1 block font-bold text-red-400" v-if="validationErrors.assignedUsers">
				{{ $t('validation.validation_empty_assignedUsers') }}
			</span>

			<div class="modal-footer mt-4">
				<div class="flex flex-row-reverse">
					<ButtonSubmit class="ml-3 mr-[15px]" :loading="loading" />

					<button class="normal_btn" type="button" @click.prevent="showModal = false">
						{{ $t('general.cancel') }}
					</button>
				</div>
			</div>
		</form>

        {{ searchString }}
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
import ButtonSubmit from '@/components/ButtonSubmit.vue';
import MultiselectInputGroup from '@/components/MultiselectInputGroup.vue';

export default {
	name: 'CourseAssignmentModal',

	components: {
		MultiselectInputGroup,
		ButtonSubmit,
		Close,
		Modal: VueModal,
		Multiselect
	},

	data() {
		return {
			loadingUsers: false,
			users: [],
			page: 1,
			lastPage: 1,
			searchString: ''
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
        search() {
            return search
        },
		...mapState('course', ['validationErrors', 'loading'])
	},

	created() {
		this.debounceSearch = debounce((query) => {
			this.users = [];
			this.page = 1;
			this.searchString = query;
			this.searchUsersFromSelect(query, this.page);
		}, 500);
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

        getLabelColorForUser(accountRole) {
            switch (accountRole) {
                case 'student':
                    return 'p-1 bg-green-400 rounded-md ml-2';
                case 'teacher':
                    return 'p-1 bg-orange-400 rounded-md ml-2';
                case 'admin':
                    return 'p-1 bg-red-400 rounded-md ml-2';
            }
        },

		searchUsersFromSelect(option, page) {
			if (option !== '') {
				this.loadingUsers = true;
				axios
					.get('/admin/users', {
						params: {
							page: page,
							search: option
						}
					})
					.then((response) => {
						this.users.push.apply(this.users, response.data.data);
						this.page = response.data.current_page;
						this.lastPage = response.data.last_page;
						this.loadingUsers = false;
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
		},

		loadMoreUsers(isVisible) {
			if (isVisible) this.searchUsersFromSelect(this.searchString, ++this.page);
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
