import { createRouter, createWebHistory } from 'vue-router'
import LoginForm from "../views/Authenticate/LoginForm"
import CourseView from "../views/Course/CourseView"
import UserMarks from "../views/Mark/UserMarks"
import UserForm from "../views/User/UserForm"
import ChangePassword from "../views/User/ChangePassword"
import StatisticsForm from "../views/Statistics/StatisticsForm"
import CourseList from "../views/Course/CourseList"
import TaskMarks from "../views/Mark/TaskMarks"
import Error404 from "../views/Error404"
import AdminUsers from "../views/Admin/AdminUsers"
import AdminCourses from "../views/Admin/AdminCourses"
import AdminTasks from "../views/Admin/AdminTasks"
import TaskList from "../views/Task/TaskList"
import TaskForm from "../views/Task/TaskForm"
import TaskView from "../views/Task/TaskView"
import TaskStudentUploads from "../views/Task/TaskStudentUploads"
import Dashboard from "../views/Dashboard"
import PassRoute from "../components/PassRoute"
import CourseForm from "../views/Course/CourseForm"
import login from "../store/modules/login"
import UserView from "../views/User/UserView"
import { loadLanguageAsync } from 'laravel-vue-i18n'
import locale from "../store/modules/locale"
import dayjs from "dayjs"
import localizedFormat from "dayjs/plugin/localizedFormat"
import pl from 'dayjs/locale/pl'
import relativeTime from 'dayjs/plugin/relativeTime'

const routes = [
    {
        path: '/login',
        name: 'Login',
        component: LoginForm,
        meta: {
            title: 'Login'
        }
    },

    {
        path: '/',
        name: 'Dashboard',
        component: Dashboard,
        meta: {
            title: 'Dashboard'
        }
    },

    /* USERS */
    {
        path: '/users',
        name: 'Users',
        component: PassRoute,
        children: [
            { path: 'show/:id', name: 'UsersView', component: UserView },
            { path: 'create', name: 'UsersCreate', component: UserForm, meta: { role: ['admin'] } },
            { path: 'edit/:id', name: 'UsersEdit', component: UserForm, meta: { role: ['admin'] } },
        ]
    },

    /* SELF PROFILE */
    {
        path: '/profile',
        name: 'Profile',
        component: PassRoute,
        children: [
            { path: '', name: 'ProfileUser', component: UserView },
            { path: 'change-password', name: 'ProfileChangePassword', component: ChangePassword }
        ]
    },

    /* STATISTICS */
    {
        path: '/statistics',
        name: 'Statistics',
        component: StatisticsForm,
        meta: {
            role: ['teacher']
        }
    },

    /* COURSES */
    {
        path: '/courses',
        name: 'Courses',
        component: PassRoute,
        children: [
            { path: '', name: 'CoursesUser', component: CourseList },
            { path: 'create', name: 'CoursesCreate', component: CourseForm, meta: { role: ['teacher', 'admin'] } },
            { path: 'edit/:id', name: 'CoursesEdit', component: CourseForm, meta: { role: ['teacher', 'admin'] } },
            { path: 'show/:id', name: 'CoursesView', component: CourseView, meta: { title: 'Course details' } }
        ]
    },

    /* MARKS */
    {
        path: '/marks',
        name: 'Marks',
        component: PassRoute,
        children: [
            { path: '', name: 'MarksUser', component: UserMarks, meta: { role: ['student'] } },
            { path: 'task/:id', name: 'MarksTask', component: TaskMarks, meta: { role: ['teacher', 'admin'] } }
        ]
    },

    /* ADMIN */
    {
        path: '/admin',
        name: 'Admin',
        component: PassRoute,
        children: [
            { path: 'users', name: 'AdminUsers', component: AdminUsers, meta: { role: ['admin'] } },
            { path: 'courses', name: 'AdminCourses', component: AdminCourses, meta: { role: ['admin'] } },
            { path: 'tasks', name: 'AdminTasks', component: AdminTasks, meta: { role: ['admin'] } }
        ]
    },

    /* TASKS */
    {
        path: '/tasks',
        name: 'Tasks',
        component: PassRoute,
        children: [
            { path: '', name: 'TasksUser', component: TaskList },
            { path: 'category/:id', name: 'TasksInCategory', component: TaskList },
            { path: 'create', name: 'TasksCreate', component: TaskForm, meta: { role: ['teacher', 'admin'] } },
            { path: 'edit/:id', name: 'TasksEdit', component: TaskForm, meta: { role: ['teacher', 'admin'] } },
            { path: 'show/:id', name: 'TasksView', component: TaskView },
            { path: 'student-uploads/:id', name: 'TaskStudentUploads', component: TaskStudentUploads, meta: { role: ['teacher', 'admin'] } }
        ]
    },

    /* ERROR 404 */
    {
        path: '/:pathMatch(.*)*',
        name: 'Error404',
        component: Error404
    }
]

const router = createRouter({
    history: createWebHistory(process.env.MIX_BASE_URL),
    routes
})

// pages guard
router.beforeEach((to, from, next) => {
    loadLanguageAsync(locale.state.locale).then(
        () => {
            const publicPages = ['/login']
            const authRequired = !publicPages.includes(to.path)

            const loggedIn = login.state.token

            // check account role
            const account_role = login.state.user && login.state.user.account_role
            if(to.meta.role && !to.meta.role.includes(account_role)) {
                return next('/')
            }

            if (authRequired && !loggedIn)
                return next('/login')

            next();
        }
    )
})

// title manager
const defaultTitle = 'Learnin'
router.afterEach(() => {
    document.title = defaultTitle

    dayjs.extend(localizedFormat)
    dayjs.extend(relativeTime)
    dayjs.locale(locale.state.locale)

})

export default router
