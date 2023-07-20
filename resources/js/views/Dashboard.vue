<template>
	<TeacherModal
		v-if="user.account_role === 'teacher' && user.teacher"
		:show-modal="showTeacherModal"
		@closing="showTeacherModal = false"
		:user-id="user.id"
		:teacher="user.teacher"
	/>

	<div
		class="relative grid grid-flow-dense grid-cols-1 gap-12"
		:class="{ 'lg:grid-cols-3': !isAdmin }"
	>
		<LoadingScreen v-if="loading" />
		<div class="col-span-1 lg:col-span-2" v-if="!isAdmin">
			<div class="mb-3 flex justify-between">
				<div class="text-2xl font-bold">
					{{ $t('dashboard.newest_courses') }}
				</div>
				<router-link :to="{ name: 'CoursesUser' }" class="normal_btn">{{
					$t('general.show_more')
				}}</router-link>
			</div>
			<div class="mb-8 grid grid-cols-1 gap-6 lg:grid-cols-3" v-if="!loading">
				<div v-if="courses.length === 0" class="mt-6 text-2xl font-bold">
					{{ $t('general.no_data') }}
				</div>

				<div
					v-for="course in courses"
					:key="course.id"
					class="flex flex-1 flex-col justify-between rounded-lg bg-white p-3 text-gray-500 drop-shadow-xl transition duration-200 ease-in-out"
				>
					<div>
						<router-link
							:to="{
								name: 'CoursesView',
								params: { id: course.id }
							}"
							class="break-words text-xl font-bold text-black no-underline"
						>
							{{ course.name }}
						</router-link>

						<div>
							{{ course.description && course.description.slice(0, 100) }}
							{{ course.description && course.description.length > 100 ? '...' : '' }}
						</div>
					</div>

					<div class="mt-4">
						<div>
							<b>{{ $t('course.teachers') }}: </b>
							<template v-for="(teacher, index) in course.users" :key="teacher.id">
								<span v-if="index !== course.users.length - 1"
									>{{ teacher.name }} {{ teacher.surname }},
								</span>
								<span v-else>{{ teacher.name }} {{ teacher.surname }}</span>
							</template>
						</div>
						<div>
							<b>{{ $t('course.available_from') }}:</b>
							{{ getFormattedDate(course.available_from) }}
						</div>
						<div v-if="course.available_to !== null">
							<b>{{ $t('course.available_to') }}:</b>
							{{ getFormattedDate(course.available_to) }}
						</div>
						<div v-else>
							{{ $t('course.no_available_to') }}
						</div>
					</div>
				</div>
			</div>

			<div v-if="!isAdmin">
				<div class="mb-3 flex justify-between">
					<div class="text-2xl font-bold">
						{{ $t('dashboard.tasks_to_do') }}
					</div>
					<router-link :to="{ name: 'TasksUser' }" class="normal_btn">{{
						$t('general.show_more')
					}}</router-link>
				</div>

				<div v-if="tasks.length === 0 && !loading" class="mt-6 text-2xl font-bold">
					{{ $t('general.no_data') }}
				</div>

				<div v-for="task in tasks" :key="task.id" class="py-3">
					<div
						class="relative grid w-full grid-flow-row grid-cols-1 gap-y-3 break-words rounded-lg bg-white p-6 text-center shadow-xl lg:grid-cols-3 lg:divide-x"
					>
						<div class="px-4">
							<router-link
								:to="{
									name: 'TasksView',
									params: { id: task.id }
								}"
								class="m-auto block text-xl font-bold text-purple-800 no-underline hover:text-purple-500"
							>
								{{ task.name }}
							</router-link>
							<div class="text-gray-500">
								<b>{{ $t('course.course') }}:</b>
								{{ task.course.name }}
							</div>
							<div class="text-gray-500">
								<b>{{ $t('course.category') }}:</b>
								{{ task.category.name }}
							</div>
						</div>
						<div class="px-4">
							<span class="block text-xl font-bold">{{
								$t('task.available_from')
							}}</span>
							<span class="text-2xl">{{
								getFormattedDate(task.available_from)
							}}</span>
						</div>
						<div class="px-4">
							<span class="block text-xl font-bold">{{
								$t('task.available_to')
							}}</span>
							<span class="text-2xl">{{
								getFormattedDate(task.available_to) || $t('general.not_available')
							}}</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row-start-1 lg:row-start-auto">
			<h1 class="mb-3 text-2xl font-bold" :class="{ 'text-center': isAdmin }">
				{{ $t('user.profile') }}
			</h1>
			<div class="text-center">
				<User class="mx-auto h-40 w-40" />

				<div class="py-4">
					<h1 class="mb-0 mt-3 text-xl font-bold">
						<template v-if="isTeacher && user.teacher">
							{{ user.teacher.scien_degree }}
						</template>
						{{ user.name }} {{ user.surname }}
					</h1>

					<p>{{ $t('user.' + user.account_role) }}</p>

					<div v-if="isStudent">
						{{ user.identification_number }}
					</div>
				</div>

				<div v-if="isStudent">
					<h1 class="mb-0 text-xl font-bold">
						{{ $t('user.student_facilities_logged') }}
					</h1>
					<div
						v-for="item in user.student"
						:key="item.user_ID"
						class="mb-2 text-slate-600"
					>
						{{ item.field_of_study }} // {{ item.semester }}
						{{ $t('user.semester') }} // {{ item.year_of_study }} //
						{{ item.mode_of_study }}
					</div>
				</div>

				<div v-if="isTeacher && user.teacher">
					<button class="normal_btn" @click="showEditableTeacherModal">
						{{ $t('user.update_this_data') }}
					</button>
					<p class="mb-0">
						{{ $t('user.contact_email') }}:
						{{ user.teacher.business_email }}
					</p>
					<p class="mb-0">
						{{ $t('user.contact_number') }}:
						{{ user.teacher.contact_number || $t('general.not_available') }}
					</p>
					<p class="mb-0">
						{{ $t('user.room') }}:
						{{ user.teacher.room || $t('general.not_available') }}
					</p>
					<p class="mb-0">
						{{ $t('user.consultation_hours') }}:
						{{ user.teacher.consultation_hours || $t('general.not_available') }}
					</p>
				</div>

				<p class="mb-0 mt-6">
					<b>{{ $t('user.last_success_login') }}:</b>
					{{ getFormattedDate(user.last_success_login) }}
				</p>
				<p class="mb-0">
					<b>{{ $t('user.last_wrong_login') }}:</b>
					{{ getFormattedDate(user.last_wrong_login) || $t('general.not_available') }}
				</p>
			</div>

			<div class="mt-9" v-if="isStudent">
				<div class="flex justify-between">
					<div class="text-2xl font-bold">
						{{ $t('dashboard.newest_marks') }}
					</div>
					<router-link :to="{ name: 'MarksUser' }" class="normal_btn">{{
						$t('general.show_more')
					}}</router-link>
				</div>

				<div v-if="marks.length === 0 && !loading" class="mt-2 text-2xl font-bold">
					{{ $t('general.no_data') }}
				</div>

				<div class="py-3" v-for="mark in marks" :key="mark.id">
					<div
						class="relative grid w-full grid-flow-row grid-cols-1 gap-y-3 break-words rounded-lg bg-white p-6 text-center shadow-xl 2xl:grid-cols-3 2xl:divide-x"
					>
						<div class="px-4">
							<span class="block text-xl font-bold">{{ $t('task.task') }}</span>
							<router-link
								:to="{
									name: 'TasksView',
									params: { id: mark.task.id }
								}"
								class="m-auto block text-xl font-bold text-purple-800 no-underline hover:text-purple-500"
								>{{ mark.task.name }}</router-link
							>
						</div>
						<div class="px-4">
							<span class="block text-xl font-bold">{{ $t('mark.mark') }}</span>
							<span class="text-2xl">{{ mark.mark }}</span>
						</div>
						<div class="px-4">
							<span class="block text-xl font-bold">{{ $t('mark.points') }}</span>
							<span class="text-2xl"
								>{{ mark.points }}
								<span class="text-zinc-500"
									>/ {{ mark.task.max_points }}</span
								></span
							>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import HamburgerIcon from '@/components/Icons/Hamburger.vue';
import { mapActions, mapGetters, mapState } from 'vuex';
import User from '@/components/Icons/User.vue';
import LoadingScreen from '@/components/LoadingScreen.vue';
import TeacherModal from '@/components/Modals/TeacherModal.vue';

export default {
	name: 'Dashboard',
	components: { TeacherModal, LoadingScreen, User, HamburgerIcon },

	data() {
		return {
			showTeacherModal: false
		};
	},

	computed: {
		...mapGetters('login', ['isStudent', 'isAdmin', 'isTeacher']),
		...mapGetters('user', ['getFormattedDate']),
		...mapState('user', ['loading', 'user', 'courses', 'tasks', 'marks', 'teacher', 'student'])
	},

	methods: {
		...mapActions('user', ['getDashboard']),

		showEditableTeacherModal() {
			this.showTeacherModal = true;
		}
	},

	created() {
		this.getDashboard();
	}
};
</script>
