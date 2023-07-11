<template>
	<DataTable
		:columns="columns"
		:loading="loading"
		:search="true"
		:title="$t('admin.all_tasks')"
		:table-data="tasks.data || []"
		:can-edit="false"
		@search-action="search"
		@refresh-action="refresh"
		@show="showTask"
	/>

	<Paginate
		class="flex justify-end lg:mt-4"
		:page-count="tasks.last_page || 0"
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
import Date from '@/components/Table/Cells/Date.vue';
import _ from 'lodash';
import { shallowRef } from 'vue';

export default {
	name: 'Courses',

	components: { DataTable, Paginate },

	data() {
		return {
			page: 1,
			searchString: '',

			columns: [
				{
					name: 'id',
					label: 'ID'
				},
				{
					name: 'name',
					label: this.$t('task.task_name')
				},
				{
					name: 'available_from',
					label: this.$t('task.available_from_label'),
					component: shallowRef(Date)
				},
				{
					name: 'available_to',
					label: this.$t('task.available_to_label'),
					component: shallowRef(Date)
				},
				{
					name: 'max_points',
					label: this.$t('task.max_points_to_earn')
				},
				{
					name: 'course',
					subName: 'name',
					label: this.$t('course.course')
				},
				{
					name: 'category',
					subName: 'name',
					label: this.$t('course.course_category')
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
		...mapState('admin', ['loading', 'tasks'])
	},

	methods: {
		...mapActions('admin', ['getTasks']),

		showTask(taskId) {
			router.push({ name: 'TasksView', params: { id: taskId } });
		},

		refresh() {
			this.fetchData(this.page);
		},

		search: _.debounce(function (search) {
			this.searchString = search;

			this.getTasks({ search: search, page: (this.page = 1) });
		}, 500),

		fetchData(page) {
			this.page = page;

			this.getTasks({ search: this.searchString, page: this.page });
		}
	},

	created() {
		this.fetchData(this.page);
	}
};
</script>
