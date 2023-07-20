<template>
	<div class="mb-2">
		<label :for="id" class="block text-sm font-medium text-gray-700">{{ label }}</label>
		<div class="relative mb-1 mt-1 rounded-md shadow-sm">
			<div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
				<span class="text-gray-500 sm:text-sm">
					<slot />
				</span>
			</div>
			<BaseInput
				:id="id"
				:type="type"
				:placeholder="placeholder"
				:value="modelValue"
				:required="required"
				@input="$emit('update:modelValue', $event.target.value)"
				class="pl-10"
				:class="{ 'border-red-600': validationErrors }"
			/>
		</div>
		<span class="block text-sm font-bold text-red-400" v-for="error in validationErrors">
			{{ error }}
		</span>
	</div>
</template>

<script>
import BaseInput from '@/components/BaseInput.vue';

export default {
	name: 'BaseInputWithIconGroup',
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
