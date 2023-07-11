<template>
	<DeleteModal
		:show-modal="showDeleteModal"
		@closing="refreshAndCloseDeleteModal"
		:deleted-resource="deletedResource"
	/>

	<DataTable
		:columns="columns"
		:loading="loading"
		:search="true"
		:title="$t('admin.all_users')"
		:table-data="users.data || []"
		@search-action="search"
		@refresh-action="refresh"
		@add-action="addUser"
		@show="showUser"
		@edit="editUser"
		@delete="deleteUser"
	/>

	<Paginate
		class="flex justify-end lg:mt-4"
		:page-count="users.last_page || 0"
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
import { shallowRef } from 'vue';
import ActiveBadge from '@/components/Table/Cells/ActiveBadge.vue';
import Date from '@/components/Table/Cells/Date.vue';
import DataTable from '@/components/Table/DataTable.vue';
import { mapActions, mapState } from 'vuex';
import Paginate from 'vuejs-paginate-next';
import router from '@/router';
import _ from 'lodash';
import DeleteModal from '@/components/Modals/DeleteModal.vue';

export default {
	name: 'Users',

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
					label: this.$t('user.name')
				},
				{
					name: 'surname',
					label: this.$t('user.surname')
				},
				{
					name: 'identification_number',
					label: this.$t('user.identification_number')
				},
				{
					name: 'email',
					label: this.$t('user.email')
				},
				{
					name: 'account_role',
					label: this.$t('user.account_role')
				},
				{
					name: 'active',
					label: this.$t('user.is_active'),
					component: shallowRef(ActiveBadge)
				},
				{
					name: 'last_success_login',
					label: this.$t('user.last_success_login'),
					component: shallowRef(Date)
				},
				{
					name: 'last_wrong_login',
					label: this.$t('user.last_wrong_login'),
					component: shallowRef(Date)
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
		...mapState('admin', ['loading', 'users'])
	},

	methods: {
		...mapActions('admin', ['getUsers']),

		addUser() {
			router.push({ name: 'UsersCreate' });
		},

		showUser(userId) {
			router.push({ name: 'UsersView', params: { id: userId } });
		},

		editUser(userId) {
			router.push({ name: 'UsersEdit', params: { id: userId } });
		},

		deleteUser(userId) {
			this.showDeleteModal = true;
			this.deletedResource.type = 'user';
			this.deletedResource.id = userId;
		},

		refresh() {
			this.fetchData(this.page);
		},

		search: _.debounce(function (search) {
			this.searchString = search;

			this.getUsers({ search: search, page: (this.page = 1) });
		}, 500),

		fetchData(page) {
			this.page = page;

			this.getUsers({ search: this.searchString, page: this.page });
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
