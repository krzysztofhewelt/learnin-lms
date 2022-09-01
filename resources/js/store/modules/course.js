import axios from "axios"
import router from "../../router"
import dayjs from "dayjs"

const course =  {
    namespaced: true,

    state: {
        loading: false,
        validationErrors: [],
        course: {},
        courseCategories: [],
        courseUsers: [],
        courseFiles: [],
        courseCategoryTasks: [],
    },

    actions: {
        async getCourseDetails({ commit }, courseId) {
            commit('loading', true)
            commit('clearValidationErrors')

            await axios.get('/courses/details/' + courseId)
                .then(
                    response => {
                        commit('setCourse', {
                            id: response.data.id,
                            name: response.data.name,
                            description: response.data.description,
                            available_from: response.data.available_from,
                            available_to: response.data.available_to
                        })
                        commit('setCourseUsers', response.data.users)
                        commit('setCourseCategories', response.data.categories)
                        commit('setCourseFiles', response.data.course_files)
                    }
                )

            commit('loading', false)
        },

        async getCourseCategories({ commit }, courseId) {
            commit('loading', true)

            await axios.get('/categories/course/' + courseId)
                .then(
                    response => {
                        commit('setCourseCategories', response.data.categories)
                    }
                )

            commit('loading', false)
        },

        async createOrEditCourse({ commit }, course) {
            commit('loading', true)
            commit('clearValidationErrors')

            if(course.available_to === '')
                delete course.available_to

            return await axios({
                method: !course.id ? 'post' : 'patch',
                url: !course.id ? '/courses/create' : '/courses/edit/' + course.id,
                data: course
            })
                .then(
                    (response) => {
                        commit('setCourse', course)
                        commit('clearValidationErrors')
                        commit('loading', false)

                        return response
                    }
                )
                .catch(
                    (error) => {
                        if(error.response.status === 422)
                            commit('setValidationErrors', error.response.data.errors)

                        commit('loading', false)
                        throw error
                }
            )
        },

        async deleteCourse({ commit }, courseId) {
            commit('loading', true)

            await axios.delete('/courses/delete/' + courseId)
                .then(() => {
                    commit('loading', false)
                    commit('resetCourse')
                    router.push({name:'CoursesUser'})
                }).catch(() => {
                    commit('loading', false)
                })
        },

        async deleteCourseFile({ commit, state }, courseFileId) {
            commit('loading', true)

            await axios.post('/delete-resource/' + courseFileId, {
                file_type: 'course_file'
            })
                .then(
                    () => {
                        commit('setCourseFiles', state.courseFiles.filter(file => file.id !== courseFileId))
                    }
                )

            commit('loading', false)
        },

        async deleteCourseCategory({ commit, state }, categoryId) {
            commit('loading', true)

            await axios.delete('/categories/delete/' + categoryId)
                .then(
                    () => {
                        commit('setCourseCategories', state.courseCategories.filter(category => category.id !== categoryId))
                    }
                )
                .catch(
                    (error) => {
                        return Promise.reject(error);
                    }
                )

            commit('loading', false)
        },

        async createOrEditCategory({ dispatch, commit }, { courseId, category }) {
            commit('loading', true)

            await axios({
                method: category.id === -1 ? 'post' : 'patch',
                url: category.id === -1 ? '/categories/create/course/' + courseId : '/categories/edit/' + category.id,
                data:
                    {
                        name: category.name
                    }
            })
                .then(
                    () => {
                        dispatch('getCourseCategories', courseId)
                        commit('clearValidationErrors')
                    }
                )
                .catch(
                    (error) => {
                        if(error.response.status === 422)
                            commit('setValidationErrors', error.response.data.errors)

                        throw error
                    }
                )

            commit('loading', false)
        },

        async assignToCourse({ dispatch, commit }, { courseId, participants }) {
            commit('loading', true)

            await axios.post('/courses/assign/' + courseId, {
                assignedUsers: participants.map(user => user.id)
            })
            .then( () => {
                commit('setCourseUsers', participants)
                commit('clearValidationErrors')
                commit('loading', false)
            })
            .catch(
                (error) => {
                    commit('setValidationErrors', error.response.data.errors)
                    commit('loading', false)
                    throw error
                }
            )
        },
    },

    mutations: {
        loading(state, newLoadingStatus) {
            state.loading = newLoadingStatus
        },

        clearValidationErrors(state) {
            state.validationErrors = []
        },

        setValidationErrors(state, validationErrors) {
            state.validationErrors = validationErrors
        },

        setCourse(state, course) {
            state.course = course
        },

        setCourseUsers(state, users) {
            state.courseUsers = users
        },

        setCourseCategories(state, courseCategories) {
            state.courseCategories = courseCategories
        },

        setCourseCategoryTasks(state, tasks) {
            state.courseCategoryTasks = tasks
        },

        setCourseFiles(state, courseFiles) {
            state.courseFiles = courseFiles
        },

        resetCourse(state) {
            state.course = {}
            state.courseCategories = []
            state.courseUsers = []
            state.courseFiles = []
        }
    },

    getters: {
        getFormattedDate: state => date => {
            return (date !== null) ? dayjs(date).format('L LT') : ''
        },

        getISODate: state => date => {
            return (date !== null) ? dayjs(date).format('YYYY-MM-DDTHH:mm') : ''
        },

        getRelativeTime: state => date => {
            return (date !== null) ? dayjs().to(dayjs(date)) : ''
        },

        getCategoriesCount(state) {
            return state.courseCategories && state.courseCategories.length
        },

        getCourseUsersCount(state) {
            return state.courseUsers && state.courseUsers.length
        },

        getDescriptionLength(state) {
            return state.course.description && state.course.description.length
        },

        getCourseFilesCount(state) {
            return state.courseFiles && state.courseFiles.length
        },

        isEnded(state) {
            return state.course.available_to !== null
                   && dayjs() > dayjs(state.course.available_to)
        },

        getTeachers(state) {
            return state.courseUsers
                && state.courseUsers.filter(user => user.account_role === 'teacher')
        }
    }

}

export default course
