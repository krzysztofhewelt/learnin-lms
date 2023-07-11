<template>
	<div class="mb-3">
		<label :for="id" class="text-sm font-medium text-gray-700">{{ label }}</label>
		<span v-if="required" class="ml-px font-bold text-red-600">*</span>
		<div class="relative rounded-md shadow-sm">
			<BaseInput
				class="pr-10"
				autocomplete="off"
				:id="id"
				v-if="password"
				type="password"
				:value="modelValue"
				:required="required"
				:placeholder="placeholder"
				:errors="validationErrors"
				@input="$emit('update:modelValue', $event.target.value)"
                :class="{ 'border-red-600': validationErrors }"
			/>
			<BaseInput
				class="pr-10"
				autocomplete="off"
				:id="id"
				v-else
				type="text"
				:value="modelValue"
				:placeholder="placeholder"
				:required="required"
				@input="$emit('update:modelValue', $event.target.value)"
                :class="{ 'border-red-600': validationErrors }"
			/>
			<div class="absolute right-0 -mt-7 mr-2 cursor-pointer" @click="password = !password">
				<HidePassword v-if="!password" class="h-5 w-5" />
				<ShowPassword v-else class="h-5 w-5" />
			</div>
		</div>
		<span class="mt-1 block text-sm font-bold text-red-400" v-for="error in validationErrors">
			{{ error }}
		</span>
	</div>
</template>

<script>
import BaseInput from '@/components/BaseInput.vue';
import HidePassword from '@/components/Icons/HidePassword.vue';
import ShowPassword from '@/components/Icons/ShowPassword.vue';

export default {
	name: 'BasePassword',
	components: { ShowPassword, HidePassword, BaseInput },
	props: {
		id: {
			type: String,
			required: true
		},

		validationErrors: {
			type: Array
		},

		placeholder: {
			type: String,
			default: ''
		},

		label: {
			type: String,
			required: true
		},

		required: {
			type: Boolean,
			default: false
		},

		modelValue: {
			type: String,
			default: ''
		}
	},

	data() {
		return {
			password: true
		};
	}
};
</script>
