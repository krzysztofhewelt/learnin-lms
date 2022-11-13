<template>
    <span
        v-if="isActive"
        class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800 hover:bg-green-300"
    >
        {{ $t("user.active") }}
    </span>
    <span
        v-else
        class="inline-flex rounded-full bg-red-100 px-2 text-xs font-semibold leading-5 text-red-800 hover:bg-red-300"
    >
        {{ $t("user.not_active") }}
    </span>
</template>

<script>
export default {
    name: "ActiveBadge",

    props: {
        value: {
            type: Number,
            required: true,
        },
    },

    computed: {
        isActive() {
            return this.value === 1;
        },
    },
};
</script>
