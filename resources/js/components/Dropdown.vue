<template>
	<div class="relative text-center">
		<div
			class="absolute right-0 z-50 mt-2 w-full min-w-max cursor-auto rounded-md bg-white px-4 py-2 shadow-lg lg:w-fit"
		>
			<slot />
		</div>
	</div>
</template>

<script>
export default {
	name: 'Dropdown'
};
</script>
