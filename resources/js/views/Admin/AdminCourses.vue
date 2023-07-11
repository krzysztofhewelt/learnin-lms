'
<template>
	<DeleteModal
		:show-modal="showDeleteModal"
		@closing="refreshAndCloseDeleteModal"
		:deleted-resource="deletedResource"
		:admin-delete="true"
	/>

	<DataTable
		:columns="columns"
		:loading="loading"
		:search="true"
		:title="$t('admin.all_courses')"
		:table-data="courses.data || []"
		@search-action="search"
		@refresh-action="refresh"
		@add-action="addCourse"
		@show="showCourse"
		@edit="editCourse"
		@delete="deleteCourse"
	/>

	<Paginate
		class="flex justify-end lg:mt-4"
		:page-count="courses.last_page || 0"
		:click-handler="fetchData"
		active-class="pagination__page_link--active"
		prev-link-class="pagination__prev_link"
		next-link-class="pagination__next_link"
		page-link-class="pagination__page_link"
		disabled-class="pagination__page_link--disabled"
		:no-li-surround="true"
		:page-range="5"
		:force-page="page"
		:prev-text="$t('pagination.previous')"
		:next-text="$t('pagination.next')"
	/>
</template>

<script>
import DataTable from '@/components/Table/DataTable.vue';
import Paginate from 'vuejs-paginate-next';
import { mapActions, mapState } from 'vuex';
import router from '@/router';
import _ from 'lodash';
import DeleteModal from '@/components/Modals/DeleteModal.vue';
import course from '@/store/modules/course';

export default {
	name: 'AdminUsers',

	components: { DeleteModal, DataTable, Paginate },

	data() {
		return {
			page: 1,
			searchString: '',
			showDeleteModal: false,
			deletedResource: {
				type: 'none',
				id: -1
			},

			columns: [
				{
					name: 'id',
					label: 'ID'
				},
				{
					name: 'name',
					label: this.$t('course.course_name')
				},
				{
					name: 'available_from',
					label: this.$t('course.available_from_label')
				},
				{
					name: 'available_to',
					label: this.$t('course.available_to_label')
				},
				{
					name: 'crud',
					label: '',
					size: 'w-2'
				}
			]
		};
	},

	computed: {
		...mapState('admin', ['loading', 'courses'])
	},

	methods: {
		...mapActions('admin', ['getCourses']),

		addCourse() {
			router.push({ name: 'CoursesCreate' });
		},

		showCourse(courseId) {
			router.push({ name: 'CoursesView', params: { id: courseId } });
		},

		editCourse(courseId) {
			router.push({ name: 'CoursesEdit', params: { id: courseId } });
		},

		deleteCourse(courseId) {
			this.showDeleteModal = true;
			this.deletedResource.type = 'course';
			this.deletedResource.id = courseId;
		},

		refresh() {
			this.fetchData(this.page);
		},

		search: _.debounce(function (search) {
			this.getCourses({ search: search, page: (this.page = 1) });
		}, 500),

		fetchData(page) {
			this.page = page;

			this.getCourses({ search: this.searchString, page: this.page });
		},

		refreshAndCloseDeleteModal() {
			this.showDeleteModal = false;
			this.refresh();
		}
	},

	created() {
		this.fetchData(this.page);
	}
};
</script>
