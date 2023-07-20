<template>
	<Popper v-bind="$attrs" class="cursor-pointer" content="&nbsp;" z-index="1">
		<slot />
		<template #content="props">
			<slot name="content" v-bind="props" />
		</template>
	</Popper>
</template>

<script>
import Popper from 'vue3-popper';

export default {
	name: 'Dropdown',

	components: {
		Popper
	}
};
</script>
