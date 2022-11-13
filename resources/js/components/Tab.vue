<template>
    <button
        class="text-2xl font-bold transition ease-in-out"
        :class="[
            active
                ? 'rounded border-b-4 border-indigo-600'
                : 'text-gray-500 hover:text-gray-700',
        ]"
    >
        <slot />
    </button>
</template>

<script>
export default {
    name: "Tab",

    props: {
        active: {
            type: Boolean,
            default: false,
        },
    },
};
</script>
