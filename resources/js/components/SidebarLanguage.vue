<template>
	<div class="cursor-pointer px-3 py-3">
		<Dropdown>
			<div class="font-bold text-gray-400 transition-all duration-300 hover:text-purple-600">
				<template v-if="!loading">
					<img :src="getCurrentLanguageImage" class="inline h-4 w-7" :alt="locale" />
					<span class="ml-1.5">{{ $t('languages.' + locale) }}</span>
				</template>
				<template v-else> <Loading class="h-5 w-5" /> </template>
			</div>

			<template #content="{ close }" v-if="!loading">
				<button
					class="block py-2 text-gray-400 transition-all duration-300 hover:text-purple-600"
					@click="changeLanguage('en', close)"
				>
					<img src="../assets/en.png" class="inline h-4 w-6" alt="english" />
					{{ $t('languages.en') }}
				</button>
				<button
					class="block py-2 text-gray-400 transition-all duration-300 hover:text-purple-600"
					@click="changeLanguage('pl', close)"
				>
					<img src="../assets/pl.png" class="inline h-4 w-6" alt="polski" />
					{{ $t('languages.pl') }}
				</button>
			</template>
		</Dropdown>
	</div>
</template>

<script>
import { loadLanguageAsync } from 'laravel-vue-i18n';
import { mapActions, mapState } from 'vuex';
import SidebarLink from '@/components/SidebarLink.vue';
import Popper from 'vue3-popper';
import Dropdown from '@/components/Dropdown.vue';
import Loading from '@/components/Icons/Loading.vue';

export default {
	name: 'SidebarLanguage',
	components: { Loading, Dropdown, SidebarLink, Popper },

	computed: {
		...mapState('locale', ['locale', 'loading']),

		getCurrentLanguageImage() {
			return new URL(`../assets/${this.locale}.png`, import.meta.url).href;
		}
	},

	methods: {
		...mapActions('locale', ['setLocale']),

		changeLanguage(lang, close) {
			close();

			loadLanguageAsync(lang);
			this.setLocale(lang);
		}
	}
};
</script>
