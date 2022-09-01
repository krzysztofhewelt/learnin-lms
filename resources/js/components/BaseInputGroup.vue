<template>
    <label :for="id" class="text-sm font-medium text-gray-700">{{ label }}</label>
    <span v-if="required" class="text-red-600 font-bold ml-px">*</span>
    <div class="relative rounded-md shadow-sm">
        <BaseInput
            :id="id"
            :type="type"
            :placeholder="placeholder"
            :value="modelValue"
            :required="required"
            @input="$emit('update:modelValue', $event.target.value)" />
    </div>
    <span class="block text-red-400 font-bold mt-1" v-for="error in validationErrors">
        {{ error }}
    </span>
</template>

<script>
import BaseInput from "./BaseInput"

export default {
    name: "BaseInputGroup",
    components: {BaseInput},
    props: {
        id: {
            type: String,
            required: true
        },

        validationErrors: {
            type: Array,
        },

        label: {
            type: String,
            required: true
        },

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
