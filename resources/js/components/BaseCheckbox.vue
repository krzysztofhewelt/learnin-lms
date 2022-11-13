<template>
    <input
        type="checkbox"
        class="mr-1 h-5 w-5 rounded-md focus:ring-4 focus:ring-blue-300 focus:ring-offset-0"
        :id="id"
        :required="required"
        :checked="modelValue"
        @change="$emit('update:modelValue', $event.target.checked)"
    />
    <label class="inline-block align-middle text-gray-800" :for="id">{{
        label
    }}</label>
    <span v-if="required" class="ml-px font-bold text-red-600">*</span>
</template>

<script>
export default {
    name: "BaseCheckbox",

    props: {
        id: {
            type: String,
            required: true,
        },

        label: {
            type: String,
            required: true,
        },

        required: {
            type: Boolean,
            default: false,
        },

        modelValue: {
            type: Number,
        },
    },
};
</script>
