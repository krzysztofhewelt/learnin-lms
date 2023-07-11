<template>
	<Modal
		:model-value="showModal"
		@update:modelValue="showModal = false"
		:title="setModalTitle"
		wrapper-class="modal-wrapper"
	>
		<form @submit.prevent="handleSubmit">
			<div class="m-2">
				<div class="w-full">
					<BaseInputGroup
						id="name"
						:label="$t('course.course_category')"
						type="text"
						:required="true"
						v-model="category.name"
						:validation-errors="validationErrors.name"
					/>
				</div>
			</div>
			<div class="modal-footer">
				<div class="flex flex-row-reverse">
                    <ButtonSubmit
                        class="ml-3"
                        :loading="loading"
                    />

					<button class="normal_btn" type="button" @click.prevent="showModal = false">
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
import BaseInputGroup from '@/components/BaseInputGroup.vue';
import { mapActions, mapState } from 'vuex';
import { useToast } from 'vue-toastification';
import LoadingScreen from '@/components/LoadingScreen.vue';
import ButtonSubmit from '@/components/ButtonSubmit.vue';

export default {
	name: 'CourseCategoryModal',

	components: {
        ButtonSubmit,
		LoadingScreen,
		BaseInputGroup,
		Modal: VueModal
	},

	props: {
		showModal: {
			type: Boolean,
			default: false
		},

		category: {
			type: Object,
			default: ''
		},

		courseId: {
			type: Number,
			default: ''
		},

		action: {
			required: true,
			validator: function (arr) {
				return arr === 'create' || arr === 'edit';
			}
		}
	},

	computed: {
		...mapState('course', ['validationErrors', 'loading']),

		setModalTitle() {
			return this.action === 'create'
				? this.$t('course.add_category_title')
				: this.$t('course.edit_category_title');
		}
	},

	methods: {
		...mapActions('course', ['createOrEditCategory']),

		handleSubmit() {
			const toast = useToast();

			this.createOrEditCategory({
				courseId: this.courseId,
				category: { id: this.category.id, name: this.category.name }
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
	padding: 15px 15px 0 0;
	border-top: 1px solid #e5e5e5;
	margin-top: 15px;
	margin-left: -15px;
	margin-right: -15px;
}
</style>
