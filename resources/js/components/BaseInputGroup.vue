<template>
	<label :for="id" class="text-sm font-medium text-gray-700">{{ label }}</label>
	<span v-if="required" class="ml-px font-bold text-red-600">*</span>
	<div class="relative">
		<BaseInput
			:id="id"
			:type="type"
			:placeholder="placeholder"
			:value="modelValue"
			:required="required"
			@input="$emit('update:modelValue', $event.target.value)"
            :class="{ 'border-red-600': validationErrors }"
		/>
	</div>
	<span class="mt-1 block text-sm font-bold text-red-400" v-for="error in validationErrors">
		{{ error }}
	</span>
</template>

<script>
import BaseInput from '@/components/BaseInput.vue';

export default {
	name: 'BaseInputGroup',
	components: { BaseInput },
	props: {
		id: {
			type: String,
			required: true
		},

		validationErrors: {
			type: Array
		},

		label: {
			type: String,
			required: true
		},

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
