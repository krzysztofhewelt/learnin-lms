<template>
	<div
		class="flex min-h-screen flex-col items-center bg-gradient-to-r from-rose-100 to-teal-100 pt-6 sm:justify-center sm:pt-0"
	>
		<div class="relative w-full overflow-hidden bg-white px-6 py-4 shadow-md sm:max-w-md sm:rounded-lg">
			<SidebarLanguage class="absolute right-0 top-0 bg-zinc-100 rounded-bl-lg shadow-md text-black" />

			<AppLogo class="mx-auto w-9/12 pb-4"></AppLogo>

			<span class="mb-3 block text-center text-xl font-bold text-red-400">
				{{ validationErrors.account }}
			</span>

			<form @submit.prevent="logIn">
				<!-- email Address -->
				<InputWithIconGroup
					id="email"
					:label="$t('auth.email')"
					v-model="email"
					:placeholder="$t('auth.type_email')"
					:required="true"
					:validation-errors="validationErrors.email"
				>
					<SimpleUser class="h-5 w-5" />
				</InputWithIconGroup>

				<!-- password -->
				<InputWithIconGroup
					type="password"
					id="password"
					:label="$t('auth.password')"
					v-model="password"
					:placeholder="$t('auth.type_password')"
					:required="true"
					:validation-errors="validationErrors.password"
				>
					<Password class="h-5 w-5" />
				</InputWithIconGroup>

				<div class="mt-4 flex items-center justify-end">
					<button
						type="submit"
						class="w-screen items-center rounded-3xl border border-transparent bg-gradient-to-r from-purple-400 via-pink-500 to-red-500 px-4 py-2 text-center text-xs font-semibold uppercase tracking-widest text-white ring-indigo-500 transition duration-150 ease-in-out focus:border-purple-900 focus:outline-none focus:ring disabled:opacity-25"
						:disabled="loading"
					>
						{{ $t('auth.sign_in') }}
					</button>
				</div>
			</form>
		</div>
	</div>
</template>

<script>
import InputWithIconGroup from '@/components/BaseInputWithIconGroup.vue';
import AppLogo from '@/components/AppLogo.vue';
import Password from '@/components/Icons/Password.vue';
import { mapActions, mapState } from 'vuex';
import SimpleUser from '@/components/Icons/SimpleUser.vue';
import router from '@/router';
import SidebarLanguage from '@/components/SidebarLanguage.vue';

export default {
	name: 'LoginForm',
	components: { SidebarLanguage, SimpleUser, Password, AppLogo, InputWithIconGroup },

	data() {
		return {
			email: '',
			password: ''
		};
	},

	computed: {
		...mapState('login', ['loading', 'validationErrors'])
	},

	created() {
		this.logout();
	},

	methods: {
		...mapActions('login', ['logout', 'login']),

		logIn() {
			this.login({ email: this.email, password: this.password }).then(() => {
				router.push('/');
			});
		}
	}
};
</script>
