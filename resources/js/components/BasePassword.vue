<template>
    <div class="mb-3">
        <label :for="id" class="text-sm font-medium text-gray-700">{{ label }}</label>
        <span v-if="required" class="text-red-600 font-bold ml-px">*</span>
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
                @input="$emit('update:modelValue', $event.target.value)" />
            <BaseInput
                class="pr-10"
                autocomplete="off"
                :id="id"
                v-else
                type="text"
                :value="modelValue"
                :placeholder="placeholder"
                :required="required"
                @input="$emit('update:modelValue', $event.target.value)" />
            <div class="absolute cursor-pointer -mt-7 right-0 mr-2" @click="password = !password">
                <HidePassword v-if="!password" class="w-5 h-5 " />
                <ShowPassword v-else class="w-5 h-5" />
            </div>
        </div>
        <span class="block text-red-400 font-bold mt-1" v-for="error in validationErrors">
            {{ error }}
        </span>
    </div>
</template>

<script>
import BaseInput from "./BaseInput"
import HidePassword from "./Icons/HidePassword"
import ShowPassword from "./Icons/ShowPassword"

export default {
    name: "BasePassword",
    components: {ShowPassword, HidePassword, BaseInput},
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
            default: "",
        },
    },

    data() {
        return {
            password: true
        }
    }
}
</script>
