<template>
    <div class="relative">
        <div class="absolute z-50 mt-2 bg-white rounded-md shadow-lg min-w-max py-2 px-4 right-0 w-fit cursor-auto">
            <slot />
        </div>
    </div>
</template>

<script>
export default {
    name: "Dropdown"
}
</script>
