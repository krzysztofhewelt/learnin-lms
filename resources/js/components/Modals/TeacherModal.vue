<template>
    <Modal v-model="showModal" :title="$t('user.teacher_informations_edit')" wrapper-class="modal-wrapper">
        <div class="m-2">
            <form @submit.prevent="handleSubmit">
                <div class="w-full mb-2">
                    <BaseInputGroup
                        id="scien_degree"
                        :label="$t('user.scientific_degree')"
                        type="text"
                        :required="true"
                        v-model="teacher.scien_degree"
                        :validation-errors="validationErrors.scien_degree"
                    />
                </div>
                <div class="w-full mb-2">
                    <BaseInputGroup
                        id="business_email"
                        :label="$t('user.contact_email')"
                        type="text"
                        :required="true"
                        v-model="teacher.business_email"
                        :validation-errors="validationErrors.business_email"
                    />
                </div>
                <div class="w-full mb-2">
                    <BaseInputGroup
                        id="contact_number"
                        :label="$t('user.contact_number')"
                        type="text"
                        :required="false"
                        v-model="teacher.contact_number"
                        :validation-errors="validationErrors.contact_number"
                    />
                </div>
                <div class="w-full mb-2">
                    <BaseInputGroup
                        id="room"
                        :label="$t('user.room')"
                        type="text"
                        :required="false"
                        v-model="teacher.room"
                        :validation-errors="validationErrors.room"
                    />
                </div>
                <div class="w-full mb-2">
                    <BaseInputGroup
                        id="consultation_hours"
                        :label="$t('user.consultation_hours')"
                        type="text"
                        :required="false"
                        v-model="teacher.consultation_hours"
                        :validation-errors="validationErrors.consultation_hours"
                    />
                </div>
            </form>
        </div>
        <div class="row mt-3">
            <div class="col-sm-12">
                <div class="float-right">
                    <button class="manage_btn" type="button" @click.prevent="showModal = false">{{ $t('general.cancel') }}</button>
                    <button class="submit_btn ml-3" type="button" @click="handleSubmit">{{ $t('general.save') }}</button>
                </div>
            </div>
        </div>
    </Modal>
</template>

<script>
import VueModal from "@kouts/vue-modal"
import '@kouts/vue-modal/dist/vue-modal.css'
import BaseInputGroup from "../BaseInputGroup"
import {mapActions, mapState} from "vuex"
import {useToast} from "vue-toastification";

export default {
    name: "TeacherModal",
    components: {
        'Modal': VueModal,
        BaseInputGroup
    },

    props: {
        showModal: {
            type: Boolean,
            default: false
        },

        userId: {
            type: Number,
            required: true,
        },

        teacher: {
            type: Object,
            required: true,
        }
    },

    computed: {
        ...mapState('user', ['loading', 'validationErrors'])
    },

    methods: {
        ...mapActions('user', ['updateTeacherInformations']),

        handleSubmit() {
            const toast = useToast()

            this.updateTeacherInformations({ userId: this.userId, teacher: this.teacher })
                .then(() => {
                    toast.success(this.$t('general.saved_successfully'))
                    this.$emit('closing')
                })
                .catch(() => {
                    toast.error(this.$t('general.validation_errors'))
                })
        }
    }
}
</script>

<style>
.modal-wrapper {
    display: flex;
    align-items: center;
}
.modal-wrapper .vm {
    top: auto;
}
</style>
