<template>
    <router-link
        class="block py-3 px-3 no-underline transition-all duration-300"
        :class="[
            $route.matched.some(({ name }) => name === routeName)
                ? 'border-r-2 border-purple-800 font-bold text-purple-800 hover:border-purple-500 hover:text-purple-500'
                : 'text-gray-400 hover:text-purple-600',
        ]"
        :to="{ name: routeName }"
    >
        <div class="inline h-7 w-7 rounded-full p-1">
            <slot />
            {{ name }}
        </div>
    </router-link>
</template>

<script>
export default {
    name: "SidebarLink",

    props: {
        name: {
            type: String,
            required: true,
        },

        routeName: {
            type: String,
            required: true,
        },

        routeParent: {
            type: String,
            required: true,
        },
    },
};
</script>
