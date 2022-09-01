<template>
    <span v-if="isActive" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 hover:bg-green-300">
        {{ $t('user.active') }}
    </span>
    <span v-else class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 hover:bg-red-300">
        {{ $t('user.not_active') }}
    </span>
</template>

<script>
export default {
    name: "ActiveBadge",

    props: {
        value: {
            type: Number,
            required: true
        }
    },

    computed: {
        isActive() {
            return this.value === 1
        }
    }
}
</script>
