import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import i18n from 'laravel-vue-i18n/vite';

export default defineConfig({
    envDir: './.env',
	plugins: [
		laravel({
			input: ['resources/css/app.css', 'resources/js/main.js'],
			refresh: true
		}),
		vue({
			template: {
				transformAssetUrls: {
					base: null,
					includeAbsolute: false
				}
			}
		}),
		i18n()
	],
	resolve: {
		alias: {
			'@': '/resources/js'
		}
	}
});
