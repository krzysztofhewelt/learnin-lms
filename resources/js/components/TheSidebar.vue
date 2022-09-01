<template>
    <button id="mobile-menu" class="z-50 fixed lg:hidden" @click="mobileSidebarOpen = !mobileSidebarOpen">
        <Close v-if="mobileSidebarOpen" class="w-16 h-16" />
        <Hamburger v-else class="w-16 h-16" />
    </button>

    <div :class="{'fixed z-40 w-full bg-gray-400 bg-opacity-90 lg:relative lg:w-fit lg:bg-transparent transition duration-400' : mobileSidebarOpen}">
        <div id="sidebar" class="left-0 py-14 min-h-screen overflow-auto lg:sticky max-h-screen lg:top-0 bg-white w-64 lg:py-7 lg:flex lg:flex-col lg:justify-between shadow-xl ease-in-out"
             :class="[ mobileSidebarOpen ? 'min-w-screen' : 'hidden' ]">
            <!-- logo -->
            <AppLogo class="w-4/5 mx-auto" />

            <!-- additional text -->
            <h3 class="text-center font-bold px-4 mb-0 break-words" v-if="getSidebarTitle">
                {{ getSidebarTitle }}
            </h3>

            <!-- nav -->
            <nav class="pl-4">
                <div class="inline">
                    <SidebarLink
                        :name="$t('sidebar.dashboard')"
                        route-name="Dashboard"
                        route-parent="Dashboard"
                        @click="mobileSidebarOpen = false"
                    >
                        <Home class="inline mr-1.5 h-5 w-5" />
                    </SidebarLink>

                    <SidebarLink
                        :name="$t('sidebar.courses')"
                        route-name="CoursesUser"
                        route-parent="Courses"
                        v-if="isStudent || isTeacher"
                        @click="mobileSidebarOpen = false"
                    >
                        <Statistics class="inline mr-1.5 h-5 w-5" />
                    </SidebarLink>

                    <SidebarLink
                        :name="$t('sidebar.tasks')"
                        route-name="TasksUser"
                        route-parent="Tasks"
                        v-if="isStudent || isTeacher"
                        @click="mobileSidebarOpen = false"
                    >
                        <Tasks class="inline mr-1.5 h-5 w-5" />
                    </SidebarLink>

                    <SidebarLink
                        :name="$t('sidebar.marks')"
                        route-name="MarksUser"
                        route-parent="Marks"
                        v-if="isStudent"
                        @click="mobileSidebarOpen = false"
                    >
                        <Marks class="inline mr-1.5 h-5 w-5" />
                    </SidebarLink>

                    <SidebarLink
                        :name="$t('sidebar.statistics')"
                        route-name="Statistics"
                        route-parent="Statistics"
                        v-if="isTeacher"
                        @click="mobileSidebarOpen = false"
                    >
                        <Statistics class="inline mr-1.5 h-5 w-5" />
                    </SidebarLink>

                    <SidebarLink
                        :name="$t('sidebar.users')"
                        route-name="AdminUsers"
                        route-parent="Admin"
                        v-if="isAdmin"
                        @click="mobileSidebarOpen = false"
                    >
                        <Users class="inline mr-1.5 h-5 w-5" />
                    </SidebarLink>

                    <SidebarLink
                        :name="$t('sidebar.courses')"
                        route-name="AdminCourses"
                        route-parent="Admin"
                        v-if="isAdmin"
                        @click="mobileSidebarOpen = false"
                    >
                        <Statistics class="inline mr-1.5 h-5 w-5" />
                    </SidebarLink>

                    <SidebarLink
                        :name="$t('sidebar.tasks')"
                        route-name="AdminTasks"
                        route-parent="Admin"
                        v-if="isAdmin"
                        @click="mobileSidebarOpen = false"
                    >
                        <Tasks class="inline mr-1.5 h-5 w-5" />
                    </SidebarLink>

                    <SidebarLink
                        :name="$t('sidebar.my_account')"
                        route-name="ProfileUser"
                        route-parent="Profile"
                        @click="mobileSidebarOpen = false"
                    >
                        <User class="inline mr-1.5 h-5 w-5" />
                    </SidebarLink>
                </div>
            </nav>

            <div class="w-full relative sticky pl-4">
                <SidebarLanguage />
                <SidebarLink
                    class="text-red-500 font-bold hover:text-red-700"
                    route-name="Login"
                    route-parent="Login"
                    :name="$t('sidebar.logout')"
                    @click="mobileSidebarOpen = false"
                >
                    <Logout class="inline mr-1.5 h-5 w-5" />
                </SidebarLink>
            </div>
        </div>
    </div>
</template>

<script>
import AppLogo from "./AppLogo"
import SidebarLink from "./SidebarLink"
import Home from "./Icons/Home"
import Statistics from "./Icons/Statistics"
import Tasks from "./Icons/Tasks"
import Marks from "./Icons/Marks"
import Users from "./Icons/Users"
import User from "./Icons/User"
import Logout from "./Icons/Logout"
import Hamburger from "./Icons/Hamburger"
import Close from "./Icons/Close"
import SidebarLanguage from "./SidebarLanguage"
import {mapGetters} from "vuex"

export default {
    name: "TheSidebar",
    components: {
        SidebarLanguage,
        Close, Hamburger, Logout, Home, User, Users, Statistics, Tasks, Marks, SidebarLink, AppLogo},

    watch: {
        mobileSidebarOpen: {
            handler() {
                const body = document.getElementsByTagName('body')[0];

                if(this.mobileSidebarOpen)
                    body.classList.add("overflow-hidden", "lg:overflow-auto");
                else
                    body.classList.remove("overflow-hidden", "lg:overflow-auto");
            }

        }
    },

    computed: {
        ...mapGetters('login', ['isStudent', 'isTeacher', 'isAdmin']),

        getSidebarTitle() {
            if(this.isAdmin)
                return this.$t('sidebar.admin_panel_title')
            else if(this.isTeacher)
                return this.$t('sidebar.teacher_panel_title')
            else
                return ''
        }
    },

    data() {
        return {
            mobileSidebarOpen: false
        }
    }
}
</script>
