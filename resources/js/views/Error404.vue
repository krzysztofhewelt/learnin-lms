<template>
	<section class="flex h-full items-center p-16 dark:bg-gray-900 dark:text-gray-100">
		<div
			class="container mx-auto my-8 flex flex-col items-center justify-center space-y-5 px-5 text-center"
		>
			<h2 class="mb-3 text-9xl font-extrabold dark:text-gray-600">404</h2>
			<p class="text-2xl font-semibold md:text-3xl">
				{{ $t('404.not_found_header') }}
			</p>
			<router-link :to="{ name: 'Dashboard' }" class="submit_btn">{{
				$t('404.back_to_homepage')
			}}</router-link>
		</div>
	</section>
</template>

<script>
export default {
	name: 'Error404'
};
</script>
