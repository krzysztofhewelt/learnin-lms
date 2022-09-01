<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-r from-rose-100 to-teal-100">
        <div class="w-full sm:max-w-md px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <AppLogo class="w-9/12 mx-auto pb-4"></AppLogo>
            <span class="block text-red-400 font-bold text-center text-xl mb-3" v-for="error in accountErrors">
                {{ error }}
            </span>
            <form @submit.prevent="logIn">
                <!-- Email Address -->
                <InputWithIconGroup id="email"
                                    :label="$t('auth.email')"
                                    v-model="email"
                                    :placeholder="$t('auth.type_email')"
                                    :required="true"
                                    :validation-errors="validationErrors.email">
                    <SimpleUser class="h-5 w-5" />
                </InputWithIconGroup>

                <!-- password -->
                <InputWithIconGroup type="password"
                                    id="password"
                                    :label="$t('auth.password')"
                                    v-model="password"
                                    :placeholder="$t('auth.type_password')"
                                    :required="true"
                                    :validation-errors="validationErrors.password">
                    <Password class="h-5 w-5" />
                </InputWithIconGroup>

                <div class="flex items-center justify-end mt-4">
                    <button type="submit" class="w-screen bg-gradient-to-r from-purple-400 via-pink-500 to-red-500 items-center px-4 py-2 bg-gray-800 border border-transparent rounded-3xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 text-center" :disabled="loading">
                        {{ $t('auth.sign_in') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import InputWithIconGroup from "../../components/BaseInputWithIconGroup"
import AppLogo from "../../components/AppLogo"
import Password from "../../components/Icons/Password"
import {mapActions, mapGetters, mapState} from "vuex"
import SimpleUser from "../../components/Icons/SimpleUser"
import {useToast} from "vue-toastification";
import router from "../../router";

export default {
    name: "LoginForm",
    components: {SimpleUser, Password, AppLogo, InputWithIconGroup},

    data() {
        return {
            email: '',
            password: ''
        }
    },

    computed: {
        ...mapState('login', ['loading', 'validationErrors', 'accountErrors'])
    },

    created() {
       this.logout()
    },

    methods: {
        ...mapActions('login', ['logout', 'login']),

        logIn() {
            const toast = useToast()

            this.login({ email: this.email, password: this.password })
                .then(() => {
                    toast.success(this.$t('auth.logged_successfully'))
                    router.push('/')
                })
        }
    },
}
</script>
