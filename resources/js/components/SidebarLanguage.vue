<template>
	<div class="cursor-pointer py-3 px-3">
		<div
			@click="dropdownOpen = !dropdownOpen"
			class="font-bold text-gray-400 transition-all duration-300 hover:text-purple-600"
		>
			<img class="inline h-4 w-7" :alt="locale" />
			<span class="ml-1.5">{{ $t('languages.' + locale) }}</span>
		</div>

		<div class="relative" v-if="dropdownOpen">
			<div
				class="absolute left-0 bottom-full z-50 mb-7 w-fit min-w-max rounded-md bg-white py-2 px-4 shadow-lg"
			>
				<button
					class="block py-2 text-gray-400 transition-all duration-300 hover:text-purple-600"
					@click="changeLanguage('en')"
				>
					<img src="../assets/en.png" class="inline h-4 w-6" alt="english" />
					{{ $t('languages.en') }}
				</button>
				<button
					class="block py-2 text-gray-400 transition-all duration-300 hover:text-purple-600"
					@click="changeLanguage('pl')"
				>
					<img src="../assets/pl.png" class="inline h-4 w-6" alt="polski" />
					{{ $t('languages.pl') }}
				</button>
			</div>
		</div>
	</div>
</template>

<script>
import { loadLanguageAsync } from 'laravel-vue-i18n';
import { mapActions, mapState } from 'vuex';

export default {
	name: 'SidebarLanguage',

	data() {
		return {
			dropdownOpen: false
		};
	},

	computed: {
		...mapState('locale', ['locale'])
	},

	methods: {
		...mapActions('locale', ['setLocale']),

		changeLanguage(lang) {
			this.dropdownOpen = false;
			loadLanguageAsync(lang);
			this.setLocale(lang);
		}
	}
};
</script>
