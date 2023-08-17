<template>
	<div class="flex justify-between">
		<div class="flex flex-col space-y-3 lg:flex-row lg:space-x-10 lg:space-y-0">
			<Tab :active="currentTab === 'active'" @click="setCoursesView('active')">
				{{ $t('course.active_tab') }} ({{ getActiveCourses.length }})
			</Tab>
			<Tab :active="currentTab === 'ended'" @click="setCoursesView('ended')">
				{{ $t('course.ended_tab') }} ({{ getEndedCourses.length }})
			</Tab>
			<Tab
				:active="currentTab === 'upcoming'"
				v-if="isTeacher"
				@click="setCoursesView('upcoming')"
			>
				{{ $t('course.upcoming_tab') }} ({{ getUpcomingCourses.length }})
			</Tab>
		</div>
		<div>
			<router-link :to="{ name: 'CoursesCreate' }" class="normal_btn" v-if="isTeacher">
				<Add class="m-1 h-4 w-4" />
				{{ $t('course.add_course') }}
			</router-link>
		</div>
	</div>

	<div v-if="loading" class="mt-6 text-2xl">
		<Loading class="inline h-4 w-4 animate-spin" />
		{{ $t('general.loading') }}
	</div>
	<div v-if="result.length === 0 && !loading" class="mt-6 text-2xl font-bold">
		{{ $t('general.no_data') }}
	</div>

	<div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
		<div
			v-for="course in result"
			:key="course.id"
			class="flex flex-1 flex-col justify-between rounded-lg bg-white p-3 text-gray-500 drop-shadow-xl transition duration-200 ease-in-out"
		>
			<div>
				<router-link
					:to="{ name: 'CoursesView', params: { id: course.id } }"
					class="text-xl font-bold text-black no-underline"
				>
					{{ course.name }}
				</router-link>

				<div>
					{{ course.description?.slice(0, 100) }}
					{{ course.description?.length > 100 ? '...' : '' }}
				</div>
			</div>

			<div class="mt-4">
				<div>
					<b>{{ $t('course.teachers') }}: </b>
					<template
						v-for="(teacher, index) in getTeachersInCourse(course)"
						:key="teacher.id"
					>
						<span v-if="index !== getTeachersInCourse(course).length - 1"
							>{{ teacher.name }} {{ teacher.surname }},</span
						>
						<span v-else>{{ teacher.name }} {{ teacher.surname }}</span>
					</template>
				</div>
				<div v-if="course.available_to !== null && currentTab !== 'upcoming'">
					<b>{{ $t('course.available_to') }}:</b>
					{{ getFormattedDateTime(course.available_to) }}
				</div>
				<div v-if="course.available_to === null && currentTab !== 'upcoming'">
					{{ $t('course.no_available_to') }}
				</div>
				<div v-if="currentTab === 'upcoming'" class="font-bold underline">
					<b>{{ $t('course.available_from') }}:</b>
					{{ getFormattedDateTime(course.available_from) }}
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import { mapActions, mapGetters, mapState } from 'vuex';
import Dropdown from '@/components/Dropdown.vue';
import Tab from '@/components/Tab.vue';
import Loading from '@/components/Icons/Loading.vue';
import Add from '@/components/Icons/Add.vue';
import { getFormattedDateTime } from '@/utils/dateFormatter';

export default {
	name: 'CourseList',
	components: { Add, Loading, Tab, Dropdown },

	data() {
		return {
			currentTab: 'active',
			result: []
		};
	},

	computed: {
		...mapState('user', ['loading', 'courses']),
		...mapGetters('user', [
			'getActiveCourses',
			'getEndedCourses',
			'getUpcomingCourses',
			'getTeachersInCourse'
		]),
		...mapGetters('login', ['isTeacher'])
	},

	methods: {
		getFormattedDateTime,
		...mapActions('user', ['getUserCourses']),

		setCoursesView(type) {
			switch (type) {
				case 'active':
					this.result = this.getActiveCourses;
					this.currentTab = 'active';
					break;
				case 'ended':
					this.result = this.getEndedCourses;
					this.currentTab = 'ended';
					break;
				case 'upcoming':
					this.result = this.getUpcomingCourses;
					this.currentTab = 'upcoming';
					break;
				default:
					this.result = this.getActiveCourses;
			}
		}
	},

	created() {
		this.getUserCourses().then(() => {
			this.result = this.getActiveCourses;
		});
	}
};
</script>
