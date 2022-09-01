<template>
    <input type="checkbox"
           class="mr-1 w-5 h-5 rounded-md focus:ring-blue-300 focus:ring-4 focus:ring-offset-0"
           :id="id"
           :required="required"
           :checked="modelValue"
           @change="$emit('update:modelValue', $event.target.checked)"
    >
    <label class="inline-block text-gray-800 align-middle" :for="id">{{ label }}</label>
    <span v-if="required" class="text-red-600 font-bold ml-px">*</span>
</template>

<script>
export default {
    name: "BaseCheckbox",

    props: {
        id: {
            type: String,
            required: true
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
            type: Number
        }
    },
}
</script>
