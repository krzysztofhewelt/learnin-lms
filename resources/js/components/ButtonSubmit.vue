<template>
    <input type="submit" :value="$t('general.save')" :disabled="loading" :class="[ loading ? 'submit_btn_disabled' : 'submit_btn']">
</template>

<script>
export default {
    name: "ButtonSubmit",

    props: {
        loading: {
            type: Boolean,
            default: false
        }
    }
}
</script>
