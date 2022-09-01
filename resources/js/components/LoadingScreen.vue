<template>
    <div class="absolute flex bg-white opacity-90 w-full h-full z-10">
        <div class="mx-auto my-auto">
            <div class="text-center my-auto font-bold text-xl">
                <Loading class="w-12 h-12 animate-spin inline mr-1" />
            </div>
            <div class="mt-3 font-bold text-zinc-600 text-xl">
                {{ $t('general.loading') }}
            </div>
        </div>
    </div>
</template>

<script>
import Loading from "./Icons/Loading"

export default {
    name: "LoadingScreen",
    components: {Loading}
}
</script>
