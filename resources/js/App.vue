<template>
    <div class="relative min-h-screen max-w-screen md:flex bg-bgmain">
        <template v-if="this.$route.name !== 'Login' && this.$route.name !== 'Error404'">
            <TheSidebar />
        </template>
        <div class="w-full overflow-auto"
             :class="{ 'p-14' : this.$route.name !== 'Login' }">
            <router-view :key="$route.fullPath"></router-view>
        </div>
    </div>
</template>
<script>
import TheSidebar from "./components/TheSidebar"
import BaseHeader from "./components/BaseHeader"

export default {
    components: {BaseHeader, TheSidebar}
}
</script>
