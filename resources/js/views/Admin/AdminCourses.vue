<template>
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
        class="flex justify-end"
        :page-count="courses.last_page || 0"
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
import _ from "lodash";

export default {
    name: "AdminUsers",

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
        }
    },

    computed: {
        ...mapState('admin', ['loading', 'courses'])
    },

    methods: {
        ...mapActions('admin', ['getAllCourses', 'getCourse']),

        addCourse() {
            router.push({name: 'CoursesCreate'})
        },

        showCourse(courseId) {
            router.push({name:'CoursesView', params: {id:courseId}})
        },

        editCourse(courseId) {
            router.push({name:'CoursesEdit', params: {id:courseId}})
        },

        deleteCourse(courseId) {
            alert(courseId)
        },

        refresh() {
            this.fetchData(this.page)
        },

        search: _.debounce(function(search) {
            if(search !== '')
                this.getCourse({search: search, page: this.page = 1})
            else
                this.getAllCourses(this.page = 1)
        }, 500),

        fetchData(page) {
            this.page = page

            if(this.searchString !== '')
                this.getCourse({search: this.searchString, page: this.page})
            else
                this.getAllCourses(page)
        }
    },

    created() {
        this.fetchData(this.page)
    }
}
</script>
