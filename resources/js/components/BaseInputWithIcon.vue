<template>
	<div class="relative rounded-md shadow-sm">
		<div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
			<span class="text-gray-500 sm:text-sm">
				<slot />
			</span>
		</div>
		<BaseInput
			class="pl-10"
			:type="type"
			:placeholder="placeholder"
			:value="modelValue"
			:required="required"
			@input="$emit('update:modelValue', $event.target.value)"
		/>
	</div>
</template>

<script>
import BaseInput from '@/components/BaseInput.vue';

export default {
	name: 'BaseInputWithIcon',
	components: { BaseInput },
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
