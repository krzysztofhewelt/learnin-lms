<template>
	<label v-show="label" :for="id" class="text-sm font-medium text-gray-700">{{ label }}</label>
	<span v-if="required" class="ml-px font-bold text-red-600">*</span>
	<div class="shadow-sm">
		<textarea
			class="block h-24 max-h-96 w-full rounded-md border-gray-300 transition ease-in-out focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
			:required="required"
			:id="id"
			@input="$emit('update:modelValue', $event.target.value)"
			>{{ modelValue }}</textarea
		>
	</div>
	<span class="mt-1 block font-bold text-red-400" v-for="error in validationErrors">
		{{ error }}
	</span>
</template>

<script>
export default {
	name: 'BaseTextarea',

	props: {
		required: {
			type: Boolean,
			default: false
		},

		validationErrors: {
			type: Array
		},

		id: {
			type: String,
			default: ''
		},

		label: {
			type: String,
			default: ''
		},

		modelValue: {
			type: String,
			default: ''
		}
	}
};
</script>
