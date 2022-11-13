<template>
	<div class="absolute z-10 flex h-full w-full bg-white opacity-90">
		<div class="mx-auto my-auto">
			<div class="my-auto text-center text-xl font-bold">
				<Loading class="mr-1 inline h-12 w-12 animate-spin" />
			</div>
			<div class="mt-3 text-xl font-bold text-zinc-600">
				{{ $t('general.loading') }}
			</div>
		</div>
	</div>
</template>

<script>
import Loading from '@/components/Icons/Loading.vue';

export default {
	name: 'LoadingScreen',
	components: { Loading }
};
</script>
