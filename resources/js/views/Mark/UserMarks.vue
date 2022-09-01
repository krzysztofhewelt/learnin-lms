<template>
    <div class="py-3" v-for="mark in infiniteMarks.data" :key="mark.id">
        <div class="relative grid grid-cols-1 space-y-3 grid-flow-row w-full text-center rounded-lg bg-white shadow-xl p-6 lg:divide-x lg:space-y-0 lg:grid-cols-5">
            <div class="break-words">
                <router-link :to="{name:'TasksView', params: {id:mark.task.id}}" class="block font-bold text-purple-800 text-xl m-auto no-underline hover:text-purple-500">{{ mark.task.name }}</router-link>
                <div class="text-gray-500">
                    <b>{{ $t('course.course') }}:</b>
                    {{ mark.task.course.name }}
                </div>
                <div class="text-gray-500">
                    <b>{{ $t('course.category') }}:</b>
                    {{ mark.task.category.name }}
                </div>
            </div>
            <div>
                <div class="text-xl font-bold">{{ $t('mark.mark') }}</div>
                <div class="text-2xl">{{ mark.mark }}</div>
            </div>
            <div>
                <div class="text-xl font-bold">{{ $t('mark.points') }}</div>
                <div class="text-2xl">{{ mark.points }} <span class="text-zinc-500">/ {{ mark.task.max_points }}</span></div>
            </div>
            <div>
                <div class="text-xl font-bold">{{ $t('mark.date_of_mark_label') }}</div>
                <div class="text-2xl">{{ getFormattedMarkDate(mark.updated_at) }}</div>
            </div>
            <div>
                <div class="text-xl font-bold">{{ $t('mark.comment') }}</div>
                <button class="font-bold text-purple-800 text-2xl m-auto no-underline hover:text-purple-500"
                        v-if="mark.description !== null"
                        @click="mark.showDesc = !mark.showDesc">
                        {{ (mark.showDesc === false || !mark.showDesc) ? $t('general.show') : $t('general.hide') }}
                </button>
                <div v-else class="text-center text-xl font-bold">
                    {{ $t('general.not_available') }}
                </div>
            </div>
        </div>
        <div class="w-full bg-slate-100 p-6 shadow-xl" v-if="mark.showDesc">
            <h1 class="font-bold text-xl mb-2">{{ $t('mark.comment') }}</h1>
            <p class="whitespace-pre-line">
                {{ mark.description }}
            </p>
        </div>
    </div>

    <div class="text-center">
        <button class="normal_btn" v-show="page < marks.last_page && !loading" @click="showMore">{{ $t('general.show_more') }}</button>

        <div v-if="loading" class="mt-6 text-2xl">
            <Loading class="w-4 h-4 inline animate-spin" />
            {{ $t('general.loading') }}
        </div>
        <div v-if="marks.length === 0 && !loading" class="mt-6 text-2xl font-bold">
            {{ $t('general.no_data') }}
        </div>

        <div v-if="page === marks.last_page && !loading" class="mt-6 text-2xl font-bold">
            {{ $t('mark.all_marks') }}
        </div>
    </div>
</template>

<script>
import {mapActions, mapGetters, mapState} from "vuex"
import Loading from "../../components/Icons/Loading"

export default {
    name: "UserMarks",
    components: {Loading},
    data() {
        return {
            infiniteMarks: [],
            page: 1
        }
    },

    computed: {
        ...mapState('user', ['loading', 'marks']),
        ...mapGetters('user', ['getFormattedMarkDate'])
    },

    methods: {
        ...mapActions('user', ['getUserMarks']),

        showMore() {
            if(this.page + 1 <= this.marks.last_page)
            {
                this.page++

                this.getUserMarks({userId: '', page: this.page}).then(
                    () => {
                        this.infiniteMarks.data.push(...this.marks.data)
                    }
                )
            }
        }
    },

    created() {
        this.getUserMarks({userId: '', page: this.page})
            .then(
                () => {
                    this.infiniteMarks = this.marks
                }
            )
    }
}
</script>
