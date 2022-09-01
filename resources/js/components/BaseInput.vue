<template>
    <input class="transition ease-in-out focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
           :type="type"
           :placeholder="placeholder"
           :value="modelValue"
           :required="required"
           @input="$emit('update:modelValue', $event.target.value)">
</template>

<script>
export default {
    name: "BaseInput",

    props: {
        type: {
            type: String,
            default: "text"
        },

        placeholder: {
            type: String,
            default: ""
        },

        required: {
            type: Boolean,
            default: false
        },

        modelValue: {
            type: [String, Number],
            default: "",
        },
    }
}
</script>
