<template>
	<div class="relative flex h-fit w-full flex-col break-words rounded-lg bg-white shadow-xl">
		<LoadingScreen v-if="loading" />
		<form @submit.prevent="handleSubmit">
			<div class="p-6">
				<h1 class="my-auto mb-4 block text-xl font-bold">
					{{
						this.$route.name === 'UsersEdit'
							? $t('user.editing_user')
							: $t('user.creating_user')
					}}
				</h1>

				<div class="grid grid-cols-4 gap-x-4 gap-y-2">
					<div class="col-span-4 lg:col-span-1">
						<MultiselectInputGroup
							:label="$t('user.account_role')"
							:required="true"
							:validation-errors="validationErrors.account_role"
							id="account_role"
						>
							<Multiselect
								id="account_role"
								v-model="user.account_role"
								:options="account_roles"
								:loading="loading"
								:searchable="false"
								:allow-empty="false"
								select-label=""
								:selected-label="$t('select.selected')"
								deselect-label=""
								:placeholder="$t('general.select_one')"
								label="label"
								track-by="name"
							/>
						</MultiselectInputGroup>
					</div>
					<div class="col-span-4 lg:col-span-1 lg:col-start-1">
						<BaseInputGroup
							id="name"
							:label="$t('user.name')"
							type="text"
							:required="true"
							v-model="user.name"
							:validation-errors="validationErrors.name"
						/>
					</div>
					<div class="col-span-4 lg:col-span-1 lg:col-start-1">
						<BaseInputGroup
							id="surname"
							:label="$t('user.surname')"
							type="text"
							:required="true"
							v-model="user.surname"
							:validation-errors="validationErrors.surname"
						/>
					</div>
					<div class="col-span-4 lg:col-span-1 lg:col-start-1">
						<BaseInputGroup
							id="identification_number"
							:label="$t('user.identification_number')"
							type="text"
							:required="true"
							v-model="user.identification_number"
							:validation-errors="validationErrors.identification_number"
						/>
					</div>
					<div class="col-span-4 lg:col-span-1 lg:col-start-1">
						<BaseInputGroup
							id="email"
							:label="$t('user.email')"
							type="email"
							:required="true"
							v-model="user.email"
							:validation-errors="validationErrors.email"
						/>
					</div>
					<div class="col-span-4 lg:col-span-1 lg:col-start-1">
						<BasePassword
							id="password"
							:label="$t('user.password')"
							:placeholder="
								$route.name === 'UsersEdit'
									? $t('user.empty_password_placeholder')
									: ''
							"
							:required="$route.name === 'UsersCreate'"
							v-model="user.password"
							:validation-errors="validationErrors.password"
						/>
					</div>
					<div class="col-span-4 lg:col-span-1 lg:col-start-1">
						<BaseCheckbox
							id="active"
							:label="$t('user.active')"
							v-model="user.active"
						/>
					</div>

					<div
						class="col-span-4 mt-1 text-2xl font-bold text-red-400"
						v-if="validationErrors.student"
					>
						{{ $t('validation.student') }}
					</div>

					<template v-if="isUserStudent">
						<div class="col-span-4 mt-4 border-t pt-4 lg:col-span-1 lg:col-start-1">
							<h1 class="text-2xl font-bold">
								{{ $t('user.student_fields_of_study') }}
							</h1>
							<button class="normal_btn" @click.prevent="addFieldOfStudy">
								{{ $t('user.add_field_of_study') }}
							</button>
						</div>
						<template v-for="(student_info, index) in student">
							<div class="col-span-4">
								<hr />
							</div>
							<div class="col-span-4 lg:col-span-1 lg:col-start-1">
								<BaseInputGroup
									:id="`field_of_study${index}`"
									:label="$t('user.field_of_study')"
									type="text"
									:required="true"
									v-model="student_info.field_of_study"
									:validation-errors="
										validationErrors['student.' + index + '.field_of_study']
									"
								/>
							</div>
							<div class="col-span-4 lg:col-span-1">
								<BaseInputGroup
									:id="`semester${index}`"
									:label="$t('user.semester')"
									type="number"
									:required="true"
									v-model="student_info.semester"
									:validation-errors="
										validationErrors['student.' + index + '.semester']
									"
								/>
							</div>
							<div class="col-span-4 lg:col-span-1">
								<BaseInputGroup
									:id="`year_of_study${index}`"
									:label="$t('user.year_of_study')"
									type="text"
									:required="true"
									v-model="student_info.year_of_study"
									:validation-errors="
										validationErrors['student.' + index + '.year_of_study']
									"
								/>
							</div>
							<div class="col-span-4 lg:col-span-1">
								<BaseInputGroup
									:id="`mode_of_study${index}`"
									:label="$t('user.mode_of_study')"
									type="text"
									:required="true"
									v-model="student_info.mode_of_study"
									:validation-errors="
										validationErrors['student.' + index + '.mode_of_study']
									"
								/>
							</div>
							<div class="col-span-4 lg:col-span-1 lg:col-start-1">
								<button
									class="delete_btn"
									@click.prevent="removeFieldOfStudy(index)"
								>
									{{ $t('user.remove_field_of_study') }}
								</button>
							</div>
						</template>
					</template>

					<template v-if="isUserTeacher">
						<div class="col-span-4 lg:col-span-1 lg:col-start-1">
							<BaseInputGroup
								id="scien_degree"
								:label="$t('user.scientific_degree')"
								type="text"
								:required="true"
								v-model="teacher.scien_degree"
								:validation-errors="validationErrors['teacher.scien_degree']"
							/>
						</div>
						<div class="col-span-4 lg:col-span-1 lg:col-start-1">
							<BaseInputGroup
								id="business_email"
								:label="$t('user.contact_email')"
								type="email"
								:required="true"
								v-model="teacher.business_email"
								:validation-errors="validationErrors['teacher.business_email']"
							/>
						</div>
						<div class="col-span-4 lg:col-span-1 lg:col-start-1">
							<BaseInputGroup
								id="contact_number"
								:label="$t('user.contact_number')"
								type="text"
								v-model="teacher.contact_number"
                                :validation-errors="validationErrors['teacher.contact_number']"
							/>
						</div>
						<div class="col-span-4 lg:col-span-1 lg:col-start-1">
							<BaseInputGroup
								id="room"
								:label="$t('user.room')"
								type="text"
								v-model="teacher.room"
								:validation-errors="validationErrors['teacher.room']"
							/>
						</div>
						<div class="col-span-4 lg:col-span-1 lg:col-start-1">
							<BaseInputGroup
								id="consultation_hours"
								:label="$t('user.consultation_hours')"
								type="text"
								v-model="teacher.consultation_hours"
								:validation-errors="validationErrors['teacher.consultation_hours']"
							/>
						</div>
					</template>
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
import ButtonSubmit from '@/components/ButtonSubmit.vue';
import {mapActions, mapState, mapMutations, mapGetters} from 'vuex';
import BasePassword from '@/components/BasePassword.vue';
import BaseCheckbox from '@/components/BaseCheckbox.vue';
import Multiselect from 'vue-multiselect';
import router from '@/router';
import { useToast } from 'vue-toastification';
import MultiselectInputGroup from '@/components/MultiselectInputGroup.vue';

