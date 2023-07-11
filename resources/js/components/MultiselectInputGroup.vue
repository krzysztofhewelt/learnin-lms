<template>
	<div :class="{ 'invalid': validationErrors }">
		<label class="text-sm font-medium text-gray-700" :for="id">{{ label }}</label>
		<span v-if="required" class="ml-px font-bold text-red-600">*</span>
		<slot />
		<span class="mt-1 block text-sm font-bold text-red-400" v-for="error in validationErrors">
			{{ error }}
		</span>
	</div>
</template>

<script>
export default {
	name: 'MultiselectInputGroup',

	props: {
		validationErrors: {
			type: Array
		},

		label: {
			type: String,
			required: true
		},

		required: {
			type: Boolean,
			default: false
		},

        id: {
            type: String,
            required: true
        }
	}
};
</script>
