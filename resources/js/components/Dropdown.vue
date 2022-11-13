<template>
    <div class="relative">
        <div
            class="absolute right-0 z-50 mt-2 w-fit min-w-max cursor-auto rounded-md bg-white py-2 px-4 shadow-lg"
        >
            <slot />
        </div>
    </div>
</template>

<script>
export default {
    name: "Dropdown",
};
</script>
