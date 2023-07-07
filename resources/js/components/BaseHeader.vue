<template>
	<header class="mb-8 text-right text-2xl font-bold"></header>
</template>

<script>
import { mapState } from 'vuex';
import dayjs from 'dayjs';

export default {
	name: 'BaseHeader',

	computed: {
		...mapState('login', ['user'])
	}
};
</script>
