<template>
    <div class="mb-2">
        <label :for="id" class="block text-sm font-medium text-gray-700">{{ label }}</label>
        <div class="mt-1 relative rounded-md shadow-sm mb-1">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <span class="text-gray-500 sm:text-sm">
                    <slot />
                </span>
            </div>
            <input class="transition ease-in-out focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-11 sm:text-sm border-gray-300 rounded-md"
                   :id="id"
                   :type="type"
                   :placeholder="placeholder"
                   :value="modelValue"
                   :required="required"
                   @input="$emit('update:modelValue', $event.target.value)">
        </div>
        <span class="block text-red-400 font-bold" v-for="error in validationErrors">
            {{ error }}
        </span>
    </div>
</template>

<script>
export default {
    name: "InputWithIconGroup",

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
