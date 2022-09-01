<template>
    <button class="font-bold text-2xl transition ease-in-out"
            :class="[ (active) ? 'border-b-4 rounded border-indigo-600' : 'text-gray-500 hover:text-gray-700' ]">
        <slot />
    </button>
</template>

<script>
export default {
    name: "Tab",

    props: {
        active: {
            type: Boolean,
            default: false
        },
    }
}
</script>
