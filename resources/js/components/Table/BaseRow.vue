<template>
    <td
        class="border-b-2 border-gray-400 bg-white p-0 lg:whitespace-nowrap lg:border-b lg:border-gray-200 lg:px-6 lg:py-3"
    >
        <slot />
    </td>
</template>

<script>
export default {
    name: "BaseRow",
};
</script>
