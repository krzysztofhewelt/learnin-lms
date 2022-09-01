<template>
    <div class="flex relative w-full h-fit flex-col break-words rounded-lg bg-white shadow-xl">
        <form @submit.prevent="handleSubmit">
            <div class="p-6">
                <h1 class="my-auto block text-xl font-bold mb-4">{{ $t('user.change_password_label') }}</h1>

                    <BasePassword
                        :label="$t('user.old_password')"
                        required="required"
                        id="old_password"
                        v-model="oldPassword"
                        :validation-errors="errors.current_password"
                    />

                    <BasePassword
                        :label="$t('user.new_password')"
                        required="required"
                        id="new_password"
                        v-model="newPassword"
                        :validation-errors="errors.new_password"
                    />

            </div>
            <div class="px-4 py-3 bg-gray-100 text-right rounded-b-lg">
               <ButtonSubmit :loading="loading"/>
            </div>
        </form>
    </div>
</template>

<script>
import BaseInputGroup from "../../components/BaseInputGroup";
import BasePassword from "../../components/BasePassword";
import {useToast} from "vue-toastification";
import ButtonSubmit from "../../components/ButtonSubmit";
import router from "../../router";
export default {
    name: "ChangePassword",
    components: {ButtonSubmit, BasePassword, BaseInputGroup},

    data() {
        return {
            loading: false,
            toast: useToast(),
            oldPassword: '',
            newPassword: '',
            errors: {}
        }
    },

    methods: {
        async handleSubmit() {
            this.loading = true
            this.errors = {}

            await axios.post('/change-password', {
                current_password: this.oldPassword,
                new_password: this.newPassword
            })
            .then(() => {
                this.errors = {}
                this.toast.success(this.$t('user.password_changed'))
                this.loading = false
                router.push({name:'Profile'})
            })
            .catch(
                (error) => {
                    if(error.response.status === 422)
                        this.errors = error.response.data.errors

                    this.toast.error(this.$t('general.validation_errors'))

                    this.loading = false
                    throw error
            })


        }
    }
}
</script>
