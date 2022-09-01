import axios from "axios"
import router from "../router"
import {useToast} from "vue-toastification"
import store from "../store"
import login from "../store/modules/login"

const toast = useToast()

export default function setup() {

    axios.defaults.baseURL = 'http://localhost/api'
    axios.defaults.headers.common['Authorization'] = "Bearer " + login.state.token
    axios.defaults.headers.post['Content-Type'] = 'application/json'


    axios.interceptors.response.use((response) => {
        return response;
    }, (error) => {
        if(error.response.status === 401 && error.response.config.url !== '/login' && error.response.config.url !== '/refresh') {

            return store.dispatch('login/refresh')
                .then(
                    () => {
                        error.config.headers = { Authorization: `Bearer ${login.state.token}` }

                        return axios.request(error.config).then(
                            () => {
                                return router.go(0)
                            })
                            .catch(() => {
                                return router.push('/login')
                            })
                    }
                )
                .catch(
                    () => {
                        return router.push('/login')
                    }
                )
        }

        if(error.response.status === 403) {
            toast.error("error")
            return router.push("/")
        }

        if(error.response.status === 404) {
            toast.error("This resource does not exists!")
            return router.push("/")
        }

        if(error.response.status === 500) {
            toast.error("Bad request!")
            return router.push("/")
        }

        return Promise.reject(error)
    })

}
