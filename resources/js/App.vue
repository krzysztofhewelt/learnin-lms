<template>
	<div class="max-w-screen relative min-h-screen bg-bgmain md:flex">
		<template v-if="this.$route.name !== 'Login' && this.$route.name !== 'Error404'">
			<TheSidebar />
		</template>
		<div class="w-full overflow-auto" :class="{ 'p-6 lg:p-14': this.$route.name !== 'Login' }">
			<router-view :key="$route.fullPath"></router-view>
		</div>
	</div>
</template>
<script>
import TheSidebar from '@/components/TheSidebar.vue';
import BaseHeader from '@/components/BaseHeader.vue';

export default {
	components: { BaseHeader, TheSidebar }
};
</script>
