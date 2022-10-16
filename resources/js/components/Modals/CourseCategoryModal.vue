<template>
    <Modal v-model="showModal" :title="setModalTitle" wrapper-class="modal-wrapper">
        <div class="m-2">
            <form @submit.prevent="handleSubmit">
                <div class="w-full">
                    <BaseInputGroup
                        id="name"
                        :label="$t('course.course_category')"
                        type="text"
                        :required="true"
                        v-model="category.name"
                        :validation-errors="validationErrors.name"
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
    name: "CourseCategoryModal",

    components: {
        BaseInputGroup,
        'Modal': VueModal
    },

    props: {
        showModal: {
            type: Boolean,
            default: false
        },

        category: {
            type: Object,
            default: ''
        },

        courseId: {
            type: Number,
            default: ''
        },

        action: {
            required: true,
            validator: function(arr) {
                return arr === 'create' || arr === 'edit'
            }
        }
    },

    computed: {
        ...mapState('course', ['validationErrors']),

        setModalTitle() {
            return this.action === 'create' ? this.$t('course.add_category_title') : this.$t('course.edit_category_title')
        }
    },

    methods: {
        ...mapActions('course', ['createOrEditCategory']),

        handleSubmit() {
            const toast = useToast()

            this.createOrEditCategory({ courseId: this.courseId, category: { id: this.category.id, name: this.category.name } })
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
