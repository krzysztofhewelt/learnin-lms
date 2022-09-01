<template>
    <div class="relative rounded-md shadow-sm">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
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
               @input="$emit('update:modelValue', $event.target.value)" />

        <span class="block text-red-400 font-bold mt-1" v-for="error in validationErrors">
            {{ error }}
        </span>
    </div>
</template>

<script>
import BaseInput from "./BaseInput"

export default {
    name: "InputWithIcon",
    components: {BaseInput},
    props: {
        type: {
            type: String,
            default: "text"
        },

        validationErrors: {
            type: Array
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
