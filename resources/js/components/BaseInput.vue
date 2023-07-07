<template>
	<input
		class="w-full rounded-md border-gray-300 transition ease-in-out focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
		:type="type"
		:placeholder="placeholder"
		:value="modelValue"
		:required="required"
		@input="$emit('update:modelValue', $event.target.value)"
	/>
</template>

<script>
export default {
	name: 'BaseInput',

    props: {
		type: {
			type: String,
			default: 'text'
		},

		placeholder: {
			type: String,
			default: ''
		},

		required: {
			type: Boolean,
			default: false
		},

		modelValue: {
			type: [String, Number],
			default: ''
		}
	}
};
</script>
