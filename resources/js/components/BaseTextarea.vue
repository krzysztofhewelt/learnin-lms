<template>
    <label v-show="label" :for="id" class="text-sm font-medium text-gray-700">{{ label }}</label>
    <span v-if="required" class="text-red-600 font-bold ml-px">*</span>
    <div class="shadow-sm">
        <textarea
            class="transition ease-in-out focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md h-24 max-h-96"
            :required="required"
            :id="id"
            @input="$emit('update:modelValue', $event.target.value)"
        >{{ modelValue }}</textarea>
    </div>
    <span class="block text-red-400 font-bold mt-1" v-for="error in validationErrors">
        {{ error }}
    </span>
</template>

<script>
export default {
    name: "BaseTextarea",

    props: {
        required: {
            type: Boolean,
            default: false
        },

        validationErrors: {
            type: Array
        },

        id: {
            type: String,
            default: ''
        },

        label: {
            type: String,
            default: ''
        },

        modelValue: {
            type: String,
            default: ''
        }
    }
}
</script>
