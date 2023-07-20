<template>
	<div class="relative flex h-fit w-full flex-col break-words rounded-lg bg-white shadow-xl">
		<LoadingScreen v-if="loading" />
		<form @submit.prevent="handleSubmit">
			<div class="p-6">
				<h1 class="my-auto mb-4 block text-xl font-bold">
					{{ $t('passwords.change_password') }}
				</h1>

				<BasePassword
					:label="$t('passwords.old_password')"
					required="required"
					id="old_password"
					v-model="oldPassword"
					:validation-errors="errors.current_password"
				/>

				<BasePassword
					:label="$t('passwords.new_password')"
					required="required"
					id="new_password"
					v-model="newPassword"
					:validation-errors="errors.new_password"
				/>
			</div>
			<div class="rounded-b-lg bg-gray-100 px-4 py-3 text-right">
				<ButtonSubmit :loading="loading" />
			</div>
		</form>
	</div>
</template>

<script>
import BaseInputGroup from '@/components/BaseInputGroup.vue';
import BasePassword from '@/components/BasePassword.vue';
import { useToast } from 'vue-toastification';
import ButtonSubmit from '@/components/ButtonSubmit.vue';
import router from '@/router';
import LoadingScreen from '@/components/LoadingScreen.vue';

export default {
	name: 'ChangePassword',
	components: { LoadingScreen, ButtonSubmit, BasePassword, BaseInputGroup },

	data() {
		return {
			loading: false,
			toast: useToast(),
			oldPassword: '',
			newPassword: '',
			errors: {}
		};
	},

	methods: {
		async handleSubmit() {
			this.loading = true;
			this.errors = {};

			await axios
				.post('/change-password', {
					current_password: this.oldPassword,
					new_password: this.newPassword
				})
				.then(() => {
					this.errors = {};
					this.toast.success(this.$t('passwords.password_changed'));
					this.loading = false;
					router.push({ name: 'ProfileUser' });
				})
				.catch((error) => {
					if (error.response.status === 422) this.errors = error.response.data.errors;

					this.toast.error(this.$t('general.validation_errors'));

					this.loading = false;
					throw error;
				});
		}
	}
};
</script>
