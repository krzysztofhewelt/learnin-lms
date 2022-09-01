<template>
    <DataTable
        :columns="columns"
        :loading="loading"
        :search="true"
        :title="$t('admin.all_tasks')"
        :table-data="tasks.data || []"
        @search-action="search"
        @refresh-action="refresh"
        @add-action="addTask"
        @show="showTask"
        @edit="editTask"
        @delete="deleteTask"
    />

    <Paginate
        class="flex justify-end"
        :page-count="tasks.last_page || 0"
        :click-handler="fetchData"
        :page-range="5"
        :force-page="page"
        :prev-text="$t('pagination.previous')"
        :next-text="$t('pagination.next')"
    />
</template>

<script>
import DataTable from "../../components/Table/DataTable"
import Paginate from "vuejs-paginate-next"
import {mapActions, mapState} from "vuex"
import router from "../../router"
import Date from "../../components/Table/Cells/Date"
import _ from "lodash"
import {shallowRef} from "vue"

export default {
    name: "Courses",

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
        }
    },

    computed: {
        ...mapState('admin', ['loading', 'tasks'])
    },

    methods: {
        ...mapActions('admin', ['getAllTasks', 'getTask']),

        addTask() {
            router.push({name: 'TasksCreate'})
        },

        showTask(taskId) {
            router.push({name:'TasksView', params: {id:taskId}})
        },

        editTask(taskId) {
            router.push({name:'TasksEdit', params: {id:taskId}})
        },

        deleteTask(taskId) {
            alert(taskId)
        },

        refresh() {
            this.fetchData(this.page)
        },

        search: _.debounce(function(search) {
            this.searchString = search

            if(search !== '')
                this.getTask({search: search, page: this.page = 1})
            else
                this.getAllTasks(this.page = 1)
        }, 500),

        fetchData(page) {
            this.page = page

            if(this.searchString !== '')
                this.getTask({search: this.searchString, page: this.page})
            else
                this.getAllTasks(page)
        }
    },

    created() {
        this.fetchData(this.page)
    }
}
</script>
