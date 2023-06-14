<template>
	<div class="relative">
		<div
			class="absolute right-0 z-50 mt-2 w-fit min-w-max cursor-auto rounded-md bg-white px-4 py-2 shadow-lg"
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
