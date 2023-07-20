<template>
	<input
		type="submit"
		:value="value || $t('general.save')"
		:disabled="loading"
		class="submit_btn"
	/>
</template>

<script>
export default {
	name: 'ButtonSubmit',

	props: {
		loading: {
			type: Boolean,
			default: false
		},

		value: {
			type: String,
			default: ''
		}
	}
};
</script>
