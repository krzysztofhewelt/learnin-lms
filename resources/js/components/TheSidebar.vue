<template>
	<button
		id="mobile-menu"
		class="fixed right-0 z-50 lg:hidden"
		@click="mobileSidebarOpen = !mobileSidebarOpen"
	>
		<Close v-if="mobileSidebarOpen" class="h-14 w-14" />
		<Hamburger v-else class="h-14 w-14" />
	</button>

	<div
		:class="{
			'duration-400 fixed z-40 w-full bg-gray-300 bg-opacity-90 transition lg:relative lg:w-fit lg:bg-transparent':
				mobileSidebarOpen
		}"
	>
		<div
			id="sidebar"
			class="left-0 max-h-screen min-h-screen w-64 overflow-auto bg-white py-14 shadow-xl ease-in-out lg:sticky lg:top-0 lg:flex lg:flex-col lg:justify-between lg:py-7"
			:class="[mobileSidebarOpen ? 'min-w-screen' : 'hidden']"
		>
			<!-- logo -->
			<AppLogo class="mx-auto w-4/5" />

			<!-- additional text -->
			<h3 class="mb-0 break-words px-4 text-center text-xl font-bold" v-if="getSidebarTitle">
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
						<Home class="mr-1.5 inline h-5 w-5" />
					</SidebarLink>

					<SidebarLink
						:name="$t('sidebar.courses')"
						route-name="CoursesUser"
						route-parent="Courses"
						v-if="isStudent || isTeacher"
						@click="mobileSidebarOpen = false"
					>
						<Statistics class="mr-1.5 inline h-5 w-5" />
					</SidebarLink>

					<SidebarLink
						:name="$t('sidebar.tasks')"
						route-name="TasksUser"
						route-parent="Tasks"
						v-if="isStudent || isTeacher"
						@click="mobileSidebarOpen = false"
					>
						<Tasks class="mr-1.5 inline h-5 w-5" />
					</SidebarLink>

					<SidebarLink
						:name="$t('sidebar.marks')"
						route-name="MarksUser"
						route-parent="Marks"
						v-if="isStudent"
						@click="mobileSidebarOpen = false"
					>
						<Marks class="mr-1.5 inline h-5 w-5" />
					</SidebarLink>

					<SidebarLink
						:name="$t('sidebar.statistics')"
						route-name="Statistics"
						route-parent="Statistics"
						v-if="isTeacher"
						@click="mobileSidebarOpen = false"
					>
						<Statistics class="mr-1.5 inline h-5 w-5" />
					</SidebarLink>

					<SidebarLink
						:name="$t('sidebar.users')"
						route-name="AdminUsers"
						route-parent="Admin"
						v-if="isAdmin"
						@click="mobileSidebarOpen = false"
					>
						<Users class="mr-1.5 inline h-5 w-5" />
					</SidebarLink>

					<SidebarLink
						:name="$t('sidebar.courses')"
						route-name="AdminCourses"
						route-parent="Admin"
						v-if="isAdmin"
						@click="mobileSidebarOpen = false"
					>
						<Statistics class="mr-1.5 inline h-5 w-5" />
					</SidebarLink>

					<SidebarLink
						:name="$t('sidebar.tasks')"
						route-name="AdminTasks"
						route-parent="Admin"
						v-if="isAdmin"
						@click="mobileSidebarOpen = false"
					>
						<Tasks class="mr-1.5 inline h-5 w-5" />
					</SidebarLink>

					<SidebarLink
						:name="$t('sidebar.my_account')"
						route-name="ProfileUser"
						route-parent="Profile"
						@click="mobileSidebarOpen = false"
					>
						<User class="mr-1.5 inline h-5 w-5" />
					</SidebarLink>
				</div>
			</nav>

			<div class="relative sticky w-full pl-4">
				<SidebarLanguage />
				<SidebarLink
					class="font-bold text-red-500 hover:text-red-700"
					route-name="Login"
					route-parent="Login"
					:name="$t('sidebar.logout')"
					@click="mobileSidebarOpen = false"
				>
					<Logout class="mr-1.5 inline h-5 w-5" />
				</SidebarLink>
			</div>
		</div>
	</div>
</template>

<script>
import AppLogo from '@/components/AppLogo.vue';
import SidebarLink from '@/components/SidebarLink.vue';
import Home from '@/components/Icons/Home.vue';
import Statistics from '@/components/Icons/Statistics.vue';
import Tasks from '@/components/Icons/Tasks.vue';
import Marks from '@/components/Icons/Marks.vue';
import Users from '@/components/Icons/Users.vue';
import User from '@/components/Icons/User.vue';
import Logout from '@/components/Icons/Logout.vue';
import Hamburger from '@/components/Icons/Hamburger.vue';
import Close from '@/components/Icons/Close.vue';
import SidebarLanguage from '@/components/SidebarLanguage.vue';
import { mapGetters } from 'vuex';

export default {
	name: 'TheSidebar',
	components: {
		SidebarLanguage,
		Close,
		Hamburger,
		Logout,
		Home,
		User,
		Users,
		Statistics,
		Tasks,
		Marks,
		SidebarLink,
		AppLogo
	},

	watch: {
		mobileSidebarOpen: {
			handler() {
				const body = document.getElementsByTagName('body')[0];

				if (this.mobileSidebarOpen)
					body.classList.add('overflow-hidden', 'lg:overflow-auto');
				else body.classList.remove('overflow-hidden', 'lg:overflow-auto');
			}
		}
	},

	computed: {
		...mapGetters('login', ['isStudent', 'isTeacher', 'isAdmin']),

		getSidebarTitle() {
			if (this.isAdmin) return this.$t('sidebar.admin_panel_title');
			else if (this.isTeacher) return this.$t('sidebar.teacher_panel_title');
			else return '';
		}
	},

	data() {
		return {
			mobileSidebarOpen: false
		};
	}
};
</script>
