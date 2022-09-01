<template>
    <div class="py-3 px-3 cursor-pointer">
        <div @click="dropdownOpen = !dropdownOpen" class="transition-all duration-300 text-gray-400 font-bold hover:text-purple-600">
            <img :src='require(`../assets/${locale}.png`).default' class="inline w-7 h-4" :alt="locale">
            <span class="ml-1.5">{{ $t('languages.' + locale) }}</span>
        </div>

        <div class="relative" v-if="dropdownOpen">
            <div class="absolute z-50 mb-7 bg-white rounded-md shadow-lg min-w-max py-2 px-4 left-0 w-fit bottom-full">
                <button class="block py-2 text-gray-400 hover:text-purple-600 transition-all duration-300" @click="changeLanguage('en')">
                    <img src="../assets/en.png" class="w-6 h-4 inline" alt="english">
                    {{ $t('languages.en') }}
                </button>
                <button class="block py-2 text-gray-400 hover:text-purple-600 transition-all duration-300" @click="changeLanguage('pl')">
                    <img src="../assets/pl.png" class="w-6 h-4 inline" alt="polski">
                    {{ $t('languages.pl') }}
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import { loadLanguageAsync } from 'laravel-vue-i18n'
import {mapActions, mapState} from "vuex"

export default {
    name: "SidebarLanguage",

    data() {
        return {
            dropdownOpen: false
        }
    },

    methods: {
        ...mapActions('locale', ['setLocale']),

        changeLanguage(lang) {
            this.dropdownOpen = false
            loadLanguageAsync(lang)
            this.setLocale(lang)
        }
    },

    computed: {
        ...mapState('locale', ['locale'])
    }
}
</script>
