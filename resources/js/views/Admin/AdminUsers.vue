<template>
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
        class="flex justify-end"
        :page-count="users.last_page || 0"
        :click-handler="fetchData"
        :page-range="5"
        :force-page="page"
        :prev-text="$t('pagination.previous')"
        :next-text="$t('pagination.next')"
    />
</template>

<script>
import {shallowRef} from "vue"
import ActiveBadge from "../../components/Table/Cells/ActiveBadge"
import Date from "../../components/Table/Cells/Date"
import DataTable from "../../components/Table/DataTable"
import {mapActions, mapState} from "vuex"
import Paginate from 'vuejs-paginate-next'
import router from "../../router"
import _ from "lodash"

export default {
    name: "Users",

    components: {DataTable, Paginate},

    data() {
        return {
            page: 1,
            searchString: '',

            columns: [
                {
                    name: 'id',
                    label: 'id'
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
            ],
        }
    },

    computed: {
        ...mapState('admin', ['loading', 'users'])
    },

    methods: {
        ...mapActions('admin', ['getAllUsers', 'getUser']),

        addUser() {
            router.push({name: 'UsersCreate'})
        },

        showUser(userId) {
            router.push({name:'UsersView', params: {id:userId}})
        },

        editUser(userId) {
            router.push({name:'UsersEdit', params: {id:userId}})
        },

        deleteUser(userId) {
            alert(userId)
        },

        refresh() {
            this.fetchData(this.page)
        },

        search: _.debounce(function(search) {
            this.searchString = search

            if(search !== '')
                this.getUser({search: search, page: this.page = 1})
            else
                this.getAllUsers(this.page = 1)

        }, 500),

        fetchData(page) {
            this.page = page

            if(this.searchString !== '')
                this.getUser({ search: this.searchString, page: this.page })
            else
                this.getAllUsers(page)
        }
    },

    created() {
        this.fetchData(this.page)
    }
}
</script>
