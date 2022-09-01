<template>
    <TeacherModal v-if="isUserTeacher"
                  :show-modal="showTeacherModal"
                  @closing="showTeacherModal = false"
                  :user-id="user.id"
                  :teacher="teacher" />

    <div class="relative mb-6 mt-6 w-full min-w-0 break-words rounded-lg bg-white shadow-xl">
        <LoadingScreen v-if="loading" />
        <div class="px-6">
            <div class="w-full px-4 text-right">
                <div class="mt-32 py-6 px-3 sm:mt-0">
                    <button class="mb-1 rounded no-underline bg-pink-500 px-4 py-2 text-xs font-bold uppercase text-white shadow outline-none transition-all duration-150 ease-linear hover:shadow-md focus:outline-none active:bg-pink-600 sm:mr-2"
                            v-if="isUserTeacher && this.$route.name === 'ProfileUser'"
                            @click="showEditableTeacherModal">
                        {{ $t('user.teacher_edit') }}
                    </button>

                    <router-link :to="{name:'UsersEdit', params:{id:$route.params.id}}" v-if="this.$route.name === 'UsersView' && isAdmin" class="mb-1 rounded bg-pink-500 px-4 py-2 text-xs font-bold uppercase text-white shadow outline-none transition-all duration-150 ease-linear hover:shadow-md focus:outline-none active:bg-pink-600 sm:mr-2 no-underline">
                        {{ $t('user.edit_this_user') }}
                    </router-link>

                    <router-link :to="{name: 'ProfileChangePassword'}" v-if="this.$route.name === 'ProfileUser'" class="mb-1 rounded no-underline bg-pink-500 px-4 py-2 text-xs font-bold uppercase text-white shadow outline-none transition-all duration-150 ease-linear hover:shadow-md focus:outline-none active:bg-pink-600 sm:mr-2">
                        {{ $t('user.change_password_button') }}
                    </router-link>
                </div>
            </div>
            <div class="mt-12 text-center">
                <h3 v-if="isUserTeacher" class="mb-2 text-4xl font-semibold leading-normal text-slate-700">
                    {{ teacher.scien_degree }} {{ user.name }} {{ user.surname }}
                </h3>
                <h3 v-else class="mb-2 text-4xl font-semibold leading-normal text-slate-700">
                    {{ user.name }} {{ user.surname }}
                </h3>
                <div v-if="isUserStudent" class="mt-0 mb-2 text-sm font-bold leading-normal text-slate-400">
                    <i class="fas fa-map-marker-alt mr-2 text-lg text-slate-400"></i>
                    {{ user.identification_number }}
                </div>
                <div class="text-sm leading-normal mt-10 mb-2 text-slate-400 font-bold uppercase">
                    <i class="mr-2 text-lg text-slate-400">
                        <University class="w-5 h-5 inline" />
                    </i>

                    <div v-if="isUserAdmin">
                        {{ $t('user.system_administrator') }}
                    </div>

                    <template v-if="isUserStudent">
                        {{ $t('user.student_facilities') }}
                    </template>

                    <template v-if="isUserTeacher">
                        {{ $t('user.teacher_info') }}
                    </template>
                </div>
                <template v-if="isUserStudent">
                    <div v-for="item in student" :key="item.user_ID" class="mb-2 text-slate-600">
                        {{ item.field_of_study }} // {{ item.semester }} {{ $t('user.semester') }} // {{ item.year_of_study }} // {{ item.mode_of_study }}
                    </div>
                </template>
                <div v-if="isUserTeacher" class="mb-2 text-slate-600">
                    <div class="block">
                        <b>{{ $t('user.contact_email') }}</b>: <a :href="'mailto:' + teacher.business_email" class="no-underline font-bold text-pink-800 hover:text-pink-600">{{ teacher.business_email }}</a>
                    </div>
                    <div class="block" v-if="teacher.contact_number != null">
                        <b>{{ $t('user.contact_number') }}</b>: {{ teacher.contact_number }}
                    </div>
                    <div class="block" v-if="teacher.room != null">
                        <b>{{ $t('user.room') }}</b>: {{ teacher.room }}
                    </div>
                    <div class="block" v-if="teacher.consultation_hours != null">
                        <b>{{ $t('user.consultation_hours') }}</b>: {{ teacher.consultation_hours }}
                    </div>
                </div>
            </div>
            <div class="mt-10 border-t border-slate-200 py-10 text-center">
                <div class="flex flex-wrap justify-center">
                    <div class="w-full px-4 lg:w-9/12">
                        <a :href="'mailto:' +  user.email" class="mb-1 no-underline rounded bg-pink-500 px-4 py-2 text-xs font-bold uppercase text-white shadow outline-none transition-all duration-150 ease-linear hover:shadow-md focus:outline-none active:bg-pink-600 sm:mr-2">
                            {{ $t('user.send_email') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import HamburgerIcon from "../../components/Icons/Hamburger"
import University from "../../components/Icons/University"
import LoadingScreen from "../../components/LoadingScreen"
import {mapActions, mapGetters, mapState} from "vuex"
import TeacherModal from "../../components/Modals/TeacherModal";

export default {
    name: "UserView",
    components: {TeacherModal, LoadingScreen, University, HamburgerIcon},

    data() {
        return {
            showTeacherModal: false,
        }
    },

    computed: {
        ...mapState('user', ['loading', 'user', 'student', 'teacher', 'courses', 'marks']),
        ...mapGetters('user', ['isUserAdmin', 'isUserTeacher', 'isUserStudent']),
        ...mapGetters('login', ['isAdmin'])
    },

    methods: {
        ...mapActions('user', ['getUserDetails']),

        showEditableTeacherModal() {
            this.showTeacherModal = true
        }
    },

    created() {
        if(this.$route.name === 'ProfileUser')
            this.getUserDetails()
        else
            this.getUserDetails(this.$route.params.id)
    }
}
</script>
