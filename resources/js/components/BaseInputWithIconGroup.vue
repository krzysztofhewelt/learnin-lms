<template>
	<div class="mb-2">
		<label :for="id" class="block text-sm font-medium text-gray-700">{{ label }}</label>
		<div class="relative mb-1 mt-1 rounded-md shadow-sm">
			<div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
				<span class="text-gray-500 sm:text-sm">
					<slot />
				</span>
			</div>
			<input
				class="block w-full rounded-md border-gray-300 pl-11 transition ease-in-out focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
				:id="id"
				:type="type"
				:placeholder="placeholder"
				:value="modelValue"
				:required="required"
				@input="$emit('update:modelValue', $event.target.value)"
			/>
		</div>
		<span class="block font-bold text-red-400" v-for="error in validationErrors">
			{{ error }}
		</span>
	</div>
</template>

<script>
export default {
	name: 'InputWithIconGroup',

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
