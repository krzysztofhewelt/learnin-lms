<template>
    <div class="flex justify-between">
        <div class="flex flex-col space-y-3 lg:flex-row lg:space-x-10 lg:space-y-0">
            <Tab
                 :active="currentTab === 'active'"
                 @click="setCoursesView('active')">
                {{ $t('course.active_tab') }} ({{ getActiveCourses.length }})
            </Tab>
            <Tab :active="currentTab === 'ended'"
                 @click="setCoursesView('ended')">
                {{ $t('course.ended_tab') }} ({{ getEndedCourses.length }})
            </Tab>
            <Tab :active="currentTab === 'upcoming'"
                 v-if="isTeacher"
                 @click="setCoursesView('upcoming')">
                {{ $t('course.upcoming_tab') }} ({{ getUpcomingCourses.length }})
            </Tab>
        </div>
        <div>
            <router-link :to="{name:'CoursesCreate'}"
                         class="normal_btn"
                         v-if="isTeacher">
                <Add class="w-4 h-4 m-1" />
                {{ $t('course.add_course') }}
            </router-link>
        </div>
    </div>

    <div v-if="loading" class="mt-6 text-2xl">
        <Loading class="w-4 h-4 inline animate-spin" />
        {{ $t('general.loading') }}
    </div>
    <div v-if="result.length === 0 && !loading" class="mt-6 text-2xl font-bold">
        {{ $t('general.no_data') }}
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 mt-6 gap-6">
        <div v-for="course in result" :key="course.id" class="flex flex-1 flex-col justify-between bg-white p-3 text-gray-500 rounded-lg transition ease-in-out duration-200 hover:drop-shadow-xl">
            <div>
                <router-link :to="{name: 'CoursesView', params: {id: course.id}}" class="font-bold text-xl text-black no-underline">
                    {{ course.name }}
                </router-link>

                <div>
                    {{ course.description && course.description.slice(0,100) }}
                    {{ course.description && course.description.length > 100 ? '...' : '' }}
                </div>
            </div>

            <div class="mt-4">
                <div>
                    {{ $t('course.teachers') }}:
                    <template v-for="(teacher, index) in getTeachersInCourse(course)" :key="teacher.id">
                        <span v-if="index !== getTeachersInCourse(course).length - 1">{{ teacher.name }} {{ teacher.surname }},</span>
                        <span v-else>{{ teacher.name }} {{ teacher.surname }}</span>
                    </template>
                </div>
                <div v-if="course.available_to !== null && currentTab !== 'upcoming'">
                    {{ $t('course.available_to_label') }}: {{ course.available_to }}
                </div>
                <div v-if="course.available_to === null && currentTab !== 'upcoming'">
                    {{ $t('course.no_available_to') }}
                </div>
                <div v-if="currentTab === 'upcoming'" class="font-bold underline">
                    {{ $t('course.available_from_label') }}: {{ course.available_from }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapActions, mapGetters, mapState} from "vuex"
import Dropdown from "../../components/Dropdown";
import Tab from "../../components/Tab";
import Loading from "../../components/Icons/Loading";
import Add from "../../components/Icons/Add";

export default {
    name: "CourseList",
    components: {Add, Loading, Tab, Dropdown},

    data() {
        return {
            currentTab: 'active',
            result: []
        }
    },

    computed: {
        ...mapState('user', ['loading', 'courses']),
        ...mapGetters('user', ['getActiveCourses', 'getEndedCourses', 'getUpcomingCourses', 'getTeachersInCourse']),
        ...mapGetters('login', ['isTeacher'])
    },

    methods: {
        ...mapActions('user', ['getUserCourses']),

        setCoursesView(type) {
            switch(type) {
                case 'active': this.result = this.getActiveCourses; this.currentTab = 'active'; break
                case 'ended': this.result = this.getEndedCourses; this.currentTab = 'ended'; break
                case 'upcoming': this.result = this.getUpcomingCourses; this.currentTab = 'upcoming'; break
                default: this.result = this.getActiveCourses
            }
        },
    },

    created() {
        this.getUserCourses()
            .then(
                () => {
                    this.result = this.getActiveCourses
                }
            )
    }
}
</script>
