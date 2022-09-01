<template>
    <header class="text-right font-bold text-2xl mb-8">
    </header>
</template>

<script>
import {mapState} from "vuex"
import dayjs from "dayjs"

export default {
    name: "BaseHeader",

    computed: {
        ...mapState('login', ['user'])
    }
}
</script>