export default {
	name: 'UserForm',
	components: {
		MultiselectInputGroup,
		BaseCheckbox,
		BasePassword,
		ButtonSubmit,
		BaseInputGroup,
		LoadingScreen,
		Multiselect
	},

	data() {
		return {
			account_roles: [
				{
					name: 'student',
					label: this.$t('user.student')
				},
				{
					name: 'teacher',
					label: this.$t('user.teacher')
				},
				{
					name: 'admin',
					label: this.$t('user.admin')
				}
			]
		};
	},

	computed: {
		...mapState('user', ['loading', 'user', 'student', 'teacher', 'validationErrors']),
        ...mapGetters('user', ['isUserStudent', 'isUserTeacher'])
	},

	methods: {
		...mapActions('user', ['getUserDetails', 'createNewUserOrEdit']),
		...mapMutations('user', ['resetUser']),

		addFieldOfStudy() {
			this.student.push({
				field_of_study: '',
				semester: '',
				year_of_study: '',
				mode_of_study: ''
			});
		},

		removeFieldOfStudy(index) {
			this.student.splice(index, 1);
		},

		handleSubmit() {
			const toast = useToast();

			this.createNewUserOrEdit({
				user: this.user,
				student: this.student,
				teacher: this.teacher
			})
				.then((response) => {
					toast.success(this.$t('general.saved_successfully'));
					router.push({
						name: 'UsersView',
						params: { id: response.data.user_ID }
					});
				})
				.catch((error) => {
					toast.error(this.$t('general.validation_errors'));
					throw error;
				});
		}
	},

	created() {
		if (this.$route.name === 'UsersEdit') {
			this.getUserDetails(this.$route.params.id).then(() => {
				this.user.account_role.label = this.$t('user.' + this.user.account_role.name);
			});
		} else {
			this.resetUser();
		}
	}
};
</script>
